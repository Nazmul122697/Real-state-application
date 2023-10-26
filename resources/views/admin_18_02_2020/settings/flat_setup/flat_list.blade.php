<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th style="min-width:100px">SL#</th>
            <th style="min-width:100px">ID</th>
            <th style="min-width:100px" class="text-center">Flat Number</th>
            <th style="min-width:100px" class="text-center">Category</th>
            <th style="min-width:100px" class="text-center">Project Name</th>
            <th style="min-width:100px" class="text-center">Flat Size</th>
            <th style="min-width:100px" class="text-center">Status</th>
            <th style="min-width:80px" class="text-center">Action</th>
        </tr>
    </thead>

    <tbody>
        @if(!empty($flat_list))
        @foreach($flat_list as $flat)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $flat->flat_id }}</td>
            <td class="text-center">{{ $flat->flat_name }}</td>
            <td class="text-center">{{ $project_cat[$flat->category_lookup_pk_no] }}</td>
            <td class="text-center">{{ $project_name[$flat->project_lookup_pk_no] }}</td>
            <td class="text-center">{{ $project_size[$flat->size_lookup_pk_no] }}</td>
            <td class="text-center">
                @if($flat->flat_status==1)
                <span class="btn btn-block btn-xs bg-red">Sold</span>
                @else
                <span class="btn btn-block btn-xs bg-green">Available</span>
                @endif
            </td>
            <td class="text-center" data-toggle="modal" data-target="#add_currency">
                <span class="btn bg-info btn-xs update_modal" data-id="{{ $flat->flatlist_pk_no }}" data-action="{{ route('edit_project_wise_flat',$flat->flatlist_pk_no) }}"><i class="fa fa-pencil"></i></span>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>