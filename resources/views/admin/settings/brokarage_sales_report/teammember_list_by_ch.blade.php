@php
$user_type = Session::get('user.user_type');
@endphp
<link rel="stylesheet"
    href="{{ asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

<br />
<div class="col-md-12 table-responsive">
    <table id="" class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th style="" class="text-center">Sales Exicutive ID</th>
                <th  class="text-center">Name</th>
                <th  class="text-center">Category</th>
                {{-- <th  class="text-center">Area</th> --}}
                <th class="text-center">Date of Join

                </th>
                <th class="text-center">Date of Report

                </th>
                {{-- <th style=" width: 100px;" class="text-center">Emp. Status

                </th> --}}
                <th> Target Value(Sales) </th>
                <th class="text-center">Lead Type
                </th>
        </thead>

        <tbody>
            @if (!empty($team_member))
                @foreach ($team_member as $member)
                    @php
                        $area_ids = '';
                        $area_ids_arr = explode(',', $member->area_lookup_pk_no);
                        if (!empty($area_ids_arr)) {
                            foreach ($area_ids_arr as $area_id) {
                                $area_ids .= $project_area[$area_id] . ', ';
                            }
                        }
                        $area_ids = rtrim($area_ids, ', ');
                    @endphp
                    <tr>
                        <input type="hidden" name="team_user[]" value="{{ $member->user_pk_no }}" />
                        <input type="hidden" name="category_id[]" value="{{ $member->category_lookup_pk_no }}" />
                        <input type="hidden" name="area_id[]" value="{{ $member->area_lookup_pk_no }}" />
                        <input type="hidden" name="target_pk_no[]"
                            value="{{ isset($target_arr[$member->user_pk_no]['target_pk_no']) ? $target_arr[$member->user_pk_no]['target_pk_no'] : '' }}" />

                        <td class="text-center">{{ $member->user_pk_no }}</td>
                        <td class="text-center">{{ $member->user_fullname }}</td>
                        <td class="text-center">
                            {{ isset($project_cat[$member->category_lookup_pk_no]) ? $project_cat[$member->category_lookup_pk_no] : '' }}
                        </td>
                        {{-- <td class="text-center">{{ $area_ids }}</td> --}}
                        <td class="text-center">
                            <div class="form-group">
                                <input type="text" class="form-control datepicker text-right " id="target_amount"
                                    name="date_of_join[]" autocomplete="off"
                                    value="{{ isset($target_arr[$member->user_pk_no]['date_of_join']) ? date('d-m-Y', strtotime($target_arr[$member->user_pk_no]['date_of_join'])) : '' }}"
                                    title="" placeholder="dd-mm-yyyy" />
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-group">
                                <input type="text" class="form-control datepicker text-right " id="target_qty"
                                    name="date_of_resign[]" autocomplete="off"
                                    value="{{ isset($target_arr[$member->user_pk_no]['date_of_report']) ? date('d-m-Y', strtotime($target_arr[$member->user_pk_no]['date_of_report'])) : '' }}"
                                    title="" placeholder="dd-mm-yyyy" />
                            </div>
                        </td>
                        <td>
                            <input type="text" name="target_value[]" value="" placeholder="">
                        </td>
                        {{-- <td class="text-center">
                            <div class="form-group">
                                <select name="e_status[]" class="form-control" id="">
                                    <option value="0">Normal</option>
                                    <option value="1">Promoted</option>
                                    <option value="2">Resigned</option>

                                </select>
                            </div>
                        </td> --}}
                        @php
                            $lead_type1 = 0;
                            $lead_type1 = isset($target_arr[$member->user_pk_no]['lead_type']) ? $target_arr[$member->user_pk_no]['lead_type'] : 0;
                        @endphp
                        <td class="text-center">
                            <div class="form-group">
                                <select name="lead_type[]" class="form-control" id="">
                                    <option value="0">Select</option>
                                    @if (!empty($lead_type))
                                        @foreach ($lead_type as $key => $type)
                                            <option value="{{ $key }}"
                                                {{ $key == $lead_type1 ? 'selected' : '' }}>{{ $type }}
                                            </option>
                                        @endforeach
                                    @endif

                                </select>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
        {{-- <tfoot>
            <tr>
                <td colspan="5"></td>
                <td colspan="3" class="text-center">
                    
                </td>
            </tr>
        </tfoot> --}}
    </table>
</div>
{{-- <div class="col-md-12">
    <hr>
    <h3>Promoted and Resigned</h3>
    <hr>
    <table class="table table-bordered table-striped table-hover">
        <tbody id="data-append-to">
            <tr id="0">
                <th style="width: 110px;">Sales Agent : </th>
                <td style="width: 350px;">
                    <Select class="form-control" id="s_0" name="sales_agent[]">
                        <option value="0">Select One</option>
                        @if (!empty($sales_agent))
                            @foreach ($sales_agent as $row)
                                <option value="{{ $row->user_pk_no }}">{{ $row->user_fullname }}</option>
                            @endforeach
                        @endif
                    </Select>
                </td>
                <td>
                    Emp. Status :
                </td>
                <td style="width: 350px;">

                    <Select class="form-control" id="emp_0" name="emp_status[]">
                        <option value="0">Select One</option>
                        <option value="Promoted">Promoted</option>
                        <option value="Rejected">Rejected</option>
                    </Select>
                </td>
                <td id="btn_add_0">
                    <span class="btn bg-green btn-sm" onclick="addMedRow(this)"><i class='fa fa-plus'></i></span>
                </td>
            </tr>
            @if (!empty($sales_agent_arr))
                @foreach ($sales_agent_arr as $key => $value)
                    <tr id="0">
                        <th style="width: 110px;">Sales Agent : </th>
                        <td style="width: 350px;">
                            <Select class="form-control" id="s_0" name="sales_agent[]">
                                <option value="0">Select One</option>
                                @if (!empty($sales_agent))
                                    @foreach ($sales_agent as $row)
                                        <option value="{{ $row->user_pk_no }}" {{ $row->user_pk_no== $key ?"selected": "" }}>{{ $row->user_fullname }}</option>
                                    @endforeach
                                @endif
                            </Select>
                        </td>
                        <td>
                            Emp. Status :
                        </td>
                        <td style="width: 350px;">

                            <Select class="form-control" id="emp_0" name="emp_status[]">
                                <option value="0">Select One</option>
                                <option value="Promoted" {{ 'Promoted'== $value ?"selected": "" }}>Promoted</option>
                                <option value="Rejected" {{ 'Rejected'== $value ?"selected": "" }}>Rejected</option>
                            </Select>
                        </td>
                        <td id="btn_add_0">
                            <span class="btn bg-danger btn-sm" onclick="removeTableRowOnly(this)"><i
                                    class='fa fa-plus'></i></span>
                        </td>
                    </tr>
                @endforeach

            @endif
        </tbody>
    </table>
</div> --}}
<div class="col-md-3 col-md-offset-4">
    <button type="submit" class="btn bg-green btn-block btnSaveUpdate">Save</button>
</div>

<script src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}">
</script>
<script>
    $(function() {
        var datepickerOptions = {
            autoclose: true,
            format: 'dd-mm-yyyy',
            todayBtn: true,
        };

        $('.datepicker').datepicker(datepickerOptions);
    });

    function addMedRow(thisElement) {

        var attribute_id = $(thisElement).parent('td').attr('id');

        var row = $(thisElement).parents("tr").clone();
        var oldId = Number($(thisElement).parents("tr").attr("id"));
        var newId = $(thisElement).parents("#data-append-to").find("tr").length + 1;
        row.attr('id', newId);
        row.find('#s_' + oldId).attr('id', 's_' + newId);
        row.find('#emp_' + oldId).attr('id', 'emp_' + newId);
        row.find('#project_sgl_con_' + oldId).attr('id', 'project_sgl_con_' + newId);

        row.find('#emp_' + newId).val($(thisElement).parents('tbody').find('#emp_' + oldId).val());
        row.find('#s_' + newId).val($(thisElement).parents('tbody').find('#s_' + oldId).val());
        row.find('#project_sgl_con_' + newId).val($(thisElement).parents('tbody').find('#project_sgl_con_' + oldId)
            .val());

        $(thisElement).parents('tbody').find('#emp_' + oldId).val(0);
        $(thisElement).parents('tbody').find('#s_' + oldId).val(0);

        row.find('#btn_add_' + oldId).attr('id', 'btn_add_' + newId);
        $(thisElement).parents("#data-append-to").append(row);

        $('#btn_add_' + newId).html(
            "<span class='btn btn-danger btn-sm' onclick='removeTableRowOnly(this)'> <i class='fa fa-times'></i> </span>"
        );

    }

    function removeTableRowOnly(thisElement) {
        if (confirm("Are you sure?")) {
            $(thisElement).parents("tr").remove();
        }
        return;
    }

</script>
