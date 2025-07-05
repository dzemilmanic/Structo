<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportSqlDump extends Command
{
    protected $signature = 'sql:import';
    protected $description = 'Import .sql dump file into the database';

    public function handle()
{
    $path = storage_path('app/str.sql');

    if (!file_exists($path)) {
        $this->error("❌ Fajl nije pronađen: $path");
        return;
    }

    $sql = file_get_contents($path);

    if (empty(trim($sql))) {
        $this->error("⚠️ Fajl je prazan: $path");
        return;
    }

    try {
        DB::unprepared($sql);
        $this->info("✅ SQL import uspešan iz fajla: $path");
    } catch (\Exception $e) {
        $this->error("❌ Greška pri importu: " . $e->getMessage());
    }
}

}
