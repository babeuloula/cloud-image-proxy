<?php

/**
 * @author      BaBeuloula <info@babeuloula.fr>
 * @copyright   Copyright (c) BaBeuloula
 * @license     MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace BaBeuloula\CloudImageProxy;

final class Options
{
    private const DEFAULT_WIDTH = null;
    private const DEFAULT_HEIGHT = null;
    private const DEFAULT_PREVENT_ENLARGEMENT = true;
    private const DEFAULT_WATERMARK_URL = null;
    private const DEFAULT_WATERMARK_GRAVITY = 'center';
    private const DEFAULT_WATERMARK_SCALE = '75p';
    private const DEFAULT_WATERMARK_OPACITY = 0.5;

    public function __construct(
        public readonly null|int|string $width = self::DEFAULT_WIDTH,
        public readonly null|int|string $height = self::DEFAULT_HEIGHT,
        public readonly bool $preventEnlargement = self::DEFAULT_PREVENT_ENLARGEMENT,
        public readonly ?string $watermarkUrl = self::DEFAULT_WATERMARK_URL,
        public readonly string $watermarkGravity = self::DEFAULT_WATERMARK_GRAVITY,
        public readonly string $watermarkScale = self::DEFAULT_WATERMARK_SCALE,
        public readonly float $watermarkOpacity = self::DEFAULT_WATERMARK_OPACITY,
    ) {
    }

    public function buildQuery(): string
    {
        $options = [
            'width' => $this->width,
            'height' => $this->height,
            'org_if_sml' => (true === $this->preventEnlargement) ? '1' : '0',
            'wat' => (true === \is_string($this->watermarkUrl)) ? '1' : null,
            'wat_url' => $this->watermarkUrl,
            'wat_gravity' => $this->watermarkGravity,
            'wat_scale' => $this->watermarkScale,
            'wat_opacity' => $this->watermarkOpacity,
        ];

        return http_build_query(array_filter($options));
    }

    /** @param array<string, mixed> $options */
    public static function fromArray(array $options): self
    {
        return new self(
            $options['width'] ?? $options['w'] ?? self::DEFAULT_WIDTH,
            $options['height'] ?? $options['h'] ?? self::DEFAULT_HEIGHT,
            (bool) ($options['preventEnlargement'] ?? $options['pe'] ?? $options['org_if_sml'] ?? self::DEFAULT_PREVENT_ENLARGEMENT),
            $options['watermarkUrl'] ?? $options['wu'] ?? $options['wat_url'] ?? self::DEFAULT_WATERMARK_URL,
            $options['watermarkGravity'] ?? $options['wg'] ?? $options['wat_gravity'] ?? self::DEFAULT_WATERMARK_GRAVITY,
            $options['watermarkScale'] ?? $options['ws'] ?? $options['wat_scale'] ?? self::DEFAULT_WATERMARK_SCALE,
            (float) ($options['watermarkOpacity'] ?? $options['wo'] ?? $options['wat_opacity'] ?? self::DEFAULT_WATERMARK_OPACITY),
        );
    }
}
