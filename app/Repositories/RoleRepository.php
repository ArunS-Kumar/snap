<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository extends BaseRepository {

    public function __construct()
    {
        parent::__construct();
    }

    public function getRoleByName($roleName){
		return Role::where('name',$roleName)->first();
	}

}