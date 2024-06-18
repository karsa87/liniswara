<?php

namespace App\Http\Resources\Admin\Log;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImportResource extends JsonResource
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

        $logs = [];
        if ($this->logs) {
            foreach ($this->logs as $log) {
                $logs[] = [
                    'description' => $log->description,
                    'reason' => $log->reason,
                ];
            }
        }

        return [
            'id' => $this->id,
            'date' => Carbon::parse($this->created_at)->isoFormat('dddd, D MMMM Y H:m'),
            'user' => $user,
            'name' => $this->name,
            'total_failed' => $this->total_failed,
            'total_success' => $this->total_success,
            'total_record' => $this->total_record,
            'status' => $this->status,
            'logs' => $logs,
        ];
    }
}
