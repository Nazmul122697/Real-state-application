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
        <div class="box-body">
            <div class="row">
                <form id="frmUserUpdate" action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="hdnUserId" name="hdnUserId" value=" {{ isset($user)? $user->id:'' }} "/>
                    <section class="content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cmbUserGroup">User Group
                                        <small style="color:red">*</small>
                                    </label>
                                    <select name="cmbUserGroup" id="cmbUserGroup" class="form-control"
                                    style="width: 100%;" required="required" aria-hidden="true">
                                    <option value="0">Select</option>

                                    @foreach ($user_group as $key => $group)
                                    @if (!empty($user) && $user->role == $key)
                                    <option value="{{ $key }}" selected>{{ $group }}</option>
                                    @else
                                    <option value="{{ $key }}">{{ $group }}</option>
                                    @endif

                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="cmbUserType">User Type
                                    <small style="color:red">*</small>
                                </label>
                                <select name="cmbUserType" id="cmbUserType" class="form-control"
                                style="width: 100%;" required="required" aria-hidden="true">
                                <option value="0">Select</option>
                                @foreach ($user_type as $key => $type)
                                @if (!empty($user) && $user->teamUser['user_type'] == $key)
                                <option value="{{ $key }}" selected>{{ $type }}</option>
                                @else
                                <option value="{{ $key }}">{{ $type }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="txtUserName">Name
                                <small style="color:red">*</small>
                            </label>
                            <input type="text" class="form-control required" id="txtUserName" name="txtUserName"
                            placeholder="Ex: Jahid Hasan" title="User Name" value="{{ isset($user)? $user->name:'' }}"
                            required="required" tabindex="1">
                        </div>

                        <div class="form-group">
                            <label for="txtContract">Contact
                                <small style="color:red">*</small>
                            </label>
                            <input type="text" class="form-control required" id="txtContract" name="txtContract"
                            title="User Contact Number" placeholder="Ex: 017XXXXXXXX"
                            value="{{ isset($user)? $user->phone:'' }}" required="required" tabindex="3">
                        </div>

                        <div class="form-group">
                            <label for="txtAddress">Address</label>
                            <textarea class="form-control" id="add_user_address" name="txtAddress" title="User Address" rows="1" placeholder="Ex: New York, United State" tabindex="4">{{ isset($user)? $user->address:'' }}</textarea>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="txtEmail">Email
                                <small style="color:red">*</small>
                            </label>
                            <input type="text" class="form-control required" id="txtEmail" name="txtEmail" title="User Email"
                            placeholder="Ex: jahid@gmail.com" value="{{ isset($user)? $user->email:'' }}"
                            required="required" tabindex="2">
                        </div>

                        <div class="form-group">
                            <label for="txtEmail">Password
                                <small style="color:red">*</small>
                            </label>
                            <input type="password" class="form-control required" id="pwdPassword" name="pwdPassword"
                            title="User Password" placeholder="Type Password" value="" required="required" tabindex="2">
                        </div>

                        <div class="form-group">
                            <label for="txtEmail">Re-type Password
                                <small style="color:red">*</small>
                            </label>
                            <input type="password" class="form-control required" id="password_confirm" name="password_confirm"
                            title="Re-type Password" placeholder="Type Password" value="" required="required" tabindex="2">
                        </div>
                        <div class="form-group">
                            <label for="txtEmail">Profile Picture
                                <small style="color:red">*</small>
                            </label>
                            <input type="file" class="required" id="image" name="image" >
                        </div>
                    </div>
                </div>
            </section>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn bg-purple btn-sm" >Update</button>
                <span class="msg"></span>
            </div>
        </form>
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

        // $(document).on("submit", "frmUserUpdate", function(event)
        // {
        //     event.preventDefault();

        //     var url=$(this).attr("action");
        //     $.ajax({
        //         url: url,
        //         type: $(this).attr("method"),
        //         data: new FormData(this),
        //         processData: false,
        //         contentType: false,
        //         success: function (data, status)
        //         {

        //         },
        //         error: function (xhr, desc, err)
        //         {


        //         }
        //     });

        // });

    });
</script>
@endpush
