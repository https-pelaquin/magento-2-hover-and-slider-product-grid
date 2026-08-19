<!--
/**
 * Copyright © Bruno Pelaquin. All rights reserved.
 *
 * https://github.com/https-pelaquin
 * https://www.linkedin.com/in/bruno-pelaquin/
 */
-->

# Pelaquin_HoverImageProductGrids

Adds an alternate product-image experience to native Magento product grids. The module can show a second image on hover or render a per-product image slider.

## Features

- Two exclusive display modes: hover image or product image slider.
- Store-view scoped configuration and product image selections.
- Product gallery controls for selecting multiple slider images.
- Slider order follows the product media gallery order.
- Main product image is always rendered as the first slider slide.
- Support for category, widget, related, upsell, and cross-sell product grids.
- Native Magento RequireJS initialization and PageBuilder Slick dependency.
- `pt_BR` translations for the configuration and gallery controls.

## Requirements

- Magento Open Source 2.4.9.
- Magento PageBuilder, which provides the Slick JavaScript dependency used by the slider.
- Product images managed through the native Magento product media gallery.

## Installation and Update

From the Magento project root, enable the module if necessary and run the Magento setup commands:

```bash
bin/magento module:enable Pelaquin_HoverImageProductGrids
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f pt_BR
bin/magento cache:flush
```

`setup:upgrade` creates the product attributes used by the module:

- `hover_catalog`: the Store View scoped media image used by hover mode.
- `slider_images`: the Store View scoped list of images selected for slider mode.

## Store Configuration

Open **Stores > Configuration > Pelaquin > Hover Image Product Grids > General**.

| Setting | Description |
| --- | --- |
| **Enable Hover Image Product Grids Module** | Enables or disables the module for the selected configuration scope. |
| **Use Slider Instead of Hover** | Switches the grid behavior from hover mode to slider mode. |

Both settings support Default Config, Website, and Store View scopes. Slider image selections also support Store View inheritance through Magento's **Use Default Value** control.

## Hover Mode

1. Set **Use Slider Instead of Hover** to **No**.
2. Edit a product and open **Images and Videos**.
3. Assign one gallery image to the **Hover** image role.
4. Save the product.

On supported product grids, the primary image fades into the image assigned to the `hover_catalog` attribute when the product card is hovered.

If a product has no Hover image assigned, Magento renders its standard product image without a hover effect.

## Slider Mode

1. Set **Use Slider Instead of Hover** to **Yes**.
2. Edit a product and open **Images and Videos**.
3. Open an image from the media gallery.
4. Enable **Use in Product Grid Slider**.
5. Repeat for every additional image that should appear in the slider, then save the product.

The primary product image is always the first slide. The selected images are appended after it in the same order as the product media gallery. Duplicate images are ignored.

At least one selected image different from the main product image is required. Otherwise, Magento keeps its standard product image output and does not initialize a slider for that card.

### Slider Interaction

Slider arrows are displayed while the product card is hovered on desktop devices. Touch devices can use swipe interaction.

The product-image link is disabled for cards with an active slider. This prevents clicks on the slider controls or slides from redirecting to the product page. Customers can still open the product through the product name, price area, or any other product link provided by the theme.

## Supported Product Grid Locations

The module only changes the following native Magento product image identifiers:

- Category product grid.
- Related products.
- Upsell products.
- Cart cross-sell products.
- New Products and Catalog Product List widgets.

It does not alter product detail page galleries, product list-mode images, cart item images, checkout images, wishlist images, comparison images, or other image contexts outside these product grids.

## Store View Behavior

The hover image and slider selection are product attributes with Store View scope.

- Configure a different Hover image or slider selection in each Store View when needed.
- In a non-default Store View, leave **Use Default Value** enabled to inherit the parent slider selection.
- Disable **Use Default Value** before selecting a different set of slider images for that Store View.

## Troubleshooting

### The hover image is not displayed

- Confirm that the module is enabled for the current Store View.
- Confirm that **Use Slider Instead of Hover** is set to **No**.
- Confirm that a product image has the **Hover** role assigned.
- Flush Magento cache after changing configuration or product media.

### The slider is not displayed

- Confirm that the module is enabled and slider mode is enabled for the current Store View.
- Select at least one gallery image through **Use in Product Grid Slider**.
- Confirm that the selected image is different from the main product image.
- Save the product, flush cache, and redeploy static content when JavaScript or LESS changes are deployed.

### Slider selections are missing in a Store View

- Verify the selected Store View in the product editor.
- Check whether **Use Default Value** is intentionally enabled.
- Save the product after changing the selection.

## Technical Notes

- The frontend is initialized per product card with `data-mage-init`; no global slider script is used.
- The slider uses Magento PageBuilder's `slick` RequireJS alias.
- Selected slider files are serialized as JSON in `slider_images` and are loaded with product listing data.
- The module uses a plugin on `Magento\Catalog\Block\Product\ImageFactory` and filters by product-grid image identifier, avoiding changes outside the supported locations.

## Validation After Deployment

After installing or updating the module, verify the following in the storefront for each relevant Store View:

1. A product with a Hover image changes image in hover mode.
2. A product with selected slider images changes slides without navigating to the product page.
3. A product without a configured Hover image or slider images keeps the default Magento image behavior.
4. Category, related, upsell, cross-sell, and widget grids behave as expected in the active mode.
