{{-- Junk Leads Table --}}
<div class="tab-pane table-responsive" id="junk_leads">
	<table id="work_list" class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th class="text-center">ID</th>
				<th class="text-center">Customer</th>
				<th class="text-center">Mobile</th>
				<th class="text-center">Category</th>
				<th class="text-center">Area</th>
				<th class="text-center">Project</th>
				<th class="text-center">Size</th>
				<th class="text-center">Status</th>
				<th class="text-center">Junk / Pass</th>
				<th class="text-center">Action</th>
			</tr>
		</thead>

		<tbody>
			@if(!empty($lead_data))
			@foreach($lead_data as $row)
			@if($row->lead_qc_flag == 2)
			<tr>
				@include('admin.lead_management.qc.lead_qc_list')
			</tr>
			@endif
			@endforeach
			@else
			
			@endif
		</tbody>
		<tfoot>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td class="text-center">
				<button type="submit" class="btn bg-red btn-xs lead-qc-status" data-target="#junk_leads" data-type="3" id="junk">Junk</button>
				<button type="submit" class="btn bg-green btn-xs lead-qc-status" data-target="#junk_leads" data-type="3" id="pass">Pass</button>
			</td>
			<td></td>
		</tfoot>
	</table>
</div>