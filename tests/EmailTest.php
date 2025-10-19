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

namespace Utopia\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Utopia\Emails\Email;

class EmailTest extends TestCase
{
    public function test_valid_email(): void
    {
        $email = new Email('test@company.org');

        $this->assertEquals('test@company.org', $email->get());
        $this->assertEquals('test', $email->getLocal());
        $this->assertEquals('company.org', $email->getDomain());
        $this->assertEquals('company.org', $email->getDomain());
        $this->assertEquals('test', $email->getLocal());
        $this->assertEquals(true, $email->isValid());
        $this->assertEquals(true, $email->hasValidLocal());
        $this->assertEquals(true, $email->hasValidDomain());
        $this->assertEquals(false, $email->isDisposable());
        $this->assertEquals(false, $email->isFree());
        $this->assertEquals(true, $email->isCorporate());
        $this->assertEquals('company.org', $email->getProvider());
        $this->assertEquals('', $email->getSubdomain());
        $this->assertEquals(false, $email->hasSubdomain());
        $this->assertEquals('test@company.org', $email->getAddress());
    }

    public function test_email_with_subdomain(): void
    {
        $email = new Email('user@mail.company.org');

        $this->assertEquals('user@mail.company.org', $email->get());
        $this->assertEquals('user', $email->getLocal());
        $this->assertEquals('mail.company.org', $email->getDomain());
        $this->assertEquals('company.org', $email->getProvider());
        $this->assertEquals('mail', $email->getSubdomain());
        $this->assertEquals(true, $email->hasSubdomain());
    }

    public function test_gmail_email(): void
    {
        $email = new Email('user@gmail.com');

        $this->assertEquals('user@gmail.com', $email->get());
        $this->assertEquals('user', $email->getLocal());
        $this->assertEquals('gmail.com', $email->getDomain());
        $this->assertEquals(false, $email->isDisposable());
        $this->assertEquals(true, $email->isFree());
        $this->assertEquals(false, $email->isCorporate());
        $this->assertEquals('gmail.com', $email->getProvider());
    }

    public function test_disposable_email(): void
    {
        $email = new Email('user@10minutemail.com');

        $this->assertEquals('user@10minutemail.com', $email->get());
        $this->assertEquals('user', $email->getLocal());
        $this->assertEquals('10minutemail.com', $email->getDomain());
        $this->assertEquals(true, $email->isDisposable());
        $this->assertEquals(false, $email->isFree());
        $this->assertEquals(false, $email->isCorporate());
    }

    public function test_email_with_special_characters(): void
    {
        $email = new Email('user.name+tag@company.org');

        $this->assertEquals('user.name+tag@company.org', $email->get());
        $this->assertEquals('user.name+tag', $email->getLocal());
        $this->assertEquals('company.org', $email->getDomain());
        $this->assertEquals(true, $email->isValid());
        $this->assertEquals(true, $email->hasValidLocal());
        $this->assertEquals(true, $email->hasValidDomain());
    }

    public function test_email_with_hyphens(): void
    {
        $email = new Email('user-name@example-domain.com');

        $this->assertEquals('user-name@example-domain.com', $email->get());
        $this->assertEquals('user-name', $email->getLocal());
        $this->assertEquals('example-domain.com', $email->getDomain());
        $this->assertEquals(true, $email->isValid());
        $this->assertEquals(true, $email->hasValidLocal());
        $this->assertEquals(true, $email->hasValidDomain());
    }

    public function test_email_with_underscores(): void
    {
        $email = new Email('user_name@company.org');

        $this->assertEquals('user_name@company.org', $email->get());
        $this->assertEquals('user_name', $email->getLocal());
        $this->assertEquals('company.org', $email->getDomain());
        $this->assertEquals(true, $email->isValid());
        $this->assertEquals(true, $email->hasValidLocal());
        $this->assertEquals(true, $email->hasValidDomain());
    }

    public function test_email_with_numbers(): void
    {
        $email = new Email('user123@example123.com');

        $this->assertEquals('user123@example123.com', $email->get());
        $this->assertEquals('user123', $email->getLocal());
        $this->assertEquals('example123.com', $email->getDomain());
        $this->assertEquals(true, $email->isValid());
        $this->assertEquals(true, $email->hasValidLocal());
        $this->assertEquals(true, $email->hasValidDomain());
    }

    public function test_email_with_multiple_dots(): void
    {
        $email = new Email('user.name.last@company.org');

        $this->assertEquals('user.name.last@company.org', $email->get());
        $this->assertEquals('user.name.last', $email->getLocal());
        $this->assertEquals('company.org', $email->getDomain());
        $this->assertEquals(true, $email->isValid());
        $this->assertEquals(true, $email->hasValidLocal());
        $this->assertEquals(true, $email->hasValidDomain());
    }

    public function test_email_with_multiple_subdomains(): void
    {
        $email = new Email('user@mail.sub.company.org');

        $this->assertEquals('user@mail.sub.company.org', $email->get());
        $this->assertEquals('user', $email->getLocal());
        $this->assertEquals('mail.sub.company.org', $email->getDomain());
        $this->assertEquals('company.org', $email->getProvider());
        $this->assertEquals('mail.sub', $email->getSubdomain());
        $this->assertEquals(true, $email->hasSubdomain());
    }

    public function test_email_formatted(): void
    {
        $email = new Email('user@mail.company.org');

        $this->assertEquals('user@mail.company.org', $email->getFormatted('full'));
        $this->assertEquals('user', $email->getFormatted('local'));
        $this->assertEquals('mail.company.org', $email->getFormatted('domain'));
        $this->assertEquals('company.org', $email->getFormatted('provider'));
        $this->assertEquals('mail', $email->getFormatted('subdomain'));
    }

    public function test_email_normalization(): void
    {
        $email = new Email('  USER@COMPANY.ORG  ');

        $this->assertEquals('user@company.org', $email->get());
        $this->assertEquals('user@company.org', $email->getAddress());
    }

    public function test_invalid_email_empty(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Email address cannot be empty');

        new Email('');
    }

    public function test_invalid_email_no_at(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("'invalid-email' must be a valid email address");

        new Email('invalid-email');
    }

    public function test_invalid_email_multiple_at(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("'user@example@com' must be a valid email address");

        new Email('user@example@com');
    }

    public function test_invalid_email_no_local(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("'@example.com' must be a valid email address");

        new Email('@example.com');
    }

    public function test_invalid_email_no_domain(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("'user@' must be a valid email address");

        new Email('user@');
    }

    public function test_invalid_email_consecutive_dots(): void
    {
        $email = new Email('user..name@example.com');

        $this->assertEquals(false, $email->hasValidLocal());
    }

    public function test_invalid_email_starts_with_dot(): void
    {
        $email = new Email('.user@example.com');

        $this->assertEquals(false, $email->hasValidLocal());
    }

    public function test_invalid_email_ends_with_dot(): void
    {
        $email = new Email('user.@example.com');

        $this->assertEquals(false, $email->hasValidLocal());
    }

    public function test_invalid_email_local_too_long(): void
    {
        $longLocal = str_repeat('a', 65); // 65 characters
        $email = new Email($longLocal.'@example.com');

        $this->assertEquals(false, $email->hasValidLocal());
    }

    public function test_invalid_email_domain_too_long(): void
    {
        $longDomain = str_repeat('a', 250).'.com'; // 254 characters
        $email = new Email('user@'.$longDomain);

        $this->assertEquals(false, $email->hasValidDomain());
    }

    public function test_invalid_email_domain_consecutive_dots(): void
    {
        $email = new Email('user@example..com');

        $this->assertEquals(false, $email->hasValidDomain());
    }

    public function test_invalid_email_domain_consecutive_hyphens(): void
    {
        $email = new Email('user@example--com.com');

        // filter_var allows consecutive hyphens, so this will be valid
        $this->assertEquals(true, $email->hasValidDomain());
    }

    public function test_invalid_email_domain_starts_with_dot(): void
    {
        $email = new Email('user@.example.com');

        $this->assertEquals(false, $email->hasValidDomain());
    }

    public function test_invalid_email_domain_ends_with_dot(): void
    {
        $email = new Email('user@example.com.');

        $this->assertEquals(false, $email->hasValidDomain());
    }

    public function test_invalid_email_domain_starts_with_hyphen(): void
    {
        $email = new Email('user@-example.com');

        $this->assertEquals(false, $email->hasValidDomain());
    }

    public function test_invalid_email_domain_ends_with_hyphen(): void
    {
        $email = new Email('user@example-.com');

        $this->assertEquals(false, $email->hasValidDomain());
    }

    public function test_invalid_email_domain_no_tld(): void
    {
        $email = new Email('user@example');

        $this->assertEquals(false, $email->hasValidDomain());
    }

    public function test_invalid_email_domain_invalid_characters(): void
    {
        $email = new Email('user@example!.com');

        $this->assertEquals(false, $email->hasValidDomain());
    }

    public function test_invalid_email_local_invalid_characters(): void
    {
        $email = new Email('user!@example.com');

        $this->assertEquals(false, $email->hasValidLocal());
    }

    public function test_free_email_providers(): void
    {
        $freeProviders = [
            'gmail.com',
            'yahoo.com',
            'hotmail.com',
            'outlook.com',
            'live.com',
            'aol.com',
            'icloud.com',
            'protonmail.com',
            'zoho.com',
            'yandex.com',
            'mail.com',
            'gmx.com',
            'web.de',
            'tutanota.com',
            'fastmail.com',
            'hey.com',
        ];

        foreach ($freeProviders as $provider) {
            $email = new Email('user@'.$provider);
            $this->assertEquals(true, $email->isFree(), "Failed for provider: {$provider}");
            $this->assertEquals(false, $email->isCorporate(), "Failed for provider: {$provider}");
        }
    }

    public function test_disposable_email_providers(): void
    {
        $disposableProviders = [
            '10minutemail.com',
            'tempmail.org',
            'guerrillamail.com',
            'mailinator.com',
            'yopmail.com',
            'temp-mail.org',
            'throwaway.email',
            'getnada.com',
            'maildrop.cc',
            'sharklasers.com',
            'test.com',
        ];

        foreach ($disposableProviders as $provider) {
            $email = new Email('user@'.$provider);
            $this->assertEquals(true, $email->isDisposable(), "Failed for provider: {$provider}");
            $this->assertEquals(false, $email->isCorporate(), "Failed for provider: {$provider}");
        }
    }

    public function test_corporate_email_providers(): void
    {
        $corporateProviders = [
            'company.com',
            'business.org',
            'enterprise.net',
            'corporation.co.uk',
            'organization.org',
            'firm.com',
            'office.net',
            'work.org',
        ];

        foreach ($corporateProviders as $provider) {
            $email = new Email('user@'.$provider);
            $this->assertEquals(false, $email->isFree(), "Failed for provider: {$provider}");
            $this->assertEquals(false, $email->isDisposable(), "Failed for provider: {$provider}");
            $this->assertEquals(true, $email->isCorporate(), "Failed for provider: {$provider}");
        }
    }

    public function test_get_unique_gmail_aliases(): void
    {
        $testCases = [
            // Gmail dot notation and plus addressing
            ['user.name@gmail.com', 'username@gmail.com'],
            ['user.name+tag@gmail.com', 'username@gmail.com'],
            ['user.name+spam@gmail.com', 'username@gmail.com'],
            ['user.name+newsletter@gmail.com', 'username@gmail.com'],
            ['user.name+work@gmail.com', 'username@gmail.com'],
            ['user.name+personal@gmail.com', 'username@gmail.com'],
            ['user.name+test123@gmail.com', 'username@gmail.com'],
            ['user.name+anything@gmail.com', 'username@gmail.com'],
            ['user.name+verylongtag@gmail.com', 'username@gmail.com'],
            ['user.name+tag.with.dots@gmail.com', 'username@gmail.com'],
            ['user.name+tag-with-hyphens@gmail.com', 'username@gmail.com'],
            ['user.name+tag_with_underscores@gmail.com', 'username@gmail.com'],
            ['user.name+tag123@gmail.com', 'username@gmail.com'],
            ['user.name+tag@googlemail.com', 'username@gmail.com'],
            ['user.name+tag@googlemail.com', 'username@gmail.com'],
            ['user.name+spam@googlemail.com', 'username@gmail.com'],
            ['user.name@googlemail.com', 'username@gmail.com'],
            // Multiple dots
            ['u.s.e.r.n.a.m.e@gmail.com', 'username@gmail.com'],
            ['u.s.e.r.n.a.m.e+tag@gmail.com', 'username@gmail.com'],
            // Edge cases
            ['user+@gmail.com', 'user@gmail.com'],
            ['user.@gmail.com', 'user@gmail.com'],
            ['.user@gmail.com', 'user@gmail.com'],
            ['user..name@gmail.com', 'username@gmail.com'],
        ];

        foreach ($testCases as [$input, $expected]) {
            $email = new Email($input);
            $this->assertEquals($expected, $email->getCanonical(), "Failed for input: {$input}");
        }
    }

    public function test_get_unique_outlook_aliases(): void
    {
        $testCases = [
            // TODO: Commented out until manual confirmation of Outlook's plus addressing support
            // // Outlook/Hotmail/Live plus addressing
            // ['user.name+tag@outlook.com', 'user.name@outlook.com'],
            // ['user.name+spam@outlook.com', 'user.name@outlook.com'],
            // ['user.name+newsletter@outlook.com', 'user.name@outlook.com'],
            // ['user.name+work@outlook.com', 'user.name@outlook.com'],
            // ['user.name+personal@outlook.com', 'user.name@outlook.com'],
            // ['user.name+test123@outlook.com', 'user.name@outlook.com'],
            // ['user.name+anything@outlook.com', 'user.name@outlook.com'],
            // ['user.name+verylongtag@outlook.com', 'user.name@outlook.com'],
            // ['user.name+tag.with.dots@outlook.com', 'user.name@outlook.com'],
            // ['user.name+tag-with-hyphens@outlook.com', 'user.name@outlook.com'],
            // ['user.name+tag_with_underscores@outlook.com', 'user.name@outlook.com'],
            // ['user.name+tag123@outlook.com', 'user.name@outlook.com'],
            // // Hotmail
            // ['user.name+tag@hotmail.com', 'user.name@outlook.com'],
            // ['user.name+spam@hotmail.com', 'user.name@outlook.com'],
            // ['user.name@hotmail.com', 'user.name@outlook.com'],
            // // Live
            // ['user.name+tag@live.com', 'user.name@outlook.com'],
            // ['user.name+spam@live.com', 'user.name@outlook.com'],
            // ['user.name@live.com', 'user.name@outlook.com'],
            // // UK variants
            // ['user.name+tag@outlook.co.uk', 'user.name@outlook.com'],
            // ['user.name+tag@hotmail.co.uk', 'user.name@outlook.com'],
            // ['user.name+tag@live.co.uk', 'user.name@outlook.com'],
            // Dots are preserved for Outlook
            ['user.name@outlook.com', 'user.name@outlook.com'],
            ['u.s.e.r.n.a.m.e@outlook.com', 'u.s.e.r.n.a.m.e@outlook.com'],
            // Edge cases
            // ['user+@outlook.com', 'user@outlook.com'],
            ['user.@outlook.com', 'user.@outlook.com'],
            ['.user@outlook.com', '.user@outlook.com'],
            // Hotmail
            ['user.name@hotmail.com', 'user.name@outlook.com'],
            // Live
            ['user.name@live.com', 'user.name@outlook.com'],
            // UK variants
            ['user.name@outlook.co.uk', 'user.name@outlook.com'],
            ['user.name@hotmail.co.uk', 'user.name@outlook.com'],
            ['user.name@live.co.uk', 'user.name@outlook.com'],
        ];

        foreach ($testCases as [$input, $expected]) {
            $email = new Email($input);
            $this->assertEquals($expected, $email->getCanonical(), "Failed for input: {$input}");
        }
    }

    public function test_get_unique_yahoo_aliases(): void
    {
        $testCases = [
            // TODO: Commented out until manual confirmation of Yahoo's plus addressing, dots, and hyphens support
            // // Yahoo plus addressing and hyphen removal
            // ['user.name+tag@yahoo.com', 'username@yahoo.com'],
            // ['user.name+spam@yahoo.com', 'username@yahoo.com'],
            // ['user.name+newsletter@yahoo.com', 'username@yahoo.com'],
            // ['user.name+work@yahoo.com', 'username@yahoo.com'],
            // ['user.name+personal@yahoo.com', 'username@yahoo.com'],
            // ['user.name+test123@yahoo.com', 'username@yahoo.com'],
            // ['user.name+anything@yahoo.com', 'username@yahoo.com'],
            // ['user.name+verylongtag@yahoo.com', 'username@yahoo.com'],
            // ['user.name+tag.with.dots@yahoo.com', 'username@yahoo.com'],
            // ['user.name+tag-with-hyphens@yahoo.com', 'username@yahoo.com'],
            // ['user.name+tag_with_underscores@yahoo.com', 'username@yahoo.com'],
            // ['user.name+tag123@yahoo.com', 'username@yahoo.com'],
            // // Hyphen removal
            // ['user-name@yahoo.com', 'username@yahoo.com'],
            // ['user-name+tag@yahoo.com', 'username@yahoo.com'],
            // ['user-name+spam@yahoo.com', 'username@yahoo.com'],
            // ['user-name+newsletter@yahoo.com', 'username@yahoo.com'],
            // ['user-name+work@yahoo.com', 'username@yahoo.com'],
            // ['user-name+personal@yahoo.com', 'username@yahoo.com'],
            // ['user-name+test123@yahoo.com', 'username@yahoo.com'],
            // ['user-name+anything@yahoo.com', 'username@yahoo.com'],
            // ['user-name+verylongtag@yahoo.com', 'username@yahoo.com'],
            // ['user-name+tag.with.dots@yahoo.com', 'username@yahoo.com'],
            // ['user-name+tag-with-hyphens@yahoo.com', 'username@yahoo.com'],
            // ['user-name+tag_with_underscores@yahoo.com', 'username@yahoo.com'],
            // ['user-name+tag123@yahoo.com', 'username@yahoo.com'],
            // // Multiple hyphens
            // ['u-s-e-r-n-a-m-e@yahoo.com', 'username@yahoo.com'],
            // ['u-s-e-r-n-a-m-e+tag@yahoo.com', 'username@yahoo.com'],
            // // Other Yahoo domains
            // ['user.name+tag@yahoo.co.uk', 'username@yahoo.com'],
            // ['user.name+tag@yahoo.ca', 'username@yahoo.com'],
            // ['user.name+tag@ymail.com', 'username@yahoo.com'],
            // ['user.name+tag@rocketmail.com', 'username@yahoo.com'],
            // // Edge cases
            // ['user+@yahoo.com', 'user@yahoo.com'],
            // ['user-@yahoo.com', 'user@yahoo.com'],
            // Dots and hyphens are preserved for Yahoo
            ['user.name@yahoo.com', 'user.name@yahoo.com'],
            ['user-name@yahoo.com', 'user-name@yahoo.com'],
            ['u.s.e.r.n.a.m.e@yahoo.com', 'u.s.e.r.n.a.m.e@yahoo.com'],
            ['u-s-e-r-n-a-m-e@yahoo.com', 'u-s-e-r-n-a-m-e@yahoo.com'],
            ['user.@yahoo.com', 'user.@yahoo.com'],
            ['.user@yahoo.com', '.user@yahoo.com'],
            // Other Yahoo domains
            ['user.name@yahoo.co.uk', 'user.name@yahoo.com'],
            ['user.name@yahoo.ca', 'user.name@yahoo.com'],
            ['user.name@ymail.com', 'user.name@yahoo.com'],
            ['user.name@rocketmail.com', 'user.name@yahoo.com'],
        ];

        foreach ($testCases as [$input, $expected]) {
            $email = new Email($input);
            $this->assertEquals($expected, $email->getCanonical(), "Failed for input: {$input}");
        }
    }

    public function test_get_unique_icloud_aliases(): void
    {
        $testCases = [
            // TODO: Commented out until manual confirmation of iCloud's plus addressing and dots support
            // // iCloud plus addressing
            // ['user.name+tag@icloud.com', 'username@icloud.com'],
            // ['user.name+spam@icloud.com', 'username@icloud.com'],
            // ['user.name+newsletter@icloud.com', 'username@icloud.com'],
            // ['user.name+work@icloud.com', 'username@icloud.com'],
            // ['user.name+personal@icloud.com', 'username@icloud.com'],
            // ['user.name+test123@icloud.com', 'username@icloud.com'],
            // ['user.name+anything@icloud.com', 'username@icloud.com'],
            // ['user.name+verylongtag@icloud.com', 'username@icloud.com'],
            // ['user.name+tag.with.dots@icloud.com', 'username@icloud.com'],
            // ['user.name+tag-with-hyphens@icloud.com', 'username@icloud.com'],
            // ['user.name+tag_with_underscores@icloud.com', 'username@icloud.com'],
            // ['user.name+tag123@icloud.com', 'username@icloud.com'],
            // // Other Apple domains
            // ['user.name+tag@me.com', 'username@icloud.com'],
            // ['user.name+tag@mac.com', 'username@icloud.com'],
            // Dots are preserved for iCloud
            ['user.name@icloud.com', 'user.name@icloud.com'],
            ['u.s.e.r.n.a.m.e@icloud.com', 'u.s.e.r.n.a.m.e@icloud.com'],
            // Edge cases
            // ['user+@icloud.com', 'user@icloud.com'],
            ['user.@icloud.com', 'user.@icloud.com'],
            ['.user@icloud.com', '.user@icloud.com'],
            // Other Apple domains
            ['user.name@me.com', 'user.name@icloud.com'],
            ['user.name@mac.com', 'user.name@icloud.com'],
        ];

        foreach ($testCases as [$input, $expected]) {
            $email = new Email($input);
            $this->assertEquals($expected, $email->getCanonical(), "Failed for input: {$input}");
        }
    }

    public function test_get_unique_protonmail_aliases(): void
    {
        $testCases = [
            // TODO: Commented out until manual confirmation of ProtonMail's plus addressing and dots support
            // // ProtonMail plus addressing
            // ['user.name+tag@protonmail.com', 'username@protonmail.com'],
            // ['user.name+spam@protonmail.com', 'username@protonmail.com'],
            // ['user.name+newsletter@protonmail.com', 'username@protonmail.com'],
            // ['user.name+work@protonmail.com', 'username@protonmail.com'],
            // ['user.name+personal@protonmail.com', 'username@protonmail.com'],
            // ['user.name+test123@protonmail.com', 'username@protonmail.com'],
            // ['user.name+anything@protonmail.com', 'username@protonmail.com'],
            // ['user.name+verylongtag@protonmail.com', 'username@protonmail.com'],
            // ['user.name+tag.with.dots@protonmail.com', 'username@protonmail.com'],
            // ['user.name+tag-with-hyphens@protonmail.com', 'username@protonmail.com'],
            // ['user.name+tag_with_underscores@protonmail.com', 'username@protonmail.com'],
            // ['user.name+tag123@protonmail.com', 'username@protonmail.com'],
            // // Other ProtonMail domains
            // ['user.name+tag@proton.me', 'username@protonmail.com'],
            // ['user.name+tag@pm.me', 'username@protonmail.com'],
            // Dots are preserved for ProtonMail
            ['user.name@protonmail.com', 'user.name@protonmail.com'],
            ['u.s.e.r.n.a.m.e@protonmail.com', 'u.s.e.r.n.a.m.e@protonmail.com'],
            // Edge cases
            // ['user+@protonmail.com', 'user@protonmail.com'],
            ['user.@protonmail.com', 'user.@protonmail.com'],
            ['.user@protonmail.com', '.user@protonmail.com'],
            // Other ProtonMail domains
            ['user.name@proton.me', 'user.name@protonmail.com'],
            ['user.name@pm.me', 'user.name@protonmail.com'],
        ];

        foreach ($testCases as [$input, $expected]) {
            $email = new Email($input);
            $this->assertEquals($expected, $email->getCanonical(), "Failed for input: {$input}");
        }
    }

    public function test_get_unique_fastmail_aliases(): void
    {
        $testCases = [
            // TODO: Commented out until manual confirmation of Fastmail's plus addressing and dots support
            // // Fastmail plus addressing
            // ['user.name+tag@fastmail.com', 'username@fastmail.com'],
            // ['user.name+spam@fastmail.com', 'username@fastmail.com'],
            // ['user.name+newsletter@fastmail.com', 'username@fastmail.com'],
            // ['user.name+work@fastmail.com', 'username@fastmail.com'],
            // ['user.name+personal@fastmail.com', 'username@fastmail.com'],
            // ['user.name+test123@fastmail.com', 'username@fastmail.com'],
            // ['user.name+anything@fastmail.com', 'username@fastmail.com'],
            // ['user.name+verylongtag@fastmail.com', 'username@fastmail.com'],
            // ['user.name+tag.with.dots@fastmail.com', 'username@fastmail.com'],
            // ['user.name+tag-with-hyphens@fastmail.com', 'username@fastmail.com'],
            // ['user.name+tag_with_underscores@fastmail.com', 'username@fastmail.com'],
            // ['user.name+tag123@fastmail.com', 'username@fastmail.com'],
            // // Other Fastmail domain
            // ['user.name+tag@fastmail.fm', 'username@fastmail.com'],
            // Dots are preserved for Fastmail
            ['user.name@fastmail.com', 'user.name@fastmail.com'],
            ['u.s.e.r.n.a.m.e@fastmail.com', 'u.s.e.r.n.a.m.e@fastmail.com'],
            // Edge cases
            // ['user+@fastmail.com', 'user@fastmail.com'],
            ['user.@fastmail.com', 'user.@fastmail.com'],
            ['.user@fastmail.com', '.user@fastmail.com'],
            // Other Fastmail domain
            ['user.name@fastmail.fm', 'user.name@fastmail.com'],
        ];

        foreach ($testCases as [$input, $expected]) {
            $email = new Email($input);
            $this->assertEquals($expected, $email->getCanonical(), "Failed for input: {$input}");
        }
    }

    public function test_get_unique_other_domains(): void
    {
        $testCases = [
            // TODO: Commented out until manual confirmation of generic providers' plus addressing, dots, and hyphens support
            // // Other domains with plus addressing
            // ['user.name+tag@example.com', 'username@example.com'],
            // ['user.name+spam@example.com', 'username@example.com'],
            // ['user.name+newsletter@example.com', 'username@example.com'],
            // ['user.name+work@example.com', 'username@example.com'],
            // ['user.name+personal@example.com', 'username@example.com'],
            // ['user.name+test123@example.com', 'username@example.com'],
            // ['user.name+anything@example.com', 'username@example.com'],
            // ['user.name+verylongtag@example.com', 'username@example.com'],
            // ['user.name+tag.with.dots@example.com', 'username@example.com'],
            // ['user.name+tag-with-hyphens@example.com', 'username@example.com'],
            // ['user.name+tag_with_underscores@example.com', 'username@example.com'],
            // ['user.name+tag123@example.com', 'username@example.com'],
            // Dots are preserved for other domains
            ['user.name@example.com', 'user.name@example.com'],
            ['u.s.e.r.n.a.m.e@example.com', 'u.s.e.r.n.a.m.e@example.com'],
            // Hyphens are preserved for other domains
            ['user-name@example.com', 'user-name@example.com'],
            // ['user-name+tag@example.com', 'username@example.com'],
            // Edge cases
            // ['user+@example.com', 'user@example.com'],
            ['user.@example.com', 'user.@example.com'],
            ['.user@example.com', '.user@example.com'],
        ];

        foreach ($testCases as [$input, $expected]) {
            $email = new Email($input);
            $this->assertEquals($expected, $email->getCanonical(), "Failed for input: {$input}");
        }
    }

    public function test_get_unique_edge_cases(): void
    {
        $testCases = [
            // Empty plus addressing
            ['user+@gmail.com', 'user@gmail.com'],
            // TODO: Commented out until manual confirmation of Outlook's plus addressing support
            // ['user+@outlook.com', 'user@outlook.com'],
            // TODO: Commented out until manual confirmation of non-Gmail providers' plus addressing support
            // ['user+@yahoo.com', 'user@yahoo.com'],
            // ['user+@icloud.com', 'user@icloud.com'],
            // ['user+@protonmail.com', 'user@protonmail.com'],
            // ['user+@fastmail.com', 'user@fastmail.com'],
            // ['user+@example.com', 'user@example.com'],
            // Plus at the beginning
            ['+user@gmail.com', '+user@gmail.com'],
            ['+user@outlook.com', '+user@outlook.com'],
            ['+user@yahoo.com', '+user@yahoo.com'],
            ['+user@icloud.com', '+user@icloud.com'],
            ['+user@protonmail.com', '+user@protonmail.com'],
            ['+user@fastmail.com', '+user@fastmail.com'],
            ['+user@example.com', '+user@example.com'],
            // Multiple plus signs (only first one is considered)
            ['user+tag+more@gmail.com', 'user@gmail.com'],
            // TODO: Commented out until manual confirmation of Outlook's plus addressing support
            // ['user+tag+more@outlook.com', 'user@outlook.com'],
            // TODO: Commented out until manual confirmation of non-Gmail providers' plus addressing support
            // ['user+tag+more@yahoo.com', 'user@yahoo.com'],
            // ['user+tag+more@icloud.com', 'user@icloud.com'],
            // ['user+tag+more@protonmail.com', 'user@protonmail.com'],
            // ['user+tag+more@fastmail.com', 'user@fastmail.com'],
            // ['user+tag+more@example.com', 'user@example.com'],
            // Special characters in plus addressing
            ['user+tag!@gmail.com', 'user@gmail.com'],
            ['user+tag#@gmail.com', 'user@gmail.com'],
            ['user+tag$@gmail.com', 'user@gmail.com'],
            ['user+tag%@gmail.com', 'user@gmail.com'],
            ['user+tag&@gmail.com', 'user@gmail.com'],
            ['user+tag*@gmail.com', 'user@gmail.com'],
            ['user+tag(@gmail.com', 'user@gmail.com'],
            ['user+tag)@gmail.com', 'user@gmail.com'],
            ['user+tag=@gmail.com', 'user@gmail.com'],
            ['user+tag[@gmail.com', 'user@gmail.com'],
            ['user+tag]@gmail.com', 'user@gmail.com'],
            ['user+tag{@gmail.com', 'user@gmail.com'],
            ['user+tag}@gmail.com', 'user@gmail.com'],
            ['user+tag|@gmail.com', 'user@gmail.com'],
            ['user+tag\@gmail.com', 'user@gmail.com'],
            ['user+tag/@gmail.com', 'user@gmail.com'],
            ['user+tag?@gmail.com', 'user@gmail.com'],
            ['user+tag<@gmail.com', 'user@gmail.com'],
            ['user+tag>@gmail.com', 'user@gmail.com'],
            ['user+tag,@gmail.com', 'user@gmail.com'],
            ['user+tag;@gmail.com', 'user@gmail.com'],
            ['user+tag:@gmail.com', 'user@gmail.com'],
            ['user+tag"@gmail.com', 'user@gmail.com'],
            ['user+tag\'@gmail.com', 'user@gmail.com'],
            ['user+tag~@gmail.com', 'user@gmail.com'],
            ['user+tag`@gmail.com', 'user@gmail.com'],
        ];

        foreach ($testCases as [$input, $expected]) {
            $email = new Email($input);
            $this->assertEquals($expected, $email->getCanonical(), "Failed for input: {$input}");
        }
    }

    public function test_get_unique_case_sensitivity(): void
    {
        $testCases = [
            // Case sensitivity should not matter
            ['USER.NAME+TAG@GMAIL.COM', 'username@gmail.com'],
            ['User.Name+Tag@Gmail.Com', 'username@gmail.com'],
            ['user.name+tag@Gmail.com', 'username@gmail.com'],
            // TODO: Commented out until manual confirmation of Outlook's plus addressing support
            // ['USER.NAME+TAG@OUTLOOK.COM', 'user.name@outlook.com'],
            // ['User.Name+Tag@Outlook.Com', 'user.name@outlook.com'],
            // ['user.name+tag@Outlook.com', 'user.name@outlook.com'],
            // Dots are preserved for Outlook
            ['USER.NAME@OUTLOOK.COM', 'user.name@outlook.com'],
            ['User.Name@Outlook.Com', 'user.name@outlook.com'],
            ['user.name@Outlook.com', 'user.name@outlook.com'],
            // TODO: Commented out until manual confirmation of non-Gmail providers' plus addressing and dots support
            // ['USER.NAME+TAG@YAHOO.COM', 'username@yahoo.com'],
            // ['User.Name+Tag@Yahoo.Com', 'username@yahoo.com'],
            // ['user.name+tag@Yahoo.com', 'username@yahoo.com'],
            // ['USER.NAME+TAG@ICLOUD.COM', 'username@icloud.com'],
            // ['User.Name+Tag@Icloud.Com', 'username@icloud.com'],
            // ['user.name+tag@Icloud.com', 'username@icloud.com'],
            // ['USER.NAME+TAG@PROTONMAIL.COM', 'username@protonmail.com'],
            // ['User.Name+Tag@Protonmail.Com', 'username@protonmail.com'],
            // ['user.name+tag@Protonmail.com', 'username@protonmail.com'],
            // ['USER.NAME+TAG@FASTMAIL.COM', 'username@fastmail.com'],
            // ['User.Name+Tag@Fastmail.Com', 'username@fastmail.com'],
            // ['user.name+tag@Fastmail.com', 'username@fastmail.com'],
            // ['USER.NAME+TAG@EXAMPLE.COM', 'username@example.com'],
            // ['User.Name+Tag@Example.Com', 'username@example.com'],
            // ['user.name+tag@Example.com', 'username@example.com'],
            // Dots and pluses are preserved for non-Gmail providers
            ['USER.NAME@YAHOO.COM', 'user.name@yahoo.com'],
            ['User.Name@Yahoo.Com', 'user.name@yahoo.com'],
            ['user.name@Yahoo.com', 'user.name@yahoo.com'],
            ['USER.NAME@ICLOUD.COM', 'user.name@icloud.com'],
            ['User.Name@Icloud.Com', 'user.name@icloud.com'],
            ['user.name@Icloud.com', 'user.name@icloud.com'],
            ['USER.NAME@PROTONMAIL.COM', 'user.name@protonmail.com'],
            ['User.Name@Protonmail.Com', 'user.name@protonmail.com'],
            ['user.name@Protonmail.com', 'user.name@protonmail.com'],
            ['USER.NAME@FASTMAIL.COM', 'user.name@fastmail.com'],
            ['User.Name@Fastmail.Com', 'user.name@fastmail.com'],
            ['user.name@Fastmail.com', 'user.name@fastmail.com'],
            ['USER.NAME@EXAMPLE.COM', 'user.name@example.com'],
            ['User.Name@Example.Com', 'user.name@example.com'],
            ['user.name@Example.com', 'user.name@example.com'],
        ];

        foreach ($testCases as [$input, $expected]) {
            $email = new Email($input);
            $this->assertEquals($expected, $email->getCanonical(), "Failed for input: {$input}");
        }
    }

    public function test_is_normalization_supported(): void
    {
        $supportedEmails = [
            'user@gmail.com',
            'user@googlemail.com',
            'user@outlook.com',
            'user@hotmail.com',
            'user@live.com',
            'user@outlook.co.uk',
            'user@hotmail.co.uk',
            'user@live.co.uk',
            'user@yahoo.com',
            'user@yahoo.co.uk',
            'user@yahoo.ca',
            'user@ymail.com',
            'user@rocketmail.com',
            'user@icloud.com',
            'user@me.com',
            'user@mac.com',
            'user@protonmail.com',
            'user@proton.me',
            'user@pm.me',
            'user@fastmail.com',
            'user@fastmail.fm',
        ];

        foreach ($supportedEmails as $emailAddress) {
            $email = new Email($emailAddress);
            $this->assertTrue($email->isCanonicalSupported(), "Email {$emailAddress} should support normalization");
        }

        $unsupportedEmails = [
            'user@example.com',
            'user@test.org',
            'user@company.net',
            'user@business.co.uk',
        ];

        foreach ($unsupportedEmails as $emailAddress) {
            $email = new Email($emailAddress);
            $this->assertFalse($email->isCanonicalSupported(), "Email {$emailAddress} should not support normalization");
        }
    }

    public function test_get_canonical_domain(): void
    {
        $testCases = [
            ['user@gmail.com', 'gmail.com'],
            ['user@googlemail.com', 'gmail.com'],
            ['user@outlook.com', 'outlook.com'],
            ['user@hotmail.com', 'outlook.com'],
            ['user@live.com', 'outlook.com'],
            ['user@outlook.co.uk', 'outlook.com'],
            ['user@hotmail.co.uk', 'outlook.com'],
            ['user@live.co.uk', 'outlook.com'],
            ['user@yahoo.com', 'yahoo.com'],
            ['user@yahoo.co.uk', 'yahoo.com'],
            ['user@yahoo.ca', 'yahoo.com'],
            ['user@ymail.com', 'yahoo.com'],
            ['user@rocketmail.com', 'yahoo.com'],
            ['user@icloud.com', 'icloud.com'],
            ['user@me.com', 'icloud.com'],
            ['user@mac.com', 'icloud.com'],
            ['user@protonmail.com', 'protonmail.com'],
            ['user@proton.me', 'protonmail.com'],
            ['user@pm.me', 'protonmail.com'],
            ['user@fastmail.com', 'fastmail.com'],
            ['user@fastmail.fm', 'fastmail.com'],
            ['user@example.com', null],
            ['user@test.org', null],
            ['user@company.net', null],
            ['user@business.co.uk', null],
        ];

        foreach ($testCases as [$emailAddress, $expectedCanonical]) {
            $email = new Email($emailAddress);
            $this->assertEquals($expectedCanonical, $email->getCanonicalDomain(), "Failed for email: {$emailAddress}");
        }
    }

    public function test_get_unique_with_different_providers(): void
    {
        // Test that different providers are used correctly
        $gmailEmail = new Email('user.name+tag@gmail.com');
        $this->assertEquals('username@gmail.com', $gmailEmail->getCanonical());

        // TODO: Commented out until manual confirmation of Outlook's plus addressing support
        // $outlookEmail = new Email('user.name+tag@outlook.com');
        // $this->assertEquals('user.name@outlook.com', $outlookEmail->getCanonical());

        // Dots are preserved for Outlook
        $outlookEmail = new Email('user.name@outlook.com');
        $this->assertEquals('user.name@outlook.com', $outlookEmail->getCanonical());

        // TODO: Commented out until manual confirmation of non-Gmail providers' plus addressing and dots support
        // $yahooEmail = new Email('user-name+tag@yahoo.com');
        // $this->assertEquals('username@yahoo.com', $yahooEmail->getCanonical());

        // $genericEmail = new Email('user.name+tag@example.com');
        // $this->assertEquals('username@example.com', $genericEmail->getCanonical());

        // Dots and pluses are preserved for non-Gmail providers
        $yahooEmail = new Email('user-name@yahoo.com');
        $this->assertEquals('user-name@yahoo.com', $yahooEmail->getCanonical());

        $genericEmail = new Email('user.name@example.com');
        $this->assertEquals('user.name@example.com', $genericEmail->getCanonical());
    }
}
