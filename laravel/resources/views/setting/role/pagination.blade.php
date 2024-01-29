<table class="table table-striped projects">
    <thead>
        <tr>
            <th style="width: 1%">
                #
            </th>
            <th style="width: 20%">
                Tên
            </th>
            <th style="width: 30%">
                Quyền
            </th>
            <th style="width: 8%" class="text-center">
                Trạng Thái
            </th>
            <th style="width: 15%">
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach($roles as $role)
        <tr>
            <td>
                #
            </td>
            <td>
                {{$role->name}}
            </td>
            <td>
                {{$role->permission}}
            </td>
            <td class="project-state">
                <a id="changeStatus" href="{{ route('setting.role.changeStatus', ['id' => $role->id]) }}">
                    @if($role->status == 1)
                    <span class="badge badge-success">Acitve</span>
                    @else
                    <span class="badge badge-danger">Inactive</span>
                    @endif
                </a>
            </td>
            <td class="project-actions text-right">
                <a class="btn btn-info btn-sm" href="{{ route('setting.role.show', ['id' => $role->id]) }}">
                    <i class="fas fa-pencil-alt">
                    </i>
                    Sửa
                </a>
                <button type="button" class="btn btn-danger btn-sm btn_delete" data-toggle="modal" data-target="#modal-sm" data-id="{{ $role->id }}">
                    <i class="fas fa-trash">
                    </i>
                    Xóa
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $roles->links() }}