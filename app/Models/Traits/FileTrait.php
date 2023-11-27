<?php

namespace App\Models\Traits;

use App\Models\File;
use App\Util\Helpers\Upload;
use Illuminate\Support\Str;

trait FileTrait
{
    /**
     * Get all of the owning commentable models.
     */
    public function files()
    {
        return $this->morphMany(File::class, 'record');
    }

    public function saveFile($input)
    {
        $file = isset($input['id']) ? File::find($input['id']) : new File();

        $path = $input['path'] ?? '';
        $filename_destination = '';
        if (! empty($path) && $file->getOriginal('path') != $path) {
            $pathinfo = pathinfo($path);
            $filename_destination = sprintf('%s.%s', ($input['name'] ?? $pathinfo['filename']), $pathinfo['extension']);
            $destination = sprintf('%s/%s', $this->getTable(), $this->id);
            $source = str_replace(\Storage::getAdapter()->applyPathPrefix('/'), '', $pathinfo['dirname']);

            $path = Upload::moveFile($pathinfo['basename'], $source, $destination, $filename_destination);
        }

        if (empty($input['name'])) {
            if (! empty($filename_destination)) {
                $input['name'] = $filename_destination;
            } else {
                $input['name'] = $input['url'];
            }

            $input['name'] = empty($input['name']) ? Str::random('10') : $input['name'];
        }

        $file->fill([
            'record_id' => $this->id,
            'record_type' => get_class($this),
            'name' => $input['name'] ?? $filename_destination,
            'information' => $input['information'] ?? '',
            'url' => $input['url'] ?? '',
            'path' => $path,
        ]);
        $file->save();

        return $file;
    }
}
