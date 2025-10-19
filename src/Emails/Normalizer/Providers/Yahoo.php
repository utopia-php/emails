<?php

namespace Utopia\Emails\Normalizer\Providers;

use Utopia\Emails\Normalizer\Provider;

/**
 * Yahoo
 *
 * Handles Yahoo email normalization
 * - TODO: Plus addressing, dots, and hyphens removal commented out until manual confirmation
 * - Preserves dots and hyphens in local part
 * - Normalizes to yahoo.com domain
 */
class Yahoo extends Provider
{
    private const SUPPORTED_DOMAINS = [
        'yahoo.com', 'yahoo.co.uk', 'yahoo.ca',
        'ymail.com', 'rocketmail.com',
    ];

    private const CANONICAL_DOMAIN = 'yahoo.com';

    public function supports(string $domain): bool
    {
        return in_array($domain, self::SUPPORTED_DOMAINS, true);
    }

    public function normalize(string $local, string $domain): array
    {
        // Convert to lowercase
        $normalizedLocal = $this->toLowerCase($local);

        // TODO: Commented out until manual confirmation of Yahoo's plus addressing, dots, and hyphens support
        // Check if there's plus addressing
        // $hasPlus = strpos($normalizedLocal, '+') !== false && strpos($normalizedLocal, '+') > 0;

        // Remove plus addressing (everything after +)
        // $normalizedLocal = $this->removePlusAddressing($normalizedLocal);

        // Remove dots only if there was plus addressing (Yahoo treats dots as aliases only with plus)
        // if ($hasPlus) {
        //     $normalizedLocal = $this->removeDots($normalizedLocal);
        // }

        // Remove hyphens (Yahoo treats hyphens as aliases)
        // $normalizedLocal = $this->removeHyphens($normalizedLocal);

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
