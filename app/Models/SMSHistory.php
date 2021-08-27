<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SMSHistory extends Model
{
    use \Conner\Tagging\Taggable;
    protected $table='sys_sms_history';
    protected $fillable=['userid','sender','receiver','message','amount','status','sms_type','api_key','use_gateway','send_by','media_url'];
}
