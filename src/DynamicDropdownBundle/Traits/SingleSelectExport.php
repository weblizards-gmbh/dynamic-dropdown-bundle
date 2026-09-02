<?php
/**
 * Created by Thomas Keil - Weblizards GmbH.
 * User: Thomas Keil
 * Email: thomas@weblizards.de
 *
 * Date: 08.11.18
 * Time: 11:15
 *
 * Dieser Quellcode ist geistiges Eigentum der Weblizards GmbH
 * und darf ohne vorheriges schriftliches Einverständnis nicht
 * vervielfältigt werden.
 *
 */
namespace Weblizards\DynamicDropdownBundle\Traits;

use Exception;
use Pimcore\Model\Element\Service;
use Pimcore\Model\DataObject;
use Pimcore\Model\Element\ElementInterface;

trait SingleSelectExport
{
    /**
     * @throws Exception
     */
    public function getFromCsvImport(string $importValue, ?DataObject\AbstractObject $object = null, array $params = []): ?ElementInterface
    {
        $parts = explode(':', $importValue);
        $path = $parts[1];

        $value = Service::getElementByPath('object', $path);

        if (!$value) {
            throw new Exception("Value not found");
        }

        return $value;
    }

    /**
     * Converts object data to a simple string value or CSV Export
     *
     * @abstract
     */
    public function getForCsvExport(DataObject\AbstractObject $object, array $params = []): string
    {
        $data = $this->getDataForGrid($object);

        return $data . ':' . $object->getFullPath();
    }
}