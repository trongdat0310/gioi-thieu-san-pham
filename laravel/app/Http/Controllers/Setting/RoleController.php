<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

use function GuzzleHttp\json_encode;

class RoleController extends Controller
{
    public function getPermission()
    {
        return [
            "Thống kê" => [
                "dashboard.edit",
            ],
            "Tài khoản" => [
                "account.edit",
                "account.delete",
                "account.list",
                "account.add",
                "account.active-inactive",
            ],
            "Quyền" => [
                "role.edit",
                "role.delete",
                "role.list",
                "role.add",
                "role.active-inactive",
            ],
        ];
    }

    public function index(Request $request)
    {
        $folderName = 'Cài đặt';
        $pageName = 'Quyền';
        if (Controller::checkRole(Auth::user(), 'role.list')) {
            $roles = Role::latest()->paginate(env('LIMIT'));

            return view('setting/role/role', compact('roles', 'folderName', 'pageName'));
        }

        return view('404', compact('folderName', 'pageName'));
    }

    public function getRole(Request $request)
    {
        $folderName = 'Cài đặt';
        $pageName = 'Quyền';
        if (Controller::checkRole(Auth::user(), 'role.list')) {
            if ($request->ajax()) {
                $roles = Role::query()
                    ->where('name', 'LIKE', '%' . $request->get('keyword') . '%')
                    ->orWhere('permission', 'LIKE', '%' . $request->get('keyword') . '%')
                    ->orderBy('id', 'desc')
                    ->paginate(env('LIMIT'));

                return view('setting/role/pagination', compact('roles'))->render();
            }
        }

        return view('404', compact('folderName', 'pageName'));
    }

    public function create()
    {
        $folderName = 'Cài đặt';
        $pageName = 'Thêm quyền';

        if (Controller::checkRole(Auth::user(), 'role.add')) {
            $permission = $this->getPermission();
            return view('setting/role/form', compact('permission', 'folderName', 'pageName'));
        }

        return view('404', compact('folderName', 'pageName'));
    }

    public function store(Request $request)
    {
        $folderName = 'Cài đặt';
        $pageName = 'Thêm quyền';

        if (Controller::checkRole(Auth::user(), 'role.add')) {
            $data = [
                'name' => $request->get('name'),
                'permission' => json_encode($request->get('per')),
                'status' => 0
            ];

            Role::create($data);

            return Redirect::route('setting.role');
        }

        return view('404', compact('folderName', 'pageName'));
    }

    public function show($id)
    {
        $folderName = 'Cài đặt';
        $pageName = 'Chi tiết quyền';

        if (Controller::checkRole(Auth::user(), 'role.edit')) {
            $permission = $this->getPermission();

            $role = Role::query()
                ->where('id', $id)
                ->first();

            return view('setting/role/form', compact('role', 'permission', 'folderName', 'pageName'));
        }

        return view('404', compact('folderName', 'pageName'));
    }

    public function update($id, Request $request)
    {
        $folderName = 'Cài đặt';
        $pageName = 'Chi tiết quyền';

        if (Controller::checkRole(Auth::user(), 'role.edit')) {
            // Kiểm tra xem user có tồn tại hay không
            $role = Role::find($id);

            $data = [
                'name' => $request->get('name'),
                'permission' => json_encode($request->get('per')),
            ];

            if ($role) { $role->update($data); }

            return redirect()->route('setting.role.show', ['id' => $id]);
        }

        return view('404', compact('folderName', 'pageName'));
    }

    public function changeStatus($id, Request $request)
    {
        $folderName = 'Cài đặt';
        $pageName = 'Chi tiết quyền';

        if (Controller::checkRole(Auth::user(), 'role.active-inactive')) {
            $role = Role::find($id);

            $role->status == 0 ? $role->update(['status' => 1]) : $role->update(['status' => 0]);

            return $this->getRole($request);
        }

        return view('404', compact('folderName', 'pageName'));
    }

    public function destroy($id)
    {
        $folderName = 'Cài đặt';
        $pageName = 'Quyền';

        if (Controller::checkRole(Auth::user(), 'role.delete')) {
            $role = Role::find($id);

            if ($role) { $role->delete(); }

            return Redirect::route('setting.role');
        }

        return view('404', compact('folderName', 'pageName'));
    }
}
