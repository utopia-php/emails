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

namespace Utopia\Tests\Canonicals\Providers;

use PHPUnit\Framework\TestCase;
use Utopia\Emails\Canonicals\Providers\Fastmail;

class FastmailTest extends TestCase
{
    private Fastmail $provider;

    protected function setUp(): void
    {
        $this->provider = new Fastmail;
    }

    public function test_supports(): void
    {
        $this->assertTrue($this->provider->supports('fastmail.com'));
        $this->assertTrue($this->provider->supports('fastmail.fm'));
        $this->assertFalse($this->provider->supports('gmail.com'));
        $this->assertFalse($this->provider->supports('outlook.com'));
        $this->assertFalse($this->provider->supports('example.com'));
    }

    public function test_get_canonical(): void
    {
        $testCases = [
            // TODO: Commented out until manual confirmation of Fastmail's plus addressing and dots support
            // ['user.name+tag', 'fastmail.com', 'username', 'fastmail.com'],
            // ['user.name+spam', 'fastmail.com', 'username', 'fastmail.com'],
            // ['user.name+newsletter', 'fastmail.com', 'username', 'fastmail.com'],
            // ['user.name+work', 'fastmail.com', 'username', 'fastmail.com'],
            // ['user.name+personal', 'fastmail.com', 'username', 'fastmail.com'],
            // ['user.name+test123', 'fastmail.com', 'username', 'fastmail.com'],
            // ['user.name+anything', 'fastmail.com', 'username', 'fastmail.com'],
            // ['user.name+verylongtag', 'fastmail.com', 'username', 'fastmail.com'],
            // ['user.name+tag.with.dots', 'fastmail.com', 'username', 'fastmail.com'],
            // ['user.name+tag-with-hyphens', 'fastmail.com', 'username', 'fastmail.com'],
            // ['user.name+tag_with_underscores', 'fastmail.com', 'username', 'fastmail.com'],
            // ['user.name+tag123', 'fastmail.com', 'username', 'fastmail.com'],
            // // Other Fastmail domain
            // ['user.name+tag', 'fastmail.fm', 'username', 'fastmail.com'],
            // Dots are preserved for Fastmail
            ['user.name', 'fastmail.com', 'user.name', 'fastmail.com'],
            ['u.s.e.r.n.a.m.e', 'fastmail.com', 'u.s.e.r.n.a.m.e', 'fastmail.com'],
            // Edge cases
            // ['user+', 'fastmail.com', 'user', 'fastmail.com'],
            ['user.', 'fastmail.com', 'user.', 'fastmail.com'],
            ['.user', 'fastmail.com', '.user', 'fastmail.com'],
        ];

        foreach ($testCases as [$inputLocal, $inputDomain, $expectedLocal, $expectedDomain]) {
            $result = $this->provider->getCanonical($inputLocal, $inputDomain);
            $this->assertEquals($expectedLocal, $result['local'], "Failed for local: {$inputLocal}@{$inputDomain}");
            $this->assertEquals($expectedDomain, $result['domain'], "Failed for domain: {$inputLocal}@{$inputDomain}");
        }
    }

    public function test_get_canonical_domain(): void
    {
        $this->assertEquals('fastmail.com', $this->provider->getCanonicalDomain());
    }

    public function test_get_supported_domains(): void
    {
        $domains = $this->provider->getSupportedDomains();
        $expected = ['fastmail.com', 'fastmail.fm'];
        $this->assertEquals($expected, $domains);
    }
}
