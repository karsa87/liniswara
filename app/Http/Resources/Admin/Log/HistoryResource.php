<?php

namespace App\Http\Resources\Admin\Log;

use App\Enums\LogHistoryEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = null;
        if ($this->user) {
            $user = [
                'name' => $this->user->name,
                'phone_number' => $this->user->phone_number,
                'email' => $this->user->email,
            ];
        }

        return [
            'id' => $this->id,
            'datetime' => Carbon::parse($this->log_datetime)->locale('id')->format('l, j F Y H:i'),
            'user' => $user,
            'information' => $this->information,
            'source' => $this->source,
            'transaction_type' => LogHistoryEnum::MAP_LABEL[$this->transaction_type] ?? '',
            'data_before' => $this->data_before,
            'data_after' => $this->data_after,
            'data_change' => $this->data_change,
        ];
    }
}
