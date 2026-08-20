/*
 * Copyright © Bruno Pelaquin. All rights reserved.
 */

'use strict';

const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const {test} = require('node:test');

const source = fs.readFileSync(
    path.resolve(__dirname, '../../../view/frontend/web/js/product-slider.js'),
    'utf8'
);

test('keeps the product link while placing slider controls outside it', () => {
    assert.match(source, /appendArrows: controls/);
    assert.match(source, /controls\.insertAfter\(productLink\)/);
    assert.doesNotMatch(source, /removeAttr\('href'\)/);
    assert.doesNotMatch(source, /attr\('tabindex'/);
});

test('uses on-demand loading for secondary slider images', () => {
    assert.match(source, /lazyLoad: 'ondemand'/);
});
