<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

Route::get('/', function () {
    return redirect('/admin/login');
});
Route::get('/home', function () {
    return redirect('/admin/dashboard');
});
Route::group(['prefix' => 'admin'], function () {
    Auth::routes();
});

Route::get('/admin', function () {
    return redirect('/admin/dashboard');
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth', 'namespace' => 'Admin'], function () {
    Route::get('dashboard/{switch_user?}', 'DashboardController@index')->name('admin.dashboard');
    Route::get('performance_chart_data/user_id/{user_id}/type/{type}', 'DashboardController@performance_chart_data')->name('performance_chart_data');
    Route::resource('customer', 'CustomerController');
    Route::resource('category', 'CategoryController');
    Route::resource('attribute', 'AttributeController');
    Route::resource('supplier', 'SupplierController');

    Route::post('load_users', 'UserController@load_users')->name('load_users');
    Route::resource('user', 'UserController');

    Route::post('lookup_list', 'SettingsController@load_list')->name('lookup_list');
    Route::get('project_wise_flat', 'SettingsController@project_wise_flat')->name('project_wise_flat');
    Route::get('create_project_wise_flat', 'SettingsController@create_project_wise_flat')->name('create_project_wise_flat');
    Route::post('store_flat_setup', 'SettingsController@store_flat_setup')->name('store_flat_setup');
    Route::get('edit_project_wise_flat/{lead_id}', 'SettingsController@edit_project_wise_flat')->name('edit_project_wise_flat');
    Route::post('delete_project_wise_flat', 'SettingsController@destroy_project_wise_flat')->name('delete_project_wise_flat');
    Route::post('update_flat_setup', 'SettingsController@update_flat_setup')->name('update_flat_setup');
    Route::post('load_project_wise_flat_list', 'SettingsController@load_project_wise_flat_list')->name('load_project_wise_flat_list');
    //New ROute for flitering
    Route::post("getFlatListByType", "SettingsController@getFlatListByType")->name("getFlatListByType");

    Route::get("update_selling_period", "SettingsController@update_selling_period")->name("update_selling_period");
    Route::post("store_update_selling_period", "SettingsController@store_update_selling_period")->name("store_update_selling_period");

    Route::resource('settings', 'SettingsController');

    Route::get('import_csv', 'LeadController@import_csv')->name('import_csv');
    Route::post('store_import_csv', 'LeadController@store_import_csv')->name('store_import_csv');
    Route::get('lead_list/{lead_id}/{transfer_type?}', 'LeadController@lead_list')->name('lead_list');
    Route::get('stage_wise_lead_list/{type}', 'LeadController@stageWiseLeadList')->name('stage_wise_lead_list');
    Route::get('delete_closed_lead/{lead_id}', 'LeadController@deleteClosedLead')->name('delete_closed_lead');
    Route::post('lead_list_followup/{lead_id}/{transfer_type?}', 'LeadController@lead_list')->name('lead_list_followup');
    Route::get('lead_list_view/{lead_id}/{transfer_type?}', 'LeadController@lead_list_view')->name('lead_list_view');
    Route::get('lead_view/{lead_id}', 'LeadController@lead_view')->name('lead_view');
    Route::get('lead_download/{lead_id}', 'LeadController@lead_download')->name('lead_download');

    Route::get('lead_edit/{lead_id}', 'LeadController@lead_edit')->name('lead_edit');
    Route::post('get_team_users', 'LeadController@get_team_users')->name('get_team_users');
    Route::post('load_area_project_size', 'LeadController@load_area_project_size')->name('load_area_project_size');
    Route::post('load_project_size', 'LeadController@load_project_size')->name('load_project_size');
    Route::post('load_sales_agent_by_area', 'LeadController@load_sales_agent_by_area')->name('load_sales_agent_by_area');
    Route::post('check_if_phone_no_exist', 'LeadController@check_if_phone_no_exist')->name('check_if_phone_no_exist');
    // Route::post('check_if_phone_no_exist_hold_stage', 'LeadController@check_if_phone_no_exist_hold_stage')->name('check_if_phone_no_exist_hold_stage');
    Route::resource('lead', 'LeadController');

    Route::get('stage_update/{lead_id}', 'LeadFllowupController@stage_update')->name('stage_update');
    Route::post('store_stage_update', 'LeadFllowupController@store_stage_update')->name('store_stage_update');
    Route::get('lead_sold/{lead_id}', 'LeadFllowupController@lead_sold')->name('lead_sold');
    Route::post('store_lead_sold', 'LeadFllowupController@store_lead_sold')->name('store_lead_sold');
    Route::post('/load_followup_leads', 'LeadFllowupController@load_followup_leads')->name('load_followup_leads');
    Route::get('lead_follow_up_from_dashboard/{lead_id}/{type}', 'LeadFllowupController@lead_follow_up_from_dashboard')->name('lead_follow_up_from_dashboard');
    Route::resource('lead_follow_up', 'LeadFllowupController');

    Route::post('load_team_lead_by_team', 'TeamController@load_team_lead_by_team')->name('load_team_lead_by_team');
    Route::post('load_team_list_by_team', 'TeamController@load_team_list_by_team')->name('load_team_list_by_team');
    Route::post('remove_team', 'TeamController@remove_team')->name('remove_team');
    Route::get('team_target', 'TeamController@team_target')->name('team_target');
    Route::post('get_agent_by_type', 'TeamController@get_agent_by_type')->name('get_agent_by_type');
    Route::post('store_team_target', 'TeamController@store_team_target')->name('store_team_target');
    Route::resource('team', 'TeamController');

    Route::get('rbac', 'RbacController@index')->name('rbac');
    Route::get('rbac_pages/{role_id}', 'RbacController@rbac_pages')->name('rbac_pages');
    Route::get('rbac_assign/{role_id}/{page_id}', 'RbacController@rbac_assign')->name('rbac_assign');

    Route::get('search_engine', 'ReportController@index')->name('search_engine');
    Route::post('export_report', 'ReportController@export_report')->name('export_report');
    Route::post('search_result', 'ReportController@search_result')->name('search_result');

    //profile route
    Route::get('profile', 'ProfilesController@index')->name('profile');
    Route::post('profile/update', 'ProfilesController@update')->name('profile.update');

    Route::get('/lead_qc', 'LeadQcController@index')->name('lead_qc');
    Route::get('/lead_pass_junk', 'LeadQcController@lead_pass_junk')->name('lead_pass_junk');
    Route::get('/lead_bypass', 'LeadQcController@lead_bypass')->name('lead_bypass');
    Route::post('/load_qc_leads', 'LeadQcController@load_qc_leads')->name('load_qc_leads');

    Route::get('/lead_transfer', 'LeadTransferController@index')->name('lead_transfer');
    Route::post('/lead_create_transfer', 'LeadTransferController@lead_create_transfer')->name('lead_create_transfer');
    Route::post('/accept_transfer', 'LeadTransferController@accept_transfer')->name('accept_transfer');
    Route::post('/load_transfer_leads', 'LeadTransferController@load_transfer_leads')->name('load_transfer_leads');

    Route::get('/lead_dist_list', 'LeadDistribution@index')->name('lead_dist_list');
    Route::get('/distribute_lead', 'LeadDistribution@distribute_lead')->name('distribute_lead');
    Route::get('/lead_auto_distribute', 'LeadDistribution@lead_auto_distribute')->name('lead_auto_distribute');
    Route::post('/load_dist_leads', 'LeadDistribution@load_dist_leads')->name('load_dist_leads');

    Route::post('search_result_for_sms_campaign', 'CampaignController@search_result')->name('search_result_for_sms_campaign');

    Route::get("/sms_campaign_list", "CampaignController@sms_campaign_list")->name('sms_campaign_list');
    Route::get("/email_campaign_list", "CampaignController@email_campaign_list")->name('email_campaign_list');

    Route::get('/send-SMS', 'CampaignController@sendSMS')->name('send-SMS');
    Route::get('/send-email', 'CampaignController@sendEmail')->name('send-email');
    Route::post('/send-sms-to-gateway', 'CampaignController@sendSmsToGateway')->name('send-sms-to-gateway');
    Route::post('/send-email-to-gateway', 'CampaignController@campaignEmailList')->name('send-email-to-gateway');

    Route::resource("campaign", "CampaignController");

    //Settings Report
    Route::get("/year_to_year_setup", "SettingsController@year_to_year_setup")->name('year_to_year_setup');
    Route::get("/create_year_to_year_setup", "SettingsController@create_year_to_year_setup")->name('create_year_to_year_setup');

    Route::get('/report_setup', 'SettingsController@report_setup')->name('report_setup');
    Route::post('load_team_list_by_team_report', 'SettingsController@load_team_list_by_team')->name('load_team_list_by_team_report');

    Route::post("/store_year_setup", "SettingsController@store_year_setup")->name('store_year_setup');
    Route::get("/edit_year_setup/{id?}", "SettingsController@edit_year_setup")->name('edit_year_setup');

    Route::post("/update_year_setup", "SettingsController@update_year_setup")->name('update_year_setup');
    Route::post('load_ch_by_team', 'SettingsController@load_ch_by_team')->name('load_ch_by_team');

    Route::post('store_report_target', 'SettingsController@store_report_target')->name('store_report_target');

    //Conversion Report
    Route::get("/conversion_report", "ReportController@conversion_report")->name('conversion_report');
    Route::post("/conversion_report_result", "ReportController@conversion_report_result")->name('conversion_report_result');
    //Sales Report
    Route::get("/sales_report", "ReportController@sales_report")->name('sales_report');
    Route::post("/conversion_report_result", "ReportController@conversion_report_result")->name('conversion_report_result');
    //OBM Conversion Report
    Route::get("/obm_conversion_report", "ReportController@obm_conversion_report")->name('obm_conversion_report');
    Route::post("/obm_conversion_report_result", "ReportController@obm_conversion_report_result")->name('obm_conversion_report_result');

    Route::get("/cancel_sale/{lead_pk_no}", "LeadController@cancel_sale")->name('cancel_sale');

    Route::get('/brokarage_report_setup', 'SettingsController@brokarage_report_setup')->name('brokarage_report_setup');
    Route::post('load_team_list_by_ch', 'SettingsController@load_team_list_by_ch')->name('load_team_list_by_ch');

    Route::post('store_brokarage_setup', 'SettingsController@store_brokarage_setup')->name('store_brokarage_setup');
    //Sales report A
    Route::get('/target_sales_a', "TargetSetupController@target_sales_a")->name('target_sales_a');
    Route::post('load_team_member_by_team_for_sales_a', 'TargetSetupController@load_team_member_by_team_for_sales_a')->name('load_team_member_by_team_for_sales_a');
    Route::post('/store_target_sales_a', "TargetSetupController@store_target_sales_a")->name('store_target_sales_a');

    Route::get("/sales_report_a", "ReportController@sales_report_a")->name('sales_report_a');
    Route::post("/sales_report_a_result", "ReportController@sales_report_a_result")->name('sales_report_a_result');

    Route::get("/sfs", "ReportController@sfs")->name('sfs');
    Route::post("/sfs_result", "ReportController@sfs_result")->name('sfs_result');

    Route::get("/target_bd_land", "TargetSetupController@target_bd_land")->name('target_bd_land');
    Route::post('load_team_member_by_bd_land', 'TargetSetupController@load_team_member_by_bd_land')->name('load_team_member_by_bd_land');

    Route::post("/store_bd_land", "TargetSetupController@store_bd_land")->name('store_bd_land');

    Route::get("/bd_land", "ReportController@bd_land")->name('bd_land');
    Route::post("/bd_land_result", "ReportController@bd_land_result")->name('bd_land_result');

    Route::get("/hollow_block_sales", "ReportController@hollow_block_sales")->name('hollow_block_sales');
    Route::post("/hollow_block_sales_result", "ReportController@hollow_block_sales_result")->name('hollow_block_sales_result');

    Route::get('/target_hot_performance', "TargetSetupController@target_hot_performance")->name("target_hot_performance");
    Route::post('load_hot', 'TargetSetupController@load_hot')->name('load_hot');



    Route::get('/target_chs_performance', "TargetSetupController@target_chs_performance")->name("target_chs_performance");
    Route::post('load_chs', 'TargetSetupController@load_chs')->name('load_chs');

    Route::get("/k_classification", "ReportController@k_classification")->name('k_classification');
    Route::post("/k_classification_result", "ReportController@k_classification_result")->name('k_classification_result');

    Route::get("/project_wise_report", "ReportController@project_wise_report")->name('project_wise_report');
    Route::post("/project_wise_report_result", "ReportController@project_wise_report_result")->name('project_wise_report_result');

    Route::get("/call_center_report", "ReportController@call_center_report")->name('call_center_report');
    Route::post("/call_center_report_result", "ReportController@call_center_report_result")->name('project_wise_report_result');

    //Brokerage Inventory
    Route::get("/brokerage_inventory_report", "ReportController@brokerage_inventory_report")->name('brokerage_inventory_report');
    Route::post("/brokerage_inventory_report_result", "ReportController@brokerage_inventory_report_result")->name('brokerage_inventory_report_result');

    //Brokerage Sales
    Route::get("/brokerage_sales_report", "ReportController@brokerage_sales_report")->name('brokerage_sales_report');
    Route::post("/brokerage_sales_report_result", "ReportController@brokerage_sales_report_result")->name('brokerage_sales_report_result');

    Route::get("/import_csv_report_setup", "ImportCSVController@import_csv_report_setup")->name('import_csv_report_setup');
    Route::post("/store_import_csv_report_setup", "ImportCSVController@store_import_csv_report_setup")->name('store_import_csv_report_setup');

    Route::get("/import_csv_target_setup", "ImportCSVController@import_csv_target_setup")->name('import_csv_target_setup');
    Route::post("/store_import_csv_target_setup", "ImportCSVController@store_import_csv_target_setup")->name('store_import_csv_target_setup');

    Route::get("/import_csv_flat_setup", "ImportCSVController@import_csv_flat_setup")->name('import_csv_flat_setup');
    Route::post("/store_import_csv_flat_setup", "ImportCSVController@store_import_csv_flat_setup")->name('store_import_csv_flat_setup');
    //Sales Summary
    Route::get("/sales_summary", "ReportController@sales_summary")->name('sales_summary');
    Route::post("/sales_summary_result", "ReportController@sales_summary_result")->name('sales_summary_result');

    Route::get("/hot_performance_report", "ReportController@hot_performance")->name('hot_performance');
    Route::post("/hot_performance_result", "ReportController@hot_performance_result")->name('hot_performance_result');

    Route::post("get_type_wise_catgeory", "ReportController@get_type_wise_catgeory")->name("get_type_wise_catgeory");

    //Sac Lead
    Route::get('bd_sac_lead','ReportController@bd_sac_lead')->name('bd_sac_lead');
    Route::post('bd_sac_lead_result','ReportController@bd_sac_lead_result')->name('bd_sac_lead_result');
    //ACD Report
    Route::get('acd_report','ReportController@acd_report')->name('acd_report');
    Route::post('acd_report_result','ReportController@acd_report_result')->name('acd_report_result');

    Route::get('source_wise_report','ReportController@source_wise_report')->name('source_wise_report');
    Route::post('source_wise_report_result','ReportController@source_wise_report_result')->name('source_wise_report_result');

    Route::get('lead_hold_list','LeadController@lead_hold_list')->name('lead_hold_list');
    Route::post('lead_transfer_by_cre','LeadController@lead_transfer_by_cre')->name('lead_transfer_by_cre');
    Route::post('lead_closed_by_cre','LeadController@lead_closed_by_cre')->name('lead_closed_by_cre');
    Route::get('lead_hold_list_table','LeadController@lead_hold_list_table')->name('lead_hold_list_table');

    Route::get('/sold_lead_migration','LeadController@sold_lead_migration');
});
