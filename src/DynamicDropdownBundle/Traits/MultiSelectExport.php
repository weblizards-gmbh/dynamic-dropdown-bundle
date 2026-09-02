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

use Pimcore\Model\DataObject;
use Pimcore\Model\Element\ElementInterface;

trait MultiSelectExport
{
    /**
     * @param $importValue
     * @param null|DataObject\AbstractObject $object
     * @param mixed $params
     * @throws \Exception
     *
     * @return mixed|null|ElementInterface
     */
    public function getFromCsvImport($importValue, $object = null, $params = [])
    {
        // TODO
        return null;
    }

    /**
     * converts object data to a simple string value or CSV Export
     *
     * @abstract
     *
     * @param DataObject\AbstractObject $object
     * @param array $params
     *
     * @return string
     */
    public function getForCsvExport($object, $params = [])
    {
        // TODO
        return null;
    }
}