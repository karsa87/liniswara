<?php

namespace App\Console\Commands;

use App\Models\Area;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BackfillAreaOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:backfill-area {--filename= : File name in directory storage}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for backfill area in order';

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
        $orders = Order::whereIn('invoice_number', $invoiceNumbers)->get();

        $bar = $this->output->createProgressBar($data->count());
        $bar->start();
        $msg = collect();
        foreach ($data as $dataOrder) {
            $bar->advance();
            $order = $orders->firstWhere('invoice_number', $dataOrder['Nomor Invoice']);
            if (empty($order)) {
                $msg->push("Invoice Number not found {$dataOrder['Nomor Invoice']}");

                continue;
            }

            if ($order->area_id) {
                $msg->push("Invoice Number {$dataOrder['Nomor Invoice']} already have area");

                continue;
            }

            $area = $areas->firstWhere('name', $dataOrder['Area']);
            if (empty($area)) {
                $msg->push("Area not found {$dataOrder['Area']} - {$dataOrder['Nomor Invoice']}");

                continue;
            }

            $order->area_id = $area->id;
            $order->save();
        }
        $bar->finish();

        if ($msg->count() > 0) {
            foreach ($msg as $m) {
                $this->warn($m);
            }
        }
    }
}
