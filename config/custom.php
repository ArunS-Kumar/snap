<?php

return [
	//App wide related
	'encSaltStatic' => 'jhgYsTy67M$mzRtaP3&c2@6Wm<pRtmxC',
	'encSaltInternal' => 'aU32SdfYK@dnn7sd',
	'encSaltExternal' => 'mYx8jW7s<zPa0Rtrn7@x%x',

	'events_api_base_url'=>env('EVENTS_API_BASE_URL'),
	'api_base_url'=>env('OTS_API_BASE_URL'),
	'vat_api_base_url'=>env('VAT_API_BASE_URL'),
	'default_endpoint'=>env('DEFAULT_ENDPOINT'),
	'per_page'=>env('PER_PAGE', 10),
	'coms_validity_in_seconds' => env('AUTH_COMMS_VALIDITY_IN_SECONDS',"30"),
];