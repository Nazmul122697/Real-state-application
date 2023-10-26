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
            <thead>
                <tr style="background-color:#6e9cce;color:white">
                    <td colspan="13" class="text-center">
                        <b>Brokerage</b>
                    </td>

                </tr>
                <tr style="background-color:#6e9cce;color:white">
                    <th class="text-center" colspan="5">Team Details</th>
                    <th class="text-center" colspan="3">{{ isset($month) ? $month : 'January' }} -
                        {{ isset($target_year1) ? $target_year1 : '2021' }}</th>
                    <th class="text-center" colspan="3">Year to Date</th>
                </tr>
                <tr style="background-color:#6e9cce;color:white">
                    <th class="text-center">SL</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">DOJ</th>
                    <th class="text-center">DOR</th>
                    <th class="text-center">HoT</th>
                    <th>Target
                    </th>
                    <th>Sale</th>

                    <th>Achv. (%)
                    </th>
                    <th>YTD Target
                    </th>
                    <th>YTD Achv.
                    </th>
                    <th>YTD Achv. (%)
                    </th>


                </tr>
            </thead>
            <tbody>
                @if (!empty($tl_name))
                    @php
                        $i = 1;
                        $sale_mm = 0;
                        $sale_target = 0;
                        $sale_ytd = 0;
                        $sale_ytd_target = 0;
                        $prev = '';
                        $c = 0;
                    @endphp
                    @foreach ($tl_name as $key => $value)
                        @php
                            $c = 0;
                        @endphp
                        @foreach ($tl_arr[$key] as $id => $member)
                            @php
                                $member_name = explode('_', $member);
                            @endphp
                            @php
                                $res = 0;
                                try {
                                    $t1 = isset($target_mm[$member_name[0]]) ? $target_mm[$member_name[0]] : 0;
                                    $a1 = isset($res_arr[$member_name[0]]['month']) ? $res_arr[$member_name[0]]['month'] : 0;
                                    $res = ($a1 / $t1) * 100;
                                } catch (\Exception $e) {
                                    $res = 0;
                                }
                            @endphp
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $member_name[0] }} - {{ $member_name[1] }}</td>
                                <td class="text-center">
                                    {{ isset($doj_arr[$member_name[0]]) ? date('d/m/Y', strtotime($doj_arr[$member_name[0]])) : '' }}
                                </td>
                                <td class="text-center">
                                    {{ isset($dor_arr[$member_name[0]]) ? date('d/m/Y', strtotime($dor_arr[$member_name[0]])) : '' }}
                                </td>
                                @if ($c == 0)
                                    <td rowspan="{{ count($tl_arr[$key]) }}"> {{ $value }}</td>
                                    @php
                                        $c = 1;
                                    @endphp
                                @endif
                                <td>{{ isset($target_mm[$member_name[0]]) ? $target_mm[$member_name[0]] : 0 }}</td>

                                <td>{{ isset($res_arr[$member_name[0]]['month']) ? $res_arr[$member_name[0]]['month'] : 0 }}
                                </td>
                                <td> {{ number_format($res) }} %</td>
                                <td>{{ isset($target_ytd[$member_name[0]]) ? $target_ytd[$member_name[0]] : 0 }}</td>
                                <td>{{ isset($res_arr[$member_name[0]]['ytd']) ? $res_arr[$member_name[0]]['ytd'] : 0 }}
                                </td>
                                @php
                                    $res = 0;
                                    try {
                                        $t1 = isset($target_mm[$member_name[0]]) ? $target_mm[$member_name[0]] : 0;
                                        $a1 = isset($res_arr[$member_name[0]]['month']) ? $res_arr[$member_name[0]]['month'] : 0;
                                        $res = ($a1 / $t1) * 100;
                                    } catch (\Exception $e) {
                                        $res = 0;
                                    }
                                @endphp

                                <td>{{ number_format($res) }} %</td>
                                @php
                                    $sale_mm += isset($res_arr[$member_name[0]]['month']) ? $res_arr[$member_name[0]]['month'] : 0;
                                    $sale_target += isset($target_mm[$member_name[0]]) ? $target_mm[$member_name[0]] : 0;
                                    $sale_ytd += isset($res_arr[$member_name[0]]['ytd']) ? $res_arr[$member_name[0]]['ytd'] : 0;
                                    $sale_ytd_target += isset($target_ytd[$member_name[0]]) ? $target_ytd[$member_name[0]] : 0;
                                @endphp
                            </tr>
                        @endforeach

                    @endforeach
                    <tr>
                        <td colspan="5" class="text-center">Grand Total</td>
                        <td class="text-center">{{ $sale_target }}</td>
                        <td class="text-center">{{ $sale_mm }}</td>

                        @php
                            $res = 0;
                            try {
                                $res = ($sale_mm / $sale_target) * 100;
                            } catch (\Exception $e) {
                                $res = 0;
                            }
                        @endphp

                        <td class="text-center">{{ number_format($res, 2) }} %</td>
                        <td class="text-center">{{ $sale_ytd_target }}</td>
                        <td class="text-center">{{ $sale_ytd }}</td>
                        @php
                            $res = 0;
                            try {
                                $res = ($sale_ytd / $sale_ytd_target) * 100;
                            } catch (\Exception $e) {
                                $res = 0;
                            }
                        @endphp
                        <td class="text-center">{{ number_format($res, 2) }} %</td>

                    </tr>
                @endif


            </tbody>


        </table>
    </div>
</div>
<script type="text/javascript">
    function ExportCSV() {
        TableToExcel.convert(document.getElementById("tbl_search_result"), {
            name: "brokerage_inventory_report.xlsx",
            sheet: {
                name: "Sheet1"
            }
        });
    }
</script>
