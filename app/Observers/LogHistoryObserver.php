<?php

namespace App\Observers;

use App\Enums\LogHistoryEnum;
use App\Enums\SourceLogEnum;
use App\Models\LogHistory;

class LogHistoryObserver
{
    /**
     * Handle the Models "created" event.
     *
     * @param  App\Models  $object
     * @return void
     */
    public function created($object)
    {
        if (! $object->getSkipLog() && auth()->user()) {
            $data_before = $object->getOriginal();
            $data_after = $object->toArray();
            $table = $object->getTable();
            $object_name = optional(SourceLogEnum::fromValue($table))->getLabel() ?: ucwords(str_replace('_', ' ', $table));

            $this->log_history([
                'record_id' => $object->id,
                'data_after' => $data_after,
                'data_before' => $data_before,
                'transaction_type' => LogHistoryEnum::TRANSACTION_CREATE,
                'information' => sprintf('Buat %s', $object_name),
                'record_type' => get_class($object),
                'table' => $table,
            ]);
        }
    }

    /**
     * Handle the Models "updated" event.
     *
     * @param  App\Models  $object
     * @return void
     */
    public function updated($object)
    {
        if (! $object->getSkipLog() && auth()->user()) {
            $data_before = $object->getOriginal();
            $data_after = $object->toArray();
            $table = $object->getTable();
            $object_name = optional(SourceLogEnum::fromValue($table))->getLabel() ?: ucwords(str_replace('_', ' ', $table));

            $change = $object->getChanges();
            $isDelete = array_key_exists('deleted_at', $change);

            $this->log_history([
                'record_id' => $object->id,
                'data_after' => $data_after,
                'data_before' => $data_before,
                'transaction_type' => $isDelete ? LogHistoryEnum::TRANSACTION_DELETE : LogHistoryEnum::TRANSACTION_UPDATE,
                'information' => $isDelete ? sprintf('Hapus %s', $object_name) : sprintf('Edit %s', $object_name),
                'record_type' => get_class($object),
                'data_change' => $change,
                'table' => $table,
            ]);
        }
    }

    /**
     * Handle the Models "deleted" event.
     *
     * @param  App\Models  $object
     * @return void
     */
    public function deleted($object)
    {
        if (! $object->getSkipLog()) {
            $data_before = $object->getOriginal();
            $data_after = $object->toArray();
            $table = $object->getTable();
            $object_name = optional(SourceLogEnum::fromValue($table))->getLabel() ?: ucwords(str_replace('_', ' ', $table));

            $this->log_history([
                'record_id' => $object->id,
                'data_after' => $data_after,
                'data_before' => $data_before,
                'transaction_type' => LogHistoryEnum::TRANSACTION_DELETE,
                'information' => sprintf('Hapus %s', $object_name),
                'record_type' => get_class($object),
                'table' => $table,
            ]);
        }
    }

    protected function log_history($input = [])
    {
        $input['log_datetime'] = date('Y-m-d H:i:s');
        $input['user_id'] = auth()->user()->id;

        $input['data_after'] = array_key_exists('data_after', $input) ? $input['data_after'] : [];
        $input['data_before'] = array_key_exists('data_before', $input) ? $input['data_before'] : [];
        $input['data_change'] = array_key_exists('data_change', $input) ? $input['data_change'] : [];
        if (array_key_exists('updated_at', $input['data_change'])) {
            unset($input['data_change']['updated_at']);
        }

        if (array_key_exists('deleted_at', $input['data_change'])) {
            unset($input['data_change']['deleted_at']);
        }

        $result = new LogHistory($input);
        $result->save();

        return $result;
    }
}
