<?php

namespace App\Console\Commands;

use App\Campaigns;
use App\CampaignSubscriptionList;
use App\CustomSMSGateways;
use App\Jobs\SendBulkMMS;
use App\Jobs\SendBulkSMS;
use App\Jobs\SendBulkVoice;
use App\SMSGatewayCredential;
use App\SMSGateways;
use App\SenderIdManage;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberToCarrierMapper;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\geocoding\PhoneNumberOfflineGeocoder;
use Illuminate\Console\Command;
use SignalWire\Rest\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Client;
use App\ClientGroups;
use App\ContactList;

class BulkSMSFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:sendbulk';
    static $recentlyUsedId = '';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Bulk SMS From File';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    
    
   public function process($text)
    {
        return preg_replace_callback(
            '/\{(((?>[^\{\}]+)|(?R))*?)\}/x',
            array($this, 'replace'),
            $text
        );
    }

    public function replace($text)
    {
        $text = $this->process($text[1]);
        $parts = explode('|', $text);
        return $parts[array_rand($parts)];
    }
    
    
    public function rotateSenderId($user_id) {

        $all_sender_id = SenderIdManage::where('status', 'unblock')->get();
        $all_ids = [];

        // Log::debug("==============================");

        foreach ($all_sender_id as $sid) {
            $client_array = json_decode($sid->cl_id);

            if (isset($client_array) && is_array($client_array) && in_array('0', $client_array)) {
                array_push($all_ids, $sid->sender_id);
            } elseif (isset($client_array) && is_array($client_array) && in_array($user_id, $client_array)) {
                array_push($all_ids, $sid->sender_id);
            }
        }
        $sender_ids = array_unique($all_ids);

        // $ids_ = explode(',', $gateway->gateway_ids);
        $random_number = mt_rand(0, count($sender_ids)-1);
        $sender_id_ran = $sender_ids[$random_number];

        if($sender_id_ran == Cache::pull('recentlyUsedId')) {
            $this->rotateSenderId($user_id);
        }

        // $this->recentlyUsedId = $sender_id_ran;
        Cache::put('recentlyUsedId', $sender_id_ran);

        return $sender_id_ran;
    }


      //======================================================================
    // renderSMS Start Here
    //======================================================================
    public function renderSMS($msg, $data)
    {
        preg_match_all('~<%(.*?)%>~s', $msg, $datas);
        $Html = $msg;
        foreach ($datas[1] as $value) {
            if (array_key_exists($value, $data)) {
                $Html = preg_replace("/\b$value\b/u", $data[$value], $Html);
            } else {
                $Html = str_ireplace($value, '', $Html);
            }
        }
        return str_ireplace(array("<%", "%>"), '', $Html);
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $campaigns = Campaigns::whereIn('status', array('Running', 'In Progress'))->where('camp_type','regular')->get();
        // Log::debug($campaigns);
        foreach ($campaigns as $camp) {
            $results = [];

            $now = Carbon::now();
            // Log::debug(Carbon::createFromFormat('Y-m-d H:i:s', $camp->run_at));
            // Log::debug(Carbon::createFromFormat('Y-m-d H:i:s', $camp->run_at)->addMinutes($camp->intaval));
            if ($now->greaterThanOrEqualTo(Carbon::createFromFormat('Y-m-d H:i:s', $camp->run_at)->addMinutes($camp->intaval))) {
                // $camp->status = 'In Progress';
                // $camp->save();

                if ($camp->contact_type == 'phone_book') {
                    $get_data = ContactList::whereIn('pid', explode(',', $camp->contact))->select('phone_number', 'email_address', 'user_name', 'company', 'first_name', 'last_name')->get()->toArray();
                    foreach ($get_data as $data) {
                        array_push($results, $data);
                    }
                }
        
                if ($camp->contact_type == 'client_group') {
                    $get_group = Client::whereIn('groupid', explode(',', $camp->contact))->select('phone AS phone_number', 'email AS email_address', 'username AS user_name', 'company AS company', 'fname AS first_name', 'lname AS last_name')->get()->toArray();
                    foreach ($get_group as $data) {
                        array_push($results, $data);
                    }
                }
                //Check queued in subscription list
                $subscription_list = CampaignSubscriptionList::where('campaign_id', $camp->campaign_id)->where('status','queued')->limit($camp->amount)->get();
                $list_count = $subscription_list->count();

                //if queued available run send sms query
                if ($list_count > 0) {

                    $msg_type = $camp->sms_type;
                    $gateway = SMSGateways::find($camp->use_gateway);

                    $gateway_credential = null;
                    $cg_info = null;
                    if ($gateway->custom == 'Yes') {
                        if ($gateway->type == 'smpp') {
                            $gateway_credential = SMSGatewayCredential::where('gateway_id', $gateway->id)->where('status', 'Active')->first();
                        } else {
                            $cg_info = CustomSMSGateways::whertotal_recipiente('gateway_id', $camp->use_gateway)->first();
                        }
                    } else {
                        $gateway_credential = SMSGatewayCredential::where('gateway_id', $gateway->id)->where('status', 'Active')->first();
                    }


                    foreach ($subscription_list as $list) {

                        

                        $r = array_search($list->number, array_column($results, 'phone_number'));
                        // Log::debug($r);

                        if($r !== false) {

                            $msg_data = array(
                                'Phone Number' => $results[$r]['phone_number'],
                                'Email Address' => $results[$r]['email_address'],
                                'User Name' => $results[$r]['user_name'],
                                'Company' => $results[$r]['company'],
                                'First Name' => $results[$r]['first_name'],
                                'Last Name' => $results[$r]['last_name'],
                            );
    
                            // Log::debug($msg_data);
    
                            $message = $this->process($this->renderSMS($camp->message, $msg_data));

                        } else {
                            // Log::debug('contact not found');
                            $message = $this->process($list->message);
                        }

                        

                        $msgcount = $list->amount;

                        if ($msg_type == 'plain' || $msg_type == 'unicode' || $msg_type == 'arabic') {
                            dispatch(new SendBulkSMS($camp->user_id, $list->number, $gateway, $gateway_credential, $camp->sender ? $camp->sender : $this->rotateSenderId($camp->user_id), $message, $msgcount, $cg_info, '', $msg_type, $list->id));
                        }

                        if ($msg_type == 'voice') {
                            dispatch(new SendBulkVoice($camp->user_id, $list->number, $gateway, $gateway_credential, $camp->sender ? $camp->sender : $this->rotateSenderId($camp->user_id), $message, $msgcount, '', $msg_type, $list->id));
                        }


                        if ($msg_type == 'mms') {
                            $media_url = $camp->media_url;
                            dispatch(new SendBulkMMS($camp->user_id, $list->number, $gateway, $gateway_credential, $camp->sender ? $camp->sender : $this->rotateSenderId($camp->user_id), $message,$media_url, '', $msg_type, $list->id));
                        }


                        if($list->message != $message ) {
                            $list->message = $message;
                            $list->save();
                        }

                    }

                    
                }


                // Update record run_at 

                $delivered = CampaignSubscriptionList::where('campaign_id', $camp->campaign_id)->where('status', 'like', "%Success%")->get()->count();
                $failed = CampaignSubscriptionList::where('campaign_id', $camp->campaign_id)->where('status', 'like', "%Failed%")->get()->count();
                // $failed    = $camp->total_recipient - $delivered;


                
                if(($delivered + $failed) == $camp->total_recipient ) {
                    $camp->total_delivered = $delivered;
                    $camp->total_failed    = $failed;
                    $camp->status          = 'Delivered';
                    $camp->delivery_at     = date('Y-m-d H:i:s');
                    $camp->save();
                }else {
                    $camp->total_delivered = $delivered;
                    $camp->total_failed    = $failed;
                    $camp->status = 'In Progress';
                    $camp->run_at = Carbon::createFromFormat('Y-m-d H:i:s', $camp->run_at)->addMinutes($camp->intaval);
                    $camp->save();
                }
                
            }


            
        }



    }
}
