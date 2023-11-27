<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Utils\Upload;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            try {
                $path = Upload::upload($request->file, 'files/'.date('Ymd'));

                if ($path) {
                    $name = preg_replace('/^([^\\\\]*)\.(\w+)$/', '$1', $request->file->getClientOriginalName());

                    $file = new File();
                    $file->fill([
                        'name' => $name,
                        'path' => $path,
                        'url' => null,
                    ]);
                    $file->save();

                    return response()->json([
                        'id' => $file->id,
                        'name' => $file->name,
                        'path' => $file->path,
                        'full_url' => url($file->path),
                    ]);
                }
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                    'exception_message' => $th->getMessage(),
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json([
                'message' => 'Gagal upload file',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'message' => 'File yang diupload tidak ditemukan',
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Delete a listing of the resource.
     */
    public function destroy($id)
    {
        $file = File::find($id);

        if (is_null($file)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        try {
            Upload::deleteFile($file->path);
            $file->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }
}
