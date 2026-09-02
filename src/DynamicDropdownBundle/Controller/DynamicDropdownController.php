<?php

namespace Weblizards\DynamicDropdownBundle\Controller;

use Pimcore\Controller\FrontendController;
use Pimcore\Model\DataObject\Concrete;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Pimcore\Model\DataObject;
use Pimcore\Tool;

class DynamicDropdownController extends FrontendController
{
    private string $separator = ' - ';

    /**
     * @Route("/tag/options", name="dynamicdropdownbundle_tag_options")
     *
     * Produces the json to feed the dynamic dropdown
     * Used by pimcore.object.tags.dynamicDropdown
     */
    public function optionsAction(Request $request): JsonResponse
    {

        $parentFolderPath = '/' . $this->filter($request->get('source_parent'));
        $sort = $request->get('sort_by');
        $options = [];

        if ($parentFolderPath) {
            // remove trailing slash
            if ($parentFolderPath != '/') {
                $parentFolderPath = rtrim($parentFolderPath, '/ ');
            }

            // correct wrong path (root-node problem)
            $parentFolderPath = str_replace('//', '/', $parentFolderPath);

            $folder = DataObject\Folder::getByPath($parentFolderPath);

            if ($folder) {
                $options = $this->walkPath($request, $folder);
            } else {
                $message = 'The folder submitted could not be found: "' . $request->get('source_parent') . '" (' . $parentFolderPath . ')';
                \Pimcore\Logger::crit($message);
                return new JsonResponse([
                    'success' => false,
                    'message' => $message,
                    "options" => $options
                ]);
            }
        } else {
            $message = 'The folder submitted for source_parent is not valid: "' . $request->get('source_parent') . '"';
            \Pimcore\Logger::warning($message);
            return new JsonResponse([
                'success' => false,
                'message' => $message,
                "options" => $options
            ]);


        }

        usort($options, function ($a, $b) use ($sort) {
            $field = 'value';

            if ($sort == 'byvalue') {
                $field = 'key';
            }

            if ($a[$field] == $b[$field]) {
                return 0;
            }

            return $a[$field] < $b[$field] ? 0 : 1;
        });


        return new JsonResponse([
            'success' => true,
            "options" => $options
        ]);
    }

    private function walkPath(Request $request, ?DataObject\AbstractObject $folder, array $options = [], string $path = ''): array
    {
        if ($folder) {
            $source = $request->get('source_methodname');

            $objectClass = 'Pimcore\Model\DataObject\\' . ucfirst($request->get('source_classname'));

            $usesI18n = false;
            $children = $folder->getChildren();
            foreach ($children as $i18nProbeChild) {
                if ($i18nProbeChild instanceof Concrete) {
                    $usesI18n = $this->isUsingI18n($i18nProbeChild, $source);
                    break;
                }
            }

            $currentLanguage = $request->get('current_language');

            if (!Tool::isValidLanguage($currentLanguage)) {
                $currentLanguage = Tool::getDefaultLanguage();
                if (is_null($currentLanguage)) {
                    $usesI18n = false;
                }
            }

            foreach ($children as $child) {
                switch (true) {

                    case $child instanceof DataObject\Folder:
                        $key = $child->getProperty('Taglabel') != '' ? $child->getProperty('Taglabel') : $child->getKey();
                        if ($request->get('source_recursive') == 'true') {
                            $options = $this->walkPath($request, $child, $options, $path . $this->separator . $key);
                        }
                        break;

                    case $child instanceof $objectClass:
                        /**
                         * @var Concrete $child
                         */
                        $key = $usesI18n ? $child->$source($currentLanguage) : $child->$source();
                        $options[] = array(
                            'value' => $child->getId(),
                            'key' => ltrim($path . $this->separator . $key, $this->separator),
                            'published' => $child->getPublished()
                        );
                        if ($request->get('source_recursive') == 'true') {
                            $options = $this->walkPath($request, $child, $options, $path . $this->separator . $key);
                        }
                        break;
                }
            }
        }

        return $options;
    }

    /**
     * @Route("/tag/methods", name="dynamicdropdownbundle_tag_methods")
     *
     * Produces the json for the "available methods" dropdown in the backend.
     * used by pimcore.object.classes.data.dynamicDropdown
     */
    public function methodsAction(Request $request): JsonResponse
    {
        $methods = [];

        $className = $this->filter($request->get('classname'));
        if (!empty($className)) {
            $classMethods = self::getModelClassMethods('\Pimcore\Model\DataObject\\' . ucfirst($className));
            foreach ($classMethods as $methodName) {
                if (str_starts_with($methodName, 'get')) {
                    $methods[] = [
                        'value' => $methodName,
                        'key' => $methodName
                    ];
                }
            }
        }
        return $this->json($methods);
    }

    /**
     * Gets the model's class methods excluding Pimcore's DataObject\Concrete
     */
    private static function getModelClassMethods(string $class): array
    {
        if (($classMethods = get_class_methods($class)) !== null) {
            $parentClassMethods = get_class_methods(Concrete::class);

            return array_diff($classMethods, $parentClassMethods);
        }

        return [];
    }

    private function isUsingI18n(Concrete $object, string $method): bool
    {
        $classDefinition = $object->getClass();
        $definitionFile = $classDefinition->getDefinitionFile();

        if (!is_file($definitionFile)) {
            return false;
        }

        $tree = include $definitionFile;
        $definition = $this->parseTree($tree, array());

		return array_key_exists($method, $definition) ? $definition[$method] : false;
    }

    /**
     * @param DataObject\ClassDefinition\Layout|DataObject\ClassDefinition\Data\Localizedfields $tree
     */
    private function parseTree(mixed $tree, array $definition): array
    {
        if ($tree instanceof DataObject\ClassDefinition\Layout || $tree instanceof DataObject\ClassDefinition\Data\Localizedfields) { // Did I forget something?
            $children = $tree->getChildren();
            foreach ($children as $child) {
                $definition["get" . ucfirst($child->name)] = $tree->fieldtype == "localizedfields";
                $definition = $this->parseTree($child, $definition);
            }
        }

        return $definition;
    }

    private function filter(array|string $string): array|string|null
    {
        return preg_replace('@[^a-zA-ZÄÖÜäöü0-9_ /-]@u', '', $string);
    }
}
