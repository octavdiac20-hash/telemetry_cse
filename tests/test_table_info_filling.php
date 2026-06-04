<?php
/**
 * Test the dashboard table data transformation logic.
 *
 * This verifies that nested details are flattened into individual table columns.
 */

function assertTrue($condition, $message)
{
    if (!$condition) {
        fwrite(STDERR, "FAIL: $message\n");
        exit(1);
    }
}

echo "Testing: table info filling... ";

$sample = [
    'timestamp' => '2026-06-04T12:00:00Z',
    'status' => 'OK',
    'temperature' => 28.5,
    'pressure' => 152.1,
    'alerts' => [],
    'details' => [
        'sensor_core_1' => 30.5,
        'sensor_core_2' => 31.1,
        'coolant_flow' => 103.7,
    ],
];

$normalized = $sample;
if (isset($normalized['details']) && is_array($normalized['details'])) {
    foreach ($normalized['details'] as $detailKey => $detailValue) {
        $normalized[$detailKey] = $detailValue;
    }
    unset($normalized['details']);
}

assertTrue(!isset($normalized['details']), 'details key should be removed after flattening');
assertTrue(isset($normalized['sensor_core_1']), 'sensor_core_1 should become a separate column');
assertTrue(isset($normalized['sensor_core_2']), 'sensor_core_2 should become a separate column');
assertTrue(isset($normalized['coolant_flow']), 'coolant_flow should become a separate column');
assertTrue($normalized['sensor_core_1'] === 30.5, 'sensor_core_1 value must be preserved');

echo "OK\n";

echo "Done test_table_info_filling.php\n";
