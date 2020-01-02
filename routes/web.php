<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/sync', function(){
//     $client = \App\Models\Client::inRandomOrder()->first();
//     $client->source = 'snapshot';
//     event(new \App\Events\SyncEvent('sync_single_client_create', $client));
// });

// ******* TEST ROUTES TO GET CODE AND TOKEN MANUALLY ******** //
// Route::get('/redirect', function () {
//     $query = http_build_query([
//         'client_id' => 6,
//         'redirect_uri' => 'http://snapshot.local.com/callback',
//         'response_type' => 'code',
//         'scope' => '',
//     ]);
//     return redirect('http://tbiauth.local.com/oauth/authorize?'.$query);
// });


// Route::get('/callback',function(){
//     echo "hi im inside callback route";

//     // echo "<pre>"; print_r($_REQUEST['code']); exit;
// });