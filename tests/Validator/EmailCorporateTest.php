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
use Utopia\Emails\Validator\EmailCorporate;

class EmailCorporateTest extends TestCase
{
    public function test_valid_corporate_email(): void
    {
        $validator = new EmailCorporate;

        $this->assertEquals(true, $validator->isValid('test@company.com'));
        $this->assertEquals(true, $validator->isValid('user@business.org'));
        $this->assertEquals(true, $validator->isValid('user@enterprise.net'));
        $this->assertEquals(true, $validator->isValid('user@corporation.co.uk'));
        $this->assertEquals(true, $validator->isValid('user@organization.org'));
        $this->assertEquals(true, $validator->isValid('user@firm.com'));
        $this->assertEquals(true, $validator->isValid('user@office.net'));
        $this->assertEquals(true, $validator->isValid('user@work.org'));
    }

    public function test_invalid_free_email(): void
    {
        $validator = new EmailCorporate;

        $this->assertEquals(false, $validator->isValid('user@gmail.com'));
        $this->assertEquals(false, $validator->isValid('user@yahoo.com'));
        $this->assertEquals(false, $validator->isValid('user@hotmail.com'));
        $this->assertEquals(false, $validator->isValid('user@outlook.com'));
        $this->assertEquals(false, $validator->isValid('user@live.com'));
        $this->assertEquals(false, $validator->isValid('user@aol.com'));
        $this->assertEquals(false, $validator->isValid('user@icloud.com'));
        $this->assertEquals(false, $validator->isValid('user@protonmail.com'));
        $this->assertEquals(false, $validator->isValid('user@zoho.com'));
        $this->assertEquals(false, $validator->isValid('user@yandex.com'));
        $this->assertEquals(false, $validator->isValid('user@mail.com'));
        $this->assertEquals(false, $validator->isValid('user@gmx.com'));
        $this->assertEquals(false, $validator->isValid('user@web.de'));
        $this->assertEquals(false, $validator->isValid('user@tutanota.com'));
        $this->assertEquals(false, $validator->isValid('user@fastmail.com'));
        $this->assertEquals(false, $validator->isValid('user@hey.com'));
    }

    public function test_invalid_disposable_email(): void
    {
        $validator = new EmailCorporate;

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
        // company.org is corporate
        $this->assertEquals(true, $validator->isValid('user@company.org'));
        $this->assertEquals(true, $validator->isValid('user@business.org'));
        $this->assertEquals(true, $validator->isValid('user@enterprise.net'));
    }

    public function test_invalid_email_format(): void
    {
        $validator = new EmailCorporate;

        $this->assertEquals(false, $validator->isValid(''));
        $this->assertEquals(false, $validator->isValid('invalid-email'));
        $this->assertEquals(false, $validator->isValid('user@example@com'));
        $this->assertEquals(false, $validator->isValid('@example.com'));
        $this->assertEquals(false, $validator->isValid('user@'));
    }

    public function test_non_string_input(): void
    {
        $validator = new EmailCorporate;

        $this->assertEquals(false, $validator->isValid(null));
        $this->assertEquals(false, $validator->isValid(123));
        $this->assertEquals(false, $validator->isValid([]));
        $this->assertEquals(false, $validator->isValid(new \stdClass));
        $this->assertEquals(false, $validator->isValid(true));
        $this->assertEquals(false, $validator->isValid(false));
    }

    public function test_validator_description(): void
    {
        $validator = new EmailCorporate;

        $this->assertEquals('Value must be a valid email address from a corporate domain', $validator->getDescription());
    }

    public function test_validator_type(): void
    {
        $validator = new EmailCorporate;

        $this->assertEquals('string', $validator->getType());
    }

    public function test_validator_is_array(): void
    {
        $validator = new EmailCorporate;

        $this->assertEquals(false, $validator->isArray());
    }
}
