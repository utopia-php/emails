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
use Utopia\Emails\Validator\EmailLocal;

class EmailLocalTest extends TestCase
{
    public function testValidEmailLocal(): void
    {
        $validator = new EmailLocal();

        $this->assertEquals(true, $validator->isValid('test@example.com'));
        $this->assertEquals(true, $validator->isValid('user.name+tag@example.com'));
        $this->assertEquals(true, $validator->isValid('user-name@example.com'));
        $this->assertEquals(true, $validator->isValid('user_name@example.com'));
        $this->assertEquals(true, $validator->isValid('user123@example.com'));
        $this->assertEquals(true, $validator->isValid('user.name.last@example.com'));
    }

    public function testInvalidEmailLocal(): void
    {
        $validator = new EmailLocal();

        $this->assertEquals(false, $validator->isValid(''));
        $this->assertEquals(false, $validator->isValid('invalid-email'));
        $this->assertEquals(false, $validator->isValid('user..name@example.com'));
        $this->assertEquals(false, $validator->isValid('.user@example.com'));
        $this->assertEquals(false, $validator->isValid('user.@example.com'));
        $this->assertEquals(false, $validator->isValid('user!@example.com'));
    }

    public function testNonStringInput(): void
    {
        $validator = new EmailLocal();

        $this->assertEquals(false, $validator->isValid(null));
        $this->assertEquals(false, $validator->isValid(123));
        $this->assertEquals(false, $validator->isValid([]));
        $this->assertEquals(false, $validator->isValid(new \stdClass()));
        $this->assertEquals(false, $validator->isValid(true));
        $this->assertEquals(false, $validator->isValid(false));
    }

    public function testValidatorDescription(): void
    {
        $validator = new EmailLocal();

        $this->assertEquals('Value must be a valid email address with a valid local part', $validator->getDescription());
    }

    public function testValidatorType(): void
    {
        $validator = new EmailLocal();

        $this->assertEquals('string', $validator->getType());
    }

    public function testValidatorIsArray(): void
    {
        $validator = new EmailLocal();

        $this->assertEquals(false, $validator->isArray());
    }
}

