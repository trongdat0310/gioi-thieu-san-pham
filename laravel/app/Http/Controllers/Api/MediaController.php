<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\MediaProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return $this->indexObject(Media::class);
        } catch (\Exception $e) {
            return response()->json("Lỗi rồi: " . $e->getMessage(), 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = [
                'media_type' => $request->media_type,
                'media_path' => $request->media_path,
                'media_code' => $request->media_code,
                'media_name' => $request->media_name,
            ];

            return $this->storeObject($request, Media::class, $data);
        } catch (\Exception $e) {
            return response()->json("Lỗi rồi: " . $e->getMessage(), 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return $this->showObject(Media::class, $id);
        } catch (\Exception $e) {
            return response()->json("Lỗi rồi: " . $e->getMessage(), 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = [
                'media_type' => $request->media_type,
                'media_path' => $request->media_path,
                'media_code' => $request->media_code,
                'media_name' => $request->media_name,
            ];

            return $this->updateObject($request, Media::class, $data, $id);
        } catch (\Exception $e) {
            return response()->json("Lỗi rồi: " . $e->getMessage(), 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $media = self::checkExist(Media::class, $id);
            $mediaProduct = MediaProduct::where('media_id', $id)->where('org_id', Auth::user()->org_id)->first();

            if ($media) {
                $media->delete();
            }
            if ($mediaProduct) {
                $mediaProduct->delete();
            }

            return response()->json("Xóa thành công", 200);
        } catch (\Exception $e) {
            return response()->json("Lỗi rồi: " . $e->getMessage(), 401);
        }
    }
}
