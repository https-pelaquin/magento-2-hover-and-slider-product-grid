/**
 * Copyright © Bruno Pelaquin. All rights reserved.
 * https://github.com/https-pelaquin
 * https://www.linkedin.com/in/bruno-pelaquin/
 */

define([
    'jquery',
    'mage/translate',
    'slick'
], function ($) {
    'use strict';

    return function (config, element) {
        var slider = $(element),
            productLink = slider.closest('a'),
            controls = $('<div>', {
                'class': 'bp-product-slider-controls',
                'aria-label': $.mage.__('Product image gallery controls'),
                role: 'group'
            });

        if (slider.hasClass('slick-initialized')) {
            return;
        }

        if (productLink.length) {
            controls.insertAfter(productLink);
        } else {
            controls.insertAfter(slider);
        }

        slider.slick({
            appendArrows: controls,
            dots: false,
            infinite: true,
            lazyLoad: 'ondemand',
            slidesToShow: 1,
            slidesToScroll: 1,
            speed: 300
        });

        controls.find('.slick-prev').attr('aria-label', $.mage.__('Previous image'));
        controls.find('.slick-next').attr('aria-label', $.mage.__('Next image'));

        slider.on('destroy.bpProductSlider', function () {
            controls.remove();
        });
    };
});
