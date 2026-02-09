<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ConvertFilterUrls extends Command
{
    protected $signature = 'filters:convert-urls {--dry-run : Show changes without applying}';
    protected $description = 'Convert subdomain URLs to path-based URLs in the filters table';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $domain = 'mobilekishop.net';
        $columns = ['page_url', 'url'];
        $totalUpdated = 0;

        foreach ($columns as $column) {
            // Find records with subdomain URLs (e.g., bd.mobilekishop.net, us.mobilekishop.net)
            $records = DB::table('filters')
                ->where($column, 'REGEXP', '^https?://[a-z]{2,3}\.' . preg_quote($domain))
                ->get(['id', $column]);

            $this->info("Found {$records->count()} records with subdomain URLs in '{$column}'");

            foreach ($records as $record) {
                $oldUrl = $record->$column;

                // Extract subdomain and path
                // Pattern: https://xx.mobilekishop.net/path → https://mobilekishop.net/xx/path
                $newUrl = preg_replace(
                    '#^(https?://)([a-z]{2,3})\.' . preg_quote($domain, '#') . '(/|$)#',
                    '$1' . $domain . '/$2$3',
                    $oldUrl
                );

                if ($oldUrl !== $newUrl) {
                    if ($dryRun) {
                        $this->line("  ID {$record->id}: {$oldUrl}");
                        $this->line("         → {$newUrl}");
                    } else {
                        DB::table('filters')->where('id', $record->id)->update([$column => $newUrl]);
                    }
                    $totalUpdated++;
                }
            }
        }

        if ($dryRun) {
            $this->warn("DRY RUN: {$totalUpdated} URLs would be updated. Run without --dry-run to apply.");
        } else {
            $this->info("Done! Updated {$totalUpdated} URLs.");
        }
    }
}
