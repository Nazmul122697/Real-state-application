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
                <th style=" min-width: 100px" class="text-center">Name</th>
                <th style=" min-width: 100px" class="text-center">Category</th>
                <th style=" min-width: 155px" class="text-center">Area</th>

                <th style=" width: 100px;" class="text-center">Target of This Month
                </th>

                <th style=" width: 100px;" class="text-center">Target of YTD
                </th>
                <th style=" width: 100px;" class="text-center">Designation
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
                        <td class="text-center">{{ $area_ids }}</td>
                        <td class="text-center">
                            <div class="form-group">
                                <input type="number" class="form-control number_only text-right " id="target_amount"
                                    name="target[]" autocomplete="off"
                                    value="{{ isset($target_arr[$member->user_pk_no]['target_mm']) ? $target_arr[$member->user_pk_no]['target_mm'] : '' }}"
                                    title="" placeholder="0" />
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-group">
                                <input type="number" class="form-control number_only text-right " id="target_qty"
                                    name="ytd_target[]" autocomplete="off"
                                    value="{{ isset($target_arr[$member->user_pk_no]['target_ytd']) ? $target_arr[$member->user_pk_no]['target_ytd'] : '' }}"
                                    title="" placeholder="0" />
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-group">
                                <select name="desgination[]" class="form-control" id="">
                                    <option value="">Select One</option>
                                    <option value="Sr. Consultant"
                                        {{ isset($target_arr[$member->user_pk_no]['designation']) ? ($target_arr[$member->user_pk_no]['designation'] == 'Sr. Consultant' ? 'selected' : '') : '' }}>
                                        Sr. Consultant</option>
                                    <option value="Consultant"
                                        {{ isset($target_arr[$member->user_pk_no]['designation']) ? ($target_arr[$member->user_pk_no]['designation'] == 'Consultant' ? 'selected' : '') : '' }}>
                                        Consultant</option>

                                </select>
                            </div>
                        </td>
                        @php
                            $lead_type1 = 0;
                            $lead_type1 = isset($target_arr[$member->user_pk_no]['lead_type']) ? $target_arr[$member->user_pk_no]['lead_type'] : 0;
                        @endphp

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
