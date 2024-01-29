<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Mail;
use App\Mail\SendMail;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail as FacadesMail;
use Illuminate\Support\Facades\Redirect;

class AccountController extends Controller
{
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

    public function index(Request $request)
    {
        $folderName = 'Cài đặt';
        $pageName = 'Tài khoản';
        if (Controller::checkRole(Auth::user(), 'account.list')) {
            $accounts = User::latest()->paginate(env('LIMIT'));

            return view('setting/account/account', compact('accounts', 'folderName', 'pageName'));
        }

        return view('404', compact('folderName', 'pageName'));
    }

    public function getAccount(Request $request)
    {
        $folderName = 'Cài đặt';
        $pageName = 'Tài khoản';
        if (Controller::checkRole(Auth::user(), 'account.list')) {
            if ($request->ajax()) {
                $accounts = User::query()
                    ->where('name', 'LIKE', '%' . $request->get('keyword') . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->get('keyword') . '%')
                    ->orWhere('phone', 'LIKE', '%' . $request->get('keyword') . '%')
                    ->orderBy('id', 'desc')
                    ->paginate(env('LIMIT'));

                return view('setting/account/pagination', compact('accounts'))->render();
            }
        }

        return view('404', compact('folderName', 'pageName'));
    }

    public function create()
    {
        $folderName = 'Cài đặt';
        $pageName = 'Thêm tài khoản';
        if (Controller::checkRole(Auth::user(), 'account.add')) {
            $roles = Role::query()->get();

            return view('setting/account/form', compact('roles', 'folderName', 'pageName'));
        }

        return view('404', compact('folderName', 'pageName'));
    }

    public function store(Request $request)
    {
        $folderName = 'Cài đặt';
        $pageName = 'Thêm tài khoản';
        if (Controller::checkRole(Auth::user(), 'account.add')) {
            // Kiểm tra dữ liệu đầu vào
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'phone' => 'required|string',
                'role_id' => 'required|string',
            ]);

            $password = $this->generateRandomPassword();

            User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($password),
                'phone' => $request->get('phone'),
                'role_id' => $request->get('role_id'),
                'status' => 0
            ]);

            $mailData = [
                'title' => 'Mail From Dat Phi',
                'body' => 'Mật khẩu của bạn là: ' . $password,
            ];

            FacadesMail::to($request->get('email'))->send(new SendMail($mailData));

            return Redirect::route('setting.account');
        }

        return view('404', compact('folderName', 'pageName'));
    }

    public function show($id)
    {
        $folderName = 'Cài đặt';
        $pageName = 'Chi tiết tài khoản';
        if (Controller::checkRole(Auth::user(), 'account.edit')) {
            $account = User::query()->where('id', $id)->first();

            $roles = Role::query()->get();

            return view('setting/account/form', compact('account', 'roles', 'folderName', 'pageName'));
        }

        return view('404', compact('folderName', 'pageName'));
    }

    public function update($id, Request $request)
    {
        $folderName = 'Cài đặt';
        $pageName = 'Chi tiết tài khoản';
        if (Controller::checkRole(Auth::user(), 'account.edit')) {
            // Kiểm tra xem user có tồn tại hay không
            $account = User::find($id);

            if ($account) {
                // Sử dụng Mass Assignment để cập nhật dữ liệu
                $account->update([
                    'name' => $request->get('name'),
                    'email' => $request->get('email'),
                    'phone' => $request->get('phone'),
                    'role_id' => $request->get('role_id'),
                ]);
            }

            // Chuyển hướng đến route 'setting.account.update' với tham số 'id'
            return redirect()->route('setting.account.show', ['id' => $id]);
        }

        return view('404', compact('folderName', 'pageName'));
    }

    public function changeStatus($id, Request $request)
    {
        $folderName = 'Cài đặt';
        $pageName = 'Tài khoản';
        if (Controller::checkRole(Auth::user(), 'account.active-inactive')) {
            $account = User::find($id);

            $account->status == 0 ? $account->update(['status' => 1]) : $account->update(['status' => 0]);

            return $this->getAccount($request);
        }

        return view('404', compact('folderName', 'pageName'));
    }

    public function destroy($id)
    {
        $folderName = 'Cài đặt';
        $pageName = 'Tài khoản';
        if (Controller::checkRole(Auth::user(), 'account.delete')) {
            $account = User::find($id);

            if ($account) {
                $account->delete();
            }

            return Redirect::route('setting.account');
        }

        return view('404', compact('folderName', 'pageName'));
    }
}
