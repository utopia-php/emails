<?php

namespace Utopia\Emails\Canonicals\Providers;

use Utopia\Emails\Canonicals\Provider;

/**
 * ProtonMail
 *
 * Handles ProtonMail email normalization
 * - Preserves all characters in local part (no subaddress or dot removal)
 * - Normalizes to protonmail.com domain
 */
class Protonmail extends Provider
{
    private const SUPPORTED_DOMAINS = ['protonmail.com', 'proton.me', 'pm.me'];

    private const CANONICAL_DOMAIN = 'protonmail.com';

    public function supports(string $domain): bool
    {
        return in_array($domain, self::SUPPORTED_DOMAINS, true);
    }

    public function getCanonical(string $local, string $domain): array
    {
        // Convert to lowercase
        $normalizedLocal = $this->toLowerCase($local);

        // ProtonMail doesn't remove subaddresses or dots
        // Just normalize case and domain

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
