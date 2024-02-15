<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return $this->indexObject(Permission::class);
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
                'permission_code' => $request->permission_code,
                'permission_name' => $request->permission_name,
            ];

            return $this->storeObject($request, Permission::class, $data);
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
            return $this->showObject(Permission::class, $id);
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
                'permission_code' => $request->permission_code,
                'permission_name' => $request->permission_name,
            ];

            return $this->updateObject($request, Permission::class, $data, $id);
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
            $permission = self::checkExist(Permission::class, $id);
            $rolePermission = RolePermission::where('permission_id', $id)->where('org_id', Auth::user()->org_id)->first();

            if ($permission) {
                $permission->delete();
            }
            if ($rolePermission) {
                $rolePermission->delete();
            }

            return response()->json("Xóa thành công", 200);
        } catch (\Exception $e) {
            return response()->json("Lỗi rồi: " . $e->getMessage(), 401);
        }
    }
}
