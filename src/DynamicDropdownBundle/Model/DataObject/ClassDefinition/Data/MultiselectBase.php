<?php
/**
 * Created by Thomas Keil - Weblizards GmbH.
 * User: Thomas Keil
 * Email: thomas@weblizards.de
 *
 * Date: 08.11.18
 * Time: 11:09
 *
 * Dieser Quellcode ist geistiges Eigentum der Weblizards GmbH
 * und darf ohne vorheriges schriftliches Einverständnis nicht
 * vervielfältigt werden.
 *
 */
namespace Weblizards\DynamicDropdownBundle\Model\DataObject\ClassDefinition\Data;

class MultiselectBase extends \Pimcore\Model\DataObject\ClassDefinition\Data\ManyToManyObjectRelation
{

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

    public function getSort_by()
    {
        return $this->sort_by;
    }

    public function setSort_by($sort_by)
    {
        $this->sort_by = $sort_by;
    }

    /**
     * @return boolean
     */
    public function getObjectsAllowed()
    {
        return true;
    }

}
