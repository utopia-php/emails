<?php

namespace Utopia\Emails\Normalizer\Providers;

use Utopia\Emails\Normalizer\Provider;

/**
 * Outlook
 *
 * Handles Outlook, Hotmail, and Live email normalization
 * - TODO: Plus addressing removal commented out until manual confirmation
 * - Preserves dots in local part
 * - Normalizes to outlook.com domain
 */
class Outlook extends Provider
{
    private const SUPPORTED_DOMAINS = [
        'outlook.com', 'hotmail.com', 'live.com',
        'outlook.co.uk', 'hotmail.co.uk', 'live.co.uk',
    ];

    private const CANONICAL_DOMAIN = 'outlook.com';

    public function supports(string $domain): bool
    {
        return in_array($domain, self::SUPPORTED_DOMAINS, true);
    }

    public function normalize(string $local, string $domain): array
    {
        // Convert to lowercase
        $normalizedLocal = $this->toLowerCase($local);

        // TODO: Commented out until manual confirmation of Outlook's plus addressing support
        // Remove plus addressing (everything after +)
        // $normalizedLocal = $this->removePlusAddressing($normalizedLocal);

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
