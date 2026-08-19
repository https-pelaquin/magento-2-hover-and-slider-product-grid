<?php
/**
 * Copyright © Bruno Pelaquin. All rights reserved.
 * https://github.com/https-pelaquin
 * https://www.linkedin.com/in/bruno-pelaquin/
 */

declare(strict_types=1);

namespace Pelaquin\HoverImageProductGrids\Plugin;

use Magento\Catalog\Block\Product\Image as ImageBlock;
use Magento\Catalog\Block\Product\ImageFactory;
use Magento\Catalog\Helper\ImageFactory as ImageHelperFactory;
use Magento\Catalog\Model\Product;
use Pelaquin\HoverImageProductGrids\Helper\Data as Helper;
use Pelaquin\HoverImageProductGrids\Model\SliderImages;

class ImageTemplateSwitcher
{
    private const HOVER_ATTRIBUTE = 'hover_catalog';

    private const PRODUCT_GRID_IMAGE_IDS = [
        'category_page_grid',
        'related_products_list',
        'upsell_products_list',
        'cart_cross_sell_products',
        'new_products_content_widget_grid'
    ];

    public function __construct(
        private readonly Helper $helper,
        private readonly ImageHelperFactory $imageHelperFactory,
        private readonly SliderImages $sliderImages
    ) {
    }

    /**
     * @param array<string, mixed>|null $attributes
     */
    public function aroundCreate(
        ImageFactory $subject,
        callable $proceed,
        Product $product,
        string $imageId,
        ?array $attributes = null
    ): ImageBlock {
        /** @var ImageBlock $imageBlock */
        $imageBlock = $proceed($product, $imageId, $attributes);

        if (!$this->helper->isModuleEnabled()) {
            return $imageBlock;
        }

        if (!in_array($imageId, self::PRODUCT_GRID_IMAGE_IDS, true)) {
            return $imageBlock;
        }

        if ($this->helper->isSlider()) {
            return $this->applySliderTemplate($imageBlock, $product, $imageId);
        }

        $hoverImage = (string)$product->getData(self::HOVER_ATTRIBUTE);
        if ($hoverImage === '' || $hoverImage === 'no_selection') {
            return $imageBlock;
        }

        $imageHelper = $this->imageHelperFactory->create();
        $hoverImageUrl = $imageHelper
            ->init($product, $imageId)
            ->setImageFile($hoverImage)
            ->getUrl();

        $imageBlock->setData('hover_image_url', $hoverImageUrl);
        $imageBlock->setTemplate('Pelaquin_HoverImageProductGrids::product/hover_product_image.phtml');

        return $imageBlock;
    }

    private function applySliderTemplate(
        ImageBlock $imageBlock,
        Product $product,
        string $imageId
    ): ImageBlock {
        $sliderImageUrls = [];
        $primaryImageUrl = (string)$imageBlock->getImageUrl();
        $sliderImageFiles = $this->sliderImages->decode($product->getData(SliderImages::ATTRIBUTE_CODE));

        foreach ($sliderImageFiles as $sliderImageFile) {
            $imageHelper = $this->imageHelperFactory->create();
            $sliderImageUrl = $imageHelper
                ->init($product, $imageId)
                ->setImageFile($sliderImageFile)
                ->getUrl();

            if ($sliderImageUrl !== $primaryImageUrl) {
                $sliderImageUrls[] = $sliderImageUrl;
            }
        }

        $sliderImageUrls = array_values(array_unique($sliderImageUrls));
        if ($sliderImageUrls === []) {
            return $imageBlock;
        }

        $imageBlock->setData('slider_image_urls', $sliderImageUrls);
        $imageBlock->setTemplate('Pelaquin_HoverImageProductGrids::product/slider_product_image.phtml');

        return $imageBlock;
    }
}
