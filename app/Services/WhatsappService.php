<?php

namespace App\Services;

use App\Enums\SettingKeyEnum;
use App\Models\Order;
use App\Models\Preorder;
use App\Models\Setting;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WhatsappService
{
    /**
     * Send Invoice preorder message to WA
     *
     * @param  string  $to to numeric
     * @param  \App\Models\Preorder  $order order
     *
     * **/
    public function sentCreatedPreorderMessage($to, Preorder $preorder): void
    {
        if (! config('myapp.send_notif_wa')) {
            return;
        }

        $key = Str::ulid();
        Cache::put('user-invoice:'.$key, Crypt::encrypt($preorder->id));

        $message = sprintf(
            '*PESANAN DIBUAT* <br>'
            .'Hallo %s,  <br>'
            .'Pesanan anda dengan no faktur *%s* diproses untuk pengiriman. '
            .'untuk pesanan %s '
            .'*DOWNLOAD FAKTUR*  <br>'
            .'%s  <br>'
            .'_(Pesan ini otomatis dikirim oleh sistem)_ ', $preorder->customer_address->name, $preorder->invoice_number, html_entity_decode($preorder->notes), route('customer.po_invoice', [
                'encrypt' => $key,
            ])
        );

        $params = [
            'phone' => $to,
            'message' => $message,
        ];

        $url = config('myapp.whatsapp.url');
        if (empty($url)) {
            Log::stack(['whatsapp'])->warning('API URL whatsapp not setting');
            throw new Exception("Whatsapp hasn't token", 1);
        }

        $response = Http::withToken(
            $this->getToken(),
            ''
        )->post(
            $url.'/send-message',
            $params
        );

        try {
            $response->throw()->json();

            Log::stack(['whatsapp'])->info('Success send notif WA create preorder : '.$preorder->invoice_number);
        } catch (\Throwable $th) {
            Log::stack(['whatsapp'])->error($th->getMessage(), $params);
            // throw new Exception($th->getMessage(), 1);
        }
    }

    /**
     * Send Invoice preorder message to WA
     *
     * @param  string  $to to numeric
     * @param  \App\Models\Order  $order order
     *
     * **/
    public function sentMigrationMessage($to, Order $order): void
    {
        if (! config('myapp.send_notif_wa')) {
            return;
        }

        $key = Str::ulid();
        Cache::put('user-invoice:'.$key, Crypt::encrypt($order->id));

        $message = sprintf(
            '*PESANAN DIPROSES UNTUK PENGIRIMAN* <br>'
            .'Hallo %s,  <br>'
            .'Pesanan anda dengan no faktur *%s* diproses untuk pengiriman. '
            .'untuk pesanan %s '
            .'*DOWNLOAD FAKTUR*  <br>'
            .'%s  <br>'
            .'_(Pesan ini otomatis dikirim oleh sistem)_ ', $order->customer_address->name, $order->invoice_number, html_entity_decode($order->notes), route('customer.po_order', [
                'encrypt' => $key,
            ])
        );

        $params = [
            'phone' => $to,
            'message' => $message,
        ];

        $url = config('myapp.whatsapp.url');
        if (empty($url)) {
            Log::stack(['whatsapp'])->warning('API URL whatsapp not setting');
            throw new Exception("Whatsapp hasn't token", 1);
        }

        $response = Http::withToken(
            $this->getToken(),
            ''
        )->post(
            $url.'/send-message',
            $params
        );

        try {
            $response->throw()->json();

            Log::stack(['whatsapp'])->info('Success send notif WA migrasi ready : '.$order->invoice_number);
        } catch (\Throwable $th) {
            Log::stack(['whatsapp'])->error($th->getMessage(), $params);
            // throw new Exception($th->getMessage(), 1);
        }
    }

    /**
     * Send Invoice message to WA
     *
     * @param  string  $to to numeric
     * @param  \App\Models\Order  $order order
     *
     * **/
    public function sentInvoiceResiMessage($to, Order $order): void
    {
        if (! config('myapp.send_notif_wa')) {
            return;
        }

        $key = Str::ulid();
        Cache::put('user-invoice:'.$key, Crypt::encrypt($order->id));

        $message = sprintf(
            '*RESI DI KIRIM* <br>'
            .'Hallo %s,  <br>'
            .'Pesanan anda dengan no faktur *%s* telah dikirim dengan resi: %s melalui %s. '
            .'untuk pesanan %s '
            .'*DOWNLOAD FAKTUR*  <br>'
            .'%s  <br>'
            .'_(Pesan ini otomatis dikirim oleh sistem)_ ', $order->customer_address->name, $order->invoice_number, $order->shipping->resi, $order->shipping->expedition->name, html_entity_decode($order->notes), route('customer.po_order', [
                'encrypt' => $key,
            ])
        );

        $params = [
            'phone' => $to,
            'message' => $message,
        ];

        $url = config('myapp.whatsapp.url');
        if (empty($url)) {
            Log::stack(['whatsapp'])->warning('API URL whatsapp not setting');
            throw new Exception("Whatsapp hasn't token", 1);
        }

        $response = Http::withToken(
            $this->getToken(),
            ''
        )->post(
            $url.'/send-message',
            $params
        );

        try {
            $response->throw()->json();

            Log::stack(['whatsapp'])->info('Success send notif WA send resi : '.$order->invoice_number);
        } catch (\Throwable $th) {
            Log::stack(['whatsapp'])->error($th->getMessage(), $params);
            // throw new Exception($th->getMessage(), 1);
        }
    }

    private function getToken()
    {
        $setting = Setting::where('key', SettingKeyEnum::WHATSAPP_API_KEY)->first();
        if (empty(optional($setting)->value)) {
            Log::stack(['whatsapp'])->warning("Whatsapp hasn't token");
            // throw new Exception("Whatsapp hasn't token", 1);
        }

        return $setting->value;
    }
}
