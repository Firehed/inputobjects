<?php
declare(strict_types=1);

namespace Firehed\InputObjects;

/**
 * @coversDefaultClass Firehed\InputObjects\URL
 * @covers ::<protected>
 * @covers ::<private>
 */
class URLTest extends \PHPUnit\Framework\TestCase
{
    use InputObjectTestTrait;

    public function getInputObject()
    {
        return new URL([PHP_URL_SCHEME, PHP_URL_HOST]);
    }

    public function evaluations(): array
    {
        // All of these valid values should pass through as a string
        return array_map(function ($url) {
            return [$url, $url];
        }, [
            'http://example.com',
            'https://example.com',
            'https://example.com/path',
            'https://user@example.com/path',
            'https://user:pass@example.com/path',
            'https://user:pass@example.com:8080/path',

        ]);
    }

    public function invalidEvaluations(): array
    {
        return [
            ['http://'],
        ];
    }
}
