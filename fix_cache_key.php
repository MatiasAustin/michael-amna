<?php
use Illuminate\Support\Facades\DB;

try {
    echo "Attempting to fix cache key length...\n";
    DB::statement('ALTER TABLE `cache` MODIFY `key` VARCHAR(255)');
    echo "SUCCESS: Cache key column expanded to 255 chars.\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
