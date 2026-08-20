<?php
/**
 *  Copyright © Bruno Pelaquin. All rights reserved.
 *
 *  https://github.com/https-pelaquin
 *  https://www.linkedin.com/in/bruno-pelaquin/
 */

declare(strict_types=1);

namespace Pelaquin\HoverImageProductGrids\Test\Unit\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Pelaquin\HoverImageProductGrids\Model\Config;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;

#[AllowMockObjectsWithoutExpectations]
class ConfigTest extends TestCase
{
    public function testReadsEnabledFlagFromExplicitStoreScope(): void
    {
        $scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $scopeConfig->expects(self::once())
            ->method('isSetFlag')
            ->with(Config::XML_PATH_ACTIVE, ScopeInterface::SCOPE_STORE, 2)
            ->willReturn(true);

        self::assertTrue((new Config($scopeConfig))->isEnabled(2));
    }

    public function testReadsSliderFlagFromExplicitStoreScope(): void
    {
        $scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $scopeConfig->expects(self::once())
            ->method('isSetFlag')
            ->with(Config::XML_PATH_SLIDER_OPTION, ScopeInterface::SCOPE_STORE, 3)
            ->willReturn(true);

        self::assertTrue((new Config($scopeConfig))->isSliderEnabled(3));
    }
}
