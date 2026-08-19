<?php
/**
 * Copyright © Bruno Pelaquin. All rights reserved.
 * https://github.com/https-pelaquin
 * https://www.linkedin.com/in/bruno-pelaquin/
 */

declare(strict_types=1);

namespace Pelaquin\HoverImageProductGrids\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Attribute\Frontend\Image;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class HoverAttribute implements DataPatchInterface
{
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
        private readonly EavSetupFactory $eavSetupFactory
    ) {
    }

    /**
     * Do Upgrade
     *
     * @return self
     */
    public function apply(): self
    {
        $setup = $this->moduleDataSetup;
        $setup->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $checkAttribute = $eavSetup->getAttribute(Product::ENTITY, 'hover_catalog');
        if (!$checkAttribute) {
            $eavSetup->addAttribute(
                Product::ENTITY,
                'hover_catalog',
                [
                    'type' => 'varchar',
                    'label' => 'Hover',
                    'input' => 'media_image',
                    'frontend' => Image::class,
                    'required' => false,
                    'global' => ScopedAttributeInterface::SCOPE_STORE,
                    'used_in_product_listing' => true
                ]
            );
        }

        $setup->endSetup();

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies(): array
    {
        return [];
    }
}
