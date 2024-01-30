<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public static function checkRole($user, $permission)
    {
        // if ($user->isAdmin == true) {
        return true;
        // }
        // if ($user->role && $user->role->status == 1) {
        //     $permissions = json_decode($user->role->permission, true);

        //     return in_array($permission, $permissions);
        // }

        // return false;
    }

    public function indexObject($object)
    {
        $objects = $object::all();
        $objectData = [];
        if ($objects) {
            foreach ($objects as $object) {
                $objectData[] = $object->getDataJson();
            }
        }

        return response()->json($objectData, 200);
    }

    public function checkExist($model, $id)
    {
        $object = $model::query()->where('id', $id)->where('org_id', Auth::user()->org_id)->first();

        return $object;
    }

    public function storeObject($request, $model, $data1)
    {
        $data2 = [
            'org_id' => Auth::user()->org_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => Auth::user()->id,
            'last_updated_by' => Auth::user()->id,
        ];

        $data = array_merge($data1, $data2);

        try {
            $model::create($data);
            return response()->json("Tạo thành công", 200);
        } catch (\Exception $e) {
            return response()->json("Tạo không thành công: " . $e->getMessage(), 401);
        }
    }

    public function showObject($model, $id)
    {
        $object = self::checkExist($model, $id);

        if ($object) {
            return response()->json(["data" => $object->getDataJson()], 200);
        }
        return response()->json("Đối tượng này không tồn tại", 401);
    }

    public function updateObject($request, $model, $data1, $id)
    {
        $object = self::checkExist($model, $id);

        if ($object) {
            $data2 = [
                'org_id' => Auth::user()->org_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'last_updated_by' => Auth::user()->id,
            ];

            $data = array_merge($data1, $data2);

            try {
                $object->update($data);
                return response()->json("Cập nhập thành công", 200);
            } catch (\Exception $e) {
                return response()->json("Cập nhập không thành công: " . $e->getMessage(), 401);
            }
        }
        return response()->json("Đối tượng này không tồn tại", 401);
    }

    public function destroyObject($model, $id)
    {
        $object = self::checkExist($model, $id);

        if ($object) {
            $object->delete();
            return response()->json("Xóa thành công", 200);
        }
        return response()->json("Đối tượng này không tồn tại", 401);
    }
}
