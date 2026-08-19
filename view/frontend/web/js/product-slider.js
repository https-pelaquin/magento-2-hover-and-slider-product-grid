/**
 * Copyright © Bruno Pelaquin. All rights reserved.
 * https://github.com/https-pelaquin
 * https://www.linkedin.com/in/bruno-pelaquin/
 */

define([
    'jquery',
    'slick'
], function ($) {
    'use strict';

    return function (config, element) {
        var slider = $(element),
            productLink = slider.closest('a');

        if (slider.hasClass('slick-initialized')) {
            return;
        }

        slider.slick({
            dots: false,
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            speed: 300
        });

        productLink
            .attr('aria-disabled', 'true')
            .attr('tabindex', '-1')
            .removeAttr('href');
    };
});
