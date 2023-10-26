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
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Search Result</h3>
        {{-- <button type="submit" class="btn bg-blue btn-xs pull-right" id="btnExportLeads">Export to CSV</button> --}}
    </div>

    <div class="box-body table-responsive">
        <table id="tbl_search_result" class="table table-bordered table-striped table-hover">
            <thead style="background-color:#5f5fd4;color:white">


                <tr>
                    <th class="text-center" rowspan="2">SL</th>
                    <th class="text-center" rowspan="2">Category</th>
                    <th class="text-center" colspan="6">{{ isset($month) ? $month : 'January' }} -
                        {{ isset($target_year1) ? $target_year1 : '2021' }}</th>
                    <th class="text-center" colspan="6" style="background-color:#a97e2f;color:white">Year to Date
                    </th>
                </tr>
                <tr>

                    <th>New Lead</th>
                    <th>New K</th>
                    <th>Sales</th>
                    <th>Lead to K Con.</th>
                    <th>K to Sale Con.</th>
                    <th>Lead to Sales Con.</th>
                    <th style="background-color:#a97e2f;color:white">Total New Lead</th>
                    <th style="background-color:#a97e2f;color:white">Total New K</th>
                    <th style="background-color:#a97e2f;color:white">Sales</th>
                    <th style="background-color:#a97e2f;color:white">Lead to K Con.</th>
                    <th style="background-color:#a97e2f;color:white">K to Sale Con.</th>
                    <th style="background-color:#a97e2f;color:white">Lead to Sales Con.</th>

                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                    $rand = '#F5C87B';
                    $rand_pre = '';
                    $newk = 0;
                    $totalk = 0;
                    $prority = 0;
                    $sales = 0;
                    $tnewk = 0;
                    $new_lead = 0;
                    $total_new_lead = 0;
                    $total_new_k1 = 0;

                @endphp
                @if (!empty($result_arr))
                    <tr>
                        <td>1</td>
                        <td>DSI</td>
                        <td>
                            {{ $result_arr['dsi'] }}
                        </td>
                        <td>
                            {{ $result_arr['dsi_total'] }}
                        </td>
                        <td>
                            {{ $result_arr['dsi_sold'] }}
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['dsi_total'] / $result_arr['dsi']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['dsi_sold'] / $result_arr['dsi_total']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['dsi_sold'] / $result_arr['dsi']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        <td>
                            {{ $result_arr['t_dsi'] }}
                        </td>
                        <td>
                            {{ $result_arr['t_dsi_total'] }}
                        </td>
                        <td>
                            {{ $result_arr['t_dsi_sold'] }}
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['t_dsi_total'] / $result_arr['t_dsi']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['t_dsi_sold'] / $result_arr['t_dsi_total']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['t_dsi_sold'] / $result_arr['t_dsi']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>CRE</td>
                        <td>
                            {{ $result_arr['cre'] }}
                        </td>
                        <td>
                            {{ $result_arr['cre_total'] }}
                        </td>
                        <td>
                            {{ $result_arr['cre_sold'] }}
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['cre_total'] / $result_arr['cre']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['cre_sold'] / $result_arr['cre_total']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['cre_sold'] / $result_arr['cre']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        <td>
                            {{ $result_arr['t_cre'] }}
                        </td>
                        <td>
                            {{ $result_arr['t_cre_total'] }}
                        </td>
                        <td>
                            {{ $result_arr['t_cre_sold'] }}
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['t_cre_total'] / $result_arr['t_cre']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['t_cre_sold'] / $result_arr['t_cre_total']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['t_cre_sold'] / $result_arr['t_cre']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>IR</td>
                        <td>
                            {{ $result_arr['ir'] }}
                        </td>
                        <td>
                            {{ $result_arr['ir_total'] }}
                        </td>
                        <td>
                            {{ $result_arr['ir_sold'] }}
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['ir_total'] / $result_arr['ir']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['ir_sold'] / $result_arr['ir_total']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['ir_sold'] / $result_arr['ir']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        <td>
                            {{ $result_arr['t_ir'] }}
                        </td>
                        <td>
                            {{ $result_arr['t_ir_total'] }}
                        </td>
                        <td>
                            {{ $result_arr['t_ir_sold'] }}
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['t_ir_total'] / $result_arr['t_ir']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['t_ir_sold'] / $result_arr['t_ir_total']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['t_ir_sold'] / $result_arr['t_ir']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Hotline</td>
                        <td>
                            {{ $result_arr['hotine'] }}
                        </td>
                        <td>
                            {{ $result_arr['hotine_total'] }}
                        </td>
                        <td>
                            {{ $result_arr['hotine_sold'] }}
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['hotine_total'] / $result_arr['hotine']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['hotine_sold'] / $result_arr['hotine_total']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['hotine_sold'] / $result_arr['hotine']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        <td>
                            {{ $result_arr['t_hotine'] }}
                        </td>
                        <td>
                            {{ $result_arr['t_hotine_total'] }}
                        </td>
                        <td>
                            {{ $result_arr['t_hotine_sold'] }}
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['t_hotine_total'] / $result_arr['t_hotine']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['t_hotine_sold'] / $result_arr['t_hotine_total']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['t_hotine_sold'] / $result_arr['t_hotine']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>ACD</td>
                        <td>
                            {{ $result_arr['acd'] }}
                        </td>
                        <td>
                            {{ $result_arr['acd_total'] }}
                        </td>
                        <td>
                            {{ $result_arr['acd_sold'] }}
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['acd_total'] / $result_arr['acd']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['acd_sold'] / $result_arr['acd_total']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['acd_sold'] / $result_arr['acd']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        <td>
                            {{ $result_arr['t_acd'] }}
                        </td>
                        <td>
                            {{ $result_arr['t_acd_total'] }}
                        </td>
                        <td>
                            {{ $result_arr['t_acd_sold'] }}
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['t_acd_total'] / $result_arr['t_acd']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['t_acd_sold'] / $result_arr['t_acd_total']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['t_acd_sold'] / $result_arr['t_acd']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>Exiting Customer</td>
                        <td>
                            {{ $result_arr['ex'] }}
                        </td>
                        <td>
                            {{ $result_arr['ex_total'] }}
                        </td>
                        <td>
                            {{ $result_arr['ex_sold'] }}
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['ex_total'] / $result_arr['ex']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['ex_sold'] / $result_arr['ex_total']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['ex_sold'] / $result_arr['ex']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        <td>
                            {{ $result_arr['t_ex'] }}
                        </td>
                        <td>
                            {{ $result_arr['t_ex_total'] }}
                        </td>
                        <td>
                            {{ $result_arr['t_ex_sold'] }}
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['t_ex_total'] / $result_arr['t_ex']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['t_ex_sold'] / $result_arr['t_ex_total']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['t_ex_sold'] / $result_arr['t_ex']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                    </tr>

                    <tr>
                        <td>7</td>
                        <td>ReFresh</td>
                        <td>
                            {{ $result_arr['refresh'] }}
                        </td>
                        <td>
                            {{ $result_arr['refresh_total'] }}
                        </td>
                        <td>
                            {{ $result_arr['refresh_sold'] }}
                        </td>

                        @php

                            $result = 0;
                            try {
                                $result = ($result_arr['refresh_total'] / $result_arr['refresh']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['refresh_sold'] / $result_arr['refresh_total']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['refresh_sold'] / $result_arr['refresh']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        <td>
                            {{ $result_arr['t_refresh'] }}
                        </td>
                        <td>
                            {{ $result_arr['t_refresh_total'] }}
                        </td>
                        <td>
                            {{ $result_arr['t_refresh_sold'] }}
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['t_refresh_total'] / $result_arr['t_refresh']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['t_refresh_sold'] / $result_arr['t_refresh_total']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['t_refresh_sold'] / $result_arr['t_refresh']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>Close</td>
                        <td>
                            {{ $result_arr['close'] }}
                        </td>
                        <td>
                            {{ $result_arr['close_total'] }}
                        </td>
                        <td>
                            {{ $result_arr['close_sold'] }}
                        </td>

                        @php

                            $result = 0;
                            try {
                                $result = ($result_arr['close_total'] / $result_arr['close']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['close_sold'] / $result_arr['close_total']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['close_sold'] / $result_arr['close']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        <td>
                            {{ $result_arr['t_close'] }}
                        </td>
                        <td>
                            {{ $result_arr['t_close_total'] }}
                        </td>
                        <td>
                            {{ $result_arr['t_close_sold'] }}
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['t_close_total'] / $result_arr['t_close']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['t_close_sold'] / $result_arr['t_close_total']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = ($result_arr['t_close_sold'] / $result_arr['t_close']) * 100;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($result, 2) }} %
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">Total</td>
                        <td>{{ $total = $result_arr['cre'] +  $result_arr['acd'] + $result_arr['ex'] + $result_arr['hotine'] + $result_arr['ir'] + $result_arr['dsi']  + $result_arr['refresh'] + $result_arr['close'] }}
                        </td>
                        <td>{{ $total1 = $result_arr['cre_total']  + $result_arr['acd_total'] + $result_arr['ex_total'] + $result_arr['hotine_total'] + $result_arr['ir_total'] + $result_arr['dsi_total']  + $result_arr['refresh_total'] + $result_arr['close_total'] }}
                        </td>
                        <td>{{ $total2 = $result_arr['cre_sold']  + $result_arr['acd_sold'] + $result_arr['ex_sold'] + $result_arr['hotine_sold'] + $result_arr['ir_sold'] + $result_arr['dsi_sold']  + $result_arr['refresh_sold'] + $result_arr['close_sold'] }}
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = $total1 / $total;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                            $result= $result*100;
                        @endphp
                        <td>{{ number_format($result, 2) }} %</td>
                        @php
                            $result = 0;
                            try {
                                $result = $total2 / $total1;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                            $result= $result*100;
                        @endphp
                        <td>{{ number_format($result, 2) }} %</td>
                        @php
                            $result = 0;
                            try {
                                $result = $total2 / $total;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                            $result= $result*100;
                        @endphp
                        <td>{{ number_format($result, 2) }} %</td>
                        <td>{{ $total = $result_arr['t_cre'] + $result_arr['t_acd'] + $result_arr['t_ex'] + $result_arr['t_hotine'] + $result_arr['t_ir'] + $result_arr['t_dsi']  + $result_arr['t_refresh'] + $result_arr['t_close'] }}
                        </td>
                        <td>{{ $total1 = $result_arr['t_cre_total'] + $result_arr['t_acd_total'] + $result_arr['t_ex_total'] + $result_arr['t_hotine_total'] + $result_arr['t_ir_total'] + $result_arr['t_dsi_total'] + $result_arr['t_refresh_total'] + $result_arr['t_close_total'] }}
                        </td>
                        <td>{{ $total2 = $result_arr['t_cre_sold']+ $result_arr['t_acd_sold'] + $result_arr['t_ex_sold'] + $result_arr['t_hotine_sold'] + $result_arr['t_ir_sold'] + $result_arr['t_dsi_sold']  + $result_arr['t_refresh_sold'] + $result_arr['t_close_sold'] }}
                        </td>
                        @php
                            $result = 0;
                            try {
                                $result = $total1 / $total;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                            $result= $result*100;
                        @endphp
                        <td>{{ number_format($result, 2) }} %</td>
                        @php
                            $result = 0;
                            try {
                                $result = $total2 / $total1;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                            $result= $result*100;
                        @endphp
                        <td>{{ number_format($result, 2) }} %</td>
                        @php
                            $result = 0;
                            try {
                                $result = $total2 / $total;
                            } catch (Exception $e) {
                                $result = 0;
                            }
                            $result= $result*100;
                        @endphp
                        <td>{{ number_format($result, 2) }} %</td>
                    </tr>



                @endif

            </tbody>


        </table>
    </div>
</div>
