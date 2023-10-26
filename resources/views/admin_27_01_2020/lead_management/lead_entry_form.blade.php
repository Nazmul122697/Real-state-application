@php
$group_id = Session::get('user.ses_role_lookup_pk_no');
@endphp
<form id="frmLead" action="{{ !isset($lead_data)?route('lead.store') : route('lead.update',$lead_data->lead_pk_no) }}" method="{{ !isset($lead_data)?'post' : 'patch' }}">
	<div class="box box-success">
		<div class="box-header with-border ">
			<h3 class="box-title">Customer Information</h3>
			@if($group_id == 74)
			<a href="{{ route('import_csv') }}" class="btn bg-green btn-sm pull-right">Import CSV</a>
			@endif
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="txt_lead_id">Lead ID </label>
						<input type="text" class="form-control" id="txt_lead_id" readonly="readonly" name="txt_lead_id" value="" title="" placeholder="USERGROPCODE+YYMM+99999"/>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label for="txt_lead_date">Date<span class="text-danger"> *</span></label>
						<input type="text" class="form-control datepicker required" id="txt_lead_date" name="txt_lead_date" value="<?php echo date('d-m-Y'); ?>" title="" readonly="" placeholder="Entry Date"/>
					</div>
				</div>

				<div class="col-md-12">
					<label for="txt_cus_first_name">Customer Name<span class="text-danger"> *</span></label>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<input type="text" class="form-control required capitalize-text" id="customer_first_name" name="customer_first_name" value="" title="" placeholder="Customer First Name"/>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<input type="text" class="form-control required capitalize-text" id="customer_last_name" name="customer_last_name" value="" title="" placeholder="Customer Last Name"/>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-12">

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<div><label for="customer_phone1">Phone Number 1 <span class="text-danger"> *</span></label></div>
								<div class="col-xs-4" style="padding-left: 0;">
									<select class="form-control select2" name="country_code1" aria-hidden="true">
										<option selected="selected" value="0">Country Code</option>
										@if(!empty($countries))
										@foreach ($countries as $country)
										<option value="{{ $country->phonecode }}" {{ ($country->iso=='BD')? 'selected':'' }} >{{ $country->name ." (". $country->phonecode.")" }}</option>
										@endforeach
										@endif
									</select>
								</div>
								<div class="col-xs-8">
									<input type="number" class="form-control number-only required check_phone_no" id="customer_phone1" name="customer_phone1" maxlength="10" placeholder="Phone Number 1"/>
								</div>

							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<div><label for="customer_phone2">Phone Number 2</label></div>
								<div class="col-xs-4" style="padding-left: 0;">
									<select class="form-control select2" name="country_code2" aria-hidden="true">
										<option selected="selected" value="0">Country Code</option>
										@if(!empty($countries))
										@foreach ($countries as $key => $country)
										<option value="{{ $country->phonecode }}" {{ ($country->iso=='BD')? 'selected':'' }}>{{ $country->name ." (". $country->phonecode.")" }}</option>
										@endforeach
										@endif
									</select>
								</div>
								<div class="col-xs-8">
									<input type="number" class="form-control number-only check_phone_no" id="customer_phone2" name="customer_phone2" maxlength="10" placeholder="Phone Number 2"/>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label for="txt_cus_email">Customer Email<span class="text-danger"> *</span> </label>
						<input type="email" class="form-control required email-only" id="customer_email" name="customer_email" value="" title="Customer Email" placeholder="e.g. username@bti.com"/>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label>Occupation </label>
						<select class="form-control select2" name="cmb_ocupation" style="width: 100%;" aria-hidden="true">
							<option selected="selected" value="0">Select Occupation</option>
							@if(!empty($ocupations))
							@foreach ($ocupations as $key => $ocupation)
							<option value="{{ $key }}">{{ $ocupation }}</option>
							@endforeach
							@endif
						</select>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label for="txt_organization">Organization </label>
						<input type="email" class="form-control" id="txt_organization" name="txt_organization" value="" title="" placeholder="Organization"/>
					</div>
				</div>
			</div>
		</div>
		<!-- /.box-body -->
	</div>

	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">Project Detail</h3>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label>Category<span class="text-danger"> *</span></label>
						<select class="form-control required" id="cmb_category" name="cmb_category" data-action="{{ route('load_area_project_size') }}" aria-hidden="true">
							<option selected="selected" value="0">Select Category</option>
							@if(!empty($project_cat))
							@foreach ($project_cat as $key => $cat)
							<option value="{{ $key }}">{{ $cat }}</option>
							@endforeach
							@endif
						</select>
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label>Area<span class="text-danger"> *</span></label>
						<select class="form-control required" id="cmb_area" name="cmb_area" style="width: 100%;" aria-hidden="true">
							<option selected="selected" value="">Select Area</option>
						</select>
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label>Project Name<span class="text-danger"> *</span></label>
						<select class="form-control required" id="cmb_project_name" name="cmb_project_name" style="width: 100%;" aria-hidden="true">
							<option selected="selected" value="">Select Project Name</option>
						</select>
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label>Size<span class="text-danger"> *</span></label>
						<select class="form-control required" id="cmb_size" name="cmb_size" style="width: 100%;" aria-hidden="true">
							<option selected="selected" value="">Select Size</option>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="box" style="border-color:#ff851b;">
		<div class="box-header with-border">
			<h3 class="box-title">Source Detail (Auto)</h3>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="txt_source_title">Source Title</label>
						<input type="text" class="form-control keep_me" id="txt_source_title" name="txt_source_title"
						value="{{ Session::get('user.ses_role_name') }}" title="Source Title"
						readonly="readonly" placeholder="Source Title"/>
						<input class="keep_me" type="hidden" name="hdn_source_role" value="{{ Session::get('user.ses_role_lookup_pk_no') }}" readonly="readonly" />
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label for="txt_source_name">Source Name</label>
						<input type="text" class="form-control keep_me" id="txt_source_name" name="txt_source_name"
						value="{{ Session::get('user.ses_full_name') }}" title="Source Name"
						readonly="readonly" placeholder="Source Name"/>
						<input class="keep_me" type="hidden" name="hdn_source_id" value="{{ Session::get('user.ses_user_pk_no') }}" readonly="readonly" />
					</div>
				</div>
				@if($group_id == 73)
				<div class="col-md-4">
					<div class="form-group">
						<label for="txt_source_name">Sub Source Name</label>
						<input type="text" class="form-control" id="sub_source_name" name="sub_source_name"
						value="" title="Source Name" placeholder="Sub Source Name"/>
					</div>
				</div>
				@endif
			</div>
		</div>
	</div>
	@if($group_id != 73)
	@if($group_id == 74)
	<div class="box" style="border-color:#ff851b;">
		<div class="box-header with-border">
			<label class="" style="cursor: pointer;">
				<div class="iradio_flat-green" aria-checked="false" aria-disabled="false" style="position: relative; margin-right:10px; margin-bottom:6px;">
					<input type="radio" id="src_sac" value="DM" name="src_detail" class="flat-red"  style="position: absolute; opacity: 0;">
					<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
				</div>
			</label>
			<h3 class="box-title">Digital Marketing</h3>
		</div>
		<div class="box-body">
			@if(!empty($digital_mkt))
			@foreach ($digital_mkt as $key=>$digi)
			<div class="col-md-2">
				<div class="form-group">
					<label style="cursor:pointer;">
						<div class="icheckbox_minimal-blue" aria-checked="true" aria-disabled="false" style="position: relative;">
							<input type="checkbox" class="minimal" name="chk_digital_mark[]" value="{{ $key }}" style="position: absolute; opacity: 0;">
							<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
						</div>

						<span style="font-size:14px; margin-top:-5px;">
							&nbsp;{{ $digi }}
						</span>
					</label>
				</div>
			</div>
			@endforeach
			@endif
		</div>
	</div>
	@endif

	@if($group_id == 75)
	<div class="box" style="border-color:#ff851b;">
		<div class="box-header with-border">
			<h3 class="box-title">Internal Reference</h3>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label for="txt_emp_id">Emp ID<span class="text-danger"> *</span></label>
						<input type="text" class="form-control required" id="txt_emp_id" name="txt_emp_id" value="" title="Emp ID" placeholder="Employee ID"/>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="txt_emp_name">Name<span class="text-danger"> *</span></label>
						<input type="text" class="form-control required" id="txt_emp_name" name="txt_emp_name" value="" title="Name" placeholder="Employee Name"/>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="txt_emp_position">Position<span class="text-danger"> *</span></label>
						<input type="text" class="form-control required" id="txt_emp_position" name="txt_emp_position" value="" title="Employee Position" placeholder="Position"/>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="txt_contract_no">Contact Number<span class="text-danger"> *</span></label>
						<input type="text" class="form-control required" id="txt_contract_no" name="txt_contract_no" value="" title="Employee Contact Number" placeholder="Contact Number"/>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="box" style="border-color:#ff851b;">
		<div class="box-header with-border">
			<h3 class="box-title">Sales Agent</h3>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="txt_source_title">Agent Group</label>
						<select class="form-control" id="sales_user_group" name="sales_user_group">
							<option value="77">ST - Sales Team</option>
						</select>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label for="txt_source_title">Agent Name</label>
						<select class="form-control" id="sales_user_name" name="sales_user_name">
							<option value="0">ST - Select Agent</option>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endif

	@if($group_id == 119)
	<div class="box" style="border-color:#ff851b;">
		<div class="box-header with-border">
			<label class="" style="cursor: pointer;">
				<div class="iradio_flat-green" aria-checked="false" aria-disabled="false" style="position: relative; margin-right:10px; margin-bottom:6px;">
					<input type="radio" id="src_sac" value="DM" name="src_detail" class="flat-red"  style="position: absolute; opacity: 0;">
					<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
				</div>
			</label>
			<h3 class="box-title">SAC</h3>
		</div>
		<div class="box-body">
			<div class="col-md-4">
				<div class="form-group">
					<label for="txt_src_name">Name</label>
					<input type="email" class="form-control" id="txt_src_name" name="txt_sac_name" value="" title="Source Name" placeholder="Name"/>
				</div>
			</div>
			<div class="col-md-8">
				<div class="form-group">
					<label for="txt_src_note">Note</label>
					<textarea class="form-control" rows="5" id="txt_src_note" name="txt_sac_note" title="Note" placeholder="Write Note ..."></textarea>
				</div>
			</div>
		</div>
	</div>
	@endif

	@if($group_id == 76)
	<div class="box box-info">
		<div class="box-header with-border">
			<label class="" style="cursor: pointer;">
				<span style="font-size:18px; margin-top:-5px;">Hotline <small style="font-weight: normal !important;">(Select any one option from the following Dropdown Items)</small></span>
			</label>
		</div>
		<div class="box-body">
			<div class="col-md-4">
				<div class="form-group">
					<label>Hotline</label>
					<select class="form-control select2 hotline_dropdown" name="hotline[]" style="width: 100%;" aria-hidden="true">
						<option selected="selected" value="0">Select Hotline</option>
						@if(!empty($hotline))
						@foreach ($hotline as $key => $hline)
						<option value="{{ $hline }}">{{ $hline }}</option>
						@endforeach
						@endif
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Press Ad. </label>
					<select class="form-control select2 hotline_dropdown" name="hotline[]" style="width: 100%;" aria-hidden="true">
						<option selected="selected" value="0">Select Press Ad.</option>
						@if(!empty($press_adds))
						@foreach ($press_adds as $key => $press_add)
						<option value="{{ $press_add }}">{{ $press_add }}</option>
						@endforeach
						@endif
					</select>
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label>Billboard </label>
					<select class="form-control select2 hotline_dropdown" name="hotline[]" style="width: 100%;" aria-hidden="true">
						<option selected="selected" value="0">Select Billboard</option>
						@if(!empty($billboards))
						@foreach ($billboards as $key => $billboard)
						<option value="{{ $billboard }}">{{ $billboard }}</option>
						@endforeach
						@endif
					</select>
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label>Project Board </label>
					<select class="form-control select2 hotline_dropdown" name="hotline[]" style="width: 100%;" aria-hidden="true">
						<option selected="selected" value="0">Select Project Board</option>
						@if(!empty($project_boards))
						@foreach ($project_boards as $key => $project_board)
						<option value="{{ $project_board }}">{{ $project_board }}</option>
						@endforeach
						@endif
					</select>
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label>Flyer </label>
					<select class="form-control select2 hotline_dropdown" name="hotline[]" style="width: 100%;" aria-hidden="true">
						<option selected="selected" value="0">Select Flyer</option>
						@if(!empty($flyers))
						@foreach ($flyers as $key => $flyer)
						<option value="{{ $flyer }}">{{ $flyer }}</option>
						@endforeach
						@endif
					</select>
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label>FNF </label>
					<select class="form-control select2 hotline_dropdown" name="hotline[]" style="width: 100%;" aria-hidden="true">
						<option selected="selected" value="0">Select Existing Customer Name</option>
						@if(!empty($fnfs))
						@foreach ($fnfs as $key => $fnf)
						<option value="{{ $fnf }}">{{ $fnf }}</option>
						@endforeach
						@endif
					</select>
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label for="txt_other">Others </label>
					<input type="text" class="form-control hotline_text" id="hotline" name="hotline[]" value="" title="Others" placeholder="Others"/>
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label for="sale_agent">Sales Event </label>
					<input type="text" class="form-control hotline_text" id="hotline" name="hotline[]" value="" title="Sales Event" placeholder="Sales Event"/>
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label for="txt_cust_engagment">Customer Engagement </label>
					<input type="text" class="form-control hotline_text" id="hotline" name="hotline[]" value="" title="Customer Engagement" placeholder="Customer Engagement"/>
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label for="txt_fair">Fair </label>
					<input type="text" class="form-control hotline_text" id="hotline" name="hotline[]" value="" title="Fair" placeholder="Input Fair Name"/>
				</div>
			</div>
		</div>
	</div>
	@endif
	@endif
	<div class="box" style="border-color:#444444;">
		<div class="box-header with-border  text-center">

			<label class="" style="cursor: pointer;">
				<input type="checkbox" id="chkKyc" name="chkKyc" /> More Details (KYC)
			</label>
		</div>

		<div id="more_details" class="box-body hidden">
			<div class="row" id="appendPlace">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="cust_dob">Customer DOB</label>
								<input type="text" class="form-control datepicker" id="txt_cust_dob" name="txt_cust_dob" value="<?php echo date('d-m-Y'); ?>" title="" readonly="readonly" placeholder=""/>
							</div>

							<div class="form-group">
								<label for="wife_name">Wife Name</label>
								<input type="text" class="form-control" id="txt_wife_name" name="txt_wife_name" value="" title="Source Title" placeholder="Wife Name"/>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="marriage_anniversary">Marriage Anniversary</label>
								<input type="text" class="form-control datepicker" id="txt_marriage_anniversary" name="txt_marriage_anniversary" value="<?php echo date('d-m-Y'); ?>" title="" readonly="readonly" placeholder=""/>
							</div>

							<div class="form-group">
								<label for="wife_dob">Wife DOB</label>
								<input type="text" class="form-control datepicker" id="txt_wife_dob" name="txt_wife_dob" value="<?php echo date('d-m-Y'); ?>" title="" readonly="readonly" placeholder=""/>
							</div>
						</div>
						<br clear="all" /><br />
						<div class="col-md-6">
							<div class="form-group">
								<label for="marriage_anniversary">Children Name</label>
								<input type="text" class="form-control" id="txt_child_name_1" name="txt_child_name_1" value="" title="Source Title" placeholder="First Children Name"/>
							</div>

							<div class="form-group">
								<input type="text" class="form-control" id="txt_child_name_2" name="txt_child_name_2" value="" title="Source Title" placeholder="Second Children Name"/>
							</div>
							<div class="form-group">
								<input type="text" class="form-control" id="txt_child_name_3" name="txt_child_name_3" value="" title="Source Title" placeholder="Third Children Name"/>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="marriage_anniversary">Children DOB</label>
								<input type="text" class="form-control datepicker" id="txt_child_dob_1" name="txt_child_dob_1" value="<?php echo date('d-m-Y'); ?>" title="" readonly="readonly" placeholder=""/>
							</div>

							<div class="form-group">
								<input type="text" class="form-control datepicker" id="txt_child_dob_2" name="txt_child_dob_2" value="<?php echo date('d-m-Y'); ?>" title="" readonly="readonly" placeholder=""/>
							</div>
							<div class="form-group">
								<input type="text" class="form-control datepicker" id="txt_child_dob_3" name="txt_child_dob_3" value="<?php echo date('d-m-Y'); ?>" title="" readonly="readonly" placeholder=""/>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<label for="remarks">Remarks</label>
			<div class="form-group">
				<textarea class="form-control" style="height: 100px !important;" id="remarks" name="remarks" placeholder="Enter Remarks"></textarea>
			</div>
		</div>
		<br />
		<div class="row mt-50">
			<div class="col-md-12">
				<div class="col-md-12 pb-15">
					<button type="submit" class="btn bg-green btn-sm btnSaveUpdate">Save</button>
					<a href="#" class="btn bg-red btn-sm">Cancel</a>
				</div>
			</div>
		</div>
	</div>
</form>