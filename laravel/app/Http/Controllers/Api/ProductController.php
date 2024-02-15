<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryProduct;
use App\Models\ItemAttribute;
use App\Models\MediaProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return $this->indexObject(Product::class);
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
            $validator = Validator::make($request->all(), [
                'product_code' => 'required|string',
                'product_name' => 'required|string',
                'product_description' => 'required|string',
                'primary_price' => 'required|int',
                'product_sku' => 'required|string',
                'media_id' => 'required|int',
                'primary_flag' => 'required|string',
                'category_id' => 'required|int',
                'attribute_id' => 'required|int',
            ]);

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

            $dataProduct = [
                'product_code' => $request->product_code,
                'product_name' => $request->product_name,
                'product_description' => $request->product_description,
                'primary_price' => $request->primary_price,
                'product_sku' => $request->product_sku,
            ];

            $product = Product::create(array_merge($data, $dataProduct));

            $dataMediaProduct = [
                'product_id' => $product->id,
                'media_id' => $request->media_id,
                'primary_flag' => $request->primary_flag,
            ];

            MediaProduct::create(array_merge($data, $dataMediaProduct));

            $dataCategoryProduct = [
                'product_id' => $product->id,
                'category_id' => $request->category_id,
            ];

            CategoryProduct::create(array_merge($data, $dataCategoryProduct));

            $dataItemAttribute = [
                'item_id' => $product->id,
                'attribute_id' => $request->attribute_id,
                'item_type' => 'prod',
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
            return $this->showObject(Product::class, $id);
        } catch (\Exception $e) {
            return response()->json("Lỗi rồi: " . $e->getMessage(), 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = self::checkExist(Product::class, $id);
        $categoryProduct = CategoryProduct::where('product_id', $id)->where('org_id', Auth::user()->org_id)->first();
        $itemAttribute = ItemAttribute::where('item_id', $id)->where('org_id', Auth::user()->org_id)->where('item_type', 'prod')->first();

        if ($product) {
            $validator = Validator::make($request->all(), [
                'product_code' => 'required|string',
                'product_name' => 'required|string',
                'product_description' => 'required|string',
                'primary_price' => 'required|int',
                'product_sku' => 'required|string',
                'media_id' => 'required|int',
                'primary_flag' => 'required|string',
                'category_id' => 'required|int',
                'attribute_id' => 'required|int',
            ]);

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

                $dataProduct = [
                    'product_code' => $request->product_code,
                    'product_name' => $request->product_name,
                    'product_description' => $request->product_description,
                    'primary_price' => $request->primary_price,
                    'product_sku' => $request->product_sku,
                ];

                $product->update(array_merge($data, $dataProduct));

                $dataMediaProduct = [
                    'product_id' => $product->id,
                    'media_id' => $request->media_id,
                    'primary_flag' => $request->primary_flag,
                ];

                if ($categoryProduct) $categoryProduct->update(array_merge($data, $dataMediaProduct));

                $dataCategoryProduct = [
                    'product_id' => $product->id,
                    'category_id' => $request->category_id,
                ];

                if ($categoryProduct) $categoryProduct->update(array_merge($data, $dataCategoryProduct));

                $dataItemAttribute = [
                    'item_id' => $product->id,
                    'attribute_id' => $request->attribute_id,
                    'item_type' => 'prod',
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
        $product = self::checkExist(Product::class, $id);
        $categoryProduct = CategoryProduct::where('product_id', $id)->where('org_id', Auth::user()->org_id)->first();
        $itemAttribute = ItemAttribute::where('item_id', $id)->where('org_id', Auth::user()->org_id)->where('item_type', 'prod')->first();
        try {
            if ($product) {
                $product->delete();
            }
            if ($categoryProduct) {
                $categoryProduct->delete();
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
