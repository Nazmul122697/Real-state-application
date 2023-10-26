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
                        <b>Brokerage Sales HoT Performance</b>
                    </td>

                </tr>
                <tr style="background-color:#6e9cce;color:white">
                    <th class="text-center" colspan="5">Team Details</th>
                    <th class="text-center" colspan="5">{{ isset($month) ? $month : 'January' }} -
                        {{ isset($target_year1) ? $target_year1 : '2021' }}</th>
                    <th class="text-center" colspan="8">Year to Date</th>
                </tr>
                <tr style="background-color:#6e9cce;color:white">
                    <th class="text-center">SL</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">DOJ</th>
                    <th class="text-center">DOR</th>
                    <th class="text-center">CHS</th>
                    <th>Sales</th>
                    <th>Cancel</th>
                    <th>Resultant Value
                    </th>
                    <th>Target
                    </th>
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
                @php
                    $i = 1;
                    $rand = '#F5C87B';
                    $rand_pre = '';
                @endphp
                @if (!empty($cluster_head1))
                    @foreach ($cluster_head1 as $cluster)
                        @php
                            $key = 0;
                            $total_new_k1 = 0;
                            $total_k1 = 0;
                            $total_prority = 0;
                            $total_m_con = 0;
                            $total_y_con = 0;
                            $total_new_lead = 0;
                            $total_all_lead = 0;
                        @endphp
                        @if (!empty($tl_arr[$cluster->user_pk_no]))

                            @foreach ($tl_arr[$cluster->user_pk_no] as $tl => $tl_name)
                                @php
                                    $tl_count = 0;
                                    if ($tl_count == 0) {
                                        if ($rand != $rand_pre) {
                                            $rand = '#DCECDC';
                                            $rand_pre = '#DCECDC';
                                        } else {
                                            $rand = '#F5C87B';
                                        }
                                    }
                                @endphp
                                @if (!empty($team_member[$cluster->user_pk_no][$tl]))
                                    @foreach ($team_member[$cluster->user_pk_no][$tl] as $mem_id => $mem_name)
                                        <tr
                                            style="border-bottom:1px solid black;background-color: {{ $rand }};">
                                            <td class="text-center">{{ $i }}</td>
                                            <td class="text-center">{{ $mem_id }}</td>


                                            @if ($tl_count == 0)
                                                <td class="text-center"
                                                    rowspan="{{ count($team_member[$cluster->user_pk_no][$tl]) }}">
                                                    {{ $cluster->user_pk_no . '_' . $cluster->user_fullname }}</td>
                                            @endif
                                            @if ($tl_count == 0)
                                                <td class="text-center"
                                                    rowspan="{{ count($team_member[$cluster->user_pk_no][$tl]) }}">
                                                    {{ $tl }} - {{ $tl_name }}</td>
                                            @endif

                                            @php
                                                $result = 0;
                                                try {
                                                    $result = $report_data['con'][$mem_id] / $report_data['new_k1'][$mem_id];
                                                } catch (\Exception $e) {
                                                    $result = 0;
                                                }
                                                $result = round($result * 100);
                                                $result1 = 0;
                                                try {
                                                    $result1 = $report_data['t_con'][$mem_id] / $report_data['total_k1'][$mem_id];
                                                } catch (\Exception $e) {
                                                    $result1 = 0;
                                                }
                                                $result1 = round($result1 * 100);
                                                
                                            @endphp

                                            <td class="text-center">{{ $mem_name }}</td>
                                            <td class="text-center">
                                                {{ isset($doj_arr[$mem_id]) ? date('d/m/Y', strtotime($doj_arr[$mem_id])) : '' }}
                                            </td>
                                            <td class="text-center">
                                                {{ isset($dor_arr[$mem_id]) ? date('d/m/Y', strtotime($dor_arr[$mem_id])) : '' }}
                                            </td>
                                            <td class="text-center">
                                                {{ isset($report_data['new_lead'][$mem_id]) ? $report_data['new_lead'][$mem_id] : 0 }}
                                            </td>
                                            <td class="text-center">
                                                {{ isset($report_data['new_k1'][$mem_id]) ? $report_data['new_k1'][$mem_id] : 0 }}
                                            </td>
                                            <td class="text-center">
                                                {{ isset($report_data['total_k1'][$mem_id]) ? $report_data['total_k1'][$mem_id] : 0 }}
                                            </td>
                                            <td class="text-center">
                                                {{ isset($report_data['priority'][$mem_id]) ? $report_data['priority'][$mem_id] : 0 }}
                                            </td>
                                            <td class="text-center">
                                                {{ isset($report_data['con'][$mem_id]) ? $report_data['con'][$mem_id] : 0 }}
                                            </td>
                                            @php
                                                $result2 = 0;
                                                try {
                                                    $result2 = $report_data['new_k1'][$mem_id] / $report_data['new_lead'][$mem_id];
                                                } catch (\Exception $e) {
                                                    $result2 = 0;
                                                }
                                                $result2 = round($result2 * 100);
                                            @endphp

                                            <td class="text-center">{{ $result2 }} %</td>
                                            <td class="text-center">{{ $result }} %</td>
                                            @php
                                                $result2 = 0;
                                                try {
                                                    $result2 = $report_data['con'][$mem_id] / $report_data['new_lead'][$mem_id];
                                                } catch (\Exception $e) {
                                                    $result2 = 0;
                                                }
                                                $result2 = round($result2 * 100);
                                            @endphp

                                            <td class="text-center">{{ $result2 }} %</td>
                                            <td class="text-center">
                                                {{ isset($report_data['total_new_lead'][$mem_id]) ? $report_data['total_new_lead'][$mem_id] : 0 }}
                                            </td>
                                            <td class="text-center">
                                                {{ isset($report_data['total_k1'][$mem_id]) ? $report_data['total_k1'][$mem_id] : 0 }}
                                            </td>
                                            <td class="text-center">
                                                {{ isset($report_data['t_con'][$mem_id]) ? $report_data['t_con'][$mem_id] : 0 }}
                                            </td>
                                            @php
                                                $result2 = 0;
                                                try {
                                                    $result2 = $report_data['total_k1'][$mem_id] / $report_data['total_new_lead'][$mem_id];
                                                } catch (\Exception $e) {
                                                    $result2 = 0;
                                                }
                                                $result2 = round($result2 * 100);
                                            @endphp

                                            <td class="text-center">{{ $result2 }} %</td>
                                            <td class="text-center">{{ $result1 }} %</td>
                                            @php
                                                $result2 = 0;
                                                try {
                                                    $result2 = $report_data['t_con'][$mem_id] / $report_data['total_new_lead'][$mem_id];
                                                } catch (\Exception $e) {
                                                    $result2 = 0;
                                                }
                                                $result2 = round($result2 * 100);
                                            @endphp

                                            <td class="text-center">{{ $result2 }} %</td>

                                        </tr>
                                        @php
                                            $key++;
                                            $tl_count++;
                                            $i++;
                                            $total_new_k1 += isset($report_data['new_k1'][$mem_id]) ? $report_data['new_k1'][$mem_id] : 0;
                                            $total_k1 += isset($report_data['total_k1'][$mem_id]) ? $report_data['total_k1'][$mem_id] : 0;
                                            $total_prority += isset($report_data['priority'][$mem_id]) ? $report_data['priority'][$mem_id] : 0;
                                            $total_m_con += isset($report_data['con'][$mem_id]) ? $report_data['con'][$mem_id] : 0;
                                            $total_y_con += isset($report_data['t_con'][$mem_id]) ? $report_data['t_con'][$mem_id] : 0;
                                            $total_new_lead += isset($report_data['new_lead'][$mem_id]) ? $report_data['new_lead'][$mem_id] : 0;
                                            $total_all_lead += isset($report_data['total_new_lead'][$mem_id]) ? $report_data['total_new_lead'][$mem_id] : 0;
                                        @endphp

                                    @endforeach
                                @endif

                            @endforeach
                        @endif
                        @if (!empty($team_member[$cluster->user_pk_no]))

                            <tr style="background-color:#9ec1d2f5;color:white">
                                <td colspan="7" class="text-center"> Resigned</td>

                                <td class="text-center">
                                    {{ isset($report_data['res_new_lead'][$cluster->user_pk_no]) ? $report_data['res_new_lead'][$cluster->user_pk_no] : 0 }}
                                </td>
                                <td class="text-center">
                                    {{ isset($report_data['res_new_k1'][$cluster->user_pk_no]) ? $report_data['res_new_k1'][$cluster->user_pk_no] : 0 }}
                                </td>
                                <td class="text-center">
                                    {{ isset($report_data['res_total_k1'][$cluster->user_pk_no]) ? $report_data['res_total_k1'][$cluster->user_pk_no] : 0 }}
                                </td>
                                <td class="text-center">
                                    {{ isset($report_data['res_priority'][$cluster->user_pk_no]) ? $report_data['res_priority'][$cluster->user_pk_no] : 0 }}
                                </td>
                                <td class="text-center">
                                    {{ isset($report_data['res_con'][$cluster->user_pk_no]) ? $report_data['res_con'][$cluster->user_pk_no] : 0 }}
                                </td>

                                @php
                                    $result2 = 0;
                                    try {
                                        $result2 = $report_data['res_new_k1'][$cluster->user_pk_no] / $report_data['res_new_lead'][$cluster->user_pk_no];
                                    } catch (\Exception $e) {
                                        $result2 = 0;
                                    }
                                    $result2 = round($result2 * 100);
                                @endphp
                                <td class="text-center">{{ $result2 }} %</td>
                                @php
                                    $result2 = 0;
                                    try {
                                        $result2 = $report_data['res_con'][$cluster->user_pk_no] / $report_data['res_new_k1'][$cluster->user_pk_no];
                                    } catch (\Exception $e) {
                                        $result2 = 0;
                                    }
                                    $result2 = round($result2 * 100);
                                @endphp

                                <td class="text-center">{{ $result2 }}%</td>

                                @php
                                    $result2 = 0;
                                    try {
                                        $result2 = $report_data['res_con'][$cluster->user_pk_no] / $report_data['res_new_lead'][$cluster->user_pk_no];
                                    } catch (\Exception $e) {
                                        $result2 = 0;
                                    }
                                    $result2 = round($result2 * 100);
                                @endphp
                                <td class="text-center">{{ $result2 }} %</td>
                                <td class="text-center">
                                    {{ isset($report_data['res_total_new_lead'][$cluster->user_pk_no]) ? $report_data['res_total_new_lead'][$cluster->user_pk_no] : 0 }}
                                </td>
                                <td class="text-center">
                                    {{ isset($report_data['res_total_k1'][$cluster->user_pk_no]) ? $report_data['res_total_k1'][$cluster->user_pk_no] : 0 }}
                                </td>


                                <td class="text-center">
                                    {{ isset($report_data['res_t_con'][$cluster->user_pk_no]) ? $report_data['res_t_con'][$cluster->user_pk_no] : 0 }}
                                </td>
                                @php
                                    $result2 = 0;
                                    try {
                                        $result2 = $report_data['res_total_k1'][$cluster->user_pk_no] / $report_data['res_total_new_lead'][$cluster->user_pk_no];
                                    } catch (\Exception $e) {
                                        $result2 = 0;
                                    }
                                    $result2 = round($result2 * 100);
                                @endphp
                                <td class="text-center">{{ $result2 }} %</td>
                                @php
                                    $result2 = 0;
                                    try {
                                        $result2 = $report_data['res_t_con'][$cluster->user_pk_no] / $report_data['res_total_k1'][$cluster->user_pk_no];
                                    } catch (\Exception $e) {
                                        $result2 = 0;
                                    }
                                    $result2 = round($result2 * 100);
                                @endphp

                                <td class="text-center">{{ $result2 }}%</td>

                                @php
                                    $result2 = 0;
                                    try {
                                        $result2 = $report_data['res_t_con'][$cluster->user_pk_no] / $report_data['res_total_new_lead'][$cluster->user_pk_no];
                                    } catch (\Exception $e) {
                                        $result2 = 0;
                                    }
                                    $result2 = round($result2 * 100);
                                @endphp
                                <td class="text-center">{{ $result2 }}%</td>



                            </tr>



                            @php
                                $result = 0;
                                try {
                                    $result = $total_m_con / $total_new_k1;
                                } catch (\Exception $e) {
                                    $result = 0;
                                }
                                $result = round($result * 100);
                                $result1 = 0;
                                try {
                                    $result1 = $total_y_con / $total_k1;
                                } catch (\Exception $e) {
                                    $result1 = 0;
                                }
                                $result1 = round($result1 * 100);
                                
                            @endphp


                            <tr style="background-color:#5f5fd4;color:white">
                                <td colspan="7" class="text-center"> Total</td>

                                <td class="text-center"> {{ $total_new_lead }}</td>
                                <td class="text-center">{{ $total_new_k1 }}</td>
                                <td class="text-center">{{ $total_k1 }}</td>
                                <td class="text-center">{{ $total_prority }}</td>
                                <td class="text-center">{{ $total_m_con }}</td>
                                @php
                                    $result2 = 0;
                                    try {
                                        $result2 = $total_new_k1 / $total_new_lead;
                                    } catch (\Exception $e) {
                                        $result = 0;
                                    }
                                    $result2 = round($result2 * 100);
                                @endphp
                                <td class="text-center">{{ $result2 }} %</td>

                                <td class="text-center">{{ $result }}%</td>

                                @php
                                    $result2 = 0;
                                    try {
                                        $result2 = $total_m_con / $total_new_lead;
                                    } catch (\Exception $e) {
                                        $result = 0;
                                    }
                                    $result2 = round($result2 * 100);
                                @endphp
                                <td class="text-center">{{ $result2 }} %</td>
                                <td class="text-center">{{ $total_all_lead }}</td>
                                <td class="text-center">{{ $total_k1 }}</td>


                                <td class="text-center">{{ $total_y_con }}</td>

                                @php
                                    $result2 = 0;
                                    try {
                                        $result2 = $total_k1 / $total_all_lead;
                                    } catch (\Exception $e) {
                                        $result = 0;
                                    }
                                    $result2 = round($result2 * 100);
                                @endphp
                                <td class="text-center">{{ $result2 }} %</td>



                                <td class="text-center">{{ $result1 }} %</td>

                                @php
                                    $result2 = 0;
                                    try {
                                        $result2 = $total_y_con / $total_all_lead;
                                    } catch (\Exception $e) {
                                        $result = 0;
                                    }
                                    $result2 = round($result2 * 100);
                                @endphp
                                <td class="text-center">{{ $result2 }} %</td>




                            </tr>
                            <tr>
                                <td style="height: 20px"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endif

                    @endforeach
                @else
                    <tr>
                        <td colspan="21" class="text-center">
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
            name: "sales_report.xlsx",
            sheet: {
                name: "Sheet1"
            }
        });
    }

</script>
