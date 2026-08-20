<?php
/**
 *  Copyright © Bruno Pelaquin. All rights reserved.
 *
 *  https://github.com/https-pelaquin
 *  https://www.linkedin.com/in/bruno-pelaquin/
 */

declare(strict_types=1);

namespace Pelaquin\HoverImageProductGrids\Test\Unit\Model;

use InvalidArgumentException;
use Magento\Framework\Serialize\Serializer\Json;
use Pelaquin\HoverImageProductGrids\Model\SliderImages;
use PHPUnit\Framework\TestCase;

class SliderImagesTest extends TestCase
{
    public function testNormalizesSelectionAndPreservesOrder(): void
    {
        $serializer = new SliderImages(new Json());

        self::assertSame(
            ['/a.jpg', '/b.jpg'],
            $serializer->normalize(['/a.jpg', '', 'no_selection', '/b.jpg', '/a.jpg', 10])
        );
    }

    public function testInvalidStoredJsonFailsSafely(): void
    {
        $serializer = new SliderImages(new Json());

        self::assertSame([], $serializer->decode('{invalid'));
    }

    public function testStrictDecodeRejectsNonArrayJson(): void
    {
        $serializer = new SliderImages(new Json());

        $this->expectException(InvalidArgumentException::class);
        $serializer->decodeStrict('"/a.jpg"');
    }
}
