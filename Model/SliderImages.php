<?php
/**
 * Copyright © Bruno Pelaquin. All rights reserved.
 * https://github.com/https-pelaquin
 * https://www.linkedin.com/in/bruno-pelaquin/
 */

declare(strict_types=1);

namespace Pelaquin\HoverImageProductGrids\Model;

use InvalidArgumentException;
use Magento\Framework\Serialize\SerializerInterface;

class SliderImages
{
    public const ATTRIBUTE_CODE = 'slider_images';

    public function __construct(
        private readonly SerializerInterface $serializer
    ) {
    }

    /**
     * @return string[]
     */
    public function decode(mixed $value): array
    {
        if (!is_string($value) || $value === '') {
            return [];
        }

        try {
            $images = $this->serializer->unserialize($value);
        } catch (InvalidArgumentException) {
            return [];
        }

        if (!is_array($images)) {
            return [];
        }

        $images = array_filter($images, static fn (mixed $image): bool => is_string($image) && $image !== '');

        return array_values(array_unique($images));
    }

    /**
     * @param string[] $images
     */
    public function encode(array $images): string
    {
        return $this->serializer->serialize(array_values(array_unique($images)));
    }
}
