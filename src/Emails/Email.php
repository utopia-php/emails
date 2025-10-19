<?php

namespace Utopia\Emails;

use Exception;
use Utopia\Emails\Canonicals\Provider;
use Utopia\Emails\Canonicals\Providers\Fastmail;
use Utopia\Emails\Canonicals\Providers\Generic;
use Utopia\Emails\Canonicals\Providers\Gmail;
use Utopia\Emails\Canonicals\Providers\Icloud;
use Utopia\Emails\Canonicals\Providers\Outlook;
use Utopia\Emails\Canonicals\Providers\Protonmail;
use Utopia\Emails\Canonicals\Providers\Yahoo;

class Email
{
    /**
     * Maximum length for local part (before @)
     */
    public const LOCAL_MAX_LENGTH = 64;

    /**
     * Maximum length for domain part (after @)
     */
    public const DOMAIN_MAX_LENGTH = 253;

    /**
     * Email format constants
     */
    public const FORMAT_FULL = 'full';

    public const FORMAT_LOCAL = 'local';

    public const FORMAT_DOMAIN = 'domain';

    public const FORMAT_PROVIDER = 'provider';

    public const FORMAT_SUBDOMAIN = 'subdomain';

    /**
     * Email address
     *
     * @var string
     */
    protected $email = '';

    /**
     * Local part (before @)
     *
     * @var string
     */
    protected $local = '';

    /**
     * Domain part (after @)
     *
     * @var string
     */
    protected $domain = '';

    /**
     * Email parts
     *
     * @var array
     */
    protected $parts = [];

    /**
     * Free email domains cache
     *
     * @var array|null
     */
    protected static $freeDomains = null;

    /**
     * Disposable email domains cache
     *
     * @var array|null
     */
    protected static $disposableDomains = null;

    /**
     * Email providers
     *
     * @var Provider[]
     */
    protected static $providers = null;

    /**
     * Email constructor.
     */
    public function __construct(string $email)
    {
        $this->email = \mb_strtolower(\trim($email));

        if (empty($this->email)) {
            throw new Exception('Email address cannot be empty');
        }

        $this->parts = \explode('@', $this->email);

        if (count($this->parts) !== 2) {
            throw new Exception("'{$email}' must be a valid email address");
        }

        $this->local = $this->parts[0];
        $this->domain = $this->parts[1];

        if (empty($this->local) || empty($this->domain)) {
            throw new Exception("'{$email}' must be a valid email address");
        }
    }

    /**
     * Return full email address
     */
    public function get(): string
    {
        return $this->email;
    }

    /**
     * Return local part (before @)
     */
    public function getLocal(): string
    {
        return $this->local;
    }

    /**
     * Return domain part (after @)
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * Check if email is valid format
     */
    public function isValid(): bool
    {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Check if email has valid local part
     */
    public function hasValidLocal(): bool
    {
        // Check local part length
        if (mb_strlen($this->local) > self::LOCAL_MAX_LENGTH) {
            return false;
        }

        // Check for valid characters in local part
        if (! preg_match('/^[a-zA-Z0-9._+-]+$/', $this->local)) {
            return false;
        }

        // Check for consecutive dots
        if (strpos($this->local, '..') !== false) {
            return false;
        }

        // Check if starts or ends with dot
        if (str_starts_with($this->local, '.') || str_ends_with($this->local, '.')) {
            return false;
        }

        return true;
    }

    /**
     * Check if email has valid domain part
     */
    public function hasValidDomain(): bool
    {
        // Check domain part length
        if (mb_strlen($this->domain) > self::DOMAIN_MAX_LENGTH) {
            return false;
        }

        // Check for valid domain format using filter_var
        if (! filter_var('test@'.$this->domain, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    /**
     * Check if email is from a disposable email service
     */
    public function isDisposable(): bool
    {
        if (self::$disposableDomains === null) {
            self::$disposableDomains = include __DIR__.'/../../data/disposable-domains.php';
        }

        return in_array($this->domain, self::$disposableDomains);
    }

    /**
     * Check if email is from a free email service
     */
    public function isFree(): bool
    {
        if (self::$freeDomains === null) {
            self::$freeDomains = include __DIR__.'/../../data/free-domains.php';
        }

        // If domain is both free and disposable, prioritize disposable classification
        if (in_array($this->domain, self::$freeDomains) && $this->isDisposable()) {
            return false; // It's disposable, not free
        }

        return in_array($this->domain, self::$freeDomains);
    }

    /**
     * Check if email is from a corporate domain
     */
    public function isCorporate(): bool
    {
        // If domain is both free and disposable, prioritize free classification
        if ($this->isFree() && $this->isDisposable()) {
            return false; // It's free, not corporate
        }

        return ! $this->isFree() && ! $this->isDisposable();
    }

    /**
     * Get email provider (domain without subdomain)
     */
    public function getProvider(): string
    {
        $domainParts = explode('.', $this->domain);

        if (count($domainParts) < 2) {
            return $this->domain;
        }

        // For domains like mail.company.com, return company.com
        if (count($domainParts) > 2) {
            return implode('.', array_slice($domainParts, -2));
        }

        return $this->domain;
    }

    /**
     * Get email subdomain (if any)
     */
    public function getSubdomain(): string
    {
        $domainParts = explode('.', $this->domain);

        if (count($domainParts) <= 2) {
            return '';
        }

        return implode('.', array_slice($domainParts, 0, -2));
    }

    /**
     * Check if email has subdomain
     */
    public function hasSubdomain(): bool
    {
        return ! empty($this->getSubdomain());
    }

    /**
     * Get the email address (as provided, just lowercased and trimmed)
     */
    public function getAddress(): string
    {
        return $this->email;
    }

    /**
     * Get the canonical email address by removing aliases and provider-specific variations
     * This method removes plus addressing, dot notation (for Gmail), and other aliasing techniques
     * to return the canonical form of the email address
     */
    public function getCanonical(): string
    {
        $provider = $this->getProviderForDomain($this->domain);
        $canonical = $provider->getCanonical($this->local, $this->domain);

        return $canonical['local'].'@'.$canonical['domain'];
    }

    /**
     * Check if the email domain is supported for canonical form generation
     */
    public function isCanonicalSupported(): bool
    {
        return $this->isDomainSupported($this->domain);
    }

    /**
     * Get the canonical domain for this email
     */
    public function getCanonicalDomain(): ?string
    {
        $provider = $this->getProviderForDomain($this->domain);

        // Only return canonical domain if it's not the generic provider
        if (! $provider instanceof Generic) {
            return $provider->getCanonicalDomain();
        }

        return null;
    }

    /**
     * Get the appropriate provider for a given domain
     */
    protected function getProviderForDomain(string $domain): Provider
    {
        if (self::$providers === null) {
            self::$providers = [
                new Gmail,
                new Outlook,
                new Yahoo,
                new Icloud,
                new Protonmail,
                new Fastmail,
            ];
        }

        foreach (self::$providers as $provider) {
            if ($provider->supports($domain)) {
                return $provider;
            }
        }

        // Return generic provider if no specific provider found
        return new Generic;
    }

    /**
     * Check if a domain is supported by any provider
     */
    protected function isDomainSupported(string $domain): bool
    {
        if (self::$providers === null) {
            self::$providers = [
                new Gmail,
                new Outlook,
                new Yahoo,
                new Icloud,
                new Protonmail,
                new Fastmail,
            ];
        }

        foreach (self::$providers as $provider) {
            if ($provider->supports($domain)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get email in different formats
     */
    public function getFormatted(string $format = self::FORMAT_FULL): string
    {
        switch ($format) {
            case self::FORMAT_LOCAL:
                return $this->local;
            case self::FORMAT_DOMAIN:
                return $this->domain;
            case self::FORMAT_PROVIDER:
                return $this->getProvider();
            case self::FORMAT_SUBDOMAIN:
                return $this->getSubdomain();
            case self::FORMAT_FULL:
            default:
                return $this->email;
        }
    }
}
