@extends('layouts.layout')

@section('content')
@php
$routeName = request()->route()->getName();
$routeParts = explode('.', $routeName);

if ($routeParts[0] != 'contact') {
$route = route($routeParts[0] . '.' . $routeParts[1] . '.create');
}
@endphp
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <div class="input-group">
                <input class="form-control form-control-sidebar" name="keyword" type="input" id="search" placeholder="Tìm kiếm" aria-label="Search" autocomplete="off">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        @if ($routeParts[0] != 'contact')
        <div class="card-tools">
            <a href="{{ $route }}">
                <button type="button" class="btn btn-primary btn-lg ml-auto">Thêm {{ $pageName }}</button>
            </a>
        </div>
        @endif
    </div>
    <div class="card-body p-0" id="divTableAccount">
        @yield('content_listing')
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
<div class="modal fade" id="modal-sm">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center">Bạn muốn xóa chứ!!!</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>

                <a id="model_delete" href="#">
                    <button type="button" class="btn btn-danger">Xóa</button>
                </a>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection

@section('include_js')
<script>
    var clearAjax = false
    var page = 1;
    var keyword = '';
    var currentUrl = window.location.pathname;
    const params = new URLSearchParams(window.location.search);
    locale = params.get('locale') ?? 'vi';
    var explodeUrl = currentUrl.split('/');
    if (explodeUrl.length == 2) {
        var url = explodeUrl[1] + "/get-" + explodeUrl[1];
    } else {
        var url = explodeUrl[2] + "/get-" + explodeUrl[2];
    }

    $(document).on('keyup', '#search', function(e) {
        keyword = $('#search').val();
        getListingAjax(keyword, page);
    });

    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();
        page = $(this).attr('href').split('page=')[1];
        keyword = $('#search').val();
        getListingAjax(keyword, page);

    });

    $(document).on('click', '#changeStatus', function(event) {
        event.preventDefault();
        var hrefValue = $(this).attr('href');

        changeStatus(keyword, page, hrefValue);
    });

    $(document).on('click', '#edit_form', function(event) {
        event.preventDefault();
        var hrefValue = $(this).attr('href');

        renderForm(hrefValue);
    });

    $(document).on('click', '#add_form', function(event) {
        event.preventDefault();
        var hrefValue = 'locale/create';

        renderForm(hrefValue);
    });

    function getListingAjax(keyword, page) {
        if (clearAjax) clearTimeout(clearAjax);
        clearAjax = setTimeout(function() {
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    keyword: keyword,
                    page: page,
                    locale: locale
                },
                success: function(data) {
                    $('#divTableAccount').html(data);
                },
                error: function(xhr, status, error) {
                    $('#tableAccount tbody').empty();
                    $('#tableAccount tbody').append('<tr><td colspan="7" class="text-center">Không thể tìm thấy dữ liệu</td></tr>');
                }
            });
        }, 500);
    }

    function changeStatus(keyword, page, urlChange) {
        $.ajax({
            url: urlChange,
            type: 'GET',
            data: {
                keyword: keyword,
                page: page,
                locale: locale
            },
            success: function(data) {
                $('#divTableAccount').html(data);
            },
            error: function(xhr, status, error) {
                $('#tableAccount tbody').empty();
                $('#tableAccount tbody').append('<tr><td colspan="7" class="text-center">Không thể tìm thấy dữ liệu</td></tr>');
            }
        });
    }

    function renderForm (url) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                $('#form_modal').html(data);
            },
            error: function(xhr, status, error) {
                $('#tableAccount tbody').empty();
                $('#tableAccount tbody').append('<tr><td colspan="7" class="text-center">Không thể tìm thấy dữ liệu</td></tr>');
            }
        });
    }
</script>

<script>
    $(document).ready(function() {
        $('.btn_delete').click(function() {
            var memberId = $(this).data('id');
            console.log(memberId);

            var pathName = window.location.pathname;

            $('#model_delete').attr('href', pathName + '/destroy/' + memberId)
        });
    });
</script>
@endsection