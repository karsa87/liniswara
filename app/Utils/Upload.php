<?php

namespace App\Utils;

trait Upload
{
    /**
     * Move multiple file
     *
     * @param  array  $files list array file
     * @param  string  $destination Directory destination file
     * @param  string  $source Directory source file
     * @return array
     * **/
    public static function moveFiles($files, $destination, $source = '')
    {
        $files = is_array($files) ? $files : [$files];
        $result_files = [];

        foreach ($files as $img) {
            $public_path = \Storage::getAdapter()->applyPathPrefix('/');
            $full_path = \Storage::getAdapter()->applyPathPrefix($img);
            $pathinfo = pathinfo($full_path);
            $filename = $pathinfo['basename'];
            $source = ! empty($source) ? $source : str_replace($public_path, '', $pathinfo['dirname']);

            $result_files[] = self::moveFile($filename, $source, $destination);
        }

        return $result_files;
    }

    /**
     * Move single file
     *
     * @param  string  $filename Filename
     * @param  string  $source Directory source file
     * @param  string  $destination Directory destination file
     * @param  string  $filename_destination Filename destination if you want different filename
     * @return string
     * **/
    public static function moveFile($filename, string $source, string $destination, $filename_destination = null)
    {
        $source = preg_replace('/(\/*)$/', '', $source);
        $destination = preg_replace('/(\/*)$/', '', $destination);

        $file_source = sprintf('%s/%s', $source, $filename);
        $file_destination = sprintf('%s/%s', $destination, ($filename_destination ?? $filename));

        if (\Storage::exists($file_source)) {
            if (\Storage::exists($file_destination)) {
                if ($file_destination == $file_source) {
                    self::moveFile($filename, $destination, $destination, 'deleted - '.$filename);
                    $file_source = sprintf('%s/%s', $destination, 'deleted - '.$filename);
                } else {
                    self::deleteFile($file_destination);
                }
            }

            \Storage::move($file_source, $file_destination);

            return $file_destination;
        }

        return $file_source;
    }

    /**
     * Upload file to temporary folder
     *
     * @param  File  $file object file
     * @return File
     * **/
    public static function uploadTmp($file)
    {
        $random = strtotime(date('Y-m-d'));
        $path_dir = sprintf('/tmp/%s', $random);

        return self::upload($file, $path_dir);
    }

    /**
     * Upload file to temporary folder
     *
     * @param  File  $file object file
     * @return File
     * **/
    public static function upload($file, $destination)
    {
        $uploadedFile = $file;
        $filename = preg_replace('/^([^\\\\]*)\.(\w+)$/', '$1-'.strtotime(date('Y-m-d H:i:s')).'.$2', $uploadedFile->getClientOriginalName());
        $filename = preg_replace('/[^a-zA-Z0-9-_.]/', '-', strtolower($filename));

        return \Storage::putFileAs(
            $destination,
            $uploadedFile,
            $filename
        );
    }

    /**
     * Delete single file
     *
     * @param  string  $file_path Directory full of file
     * @return bool
     * **/
    public static function deleteFile($file_path)
    {
        $deleted = false;
        if (\Storage::exists($file_path)) {
            \Storage::delete($file_path);
            $deleted = true;
        }

        return $deleted;
    }

    /**
     * Delete File
     *
     * @param  array  $files list array of files
     * @return array
     * **/
    public static function deleteFiles($files)
    {
        $deleted = [];
        $not_deleted = [];

        foreach ($files as $file) {
            if (self::deleteFile($file)) {
                $deleted[] = $file;
            } else {
                $not_deleted[] = $file;
            }
        }

        return [
            $deleted,
            $not_deleted,
        ];
    }
}
