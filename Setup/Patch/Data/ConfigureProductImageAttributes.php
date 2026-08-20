<?php
/**
 *  Copyright © Bruno Pelaquin. All rights reserved.
 *
 *  https://github.com/https-pelaquin
 *  https://www.linkedin.com/in/bruno-pelaquin/
 */

declare(strict_types=1);

namespace Pelaquin\HoverImageProductGrids\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Pelaquin\HoverImageProductGrids\Model\Product\Attribute\Backend\SliderImages as SliderImagesBackend;
use Pelaquin\HoverImageProductGrids\Model\SliderImages;

class ConfigureProductImageAttributes implements DataPatchInterface
{
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
        private readonly EavSetupFactory $eavSetupFactory
    ) {
    }

    public function apply(): self
    {
        $this->moduleDataSetup->startSetup();

        try {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
            if ($eavSetup->getAttribute(Product::ENTITY, 'hover_catalog')) {
                $eavSetup->updateAttribute(Product::ENTITY, 'hover_catalog', 'frontend_label', 'Hover Image');
            }

            if ($eavSetup->getAttribute(Product::ENTITY, SliderImages::ATTRIBUTE_CODE)) {
                $eavSetup->updateAttribute(
                    Product::ENTITY,
                    SliderImages::ATTRIBUTE_CODE,
                    'backend_model',
                    SliderImagesBackend::class
                );
            }
        } finally {
            $this->moduleDataSetup->endSetup();
        }

        return $this;
    }

    public function getAliases(): array
    {
        return [];
    }

    public static function getDependencies(): array
    {
        return [
            HoverAttribute::class,
            SliderImagesAttribute::class
        ];
    }
}
