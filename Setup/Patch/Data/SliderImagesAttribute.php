<?php
/**
 * Copyright © Bruno Pelaquin. All rights reserved.
 * https://github.com/https-pelaquin
 * https://www.linkedin.com/in/bruno-pelaquin/
 */

declare(strict_types=1);

namespace Pelaquin\HoverImageProductGrids\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Pelaquin\HoverImageProductGrids\Model\SliderImages;

class SliderImagesAttribute implements DataPatchInterface
{
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
        private readonly EavSetupFactory $eavSetupFactory
    ) {
    }

    public function apply(): self
    {
        $this->moduleDataSetup->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $attribute = $eavSetup->getAttribute(Product::ENTITY, SliderImages::ATTRIBUTE_CODE);

        if (!$attribute) {
            $eavSetup->addAttribute(
                Product::ENTITY,
                SliderImages::ATTRIBUTE_CODE,
                [
                    'type' => 'text',
                    'label' => 'Product Grid Slider Images',
                    'input' => 'text',
                    'required' => false,
                    'global' => ScopedAttributeInterface::SCOPE_STORE,
                    'visible' => false,
                    'user_defined' => false,
                    'used_in_product_listing' => true
                ]
            );
        }

        $this->moduleDataSetup->endSetup();

        return $this;
    }

    public function getAliases(): array
    {
        return [];
    }

    public static function getDependencies(): array
    {
        return [];
    }
}
