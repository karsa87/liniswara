<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Writer\WriterStoreUpdateRequest;
use App\Http\Resources\Admin\Writer\WriterResource;
use App\Models\Writer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class WriterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Writer::with([
                'products',
            ]);

            if ($q = $request->input('search.value')) {
                $query->where(function ($qWriter) use ($q) {
                    $qWriter->whereLike('name', $q);
                });
            }

            $totalAll = (clone $query)->count();

            $writers = $query->offset($request->get('start', 0))
                ->limit($request->get('length', 10))
                ->get();

            return WriterResource::collection($writers)->additional([
                'recordsTotal' => $totalAll,
                'recordsFiltered' => $totalAll,
            ]);
        }

        return view(
            'admin.writer.index',
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WriterStoreUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            $writer = new Writer();
            $writer->fill([
                'name' => $request->input('writer_name'),
                'email' => $request->input('writer_email'),
                'phone' => $request->input('writer_phone'),
            ]);

            if ($writer->save()) {
                $writer->products()->sync($request->input('writer_product_id', []));
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $writer = Writer::with([
            'products',
        ])->find($id);

        if (is_null($writer)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        return new WriterResource($writer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WriterStoreUpdateRequest $request, string $id)
    {
        $writer = Writer::find($id);

        if (is_null($writer)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        DB::beginTransaction();
        try {
            $writer->fill([
                'name' => $request->input('writer_name'),
                'email' => $request->input('writer_email'),
                'phone' => $request->input('writer_phone'),
            ]);

            if ($writer->save()) {
                $writer->products()->sync($request->input('writer_product_id', []));
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $writer = Writer::with('products')->find($id);

        if (is_null($writer)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        try {
            if ($writer->products) {
                $writer->products()->detach();
            }
            $writer->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }
}
