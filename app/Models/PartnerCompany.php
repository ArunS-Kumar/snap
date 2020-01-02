<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerCompany extends Model
{
    protected $fillable = ['unique_id','name','is_active','logo'];

    protected $hidden = ['unique_id','pivot'];

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }
}
