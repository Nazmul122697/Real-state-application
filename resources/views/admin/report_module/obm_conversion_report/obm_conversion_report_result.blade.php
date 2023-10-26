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
                        <th colspan="23" style="background-color:#5f5fd4;color:white" class="text-center">
                            {{ isset($categoryname) ? $categoryname->lookup_name : '' }}
                        </th>
                    </tr>
                    <tr>
                        <th class="text-center" colspan="6">Team Details</th>
                        <th class="text-center" colspan="8">{{ isset($month) ? $month : 'January' }} -
                            {{ isset($target_year1) ? $target_year1 : '2021' }}</th>
                        <th class="text-center" colspan="8" style="background-color:#a97e2f;color:white">Year to Date
                        </th>
                    </tr>
                    <tr>
                        <th class="text-center">SL</th>
                        <th class="text-center">ID</th>
                        <th class="text-center">HOT</th>
                        <th class="text-center">Sales Persion</th>
                        <th class="text-center">DOJ</th>
                        <th class="text-center">DOR</th>
                        <th>New Lead</th>
                        <th>New K1</th>
                        <th>Total K1</th>
                        <th>Prority</th>
                        <th>Sales</th>
                        <th>Lead to K Con.</th>
                        <th>K to Sale Con.</th>
                        <th>Lead to Sale Con.</th>
                        <th style="background-color:#a97e2f;color:white">Total New Lead</th>
                        <th style="background-color:#a97e2f;color:white">Total K1</th>
                        <th style="background-color:#a97e2f;color:white">Sales</th>
                        <th style="background-color:#a97e2f;color:white">Lead to K Con.</th>
                        <th style="background-color:#a97e2f;color:white">K to Sale Con.</th>
                        <th style="background-color:#a97e2f;color:white">Lead to Sale Con.</th>


                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                        $rand = '#F5C87B';
                        $rand_pre = '';
                        $tl_count = 0;
                    @endphp

                    @if (!empty($team_build))
                        {{-- {{ dd($team_build) }} --}}
                        @php
                            $key = 0;
                            $total_new_k1 = 0;
                            $total_k1 = 0;
                            $total_prority = 0;
                            $total_m_con = 0;
                            $total_y_con = 0;
                            $total_new_lead = 0;
                            $total_all_lead = 0;
                            $total_all_k1 = 0;
                        @endphp

                        @foreach ($team_build as $tl => $member)


                            @php

                                if ($tl_count == 0) {
                                    if ($rand != $rand_pre) {
                                        $rand = '#DCECDC';
                                        $rand_pre = '#DCECDC';
                                    } else {
                                        $rand = '#F5C87B';
                                    }
                                }
                            @endphp

                            <tr style="border-bottom:1px solid black;background-color: {{ $rand }};">
                                <td class="text-center">{{ $i }}</td>
                                <td class="text-center">{{ $member['user_pk_no'] }}</td>


                                {{-- @if ($tl_count == 0) --}}
                                <td class="text-center" rowspan="">
                                    {{ $member['user_pk_no'] }} - {{ $member['tl_fullname'] }}</td>
                                {{-- @endif --}}

                                @php
                                    $result = 0;
                                    try {
                                        $result = $report_data['con'][$member['user_pk_no']] / $report_data['new_k1'][$member['user_pk_no']];
                                    } catch (\Exception $e) {
                                        $result = 0;
                                    }
                                    $result = round($result * 100);
                                    $result1 = 0;
                                    try {
                                        $result1 = $report_data['t_con'][$member['user_pk_no']] / $report_data['total_k1'][$member['user_pk_no']];
                                    } catch (\Exception $e) {
                                        $result1 = 0;
                                    }
                                    $result1 = round($result1 * 100);

                                @endphp

                                <td class="text-center">{{ $member['user_fullname'] }}</td>

                                <td class="text-center">
                                    {{ isset($doj_arr[$member['user_pk_no']]) ? date('d/m/Y', strtotime($doj_arr[$member['user_pk_no']])) : '' }}
                                </td>

                                <td class="text-center">
                                    {{ isset($dor_arr[$member['user_pk_no']]) ? date('d/m/Y', strtotime($dor_arr[$member['user_pk_no']])) : '' }}
                                </td>

                                <td class="text-center">
                                    {{ isset($report_data['new_lead'][$member['user_pk_no']]) ? $report_data['new_lead'][$member['user_pk_no']] : 0 }}
                                </td>

                                <td class="text-center">
                                    {{ isset($report_data['new_k1'][$member['user_pk_no']]) ? $report_data['new_k1'][$member['user_pk_no']] : 0 }}
                                </td>
                                <td class="text-center">
                                    {{ isset($report_data['new_total_k1'][$member['user_pk_no']])? $report_data['new_total_k1'][$member['user_pk_no']]: 0 }}
                                </td>
                                <td class="text-center">
                                    {{ isset($report_data['priority'][$member['user_pk_no']]) ? $report_data['priority'][$member['user_pk_no']] : 0 }}
                                </td>
                                <td class="text-center">
                                    {{ isset($report_data['con'][$member['user_pk_no']]) ? $report_data['con'][$member['user_pk_no']] : 0 }}
                                </td>
                                @php
                                    $result2 = 0;
                                    try {
                                        $result2 = $report_data['new_k1'][$member['user_pk_no']] / $report_data['new_lead'][$member['user_pk_no']];
                                    } catch (\Exception $e) {
                                        $result2 = 0;
                                    }
                                    $result2 = number_format(($result2 * 100),2);
                                @endphp

                                <td class="text-center">{{ $result2 }} %</td>
                                <td class="text-center">{{ $result }} %</td>
                                @php
                                    $result2 = 0;
                                    try {
                                        $result2 = $report_data['con'][$member['user_pk_no']] / $report_data['new_lead'][$member['user_pk_no']];
                                    } catch (\Exception $e) {
                                        $result2 = 0;
                                    }
                                    $result2 = number_format(($result2 * 100),2);
                                @endphp

                                <td class="text-center">{{ $result2 }} %</td>
                                <td class="text-center">
                                    {{ isset($report_data['total_new_lead'][$member['user_pk_no']])? $report_data['total_new_lead'][$member['user_pk_no']]: 0 }}
                                </td>
                                <td class="text-center">
                                    {{ isset($report_data['total_k1'][$member['user_pk_no']]) ? $report_data['total_k1'][$member['user_pk_no']] : 0 }}
                                </td>
                                <td class="text-center">
                                    {{ isset($report_data['t_con'][$member['user_pk_no']]) ? $report_data['t_con'][$member['user_pk_no']] : 0 }}
                                </td>
                                @php
                                    $result2 = 0;
                                    try {
                                        $result2 = $report_data['total_k1'][$member['user_pk_no']] / $report_data['total_new_lead'][$member['user_pk_no']];
                                    } catch (\Exception $e) {
                                        $result2 = 0;
                                    }
                                    $result2 = number_format(($result2 * 100),2);
                                @endphp

                                <td class="text-center">{{ $result2 }} %</td>
                                <td class="text-center">{{ $result1 }} %</td>
                                @php
                                    $result2 = 0;
                                    try {
                                        $result2 = $report_data['t_con'][$member['user_pk_no']] / $report_data['total_new_lead'][$member['user_pk_no']];
                                    } catch (\Exception $e) {
                                        $result2 = 0;
                                    }
                                    $result2 = number_format(($result2 * 100),2);
                                @endphp

                                <td class="text-center">{{ $result2 }} %</td>

                            </tr>
                            @php
                                $key++;
                                $tl_count++;
                                $i++;
                                $total_new_k1 += isset($report_data['new_k1'][$member['user_pk_no']]) ? $report_data['new_k1'][$member['user_pk_no']] : 0;
                                $total_k1 += isset($report_data['new_total_k1'][$member['user_pk_no']]) ? $report_data['total_k1'][$member['user_pk_no']] : 0;
                                $total_prority += isset($report_data['priority'][$member['user_pk_no']]) ? $report_data['priority'][$member['user_pk_no']] : 0;
                                $total_m_con += isset($report_data['con'][$member['user_pk_no']]) ? $report_data['con'][$member['user_pk_no']] : 0;
                                $total_y_con += isset($report_data['t_con'][$member['user_pk_no']]) ? $report_data['t_con'][$member['user_pk_no']] : 0;
                                $total_new_lead += isset($report_data['new_lead'][$member['user_pk_no']]) ? $report_data['new_lead'][$member['user_pk_no']] : 0;
                                $total_all_lead += isset($report_data['total_new_lead'][$member['user_pk_no']]) ? $report_data['total_new_lead'][$member['user_pk_no']] : 0;
                                $total_all_k1 += isset($report_data['new_total_k1'][$member['user_pk_no']]) ? $report_data['new_total_k1'][$member['user_pk_no']] : 0;
                            @endphp





                        @endforeach



                        <tr style="background-color:#9ec1d2f5;color:white">
                            <td colspan="6" class="text-center"> Resigned/ Promotion</td>

                            <td class="text-center">
                                {{ isset($resize_arr['new_lead']) ? $resize_arr['new_lead'] : 0 }}
                            </td>
                            <td class="text-center">
                                {{ isset($resize_arr['new_k1']) ? $resize_arr['new_k1'] : 0 }}
                            </td>
                            <td class="text-center">
                                {{ isset($resize_arr['new_total_k1']) ? $resize_arr['new_total_k1'] : 0 }}
                            </td>
                            <td class="text-center">
                                {{ isset($resize_arr['priority']) ? $resize_arr['priority'] : 0 }}
                            </td>
                            <td class="text-center">
                                {{ isset($resize_arr['con']) ? $resize_arr['con'] : 0 }}
                            </td>

                            @php
                                $result2 = 0;
                                try {
                                    $result2 = $resize_arr['new_k1'] / $resize_arr['new_lead'];
                                } catch (\Exception $e) {
                                    $result2 = 0;
                                }
                                $result2 = number_format(($result2 * 100),2);
                            @endphp
                            <td class="text-center">{{ $result2 }} %</td>
                            @php
                                $result2 = 0;
                                try {
                                    $result2 = $resize_arr['con'] / $resize_arr['new_total_k1'];
                                } catch (\Exception $e) {
                                    $result2 = 0;
                                }
                                $result2 = number_format(($result2 * 100),2);
                            @endphp

                            <td class="text-center">{{ $result2 }}%</td>

                            @php
                                $result2 = 0;
                                try {
                                    $result2 = $resize_arr['con'] / $resize_arr['new_lead'];
                                } catch (\Exception $e) {
                                    $result2 = 0;
                                }
                                $result2 = number_format(($result2 * 100),2);
                            @endphp
                            <td class="text-center">{{ $result2 }} %</td>
                            <td class="text-center">
                                {{ isset($resize_arr['total_new_lead']) ? $resize_arr['total_new_lead'] : 0 }}
                            </td>
                            <td class="text-center">
                                {{ isset($resize_arr['total_k1']) ? $resize_arr['total_k1'] : 0 }}
                            </td>


                            <td class="text-center">
                                {{ isset($resize_arr['t_con']) ? $resize_arr['t_con'] : 0 }}
                            </td>
                            @php
                                $result2 = 0;
                                try {
                                    $result2 = $resize_arr['total_k1'] / $resize_arr['total_k1'];
                                } catch (\Exception $e) {
                                    $result2 = 0;
                                }
                                $result2 = number_format(($result2 * 100),2);
                            @endphp
                            <td class="text-center">{{ $result2 }} %</td>
                            @php
                                $result2 = 0;
                                try {
                                    $result2 = $resize_arr['t_con'] / $resize_arr['total_k1'];
                                } catch (\Exception $e) {
                                    $result2 = 0;
                                }
                                $result2 = number_format(($result2 * 100),2);
                            @endphp

                            <td class="text-center">{{ $result2 }}%</td>

                            @php
                                $result2 = 0;
                                try {
                                    $result2 = $resize_arr['total_k1'] / $resize_arr['total_new_lead'];
                                } catch (\Exception $e) {
                                    $result2 = 0;
                                }
                                $result2 = number_format(($result2 * 100),2);
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

                        @php
                            $key++;
                            $tl_count++;
                            $i++;

                            $total_new_k1 += isset($resize_arr['new_k1']) ? $resize_arr['new_k1'] : 0;
                            $total_k1 += isset($resize_arr['total_k1']) ? $resize_arr['total_k1'] : 0;
                            $total_prority += isset($resize_arr['priority']) ? $resize_arr['priority'] : 0;
                            $total_m_con += isset($resize_arr['con']) ? $resize_arr['con'] : 0;
                            $total_y_con += isset($resize_arr['t_con']) ? $resize_arr['t_con'] : 0;
                            $total_new_lead += isset($resize_arr['new_lead']) ? $resize_arr['new_lead'] : 0;
                            $total_all_lead += isset($resize_arr['total_new_lead']) ? $resize_arr['total_new_lead'] : 0;
                            $total_all_k1 += isset($resize_arr['new_total_k1']) ? $resize_arr['new_total_k1'] : 0;
                            // dd( $resize_arr['con'],   $total_m_con);
                        @endphp

                        <tr style="background-color:#5f5fd4;color:white">
                            <td colspan="6" class="text-center"> Total</td>

                            <td class="text-center"> {{ $total_new_lead }}</td>
                            <td class="text-center">{{ $total_new_k1 }}</td>
                            <td class="text-center">{{ $total_all_k1 }}</td>
                            <td class="text-center">{{ $total_prority }}</td>
                            <td class="text-center">{{ $total_m_con }}</td>
                            @php
                                $result2 = 0;
                                try {
                                    $result2 = $total_new_k1 / $total_new_lead;
                                } catch (\Exception $e) {
                                    $result = 0;
                                }
                                $result2 = number_format(($result2 * 100),2);
                            @endphp
                            <td class="text-center">{{ $result2 }} %</td>
                            @php
                                $result2 = 0;
                                try {
                                    $result2 = $total_m_con / $total_all_k1;
                                } catch (\Exception $e) {
                                    $result = 0;
                                }
                                $result2 = number_format(($result2 * 100),2);
                            @endphp
                            <td class="text-center">{{ $result2 }}%</td>

                            @php
                                $result2 = 0;
                                try {
                                    $result2 = $total_m_con / $total_new_lead;
                                } catch (\Exception $e) {
                                    $result = 0;
                                }
                                $result2 = number_format(($result2 * 100),2);
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
                                $result2 = number_format(($result2 * 100),2);
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
                                $result2 = number_format(($result2 * 100),2);
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

                        </tr>



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
                name: "OBM_Conversion_report.xlsx",
                sheet: {
                    name: "Sheet1"
                }
            });
        }
    </script>
