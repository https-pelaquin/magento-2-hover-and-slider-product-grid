<?php
/**
 * Copyright © Bruno Pelaquin. All rights reserved.
 * https://github.com/https-pelaquin
 * https://www.linkedin.com/in/bruno-pelaquin/
 */

declare(strict_types=1);

namespace Pelaquin\HoverImageProductGrids\Plugin\Adminhtml;

use InvalidArgumentException;
use Magento\Catalog\Block\Adminhtml\Product\Helper\Form\Gallery;
use Magento\Catalog\Block\Adminhtml\Product\Helper\Form\Gallery\Content;
use Magento\Catalog\Model\Product;
use Magento\Framework\Serialize\Serializer\Json;
use Pelaquin\HoverImageProductGrids\Model\SliderImages;

class GalleryImagesData
{
    public function __construct(
        private readonly Json $serializer,
        private readonly SliderImages $sliderImages
    ) {
    }

    public function afterGetImagesJson(Content $subject, string $result): string
    {
        try {
            $images = $this->serializer->unserialize($result);
        } catch (InvalidArgumentException) {
            return $result;
        }

        if (!is_array($images) || $images === []) {
            return $result;
        }

        $element = $subject->getData('element');
        if (!$element instanceof Gallery) {
            return $result;
        }

        $product = $element->getDataObject();
        if (!$product instanceof Product) {
            return $result;
        }

        $selectedImages = $this->sliderImages->decode($product->getData(SliderImages::ATTRIBUTE_CODE));
        $canUseDefault = (int)$product->getStoreId() !== 0;
        $useDefault = $canUseDefault && !$product->getExistsStoreValueFlag(SliderImages::ATTRIBUTE_CODE);

        foreach ($images as $key => $image) {
            if (!is_array($image)) {
                continue;
            }

            $image['slider_selected'] = in_array($image['file'] ?? '', $selectedImages, true);
            $image['slider_can_use_default'] = $canUseDefault;
            $image['slider_use_default'] = $useDefault;
            $images[$key] = $image;
        }

        return $this->serializer->serialize($images);
    }
}
