<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

    public function generateRandomPassword()
    {
        // Đặt các ký tự có thể sử dụng
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $specialChars = '!@#$%^&*()-_';

        // Tạo một mảng chứa các loại ký tự
        $characterSets = array(
            $lowercase,
            $uppercase,
            $numbers,
            $specialChars
        );

        $password = '';

        // Bắt đầu với ít nhất một ký tự từ mỗi loại
        foreach ($characterSets as $characterSet) {
            $password .= $characterSet[rand(0, strlen($characterSet) - 1)];
        }

        // Đảm bảo mật khẩu có độ dài ít nhất là 4
        for ($i = 4; $i < 15; $i++) {
            // Lựa chọn ngẫu nhiên một loại ký tự từ mảng
            $randomSet = $characterSets[rand(0, count($characterSets) - 1)];

            // Lựa chọn ngẫu nhiên một ký tự từ loại ký tự được chọn
            $randomChar = $randomSet[rand(0, strlen($randomSet) - 1)];

            // Thêm ký tự vào mật khẩu
            $password .= $randomChar;
        }

        // Trộn ngẫu nhiên các ký tự trong mật khẩu
        $password = str_shuffle($password);

        return $password;
    }

    public function indexObject($object)
    {
        $objects = $object::where('org_id', Auth::user()->org_id)->orderBy('id', 'desc')->paginate(env('LIMIT'));
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

    public function storeObject($request, $model, $data1, $isRelation = false, $dataRelation = [], $columnRelation = '', $modelRelation = false)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $data2 = [
            'org_id' => Auth::user()->org_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => $userId = Auth::user()->id,
            'last_updated_by' => $userId,
        ];

        $data = array_merge($data1, $data2);

        try {
            $object = $model::create($data);

            if ($isRelation) {
                $data2[$columnRelation] = $object->id;
                $this->storeRelationObject($modelRelation, $dataRelation, $data2);
            }

            return response()->json("Tạo thành công", 200);
        } catch (\Exception $e) {
            return response()->json("Tạo không thành công: " . $e->getMessage(), 401);
        }
    }

    public function storeRelationObject($model, $data1, $data2)
    {
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
            return response()->json($object->getDataJson(), 200);
        }
        return response()->json("Đối tượng này không tồn tại", 401);
    }

    public function updateObject($request, $model, $data1, $id, $isRelation = false, $dataRelation = [], $columnRelation = '', $modelRelation = false)
    {
        $object = self::checkExist($model, $id);

        if ($object) {
            $validator = Validator::make($request->all(), [
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $data2 = [
                'org_id' => Auth::user()->org_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'last_updated_by' => Auth::user()->id,
            ];

            $data = array_merge($data1, $data2);

            try {
                $object->update($data);

                if ($isRelation) {
                    $data2[$columnRelation] = $id;
                    $this->updateRelationObject($modelRelation, $id, $dataRelation, $data2, $columnRelation);
                }
                return response()->json("Cập nhập thành công", 200);
            } catch (\Exception $e) {
                return response()->json("Cập nhập không thành công: " . $e->getMessage(), 401);
            }
        }
        return response()->json("Đối tượng này không tồn tại", 401);
    }

    public function updateRelationObject($model, $id, $data1, $data2, $columnRelation)
    {
        $objectRelation = $model::query()->where($columnRelation, $id)->where('org_id', Auth::user()->org_id)->first();

        $data = array_merge($data1, $data2);

        try {
            $objectRelation->update($data);
            return response()->json("Cập nhập thành công", 200);
        } catch (\Exception $e) {
            return response()->json("Cập nhập không thành công: " . $e->getMessage(), 401);
        }
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
