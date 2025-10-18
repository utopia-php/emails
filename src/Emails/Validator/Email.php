<?php

namespace Utopia\Emails\Validator;

use Utopia\Emails\Email as EmailParser;
use Utopia\Validator;

/**
 * Email
 *
 * Validate that a value is a valid email address
 */
class Email extends Validator
{
    /**
     * Get Description
     *
     * Returns validator description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return 'Value must be a valid email address';
    }

    /**
     * Is valid
     *
     * Validation will pass when $value is a valid email address
     *
     * @param  mixed $value
     * @return bool
     */
    public function isValid($value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        try {
            $email = new EmailParser($value);
            return $email->isValid();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Is array
     *
     * Function will return true if object is array.
     *
     * @return bool
     */
    public function isArray(): bool
    {
        return false;
    }

    /**
     * Get Type
     *
     * Returns validator type.
     *
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE_STRING;
    }
}
