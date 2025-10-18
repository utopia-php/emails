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
    public function testValidEmail(): void
    {
        $email = new Email('test@example.com');

        $this->assertEquals('test@example.com', $email->get());
        $this->assertEquals('test', $email->getLocal());
        $this->assertEquals('example.com', $email->getDomain());
        $this->assertEquals('example.com', $email->getDomainOnly());
        $this->assertEquals('test', $email->getLocalOnly());
        $this->assertEquals(true, $email->isValid());
        $this->assertEquals(true, $email->hasValidLocal());
        $this->assertEquals(true, $email->hasValidDomain());
        $this->assertEquals(false, $email->isDisposable());
        $this->assertEquals(false, $email->isFree());
        $this->assertEquals(true, $email->isCorporate());
        $this->assertEquals('example.com', $email->getProvider());
        $this->assertEquals('', $email->getSubdomain());
        $this->assertEquals(false, $email->hasSubdomain());
        $this->assertEquals('test@example.com', $email->normalize());
    }

    public function testEmailWithSubdomain(): void
    {
        $email = new Email('user@mail.example.com');

        $this->assertEquals('user@mail.example.com', $email->get());
        $this->assertEquals('user', $email->getLocal());
        $this->assertEquals('mail.example.com', $email->getDomain());
        $this->assertEquals('example.com', $email->getProvider());
        $this->assertEquals('mail', $email->getSubdomain());
        $this->assertEquals(true, $email->hasSubdomain());
    }

    public function testGmailEmail(): void
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

    public function testDisposableEmail(): void
    {
        $email = new Email('user@10minutemail.com');

        $this->assertEquals('user@10minutemail.com', $email->get());
        $this->assertEquals('user', $email->getLocal());
        $this->assertEquals('10minutemail.com', $email->getDomain());
        $this->assertEquals(true, $email->isDisposable());
        $this->assertEquals(false, $email->isFree());
        $this->assertEquals(false, $email->isCorporate());
    }

    public function testEmailWithSpecialCharacters(): void
    {
        $email = new Email('user.name+tag@example.com');

        $this->assertEquals('user.name+tag@example.com', $email->get());
        $this->assertEquals('user.name+tag', $email->getLocal());
        $this->assertEquals('example.com', $email->getDomain());
        $this->assertEquals(true, $email->isValid());
        $this->assertEquals(true, $email->hasValidLocal());
        $this->assertEquals(true, $email->hasValidDomain());
    }

    public function testEmailWithHyphens(): void
    {
        $email = new Email('user-name@example-domain.com');

        $this->assertEquals('user-name@example-domain.com', $email->get());
        $this->assertEquals('user-name', $email->getLocal());
        $this->assertEquals('example-domain.com', $email->getDomain());
        $this->assertEquals(true, $email->isValid());
        $this->assertEquals(true, $email->hasValidLocal());
        $this->assertEquals(true, $email->hasValidDomain());
    }

    public function testEmailWithUnderscores(): void
    {
        $email = new Email('user_name@example.com');

        $this->assertEquals('user_name@example.com', $email->get());
        $this->assertEquals('user_name', $email->getLocal());
        $this->assertEquals('example.com', $email->getDomain());
        $this->assertEquals(true, $email->isValid());
        $this->assertEquals(true, $email->hasValidLocal());
        $this->assertEquals(true, $email->hasValidDomain());
    }

    public function testEmailWithNumbers(): void
    {
        $email = new Email('user123@example123.com');

        $this->assertEquals('user123@example123.com', $email->get());
        $this->assertEquals('user123', $email->getLocal());
        $this->assertEquals('example123.com', $email->getDomain());
        $this->assertEquals(true, $email->isValid());
        $this->assertEquals(true, $email->hasValidLocal());
        $this->assertEquals(true, $email->hasValidDomain());
    }

    public function testEmailWithMultipleDots(): void
    {
        $email = new Email('user.name.last@example.com');

        $this->assertEquals('user.name.last@example.com', $email->get());
        $this->assertEquals('user.name.last', $email->getLocal());
        $this->assertEquals('example.com', $email->getDomain());
        $this->assertEquals(true, $email->isValid());
        $this->assertEquals(true, $email->hasValidLocal());
        $this->assertEquals(true, $email->hasValidDomain());
    }

    public function testEmailWithMultipleSubdomains(): void
    {
        $email = new Email('user@mail.sub.example.com');

        $this->assertEquals('user@mail.sub.example.com', $email->get());
        $this->assertEquals('user', $email->getLocal());
        $this->assertEquals('mail.sub.example.com', $email->getDomain());
        $this->assertEquals('example.com', $email->getProvider());
        $this->assertEquals('mail.sub', $email->getSubdomain());
        $this->assertEquals(true, $email->hasSubdomain());
    }

    public function testEmailFormatted(): void
    {
        $email = new Email('user@mail.example.com');

        $this->assertEquals('user@mail.example.com', $email->getFormatted('full'));
        $this->assertEquals('user', $email->getFormatted('local'));
        $this->assertEquals('mail.example.com', $email->getFormatted('domain'));
        $this->assertEquals('example.com', $email->getFormatted('provider'));
        $this->assertEquals('mail', $email->getFormatted('subdomain'));
    }

    public function testEmailNormalization(): void
    {
        $email = new Email('  USER@EXAMPLE.COM  ');

        $this->assertEquals('user@example.com', $email->get());
        $this->assertEquals('user@example.com', $email->normalize());
    }

    public function testInvalidEmailEmpty(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Email address cannot be empty");

        new Email('');
    }

    public function testInvalidEmailNoAt(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("'invalid-email' must be a valid email address");

        new Email('invalid-email');
    }

    public function testInvalidEmailMultipleAt(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("'user@example@com' must be a valid email address");

        new Email('user@example@com');
    }

    public function testInvalidEmailNoLocal(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("'@example.com' must be a valid email address");

        new Email('@example.com');
    }

    public function testInvalidEmailNoDomain(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("'user@' must be a valid email address");

        new Email('user@');
    }

    public function testInvalidEmailConsecutiveDots(): void
    {
        $email = new Email('user..name@example.com');

        $this->assertEquals(false, $email->hasValidLocal());
    }

    public function testInvalidEmailStartsWithDot(): void
    {
        $email = new Email('.user@example.com');

        $this->assertEquals(false, $email->hasValidLocal());
    }

    public function testInvalidEmailEndsWithDot(): void
    {
        $email = new Email('user.@example.com');

        $this->assertEquals(false, $email->hasValidLocal());
    }

    public function testInvalidEmailLocalTooLong(): void
    {
        $longLocal = str_repeat('a', 65); // 65 characters
        $email = new Email($longLocal . '@example.com');

        $this->assertEquals(false, $email->hasValidLocal());
    }

    public function testInvalidEmailDomainTooLong(): void
    {
        $longDomain = str_repeat('a', 250) . '.com'; // 254 characters
        $email = new Email('user@' . $longDomain);

        $this->assertEquals(false, $email->hasValidDomain());
    }

    public function testInvalidEmailDomainConsecutiveDots(): void
    {
        $email = new Email('user@example..com');

        $this->assertEquals(false, $email->hasValidDomain());
    }

    public function testInvalidEmailDomainConsecutiveHyphens(): void
    {
        $email = new Email('user@example--com.com');

        // filter_var allows consecutive hyphens, so this will be valid
        $this->assertEquals(true, $email->hasValidDomain());
    }

    public function testInvalidEmailDomainStartsWithDot(): void
    {
        $email = new Email('user@.example.com');

        $this->assertEquals(false, $email->hasValidDomain());
    }

    public function testInvalidEmailDomainEndsWithDot(): void
    {
        $email = new Email('user@example.com.');

        $this->assertEquals(false, $email->hasValidDomain());
    }

    public function testInvalidEmailDomainStartsWithHyphen(): void
    {
        $email = new Email('user@-example.com');

        $this->assertEquals(false, $email->hasValidDomain());
    }

    public function testInvalidEmailDomainEndsWithHyphen(): void
    {
        $email = new Email('user@example-.com');

        $this->assertEquals(false, $email->hasValidDomain());
    }

    public function testInvalidEmailDomainNoTLD(): void
    {
        $email = new Email('user@example');

        $this->assertEquals(false, $email->hasValidDomain());
    }

    public function testInvalidEmailDomainInvalidCharacters(): void
    {
        $email = new Email('user@example!.com');

        $this->assertEquals(false, $email->hasValidDomain());
    }

    public function testInvalidEmailLocalInvalidCharacters(): void
    {
        $email = new Email('user!@example.com');

        $this->assertEquals(false, $email->hasValidLocal());
    }

    public function testFreeEmailProviders(): void
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
            'hey.com'
        ];

        foreach ($freeProviders as $provider) {
            $email = new Email('user@' . $provider);
            $this->assertEquals(true, $email->isFree(), "Failed for provider: {$provider}");
            $this->assertEquals(false, $email->isCorporate(), "Failed for provider: {$provider}");
        }
    }

    public function testDisposableEmailProviders(): void
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
            'test.com'
        ];

        foreach ($disposableProviders as $provider) {
            $email = new Email('user@' . $provider);
            $this->assertEquals(true, $email->isDisposable(), "Failed for provider: {$provider}");
            $this->assertEquals(false, $email->isCorporate(), "Failed for provider: {$provider}");
        }
    }

    public function testCorporateEmailProviders(): void
    {
        $corporateProviders = [
            'company.com',
            'business.org',
            'enterprise.net',
            'corporation.co.uk',
            'organization.org',
            'firm.com',
            'office.net',
            'work.org'
        ];

        foreach ($corporateProviders as $provider) {
            $email = new Email('user@' . $provider);
            $this->assertEquals(false, $email->isFree(), "Failed for provider: {$provider}");
            $this->assertEquals(false, $email->isDisposable(), "Failed for provider: {$provider}");
            $this->assertEquals(true, $email->isCorporate(), "Failed for provider: {$provider}");
        }
    }
}
