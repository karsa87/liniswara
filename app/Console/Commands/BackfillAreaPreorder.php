<?php

namespace App\Console\Commands;

use App\Models\Area;
use App\Models\Preorder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BackfillAreaPreorder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'preorder:backfill-area {--filename= : File name in directory storage}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for backfill area in preorder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filename = $this->option('filename');
        if (empty($filename)) {
            $this->warn('Input filename!!!');

            return;
        }

        $path = "backfill/{$filename}";
        $storage = Storage::disk('system');

        if (! $storage->exists($path)) {
            $this->warn("File {$path} not found");

            return;
        }

        $fileContents = File::get($storage->path($path));

        // 1. Split by new line. Use the PHP_EOL constant for cross-platform compatibility.
        $lines = explode(PHP_EOL, $fileContents);

        // 2. Extract the header and convert it into a Laravel collection.
        $header = collect(str_getcsv(array_shift($lines)));

        // 3. Convert the rows into a Laravel collection.
        $rows = collect($lines)->filter();

        // 4. Map through the rows and combine them with the header to produce the final collection.
        $data = $rows->map(fn ($row) => $header->combine(str_getcsv($row)));

        $invoiceNumbers = $data->pluck('Nomor Invoice')->unique();
        $areaNames = $data->pluck('Area')->unique();

        $areas = Area::whereIn('name', $areaNames)->get();
        $preorders = Preorder::whereIn('invoice_number', $invoiceNumbers)->get();

        $bar = $this->output->createProgressBar($data->count());
        $bar->start();
        $msg = collect();
        foreach ($data as $dataPreorder) {
            $bar->advance();
            $preorder = $preorders->firstWhere('invoice_number', $dataPreorder['Nomor Invoice']);
            if (empty($preorder)) {
                $msg->push("Invoice Number not found {$dataPreorder['Nomor Invoice']}");

                continue;
            }

            if ($preorder->area_id) {
                $msg->push("Invoice Number {$dataPreorder['Nomor Invoice']} already have area");

                continue;
            }

            $area = $areas->firstWhere('name', $dataPreorder['Area']);
            if (empty($area)) {
                $msg->push("Area not found {$dataPreorder['Area']} - {$dataPreorder['Nomor Invoice']}");

                continue;
            }

            $preorder->area_id = $area->id;
            $preorder->save();
        }
        $bar->finish();

        if ($msg->count() > 0) {
            foreach ($msg as $m) {
                $this->warn($m);
            }
        }
    }
}
