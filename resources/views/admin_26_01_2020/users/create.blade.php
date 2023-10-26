<form id="frmUser" action="{{ !isset($user)?route('user.store') : route('user.update',$user->id) }}"
      method="{{ !isset($user)?'post' : 'patch' }}">
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
                        <option value="">Select</option>

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
            </div>
        </div>
    </section>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm pull-left" data-dismiss="modal">Close</button>
        <button type="submit"
                class="btn bg-purple btn-sm btnSaveUpdate" data-response-action="{{ route('load_users') }}" >{{ isset($user)? 'Update User':'Create User' }}</button>
        <span class="msg"></span>
    </div>
</form>