<?php
/**
 *  Copyright © Bruno Pelaquin. All rights reserved.
 *
 *  https://github.com/https-pelaquin
 *  https://www.linkedin.com/in/bruno-pelaquin/
 */

declare(strict_types=1);

namespace Pelaquin\HoverImageProductGrids\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    public const XML_PATH_ACTIVE = 'bp_hover_image_product_grids/general/active';
    public const XML_PATH_SLIDER_OPTION = 'bp_hover_image_product_grids/general/slider_option';

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    public function isEnabled(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ACTIVE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function isSliderEnabled(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SLIDER_OPTION,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
