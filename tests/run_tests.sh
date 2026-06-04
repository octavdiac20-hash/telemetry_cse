#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")"

echo "Running dashboard test suite..."
php -d display_errors=1 test_api_generation.php
php -d display_errors=1 test_api_retrieval.php
php -d display_errors=1 test_table_info_filling.php
php -d display_errors=1 test_azure_blob.php

echo "\nAll tests completed."
