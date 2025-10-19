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
use Utopia\Emails\Normalizer\Providers\Yahoo;

class YahooTest extends TestCase
{
    private Yahoo $provider;

    protected function setUp(): void
    {
        $this->provider = new Yahoo;
    }

    public function test_supports(): void
    {
        $this->assertTrue($this->provider->supports('yahoo.com'));
        $this->assertTrue($this->provider->supports('yahoo.co.uk'));
        $this->assertTrue($this->provider->supports('yahoo.ca'));
        $this->assertTrue($this->provider->supports('ymail.com'));
        $this->assertTrue($this->provider->supports('rocketmail.com'));
        $this->assertFalse($this->provider->supports('gmail.com'));
        $this->assertFalse($this->provider->supports('outlook.com'));
        $this->assertFalse($this->provider->supports('example.com'));
    }

    public function test_normalize(): void
    {
        $testCases = [
            // TODO: Commented out until manual confirmation of Yahoo's plus addressing, dots, and hyphens support
            // ['user.name+tag', 'yahoo.com', 'username', 'yahoo.com'],
            // ['user.name+spam', 'yahoo.com', 'username', 'yahoo.com'],
            // ['user.name+newsletter', 'yahoo.com', 'username', 'yahoo.com'],
            // ['user.name+work', 'yahoo.com', 'username', 'yahoo.com'],
            // ['user.name+personal', 'yahoo.com', 'username', 'yahoo.com'],
            // ['user.name+test123', 'yahoo.com', 'username', 'yahoo.com'],
            // ['user.name+anything', 'yahoo.com', 'username', 'yahoo.com'],
            // ['user.name+verylongtag', 'yahoo.com', 'username', 'yahoo.com'],
            // ['user.name+tag.with.dots', 'yahoo.com', 'username', 'yahoo.com'],
            // ['user.name+tag-with-hyphens', 'yahoo.com', 'username', 'yahoo.com'],
            // ['user.name+tag_with_underscores', 'yahoo.com', 'username', 'yahoo.com'],
            // ['user.name+tag123', 'yahoo.com', 'username', 'yahoo.com'],
            // // Hyphen removal
            // ['user-name', 'yahoo.com', 'username', 'yahoo.com'],
            // ['user-name+tag', 'yahoo.com', 'username', 'yahoo.com'],
            // ['user-name+spam', 'yahoo.com', 'username', 'yahoo.com'],
            // ['user-name+newsletter', 'yahoo.com', 'username', 'yahoo.com'],
            // ['user-name+work', 'yahoo.com', 'username', 'yahoo.com'],
            // ['user-name+personal', 'yahoo.com', 'username', 'yahoo.com'],
            // ['user-name+test123', 'yahoo.com', 'username', 'yahoo.com'],
            // ['user-name+anything', 'yahoo.com', 'username', 'yahoo.com'],
            // ['user-name+verylongtag', 'yahoo.com', 'username', 'yahoo.com'],
            // ['user-name+tag.with.dots', 'yahoo.com', 'username', 'yahoo.com'],
            // ['user-name+tag-with-hyphens', 'yahoo.com', 'username', 'yahoo.com'],
            // ['user-name+tag_with_underscores', 'yahoo.com', 'username', 'yahoo.com'],
            // ['user-name+tag123', 'yahoo.com', 'username', 'yahoo.com'],
            // // Multiple hyphens
            // ['u-s-e-r-n-a-m-e', 'yahoo.com', 'username', 'yahoo.com'],
            // ['u-s-e-r-n-a-m-e+tag', 'yahoo.com', 'username', 'yahoo.com'],
            // // Other Yahoo domains
            // ['user.name+tag', 'yahoo.co.uk', 'username', 'yahoo.com'],
            // ['user.name+tag', 'yahoo.ca', 'username', 'yahoo.com'],
            // ['user.name+tag', 'ymail.com', 'username', 'yahoo.com'],
            // ['user.name+tag', 'rocketmail.com', 'username', 'yahoo.com'],
            // // Edge cases
            // ['user+', 'yahoo.com', 'user', 'yahoo.com'],
            // ['user-', 'yahoo.com', 'user', 'yahoo.com'],
            // Dots and hyphens are preserved for Yahoo
            ['user.name', 'yahoo.com', 'user.name', 'yahoo.com'],
            ['user-name', 'yahoo.com', 'user-name', 'yahoo.com'],
            ['u.s.e.r.n.a.m.e', 'yahoo.com', 'u.s.e.r.n.a.m.e', 'yahoo.com'],
            ['u-s-e-r-n-a-m-e', 'yahoo.com', 'u-s-e-r-n-a-m-e', 'yahoo.com'],
            ['user.', 'yahoo.com', 'user.', 'yahoo.com'],
            ['.user', 'yahoo.com', '.user', 'yahoo.com'],
            // Other Yahoo domains
            ['user.name', 'yahoo.co.uk', 'user.name', 'yahoo.com'],
            ['user.name', 'yahoo.ca', 'user.name', 'yahoo.com'],
            ['user.name', 'ymail.com', 'user.name', 'yahoo.com'],
            ['user.name', 'rocketmail.com', 'user.name', 'yahoo.com'],
        ];

        foreach ($testCases as [$inputLocal, $inputDomain, $expectedLocal, $expectedDomain]) {
            $result = $this->provider->normalize($inputLocal, $inputDomain);
            $this->assertEquals($expectedLocal, $result['local'], "Failed for local: {$inputLocal}@{$inputDomain}");
            $this->assertEquals($expectedDomain, $result['domain'], "Failed for domain: {$inputLocal}@{$inputDomain}");
        }
    }

    public function test_get_canonical_domain(): void
    {
        $this->assertEquals('yahoo.com', $this->provider->getCanonicalDomain());
    }

    public function test_get_supported_domains(): void
    {
        $domains = $this->provider->getSupportedDomains();
        $expected = ['yahoo.com', 'yahoo.co.uk', 'yahoo.ca', 'ymail.com', 'rocketmail.com'];
        $this->assertEquals($expected, $domains);
    }
}
