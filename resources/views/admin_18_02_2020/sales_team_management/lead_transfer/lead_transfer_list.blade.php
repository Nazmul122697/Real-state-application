<div class="tab-pane active table-responsive" id="lead_transfer">
	<div class="head_action"
	style="background-color: #ECF0F5; text-align: right; border: 1px solid #ccc; padding: 3px;">
	<strong style="display: inline-block;">Transfer To :</strong> &nbsp &nbsp &nbsp &nbsp
	<div class="form-group" style="display: inline-block;">
		<select id="cmbTransferTo" class="form-control select2 select2-hidden-accessible"
		style="width: 100%;" aria-hidden="true">
		<option selected="selected" value="">Please Select Agent</option>
		@foreach($sales_agent as $key=>$agent)
		<option Value="{{ $agent->user_pk_no }}">{{ $agent->user_fullname }}</option>
		@endforeach
	</select>
</div>

</div>
{{-- Lead Transfer Table --}}
<div class="box-body table-responsive">
	<table id="lead_transfer" class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th style=" min-width: 30px" class="text-center">ID</th>
				<th style=" min-width: 50px" class="text-center">Customer</th>
				<th style=" min-width: 80px" class="text-center">Mobile</th>
				<th style=" min-width: 50px" class="text-center">Project</th>
				<th style=" min-width: 50px" class="text-center">Agent</th>
				<th style=" min-width: 51px" class="text-center">Stage</th>
				<th style=" min-width: 50px" class="text-center">Sales Agent</th>
				<th style=" min-width: 50px" class="text-center">Last Followup</th>
				<th style=" min-width: 100px" class="text-center">Note</th>
				<th style=" min-width: 50px" class="text-center">Next Followup</th>
				<th style=" min-width: 20px" class="text-center">Select</th>
				<th style=" min-width: 20px" class="text-center">Action</th>
			</tr>
		</thead>

		<tbody>
			@if(!empty($lead_transfer_list))
			@foreach($lead_transfer_list as $row)
			<tr>
				<td class="text-center">{{ $row->lead_id }}</td>
				<td class="text-center">{{ $row->customer_firstname . " " .$row->customer_lastname }}</td>
				<td class="text-center">{{ $row->phone1 }}</td>
				<td class="text-center">{{ $row->project_name }}</td>
				<td class="text-center">{{ $row->lead_sales_agent_name }}</td>
				<td class="text-center">{{ $lead_stage_arr[$row->lead_current_stage] }}</td>
				<td class="text-center">{{ $row->lead_sales_agent_name }}</td>
				<td class="text-center">{{ $row->lead_followup_datetime }}</td>
				<td class="text-center">{{ $row->followup_Note }}</td>
				<td class="text-center">{{ $row->Next_FollowUp_date }}</td>
				<td class="text-center">
					<input type="checkbox" data-id="{{ $row->lead_pk_no }}" data-name="{{ $row->lead_id }}">
				</td>
				<td class="text-center">
					<span class="btn bg-info btn-xs lead-view" title="View Lead Details"
					data-id="{{ $row->lead_pk_no }}"
					data-action="{{ route('lead_view',$row->lead_pk_no) }}"><i
					class="fa fa-eye"></i></span>
				</td>
			</tr>
			@endforeach
			@endif
		</tbody>
		<tfoot>
			<tr>
				<td colspan="10"></td>
				<td class="text-center">
					<a href="#" class="btn bg-blue btn-block btn-xs btn-transfer">Transfer</a>
				</td>
			</tr>
		</tfoot>
	</table>
</div>
</div>