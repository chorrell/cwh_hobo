#!/usr/bin/env php
<?php
/**
 * Simple test script for cwh_hobo plugin
 * 
 * Run this from command line to verify basic functionality:
 * php test_plugin.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "cwh_hobo Plugin Test Suite\n";
echo "===========================\n\n";

// Mock Textpattern functions that the plugin uses
if (!function_exists('lAtts')) {
    function lAtts($defaults, $atts) {
        return array_merge($defaults, (array)$atts);
    }
}

if (!function_exists('doTag')) {
    function doTag($content, $tag, $class = '') {
        $class_attr = $class ? ' class="' . $class . '"' : '';
        return "<{$tag}{$class_attr}>{$content}</{$tag}>";
    }
}

// Load the plugin
require_once __DIR__ . '/cwh_hobo.php';

$tests_passed = 0;
$tests_failed = 0;

function test($name, $condition, $message = '') {
    global $tests_passed, $tests_failed;
    
    if ($condition) {
        echo "✓ PASS: {$name}\n";
        $tests_passed++;
    } else {
        echo "✗ FAIL: {$name}";
        if ($message) echo " - {$message}";
        echo "\n";
        $tests_failed++;
    }
}

// Test 1: Function exists
test(
    "Function cwh_hobo exists",
    function_exists('cwh_hobo'),
    "Main plugin function not found"
);

// Test 2: Basic call returns string
$result = cwh_hobo([]);
test(
    "Returns string",
    is_string($result),
    "Expected string, got " . gettype($result)
);

// Test 3: Contains number format
test(
    "Output contains '#NUM:' format",
    preg_match('/#\d+:/', $result),
    "Output: {$result}"
);

// Test 4: Contains hobo name
test(
    "Output contains hobo name text",
    strlen($result) > 10,
    "Output seems too short: {$result}"
);

// Test 5: Without wraptag
$result = cwh_hobo([]);
test(
    "Without wraptag - no HTML tags",
    !preg_match('/<[^>]+>/', $result),
    "Found HTML tags when none expected"
);

// Test 6: With wraptag
$result = cwh_hobo(['wraptag' => 'p']);
test(
    "With wraptag='p' - contains <p> tags",
    preg_match('/<p>.*<\/p>/', $result),
    "Expected <p> tags, got: {$result}"
);

// Test 7: With class
$result = cwh_hobo(['wraptag' => 'div', 'class' => 'test-class']);
test(
    "With class attribute",
    preg_match('/class="test-class"/', $result),
    "Class attribute not found in: {$result}"
);

// Test 8: Multiple calls for variety
$results = [];
for ($i = 0; $i < 20; $i++) {
    $results[] = cwh_hobo([]);
}
$unique_results = array_unique($results);
test(
    "Returns varied results (randomness)",
    count($unique_results) > 1,
    "Got " . count($unique_results) . " unique results from 20 calls"
);

// Test 9: Helper function exists
test(
    "Helper function cwh_hobo_get_random exists",
    function_exists('cwh_hobo_get_random'),
    "Helper function not found"
);

// Test 10: List function exists
test(
    "List function cwh_hobo_get_list exists",
    function_exists('cwh_hobo_get_list'),
    "List function not found"
);

// Test 11: List returns 700 names
$list = cwh_hobo_get_list();
test(
    "List contains 700 hobo names",
    count($list) === 700,
    "Expected 700 names, got " . count($list)
);

// Test 12: All list items have correct format
$all_valid = true;
foreach ($list as $hobo) {
    if (!preg_match('/^#\d+:/', $hobo)) {
        $all_valid = false;
        break;
    }
}
test(
    "All hobo names have correct format",
    $all_valid,
    "Some names don't match expected format"
);

// Test 13: HTML entity encoding preserved
$sample_from_list = $list[0]; // "#1: Stewbuilder Dennis"
test(
    "HTML entities are preserved in list",
    strpos($list[11], '&#8217;') !== false, // "#12: Dan&#8217;l Dinsmore Tackadoo"
    "HTML entities may have been decoded"
);

// Test 14: Edge case - empty attributes
$result = cwh_hobo(['wraptag' => '', 'class' => '']);
test(
    "Edge case: empty attributes don't break output",
    strlen($result) > 0 && !preg_match('/</', $result),
    "Empty attributes caused issues"
);

// Performance test
echo "\nPerformance Test:\n";
$start = microtime(true);
for ($i = 0; $i < 1000; $i++) {
    cwh_hobo([]);
}
$duration = microtime(true) - $start;
$per_call = ($duration / 1000) * 1000; // milliseconds

echo "  1000 calls in " . number_format($duration, 4) . " seconds\n";
echo "  ~" . number_format($per_call, 4) . " ms per call\n";

test(
    "Performance is acceptable (< 1ms per call)",
    $per_call < 1,
    "Too slow: {$per_call}ms per call"
);

// Summary
echo "\n";
echo "===========================\n";
echo "Test Results:\n";
echo "  PASSED: {$tests_passed}\n";
echo "  FAILED: {$tests_failed}\n";
echo "  TOTAL:  " . ($tests_passed + $tests_failed) . "\n";

if ($tests_failed === 0) {
    echo "\n🎉 All tests passed!\n";
    exit(0);
} else {
    echo "\n❌ Some tests failed.\n";
    exit(1);
}
