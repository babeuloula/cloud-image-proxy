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

use Defuse\Crypto\Crypto;

final class Encrypter
{
    private const PARAMETER_KEY = 'data';

    public function __construct(
        private readonly ?string $secretKey = null,
    ) {
    }

    public function isEnabled(): bool
    {
        return \is_string($this->secretKey);
    }

    public function encrypt(string $data): string
    {
        if (true === $this->isEnabled()) {
            return '?' . self::PARAMETER_KEY . '=' . Crypto::encryptWithPassword($data, $this->getSecretKey());
        }

        return '?' . $data;
    }

    /**
     * @param array<string, mixed> $parameters
     *
     * @return array<int|string, mixed>
     */
    public function decrypt(array $parameters): array
    {
        if (true === $this->isEnabled()) {
            $data = Crypto::decryptWithPassword($parameters[self::PARAMETER_KEY] ?? '', $this->getSecretKey());
            parse_str($data, $parameters);

            return $parameters;
        }

        return $parameters;
    }

    private function getSecretKey(): string
    {
        return $this->secretKey ?? '';
    }
}
