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

namespace BaBeuloula\CloudImageProxy\Twig\Extension;

use BaBeuloula\CloudImageProxy\Encrypter;
use BaBeuloula\CloudImageProxy\Options;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ProxyExtension extends AbstractExtension
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly string $routeName,
        private readonly string $routeParameter,
        private readonly Encrypter $encrypter,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('cloud_image', $this->cloudImage(...)),
            new TwigFunction('ci', $this->cloudImage(...)),
        ];
    }

    /** @param array<string, mixed> $options */
    public function cloudImage(string $file, array $options = [], bool $enableEncrypter = true): string
    {
        $queryParams = Options::fromArray($options)->buildQuery();
        $queryParams = (true === $enableEncrypter)
            ? $this->encrypter->encrypt($queryParams)
            : '?' . $queryParams
        ;

        return $this->router->generate(
            $this->routeName,
            [$this->routeParameter => $file]
        ) . $queryParams;
    }
}
