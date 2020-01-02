<?php
namespace App\Services;

use App\Responses\BaseResponse;
use TheSeer\Tokenizer\Exception;
use App\Exceptions\OtsException;
use League\Flysystem\Config;


class OtsService extends BaseService
{
	protected $baseUrl;
	public function __construct($typeOfRequest){
		if($typeOfRequest == "vat_api")
			$this->baseUrl = Config('custom.vat_api_base_url');
		else if($typeOfRequest == "pdf")
			$this->baseUrl = Config('custom.pdf_url');
		else
			$this->baseUrl = Config('custom.api_base_url');
	}

	public function getResponse($config, $params, $headers = [], $is_binary = false){
		try {
			$endpoint = Config('custom.'.$config);
			$url = $this->baseUrl.(is_null($endpoint)?Config('custom.default_endpoint'):$endpoint);
			$response = $this->sendRequest('POST', $url, $params, $headers, $is_binary);
		
			if (empty($response['data'])) {
				\Log::error("------------No Data from OTS-----------");
				\Log::error("params: ".json_encode($params));
				\Log::error("URL: ".$url);
				\Log::error("---------------------------------------");
				return [];
			} else {
				return $response['data'];
			}
		} catch (\Exception $e) {
			foreach($params['Parameters'] as $key=>$param){
				if($param['Name']=='s' || $param['Name']=='sg')
					unset($params['Parameters'][$key]);
			}
			$params['Parameters']=array_values($params['Parameters']);
			throw new OtsException(json_encode([ 'message' => $e->getMessage(),'headers' => $headers, 'params' => $params]));
		}
	}

	protected function sendRequest($method, $url, $params=[], $headers=[], $isBinary=false){

		//manually required
		$headers['Content-Type']="application/json";
		$client = new \GuzzleHttp\Client();
		$response = $client->request($method, $url, [
			'body'=>json_encode($params),
			'headers'=>$headers
		]);

		if(!$isBinary)
			return json_decode($response->getBody(), true);
		else
			return $response->getBody()->getContents();
    }
	
}
