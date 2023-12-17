<?php

namespace App\Services;

use App\Enums\SettingKeyEnum;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TrackExpeditionService
{
    /**
     * Create log history stock in of product
     *
     * @param  string  $courier courier expedition
     * @param  string  $resi No resi from expedition
     * **/
    public function track(
        string $courier,
        string $resi
    ): ?array {
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

        $response = Http::get(
            sprintf(
                '%s/%s',
                $url,
                'v1/track'
            ),
            [
                'api_key' => $setting->value,
                'courier' => $courier,
                'awb' => $resi,
            ]
        );

        try {
            return $response->throw()->json();
        } catch (\Throwable $th) {
            Log::stack('expedition')->error($th->getMessage());
        }

        return null;
    }
}
