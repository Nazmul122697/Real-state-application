<input type="hidden" value="2" id="tab_type" />
<div class="tab-pane table-responsive" id="missed_followup">
    {{-- Missed Followup Table --}}
    <table id="datatable2" class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th style=" min-width: 10px" class="text-center">ID</th>
                <th style=" min-width: 10px" class="text-center">Date</th>
                <th style=" min-width: 70px" class="text-center">Customer</th>
                <th style=" min-width: 80px" class="text-center">Mobile</th>
                <th style=" min-width: 100px" class="text-center">Project</th>
                <th style=" min-width: 50px" class="text-center">Agent</th>
                <th style=" min-width: 50px" class="text-center">Stage</th>
                <th style=" min-width: 40px" class="text-center">Last Followup</th>
                <th style=" min-width: 130px" class="text-center">Note</th>
                <th style=" min-width: 25px" class="text-center">Action</th>
            </tr>
        </thead>

        <tbody>
        @if(!empty($lead_data))
            @foreach($lead_data as $row)
            @if(strtotime($row->lead_followup_datetime) < strtotime(date('d-m-Y')))
            @if(strtotime($row->Next_FollowUp_date) < strtotime(date('d-m-Y')))
            @php
            $followup_dt = date('d-m-Y', strtotime($row->lead_followup_datetime));
            @endphp
            @include('admin.sales_team_management.lead_followup.lead_follow_up_list')
            @endif
            @endif
            @endforeach
        @endif
        </tbody>
    </table>
</div>
