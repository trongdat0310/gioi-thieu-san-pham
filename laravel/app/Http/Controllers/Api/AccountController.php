<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupperUser;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        try {
            $password = Hash::make($this->generateRandomPassword());

            $validator = Validator::make($request->all(), [
                'role_id' => 'required|int',
                'user_name' => 'required|string',
                'email' => 'required|email',
                'phone' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $dataUser = [
                'user_name' => $request->user_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => $password,
            ];

            $data = [
                'org_id' => $request->org_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'created_by' => Auth::user()->id,
                'last_updated_by' => Auth::user()->id,
            ];

            $user = User::create(array_merge($data, $dataUser));

            $dataRelation = [
                'user_id' => $user->id,
                'role_id' => $request->role_id,
            ];

            $dataSupperUser = [
                'user_id' => $user->id,
            ];

            UserRole::create(array_merge($data, $dataRelation));

            if ($request->boolean('supper_user') == true) {
                SupperUser::create(array_merge($data, $dataSupperUser));
            }

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
        $user = User::where('id', $id)
            ->where(function ($query) {
                $query->where('org_id', Auth::user()->org_id)
                    ->orWhere('created_by', Auth::user()->id);
            })->first();

        if ($user) {
            return response()->json($user->getDataJson(), 200);
        }
        return response()->json("Đối tượng này không tồn tại", 401);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::where('id', $id)
            ->where(function ($query) {
                $query->where('org_id', Auth::user()->org_id)
                    ->orWhere('created_by', Auth::user()->id);
            })->first();

        $userRole = UserRole::where('user_id', $id)
            ->where(function ($query) {
                $query->where('org_id', Auth::user()->org_id)
                    ->orWhere('created_by', Auth::user()->id);
            })->first();

        $supperUser = SupperUser::where('user_id', $id)->first();

        if ($user) {
            try {
                $validator = Validator::make($request->all(), [
                    'role_id' => 'required|int',
                    'user_name' => 'required|string',
                    'phone' => 'required|string',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after:start_date',
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 400);
                }

                $dataUser = [
                    'user_name' => $request->user_name,
                    'phone' => $request->phone,
                ];

                $data = [
                    'org_id' => $request->org_id,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'created_by' => Auth::user()->id,
                    'last_updated_by' => Auth::user()->id,
                ];

                $dataRelation = [
                    'user_id' => $id,
                    'role_id' => $request->role_id,
                ];

                $user->update(array_merge($data, $dataUser));
                $userRole->update(array_merge($data, $dataRelation));

                if ($request->boolean('supper_user') != true) {
                    if ($supperUser) $supperUser->delete();
                } else {
                    if (!$supperUser) {
                        $dataSupperUser = [
                            'user_id' => $user->id,
                        ];
                        SupperUser::create(array_merge($data, $dataSupperUser));
                    }
                }

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
        $user = User::where('id', $id)
            ->where(function ($query) {
                $query->where('org_id', Auth::user()->org_id)
                    ->orWhere('created_by', Auth::user()->id);
            })->first();

        $supperUser = SupperUser::where('user_id', $id)->where('org_id', Auth::user()->org_id)->first();
        $userRole = UserRole::where('user_id', $id)->where('org_id', Auth::user()->org_id)->first();

        try {
            if ($user) {
                $user->delete();
            }
            if ($supperUser) {
                $supperUser->delete();
            }
            if ($userRole) {
                $userRole->delete();
            }

            return response()->json("Xóa thành công", 200);
        } catch (\Exception $e) {
            return response()->json("Lỗi rồi: " . $e->getMessage(), 401);
        }
        return response()->json("Đối tượng này không tồn tại", 401);
    }
}
