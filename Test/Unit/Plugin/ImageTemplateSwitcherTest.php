<?php
/**
 *  Copyright © Bruno Pelaquin. All rights reserved.
 *
 *  https://github.com/https-pelaquin
 *  https://www.linkedin.com/in/bruno-pelaquin/
 */

declare(strict_types=1);

namespace Pelaquin\HoverImageProductGrids\Test\Unit\Plugin;

use Magento\Catalog\Block\Product\Image as ImageBlock;
use Magento\Catalog\Block\Product\ImageFactory;
use Magento\Catalog\Helper\ImageFactory as ImageHelperFactory;
use Magento\Catalog\Model\Product;
use Pelaquin\HoverImageProductGrids\Model\Config;
use Pelaquin\HoverImageProductGrids\Model\SliderImages;
use Pelaquin\HoverImageProductGrids\Plugin\ImageTemplateSwitcher;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;

#[AllowMockObjectsWithoutExpectations]
class ImageTemplateSwitcherTest extends TestCase
{
    public function testLeavesImageUntouchedWhenModuleIsDisabled(): void
    {
        $product = $this->createMock(Product::class);
        $product->method('getStoreId')->willReturn(1);
        $imageBlock = $this->createMock(ImageBlock::class);
        $config = $this->createMock(Config::class);
        $config->expects(self::once())->method('isEnabled')->with(1)->willReturn(false);

        $plugin = new ImageTemplateSwitcher(
            $config,
            $this->createMock(ImageHelperFactory::class),
            new SliderImages(new \Magento\Framework\Serialize\Serializer\Json())
        );

        self::assertSame(
            $imageBlock,
            $plugin->afterCreate($this->createMock(ImageFactory::class), $imageBlock, $product, 'category_page_grid')
        );
    }

    public function testLeavesUnsupportedImageContextUntouched(): void
    {
        $product = $this->createMock(Product::class);
        $product->method('getStoreId')->willReturn(1);
        $imageBlock = $this->createMock(ImageBlock::class);
        $config = $this->createMock(Config::class);
        $config->method('isEnabled')->willReturn(true);

        $plugin = new ImageTemplateSwitcher(
            $config,
            $this->createMock(ImageHelperFactory::class),
            new SliderImages(new \Magento\Framework\Serialize\Serializer\Json())
        );

        self::assertSame(
            $imageBlock,
            $plugin->afterCreate($this->createMock(ImageFactory::class), $imageBlock, $product, 'product_page_image_large')
        );
    }
}
