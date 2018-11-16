<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    protected $table = 'admin_log';

    public $timestamps = false;

    protected $fillable = 
    [
    	"name",
    	"logintime"
    ];
}
