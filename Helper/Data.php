<?php
/**
 * Copyright © Bruno Pelaquin. All rights reserved.
 * https://github.com/https-pelaquin
 * https://www.linkedin.com/in/bruno-pelaquin/
 */

declare(strict_types=1);

namespace Pelaquin\HoverImageProductGrids\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    public function isModuleEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            'bp_hover_image_product_grids/general/active',
            ScopeInterface::SCOPE_STORE
        );
    }

    public function isSlider(): bool
    {
        return $this->scopeConfig->isSetFlag(
            'bp_hover_image_product_grids/general/slider_option',
            ScopeInterface::SCOPE_STORE
        );
    }
}
