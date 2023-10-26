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
        <button type="button" class="btn bg-blue btn-xs pull-right" id="btnExportLead" onclick="ExportCSV()">Export
            to CSV</button>
    </div>

    <div class="box-body table-responsive">
        <table id="tbl_search_result" class="table table-bordered table-striped table-hover">
            <thead style="background-color:#5f5fd4;color:white">

                <tr>
                    <th class="text-center" rowspan="2">SL</th>
                    <th class="text-center" rowspan="2">Name</th>
                    <th class="text-center" rowspan="2">Initiatives</th>
                    <th class="text-center" rowspan="2">DOJ</th>
                    <th class="text-center" rowspan="2">DOR</th>
                    <th class="text-center" colspan="4">{{ isset($month) ? $month : 'January' }} -
                        {{ isset($target_year1) ? $target_year1 : '2021' }}</th>
                    <th class="text-center" colspan="4" style="background-color:#a97e2f;color:white">Year to Date
                    </th>
                </tr>
                <tr>
                    <th>Total New Lead</th>
                    <th> New K</th>
                    <th>Target</th>
                    <th>Achi.</th>
                    <th style="background-color:#a97e2f;color:white"> YTD Target</th>
                    <th style="background-color:#a97e2f;color:white"> YTD Ach.</th>
                    <th style="background-color:#a97e2f;color:white">Achi.</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $target1 = 0;
                    $target2 = 0;
                @endphp
                @if (!empty($get_all_tl_member))
                    @foreach ($get_all_tl_member as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->user_fullname }}</td>
                            <td></td>
                            <td> {{ isset($doj_arr[$row->user_pk_no]) ? date('d/m/Y', strtotime($doj_arr[$row->user_pk_no])) : '' }}
                            </td>
                            <td> {{ isset($dor_arr[$row->user_pk_no]) ? date('d/m/Y', strtotime($dor_arr[$row->user_pk_no])) : '' }}
                            </td>
                            <td>{{ isset($report_lead_arr['month'][$row->user_pk_no]) ? $report_lead_arr['month'][$row->user_pk_no] : '0' }}
                            </td>
                            <td>{{ isset($report_lead_arr['k1'][$row->user_pk_no]) ? $report_lead_arr['k1'][$row->user_pk_no] : '0' }}
                            </td>
                            <td>{{ isset($target_mm[$row->user_pk_no]) ? $target_mm[$row->user_pk_no] : '0' }}</td>
                            @php
                                $result2 = 0;
                                try {
                                    $month_ach = isset($report_lead_arr['month'][$row->user_pk_no]) ? $report_lead_arr['month'][$row->user_pk_no] : '0';
                                    $Month_target = isset($target_mm[$row->user_pk_no]) ? $target_mm[$row->user_pk_no] : '0';
                                    $result2 = $month_ach / $Month_target;
                                } catch (\Exception $e) {
                                    $result2 = 0;
                                }
                                $result2 = round($result2 * 100);
                            @endphp
                            <td>{{ $result2 }} %</td>
                            
                            <td>{{ isset($target_ytd[$row->user_pk_no]) ? $target_ytd[$row->user_pk_no] : '0' }}</td>
                            <td>{{ isset($report_lead_arr['ytd'][$row->user_pk_no]) ? $report_lead_arr['ytd'][$row->user_pk_no] : '0' }}
                            </td>
                            @php
                                $result2 = 0;
                                try {
                                    $month_ach = isset($report_lead_arr['ytd'][$row->user_pk_no]) ? $report_lead_arr['ytd'][$row->user_pk_no] : '0';
                                    $Month_target = isset($target_ytd[$row->user_pk_no]) ? $target_ytd[$row->user_pk_no] : '0';
                                    $result2 = $month_ach / $Month_target;
                                } catch (\Exception $e) {
                                    $result2 = 0;
                                }
                                $result2 = round($result2 * 100);
                            @endphp
                            @php
                                $target1 += isset($target_mm[$row->user_pk_no]) ? $target_mm[$row->user_pk_no] : '0';
                                $target2 += isset($target_ytd[$row->user_pk_no]) ? $target_ytd[$row->user_pk_no] : '0';
                            @endphp
                            <td>{{ $result2 }} %</td>
                        </tr>

                    @endforeach
                    <tr>
                        <td colspan="5" class="text-center">
                            Total
                        </td>
                        <td>
                            {{ isset($report_lead_arr['month']) ? array_sum($report_lead_arr['month']) : '0' }}
                        </td>
                        <td>
                            {{ isset($report_lead_arr['k1']) ? array_sum($report_lead_arr['k1']) : '0' }}
                        </td>
                        <td>{{ $target1 }}</td>
                        @php
                            $result2 = 0;
                            try {
                                $month_ach = isset($report_lead_arr['month']) ? array_sum($report_lead_arr['month']) : '0';
                            
                                $result2 = $month_ach / $target1;
                            } catch (\Exception $e) {
                                $result2 = 0;
                            }
                            $result2 = round($result2 * 100);
                        @endphp
                        <td>{{ $result2 }} %</td>
                        <td>{{ $target2 }}</td>
                        <td>
                            {{ isset($report_lead_arr['ytd']) ? array_sum($report_lead_arr['ytd']) : '0' }}
                        </td>
                       
                        @php
                            $result2 = 0;
                            try {
                                $month_ach = isset($report_lead_arr['ytd']) ? array_sum($report_lead_arr['ytd']) : '0';
                            
                                $result2 = $month_ach / $target1;
                            } catch (\Exception $e) {
                                $result2 = 0;
                            }
                            $result2 = round($result2 * 100);
                        @endphp
                        <td>{{ $result2 }} %</td>
                    </tr>
                @endif

            </tbody>


        </table>
    </div>
</div>
<script type="text/javascript">
    function ExportCSV() {
        TableToExcel.convert(document.getElementById("tbl_search_result"), {
            name: "OBM_Conversion_report.xlsx",
            sheet: {
                name: "Sheet1"
            }
        });
    }
</script>
