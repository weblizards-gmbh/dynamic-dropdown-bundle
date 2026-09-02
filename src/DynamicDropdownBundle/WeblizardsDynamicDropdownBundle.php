<?php

namespace Weblizards\DynamicDropdownBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Pimcore\Extension\Bundle\PimcoreBundleAdminClassicInterface;
use Pimcore\Extension\Bundle\Traits\BundleAdminClassicTrait;

class WeblizardsDynamicDropdownBundle extends AbstractPimcoreBundle implements PimcoreBundleAdminClassicInterface
{

    use BundleAdminClassicTrait;

    /**
     * @return array
     */
    public function getCssPaths(): array
    {
        return [
            '/bundles/weblizardsdynamicdropdown/css/admin.css'
        ];
    }

    public function getJsPaths(): array
    {
        return [
            '/bundles/weblizardsdynamicdropdown/js/dynamicdropdown/data/dynamicDropdown.js',
            '/bundles/weblizardsdynamicdropdown/js/dynamicdropdown/tags/dynamicDropdown.js',
            '/bundles/weblizardsdynamicdropdown/js/dynamicdropdown/data/dynamicDropdownMultiple.js',
            '/bundles/weblizardsdynamicdropdown/js/dynamicdropdown/tags/dynamicDropdownMultiple.js',
//            '/bundles/weblizardsdynamicdropdown/js/dynamicdropdown/data/itemselector.js',
//            '/bundles/weblizardsdynamicdropdown/js/dynamicdropdown/tags/itemselector.js',
//            '/bundles/weblizardsdynamicdropdown/js/dynamicdropdown/data/superboxselect.js',
//            '/bundles/weblizardsdynamicdropdown/js/dynamicdropdown/tags/superboxselect.js',
            '/bundles/weblizardsdynamicdropdown/js/pimcore/startup.js'
        ];
    }
}
