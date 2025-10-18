<?php

/**
 * Email Domain Sources Configuration
 * 
 * This file defines the sources for both disposable and free email domains.
 * You can modify this file to add, remove, or update sources.
 * 
 * Last updated: 2024-01-01 00:00:00
 */

return [
    'disposable' => [
        'manual' => [
            'name' => 'Manual Disposable Email Domains',
            'url' => null,
            'enabled' => true,
            'description' => 'Manually managed disposable email domains',
            'configFile' => 'disposable-domains-manual.php'
        ],
        'martenson' => [
            'name' => 'Martenson Disposable Email Domains',
            'url' => 'https://raw.githubusercontent.com/disposable-email-domains/disposable-email-domains/main/disposable_email_blocklist.conf',
            'enabled' => true,
            'description' => 'Comprehensive list of disposable email domains from Martenson repository'
        ],
        'disposable' => [
            'name' => 'Disposable Email Domains',
            'url' => 'https://raw.githubusercontent.com/disposable/disposable-email-domains/master/domains.txt',
            'enabled' => true,
            'description' => 'Popular disposable email domains list'
        ],
        'wesbos' => [
            'name' => 'Wes Bos Burner Email Providers',
            'url' => 'https://raw.githubusercontent.com/wesbos/burner-email-providers/refs/heads/master/emails.txt',
            'enabled' => true,
            'description' => 'Burner email providers from Wes Bos repository'
        ],
        'fakefilter' => [
            'name' => '7c FakeFilter Domains',
            'url' => 'https://raw.githubusercontent.com/7c/fakefilter/main/txt/data.txt',
            'enabled' => true,
            'description' => 'Fake email domains from 7c FakeFilter'
        ],
        'adamloving' => [
            'name' => 'Adam Loving Temporary Email Domains',
            'url' => 'https://gist.githubusercontent.com/adamloving/4401361/raw/e81212c3caecb54b87ced6392e0a0de2b6466287/temporary-email-address-domains',
            'enabled' => true,
            'description' => 'Temporary email domains from Adam Loving gist'
        ]
    ],
    'free' => [
        'manual' => [
            'name' => 'Manual Free Email Domains',
            'url' => null,
            'enabled' => true,
            'description' => 'Manually managed free email domains',
            'configFile' => 'free-domains-manual.php'
        ]
    ]
];
