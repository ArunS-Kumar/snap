<?php

namespace App\Repositories;

use App\Models\PartnerCompany;

class PartnerCompanyRepository extends BaseRepository {

    public function __construct(){
        parent::__construct();
    }
    
    public function findById($companyId)
    {
        return PartnerCompany::find($companyId);
    }

    public function getPartnerCompaniesList(){
        return PartnerCompany::orderBy('name')->get();
    }

    public function createOrUpdate($details){
        try{
            $company = PartnerCompany::where('unique_id', $details->unique_id)->first();
            if(!$company){
                $company = new PartnerCompany;
            }
            $company->name = $details->name;
            $company->unique_id = $details->unique_id;
            $company->logo = $details->logo;
            $company->is_active = $details->is_active;
            $company->save();
        }catch(\Exception $e){
            \Log::error("something went wrong: ".$e->getMessage());
            return false;
        }
		return $company;
    }

}