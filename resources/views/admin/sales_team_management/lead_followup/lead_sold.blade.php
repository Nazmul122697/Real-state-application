<form id="frmLeadFollowup" action="{{ route('store_lead_sold') }}" method="post">
    <div class="row">
        <div class="col-md-8">

            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="">
                        <label>Lead Type<span class="text-danger"> *</span></label>
                        <input type="text" readonly="readonly" class="form-control"
                            value="{{ isset($lead_type[$lead_data->lead_type]) ? $lead_type[$lead_data->lead_type] : '' }}">
                    </div>
                </div>
                @include('admin.sales_team_management.lead_followup.lead_follow_up_popup_elements')

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Stage :</label>
                        <input type="text" class="form-control" id="current_stage" name="current_stage"
                            value="{{ isset($lead_data) ? $lead_stage_arr[$lead_data->lead_current_stage] : '' }}"
                            title="" readonly="readonly" placeholder="Current Stage" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            @if ($lead_data->lead_type == 1 || $lead_data->lead_type == 2)
                <div class="form-group">
                    <label for="flat">Flat :</label>
                    <select class="form-control required" name="flat" style="width: 100%;" aria-hidden="true"
                        required="required">
                        <option selected="selected" value="">Select Flat</option>
                        @if (!empty($flat_list))
                            @foreach ($flat_list as $flat)
                                <option value="{{ $flat->flatlist_pk_no }}">{{ $flat->flat_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            @endif
            @if ($lead_data->lead_type == 1 || $lead_data->lead_type == 2)
                <div class="form-group">
                    <label for="">Flat Cost :</label>
                    <input type="text" class="form-control required number-only calculate-total-sold text-right"
                        id="flat_cost" name="flat_cost" value="" title="" placeholder="Flat Cost" />
                </div>

                <div class="form-group">
                    <label for="">Utility :</label>
                    <input type="text" class="form-control required number-only calculate-total-sold text-right"
                        id="utility" name="utility" value="" title="" placeholder="Utility Cost" />
                </div>
                <div class="form-group">
                    <label for="">Parking :</label>
                    <input type="text" class="form-control required number-only calculate-total-sold text-right"
                        id="parking" name="parking" value="" title="" placeholder="Parking Cost" />
                </div>
                <div class="form-group">
                    <label for=""><strong>Date of Sold :</strong></label>
                    <input type="text" class="form-control required datepicker" id="date_of_sold" name="date_of_sold"
                        value="<?php echo date('d-m-Y'); ?>" readonly="readonly" title=""
                        placeholder="Date of Sold" />
                </div>
                <div class="form-group">
                    <label for=""><strong>Grand Total :</strong></label>
                    <input type="text" class="form-control text-right" id="grand-total" name="grand-total" value=""
                        readonly="readonly" title="" placeholder="Grand Total" />
                </div>
            @endif
            
            @if ($lead_data->lead_type == 3 || $lead_data->lead_type == 8 || $lead_data->lead_type == 4 || $lead_data->lead_type == 5 || $lead_data->lead_type == 7 || $lead_data->lead_type == 10 || $lead_data->lead_type == 6 || $lead_data->lead_type == 9 ||$lead_data->lead_type == 11)
                <div class="form-group" style="padding-top:43px">
                    <label for="flat">Flat :</label>
                    <select class="form-control required" name="flat" style="width: 100%;" aria-hidden="true"
                        required="required">
                        <option selected="selected" value="">Select Flat</option>
                        @if (!empty($flat_list))
                            @foreach ($flat_list as $flat)
                                <option value="{{ $flat->flatlist_pk_no }}">{{ $flat->flat_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group" >
                    <label for=""><strong>Date of Aggreement :</strong></label>
                    <input type="text" class="form-control required datepicker" id="date_of_sold" name="date_of_sold"
                        value="<?php echo date('d-m-Y'); ?>" title=""
                        placeholder="Date of Sold" />
                </div>
                <div class="form-group">
                    <label for="">Given Value</label>
                    <input type="text" class="form-control" placeholder="Given Value" name="given_value">
                </div>
                <div class="form-group">
                    <input type="checkbox" value="1" id="deal_done" name="deal_done"> &nbsp; <label for="deal_done">Deal
                        Done</label>
                </div>
            @endif
            <div class="form-group">
                <button type="submit" class="btn btn-block bg-green btnSaveUpdate"
                    data-response-action="{{ route('load_followup_leads') }}">Update Lead</button>
            </div>
        </div>
    </div>
</form>
