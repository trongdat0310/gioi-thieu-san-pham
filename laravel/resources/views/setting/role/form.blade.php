@extends('layouts.layout_form')

@section('content_form')
<div class="form-group">
    <label for="inputName">Tên Quyền<span class="text-danger">*</span></label>
    <input type="text" id="inputName" name="name" class="form-control" value="{{ isset($role) ? $role->name : '' }}" required>
</div>
<div class="form-group">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Quyền</h3>
            <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="checkbox" class="toggle-switch-input" id="customSwitchSmallSize">
                    <span class="ml-2">Chọn tất cả</span>
                </div>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap" id="table_roles">
                <thead>
                    <tr>
                        <th style="width: 1%">#</th>
                        <th style="width: 19%">Tên Quyền</th>
                        <th class="text-center" style="width: 20%">Chọn theo hàng</th>
                        <th class="text-center" style="width: 12%">Sửa</th>
                        <th class="text-center" style="width: 12%">Xóa</th>
                        <th class="text-center" style="width: 12%">Xem Danh Sách</th>
                        <th class="text-center" style="width: 12%">Thêm</th>
                        <th class="text-center" style="width: 12%">Sửa Trạng Thái</th>
                    </tr>
                </thead>
                <tbody>
                    @php $outerLoopIteration = 0; @endphp
                    @foreach($permission as $perKey => $perValue)
                    <tr>
                        <td>#</td>
                        <td>{{ $perKey }}</td>
                        <td class="text-center">
                            <input class="check-all" type="checkbox" data-target="row-{{ $outerLoopIteration }}">
                        </td>
                        @for($x = 0; $x < 5; $x++) <td class="text-center">
                            @php
                            $currentPermission = isset($perValue[$x]) ? $perValue[$x] : null;
                            @endphp
                            @if($currentPermission)
                            <input class="check-box row-{{ $outerLoopIteration }}" type="checkbox" name="per[]" value="{{ $currentPermission }}" {{ isset($role) && in_array($currentPermission, json_decode($role->permission)) ? 'checked' : '' }}>
                            @endif
                            </td>
                            @endfor
                    </tr>
                    @php $outerLoopIteration++; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
@endsection

@section('add_js')
<script>
    $(document).ready(function() {
        const toggleAllCheckbox = $('#customSwitchSmallSize');
        const checkboxes = $('.check-box');
        const rowCheckboxes = $('.check-all');

        function updateToggleAllCheckbox() {
            const checkedCheckboxesCount = checkboxes.filter(':checked').length;
            toggleAllCheckbox.prop('checked', checkboxes.length === checkedCheckboxesCount);
        }

        function updateRowCheckboxes(rowIndex) {
            const rowCheckboxes = $(`.${rowIndex}`);
            const checkedRowCheckboxCount = rowCheckboxes.filter(':checked').length;
            rowCheckboxes.prop('checked', rowCheckboxes.length === checkedRowCheckboxCount);
        }

        function checkAllRow() {
            var table = $('#table_roles tbody');
            var rowCount = table.find('tr').length;

            for (let i = 0; i < rowCount; i++) {
                let checkboxAll = $(`.check-all[data-target=${"row-" + i}`);
                const checkedCheckboxCount = $(`.${"row-" + i}`).length;
                const totalCheckboxCount = $(`.${"row-" + i}:checked`).length;

                checkboxAll.prop('checked', checkedCheckboxCount === totalCheckboxCount);
            }

        }

        checkAllRow()
        updateToggleAllCheckbox()

        toggleAllCheckbox.change(function() {
            checkboxes.prop('checked', toggleAllCheckbox.prop('checked'));
            rowCheckboxes.prop('checked', toggleAllCheckbox.prop('checked'));
        });

        checkboxes.change(function() {
            updateToggleAllCheckbox();
            checkAllRow();
        });

        rowCheckboxes.change(function() {
            const rowIndex = $(this).data('target');
            const rowCheckboxes = $(`.${rowIndex}`);

            rowCheckboxes.prop('checked', $(this).prop('checked'));

            updateToggleAllCheckbox();
        });

    });
</script>
@endsection