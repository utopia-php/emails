<?php

namespace Utopia\Tests\Canonicals\Providers;

use PHPUnit\Framework\TestCase;
use Utopia\Emails\Canonicals\Providers\Icloud;

class IcloudTest extends TestCase
{
    private Icloud $provider;

    protected function setUp(): void
    {
        $this->provider = new Icloud;
    }

    public function test_supports(): void
    {
        $this->assertTrue($this->provider->supports('icloud.com'));
        $this->assertTrue($this->provider->supports('me.com'));
        $this->assertTrue($this->provider->supports('mac.com'));
        $this->assertFalse($this->provider->supports('gmail.com'));
        $this->assertFalse($this->provider->supports('outlook.com'));
        $this->assertFalse($this->provider->supports('example.com'));
    }

    public function test_get_canonical(): void
    {
        $testCases = [
            // TODO: Commented out until manual confirmation of iCloud's plus addressing and dots support
            // ['user.name+tag', 'icloud.com', 'username', 'icloud.com'],
            // ['user.name+spam', 'icloud.com', 'username', 'icloud.com'],
            // ['user.name+newsletter', 'icloud.com', 'username', 'icloud.com'],
            // ['user.name+work', 'icloud.com', 'username', 'icloud.com'],
            // ['user.name+personal', 'icloud.com', 'username', 'icloud.com'],
            // ['user.name+test123', 'icloud.com', 'username', 'icloud.com'],
            // ['user.name+anything', 'icloud.com', 'username', 'icloud.com'],
            // ['user.name+verylongtag', 'icloud.com', 'username', 'icloud.com'],
            // ['user.name+tag.with.dots', 'icloud.com', 'username', 'icloud.com'],
            // ['user.name+tag-with-hyphens', 'icloud.com', 'username', 'icloud.com'],
            // ['user.name+tag_with_underscores', 'icloud.com', 'username', 'icloud.com'],
            // ['user.name+tag123', 'icloud.com', 'username', 'icloud.com'],
            // // Other Apple domains
            // ['user.name+tag', 'me.com', 'username', 'icloud.com'],
            // ['user.name+tag', 'mac.com', 'username', 'icloud.com'],
            // Dots are preserved for iCloud
            ['user.name', 'icloud.com', 'user.name', 'icloud.com'],
            ['u.s.e.r.n.a.m.e', 'icloud.com', 'u.s.e.r.n.a.m.e', 'icloud.com'],
            // Edge cases
            // ['user+', 'icloud.com', 'user', 'icloud.com'],
            ['user.', 'icloud.com', 'user.', 'icloud.com'],
            ['.user', 'icloud.com', '.user', 'icloud.com'],
            // Other Apple domains
            ['user.name', 'me.com', 'user.name', 'icloud.com'],
            ['user.name', 'mac.com', 'user.name', 'icloud.com'],
        ];

        foreach ($testCases as [$inputLocal, $inputDomain, $expectedLocal, $expectedDomain]) {
            $result = $this->provider->getCanonical($inputLocal, $inputDomain);
            $this->assertEquals($expectedLocal, $result['local'], "Failed for local: {$inputLocal}@{$inputDomain}");
            $this->assertEquals($expectedDomain, $result['domain'], "Failed for domain: {$inputLocal}@{$inputDomain}");
        }
    }

    public function test_get_canonical_domain(): void
    {
        $this->assertEquals('icloud.com', $this->provider->getCanonicalDomain());
    }

    public function test_get_supported_domains(): void
    {
        $domains = $this->provider->getSupportedDomains();
        $expected = ['icloud.com', 'me.com', 'mac.com'];
        $this->assertEquals($expected, $domains);
    }
}
