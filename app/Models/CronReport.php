<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CronReport extends Model
{

    const SYNC_CLIENT_ANALYTICS_SUMMARY_FROM_CRM = "sync_client_analytics_summary";
    const STATUS_FAIL = "failed";
    const STATUS_PASS = "passed";

    public $fillable=['status','type','start_time','end_time','success_message','error_message'];
}
