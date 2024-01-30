<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categorys = Category::all();
            $categoryData = [];
            if ($categorys) {
                foreach ($categorys as $category) {
                    $categoryData[] = $category->getDataJson();
                }
            }

            return response()->json($categoryData, 200);
        } catch (\Exception $e) {
            return response()->json(['data' => "Lỗi rồi: " . $e->getMessage()], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
