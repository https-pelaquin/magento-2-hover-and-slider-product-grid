<?php
/**
 *  Copyright © Bruno Pelaquin. All rights reserved.
 *
 *  https://github.com/https-pelaquin
 *  https://www.linkedin.com/in/bruno-pelaquin/
 */

declare(strict_types=1);

namespace Pelaquin\HoverImageProductGrids\Model\Product\Attribute\Backend;

use InvalidArgumentException;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;
use Magento\Framework\Exception\LocalizedException;
use Pelaquin\HoverImageProductGrids\Model\SliderImages as SliderImagesSerializer;

class SliderImages extends AbstractBackend
{
    public function __construct(
        private readonly SliderImagesSerializer $sliderImages
    ) {
    }

    /**
     * @param Product $object
     */
    public function beforeSave($object): self
    {
        $attributeCode = (string)$this->getAttribute()->getAttributeCode();
        $value = $object->getData($attributeCode);

        if ($value === null || $value === '') {
            return $this;
        }

        if (!is_string($value)) {
            throw new LocalizedException(__('The product grid slider image selection is invalid.'));
        }

        try {
            $images = $this->sliderImages->decodeStrict($value);
        } catch (InvalidArgumentException) {
            throw new LocalizedException(__('The product grid slider image selection is invalid.'));
        }

        $availableImages = $this->getAvailableImageFiles($object);
        $images = array_values(array_intersect($images, $availableImages));

        $object->setData($attributeCode, $this->sliderImages->encode($images));

        return $this;
    }

    /**
     * @return string[]
     */
    private function getAvailableImageFiles(Product $product): array
    {
        $mediaGallery = $product->getData('media_gallery');
        $galleryImages = is_array($mediaGallery) ? ($mediaGallery['images'] ?? []) : [];

        if (!is_array($galleryImages) || $galleryImages === []) {
            $galleryImages = $product->getMediaGalleryImages();
        }

        if (!is_iterable($galleryImages)) {
            return [];
        }

        $files = [];
        foreach ($galleryImages as $galleryImage) {
            $file = is_array($galleryImage)
                ? ($galleryImage['file'] ?? null)
                : $galleryImage->getData('file');
            $isRemoved = is_array($galleryImage)
                ? !empty($galleryImage['removed'])
                : false;

            if (!is_string($file)) {
                continue;
            }

            if ($file !== '' && !$isRemoved) {
                $files[] = $file;
            }
        }

        return array_values(array_unique($files));
    }
}
