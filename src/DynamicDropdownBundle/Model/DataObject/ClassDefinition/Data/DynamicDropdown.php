<?php

namespace Weblizards\DynamicDropdownBundle\Model\DataObject\ClassDefinition\Data;

use Pimcore\Model\Asset;
use Pimcore\Model\Document;
use Pimcore\Model\DataObject;
use Pimcore\Model\DataObject\Service;
use Pimcore\Model\Element;
use Weblizards\DynamicDropdownBundle\Traits\SingleSelectExport;

/**
 * @category   Pimcore
 * @package    DataObject\ClassDefinition\Data
 * @copyright  Copyright (c) Weblizards GmbH (http://www.weblizards.de)
 * @author     Thomas Keil <thomas@weblizards.de>
 * @license    GPLv3
 */
class DynamicDropdown extends DataObject\ClassDefinition\Data\ManyToOneRelation
{

    use SingleSelectExport;

    /**
     * Static type of this element
     */
    public $fieldtype = "dynamicDropdown";

    public $source_parentid;

    public $source_classname;

    public $source_methodname;

    public $source_recursive;

    public $sort_by;

    public function setsource_parentid($id)
    {
        $this->source_parentid = $id;
    }

    public function getsource_parentid()
    {
        return $this->source_parentid;
    }

    public function setsource_classname($classname)
    {
        $this->source_classname = $classname;
    }

    public function getsource_classname()
    {
        return $this->source_classname;
    }

    public function setsource_methodname($methodname)
    {
        $this->source_methodname = $methodname;
    }

    public function getsource_methodname()
    {
        return $this->source_methodname;
    }

    public function setsource_recursive($recursive)
    {
      $this->source_recursive = $recursive;
    }

    public function getsource_recursive()
    {
      return $this->source_recursive;
    }

    public function setsort_by($sort_by)
    {
        $this->sort_by = $sort_by;
    }

    public function getsort_by()
    {
        return $this->sort_by;
    }

	/**
	 * @see Data::getDataForEditmode
	 *
	 * @param ?Element\ElementInterface $data
	 * @param ?DataObject\Concrete $object
	 * @param array|null $params
	 *
	 * @return object|null
	 */
	public function getDataForEditmode($data, $object = null, $params = array()): ?int
    {
        if ($data instanceof Element\ElementInterface) {
            return $data->getId();
        }
        return null;
    }

    /**
     * @see Data::getDataFromEditmode
     * @param mixed $data
     * @param null|DataObject\Concrete $object
     * @param mixed $params
	 *
	 * @return DataObject\Concrete
     */
    public function getDataFromEditmode($data, $object = null, $params = array())
    {
        return DataObject\Concrete::getById($data);
    }


    /**
     * @param $data
     * @param null $object
     * @param array $params
     * @return null|string
     */
    public function getDataForGrid($data, $object = null, $params = [])
    {
		return $this->getDataForEditmode($data, $object, $params);
    }

    public function getDataForQueryResource($data, $object = null, $params = [])
    {
        if (is_int($data)) {
            return [[
                "dest_id" => $data,
                "type" => "object",
                "fieldname" => $this->getName()
            ]];
        }

        return parent::getDataForQueryResource($data, $object, $params);

    }

    /**
     * @param $importValue
     * @param null|DataObject\Concrete $object
     * @param mixed $params
     * @throws \Exception
     *
     * @return mixed|null|Asset|Document|DataObject\Concrete
     */
    public function getFromCsvImport($importValue, $object = null, $params = [])
    {
        $parts = explode(":", $importValue);
        $path = $parts[1];

        $value = Service::getElementByPath("object", $path);

        if (!$value) {
            throw new \Exception("Value not found");
        }

        return $value;
    }

    /**
     * converts object data to a simple string value or CSV Export
     *
     * @abstract
     *
     * @param DataObject\Concrete|DataObject\Localizedfield|DataObject\Objectbrick\Data\AbstractData|DataObject\Fieldcollection\Data\AbstractData $object
     * @throws \Exception
     */
    public function getForCsvExport($object, $params = []): ?string
    {
        $data = $this->getDataFromObjectParam($object, $params);
        if ($data instanceof DataObject\Concrete) {
            $method = $this->getsource_methodname();
            $result = $data->$method();
            return $result . ':' . $data->getFullPath();
        }
        return null;
    }

    public function getDataForSearchIndex($object, $params = []): string
    {
        return '';
    }

    /**
     * @param $object
     * @return null|string
     */
    public function getProcessedData($object) {
        if (is_int($object)) {
            $object = DataObject::getById($object);
        }

        $result = null;

        if ($object instanceof DataObject\Concrete) {
            $method = $this->getsource_methodname();
            $result = $object->$method();
        }
        return $result;
    }

    public function checkValidity($data, $omitMandatoryCheck = false, $params = [])
    {
        return;
    }
}
