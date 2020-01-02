<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable=['name','crm_id','status'];

    protected $hidden = [
        'pivot'
    ];
    
    public function users(){
        return $this->belongsToMany('App\Models\User')->orderBy('name')->withTimestamps();;
    }

    public function scopeClient($query){
        return $query->where('is_prospect', false);
    }
}
