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

namespace Utopia\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Utopia\Emails\Validator\EmailNotDisposable;

class EmailNotDisposableTest extends TestCase
{
    public function test_valid_non_disposable_email(): void
    {
        $validator = new EmailNotDisposable;

        $this->assertEquals(true, $validator->isValid('test@company.org'));
        $this->assertEquals(true, $validator->isValid('user@gmail.com'));
        $this->assertEquals(true, $validator->isValid('user@yahoo.com'));
        $this->assertEquals(true, $validator->isValid('user@company.com'));
        $this->assertEquals(true, $validator->isValid('user@business.org'));
    }

    public function test_invalid_disposable_email(): void
    {
        $validator = new EmailNotDisposable;

        $this->assertEquals(false, $validator->isValid('user@10minutemail.com'));
        $this->assertEquals(false, $validator->isValid('user@tempmail.org'));
        $this->assertEquals(false, $validator->isValid('user@guerrillamail.com'));
        $this->assertEquals(false, $validator->isValid('user@mailinator.com'));
        $this->assertEquals(false, $validator->isValid('user@yopmail.com'));
        $this->assertEquals(false, $validator->isValid('user@temp-mail.org'));
        $this->assertEquals(false, $validator->isValid('user@throwaway.email'));
        $this->assertEquals(false, $validator->isValid('user@getnada.com'));
        $this->assertEquals(false, $validator->isValid('user@maildrop.cc'));
        $this->assertEquals(false, $validator->isValid('user@sharklasers.com'));
        $this->assertEquals(false, $validator->isValid('user@test.com'));
        // company.org is not disposable
        $this->assertEquals(true, $validator->isValid('user@company.org'));
        $this->assertEquals(true, $validator->isValid('user@business.org'));
        $this->assertEquals(true, $validator->isValid('user@enterprise.net'));
    }

    public function test_invalid_email_format(): void
    {
        $validator = new EmailNotDisposable;

        $this->assertEquals(false, $validator->isValid(''));
        $this->assertEquals(false, $validator->isValid('invalid-email'));
        $this->assertEquals(false, $validator->isValid('user@example@com'));
        $this->assertEquals(false, $validator->isValid('@example.com'));
        $this->assertEquals(false, $validator->isValid('user@'));
    }

    public function test_non_string_input(): void
    {
        $validator = new EmailNotDisposable;

        $this->assertEquals(false, $validator->isValid(null));
        $this->assertEquals(false, $validator->isValid(123));
        $this->assertEquals(false, $validator->isValid([]));
        $this->assertEquals(false, $validator->isValid(new \stdClass));
        $this->assertEquals(false, $validator->isValid(true));
        $this->assertEquals(false, $validator->isValid(false));
    }

    public function test_validator_description(): void
    {
        $validator = new EmailNotDisposable;

        $this->assertEquals('Value must be a valid email address that is not from a disposable email service', $validator->getDescription());
    }

    public function test_validator_type(): void
    {
        $validator = new EmailNotDisposable;

        $this->assertEquals('string', $validator->getType());
    }

    public function test_validator_is_array(): void
    {
        $validator = new EmailNotDisposable;

        $this->assertEquals(false, $validator->isArray());
    }
}
