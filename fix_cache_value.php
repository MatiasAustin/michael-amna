<?php
use Illuminate\Support\Facades\DB;

try {
    echo "Attempting to fix cache VALUE column length...\n";
    DB::statement('ALTER TABLE `cache` MODIFY `value` MEDIUMTEXT');
    echo "SUCCESS: Cache value column expanded to MEDIUMTEXT.\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
