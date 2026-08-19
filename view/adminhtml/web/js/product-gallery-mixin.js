/**
 * Copyright © Bruno Pelaquin. All rights reserved.
 * https://github.com/https-pelaquin
 * https://www.linkedin.com/in/bruno-pelaquin/
 */

define([
    'jquery',
    'mage/translate'
], function ($) {
    'use strict';

    return function (productGallery) {
        $.widget('mage.productGallery', productGallery, {
            _create: function () {
                this._super();
                this._createSliderInputs();
                this._bindSliderEvents();
                this._syncSliderImages();
            },

            _showDialog: function (imageData) {
                this._super(imageData);
                this._addSliderField(imageData);
            },

            _removeItem: function (event, imageData) {
                imageData.slider_selected = false;
                this._super(event, imageData);
                this._syncSliderImages();
            },

            _resort: function () {
                this._super();
                this._syncSliderImages();
            },

            _createSliderInputs: function () {
                var firstImage = this.options.images[0] || {},
                    formName = this.element.find('[data-form-part]').first().attr('data-form-part') || 'product_form';

                this.sliderCanUseDefault = Boolean(firstImage.slider_can_use_default);
                this.sliderUseDefault = Boolean(firstImage.slider_use_default);
                this.sliderImagesInput = $('<input>', {
                    type: 'hidden',
                    name: 'product[slider_images]',
                    'data-form-part': formName
                }).appendTo(this.element);
                this.sliderUseDefaultInput = $('<input>', {
                    type: 'hidden',
                    name: 'use_default[slider_images]',
                    value: this.sliderUseDefault ? '1' : '0',
                    'data-form-part': formName
                }).appendTo(this.element);
            },

            _bindSliderEvents: function () {
                this.$dialog.on(
                    'change.bpSliderImages',
                    '[data-role=slider-image-selector]',
                    function (event) {
                        var imageData = this.$dialog.data('imageData');

                        imageData.slider_selected = $(event.currentTarget).is(':checked');
                        this._syncSliderImages();
                        this._contentUpdated();
                    }.bind(this)
                );

                this.$dialog.on(
                    'change.bpSliderImagesDefault',
                    '[data-role=slider-images-use-default]',
                    function (event) {
                        this.sliderUseDefault = $(event.currentTarget).is(':checked');
                        this.sliderUseDefaultInput.val(this.sliderUseDefault ? '1' : '0');
                        this.$dialog.find('[data-role=slider-image-selector]').prop('disabled', this.sliderUseDefault);
                        this._contentUpdated();
                    }.bind(this)
                );
            },

            _addSliderField: function (imageData) {
                var fieldId = 'bp-slider-image-' + String(imageData.file_id).replace(/[^a-zA-Z0-9_-]/g, ''),
                    field = $('<div>', {
                        'class': 'admin__field field field-slider-image'
                    }),
                    label = $('<label>', {
                        'class': 'admin__field-label',
                        for: fieldId
                    }).append($('<span>').text($.mage.__('Use in Product Grid Slider'))),
                    control = $('<div>', {
                        'class': 'admin__field-control'
                    }),
                    switcher = $('<div>', {
                        'class': 'admin__actions-switch',
                        'data-role': 'switcher'
                    }),
                    checkbox = $('<input>', {
                        id: fieldId,
                        type: 'checkbox',
                        value: '1',
                        'class': 'admin__actions-switch-checkbox',
                        'data-role': 'slider-image-selector'
                    }).prop({
                        checked: Boolean(imageData.slider_selected),
                        disabled: this.sliderUseDefault
                    }),
                    switchLabel = $('<label>', {
                        'class': 'admin__actions-switch-label',
                        for: fieldId
                    }).append($('<span>', {
                        'class': 'admin__actions-switch-text',
                        'data-text-on': $.mage.__('Yes'),
                        'data-text-off': $.mage.__('No')
                    }));

                switcher.append(checkbox, switchLabel);
                control.append(switcher);

                if (this.sliderCanUseDefault) {
                    control.append(this._createUseDefaultField(fieldId));
                }

                field.append(label, control);
                this.$dialog.find('.fieldset-image-panel').append(field);
            },

            _createUseDefaultField: function (fieldId) {
                var service = $('<div>', {
                        'class': 'admin__field-service'
                    }),
                    checkboxId = fieldId + '-use-default',
                    checkbox = $('<input>', {
                        id: checkboxId,
                        type: 'checkbox',
                        value: '1',
                        'class': 'admin__control-checkbox',
                        'data-role': 'slider-images-use-default'
                    }).prop('checked', this.sliderUseDefault),
                    label = $('<label>', {
                        'class': 'admin__field-label',
                        for: checkboxId
                    }).text($.mage.__('Use Default Value'));

                return service.append(checkbox, label);
            },

            _syncSliderImages: function () {
                var sliderImages = [];

                this.element.find(this.options.imageSelector).each(function () {
                    var imageContainer = $(this),
                        imageData = imageContainer.data('imageData'),
                        roleLabels = imageContainer.find('[data-role=roles-labels]'),
                        roleLabel = roleLabels.find('[data-role=slider-role-label]');

                    if (!roleLabel.length) {
                        roleLabel = $('<li>', {
                            'class': 'item-role item-role-slider-images',
                            'data-role': 'slider-role-label'
                        }).text($.mage.__('Product Grid Slider')).appendTo(roleLabels);
                    }

                    roleLabel.toggle(Boolean(imageData.slider_selected));

                    if (imageData.slider_selected && !imageContainer.hasClass('removed')) {
                        sliderImages.push(imageData.file);
                    }
                });

                this.sliderImagesInput.val(JSON.stringify(sliderImages));
            }
        });

        return $.mage.productGallery;
    };
});
