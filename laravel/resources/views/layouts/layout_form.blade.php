@extends('layouts.layout')

@section('include_style')
    @yield('add_style')
@endsection

@section('content')
@php
    $routeName = request()->route()->getName();
    $routeParts = explode('.', $routeName);

    $route = '';
    // dd($routeParts[0]);
    if ($routeParts[0] != 'contact' && $routeParts[1] != 'translation') {
        if ($routeParts[2] == 'create') {
            $route = route($routeParts[0] . '.' . $routeParts[1] . '.store');
        } else {
            $route = route($routeParts[0] . '.' . $routeParts[1] . '.update', ['id' => request()->route()->parameter('id')]);
        }
    }
    if ($routeParts[1] == 'translation') {
        $route = 'translation/update';
    }

@endphp
<form id="quickForm" method="POST" action="{{ $route }}" enctype="multipart/form-data">
    {{csrf_field()}}
    <div class="card card-primary">
        <div class="card-body">
        @yield('content_form')
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    <div class="row">
        <div class="col-12">
            <!-- <input type="reset" value="Hủy" class="btn btn-secondary"> -->
            @if ($route != '')
            <input type="submit" value="Lưu thay đổi" class="btn btn-success float-right">
            @endif
        </div>
    </div>
</form>
@endsection

@section('include_js')
    @yield('add_js')
<!-- <script>
    $('#quickForm').submit(function(e) {
        e.preventDefault(); // Prevent form submission for now

        var nextStep = validate();

        if (nextStep) {
            this.submit();
        }
    });

    function validate() {
        var nextStep = true;

        // Loop through each input element
        $('#quickForm input').each(function() {
            // Check if the input is not empty
            if ($(this).val().trim() !== '') {
                // Add the 'is-valid' class if not empty
                $(this).removeClass('is-invalid').addClass('is-valid');
            } else {
                // Add the 'is-invalid' class if empty
                $(this).removeClass('is-valid').addClass('is-invalid');
                nextStep = false; // Set nextStep to false if any input is invalid
            }
        });

        // Additional validation logic can be added here

        return nextStep;
    }
</script> -->
<script>
    function previewImage() {
      var fileInput = document.getElementById('customFile');
      var preview = document.getElementById('preview');
      
      // Kiểm tra xem có tệp được chọn hay không
      if (fileInput.files && fileInput.files[0]) {
        var reader = new FileReader();

        // Đọc dữ liệu từ tệp hình ảnh
        reader.onload = function (e) {
          // Hiển thị ảnh đã chọn
          preview.src = e.target.result;
          preview.style.display = 'block';
        };

        // Đọc dữ liệu từ tệp hình ảnh
        reader.readAsDataURL(fileInput.files[0]);
      }
    }
</script>
@endsection