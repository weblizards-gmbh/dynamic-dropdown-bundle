<?php

namespace Weblizards\DynamicDropdownBundle\Model\DataObject\ClassDefinition\Data;

use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Service;
use Pimcore\Model\DataObject;

/**
 * @category   Pimcore
 * @copyright  Copyright (c) 2016 Weblizards GmbH (http://www.weblizards.de)
 * @author     Thomas Keil <thomas@weblizards.de>
 * @license    GPLv3
 */
class Superboxselect extends MultiselectBase {
    /**
     * Static type of this element
     *
     * @var string
     */
    public $fieldtype = "superboxselect";


    /**
     * @see DataObject\ClassDefinition\Data::getDataForEditmode
     * @param array $data
     * @param null|AbstractObject $object
     * @param mixed $params
     * @return array
     */
    public function getDataFromEditmode($data, $object = null, $params = []) {
        //if not set, return null
        if ($data === null or $data === FALSE) {
            return null;
        }

        $elements = array();
        if (is_array($data) && count($data) > 0) {
              foreach ($data as $id) {
                    $elements[] = Service::getElementById("object", $id);
              }
        }
        //must return array if data shall be set
        return $elements;
    }

    /**
     * @see DataObject\ClassDefinition\Data::getDataForEditmode
     * @param array $data
     * @param null|AbstractObject $object
     * @param mixed $params
     * @return array|bool
     */
    public function getDataForEditmode($data, $object = null, $params = []) {
        $return = array();

        if (is_array($data) && count($data) > 0) {
              foreach ($data as $element) {
                  /** @var AbstractObject $element */
                  $return[] = $element->getId();
              }
              return implode(",", $return);
        }

        return false;
    }


}
