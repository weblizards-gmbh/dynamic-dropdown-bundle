<?php

namespace App\Weblizards\DynamicDropdownBundle\Model\DataObject\ClassDefinition\Data;

use Pimcore\Model\DataObject;

/**
 * @category   Pimcore
 * @package    DataObject\ClassDefinition\Data
 * @copyright  Copyright (c) 2016 Weblizards GmbH (http://www.weblizards.de)
 * @author     Thomas Keil <thomas@weblizards.de>
 * @author     Thomas Akkermans <thomas.akkermans@amgate.com>
 * @license    GPLv3
 */
class DynamicDropdownMultiple extends MultiselectBase
{
    /**
     * Static type of this element
     *
     * @var string
     */
    public $fieldtype = "dynamicDropdownMultiple";

    /**
     * @see Data::getDataForEditmode
     *
     * @param array $data
     * @param null|DataObject\AbstractObject $object
     * @param mixed $params
     *
     * @return int[]
     */
    public function getDataForEditmode($data, $object = null, $params = array())
    {
        $objectIds = [];
        if (is_array($data)) {
            foreach ($data as $object) {
                $objectIds[] = $object->getId();
            }
        }
        return $objectIds;
    }

    /**
     * @see Data::getDataFromEditmode
     *
     * @param array $data
     * @param null|DataObject\AbstractObject $object
     * @param array $params
     *
     * @return DataObject\AbstractObject[]|null
     */
    public function getDataFromEditmode($data, $object = null, $params = [])
    {
        if ($data === null || $data === false) {
            return null;
        }

        $objects = [];
        if (is_array($data) && count($data) > 0) {
            foreach ($data as $objectId) {
                if (($o = DataObject\AbstractObject::getById($objectId)) !== null) {
                    $objects[] = $o;
                }
            }
        }

        return $objects;
    }
}
