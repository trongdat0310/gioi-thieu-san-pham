@extends('layouts.layout')

@section('include_style')
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@yield('add_style')
@endsection

@section('content')
@php
$routeName = request()->route()->getName();
$routeParts = explode('.', $routeName);

if ($routeParts[0] == "document" || $routeParts[1] == "infoCompany") {
$route = route($routeParts[0] . '.' . $routeParts[1] . '.update');
} else {
if ($routeParts[2] == 'create') {
$route = route($routeParts[0] . '.' . $routeParts[1] . '.store');
} else {
$route = route($routeParts[0] . '.' . $routeParts[1] . '.update', ['id' => request()->route()->parameter('id')]);
}
}
@endphp
<!-- Thêm thẻ div để làm màn hình loading -->
<div id="loading-overlay" style="display: none;">
    <div id="loading-spinner"></div>
</div>

<form id="quickForm" method="POST" action="{{$route}}" enctype="multipart/form-data">
    {{csrf_field()}}
    <div class="row">
        <!-- left column -->
        <div class="col-lg-9 col-md-12">
            <input type="input" name="locale" class="form-control" id="" value="{{ request()->input('locale', 'vi') }}" hidden>
            @yield('content_card_body')
            <!-- /.card -->
        </div>
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-lg-3 col-md-12">
            <div class="card card-primary" style="position: sticky; top: 50px;">
                <!-- form start -->
                <div class="card-body">
                    <select id="languageSelect">
                        @foreach ($locales as $locale)
                        <option value="{{ $locale->key }}">{{ $locale->name }}</option>
                        @endforeach
                    </select>

                    @yield('content_form_right')

                    <div class="row mt-3">
                        <div class="col-lg-6">
                            <button type="button" class="btn btn-primary w-100 btn-lg">Xem</button>
                        </div>
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-primary w-100 btn-lg">Gửi</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/.col (right) -->
    </div>
</form>
@endsection

@section('include_js')
<script>
    $(document).ready(function() {
        // Xóa tham số locale=vi nếu có
        removeLocaleViFromURL();

        // Lắng nghe sự kiện thay đổi giá trị của thẻ select
        $(document).on('change', '#languageSelect', function() {
            var locale = $(this).val();
            window.location.href = appendLocaleToCurrentURL(locale);
        });

        // Lấy giá trị locale từ URL và đặt giá trị cho thẻ select
        var localeFromUrl = getUrlParameter('locale');
        if (localeFromUrl) {
            $('#languageSelect').val(localeFromUrl);
        }

        function getUrlParameter(name) {
            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
            var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            var results = regex.exec(location.search);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        }

        function appendLocaleToCurrentURL(localeValue) {
            var currentURL = window.location.href;
            var separator = currentURL.indexOf('?') !== -1 ? '&' : '?';

            if (currentURL.indexOf('locale=') === -1) {
                return currentURL + separator + 'locale=' + localeValue;
            } else {
                return currentURL.replace(/locale=[^&]*/, 'locale=' + localeValue);
            }
        }

        function removeLocaleViFromURL() {
            var currentURL = window.location.href;

            if (currentURL.indexOf('locale=vi') !== -1) {
                currentURL = currentURL.replace(/([&?]locale=)vi\b/, '');
                currentURL = currentURL.replace(/locale=vi&/, '');
                currentURL = currentURL.replace(/locale=vi/, '');

                if (currentURL.endsWith('&')) {
                    currentURL = currentURL.slice(0, -1);
                }

                window.history.replaceState({}, '', currentURL); // Cập nhật URL mà không làm tải lại trang
            }
        }

    });
</script>

<!-- Select2 -->
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>

<script>
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2()

        $('.reservation').daterangepicker({
            timePicker: true,
            timePickerIncrement: 30,
            locale: {
                format: 'DD/MM/YYYY'
            }
        })
    });
</script>

@yield('add_js')
@endsection