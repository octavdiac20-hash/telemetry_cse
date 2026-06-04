<?php
/**
 * Test that the mock API can be retrieved via the CLI entry point.
 */

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

function runPhpScript($script)
{
    $command = sprintf('php %s', escapeshellarg($script));
    exec($command, $output, $returnVar);
    assertTrue($returnVar === 0, "PHP script exited with code $returnVar");
    return implode("\n", $output);
}

runTest('mock API retrieval returns JSON', function () {
    $script = __DIR__ . '/../enterprise_b_api/index.php';
    $raw = runPhpScript($script);
    $payload = json_decode($raw, true);
    assertTrue(is_array($payload), 'API output must be valid JSON');
    assertTrue(isset($payload['timestamp']), 'Missing timestamp');
    assertTrue(isset($payload['temperature']), 'Missing temperature');
    assertTrue(isset($payload['pressure']), 'Missing pressure');
    assertTrue(isset($payload['status']), 'Missing status');
    assertTrue(isset($payload['alerts']), 'Missing alerts');
    assertTrue(!array_key_exists('source', $payload), 'Returned payload should not include source');
});

echo "Done test_api_retrieval.php\n";
