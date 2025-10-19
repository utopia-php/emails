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
use Utopia\Emails\Validator\Email;

class EmailTest extends TestCase
{
    public function test_validemail(): void
    {
        $validator = new Email;

        $this->assertEquals(true, $validator->isValid('test@example.com'));
        $this->assertEquals(true, $validator->isValid('user.name+tag@example.com'));
        $this->assertEquals(true, $validator->isValid('user-name@example-domain.com'));
        $this->assertEquals(true, $validator->isValid('user_name@example.com'));
        $this->assertEquals(true, $validator->isValid('user123@example123.com'));
        $this->assertEquals(true, $validator->isValid('user.name.last@example.com'));
        $this->assertEquals(true, $validator->isValid('user@mail.example.com'));
        $this->assertEquals(true, $validator->isValid('user@mail.sub.example.com'));
    }

    public function test_invalidemail(): void
    {
        $validator = new Email;

        $this->assertEquals(false, $validator->isValid(''));
        $this->assertEquals(false, $validator->isValid('invalid-email'));
        $this->assertEquals(false, $validator->isValid('user@example@com'));
        $this->assertEquals(false, $validator->isValid('@example.com'));
        $this->assertEquals(false, $validator->isValid('user@'));
        $this->assertEquals(false, $validator->isValid('user..name@example.com'));
        $this->assertEquals(false, $validator->isValid('.user@example.com'));
        $this->assertEquals(false, $validator->isValid('user.@example.com'));
        $this->assertEquals(false, $validator->isValid('user@example..com'));
        // filter_var allows consecutive hyphens, so this will be valid
        $this->assertEquals(true, $validator->isValid('user@example--com.com'));
        $this->assertEquals(false, $validator->isValid('user@.example.com'));
        $this->assertEquals(false, $validator->isValid('user@example.com.'));
        $this->assertEquals(false, $validator->isValid('user@-example.com'));
        $this->assertEquals(false, $validator->isValid('user@example-.com'));
        $this->assertEquals(false, $validator->isValid('user@example'));
        $this->assertEquals(false, $validator->isValid('user@example!.com'));
        // filter_var allows exclamation marks in local part, so this will be valid
        $this->assertEquals(true, $validator->isValid('user!@example.com'));
    }

    public function test_non_string_input(): void
    {
        $validator = new Email;

        $this->assertEquals(false, $validator->isValid(null));
        $this->assertEquals(false, $validator->isValid(123));
        $this->assertEquals(false, $validator->isValid([]));
        $this->assertEquals(false, $validator->isValid(new \stdClass));
        $this->assertEquals(false, $validator->isValid(true));
        $this->assertEquals(false, $validator->isValid(false));
    }

    public function test_validatordescription(): void
    {
        $validator = new Email;

        $this->assertEquals('Value must be a valid email address', $validator->getDescription());
    }

    public function test_validatortype(): void
    {
        $validator = new Email;

        $this->assertEquals('string', $validator->getType());
    }

    public function test_validator_is_array(): void
    {
        $validator = new Email;

        $this->assertEquals(false, $validator->isArray());
    }
}
