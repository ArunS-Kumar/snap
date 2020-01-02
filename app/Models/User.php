<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Services\UserService;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'name', 'email',
    ];

    protected $hidden = [
        'remember_token','pivot','role_id'
    ];

    protected $appends = ['role'];

    public function role() {
        return $this->belongsTo('App\Models\Role');
    }

    public function clients(){
        return $this->belongsToMany('App\Models\Client')->orderBy('name')->withTimestamps();
    }

    public function partnerCompanies(){
        return $this->belongsToMany('App\Models\PartnerCompany')->withTimestamps();
    }
    
    public function isAdmin(){
        return $this->hasRole("admin");
    }

    public function isPartner(){
        return $this->hasRole("partner");
    }

    public function getRoleAttribute(){
        return $this->role()->first()->name;
    }

    public function hasRole($roleName){
        if($this->role == $roleName)
            return true;
        return false;
    }

    public function getJWTIdentifier(){
        return $this->getKey();
    }

    public function getJWTCustomClaims(){
        return [];
    }
}
