<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class ApiRequestLog extends Model
{
    protected $table = 'api_request_log';

    protected $fillable = [
        'path',
        'params',
    ];

    protected $casts = [
        'params' => 'array',
    ];

    public $timestamps = false;

}
