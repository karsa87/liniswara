<?php

namespace App\Console\Commands;

use App\Enums\SettingKeyEnum;
use App\Models\Expedition;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncExpedition extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-expedition';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync expedition with retrieve data from Raja Ongkir';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = config('myapp.expedition.url');
        if (empty($url)) {
            Log::stack('expedition')->warning('API URL expedition not setting');
        }

        $setting = Setting::where('key', SettingKeyEnum::EXPEDITION_API_KEY)->first();
        if (
            empty($setting)
            || empty(optional($setting)->value)
        ) {
            Log::stack('expedition')->warning('Setting API key not found');
        }

        try {
            $response = Http::get(
                sprintf(
                    '%s/%s',
                    $url,
                    'v1/list_courier'
                ),
                [
                    'api_key' => $setting->value,
                ]
            );

            $couriers = $response->throw()->json();
            foreach ($couriers as $courier) {
                Expedition::updateOrCreate(
                    [
                        'courier' => $courier['code'],
                    ],
                    [
                        'courier' => $courier['code'],
                        'name' => $courier['description'],
                    ]
                );
            }
        } catch (\Throwable $th) {
            Log::stack('expedition')->error($th->getMessage());
        }
    }
}
