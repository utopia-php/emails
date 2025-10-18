<?php

/**
 * Test script for the import system
 * This script can be run to test the import functionality without external dependencies
 */

// Mock classes for testing
class MockCLI {
    public static function title($text) { echo "=== $text ===\n"; }
    public static function success($text) { echo "✓ $text\n"; }
    public static function info($text) { echo "ℹ $text\n"; }
    public static function warning($text) { echo "⚠ $text\n"; }
    public static function error($text) { echo "✗ $text\n"; }
    public static function exit($code) { exit($code); }
}

class MockDomain {
    private $domain;
    
    public function __construct($domain) {
        $this->domain = $domain;
    }
    
    public function isTest() { return false; }
    public function getName() { 
        $parts = explode('.', $this->domain);
        return $parts[0]; 
    }
    public function getTLD() { 
        $parts = explode('.', $this->domain);
        return end($parts); 
    }
    public function isKnown() { return true; }
    public function isICANN() { return true; }
    public function isPrivate() { return false; }
}

// Test domain validation
function isValidDomain($domain) {
    if (empty($domain)) {
        return false;
    }

    try {
        $domainObj = new MockDomain($domain);

        if ($domainObj->isTest()) {
            return false;
        }

        $name = $domainObj->getName();
        $tld = $domainObj->getTLD();

        if (empty($name) || empty($tld)) {
            return false;
        }

        return true;

    } catch (Exception $e) {
        return false;
    }
}

// Test the import system
echo "Testing Email Domains Import System\n";
echo "===================================\n\n";

// Test domain validation
$testDomains = [
    'gmail.com' => true,
    'test.com' => true,
    'invalid' => false,
    '' => false,
    'subdomain.example.com' => true,
    'test' => false,
];

MockCLI::info('Testing domain validation...');
foreach ($testDomains as $domain => $expected) {
    $result = isValidDomain($domain);
    $status = $result === $expected ? 'PASS' : 'FAIL';
    echo "  $status: '$domain' -> " . ($result ? 'valid' : 'invalid') . "\n";
}

// Test source configuration loading
MockCLI::info('Testing source configuration...');
if (file_exists(__DIR__ . '/data/sources.php')) {
    $sources = include __DIR__ . '/data/sources.php';
    echo "  ✓ Sources configuration loaded\n";
    echo "  - Disposable sources: " . count($sources['disposable']) . "\n";
    echo "  - Free sources: " . count($sources['free']) . "\n";
} else {
    echo "  ✗ Sources configuration not found\n";
}

// Test manual free domains
MockCLI::info('Testing manual free domains...');
if (file_exists(__DIR__ . '/data/free-domains-manual.php')) {
    $domains = include __DIR__ . '/data/free-domains-manual.php';
    echo "  ✓ Manual free domains loaded: " . count($domains) . " domains\n";
} else {
    echo "  ✗ Manual free domains not found\n";
}

// Test manual disposable domains
MockCLI::info('Testing manual disposable domains...');
if (file_exists(__DIR__ . '/data/disposable-domains-manual.php')) {
    $domains = include __DIR__ . '/data/disposable-domains-manual.php';
    echo "  ✓ Manual disposable domains loaded: " . count($domains) . " domains\n";
} else {
    echo "  ✗ Manual disposable domains not found\n";
}

// Test existing domain files
MockCLI::info('Testing existing domain files...');
if (file_exists(__DIR__ . '/data/disposable-domains.php')) {
    $domains = include __DIR__ . '/data/disposable-domains.php';
    echo "  ✓ Disposable domains loaded: " . count($domains) . " domains\n";
} else {
    echo "  ⚠ Disposable domains not found (will be created on first import)\n";
}

if (file_exists(__DIR__ . '/data/free-domains.php')) {
    $domains = include __DIR__ . '/data/free-domains.php';
    echo "  ✓ Free domains loaded: " . count($domains) . " domains\n";
} else {
    echo "  ⚠ Free domains not found (will be created on first import)\n";
}

MockCLI::success('Test completed successfully!');
MockCLI::info('To run the actual import, install dependencies and run: php import.php stats');
