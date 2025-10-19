<?php

namespace Utopia\Emails\Normalizer\Providers;

use Utopia\Emails\Normalizer\Provider;

/**
 * ProtonMail
 *
 * Handles ProtonMail email normalization
 * - Removes plus addressing
 * - Preserves dots and hyphens in local part
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

    public function normalize(string $local, string $domain): array
    {
        // Convert to lowercase
        $normalizedLocal = $this->toLowerCase($local);

        // Check if there's plus addressing
        $hasPlus = strpos($normalizedLocal, '+') !== false && strpos($normalizedLocal, '+') > 0;

        // Remove plus addressing (everything after +)
        $normalizedLocal = $this->removePlusAddressing($normalizedLocal);

        // Remove dots only if there was plus addressing (ProtonMail treats dots as aliases only with plus)
        if ($hasPlus) {
            $normalizedLocal = $this->removeDots($normalizedLocal);
        }

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
