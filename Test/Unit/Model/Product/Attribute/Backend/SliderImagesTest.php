<?php
/**
 *  Copyright © Bruno Pelaquin. All rights reserved.
 *
 *  https://github.com/https-pelaquin
 *  https://www.linkedin.com/in/bruno-pelaquin/
 */

declare(strict_types=1);

namespace Pelaquin\HoverImageProductGrids\Test\Unit\Model\Product\Attribute\Backend;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magento\Framework\Serialize\Serializer\Json;
use Pelaquin\HoverImageProductGrids\Model\Product\Attribute\Backend\SliderImages;
use Pelaquin\HoverImageProductGrids\Model\SliderImages as SliderImagesSerializer;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;

#[AllowMockObjectsWithoutExpectations]
class SliderImagesTest extends TestCase
{
    public function testKeepsOnlyFilesAvailableInTheProductGallery(): void
    {
        $attribute = $this->createMock(AbstractAttribute::class);
        $attribute->method('getAttributeCode')->willReturn('slider_images');

        $product = $this->createMock(Product::class);
        $product->method('getData')->willReturnCallback(
            static fn (string $key): mixed => $key === 'slider_images'
                ? '["/a.jpg", "/outside.jpg", "/a.jpg"]'
                : ['images' => [['file' => '/a.jpg']]]
        );
        $product->expects(self::once())
            ->method('setData')
            ->with('slider_images', '["\/a.jpg"]');

        $backend = new SliderImages(new SliderImagesSerializer(new Json()));
        $backend->setAttribute($attribute);
        $backend->beforeSave($product);
    }
}
