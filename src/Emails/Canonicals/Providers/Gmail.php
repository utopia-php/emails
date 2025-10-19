<?php

namespace Utopia\Emails\Canonicals\Providers;

use Utopia\Emails\Canonicals\Provider;

/**
 * Gmail
 *
 * Handles Gmail and Googlemail email normalization
 * - Removes all dots from local part
 * - Removes plus addressing
 * - Normalizes to gmail.com domain
 */
class Gmail extends Provider
{
    private const SUPPORTED_DOMAINS = ['gmail.com', 'googlemail.com'];

    private const CANONICAL_DOMAIN = 'gmail.com';

    public function supports(string $domain): bool
    {
        return in_array($domain, self::SUPPORTED_DOMAINS, true);
    }

    public function getCanonical(string $local, string $domain): array
    {
        // Convert to lowercase
        $normalizedLocal = $this->toLowerCase($local);

        // Remove all dots from local part
        $normalizedLocal = $this->removeDots($normalizedLocal);

        // Remove plus addressing (everything after +)
        $normalizedLocal = $this->removePlusAddressing($normalizedLocal);

        return [
            'local' => $normalizedLocal,
            'domain' => self::CANONICAL_DOMAIN,
        ];
    }

    public function getCanonicalDomain(): string
    {
        return self::CANONICAL_DOMAIN;
    }

    public function getSupportedDomains(): array
    {
        return self::SUPPORTED_DOMAINS;
    }
}
