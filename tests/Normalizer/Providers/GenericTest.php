<?php

/**
 * Utopia PHP Framework
 *
 *
 * @link https://github.com/utopia-php/framework
 *
 * @author Eldad Fux <eldad@appwrite.io>
 *
 * @version 1.0 RC4
 *
 * @license The MIT License (MIT) <http://www.opensource.org/licenses/mit-license.php>
 */

namespace Utopia\Tests\Normalizer\Providers;

use PHPUnit\Framework\TestCase;
use Utopia\Emails\Normalizer\Providers\Generic;

class GenericTest extends TestCase
{
    private Generic $provider;

    protected function setUp(): void
    {
        $this->provider = new Generic;
    }

    public function test_supports(): void
    {
        // Generic provider supports all domains
        $this->assertTrue($this->provider->supports('example.com'));
        $this->assertTrue($this->provider->supports('test.org'));
        $this->assertTrue($this->provider->supports('company.net'));
        $this->assertTrue($this->provider->supports('business.co.uk'));
        $this->assertTrue($this->provider->supports('gmail.com'));
        $this->assertTrue($this->provider->supports('outlook.com'));
        $this->assertTrue($this->provider->supports('any-domain.com'));
    }

    public function test_normalize(): void
    {
        $testCases = [
            // Other domains with plus addressing
            ['user.name+tag', 'example.com', 'username', 'example.com'],
            ['user.name+spam', 'example.com', 'username', 'example.com'],
            ['user.name+newsletter', 'example.com', 'username', 'example.com'],
            ['user.name+work', 'example.com', 'username', 'example.com'],
            ['user.name+personal', 'example.com', 'username', 'example.com'],
            ['user.name+test123', 'example.com', 'username', 'example.com'],
            ['user.name+anything', 'example.com', 'username', 'example.com'],
            ['user.name+verylongtag', 'example.com', 'username', 'example.com'],
            ['user.name+tag.with.dots', 'example.com', 'username', 'example.com'],
            ['user.name+tag-with-hyphens', 'example.com', 'username', 'example.com'],
            ['user.name+tag_with_underscores', 'example.com', 'username', 'example.com'],
            ['user.name+tag123', 'example.com', 'username', 'example.com'],
            // Dots are preserved for other domains
            ['user.name', 'example.com', 'user.name', 'example.com'],
            ['u.s.e.r.n.a.m.e', 'example.com', 'u.s.e.r.n.a.m.e', 'example.com'],
            // Hyphens are preserved for other domains
            ['user-name', 'example.com', 'user-name', 'example.com'],
            ['user-name+tag', 'example.com', 'username', 'example.com'],
            // Edge cases
            ['user+', 'example.com', 'user', 'example.com'],
            ['user.', 'example.com', 'user.', 'example.com'],
            ['.user', 'example.com', '.user', 'example.com'],
            // Test with different domains
            ['user.name+tag', 'test.org', 'username', 'test.org'],
            ['user.name+tag', 'company.net', 'username', 'company.net'],
            ['user.name+tag', 'business.co.uk', 'username', 'business.co.uk'],
        ];

        foreach ($testCases as [$inputLocal, $inputDomain, $expectedLocal, $expectedDomain]) {
            $result = $this->provider->normalize($inputLocal, $inputDomain);
            $this->assertEquals($expectedLocal, $result['local'], "Failed for local: {$inputLocal}@{$inputDomain}");
            $this->assertEquals($expectedDomain, $result['domain'], "Failed for domain: {$inputLocal}@{$inputDomain}");
        }
    }

    public function test_get_canonical_domain(): void
    {
        // Generic provider doesn't have a canonical domain
        $this->assertEquals('', $this->provider->getCanonicalDomain());
    }

    public function test_get_supported_domains(): void
    {
        // Generic provider supports all domains
        $domains = $this->provider->getSupportedDomains();
        $this->assertEquals([], $domains);
    }
}
