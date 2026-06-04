<?php
/**
 * Test the mock API generator output.
 *
 * Covers:
 * - expected keys are present
 * - `source` is not included in the generated payload
 * - status distribution is roughly 95% OK, 4.9% Warning, 0.1% Critical
 */

require __DIR__ . '/../enterprise_b_api/generator.php';

function assertTrue($condition, $message)
{
    if (!$condition) {
        fwrite(STDERR, "FAIL: $message\n");
        exit(1);
    }
}

function runTest($name, callable $test)
{
    echo "Testing: $name... ";
    $test();
    echo "OK\n";
}

runTest('generator payload shape', function () {
    $payload = generate_data();
    assertTrue(is_array($payload), 'Payload must be an array');
    $requiredKeys = ['timestamp', 'temperature', 'pressure', 'status', 'alerts', 'details'];
    foreach ($requiredKeys as $key) {
        assertTrue(array_key_exists($key, $payload), "Missing key: $key");
    }
    assertTrue(!array_key_exists('source', $payload), 'Payload should not include a source field');
    assertTrue(is_array($payload['details']), 'details must be an array');
});

runTest('generator status distribution', function () {
    $counts = ['OK' => 0, 'Warning' => 0, 'Critical' => 0];
    $iterations = 2000;
    for ($i = 0; $i < $iterations; $i++) {
        $payload = generate_data();
        $status = $payload['status'];
        assertTrue(in_array($status, ['OK', 'Warning', 'Critical'], true), "Unknown status: $status");
        $counts[$status]++;
    }

    assertTrue($counts['OK'] >= 900, 'Too few OK statuses');
    assertTrue($counts['Warning'] <= 120, 'Too many Warning statuses');
    assertTrue($counts['Critical'] <= 30, 'Too many Critical statuses');
    echo "(distribution: OK={$counts['OK']}, Warning={$counts['Warning']}, Critical={$counts['Critical']})\n";
});

runTest('detail values are numeric or scalar', function () {
    $payload = generate_data();
    foreach ($payload['details'] as $key => $value) {
        assertTrue(is_numeric($value), "detail $key must be numeric");
    }
});

echo "Done test_api_generation.php\n";
