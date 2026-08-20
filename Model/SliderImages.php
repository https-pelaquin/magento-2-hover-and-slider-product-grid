<?php
/**
 * Copyright © Bruno Pelaquin. All rights reserved.
 * https://github.com/https-pelaquin
 * https://www.linkedin.com/in/bruno-pelaquin/
 */

declare(strict_types=1);

namespace Pelaquin\HoverImageProductGrids\Model;

use InvalidArgumentException;
use Magento\Framework\Serialize\Serializer\Json;

class SliderImages
{
    public const ATTRIBUTE_CODE = 'slider_images';

    public function __construct(
        private readonly Json $serializer
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

        return is_array($images) ? $this->normalize($images) : [];
    }

    /**
     * @return string[]
     */
    public function decodeStrict(string $value): array
    {
        $images = $this->serializer->unserialize($value);

        if (!is_array($images)) {
            throw new InvalidArgumentException('The slider image selection must be an array.');
        }

        return $this->normalize($images);
    }

    /**
     * @param string[] $images
     */
    public function encode(array $images): string
    {
        return $this->serializer->serialize($this->normalize($images));
    }

    /**
     * @param mixed[] $images
     * @return string[]
     */
    public function normalize(array $images): array
    {
        $images = array_filter(
            $images,
            static fn (mixed $image): bool => is_string($image)
                && $image !== ''
                && $image !== 'no_selection'
        );

        return array_values(array_unique($images));
    }
}
