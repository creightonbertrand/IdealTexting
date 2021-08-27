<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuickReply extends Model
{
    protected $table = 'sys_quick_reply';
    // protected $fillable = ['name', 'reply'];
    protected $fillable = ['template_name','message','global','status'];

}
