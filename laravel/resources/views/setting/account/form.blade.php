@extends('layouts.layout_form')

@section('content_form')
<div class="form-group">
    <label for="inputName">Họ Tên<span class="text-danger">*</span></label>
    <input type="text" id="inputName" name="name" class="form-control" value="{{ isset($account) ? $account->name : '' }}" required>
</div>
<div class="form-group">
    <label for="inputEmail">Email<span class="text-danger">*</span></label>
    <input type="email" id="inputEmail" name="email" class="form-control" value="{{ isset($account) ? $account->email : '' }}" required>
</div>
<div class="form-group">
    <label for="inputPhone">Số điện thoại<span class="text-danger">*</span></label>
    <input type="text" id="inputPhone" name="phone" class="form-control" value="{{ isset($account) ? $account->phone : '' }}" required>
</div>
<div class="form-group">
    <label for="inputStatus">Quyền<span class="text-danger">*</span></label>
    <select id="inputStatus" class="form-control select2" name="role_id" required>
        <option disabled selected>Chọn một</option>
        @foreach ($roles as $role) 
        <option {{ isset($account) && $account->role_id == $role->id ? 'selected' : '' }} value="{{ $role->id }}">{{ $role->name }}</option>
        @endforeach
    </select>
</div>
@endsection