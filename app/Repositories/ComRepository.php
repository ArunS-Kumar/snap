<?php

namespace App\Repositories;

use DB;

class ComRepository extends BaseRepository{

	public function __construct(){
		parent::__construct();
	}

	public function getChannel(){
		return DB::table('communication_channels')->first();
	}

}