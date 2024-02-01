<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupperUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $accounts = User::where('org_id', Auth::user()->org_id)->orwhere('created_by', Auth::user()->id)->orderBy('id', 'desc')->paginate(env('LIMIT'));
            $accountData = [];
            if ($accounts) {
                foreach ($accounts as $account) {
                    $accountData[] = $account->getDataJson();
                }
            }

            return response()->json($accountData, 200);
        } catch (\Exception $e) {
            return response()->json(['data' => "Lỗi rồi: " . $e->getMessage()], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $password = Hash::make($this->generateRandomPassword());

        $data = [
            'org_id' => $request->org_id,
            'user_name' => $request->user_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $password,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => Auth::user()->id,
            'last_updated_by' => Auth::user()->id,
        ];

        try {
            User::create($data);
            return response()->json("Tạo thành công. Mật khẩu của bạn là: '" . $password . "'", 200);
        } catch (\Exception $e) {
            return response()->json("Tạo không thành công: " . $e->getMessage(), 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $object = User::where('id', $id)
            ->where(function ($query) {
                $query->where('org_id', Auth::user()->org_id)
                    ->orWhere('created_by', Auth::user()->id);
            })->first();

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
        $object = User::where('id', $id)
            ->where(function ($query) {
                $query->where('org_id', Auth::user()->org_id)
                    ->orWhere('created_by', Auth::user()->id);
            })->first();


        if ($object) {
            $data = [
                'org_id' => $request->org_id,
                'user_name' => $request->user_name,
                'phone' => $request->phone,
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
        $object = User::where('id', $id)
            ->where(function ($query) {
                $query->where('org_id', Auth::user()->org_id)
                    ->orWhere('created_by', Auth::user()->id);
            })->first();

        if ($object) {
            $object->delete();
            return response()->json("Xóa thành công", 200);
        }
        return response()->json("Đối tượng này không tồn tại", 401);
    }
}
