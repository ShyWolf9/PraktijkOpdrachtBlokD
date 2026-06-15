<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lp;
use App\Models\LpImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class MigrateCoverImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:cover_images {--dry-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate legacy cover_image values into lp_images (copies files into storage/app/public/lps)';

    public function handle()
    {
        $dry = $this->option('dry-run');
        $this->info('Searching for LPs with legacy cover_image...');

        $lps = Lp::whereNotNull('cover_image')->where('cover_image', '!=', '')->get();
        $total = $lps->count();
        $this->info("Found {$total} LP(s) with legacy cover_image.");
        $migrated = 0;

        foreach ($lps as $lp) {
            $this->line("Processing LP id={$lp->id} cover_image={$lp->cover_image}");

            // If lp already has lp_images, skip
            if ($lp->images()->exists()) {
                $this->info(" - already has images, skipping");
                continue;
            }

            $candidates = [
                storage_path('app/public/' . $lp->cover_image),
                storage_path('app/' . $lp->cover_image),
                public_path($lp->cover_image),
            ];

            $found = null;
            foreach ($candidates as $path) {
                if (file_exists($path)) {
                    $found = $path;
                    break;
                }
            }

            if (!$found) {
                $this->warn(" - file not found on disk for LP {$lp->id}, skipping");
                continue;
            }

            $filename = basename($found);
            $targetDir = 'lps';
            $targetPath = $targetDir . '/' . $filename;

            if ($dry) {
                $this->info(" - dry-run: would copy {$found} -> {$targetPath} and create lp_images record");
                $migrated++;
                continue;
            }

            try {
                // If file already present in storage 'public' under targetPath, don't copy
                if (!Storage::disk('public')->exists($targetPath)) {
                    Storage::disk('public')->putFileAs($targetDir, new File($found), $filename);
                    $this->info(" - copied to storage: {$targetPath}");
                } else {
                    $this->info(" - file already exists in storage: {$targetPath}");
                }

                // create lp_images record
                $lp->images()->create(['path' => $targetPath]);
                $this->info(" - created lp_images record for LP {$lp->id}");
                $migrated++;
            } catch (\Exception $e) {
                $this->error(' - error migrating LP ' . $lp->id . ': ' . $e->getMessage());
            }
        }

        $this->info("Completed. Migrated: {$migrated} LP(s).");
        return 0;
    }
}
