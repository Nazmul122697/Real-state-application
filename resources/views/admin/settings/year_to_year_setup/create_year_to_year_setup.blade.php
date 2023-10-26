<form id="frmUser"
    action="{{ !isset($year_setup) ? route('store_year_setup') : route('update_year_setup') }}"
    method="{{ !isset($year_setup) ? 'post' : 'post' }}">
    <input type="hidden" id="hdnYearSetup" name="hdnYearSetup"
        value="{{ isset($year_setup) ? $year_setup->id : '' }}" />
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="flat_id">Starting Year </label>
                                <input type="text" class="form-control" id="s_year_name" name="s_year_name" title="Year Name"
                                    placeholder="Year" tabindex="" value="{{ isset($year_setup) ? $year_setup->starting_year : '' }}">
                            </div>
                            <div class="form-group">
                                <label for="">Starting Month</label>
                                <select class="form-control " id="s_year_month" name="s_year_month" style="width: 100%;"
                                    aria-hidden="true">
                                    <option value="-1">Select month</option>

                                    <option <?php if (date('m')=='1') { echo 'selected' ; } ?> value="01">January</option>
                                    <option <?php if (date('m')=='2' ) { echo 'selected' ; } ?> value="02">February</option>
                                    <option <?php if (date('m')=='3' ) { echo 'selected' ; } ?> value="03">March</option>
                                    <option <?php if (date('m')=='4' ) { echo 'selected' ; } ?> value="04">April</option>
                                    <option <?php if (date('m')=='5' ) { echo 'selected' ; } ?> value="05">May</option>
                                    <option <?php if (date('m')=='6' ) { echo 'selected' ; } ?> value="06">June</option>
                                    <option <?php if (date('m')=='7' ) { echo 'selected' ; } ?> value="07">July</option>
                                    <option <?php if (date('m')=='8' ) { echo 'selected' ; } ?> value="08">Augest</option>
                                    <option <?php if (date('m')=='9' ) { echo 'selected' ; } ?> value="09">September</option>
                                    <option <?php if (date('m')=='10' ) { echo 'selected' ; } ?> value="10">October</option>
                                    <option <?php if (date('m')=='11' ) { echo 'selected' ; } ?> value="11">November</option>
                                    <option <?php if (date('m')=='12' ) { echo 'selected' ; } ?> value="12">December</option>



                                </select>
                            </div>
                            <div class="form-group">
                                <label for="flat_id">Closing Year </label>
                                <input type="text" class="form-control" id="c_year_name" name="c_year_name" title="Year Name"
                                    placeholder="Year" tabindex="" value="{{ isset($year_setup) ? $year_setup->closing_year : '' }}">
                            </div>
                            <div class="form-group">
                                <label for="">Closing Month</label>
                                <select class="form-control " id="c_year_month" name="c_year_month" style="width: 100%;"
                                    aria-hidden="true">
                                    <option value="-1">Select month</option>

                                    <option <?php if (date('m')=='1' ) { echo 'selected' ; } ?> value="01">January</option>
                                    <option <?php if (date('m')=='2' ) { echo 'selected' ; } ?> value="02">February</option>
                                    <option <?php if (date('m')=='3' ) { echo 'selected' ; } ?> value="03">March</option>
                                    <option <?php if (date('m')=='4' ) { echo 'selected' ; } ?> value="04">April</option>
                                    <option <?php if (date('m')=='5' ) { echo 'selected' ; } ?> value="05">May</option>
                                    <option <?php if (date('m')=='6' ) { echo 'selected' ; } ?> value="06">June</option>
                                    <option <?php if (date('m')=='7' ) { echo 'selected' ; } ?> value="07">July</option>
                                    <option <?php if (date('m')=='8' ) { echo 'selected' ; } ?> value="08">Augest</option>
                                    <option <?php if (date('m')=='9' ) { echo 'selected' ; } ?> value="09">September</option>
                                    <option <?php if (date('m')=='10' ) { echo 'selected' ; } ?> value="10">October</option>
                                    <option <?php if (date('m')=='11' ) { echo 'selected' ; } ?> value="11">November</option>
                                    <option <?php if (date('m')=='12' ) { echo 'selected' ; } ?> value="12">December</option>

                                </select>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm pull-left" data-dismiss="modal">Close</button>
        <button type="submit"
            class="btn bg-purple btn-sm btnSaveUpdate">{{ isset($flat_data) ? 'Update' : 'Create' }}</button>
        <span class="msg"></span>
    </div>
</form>
