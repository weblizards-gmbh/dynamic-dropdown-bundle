/**
 *
 * @copyright  Copyright (c) 2016 Weblizards GmbH (http://www.weblizards.de)
 * @author     Thomas Keil <thomas@weblizards.de>
 * @license    GPLv3
 */

pimcore.registerNS("pimcore.object.tags.dynamicDropdown");
pimcore.object.tags.dynamicDropdown = Class.create(pimcore.object.tags.select, {

    type: "dynamicDropdown",

    getGridColumnEditor:function (field) {
        if(field.layout.noteditable) {
            return null;
        }
        this.options_store = new Ext.data.JsonStore({
            proxy: {
                type: 'ajax',
                url: '/admin/dynamicdropdownbundle/tag/options',
                extraParams: {
                    source_parent: field.layout.source_parentid,
                    source_methodname: field.layout.source_methodname,
                    source_classname: field.layout.source_classname,
                    source_recursive: field.layout.source_recursive,
                    current_language: pimcore.settings.language,
                    sort_by: field.layout.sort_by
                },
                reader: {
                    type: 'json',
                    rootProperty: 'options',
                    successProperty: 'success',
                    messageProperty: 'message'
                }
            },
            fields: ["key", "value"],
            listeners: {
                load: function(store, records, success, operation) {
                    console.debug(operation);
                }.bind(this)
            },
            autoLoad: true
        });

        var options = {
            store: this.options_store,
            triggerAction: "all",
            editable: false,
            mode: "local",
            valueField: 'value',
            displayField: 'key'
        };


        return new Ext.form.ComboBox(options);
    },

    getGridColumnConfig:function (field) {
        var renderer = function (key, value, metaData, record) {

            this.applyPermissionStyle(key, value, metaData, record);

            if (record.data.inheritedFields[key] && record.data.inheritedFields[key].inherited == true) {
                try {
                    metaData.tdCls += " grid_value_inherited";
                } catch (e) {
                    console.log(e);
                }
            }

            return value;

        }.bind(this, field.key);

        return {header:t(field.label), sortable:true, dataIndex:field.key, renderer:renderer,
            editor:this.getGridColumnEditor(field)};
    },

    getLayoutEdit: function () {

        this.options_store = new Ext.data.JsonStore({
            proxy: {
                type: 'ajax',
                url: '/admin/dynamicdropdownbundle/tag/options',
                extraParams: {
                    source_parent: this.fieldConfig.source_parentid,
                    source_methodname: this.fieldConfig.source_methodname,
                    source_classname: this.fieldConfig.source_classname,
                    source_recursive: this.fieldConfig.source_recursive,
                    current_language: pimcore.settings.language,
                    sort_by: this.fieldConfig.sort_by
                },
                reader: {
                    type: 'json',
                    rootProperty: 'options',
                    successProperty: 'success',
                    messageProperty: 'message'
                }
            },
            fields: ["key", "value"],
            listeners: {
                load: function(store, records, success, operation) {
                    if (!success) {
                        pimcore.helpers.showNotification(t("error"), t("error_loading_options"), "error", operation.getError());
                    }
                }.bind(this)
            },
            autoLoad: true
        });

        var options = {
            name: this.fieldConfig.name,
            triggerAction: "all",
            editable: true,
            typeAhead: true,
            forceSelection: true,
            selectOnFocus: true,
            fieldLabel: '',
            store: this.options_store,
            componentCls: "object_field object_field_type_" + this.type,
            itemCls: "object_field",
            width: 300,
            labelWidth: this.fieldConfig.labelWidth ? this.fieldConfig.labelWidth : 100,
            displayField: "key",
            valueField: "value",
            queryMode: "local",
            autoSelect: false,
            autoLoadOnValue: true,
            listConfig: {
                getInnerTpl: function(displayField) {
                    return '<tpl for="."><tpl if="published == true">{key}<tpl else><div class="x-combo-item-disabled x-item-disabled">{key}</div></tpl></tpl>';
                }
            }
        };

        if (this.fieldConfig.width) {
            options.width = this.fieldConfig.width;
        }

        if (this.data && (typeof this.data.id == "string" || typeof this.data.id == "number")) {
            options.value = this.data.id;
        }

        this.createEmptyButton();

        this.component = new Ext.form.ComboBox(options);

        if (!this.fieldConfig.parked_selectable) {
            this.component.addListener("beforeselect", function (combo, record, index, e) {
                if (!record.data.published) {
                    return false;
                }
            });
        }

        var componentCfg = {
            fieldLabel:this.fieldConfig.title,
            layout: 'hbox',
            items: [
                this.component,
                this.emptyButton
            ],
            componentCls: "object_field object_field_type_" + this.type,
            border: false,
            style: {
                padding: 0
            }
        };

        if (this.fieldConfig.labelWidth) {
            componentCfg.labelWidth = this.fieldConfig.labelWidth;
        }

        this.componentGroup = Ext.create('Ext.form.FieldContainer', componentCfg);

        return this.componentGroup;
    },

    createEmptyButton: function() {
        if (this.getObject()) {
            this.emptyButton = new Ext.Button({
                iconCls: "pimcore_icon_delete",
                cls: 'pimcore_button_transparent',
                tooltip: t("set_to_null"),
                hidden: this.fieldConfig.hideEmptyButton || !this.getObject().data.general.allowInheritance,
                handler: function () {
                    if (this.data !== null) {
                        this.dataChanged = true;
                    }
                    this.component.setValue(null);
                    this.data = {};
                }.bind(this),
                style: "margin-left: 10px; filter:grayscale(100%);",
            });
        }
    }

});
