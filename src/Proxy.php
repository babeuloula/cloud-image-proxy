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

use BaBeuloula\CloudImageProxy\Exception\FetchAssetException;
use BaBeuloula\CloudImageProxy\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class Proxy
{
    private string $assetsPath;

    public function __construct(
        string $assetsPath,
        private readonly bool $checkAssets,
        private readonly Filesystem $filesystem,
        private readonly HttpClientInterface $client,
        private readonly string $cloudImageUrl,
    ) {
        $this->assetsPath = rtrim($assetsPath, '/') . '/';
    }

    /** @return array<string, mixed> */
    public function parseHeaders(Request $request): array
    {
        $requestedHeaders = [
            'accept-language',
            'accept-encoding',
            'accept',
            'user-agent',
        ];

        $headers = [];

        foreach ($requestedHeaders as $header) {
            if (true === $request->headers->has($header)) {
                $headers[$header] = $request->headers->get($header, '');
            }
        }

        return $headers;
    }

    /** @param array<string, mixed> $headers */
    public function response(string $file, ?Options $options = null, array $headers = []): Response
    {
        $file = $this->normalizeFile($file);

        if (true === $this->checkAssets && false === $this->exists($file)) {
            throw new FileNotFoundException($file);
        }

        $newResponse = new Response();

        try {
            $response = $this->client->request(
                Request::METHOD_GET,
                $this->cloudImageUrl . $file . '?' . ($options?->buildQuery() ?? ''),
                [
                    'headers' => $headers,
                ],
            );

            $newResponse->setContent($response->getContent());

            $copiedHeaders = [
                'last-modified',
                'etag',
                'cache-control',
                'content-encoding',
                'content-type',
                'content-length',
            ];

            foreach ($copiedHeaders as $header) {
                if (false === \array_key_exists($header, $response->getHeaders())) {
                    continue;
                }

                $newResponse->headers->set($header, $response->getHeaders()[$header]);
            }
        } catch (\Throwable $e) {
            if (Response::HTTP_NOT_FOUND === $e->getCode()) {
                throw new NotFoundHttpException(previous: $e);
            }

            throw new FetchAssetException(previous: $e);
        }

        return $newResponse;
    }

    private function exists(string $file): bool
    {
        return $this->filesystem->exists($this->assetsPath . $this->normalizeFile($file));
    }

    private function normalizeFile(string $file): string
    {
        return ltrim($file, '/');
    }
}
