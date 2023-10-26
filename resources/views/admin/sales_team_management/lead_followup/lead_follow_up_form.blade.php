<form id="frmLeadFollowup" action="{{ route('lead_follow_up.store') }}" method="post">
    <input type="hidden" class="keep_me" name="hdn_lead_pk_no" value="{{ $lead_data->lead_pk_no }}" />
    <input type="hidden" class="keep_me" name="hdn_lead_followup_pk_no"
        value="{{ $lead_data->lead_followup_pk_no }}" />
    <input type="hidden" class="keep_me" name="hdn_cur_stage" value="{{ $lead_data->lead_current_stage }}" />
    <input type="hidden" class="keep_me" name="hdn_life_cycle_id"
        value="{{ $lead_data->leadlifecycle_pk_no }}" />

    @php
        if ($lead_data->lead_current_stage == 1) {
            $stages = [3, 8, 9, 10, 11, 6];
        }
        if ($lead_data->lead_current_stage == 8) {
            $stages = [3, 4, 5, 6, 9];
        }
        if ($lead_data->lead_current_stage == 10) {
            $stages = [3, 6, 8, 9, 11];
        }
        if ($lead_data->lead_current_stage == 11) {
            $stages = [3, 6, 8, 9, 10];
        }
        if (in_array($lead_data->lead_current_stage, [6, 9])) {
            $stages = [1, 3];
        }
        if ($lead_data->lead_current_stage == 3) {
            $stages = [4, 5, 6, 9, 8];
        }
        if ($lead_data->lead_current_stage == 4) {
            $stages = [5, 6, 9];
        }
        if ($lead_data->lead_current_stage == 5) {
            $stages = [3, 4, 6, 9];
        }
        $lead_type_crc = isset($lead_data->lead_type) ? $lead_data->lead_type : 0;
        $lead_current_stage = $lead_data->lead_current_stage;
    @endphp
    <div class="row">

        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="">
                        <label>Lead Type<span class="text-danger"> *</span></label>
                        <input type="text" readonly="readonly" class="form-control"
                            value="{{ isset($lead_type[@$lead_data->lead_type]) ? $lead_type[$lead_data->lead_type] : '' }}">
                    </div>
                </div>

                @include('admin.sales_team_management.lead_followup.lead_follow_up_popup_elements')

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Change Stage :</label>
                        <select class="form-control select2 select2-hidden-accessible" style="width: 100%;"
                            aria-hidden="true" name="cmb_change_stage" id="cmb_change_stage">
                            <option selected="selected" value="0">Please Select Stage</option>
                            @if (empty($stages))
                                @php
                                    $stages = [];
                                @endphp
                            @endif
                            @if (!empty($lead_stage_arr))
                                @foreach ($lead_stage_arr as $key => $stage)
                                    @if (in_array($key, $stages))
                                        <option value="{{ $key }}">{{ $stage }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Area<span class="text-danger"> *</span></label>
                        <select class="form-control" required id="cmb_area" name="cmb_area" style="width: 100%;"
                            aria-hidden="true">
                            <option selected="selected" value="">Select Area</option>
                            @if (!empty($project_area))
                                @foreach ($project_area as $key => $area)
                                    <option
                                        @if ($lead_data->project_area_pk_no == $key) selected value="{{ $key }}" @else value="{{ $key }}" @endif>
                                        {{ $area }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Category<span class="text-danger"></span></label>
                        <select class="form-control" id="cmb_category" name="cmb_category"
                            data-action="{{ route('load_area_project_size') }}" aria-hidden="true">
                            <option selected="selected" value="0">Select Category</option>
                            @if (!empty($cat_arr))
                                @foreach ($cat_arr as $key => $cate)
                                    <option
                                        @if ($lead_data->project_category_pk_no == $key) selected value="{{ $key }}" @else value="{{ $cate }}" @endif>
                                        {{ $cate }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Project Name<span class="text-danger"></span></label>
                        <select class="form-control" id="cmb_project_name" name="cmb_project_name"
                            data-action="{{ route('load_project_size') }}" style="width: 100%;" aria-hidden="true">
                            <option value="">Select Project Name</option>

                            @if (!empty($project_arr))
                                @foreach ($project_arr as $key => $project)
                                    <option
                                        @if ($lead_data->Project_pk_no == $key) selected value="{{ $key }}" @else value="{{ $project }}" @endif>
                                        {{ $project }}</option>
                                @endforeach
                            @endif

                        </select>
                    </div>
                </div>

                {{-- <div class="col-md-6">
                    <div class="form-group">
                        <label>Size<span class="text-danger"></span></label>
                        <select class="form-control" id="cmb_size" name="cmb_size" style="width: 100%;" aria-hidden="true">
                            <option selected="selected" value="">Select Size</option>
                            if(!empty($project_size))
                            foreach ($project_size as $key => $size)
                            <option value="$key ">$size </option>
                            endforeach
                            endif
                        </select>
                    </div>
                </div> --}}

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Size<span class="text-danger"></span></label>
                        <select class="form-control" id="cmb_size" name="cmb_size" style="width: 100%;"
                            aria-hidden="true">
                            <option selected="selected" value="0">Select Size</option>
                            @if (!empty($size_arr))
                                @foreach ($size_arr as $key => $project_size)
                                    <option
                                        @if ($lead_data->project_size_pk_no == $key) selected value="{{ $key }}" @else value="{{ $project_size }}" @endif>
                                        {{ $project_size }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div id="close_reason_div" class="form-group hidden">
                        <label>Reason For Closed<span class="text-danger">
                                *</span></label>
                        <select class="form-control " id="close_reason" name="close_reason" style="width: 100%;"
                            aria-hidden="true">
                            <option value="">Select Reason</option>
                            {{-- bti Sales: Below Close Reasons and conditions are applicable. --}}
                            @if ($lead_type_crc == 1)
                                @if ($lead_current_stage == 1)
                                    <option value="Not Valid">Not Valid</option>
                                    <option value="Location Issue">Location Issue</option>
                                    <option value="Size Issue">Size Issue</option>
                                    <option value="Demand Ready Project">Demand Ready Project</option>
                                    <option value="Design Issue">Design Issue</option>
                                    <option value="Price Issue">Price Issue</option>
                                @endif
                                @if ($lead_current_stage == 3)
                                    <option value="Location Issue">Location Issue</option>
                                    <option value="Design Issue">Design Issue</option>
                                    <option value="Price Issue">Price Issue</option>
                                    <option value="Size Issue">Size Issue</option>
                                    <option value="Legal Issue">Legal Issue</option>
                                        <option value="No. of Bed Room">No. of Bed Room</option>
                                        <option value="Payment Mode">Payment Mode</option>
                                @endif
                                @if ($lead_current_stage == 4)
                                    <option value="Price Issue">Price Issue</option>
                                    <option value="Legal Issue">Legal Issue</option>
                                        <option value="No. of Bed Room">No. of Bed Room</option>
                                        <option value="Payment Mode">Payment Mode</option>
                                @endif
                                    @if ($lead_current_stage == 10)
                                        <option value="Not Valid">Not Valid</option>
                                        <option value="Location Issue">Location Issue</option>
                                        <option value="Budget Not Match">Budget Not Match</option>
                                        <option value="Size Issue">Size Issue</option>
                                        <option value="Demand Ready Project">Demand Ready Project</option>
                                        <option value="Design Issue">Design Issue</option>
                                        <option value="Price Issue">Price Issue</option>
                                    @endif
                                    @if ($lead_current_stage == 11)
                                        <option value="Not Valid">Not Valid</option>
                                        <option value="Location Issue">Location Issue</option>
                                        <option value="Budget Not Match">Budget Not Match</option>
                                        <option value="Size Issue">Size Issue</option>
                                        <option value="Demand Ready Project">Demand Ready Project</option>
                                        <option value="Design Issue">Design Issue</option>
                                        <option value="Price Issue">Price Issue</option>
                                    @endif
                                    @if ($lead_current_stage == 5)
                                        <option value="Location Issue">Location Issue</option>
                                        <option value="Design Issue">Design Issue</option>
                                        <option value="Price Issue">Price Issue</option>
                                        <option value="Size Issue">Size Issue</option>
                                        <option value="Legal Issue">Legal Issue</option>
                                        <option value="No. of Bed Room">No. of Bed Room</option>
                                        <option value="Payment Mode">Payment Mode</option>
                                    @endif
                                    @if ($lead_current_stage == 8)
                                        <option value="Location Issue">Location Issue</option>
                                        <option value="Design Issue">Design Issue</option>
                                        <option value="Price Issue">Price Issue</option>
                                        <option value="Size Issue">Size Issue</option>
                                        <option value="Legal Issue">Legal Issue</option>
                                        <option value="No. of Bed Room">No. of Bed Room</option>
                                        <option value="Payment Mode">Payment Mode</option>
                                    @endif


                            @endif
                            {{-- SFS : Below Close Reasons and  conditions are applicable. --}}
                            @if ($lead_type_crc == 5 || $lead_type_crc == 10 || $lead_type_crc == 4 )
                                @if ($lead_current_stage == 1)
                                    <option value="No Response">No Response</option>
                                    <option value="Not Valid">Not Valid</option>
                                    <option value="Location Issue">Location Issue</option>
                                    <option value="Size Issue">Size Issue</option>
                                @endif
                                @if ($lead_current_stage == 3)
                                    <option value="No Response">No Response</option>
                                    <option value="Price Issue">Price Issue</option>
                                    <option value="Design Issue">Design Issue</option>
                                    <option value="Quality Issue">Quality Issue</option>
                                    <option value="Own Source">Own Source</option>
                                    <option value="Rent Issue">Rent Issue</option>
                                @endif
                                @if ($lead_current_stage == 4)
                                    <option value="No Response">No Response</option>
                                    <option value="Price Issue">Price Issue</option>
                                    <option value="Design Issue">Design Issue</option>
                                    <option value="Quality Issue">Quality Issue</option>
                                    <option value="Own Source">Own Source</option>
                                    <option value="Rent Issue">Rent Issue</option>
                                @endif
                                    @if ($lead_current_stage == 10)
                                        <option value="No Response">No Response</option>
                                        <option value="Not Valid">Not Valid</option>
                                        <option value="Location Issue">Location Issue</option>
                                        <option value="Size Issue">Size Issue</option>

                                    @endif
                                    @if ($lead_current_stage == 11)
                                        <option value="No Response">No Response</option>
                                        <option value="Not Valid">Not Valid</option>
                                        <option value="Location Issue">Location Issue</option>
                                        <option value="Size Issue">Size Issue</option>

                                    @endif
                                    @if ($lead_current_stage == 5)
                                        <option value="Price Issue">Price Issue</option>
                                        <option value="Design Issue">Design Issue</option>
                                        <option value="Quality Issue">Quality Issue</option>
                                        <option value="Own Source">Own Source</option>
                                        <option value="Rent Issue">Rent Issue</option>

                                    @endif
                                    @if ($lead_current_stage == 8)
                                        <option value="Price Issue">Price Issue</option>
                                        <option value="Design Issue">Design Issue</option>
                                        <option value="Quality Issue">Quality Issue</option>
                                        <option value="Own Source">Own Source</option>
                                        <option value="Rent Issue">Rent Issue</option>

                                    @endif
                            @endif
                            {{-- BP  (Hollow Block): Below Close Reasons and  conditions are applicable. --}}
                            @if ($lead_type_crc == 8)
                                @if ($lead_current_stage == 1)
                                    <option value="No Response">No Response</option>
                                    <option value="Not Valid">Not Valid</option>
                                    <option value="Location Issue">Location Issue</option>
                                    <option value="Size Issue">Size Issue</option>
                                @endif
                                @if ($lead_current_stage == 3)
                                    <option value="No Response">No Response</option>
                                    <option value="Price Issue">Price Issue</option>
                                    <option value="Quality Issue">Quality Issue</option>
                                    <option value="Purchased from Others">Credit Issue</option>
                                @endif
                                @if ($lead_current_stage == 4)
                                <option value="No Response">No Response</option>
                                <option value="Price Issue">Price Issue</option>
                                <option value="Quality Issue">Quality Issue</option>
                                <option value="Purchased from Others">Credit Issue</option>
                                @endif

                                    @if ($lead_current_stage == 10)
                                        <option value="No Response">No Response</option>
                                        <option value="Not Valid">Not Valid</option>
                                        <option value="Location Issue">Location Issue</option>
                                        <option value="Size Issue">Size Issue</option>
                                    @endif
                                    @if ($lead_current_stage == 11)
                                        <option value="No Response">No Response</option>
                                        <option value="Not Valid">Not Valid</option>
                                        <option value="Location Issue">Location Issue</option>
                                        <option value="Size Issue">Size Issue</option>
                                    @endif
                                    @if ($lead_current_stage == 5)
                                        <option value="Price Issue">Price Issue</option>
                                        <option value="Quality Issue">Quality Issue</option>
                                        <option value="Credit Issue">Credit Issue</option>
                                    @endif
                                    @if ($lead_current_stage == 8)
                                        <option value="Price Issue">Price Issue</option>
                                        <option value="Quality Issue">Quality Issue</option>
                                        <option value="Credit Issue">Credit Issue</option>
                                    @endif

                            @endif



                            {{-- BD:  Below Close Reasons and it's conditions are applicable. --}}

                            @if ($lead_type_crc == 3)
                                @if ($lead_current_stage == 1)
                                    <option value="Land Size">Land Size</option>
                                    <option value="Location">Location</option>
                                    <option value="Road Condition">Road Condition</option>
                                    <option value="Land Owner Information">Land Owner Information</option>
                                @endif
                                @if ($lead_current_stage == 3)
                                    <option value="Signing Money">Signing Money</option>
                                    <option value="Land Owner Requirement">Land Owner Requirement</option>
                                    <option value="Land Document Issue">Land Document Issue</option>
                                    <option value="Logistic Obligation">Logistic Obligation</option>
                                    <option value="Decision Change">Decision Change</option>
                                @endif
                                @if ($lead_current_stage == 4)
                                    <option value="Decision Change">Decision Change</option>
                                    <option value="Deed Clauses">Deed Clauses</option>
                                    <option value="Original Land Document">Original Land Document</option>
                                    <option value="Design Issue">Design Issue</option>
                                    <option value="Legal Issue">Legal Issue</option>
                                    <option value="Competitor Aggressive Offer">Competitor Aggressive Offer</option>
                                @endif
                                    @if ($lead_current_stage == 10)
                                        <option value="Land Size">Land Size</option>
                                        <option value="Location">Location</option>
                                        <option value="Road Condition">Road Condition</option>
                                        <option value="Land Owner Information">Land Owner Information</option>
                                    @endif
                                    @if ($lead_current_stage == 11)
                                        <option value="Land Size">Land Size</option>
                                        <option value="Location">Location</option>
                                        <option value="Road Condition">Road Condition</option>
                                        <option value="Land Owner Information">Land Owner Information</option>

                                    @endif
                                    @if ($lead_current_stage == 5)
                                        <option value="Signing Money">Signing Money</option>
                                        <option value="Land Owner Requirement">Land Owner Requirement</option>
                                        <option value="Land Document Issue">Land Document Issue</option>
                                        <option value="Logistic Obligation">Logistic Obligation</option>
                                        <option value="Decision Change">Decision Change</option>

                                    @endif
                                    @if ($lead_current_stage == 8)
                                        <option value="Signing Money">Signing Money</option>
                                        <option value="Land Owner Requirement">Land Owner Requirement</option>
                                        <option value="Land Document Issue">Land Document Issue</option>
                                        <option value="Logistic Obligation">Logistic Obligation</option>
                                        <option value="Decision Change">Decision Change</option>

                                    @endif


                            @endif
                            {{-- Brokerage   'Brokerage (Sales)' and 'Brokerage (Rent Sales)' :  Below Close Reasons and it's conditions are applicable. --}}

                            @if ($lead_type_crc == 2 || $lead_type_crc == 11 )

                                @if ($lead_current_stage == 1)
                                    <option value="Location Issue">Location Issue</option>
                                    <option value="Not Interested">Not Interested</option>
                                    <option value="Budget">Budget</option>
                                    <option value="Not reachable">Not reachable</option>
                                    <option value="Not Actual Buyer/Mkt Survey">Not Actual Buyer/Mkt Survey</option>
                                    <option value="Broker">Broker</option>
                                @endif
                                @if ($lead_current_stage == 3)
                                    <option value="Bought from others">Bought from others</option>
                                    <option value="Budget">Budget</option>
                                    <option value="Not reachable">Not reachable</option>
                                @endif
                                @if ($lead_current_stage == 4)
                                    <option value="Bought from others">Bought from others</option>
                                    <option value="Legal/Documentation">Legal/Documentation</option>
                                    <option value="Payment Mode">Payment Mode</option>
                                    <option value="Budget">Budget</option>
                                    <option value="Not reachable">Not reachable</option>
                                @endif
                                    @if ($lead_current_stage == 10)
                                        <option value="Location Issue">Location Issue</option>
                                        <option value="Not Interested">Not Interested</option>
                                        <option value="Budget">Budget</option>
                                        <option value="Not reachable">Not reachable</option>
                                        <option value="Not Actual Buyer/Mkt Survey">Not Actual Buyer/Mkt Survey</option>
                                        <option value="Broker">Broker</option>
                                    @endif
                                    @if ($lead_current_stage == 11)
                                        <option value="Location Issue">Location Issue</option>
                                        <option value="Not Interested">Not Interested</option>
                                        <option value="Budget">Budget</option>
                                        <option value="Not reachable">Not reachable</option>
                                        <option value="Not Actual Buyer/Mkt Survey">Not Actual Buyer/Mkt Survey</option>
                                        <option value="Broker">Broker</option>
                                    @endif
                                    @if ($lead_current_stage == 5)
                                        <option value="Bought from others">Bought from others</option>
                                        <option value="Budget">Budget</option>
                                        <option value="Not reachable">Not reachable</option>
                                    @endif
                                    @if ($lead_current_stage == 8)
                                        <option value="Bought from others">Bought from others</option>
                                        <option value="Budget">Budget</option>
                                        <option value="Not reachable">Not reachable</option>
                                    @endif
                            @endif



                            {{-- Brokerage   'Brokerage (Rent Inventory)' and 'Brokerage (Sales Inventory)' :  Below Close Reasons and it's conditions are applicable. --}}
                            @if ( $lead_type_crc == 7 || $lead_type_crc == 9)

                                @if ($lead_current_stage == 1)
                                    <option value="Not a qualified lead">Not a qualified lead</option>
                                    <option value="Not Actual Seller">Not Actual Seller</option>
                                    <option value="Broker">Broker</option>
                                    <option value="Service charge not accepted">Service charge not accepted</option>
                                    <option value="Not Interested">Not Interested</option>
                                    <option value="Others">Others</option>
                                @endif
                                @if ($lead_current_stage == 3)
                                    <option value="Sold By Seller">Sold By Seller</option>
                                    <option value="Budget">Budget</option>
                                    <option value="Documentation Issue">Documentation Issue</option>
                                    <option value="Others">Others</option>
                                @endif
                                @if ($lead_current_stage == 4)
                                    <option value="Price">Price</option>
                                    <option value="Legal">Legal</option>
                                    <option value="Sold by seller">Sold by seller</option>
                                    <option value="Others">Others</option>
                                @endif
                                    @if ($lead_current_stage == 10)
                                        <option value="Location Issue">Location Issue</option>
                                        <option value="Not Interested">Not Interested</option>
                                        <option value="Budget">Budget</option>
                                        <option value="Not reachable">Not reachable</option>
                                        <option value="Not Actual Buyer/Mkt Survey">Not Actual Buyer/Mkt Survey</option>
                                        <option value="Broker">Broker</option>
                                    @endif
                                    @if ($lead_current_stage == 11)
                                        <option value="Location Issue">Location Issue</option>
                                        <option value="Not Interested">Not Interested</option>
                                        <option value="Budget">Budget</option>
                                        <option value="Not reachable">Not reachable</option>
                                        <option value="Not Actual Buyer/Mkt Survey">Not Actual Buyer/Mkt Survey</option>
                                        <option value="Broker">Broker</option>
                                    @endif
                                    @if ($lead_current_stage == 5)
                                        <option value="Bought from others">Bought from others</option>
                                        <option value="Budget">Budget</option>
                                        <option value="Not reachable">Not reachable</option>
                                    @endif
                                    @if ($lead_current_stage == 8)
                                        <option value="Bought from others">Bought from others</option>
                                        <option value="Budget">Budget</option>
                                        <option value="Not reachable">Not reachable</option>
                                    @endif
                            @endif

                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label>Followup Type :</label>
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true"
                    name="cmbFollowupType">
                    <option selected="selected" value="0">Please Select Followup Type</option>
                    @if (!empty($followup_type))
                        @foreach ($followup_type as $key => $f_type)
                            <option value="{{ $key }}">{{ $f_type }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="form-group">
                <label for="followup_note">Note :</label>
                <textarea class="form-control" style="height: auto !important;" rows="3" id="followup_note"
                    name="followup_note" title="Note" placeholder="Write Followup Note ..."></textarea>
            </div>

            <div>
                <div class="box-header with-border" style="padding-left: 0 !important;">
                    <h3 class="box-title">Next Followup</h3>
                </div>
                <div class="form-group">
                    <label>Next Followup Date :</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right required" id="txt_followup_date"
                            name="txt_followup_date">
                    </div>
                </div>
                <div class="form-group">
                    <label>Prefered Time :</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                        <input type="text" class="form-control pull-right" id="txt_followup_date_time"
                            name="txt_followup_date_time">
                    </div>
                </div>

                <div class="form-group">
                    <label for="next_followup_note">Note :</label>
                    <textarea class="form-control" rows="3" style="height: auto !important;" id="next_followup_note"
                        name="next_followup_note" title="Note" placeholder="Next Followup Note ..."></textarea>
                </div>
            </div>
        </div>



        <div class="col-md-12">
            <h4>K Qualification</h4>
            <div class="row">
                <div class="form-radio form-radio-inline col-md-3">
                    <input class="form-radio-input" type="radio" value="Match With Location" id="match_location"
                        name="k_qualification">
                    <label class="form-radio-label" for="match_location">Match With Location</label>
                </div>

                <div class="form-radio form-radio-inline col-md-3">
                    <input class="form-radio-input" type="radio" id="ready_ongoing_flat" name="k_qualification"
                        value="Match With Location">
                    <label class="form-radio-label" for="ready_ongoing_flat">Match With Location</label>
                </div>
                <div class="form-radio form-radio-inline col-md-3">
                    <input class="form-radio-input" id="square_feet" name="k_qualification" type="radio"
                        value="Match the size/square feet">
                    <label class="form-radio-label" for="square_feet">Match the size/square feet</label>
                </div>
                <div class="form-radio form-radio-inline col-md-3">
                    <input class="form-radio-input" type="radio" value="Match the budget" id="match_budget"
                        name="k_qualification">
                    <label class="form-radio-label" for="match_budget">Match the budget</label>
                </div>
                <div class="form-radio form-radio-inline col-md-3">
                    <input class="form-radio-input" type="radio" value="Floor/Design preferance" id="floor_design"
                        name="k_qualification">
                    <label class="form-radio-label" for="floor_design">Floor/Design preferance</label>
                </div>
                <div class="form-radio form-radio-inline col-md-3">
                    <input class="form-radio-input" type="radio" value="Time of Purchase" id="time_purchase"
                        name="k_qualification">
                    <label class="form-radio-label" for="time_purchase">Time of Purchase</label>
                </div>
            </div>
        </div>


        <div class="col-md-12">
            <h4>K Status</h4>
            <div class="row">
                <div class="form-radio form-radio-inline col-md-3">
                    <input class="form-radio-input" type="radio" value="Customer Meet" id="customer_meet"
                        name="k_status">
                    <label class="form-radio-label" for="customer_meet">Customer Meet</label>
                </div>

                <div class="form-radio form-radio-inline col-md-3">
                    <input class="form-radio-input" id="project_visit" name="k_status" type="radio"
                        value="Project Visit">
                    <label class="form-radio-label" for="project_visit">Project Visit</label>
                </div>
                <div class="form-radio form-radio-inline col-md-3">
                    <input class="form-radio-input" id="product_accepted" name="k_status" type="radio"
                        value="Product Accepted">
                    <label class="form-radio-label" for="product_accepted">Product Accepted</label>
                </div>
                <div class="form-radio form-radio-inline col-md-3">
                    <input class="form-radio-input" type="radio" value="Modification Worlk" id="modification_work"
                        name="k_status">
                    <label class="form-radio-label" for="modification_work">Modification Worlk</label>
                </div>
                <div class="form-radio form-radio-inline col-md-3">
                    <input class="form-radio-input" type="radio" value="Price Negotiated" id="price_negotiated"
                        name="k_status">
                    <label class="form-radio-label" for="price_negotiated">Price Negotiated</label>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <h4>P Status</h4>
            <div class="row">
                <div class="form-radio form-radio-inline col-md-3">
                    <input class="form-radio-input" type="radio" value="Hold Office Visit" id="hold_ofice_visit"
                        name="p_status">
                    <label class="form-radio-label" for="hold_ofice_visit">Hold Office Visit</label>
                </div>

                <div class="form-radio form-radio-inline col-md-3">
                    <input class="form-radio-input" id="modification_work_done" name="p_status" type="radio"
                        value="Modification Worlk Done">
                    <label class="form-radio-label" for="modification_work_done">Modification Worlk Done</label>
                </div>
                <div class="form-radio form-radio-inline col-md-3">
                    <input class="form-radio-input" id="price_finalization" name="p_status" type="radio"
                        value="Price FinaliZation">
                    <label class="form-radio-label" for="price_finalization">Price FinaliZation</label>
                </div>
                <div class="form-radio form-radio-inline col-md-3">
                    <input class="form-radio-input" type="radio" value="Loan Confirm" id="loan_confirm"
                        name="p_status">
                    <label class="form-radio-label" for="loan_confirm">Loan Confirm</label>
                </div>
                <div class="form-radio form-radio-inline col-md-3">
                    <input class="form-radio-input" type="radio" value="Booking Money" id="booking_money"
                        name="p_status">
                    <label class="form-radio-label" for="booking_money">Booking Money</label>
                </div>
            </div>
        </div>




    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        @if ($page == '')
            <button type="submit" class="btn btn-success btn-sm btnSaveUpdate"
                data-response-action="{{ route('load_followup_leads') }}" data-tab="1">Save</button>
        @else
            <button type="submit" class="btn btn-success btn-sm btnSaveUpdateFollowup"
                data-response-action="{{ route('lead_list', $type) }}">Save</button>
        @endif
    </div>
</form>
<script>
    $("#cmb_change_stage").change(function() {
        var stage = $(this).val();
        if (stage == 6) {
            $("#close_reason_div").removeClass("hidden");
            $("#close_reason").addClass("required");
            $("#txt_followup_date").removeClass("required");
            $("#txt_followup_date").removeAttr("style");


        } else {
            $("#close_reason_div").addClass("hidden");
            $("#txt_followup_date").addClass("required");
        }
    });
</script>
