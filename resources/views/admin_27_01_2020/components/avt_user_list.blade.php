<table id="datatable" class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th class="text-center">Team Member</th>
			<th style=" min-width: 30px" class="text-center">YY-MM</th>
			<th style=" min-width: 30px" class="text-center">Amount</th>
			<th style=" min-width: 10px" class="text-center"></th>
		</tr>
	</thead>

	<tbody>
		@if(!empty($avt_data))
		@foreach($avt_data as $row)
		<tr>
			<td>{{ $row->user_name }}</td>
			<td class="text-center">{{ $row->yy_mm }}</td>
			<td class="text-center">{{ ($row->target_amount==0 || $row->target_amount == '')?'':$row->target_amount }}</td>
			<td class="text-center">
				<span title="View Chart" class="view-chart-avt" data-title="KPI :: AVT" data-action="{{ route('performance_chart_data',['user_id'=>$row->user_pk_no,'type'=>'avt']) }}" style="cursor: pointer;"><i class="ion ion-stats-bars"></i></span>
			</td>
		</tr>
		@endforeach
		@else
		@endif
	</tbody>
</table>