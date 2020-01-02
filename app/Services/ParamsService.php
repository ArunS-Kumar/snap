<?php
namespace App\Services;

class ParamsService extends BaseService{

	const TYPE_INT = "Integer";
	const TYPE_STRING = "String";
	const TYPE_BOOL = "Boolean";

	public function __construct(){
		parent::__construct();
	}

	public function createFilterParams($queryId,$clientCrmId){
		$params = [
			'QueryId' => $queryId,
			'Parameters' => [
				$this->createParam('ClientID',$clientCrmId,'Integer')
			],
			'ArrayParameters' => []
		];

		$params['Parameters'] = array_merge($params['Parameters'],$this->signatureParams());
		
		return $params;
	}

	protected function signatureParams(){
		$hashService = new HashService();
		$sVar = null;
		$sgVar = $hashService->signature($sVar);
		return [
			$this->createParam('s',$sVar,'String'),
			$this->createParam('sg',$sgVar,'String')
		];
	}

	protected function createParam($name,$val,$type){
		return ['Name'=>$name,'Value'=>$val,'Type'=>$type];
	}

	protected function mandatoryParameters($clientIdn)
	{
		$hashService = new HashService();
		$timestampHashed = null;
		$signature = $hashService->signature($timestampHashed);
		
		return [
			$this->createParam('ClientID', $clientIdn, self::TYPE_INT),
			$this->createParam('s', $timestampHashed, self::TYPE_STRING),
			$this->createParam('sg', $signature, self::TYPE_STRING),
		];
	}

	public function vatAnalysisParams($clientCrmId, $input){
        $signatureParams = $this->signatureParams();
		$moreFilterParams = $this->globalFilterParams($input, $clientCrmId);
		$clientIdParam=[$this->createParam('ClientID',$clientCrmId,'Integer')];

        $queryParams = [
            'QueryId' => $input['query_id'],
            'ArrayParameters' => $moreFilterParams['array_params'],
            'Parameters' => array_merge($clientIdParam,$signatureParams, $moreFilterParams['params'])
        ];

        return $queryParams;
	}

	protected function globalFilterParams($input, $clientCrmId = false){
		$moreFilters = $input['more_filters'];
	
		$arrayParameters = [];
		$params = [];
		foreach($moreFilters as $name => $filter) {
			if(is_array($filter['value'])) {
				if (count($filter['value']) > 0) {
					
					if ($filter['type'] === self::TYPE_INT) {
						$filter['value'] = array_map('intval', $filter['value']);
					}
					$arrayParameters[] = $this->createParam($name,$filter['value'],$filter['type']);
				}
				
			} else {	
				if ($filter['type'] === self::TYPE_INT) {
					$filter['value'] = (int)$filter['value'];
				}
				$params[] = $this->createParam($name,$filter['value'],$filter['type']);
			}
		}
		
		return ['array_params' => $arrayParameters, 'params' => $params];
	}

	public function currenciesParams($currenciesArray){
		$queryId = 'GetExchangeRatesToEUR';
		return [
			'QueryId' => $queryId,
			'Parameters' => $this->signatureParams(),
			'ArrayParameters' => [$this->createParam('Currencies', $currenciesArray, self::TYPE_STRING)]
		];
	}
}
