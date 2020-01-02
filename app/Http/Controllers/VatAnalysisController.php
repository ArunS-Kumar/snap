<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\VatAnalysisRequest;
use App\Services\{ParamsService, DataConversionService, OtsService};
use App\Responses\VatResponse;

class VatAnalysisController extends Controller
{
    const SNAPSHOT_ANALYTICS_STAT_QUERY_ID = 'Snapshot_Analytics_Stats';

    public function getData(VatAnalysisRequest $req, $clientCrmId){
        $input = $req->validated();
        /*The following should be temporary code*/
        if(isset($input['more_filters']) && isset($input['more_filters']['ApplicationTypeForeign'])
        ){
            $input['more_filters']['ApplicationTypeForeign_EligibleExpenseLine']=['type'=>'Boolean','value'=>true];
        }

        if(isset($input['more_filters']) && isset($input['more_filters']['ApplicationTypeDomestic'])
        ){
            $input['more_filters']['ApplicationTypeDomestic_EligibleExpenseLine']=['type'=>'Boolean','value'=>true];
        }
        $input['query_id'] = self::SNAPSHOT_ANALYTICS_STAT_QUERY_ID;
        /*Currently frontend or SQL can't make this change, this should be removed when either of them can do it*/

        $queryParams = (new ParamsService())->vatAnalysisParams($clientCrmId, $input);
        $data = (new OtsService('vat_api'))->getResponse(self::SNAPSHOT_ANALYTICS_STAT_QUERY_ID, $queryParams);
        $vatData = (new DataConversionService())->vatAnalysisData($data);
        return (new VatResponse())->successWithData("data_fetched", $vatData);
    }
}
