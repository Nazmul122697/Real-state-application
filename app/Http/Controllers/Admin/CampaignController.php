<?php

namespace App\Http\Controllers\Admin;

use App\Campaign;
use App\LookupData;
use App\EmailCampaign;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $user_groups = LookupData::where('lookup_type', 1)->orderBy('lookup_name')->get();
        $user_group = [];
        if (!empty($user_groups)) {
            foreach ($user_groups as $group) {
                $user_group[$group->lookup_pk_no] = $group->lookup_name;
            }
        }

        $lookup_arr = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
        $lead_stage_arr = config('static_arrays.lead_stage_arr');
        $lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->orderBy('lookup_name')->get();

        $project_cat = $project_area = $project_name = $project_size = $hotline = $billboards = $project_boards = $flyers = $fnfs = array();
        foreach ($lookup_data as $key => $value) {
            if ($value->lookup_type == 2)
                $digital_mkt[$value->lookup_pk_no] = $value->lookup_name;

            if ($value->lookup_type == 3)
                $hotline[$value->lookup_pk_no] = $value->lookup_name;

            if ($value->lookup_type == 4)
                $project_cat[$value->lookup_pk_no] = $value->lookup_name;

            if ($value->lookup_type == 5)
                $project_area[$value->lookup_pk_no] = $value->lookup_name;

            if ($value->lookup_type == 6)
                $project_name[$value->lookup_pk_no] = $value->lookup_name;

            if ($value->lookup_type == 7)
                $project_size[$value->lookup_pk_no] = $value->lookup_name;

            if ($value->lookup_type == 10)
                $ocupations[$value->lookup_pk_no] = $value->lookup_name;

            if ($value->lookup_type == 11)
                $press_adds[$value->lookup_pk_no] = $value->lookup_name;

            if ($value->lookup_type == 12)
                $billboards[$value->lookup_pk_no] = $value->lookup_name;

            if ($value->lookup_type == 13)
                $project_boards[$value->lookup_pk_no] = $value->lookup_name;

            if ($value->lookup_type == 14)
                $flyers[$value->lookup_pk_no] = $value->lookup_name;

            if ($value->lookup_type == 15)
                $fnfs[$value->lookup_pk_no] = $value->lookup_name;
        }
        return view('admin.campaign.sms_campaign_setup', compact('project_cat', 'project_area', 'project_name', 'project_size', 'hotline', 'ocupations', 'digital_mkt', 'press_adds', 'billboards', 'project_boards', 'flyers', 'fnfs', 'lead_stage_arr', 'user_group'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $lead_data = $this->serch_result_query($request);
        if (!empty($lead_data)) {
            foreach ($lead_data as $row) {
                $smsCampaign = new Campaign();
                $smsCampaign->customer_name = $row->customer_firstname . " " . $row->customer_lastname;
                $smsCampaign->mobile_no1 = $row->phone1;
                $smsCampaign->mobile_no2 = $row->phone2;
                $smsCampaign->sms_flag = 0;
                $smsCampaign->campaign_name = $request->hdn_sms_campaign_name;
                $smsCampaign->save();
                $emailCampaign = new EmailCampaign();
                $emailCampaign->customer_name = $row->customer_firstname . " " . $row->customer_lastname;
                $emailCampaign->email_id = $row->email_id;
                $emailCampaign->project_name=$row->project_name;
                $emailCampaign->lead_id=$row->lead_id;
                $emailCampaign->email_flag = 0;
                $emailCampaign->campaign_name = $request->hdn_email_campaign_name;
                $emailCampaign->save();
            }
        }
        return response()->json(['message' => 'Campaign Record created successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\campaign $campaign
     * @return \Illuminate\Http\Response
     */
    public function show(campaign $campaign)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\campaign $campaign
     * @return \Illuminate\Http\Response
     */
    public function edit(campaign $campaign)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\campaign $campaign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, campaign $campaign)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\campaign $campaign
     * @return \Illuminate\Http\Response
     */
    public function destroy(campaign $campaign)
    {
        //
    }


    function serch_result_query($request)
    {
//        dd($request->all());
        $sql_cond = (trim($request->txt_customer_name) != "") ? " where customer_firstname like '%" . trim($request->txt_customer_name) . "%'" : "";
        $clause = ($sql_cond != "") ? " and" : " where";
        $sql_cond .= (trim($request->txt_mobile_no) != "") ? " $clause (phone1 like '%" . trim($request->txt_mobile_no) . "%' or phone2 like '%" . trim($request->txt_mobile_no) . "%')" : "";
        $clause = ($sql_cond != "") ? " and" : " where";
        $sql_cond .= (trim($request->txt_email) != "") ? " $clause email_id like '%" . trim($request->txt_email) . "%'" : "";
        $clause = ($sql_cond != "") ? " and" : " where";
        $sql_cond .= ($request->cmbOccupation != "") ? " $clause occupation_pk_no=$request->cmbOccupation" : "";
        $clause = ($sql_cond != "") ? " and" : " where";
        $sql_cond .= ($request->cmbOrganization != "") ? " $clause organization_pk_no=$request->cmbOrganization" : "";
        $clause = ($sql_cond != "") ? " and" : " where";
        $ct_array = $request->cmbCategory;
//        dd($ct_array);
        $sql_cond .= ($request->cmbCategory != "") ? " $clause project_category_pk_no in (" . implode(',', $ct_array) . ")" : "";
        $clause = ($sql_cond != "") ? " and" : " where";
        $ar_array = $request->cmbArea;
        $sql_cond .= ($request->cmbArea != "") ? " $clause project_area_pk_no in (" . implode(',', $ar_array) . ")" : "";
        $clause = ($sql_cond != "") ? " and" : " where";
        $pr_array = $request->cmbProject;
        $sql_cond .= ($request->cmbProjectName != "") ? " $clause Project_pk_no in (" . implode(',', $pr_array) . ")" : "";
        $clause = ($sql_cond != "") ? " and" : " where";
        $sql_cond .= ($request->cmbSize != "") ? " $clause project_size_pk_no=$request->cmbSize" : "";

        $clause = ($sql_cond != "") ? " and" : " where";
        $sql_cond .= ($request->cmbUserGroup > 0) ? " $clause source_auto_usergroup_pk_no=$request->cmbUserGroup" : "";

        $clause = ($sql_cond != "") ? " and" : " where";
        $st_array = $request->cmb_stage;
        $sql_cond .= ($request->cmb_stage > 0) ? " $clause lead_current_stage in (" . implode(',', $st_array) . ")" : "";

        $clause = ($sql_cond != "") ? " and" : " where";
        $entry_date = (trim($request->txt_entry_date) != "") ? date("Y-m-d", strtotime($request->txt_entry_date)) : "";
        $entry_date_to = (trim($request->txt_entry_date_to) != "") ? date("Y-m-d", strtotime($request->txt_entry_date_to)) : "";
        $sql_cond .= ($entry_date != "") ? " $clause a.created_at >='$entry_date' and a.created_at <='$entry_date_to'" : "";

        $clause = ($sql_cond != "") ? " and" : " where";
        $txt_cus_dob_from = (trim($request->txt_cus_dob_from) != "") ? date("Y-m-d", strtotime($request->txt_cus_dob_from)) : "";
        $txt_cus_dob_to = (trim($request->txt_cus_dob_to) != "") ? date("Y-m-d", strtotime($request->txt_cus_dob_to)) : "";
        $sql_cond .= ($request->txt_cus_dob_from != "") ? " $clause Customer_dateofbirth between '$txt_cus_dob_from' and '$txt_cus_dob_to'" : "";

        $clause = ($sql_cond != "") ? " and" : " where";
        $txt_mar_date_from = (trim($request->txt_mar_date_from) != "") ? date("Y-m-d", strtotime($request->txt_mar_date_from)) : "";
        $txt_mar_date_to = (trim($request->txt_mar_date_to) != "") ? date("Y-m-d", strtotime($request->txt_mar_date_to)) : "";
        $sql_cond .= ($request->txt_cus_dob_from != "") ? " $clause Marriage_anniversary between '$txt_mar_date_from' and '$txt_mar_date_to'" : "";

        $clause = ($sql_cond != "") ? " and" : " where";
        $txt_cus_wife_dob_from = (trim($request->txt_cus_wife_dob_from) != "") ? date("Y-m-d", strtotime($request->txt_cus_wife_dob_from)) : "";
        $txt_cus_wife_dob_to = (trim($request->txt_cus_wife_dob_to) != "") ? date("Y-m-d", strtotime($request->txt_cus_wife_dob_to)) : "";
        $sql_cond .= ($txt_cus_wife_dob_from != "") ? " $clause customer_wife_dataofbirth between '$txt_cus_wife_dob_from' and '$txt_cus_wife_dob_to'" : "";

        $clause = ($sql_cond != "") ? " and" : " where";
        $txt_cus_child_dob_from = (trim($request->txt_cus_child_dob_from) != "") ? date("Y-m-d", strtotime($request->txt_cus_child_dob_from)) : "";
        $txt_cus_child_dob_to = (trim($request->txt_cus_child_dob_to) != "") ? date("Y-m-d", strtotime($request->txt_cus_child_dob_to)) : "";
        $sql_cond .= ($txt_cus_wife_dob_from != "") ? " $clause (children_dateofbirth1 between '$txt_cus_child_dob_from' and '$txt_cus_child_dob_to' or children_dateofbirth2 between '$txt_cus_child_dob_from' and '$txt_cus_child_dob_to' or children_dateofbirth3 between '$txt_cus_child_dob_from' and '$txt_cus_child_dob_to')" : "";

        return DB::select("SELECT a.*,c.next_followup_Note
			FROM t_lead2lifecycle_vw a
			LEFT JOIN (SELECT b.lead_pk_no,b.next_followup_Note,MAX(lead_followup_pk_no) AS maxid
			FROM t_leadfollowup b GROUP BY b.lead_pk_no,b.next_followup_Note) AS c
			ON a.lead_pk_no = c.maxid $sql_cond");


    }

    public function search_result(Request $request)
    {
//        dd($request->all());
        $lead_stage_arr = config('static_arrays.lead_stage_arr');
        $lead_data = $this->serch_result_query($request);
        return view('admin.campaign.search_result', compact('lead_data', 'lead_stage_arr'));

    }
    public function sms_campaign_list()
    {
        $sms_list = DB::select("SELECT SUM(a.sms_flag_0) AS remain_sms, SUM(a.sms_flag_1) AS
                send_sms,SUM(a.sms_flag_0)+ SUM(a.sms_flag_1) AS total, a.campaign_name FROM (SELECT
                COUNT(sms_flag) sms_flag_0, 0 sms_flag_1, campaign_name
                FROM sms_campaign WHERE sms_flag=0 GROUP BY  campaign_name
                UNION
                SELECT 0 sms_flag_0,
                COUNT(sms_flag) sms_flag_1, campaign_name
                FROM sms_campaign WHERE sms_flag=1 GROUP BY  campaign_name) a GROUP BY campaign_name");

        return view("admin.campaign.sms_campaign_list",compact("sms_list"));
    }

    public function email_campaign_list()
    {
        $email_list = DB::select("SELECT SUM(a.email_flag_0) AS remain_email, SUM(a.email_flag_1) AS
                send_email,SUM(a.email_flag_0)+ SUM(a.email_flag_1) AS total, a.campaign_name FROM (SELECT
                COUNT(email_flag) email_flag_0, 0 email_flag_1, campaign_name
                FROM email_campaign WHERE email_flag=0 GROUP BY  campaign_name
                UNION
                SELECT 0 email_flag_0,
                COUNT(email_flag) email_flag_1, campaign_name
                FROM email_campaign WHERE email_flag=1 GROUP BY  campaign_name) a GROUP BY campaign_name");

        return view("admin.campaign.email_campaign_list",compact("email_list"));
    }

    public function sendSMS()
    {
        $campaign = Campaign::groupBy("campaign_name")->get(['campaign_name']);
        return view("admin.campaign.send_sms",compact("campaign"));
    }

    public function sendEmail()
    {
        $campaign = EmailCampaign::groupBy("campaign_name")->get(['campaign_name']);
        return view("admin.campaign.send-email",compact("campaign"));
    }



    public function sendSmsToGateway(Request $request) {
        ini_set('max_execution_time', 0);
        $mobile = [];
        $mobiles = Campaign::where("campaign_name", $request->campaign_name)
                            ->where('sms_flag',0)
                            ->skip(0)
                            ->take(1000)
                            ->get();

        foreach($mobiles as $number) {
            $mobile[] = $number->mobile_no1;
        }

        $mobilelist = "0".implode(",0", $mobile);
        // foreach(['1716187302','1718099037','1687893691'] as $number) {
        //     $mobile[] = $number;
        // }

        // $mobilelist = "0".implode(",0", $mobile);

        // $client = new Client([
        //     'headers' => [ 'Content-Type' => 'application/json', 'Accept' => 'application/json' ]
        // ]);

        // $response = $client->post('https://portal.adnsms.com/api/v1/secure/send-sms',
        //     ['body' => json_encode(
        //         [
        //             "api_key" => "KEY-lsqfeg38hu8uvhy416no023ifcxlhsdw",
        //             "api_secret" => "4GJfNI9@SzB!KMhD",
        //             "request_type" => "GENERAL_CAMPAIGN",
        //             "message_type" => "TEXT",
        //             "mobile" => "01716187302,01718099037",
        //             "message_body" => $request->message,
        //             "campaign_title" => "ABC"
        //         ]
        //     )]
        // );

        $url = "https://portal.adnsms.com/api/v1/secure/send-sms";
        $data = [
            "api_key" => "KEY-lsqfeg38hu8uvhy416no023ifcxlhsdw",
            "api_secret" => "4GJfNI9@SzB!KMhD",
            "request_type" => "GENERAL_CAMPAIGN",
            "message_type" => "UNICODE",
            "mobile" => $mobilelist,
            "message_body" => $request->write_massage,
            "campaign_title" => $request->campaign_name
        ];

        $ch = \curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);


        $mobiles = Campaign::where("campaign_name", $request->campaign_name)
                            ->whereIn('mobile_no1',$mobile)
                            ->update(['sms_flag' => 1]);
        $total_mobile = count($mobile);
        return redirect('admin/send-SMS')->with('success',"$total_mobile Message sent successfully");//$response;
    }


    public function campaignEmailList(Request $request) {
        ini_set('max_execution_time', 0);
        //$email = [];
        $emails = EmailCampaign::where("campaign_name", $request->campaign_name)
                                ->where('email_flag',0)
                                ->get();

        if ($emails->isEmpty()) {
            return redirect('admin/send-email')->with('success',"Eamil list empty");
        }


	     //$email[] = $address->email_id;
        //https://portal.adnemail.com/api/v1/lists?api_token=oau2XuK9uK96oK0RenWsUYs6fPYPUhEn436DarxecU4YsxEjJrPGQygag4zv&name=btibd-mail.org&from_email=support@btibd-mail.org&from_name=0
        //	&default_subject=0&divider=0&contact[email]=moinuddin7@gmail.com&contact[company]=0&contact[state]=0&contact[address_1]=0&contact[address_2]=0&
        //	contact[city]=0&contact[zip]=0&contact[phone]=0&contact[country_id]=0&contact[url]=http://btibd-mail.org


        //$url = "https://portal.adnemail.com/api/v1/lists?api_token="."oau2XuK9uK96oK0RenWsUYs6fPYPUhEn436DarxecU4YsxEjJrPGQygag4zv";
        $url = "https://portal.adnemail.com/api/v1/lists?api_token="."oau2XuK9uK96oK0RenWsUYs6fPYPUhEn436DarxecU4YsxEjJrPGQygag4zv";


        $data = [
                'name' => $emails[0]->campaign_name,
                'from_email' => 'info@btibd.org',
                'from_name' => 'TEST EMAIL',
                'default_subject' => 'Hello',
                'contact' => [
                    'company' => 'Bti',
                    'state' => 'BD',
                    'address_1' => 'bti Celebration Point, Plot 3 & 5, Road: 113/A, Gulshan, Dhaka-1212',
                    'address_2' => 'bti Landmark, Plot # 549/646, Wireless Moor, Zakir Hossain Road, West Khulshi, Chattogram',
                    'city' => 'Dhaka',
                    'zip' => '1212',
                    'phone' => 'For Sales: 16604 or +880 9613-191919',
                    'country_id' => '1',
                    'email' => 'info@btibd.org',
                    'url' => 'https://www.btibd.com/',
                ],
                'subscribe_confirmation' => '0',
                'send_welcome_email' => '0',
                'unsubscribe_notification' => '0',
        ];


            $ch = \curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, urldecode(http_build_query($data)));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);

        $res_data = json_decode($response,true);
//for additional field
        $url = "https://portal.adnemail.com/api/v1/lists/".$res_data['list_uid']."/add-field?api_token=oau2XuK9uK96oK0RenWsUYs6fPYPUhEn436DarxecU4YsxEjJrPGQygag4zv&type=text&label=Lead_id&tag=LEAD_ID&default_value=";

        $ch = \curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        $url = "https://portal.adnemail.com/api/v1/lists/".$res_data['list_uid']."/add-field?api_token=oau2XuK9uK96oK0RenWsUYs6fPYPUhEn436DarxecU4YsxEjJrPGQygag4zv&type=text&label=Project_name&tag=PROJECT_NAME&default_value=";

        $ch = \curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        $url = "https://portal.adnemail.com/api/v1/lists/".$res_data['list_uid']."/add-field?api_token=oau2XuK9uK96oK0RenWsUYs6fPYPUhEn436DarxecU4YsxEjJrPGQygag4zv&type=text&label=Customer_name&tag=CUSTOMER_NAME&default_value=";

        $ch = \curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        //$emails_update = EmailCampaign::where("email_id", $address->email_id)->first();
            //$emails_update->email_flag = 1;
            //$emails_update->save();


        $email_add = [];
        $emailarr = [];
        foreach($emails as $address) {
            $eamil_add[] = ['EMAIL' => $address->email_id,'LEAD_ID'=> $address->lead_id,'CUSTOMER_NAME'=>$address->customer_name,'PROJECT_NAME'=>$address->project_name];
            $emailarr[] = $address->email_id;
        }

        //$url = 'https://portal.adnemail.com/api/v1/lists/'.$res_data['list_uid'].'/subscribers/store?api_token='."oau2XuK9uK96oK0RenWsUYs6fPYPUhEn436DarxecU4YsxEjJrPGQygag4zv";
        $url = "https://portal.adnemail.com/api/v1/subscribers?list_uid=".$res_data['list_uid']."&api_token=oau2XuK9uK96oK0RenWsUYs6fPYPUhEn436DarxecU4YsxEjJrPGQygag4zv";

        foreach($eamil_add as $address) {

            $ch = \curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, urldecode(http_build_query($address)));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);

        }

        $emaillist = EmailCampaign::where("campaign_name", $request->campaign_name)
                            ->whereIn('email_id',$emailarr)
                            ->update(['email_flag' => 1]);

        $total_email = count($emails);

        return redirect('admin/send-email')->with('success',"$total_email email sent successfully");//$response;


        return $response;
        //return response("successfully pushed email address",200);//$response;
            //return ['data' => $email];
        //return response("successfully pushed email address",200);//$response;

    }



}
