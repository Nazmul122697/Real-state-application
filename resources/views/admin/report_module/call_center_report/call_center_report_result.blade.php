@php
$is_super_admin = Session::get('user.is_super_admin');
@endphp

<style type="text/css">
    .table thead tr td,
    .table thead tr th,
    .table tbody tr td,
    .table tbody tr th,
    .table tfoot tr td,
    .table tfoot tr th {
        vertical-align: middle !important;
    }

</style>
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Search Result</h3>
        {{-- <button type="submit" class="btn bg-blue btn-xs pull-right" id="btnExportLeads">Export to CSV</button> --}}
        <button type="button" class="btn bg-blue btn-xs pull-right" id="btnExportLead" onclick="ExportCSV()">Export
            to CSV</button>

    </div>

    <div class="box-body table-responsive">
        <table id="tbl_search_result" class="table table-bordered table-striped table-hover">
            <thead style="background-color:#5f5fd4;color:white">

                <tr>
                    <td colspan="3">Team Details</td>
                    <td colspan="10" class="text-center">This Month</td>
                    <td colspan="10" class="text-center">Year To Date</td>
                </tr>

                <tr>
                    <th class="text-center" rowspan="2">SL</th>
                    <th class="text-center" rowspan="2">Name </th>
                    <th class="text-center" rowspan="2">DOJ </th>
                    <th colspan="5" class="text-center">BTI Sales</th>
                    <th class="text-center" rowspan="2">Brokerage
                    </th>
                    <th rowspan="2">Rent </th>
                    <th class="text-center" rowspan="2">BD
                    </th>
                    <th class="text-center" rowspan="2">SFS
                    </th>
                    <th class="text-center" rowspan="2">Building Product
                    </th>

                    <th colspan="5" class="text-center">BTI Sales</th>
                    <th class="text-center" rowspan="2">Brokerage
                    </th>
                    <th rowspan="2">Rent </th>
                    <th class="text-center" rowspan="2">BD
                    </th>
                    <th class="text-center" rowspan="2">SFS
                    </th>
                    <th class="text-center" rowspan="2">Building Product
                    </th>

                </tr>
                <tr>
                    <th>Luxury
                    </th>
                    <th>Classic A

                    </th>
                    <th>Classic B & C

                    </th>
                    <th>Commercial</th>
                    <th>CTG </th>
                    <th>Luxury
                    </th>
                    <th>Classic A

                    </th>
                    <th>Classic B & C

                    </th>
                    <th>Commercial</th>
                    <th>CTG </th>
                </tr>

            </thead>
            <tbody>
                @if (!empty($team_members))
                    @foreach ($team_members as $member)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $member->user_fullname }}</td>
                            <td></td>
                            <td>{{ isset($result_arr[$member->user_pk_no]['luxury']) ? $result_arr[$member->user_pk_no]['luxury'] : 0 }}
                            </td>
                            <td>{{ isset($result_arr[$member->user_pk_no]['classic_a']) ? $result_arr[$member->user_pk_no]['classic_a'] : 0 }}
                            </td>
                            <td>{{ isset($result_arr[$member->user_pk_no]['classic_b_c']) ? $result_arr[$member->user_pk_no]['classic_b_c'] : 0 }}
                            </td>
                            <td>{{ isset($result_arr[$member->user_pk_no]['commercial']) ? $result_arr[$member->user_pk_no]['commercial'] : 0 }}
                            </td>
                            <td>{{ isset($result_arr[$member->user_pk_no]['ctg']) ? $result_arr[$member->user_pk_no]['ctg'] : 0 }}
                            </td>
                            <td>{{ isset($result_arr[$member->user_pk_no]['brokeage']) ? $result_arr[$member->user_pk_no]['brokeage'] : 0 }}
                            </td>
                            <td>{{ isset($result_arr[$member->user_pk_no]['rent']) ? $result_arr[$member->user_pk_no]['rent'] : 0 }}
                            </td>

                            <td>{{ isset($result_arr[$member->user_pk_no]['bd']) ? $result_arr[$member->user_pk_no]['bd'] : 0 }}
                            </td>
                            <td>{{ isset($result_arr[$member->user_pk_no]['sfs']) ? $result_arr[$member->user_pk_no]['sfs'] : 0 }}
                            </td>
                            <td>{{ isset($result_arr[$member->user_pk_no]['buidling_product']) ? $result_arr[$member->user_pk_no]['buidling_product'] : 0 }}
                            </td>

                            <td>{{ isset($result_arr[$member->user_pk_no]['t_luxury']) ? $result_arr[$member->user_pk_no]['t_luxury'] : 0 }}
                            </td>
                            <td>{{ isset($result_arr[$member->user_pk_no]['t_classic_a']) ? $result_arr[$member->user_pk_no]['t_classic_a'] : 0 }}
                            </td>
                            <td>{{ isset($result_arr[$member->user_pk_no]['t_classic_b_c']) ? $result_arr[$member->user_pk_no]['t_classic_b_c'] : 0 }}
                            </td>
                            <td>{{ isset($result_arr[$member->user_pk_no]['t_commercial']) ? $result_arr[$member->user_pk_no]['t_commercial'] : 0 }}
                            </td>
                            <td>{{ isset($result_arr[$member->user_pk_no]['t_ctg']) ? $result_arr[$member->user_pk_no]['t_ctg'] : 0 }}
                            </td>
                            <td>{{ isset($result_arr[$member->user_pk_no]['t_brokeage']) ? $result_arr[$member->user_pk_no]['t_brokeage'] : 0 }}
                            </td>
                            <td>{{ isset($result_arr[$member->user_pk_no]['t_rent']) ? $result_arr[$member->user_pk_no]['t_rent'] : 0 }}
                            </td>

                            <td>{{ isset($result_arr[$member->user_pk_no]['t_bd']) ? $result_arr[$member->user_pk_no]['t_bd'] : 0 }}
                            </td>
                            <td>{{ isset($result_arr[$member->user_pk_no]['t_sfs']) ? $result_arr[$member->user_pk_no]['t_sfs'] : 0 }}
                            </td>

                            <td>{{ isset($result_arr[$member->user_pk_no]['t_buidling_product']) ? $result_arr[$member->user_pk_no]['t_buidling_product'] : 0 }}
                            </td>

                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-center">
                            Grand total
                        </td>
                        <td>
                            {{ $final_total['luxury'] }}
                        </td>
                        <td>
                            {{ $final_total['classic_a'] }}
                        </td>
                        <td>
                            {{ $final_total['classic_b_c'] }}
                        </td>
                        
                        <td>
                            {{ $final_total['commercial'] }}
                        </td>
                        <td>
                            {{ $final_total['ctg'] }}
                        </td>
                        <td>
                            {{ $final_total['brokeage'] }}
                        </td>
                        <td>
                            {{ $final_total['rent'] }}
                        </td>
                        <td>
                            {{ $final_total['bd'] }}
                        </td>
                        <td>
                            {{ $final_total['sfs'] }}
                        </td>
                        <td>
                            {{ $final_total['buidling_product'] }}
                        </td>
                       
                        <td>
                            {{ $final_total['t_luxury'] }}
                        </td>
                        <td>
                            {{ $final_total['t_classic_a'] }}
                        </td>
                        <td>
                            {{ $final_total['t_classic_b_c'] }}
                        </td>
                        
                        <td>
                            {{ $final_total['t_commercial'] }}
                        </td>
                        <td>
                            {{ $final_total['t_ctg'] }}
                        </td>
                        <td>
                            {{ $final_total['t_brokeage'] }}
                        </td>
                        <td>
                            {{ $final_total['t_rent'] }}
                        </td>
                        <td>
                            {{ $final_total['t_bd'] }}
                        </td>
                        <td>
                            {{ $final_total['t_sfs'] }}
                        </td>
                        <td>
                            {{ $final_total['t_buidling_product'] }}
                        </td>
                       
                    </tr>
                @else
                    <tr>
                        <td colspan="23" class="text-center">
                            No Data found
                        </td>
                    </tr>
                @endif


            </tbody>


        </table>
    </div>
</div>
<script type="text/javascript">
    function ExportCSV() {
        TableToExcel.convert(document.getElementById("tbl_search_result"), {
            name: "call_center_report.xlsx",
            sheet: {
                name: "Sheet1"
            }
        });
    }
</script>
