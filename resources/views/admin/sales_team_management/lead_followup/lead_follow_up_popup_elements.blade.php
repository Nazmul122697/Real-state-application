<div class="col-md-6">
	<div class="form-group">
		<label for="lead_id">Lead ID :</label>
		<input type="text" class="form-control keep_me" id="lead_id" name="lead_id"
		value="{{ isset($lead_data)? $lead_data->lead_id:'' }}" title="" readonly="readonly" readonly="readonly"
		placeholder=""/>
		<input type="hidden" class="keep_me" name="lead_pk_no" value="{{ isset($lead_data)? $lead_data->lead_pk_no:'' }}" readonly="readonly"/>
		<input type="hidden" class="keep_me" name="leadlifecycle_id" value="{{ isset($lead_data)? $lead_data->leadlifecycle_pk_no:'' }}" readonly="readonly"/>
	</div>
</div>

<div class="col-md-6">
	<div class="form-group">
		<label for="customer">Customer :</label>
		<input type="text" class="form-control keep_me" id="customer" name="customer"
		value="{{ isset($lead_data)? $lead_data->customer_firstname .' '. $lead_data->customer_lastname:'' }}" readonly="readonly" placeholder="Customer"/>
		<input type="hidden" class="keep_me" name="leadlifecycle_id" value="{{ isset($lead_data)? $lead_data->leadlifecycle_pk_no:'' }}" readonly="readonly"/>
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
		<label for="customer_mobile">Mobile :</label>
		<input type="text" class="form-control keep_me" id="customer_mobile" name="customer_mobile"
		value="{{ isset($lead_data)? $lead_data->phone1_code .''. $lead_data->phone1:'' }}" readonly="readonly" />
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
		<label for="customer_email">Email :</label>
		<input type="text" class="form-control keep_me" id="customer" name="customer_email"
		value="{{ isset($lead_data)? $lead_data->email_id:'' }}" readonly="readonly"/>
	</div>
</div>

<div class="col-md-6">
	<div class="form-group">
		<label for="lead_category">Category :</label>
		<input type="text" class="form-control keep_me" id="lead_category" name="lead_category" value="{{ isset($lead_data)? $lead_data->project_category_name:'' }}" title="" readonly="readonly" placeholder="Category"/>
		<input type="hidden" class="keep_me" name="lead_category_id" value="{{ isset($lead_data)? $lead_data->project_category_pk_no:'' }}"  readonly="readonly"/>
		<input type="hidden" class="keep_me" name="lead_project_id" value="{{ isset($lead_data)? $lead_data->Project_pk_no:'' }}"  readonly="readonly"/>
		<input type="hidden" class="keep_me" name="lead_size_id" value="{{ isset($lead_data)? $lead_data->project_size_pk_no:'' }}"  readonly="readonly"/>
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
		<label for="project_name">Project :</label>
		<input type="text" class="form-control keep_me" id="project_name" name="project_name"
		value="{{ isset($lead_data)? $lead_data->project_name:'' }}" title="" readonly="readonly"
		placeholder="Project"/>
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
		<label for="area">Area :</label>
		<input type="text" class="form-control keep_me" id="area" name="area"
		value="{{ isset($lead_data)? $lead_data->project_area:'' }}" title="" readonly="readonly"
		placeholder="Area"/>
		<input type="hidden" class="keep_me" name="lead_area_id" value="{{ isset($lead_data)? $lead_data->project_area_pk_no:'' }}"
		readonly="readonly"/>
	</div>
</div>

<div class="col-md-6">
	<div class="form-group">
		<label for="sales_agent">Sales Agent :</label>
		<input type="text" class="form-control keep_me" id="sales_agent" name="sales_agent"
		value="{{ isset($lead_data)? $lead_data->lead_sales_agent_name:'' }}" title="" readonly="readonly"
		placeholder="Sales Agent"/>
		<input type="hidden" class="keep_me" name="sales_agent_id" value="{{ isset($lead_data)? $lead_data->lead_sales_agent_pk_no:'' }}"
		readonly="readonly"/>
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
		<label for="source_name">Source Name :</label>
		<input type="text" class="form-control keep_me" id="source_name" name="source_name"
		value="{{ isset($lead_data)? $lead_data->user_full_name:'' }}" readonly="readonly" />
	</div>
</div>
