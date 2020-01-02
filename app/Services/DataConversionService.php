<?php
namespace App\Services;

class DataConversionService extends BaseService
{
	public function vatAnalysisData($data){
		$formatted=[];
		foreach($data as $index=>$row){
			foreach($row['Properties'] as $property){
				if(isset($property['Key']) && isset($property['Value']))
					$formatted[$index][$property['Key']]=$property['Value'];
			}
		}
		return $formatted;
	}
}
