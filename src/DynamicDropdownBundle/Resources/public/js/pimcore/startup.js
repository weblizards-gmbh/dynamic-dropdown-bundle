pimcore.registerNS("pimcore.plugin.WeblizardsDynamicDropdownBundle");

pimcore.plugin.WeblizardsDynamicDropdownBundle = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return "pimcore.plugin.WeblizardsDynamicDropdownBundle";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params, broker) {
        // alert("WeblizardsDynamicDropdownBundle ready!");
    }
});

var WeblizardsDynamicDropdownBundlePlugin = new pimcore.plugin.WeblizardsDynamicDropdownBundle();
