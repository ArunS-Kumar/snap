<?php

namespace App\Services;

class PermissionService extends BaseService {

    protected $clientRepo,$clientConfigRepo,$permissionRepo;

    public function __construct() {
        parent::__construct();
    }

    public function checkViewClientsListPermission($user, $partnerId){
        if(is_null($partnerId))
            return false;
        // if the admin logs in
        if($user->isAdmin())
            return true;
        // if the partner logs in
        if( ($user->isPartner()) && ($user->id == $partnerId) )
            return true;
        //default
        return false;
    }

    public function checkViewPartnersListPermission($user, $companyId){

        if(is_null($companyId))
            return false;

        if(!$user->isAdmin()){
            return false;
        }

        return true;

    }

    public function checkViewPartnerCompaniesListPermission($user){
        if(!$user->isAdmin()){
            return false;
        }
        return true;
    }
    
    public function checkViewEventsPermission($user, $partnerId){
        if(is_null($partnerId))
            return false;
        // if the admin logs in
        if($user->isAdmin())
            return true;
        // if the partner logs in
        if( ($user->isPartner()) && ($user->id == $partnerId) )
            return true;
        //default
        return false;
    }
    
}