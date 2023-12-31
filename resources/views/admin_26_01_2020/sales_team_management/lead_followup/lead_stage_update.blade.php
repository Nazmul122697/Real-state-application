<form id="frmLeadFollowup" action="{{ route('store_stage_update') }}" method="post">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                @include('admin.sales_team_management.lead_followup.lead_follow_up_popup_elements')

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="current_stage">Current Stage :</label>
                        <input type="text" class="form-control" id="current_stage" name="current_stage" value="{{ isset($lead_data)? $lead_stage_arr[$lead_data->lead_current_stage]:'' }}" title="" readonly="readonly" placeholder="Current Stage"/>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>New Stage<span class="text-danger">*</span> :</label>
                        <select class="form-control select2 select2-hidden-accessible required" name="new_stage" style="width: 100%;" aria-hidden="true">
                            <option value="">Select New Stage</option>
                            @if(!empty($lead_stage_arr))
                            @foreach ($lead_stage_arr as $key => $stage)
                            @if( !in_array($key,[6,7]) )
                            <option value="{{ $key }}">{{ $stage }}</option>
                            @endif
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success btn-sm btnSaveUpdate" data-response-action="{{ route('load_followup_leads') }}" data-tab="1">Update Stage</button>
    </div>
</form>
