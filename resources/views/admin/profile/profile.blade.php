@extends('admin.layouts.app')
@section('title')
| Profile
@endsection
@push('css_lib')
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{ asset('backend/plugins/iCheck/all.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/dist/css/intlInputPhone.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Update your Profile</h1>
</section>
<!-- Main content -->
<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Profile Info</h3>
        </div>
        <!-- /.box-header -->
{{--        @dd($user)--}}
        <div class="box-body">
            <div class="row">
                @include('admin.users.create')
            </div>
        </div>
        <!-- /.box-body -->
    </div>
</section>
@endsection

@push('js_lib')
<!-- iCheck 1.0.1 -->
<script src="{{ asset('backend/plugins/iCheck/icheck.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endpush

@push('js_custom')
<script>
    $(function() {
        var datepickerOptions = {
            autoclose: true,
            format: 'dd-mm-yyyy',
            todayBtn: true,
        };

        $('.datepicker').datepicker(datepickerOptions);

            //Initialize Select2 Elements
            $('.select2').select2();

            // Initial Focused field
            $('#product_name').focus();

            $(document).on('click', '#productPrice', function() {
                // Does some stuff and logs the event to the console

                var placeholderValue = $(this).attr('placeholder');
                var fieldValue = $(this).val();
                var defaultPlaceholderValue = null;

                defaultPlaceholderValue = (fieldValue == 0) ? placeholderValue : fieldValue;
                if (fieldValue == 0) {

                    $(this).attr('placeholder', defaultPlaceholderValue);
                    $(this).val("");

                } else {

                    $(this).val(defaultPlaceholderValue);
                }
            });

            $(document).on('blur', '#productPrice', function() {
                // Does some stuff and logs the event to the console

                var placeholderValue = $(this).attr('placeholder');
                var fieldValue = $(this).val();
                var defaultPlaceholderValue = null;

                defaultPlaceholderValue = (fieldValue == 0) ? placeholderValue : fieldValue;

                $(this).val(defaultPlaceholderValue);
            });

            //Datepicker
            $('#datepicker').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy'
            });

            //iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });
            //Red color scheme for iCheck
            $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
                checkboxClass: 'icheckbox_minimal-red',
                radioClass: 'iradio_minimal-red'
            });
            //Flat red color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });

        });
    </script>

    @if ($errors->any())
    @foreach ($errors->all() as $error)
    <script>
        toastr.error('{{ $error }}');
    </script>
    @endforeach
    @endif
    @endpush
