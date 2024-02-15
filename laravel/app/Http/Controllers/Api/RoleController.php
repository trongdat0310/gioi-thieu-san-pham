<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return $this->indexObject(Role::class);
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
                'role_code' => 'required|string',
                'role_name' => 'required|string',
                'permission_id' => 'required|int',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $data = [
                'role_code' => $request->role_code,
                'role_name' => $request->role_name,
            ];

            $dataRelation = [
                'permission_id' => $request->permission_id,
            ];

            return $this->storeObject($request, Role::class, $data, true, $dataRelation, 'role_id', RolePermission::class);
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
            return $this->showObject(Role::class, $id);
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
            $validator = Validator::make($request->all(), [
                'role_code' => 'required|string',
                'role_name' => 'required|string',
                'permission_id' => 'required|int',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
            
            $data = [
                'role_code' => $request->role_code,
                'role_name' => $request->role_name,
            ];

            $dataRelation = [
                'permission_id' => $request->permission_id,
            ];

            return $this->updateObject($request, Role::class, $data, $id, true, $dataRelation, 'role_id', RolePermission::class);
        } catch (\Exception $e) {
            return response()->json("Lỗi rồi: " . $e->getMessage(), 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = self::checkExist(Role::class, $id);
        $rolePermission = RolePermission::where('role_id', $id)->where('org_id', Auth::user()->org_id)->first();

        try {
            if ($role) {
                $role->delete();
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
