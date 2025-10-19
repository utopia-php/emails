<?php

namespace Utopia\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Utopia\Emails\Validator\EmailLocal;

class EmailLocalTest extends TestCase
{
    public function test_valid_email_local(): void
    {
        $validator = new EmailLocal;

        $this->assertEquals(true, $validator->isValid('test@example.com'));
        $this->assertEquals(true, $validator->isValid('user.name+tag@example.com'));
        $this->assertEquals(true, $validator->isValid('user-name@example.com'));
        $this->assertEquals(true, $validator->isValid('user_name@example.com'));
        $this->assertEquals(true, $validator->isValid('user123@example.com'));
        $this->assertEquals(true, $validator->isValid('user.name.last@example.com'));
    }

    public function test_invalid_email_local(): void
    {
        $validator = new EmailLocal;

        $this->assertEquals(false, $validator->isValid(''));
        $this->assertEquals(false, $validator->isValid('invalid-email'));
        $this->assertEquals(false, $validator->isValid('user..name@example.com'));
        $this->assertEquals(false, $validator->isValid('.user@example.com'));
        $this->assertEquals(false, $validator->isValid('user.@example.com'));
        $this->assertEquals(false, $validator->isValid('user!@example.com'));
    }

    public function test_non_string_input(): void
    {
        $validator = new EmailLocal;

        $this->assertEquals(false, $validator->isValid(null));
        $this->assertEquals(false, $validator->isValid(123));
        $this->assertEquals(false, $validator->isValid([]));
        $this->assertEquals(false, $validator->isValid(new \stdClass));
        $this->assertEquals(false, $validator->isValid(true));
        $this->assertEquals(false, $validator->isValid(false));
    }

    public function test_validatordescription(): void
    {
        $validator = new EmailLocal;

        $this->assertEquals('Value must be a valid email address with a valid local part', $validator->getDescription());
    }

    public function test_validatortype(): void
    {
        $validator = new EmailLocal;

        $this->assertEquals('string', $validator->getType());
    }

    public function test_validator_is_array(): void
    {
        $validator = new EmailLocal;

        $this->assertEquals(false, $validator->isArray());
    }
}
