# Local Test Suite

This folder contains local unit and integration-style tests for the dashboard and mock API.

## What is tested

- **API generation** using `enterprise_b_api/generator.php`
- **API retrieval** using `enterprise_b_api/index.php`
- **Table data filling** to confirm nested `details` are flattened correctly
- **Azure Blob upload and retrieve** when Azure config is available

## Prerequisites

- PHP CLI installed
- `curl` extension enabled for PHP
- Optional: Azure Blob settings configured in `dashboard/settings.json` for the Azure test

## Run tests

From the repository root:

```bash
bash ./tests/run_tests.sh
```

If Azure is not configured, the Azure test will be skipped automatically.

## Test files

- `test_api_generation.php` — verifies output shape and status distribution
- `test_api_retrieval.php` — fetches the mock API payload and validates it
- `test_table_info_filling.php` — checks that details become table columns
- `test_azure_blob.php` — uploads a temporary JSON blob and then retrieves it
