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
                Email
            </th>
            <th>
                Số điện thoại
            </th>
            <th>
                Chức vụ
            </th>
            <th style="width: 8%" class="text-center">
                Trạng Thái
            </th>
            <th style="width: 15%">
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach($accounts as $account)
        <tr>
            <td>
                #
            </td>
            <td>
                {{$account->user_name}}
            </td>
            <td>
                {{$account->email}}
            </td>
            <td>
                {{$account->phone}}
            </td>
            <td>
                {{$account->role?->name}}
            </td>
            <td class="project-state">
                <a id="changeStatus" href="{{ route('setting.account.changeStatus', ['id' => $account->id]) }}">
                    @if($account->status == 1)
                    <span class="badge badge-success">Acitve</span>
                    @else
                    <span class="badge badge-danger">Inactive</span>
                    @endif
                </a>
            </td>
            <td class="project-actions text-right">
                <a class="btn btn-info btn-sm" href="{{ route('setting.account.show', ['id' => $account->id]) }}">
                    <i class="fas fa-pencil-alt">
                    </i>
                    Sửa
                </a>
                <button type="button" class="btn btn-danger btn-sm btn_delete" data-toggle="modal" data-target="#modal-sm" data-id="{{ $account->id }}">
                    <i class="fas fa-trash">
                    </i>
                    Xóa
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $accounts->links() }}