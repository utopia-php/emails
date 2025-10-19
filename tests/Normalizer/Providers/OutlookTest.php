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
use Utopia\Emails\Normalizer\Providers\Outlook;

class OutlookTest extends TestCase
{
    private Outlook $provider;

    protected function setUp(): void
    {
        $this->provider = new Outlook;
    }

    public function test_supports(): void
    {
        $this->assertTrue($this->provider->supports('outlook.com'));
        $this->assertTrue($this->provider->supports('hotmail.com'));
        $this->assertTrue($this->provider->supports('live.com'));
        $this->assertTrue($this->provider->supports('outlook.co.uk'));
        $this->assertTrue($this->provider->supports('hotmail.co.uk'));
        $this->assertTrue($this->provider->supports('live.co.uk'));
        $this->assertFalse($this->provider->supports('gmail.com'));
        $this->assertFalse($this->provider->supports('yahoo.com'));
        $this->assertFalse($this->provider->supports('example.com'));
    }

    public function test_normalize(): void
    {
        $testCases = [
            ['user.name+tag', 'outlook.com', 'user.name', 'outlook.com'],
            ['user.name+spam', 'outlook.com', 'user.name', 'outlook.com'],
            ['user.name+newsletter', 'outlook.com', 'user.name', 'outlook.com'],
            ['user.name+work', 'outlook.com', 'user.name', 'outlook.com'],
            ['user.name+personal', 'outlook.com', 'user.name', 'outlook.com'],
            ['user.name+test123', 'outlook.com', 'user.name', 'outlook.com'],
            ['user.name+anything', 'outlook.com', 'user.name', 'outlook.com'],
            ['user.name+verylongtag', 'outlook.com', 'user.name', 'outlook.com'],
            ['user.name+tag.with.dots', 'outlook.com', 'user.name', 'outlook.com'],
            ['user.name+tag-with-hyphens', 'outlook.com', 'user.name', 'outlook.com'],
            ['user.name+tag_with_underscores', 'outlook.com', 'user.name', 'outlook.com'],
            ['user.name+tag123', 'outlook.com', 'user.name', 'outlook.com'],
            ['u.s.e.r.n.a.m.e', 'outlook.com', 'u.s.e.r.n.a.m.e', 'outlook.com'],
            ['u.s.e.r.n.a.m.e+tag', 'outlook.com', 'u.s.e.r.n.a.m.e', 'outlook.com'],
            ['user+', 'outlook.com', 'user', 'outlook.com'],
            ['user.', 'outlook.com', 'user.', 'outlook.com'],
            ['.user', 'outlook.com', '.user', 'outlook.com'],
            // Hotmail
            ['user.name+tag', 'hotmail.com', 'user.name', 'outlook.com'],
            ['user.name+spam', 'hotmail.com', 'user.name', 'outlook.com'],
            ['user.name', 'hotmail.com', 'user.name', 'outlook.com'],
            // Live
            ['user.name+tag', 'live.com', 'user.name', 'outlook.com'],
            ['user.name+spam', 'live.com', 'user.name', 'outlook.com'],
            ['user.name', 'live.com', 'user.name', 'outlook.com'],
            // UK variants
            ['user.name+tag', 'outlook.co.uk', 'user.name', 'outlook.com'],
            ['user.name+tag', 'hotmail.co.uk', 'user.name', 'outlook.com'],
            ['user.name+tag', 'live.co.uk', 'user.name', 'outlook.com'],
        ];

        foreach ($testCases as [$inputLocal, $inputDomain, $expectedLocal, $expectedDomain]) {
            $result = $this->provider->normalize($inputLocal, $inputDomain);
            $this->assertEquals($expectedLocal, $result['local'], "Failed for local: {$inputLocal}@{$inputDomain}");
            $this->assertEquals($expectedDomain, $result['domain'], "Failed for domain: {$inputLocal}@{$inputDomain}");
        }
    }

    public function test_get_canonical_domain(): void
    {
        $this->assertEquals('outlook.com', $this->provider->getCanonicalDomain());
    }

    public function test_get_supported_domains(): void
    {
        $domains = $this->provider->getSupportedDomains();
        $expected = ['outlook.com', 'hotmail.com', 'live.com', 'outlook.co.uk', 'hotmail.co.uk', 'live.co.uk'];
        $this->assertEquals($expected, $domains);
    }
}
