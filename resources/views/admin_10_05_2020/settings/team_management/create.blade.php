<form id="frmTeamUser" action="{{ !isset($team_users)?route('team.store') : route('team.update',$team_id) }}"
method="{{ !isset($team_users)?'post' : 'patch' }}">
<section class="content">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="cmbUserGroup">Team <small style="color:red">*</small></label>
                <select name="team_name" id="team_name" class="form-control required" style="width: 100%;" required="required" aria-hidden="true">
                    <option value="0">Select</option>
                    @foreach ($team_arr as $key => $team)
                    <option value="{{ $key }}" selected>{{ $team }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Team Sequence</label>
                <input class="form-control" type="text" name="team_seq" value="{{ $teamSeq }}" />
            </div>

            <div class="form-group">
                <label for="cmbUserGroup">Agent Type <small style="color:red">*</small></label>
                <select name="agent_type" id="agent_type" class="form-control required" style="width: 100%;" required="required" aria-hidden="true">
                    <option value="0">Select</option>
                    @foreach ($agent_type as $key => $agent)
                    @if($key == $agentType)
                    <option value="{{ $key }}" selected="selected">{{ $agent }}</option>
                    @else
                    <option value="{{ $key }}">{{ $agent }}</option>
                    @endif
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="user_name">User Name <small style="color:red">*</small></label>
                <select name="user_name" id="user_name" class="form-control" style="width: 100%;" required="required" aria-hidden="true">
                    <option value="0">Select</option>
                    @if (!empty($user_arr))
                    @foreach ($user_arr as $key => $user)
                    <option value="{{ $key }}">{{ $user }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select class="form-control" name="category" style="width: 100%;" aria-hidden="true">
                    <option value="0">Please Select Category</option>
                    @if(!empty($project_cat))
                    @foreach ($project_cat as $key => $cat)

                    @if($key == $category)
                    <option value="{{ $key }}" selected="selected">{{ $cat }}</option>
                    @else
                    <option value="{{ $key }}">{{ $cat }}</option>
                    @endif

                    @endforeach
                    @endif
                </select>
            </div>

            <div class="form-group">
                <label>Area</label>
                <select class="form-control select2"  multiple="multiple" name="area[]" style="width: 100%;" aria-hidden="true">
                    <option value="0">Please Select Area</option>
                    @if(!empty($project_area))

                    @php
                    $area_ids_arr = array_unique(explode(",", $area_ids));
                    foreach ($area_ids_arr as $area_id)
                    {
                        $selected[$area_id] = $area_id;
                    }
                    @endphp

                    @foreach ($project_area as $key => $area)
                    @if(isset($selected[$key]))
                    <option value="{{ $key }}" selected="selected">{{ $area }}</option>
                    @else
                    <option value="{{ $key }}">{{ $area }}</option>
                    @endif
                    @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="col-md-8">
            <label>Team Member List</label>
            <div class="box">

                <table id="user-table" width="50%" class="table table-bordered table-striped table-hover data-table">
                    <thead>
                        <tr>
                            <th>Team User</th>
                            <th class="text-center" title="Head Of Department">Is HOD?</th>
                            <th class="text-center" title="Head Of Team">Is HOT?</th>
                            <th class="text-center" title="Team Leader">Is TL?</th>
                            <th class="text-center" title="Team Leader">Seq.</th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>

                    <tbody>
                        @if (!empty($team_users))
                        @foreach ($team_users as $user)
                        <tr>
                            <td>
                                {{ isset($user_arr[$user->user_pk_no])?$user_arr[$user->user_pk_no]:'' }}
                                <input type="hidden" name="txtUserID[]" value="{{ $user->user_pk_no }}" />
                                <input type="hidden" name="teammem_id[]" value="{{ $user->teammem_pk_no }}" />
                            </td>
                            <td align="center"><input type="checkbox" name="chkIsHod{{ $user->user_pk_no }}[]" {{ ($user->hod_flag==1)?'checked':'' }}  /></td>
                            <td align="center"><input type="checkbox" name="chkIsHot{{ $user->user_pk_no }}[]" {{ ($user->hot_flag==1)?'checked':'' }}  /></td>
                            <td align="center"><input type="checkbox" name="chkIsTL{{ $user->user_pk_no }}[]" {{ ($user->team_lead_flag==1)?'checked':'' }}  /></td>
                            <td><input class="form-control" style="width: 50px;" type="text" name="teammem_seq{{ $user->user_pk_no }}" value="{{ ($user->sl_no!='')? ($user->sl_no>0)?$user->sl_no:'':'' }}" /></td>
                            <td class="text-center">
                                <span class="btn bg-danger btn-xs remove-member" data-id="{{ $user->teammem_pk_no }}" ><i class="fa fa-close"></i></span>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<div class="modal-footer">
    <button type="button" class="btn btn-danger btn-sm pull-left" data-dismiss="modal">Close</button>
    <button type="submit" class="btn bg-purple btn-sm btnSaveUpdate">{{ (empty($team_users)) ?'Save' : 'Update' }}</button>
    <span class="msg"></span>
</div>
</form>