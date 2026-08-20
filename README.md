# Pelaquin Hover Image Product Grids

[![Magento 2.4.9](https://img.shields.io/badge/Magento-2.4.9-ee672f)](https://experienceleague.adobe.com/docs/commerce-operations/installation-guide/system-requirements.html)
[![PHP 8.3-8.5](https://img.shields.io/badge/PHP-8.3--8.5-777bb4)](https://www.php.net/)
[![MIT License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

An accessible Magento 2 module that enriches native product grids with either a hover image or a lazy-loaded product image slider. It preserves Magento's native product links and only changes known grid image contexts.

## Features

- Two exclusive display modes: hover image or product image slider.
- Store View scoped configuration and product image selections.
- Native product gallery controls for selecting slider images.
- Server-side validation and normalization of selected gallery files.
- Slider images loaded on demand after the primary image.
- Keyboard-accessible controls outside the product link.
- Reduced-motion support and touch-friendly slider behavior.
- Support for category, widget, related, upsell, and cross-sell product grids.
- Brazilian Portuguese translations.

## Compatibility

| Component | Supported version |
| --- | --- |
| Magento Open Source / Adobe Commerce | Magento 2.4.9 component series |
| PHP | 8.3, 8.4, 8.5 |
| JavaScript dependency | Magento Page Builder Slick alias |

The module is disabled by default. Validate other Magento and PHP combinations before advertising them as supported.

## Installation

### Composer

After publishing the package to a Composer repository:

```bash
composer require pelaquin/module-hover-image-product-grids
bin/magento module:enable Pelaquin_HoverImageProductGrids
bin/magento setup:upgrade
bin/magento cache:flush
```

### `app/code`

Copy the module to `app/code/Pelaquin/HoverImageProductGrids`, then run the Magento commands above. In production mode, also compile DI and deploy static content for the required locales.

## Configuration

Open **Stores > Configuration > Pelaquin > Hover Image Product Grids > General**.

| Setting | Description |
| --- | --- |
| Enable Hover Image Product Grids Module | Enables the module for the selected scope. |
| Use Slider Instead of Hover | Uses selected gallery images in a slider instead of the Hover image role. |

Both settings support Default Config, Website, and Store View scopes. The selected slider images are also Store View scoped and can inherit their parent value through Magento's **Use Default Value** control.

## Hover mode

1. Enable the module and keep **Use Slider Instead of Hover** disabled.
2. Edit a product and open **Images and Videos**.
3. Assign a gallery image to the **Hover Image** role.
4. Save the product and flush cache when necessary.

The primary image fades to the selected image only on devices that support hover. Products without a Hover Image keep Magento's default output.

## Slider mode

1. Enable the module and set **Use Slider Instead of Hover** to **Yes**.
2. Edit a product and open **Images and Videos**.
3. Open each gallery image that should be used.
4. Enable **Use in Product Grid Slider** and save the product.

The primary image is always the first slide. Selected images follow the product gallery order, duplicates are removed, and only images that belong to the product gallery are persisted. At least one selected image different from the primary image is required before a slider is rendered.

Slider arrows are rendered outside the product-image link, so customers can still open the product by clicking or focusing the image. Swipe remains available on touch devices.

## Supported grid locations

The module changes only these native Magento image IDs:

- Category product grid.
- Related products.
- Upsell products.
- Cart cross-sell products.
- New Products and Catalog Product List widgets.

It does not alter PDP galleries, list-mode images, cart and checkout item images, wishlist, comparison, or other image contexts.

## Performance and accessibility

- The module uses already-loaded listing data and does not load products per card.
- Secondary slider images use Slick's on-demand lazy loading.
- Images keep native width, height, labels, and escaped attributes.
- Hover transitions respect `prefers-reduced-motion`.
- Slider controls have translated accessible labels and remain usable by keyboard.

## Troubleshooting

### A hover image is not shown

- Confirm the module is enabled for the current Store View.
- Confirm slider mode is disabled.
- Confirm a valid **Hover Image** role is assigned.
- Flush Magento cache after changing configuration or media.

### A slider is not shown

- Confirm the module and slider mode are enabled for the current Store View.
- Select at least one additional gallery image.
- Confirm the selected files are still present in the product gallery.
- Save the product, flush cache, and deploy static assets after JavaScript or LESS changes.

### Selections are missing in a Store View

- Check the Store View selected in the product editor.
- Check whether **Use Default Value** is intentionally enabled.
- Save after changing image selections.

## Development

From this repository's Magento wrapper root:

```bash
bin/phpcs app/code/Pelaquin/HoverImageProductGrids
bin/cli vendor/bin/phpunit -c app/code/Pelaquin/HoverImageProductGrids/phpunit.xml.dist
bin/analyse --level 6 app/code/Pelaquin/HoverImageProductGrids
bin/magento setup:di:compile
```

Static checks do not prove Admin or storefront behavior. After deployment, validate hover and slider modes in each supported grid, Store View inheritance, keyboard navigation, touch interaction, console health, and lazy-loaded image requests.

## Security

Slider selections are submitted by the Admin gallery UI, validated server-side, normalized as JSON, and constrained to the product media gallery. Templates escape URLs, labels, classes, and native attributes by context.

## License

Released under the [MIT License](LICENSE).
