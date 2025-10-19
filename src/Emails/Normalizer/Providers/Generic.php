<?php

namespace Utopia\Emails\Normalizer\Providers;

use Utopia\Emails\Normalizer\Provider;

/**
 * Generic
 *
 * Handles generic email normalization for unsupported providers
 * - Only removes plus addressing
 * - Preserves all other characters
 */
class Generic extends Provider
{
    public function supports(string $domain): bool
    {
        // Generic provider supports all domains
        return true;
    }

    public function normalize(string $local, string $domain): array
    {
        // Convert to lowercase
        $normalizedLocal = $this->toLowerCase($local);

        // Check if there's plus addressing
        $hasPlus = strpos($normalizedLocal, '+') !== false && strpos($normalizedLocal, '+') > 0;

        // Remove plus addressing (everything after +)
        $normalizedLocal = $this->removePlusAddressing($normalizedLocal);

        // Remove dots and hyphens only if there was plus addressing (generic providers treat these as aliases only with plus)
        if ($hasPlus) {
            $normalizedLocal = $this->removeDots($normalizedLocal);
            $normalizedLocal = $this->removeHyphens($normalizedLocal);
        }

        return [
            'local' => $normalizedLocal,
            'domain' => $domain,
        ];
    }

    public function getCanonicalDomain(): string
    {
        // Generic provider doesn't have a canonical domain
        return '';
    }

    public function getSupportedDomains(): array
    {
        // Generic provider supports all domains
        return [];
    }
}
