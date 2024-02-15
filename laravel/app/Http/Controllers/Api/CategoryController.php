<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ItemAttribute;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return $this->indexObject(Category::class);
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
            $rules = [
                'category_code' => 'required|string',
                'category_name' => 'required|string',
                'attribute_id' => 'required|int',
            ];

            if ($request->has('parent_category_id')) {
                $rules['parent_category_id'] = 'required|int';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $data = [
                'org_id' => Auth::user()->org_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'created_by' => Auth::user()->id,
                'last_updated_by' => Auth::user()->id,
            ];

            $dataCategory = [
                'category_code' => $request->category_code,
                'category_name' => $request->category_name,
            ];

            $category = Category::create(array_merge($data, $dataCategory));

            $dataItemCategory = [
                'parent_category_id' => $request->parent_category_id,
                'category_id' => $category->id,
            ];

            if ($request->has('parent_category_id')) {
                ItemCategory::create(array_merge($data, $dataItemCategory));
            }

            $dataItemAttribute = [
                'item_id' => $category->id,
                'attribute_id' => $request->attribute_id,
                'item_type' => 'cate',
            ];

            ItemAttribute::create(array_merge($data, $dataItemAttribute));

            return response()->json("Tạo thành công", 200);
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
            return $this->showObject(Category::class, $id);
        } catch (\Exception $e) {
            return response()->json("Lỗi rồi: " . $e->getMessage(), 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = self::checkExist(Category::class, $id);
        $itemCategory = ItemCategory::where('category_id', $id)->where('org_id', Auth::user()->org_id)->first();
        $itemAttribute = ItemAttribute::where('item_id', $id)->where('org_id', Auth::user()->org_id)->where('item_type', 'cate')->first();
        if ($category) {
            $rules = [
                'category_code' => 'required|string',
                'category_name' => 'required|string',
                'attribute_id' => 'required|int',
            ];

            if ($request->has('parent_category_id')) {
                $rules['parent_category_id'] = 'required|int';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            try {
                $data = [
                    'org_id' => Auth::user()->org_id,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'created_by' => Auth::user()->id,
                    'last_updated_by' => Auth::user()->id,
                ];

                $dataCategory = [
                    'category_code' => $request->category_code,
                    'category_name' => $request->category_name,
                ];

                $category->update(array_merge($data, $dataCategory));

                $dataItemCategory = [
                    'parent_category_id' => $request->parent_category_id,
                    'category_id' => $category->id,
                ];

                if ($request->has('parent_category_id')) {
                    if ($itemCategory) {
                        $itemCategory->update(array_merge($data, $dataItemCategory));
                    } else {
                        ItemCategory::create(array_merge($data, $dataItemCategory));
                    }
                } else {
                    if ($itemCategory) $itemCategory->delete();
                }

                $dataItemAttribute = [
                    'attribute_id' => $request->attribute_id,
                ];

                if ($itemAttribute) $itemAttribute->update(array_merge($data, $dataItemAttribute));

                return response()->json("Cập nhập thành công", 200);
            } catch (\Exception $e) {
                return response()->json("Lỗi rồi: " . $e->getMessage(), 401);
            }
        }
        return response()->json("Đối tượng này không tồn tại", 401);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = self::checkExist(Category::class, $id);
            $itemCategory = ItemCategory::where('category_id', $id)->where('org_id', Auth::user()->org_id)->first();
            $itemAttribute = ItemAttribute::where('item_id', $id)->where('org_id', Auth::user()->org_id)->where('item_type', 'cate')->first();

            if ($category) {
                $category->delete();
            }
            if ($itemCategory) {
                $itemCategory->delete();
            }
            if ($itemAttribute) {
                $itemAttribute->delete();
            }

            return response()->json("Xóa thành công", 200);
        } catch (\Exception $e) {
            return response()->json("Lỗi rồi: " . $e->getMessage(), 401);
        }
    }
}
