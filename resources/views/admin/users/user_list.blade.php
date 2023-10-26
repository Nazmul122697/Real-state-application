<table id="user-table" class="table table-bordered table-striped table-hover data-table">
	<thead>
		<tr>
			<th style="width: 50px;">SL</th>
            <th>User Group</th>
            <th>Designation</th>
			<th>Employee ID</th>
            <th>Joining Date</th>
			<th>Full Name</th>
			<th>Email Address</th>
			<th>Contact</th>
			<th>Address</th>
			<th class="text-center">Action</th>
		</tr>
	</thead>

	<tbody>

		@foreach($users as $key=>$user)
{{--            @dd($user)--}}
		<tr>
			<td style="width: 50px;">{{ $loop->iteration }}</td>
			<td>{{ isset($user_group[$user->role])?$user_group[$user->role]:"" }}</td>
            <td>{{@$user->designation->lookup_name}}</td>
            <td>{{ $user->employee_id }}</td>
            <td>{{ $user->joining_date!=""?date("d-m-Y", strtotime($user->joining_date)):"" }}</td>
            <td>{{ $user->name }}</td>
			<td>{{ $user->email }}</td>
			<td>{{ $user->phone }}</td>
			<td>{{ $user->address }}</td>
			<td width="100" class="text-center">
				<span class="btn bg-{{ ($user->status==1)?'success':'danger' }} btn-xs" data-id="{{ $user->id }}">{{ ($user->status==1)?'Active':'Inactive' }}</span>
				<span class="btn bg-info btn-xs update_modal" data-id="{{ $user->id }}" data-action="{{ route('user.edit',$user->id) }}"><i class="fa fa-pencil"></i></span>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
