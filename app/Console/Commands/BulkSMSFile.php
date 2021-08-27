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
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Client;
use App\ClientGroups;
use App\ContactList;
use SignalWire\Rest\Client as Signalwire;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberToCarrierMapper;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\geocoding\PhoneNumberOfflineGeocoder;

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
                if($sid->sms_sent > 1000) {
                    // block number
                    // release number
                    if(!is_null($sid->sid)) {
                        try {
                            $gateway_credential = SMSGatewayCredential::where('gateway_id', $gateway->id)->where('status', 'Active')->first();
                            $Signalwire = new Signalwire($gateway_credential->username, $gateway_credential->password, array("signalwireSpaceUrl" => $gateway->api_link));
                            $Signalwire->incomingPhoneNumbers($sid->sid)->delete();

                            $sid->status = 'block';
                            $sid->save();
                        } catch (\Exception $e) {
                            $sid->status = 'block';
                            $sid->save();
                        }
                    }else {
                        $sid->status = 'block';
                        $sid->save();
                    }
                    
                    // get an new number 
                    try {
                        $clients_id = json_encode(array($user_id), true);
                        $gateway = SMSGateways::where('settings', 'Signalwire')->first();
                        $gateway_credential = SMSGatewayCredential::where('gateway_id', $gateway->id)->where('status', 'Active')->first();
            
                        $Signalwire = new Signalwire($gateway_credential->username, $gateway_credential->password, array("signalwireSpaceUrl" => $gateway->api_link));

                        try {

                            $geoCoder = PhoneNumberOfflineGeocoder::getInstance();
                            $gbNumber = \libphonenumber\PhoneNumberUtil::getInstance()->parse($sid->sender_id, 'US');

                            $area_code = substr($gbNumber->getNationalNumber(), 0, 3);
                            $numbers = $Signalwire->availablePhoneNumbers('US')->local->read(array("areaCode" => $area_code));
                            if(count($numbers) > 0) {
                                $number_to_buy = $numbers[0];
                                $incoming_phone_number = $Signalwire->incomingPhoneNumbers ->create([
                                    "phoneNumber" => $number_to_buy,
                                    "smsUrl" => "http://ec2-54-244-204-79.us-west-2.compute.amazonaws.com/sms/reply-signalwire",
                                    "smsMethod" => 'POST'
                                ]);
                        
                                if($incoming_phone_number->sid) {
                    
                                    $sender_id            = new SenderIdManage();
                                    $sender_id->sender_id = $incoming_phone_number->phoneNumber;
                                    $sender_id->cl_id     = $clients_id;
                                    $sender_id->status    = 'unblock';
                                    $sender_id->sid       = $incoming_phone_number->sid;
                                    $sender_id->save();
                                }
                                
                            }else {
                                continue;
                            }
                            
                            
                        } catch (\Exception $e) {
                            //throw $th;
                            continue;
                        }
                        
                
            
                        
                        
                    } catch (Exception $e) {
                        
                        // $this->rotateSenderId($user_id);
                        continue;
            
                    }


                    array_push($all_ids, $incoming_phone_number->phoneNumber);
                }else {
                    array_push($all_ids, $sid->sender_id);
                }
                
            }
        }
        $sender_ids = array_unique($all_ids);
        //  Log::debug("==============================");
        //   Log::debug($sender_ids);

        // $ids_ = explode(',', $gateway->gateway_ids);
        $random_number = mt_rand(0, count($sender_ids)-1);
        $sender_id_ran = $sender_ids[$random_number];

        if($sender_id_ran == Cache::pull('recentlyUsedId'.$user_id)) {
            $this->rotateSenderId($user_id);
        }

        //  Log::debug("==============================");

        $used_sender_id = SenderIdManage::where('status', 'unblock')->where('sender_id', $sender_id_ran)->first();
        //  Log::debug($used_sender_id);
        $used_sender_id->increment('sms_sent', 1);

        // $this->recentlyUsedId = $sender_id_ran;
        Cache::put('recentlyUsedId'.$user_id, $sender_id_ran);

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
        $campaigns = Campaigns::whereIn('status', array('Running', 'In Progress', 'Auto Paused'))->where('camp_type','regular')->get();
        // Log::debug($campaigns);
        foreach ($campaigns as $camp) {

            $now = Carbon::now();

            $camp->status = 'In Progress';
            $camp->save();

            if($camp->status == 'Auto Paused') {
                $can_send_camp = $now->greaterThanOrEqualTo(Carbon::createFromFormat('Y-m-d H:i:s', $camp->run_at)->addSeconds($camp->msg_pause_time));
            }else{
                $can_send_camp = $now->greaterThanOrEqualTo(Carbon::createFromFormat('Y-m-d H:i:s', $camp->run_at)->addSeconds($camp->intaval));
            }

            $cut_off = $now->greaterThanOrEqualTo(Carbon::createFromTimeString($request->start_time)) && $now->lessThanOrEqualTo(Carbon::createFromTimeString($request->stop_time));


            $results = [];

            
            // Log::debug(Carbon::createFromFormat('Y-m-d H:i:s', $camp->run_at));
            // Log::debug(Carbon::createFromFormat('Y-m-d H:i:s', $camp->run_at)->addMinutes($camp->intaval));
            if ($can_send_camp && $cut_off) {
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


                    $now = Carbon::now();
                    $ccount = 0;


                    foreach ($subscription_list as $list) {

                        if($ccount == $camp->msg_pause) {
                            $can_send = false;
                        }else {
                            $can_send = true;
                            $now = $now->addSeconds($camp->amount); 
                        }

                        if($can_send) {

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
                                Log::debug('contact not found');
                                $message = $this->process($list->message);
                            }

                                

                            $msgcount = $list->amount;

                            if ($msg_type == 'plain' || $msg_type == 'unicode' || $msg_type == 'arabic') {
                                Log::debug('Dispaching -----');
                                $job = (new SendBulkSMS($camp->user_id, $list->number, $gateway, $gateway_credential, $camp->sender ? $camp->sender : $this->rotateSenderId($camp->user_id), $message, $msgcount, $cg_info, '', $msg_type, $list->id))->delay($now);

                                dispatch($job);
                                Log::debug('done');
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

                        }else {
                            Log::debug('Breaking');
                            // do something here
                            break;
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
                    if($can_send) {
                        $camp->run_at = Carbon::createFromFormat('Y-m-d H:i:s', $camp->run_at)->addSeconds($camp->intaval);
                    }else {
                        $camp->status = 'Auto Paused';
                        $camp->run_at = Carbon::createFromFormat('Y-m-d H:i:s', $camp->run_at)->addSeconds($camp->msg_pause_time);
                    }
                    $camp->save();
                }
                
            }


            
        }



    }
}
