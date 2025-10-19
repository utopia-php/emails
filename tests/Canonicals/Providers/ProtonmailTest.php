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
use Utopia\Emails\Canonicals\Providers\Protonmail;

class ProtonmailTest extends TestCase
{
    private Protonmail $provider;

    protected function setUp(): void
    {
        $this->provider = new Protonmail;
    }

    public function test_supports(): void
    {
        $this->assertTrue($this->provider->supports('protonmail.com'));
        $this->assertTrue($this->provider->supports('proton.me'));
        $this->assertTrue($this->provider->supports('pm.me'));
        $this->assertFalse($this->provider->supports('gmail.com'));
        $this->assertFalse($this->provider->supports('outlook.com'));
        $this->assertFalse($this->provider->supports('example.com'));
    }

    public function test_get_canonical(): void
    {
        $testCases = [
            // TODO: Commented out until manual confirmation of ProtonMail's plus addressing and dots support
            // ['user.name+tag', 'protonmail.com', 'username', 'protonmail.com'],
            // ['user.name+spam', 'protonmail.com', 'username', 'protonmail.com'],
            // ['user.name+newsletter', 'protonmail.com', 'username', 'protonmail.com'],
            // ['user.name+work', 'protonmail.com', 'username', 'protonmail.com'],
            // ['user.name+personal', 'protonmail.com', 'username', 'protonmail.com'],
            // ['user.name+test123', 'protonmail.com', 'username', 'protonmail.com'],
            // ['user.name+anything', 'protonmail.com', 'username', 'protonmail.com'],
            // ['user.name+verylongtag', 'protonmail.com', 'username', 'protonmail.com'],
            // ['user.name+tag.with.dots', 'protonmail.com', 'username', 'protonmail.com'],
            // ['user.name+tag-with-hyphens', 'protonmail.com', 'username', 'protonmail.com'],
            // ['user.name+tag_with_underscores', 'protonmail.com', 'username', 'protonmail.com'],
            // ['user.name+tag123', 'protonmail.com', 'username', 'protonmail.com'],
            // // Other ProtonMail domains
            // ['user.name+tag', 'proton.me', 'username', 'protonmail.com'],
            // ['user.name+tag', 'pm.me', 'username', 'protonmail.com'],
            // Dots are preserved for ProtonMail
            ['user.name', 'protonmail.com', 'user.name', 'protonmail.com'],
            ['u.s.e.r.n.a.m.e', 'protonmail.com', 'u.s.e.r.n.a.m.e', 'protonmail.com'],
            // Edge cases
            // ['user+', 'protonmail.com', 'user', 'protonmail.com'],
            ['user.', 'protonmail.com', 'user.', 'protonmail.com'],
            ['.user', 'protonmail.com', '.user', 'protonmail.com'],
            // Other ProtonMail domains
            ['user.name', 'proton.me', 'user.name', 'protonmail.com'],
            ['user.name', 'pm.me', 'user.name', 'protonmail.com'],
        ];

        foreach ($testCases as [$inputLocal, $inputDomain, $expectedLocal, $expectedDomain]) {
            $result = $this->provider->getCanonical($inputLocal, $inputDomain);
            $this->assertEquals($expectedLocal, $result['local'], "Failed for local: {$inputLocal}@{$inputDomain}");
            $this->assertEquals($expectedDomain, $result['domain'], "Failed for domain: {$inputLocal}@{$inputDomain}");
        }
    }

    public function test_get_canonical_domain(): void
    {
        $this->assertEquals('protonmail.com', $this->provider->getCanonicalDomain());
    }

    public function test_get_supported_domains(): void
    {
        $domains = $this->provider->getSupportedDomains();
        $expected = ['protonmail.com', 'proton.me', 'pm.me'];
        $this->assertEquals($expected, $domains);
    }
}
