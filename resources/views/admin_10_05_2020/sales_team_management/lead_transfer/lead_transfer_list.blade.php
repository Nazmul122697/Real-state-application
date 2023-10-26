<div class="tab-pane active table-responsive" id="lead_transfer">
	<div class="head_action"
	style="background-color: #ECF0F5; text-align: right; border: 1px solid #ccc; padding: 3px;">

	<div class="box-body">
		<div class="row">
			<div class="col-md-2">
				<div class="form-group">
					<label>Category<span class="text-danger"> *</span></label>
					<select class="form-control required" id="cmb_category" name="cmb_category" data-action="{{ route('load_area_project_size') }}" aria-hidden="true">
						<option selected="selected" value="0">Select Category</option>
						@if(!empty($project_cat))
						@foreach ($project_cat as $key => $cat)
						<option value="{{ $key }}">{{ $cat }}</option>
						@endforeach
						@endif
					</select>
				</div>
			</div>

			<div class="col-md-2">
				<div class="form-group">
					<label>Area<span class="text-danger"> *</span></label>
					<select class="form-control required" id="cmb_area" name="cmb_area" style="width: 100%;" aria-hidden="true">
						<option selected="selected" value="">Select Area</option>
					</select>
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>Project Name<span class="text-danger"> *</span></label>
					<select class="form-control required" id="cmb_project_name" name="cmb_project_name" style="width: 100%;" aria-hidden="true">
						<option selected="selected" value="">Select Project Name</option>
					</select>
				</div>
			</div>

			<div class="col-md-2">
				<div class="form-group">
					<label>Size<span class="text-danger"> *</span></label>
					<select class="form-control required" id="cmb_size" name="cmb_size" style="width: 100%;" aria-hidden="true">
						<option selected="selected" value="">Select Size</option>
						@if(!empty($project_area))
						@foreach ($project_area as $key => $size)
						<option value="{{ $key }}">{{ $size }}</option>
						@endforeach
						@endif
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Transfer To<span class="text-danger"> *</span></label>
					<select class="form-control required" id="cmbTransferTo" name="cmbTransferTo" style="width: 100%;" aria-hidden="true">
						<option selected="selected" value="">Select Sales Agent</option>
						@foreach($sales_agent as $key=>$agent)
						<option Value="{{ $agent->user_pk_no }}" data-agent-category="{{ $agent->lookup_pk_no }}">
							{{ $agent->user_fullname }} ({{ $agent->lookup_name }})
						</option>
						@endforeach
					</select>
				</div>
			</div>
		</div>
	</div>

</div>
{{-- Lead Transfer Table --}}
<div class="box-body table-responsive">
	<table id="lead_transfer" class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th class="text-center">ID</th>
				<th class="text-center">Customer</th>
				<th class="text-center">Mobile</th>
				<th class="text-center">Category</th>
				<th class="text-center">Project</th>
				<th class="text-center">Agent</th>
				<th class="text-center">Stage</th>
				<th class="text-center">Sales Agent</th>
				<th class="text-center">Last Followup</th>
				<th class="text-center">Note</th>
				<th class="text-center">Next Followup</th>
				<th class="text-center">
					Select
				<a href="#" class="btn bg-blue btn-block btn-xs btn-transfer" data-response-action="{{ route('load_transfer_leads') }}">Transfer</a></th>
				<th class="text-center">Action</th>
			</tr>
		</thead>

		<tbody>
			@if(!empty($lead_transfer_list))
			@foreach($lead_transfer_list as $row)
			<tr>
				<td class="text-center">{{ $row->lead_id }}</td>
				<td class="text-center">{{ $row->customer_firstname . " " .$row->customer_lastname }}</td>
				<td class="text-center">{{ $row->phone1 }}</td>
				<td class="text-center">{{ $row->project_category_name }}</td>
				<td class="text-center">{{ $row->project_name }}</td>
				<td class="text-center">{{ $row->lead_sales_agent_name }}</td>
				<td class="text-center">{{ $lead_stage_arr[$row->lead_current_stage] }}</td>
				<td class="text-center">{{ $row->lead_sales_agent_name }}</td>
				<td class="text-center">{{ isset($followup_arr[$row->lead_pk_no]['lead_followup_datetime'])?$followup_arr[$row->lead_pk_no]['lead_followup_datetime']:'' }}</td>
				<td class="text-center">{{ isset($followup_arr[$row->lead_pk_no]['followup_Note'])?$followup_arr[$row->lead_pk_no]['followup_Note']:'' }}</td>
				<td class="text-center">{{ isset($followup_arr[$row->lead_pk_no]['Next_FollowUp_date'])?$followup_arr[$row->lead_pk_no]['Next_FollowUp_date']:'' }}</td>
				<td class="text-center">
					<input type="checkbox"
					data-id="{{ $row->lead_pk_no }}"
					data-name="{{ $row->lead_id }}"
					data-category="{{ $row->project_category_pk_no }}"
					data-agent="{{ $row->lead_sales_agent_pk_no }}">
				</td>
				<td class="text-center">
					<span class="btn bg-info btn-xs lead-view" title="Lead Sold"
					data-id="{{ $row->lead_pk_no }}"
					data-action="{{ route('lead_view',$row->lead_pk_no) }}"><i
					class="fa fa-eye"></i></span>
				</td>
			</tr>
			@endforeach
			@endif
		</tbody>
	</table>
</div>
</div>