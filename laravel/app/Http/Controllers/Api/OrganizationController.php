<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $organizations = Organization::paginate(env('LIMIT'));
            $organizationData = [];
            if ($organizations) {
                foreach ($organizations as $organization) {
                    $organizationData[] = $organization->getDataJson();
                }
            }

            return response()->json($organizationData, 200);
        } catch (\Exception $e) {
            return response()->json(['data' => "Lỗi rồi: " . $e->getMessage()], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = [
                'org_code' => $request->org_code,
                'org_name' => $request->org_name,
            ];

            return $this->storeObject($request, Organization::class, $data);
        } catch (\Exception $e) {
            return response()->json("Lỗi rồi: " . $e->getMessage(), 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $object = Organization::where('id', $id)->first();

        if ($object) {
            return response()->json($object->getDataJson(), 200);
        }
        return response()->json("Đối tượng này không tồn tại", 401);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $object = Organization::where('id', $id)->first();

        if ($object) {
            $data = [
                'org_code' => $request->org_code,
                'org_name' => $request->org_name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'last_updated_by' => Auth::user()->id,
            ];

            try {
                $object->update($data);
                return response()->json("Cập nhập thành công", 200);
            } catch (\Exception $e) {
                return response()->json("Cập nhập không thành công: " . $e->getMessage(), 401);
            }
        }
        return response()->json("Đối tượng này không tồn tại", 401);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $object = Organization::where('id', $id)->first();

        if ($object) {
            $object->delete();
            return response()->json("Xóa thành công", 200);
        }
        return response()->json("Đối tượng này không tồn tại", 401);
    }
}
