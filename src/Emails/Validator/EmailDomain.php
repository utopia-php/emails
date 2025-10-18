<?php

namespace Utopia\Emails\Validator;

use Utopia\Emails\Email;
use Utopia\Validator;

/**
 * EmailDomain
 *
 * Validate that an email address has a valid domain
 */
class EmailDomain extends Validator
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
        return 'Value must be a valid email address with a valid domain';
    }

    /**
     * Is valid
     *
     * Validation will pass when $value is a valid email address with a valid domain
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
            $email = new Email($value);
            return $email->isValid() && $email->hasValidDomain();
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

