<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With, auth-token');
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Origin: *");


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::group([
    'prefix' => 'v2'
], function()
{ 	

	Route::namespace('App\Http\Controllers\Api')->group(function () {
     
     	Route::post('/login', 'UserController@login');
	    Route::post('/register', 'UserController@member/registration');
	    Route::get('/logout', 'UserController@logout')->middleware('auth:api');
	    Route::match(['post','get'], 'email_verification', 'UserController@emailVerification');
	    Route::match(['post','get'], 'forgotPassword', 'Api\UserController@forgotPassword');
	    Route::match(['post','get'], 'password/reset', 'UserController@resetPassword');
	    Route::match(['post','get'], 'changePassword', 'UserController@changePassword');
	    Route::match(['post','get'], 'mChangePassword', 'UserController@mChangePassword');

		});
	
	Route::namespace('App\Http\Controllers\Api')->group(function () {
	    // Controllers Within The "App\Http\Controllers\Admin" Namespace    
	    Route::match(['post','get'],'getPlaying11', 'ApiController@getPlaying11');
	    Route::match(['post','get'],'changeMobile', 'UserController@changeMobile');
	    Route::match(['post','get'],'login', 'UserController@login');
	        
	    Route::match(['post','get'],'withdrawAmount', 'ApiController@withdrawAmount');
	    Route::match(['post','get'],'getProfile', 'UserController@getProfile');

	    Route::match(['get','post'], 'updateLiveMatchStatus', [
	        'as' => 'updateLiveMatchStatus',
	        'uses' => 'ApiController@updateMatchDataByStatus'
	    ]);

	    Route::match(['get','post'], 'getPlayerPoints', [
	        'as' => 'getPlayerPoints',
	        'uses' => 'ApiController@getPlayerPoints'
	    ]);
	    
	    Route::match(['get','post'], 'automateCreateContest', [
	        'as' => 'automateCreateContest',
	        'uses' => 'ApiController@automateCreateContest'
	    ]);

	    Route::match(['get','post'], 'verification', [
	        'as' => 'verification',
	        'uses' => 'ApiController@verification'
	    ]);

	    Route::match(['get','post'], 'playerAnalytics', [
	        'as' => 'playerAnalytics',
	        'uses' => 'ApiController@playerAnalytics'
	    ]);
	    Route::match(['get','post'], 'getMyPlayedMatches', [
	        'as' => 'getMyPlayedMatches',
	        'uses' => 'ApiController@getMyPlayedMatches'
	    ]);

	    Route::match(['post','get'],'updateMatchDataByMatchId/{match_id}/{status}', 'ApiController@updateMatchDataByMatchId'); 
	    Route::match(['get','post'], 'generateOtp', [
	        'as' => 'generateOtp',
	        'uses' => 'UserController@generateOtp'
	    ]);

	    Route::match(['get','post'], 'verifyOtp', [
	        'as' => 'verifyOtp',
	        'uses' => 'UserController@verifyOtp'
	    ]);

	    Route::match(['post','get'],'myReferralDetails', 'UserController@myReferralDetails');

	    Route::match(['post','get'],'updateAfterLogin', 'UserController@updateAfterLogin');

	    Route::match(['post','get'],'inviteUser', 'UserController@inviteUser');

	    Route::match(['post','get'],'verifyDocument', 'UserController@verifyDocument');

	    Route::match(['post','get'],'apkUpdate', 'ApiController@apkUpdate');

	    Route::match(['post','get'],'contestFillNotify', 'ApiController@contestFillNotify');
	    

	    Route::match(['post','get'],'deviceNotification', 'UserController@deviceNotification');
	    Route::match(['post','get'],'sendPushNotification', 'UserController@sendPushNotification');

	    // cron from backedn
	    Route::match(['post','get'],'getMatchDataFromApiAdmin', 'CronController@getMatchDataFromApi');

	    Route::match(['post','get'],'getPlayingMatchHistory', 'ApiController@getPlayingMatchHistory');

	    Route::match(['post','get'],'captureScreenTime', 'ApiController@captureScreenTime');

	    Route::match(['post','get'],'getMatchHistory', 'ApiController@getMatchHistory');
	    Route::match(['post','get'],'updateMatchDataByStatusAdmin/{status}', 'CronController@updateMatchDataByStatus');
	    // system API
	    Route::match(['post','get'],'storeMatchInfo/{fileName}', 'ApiController@storeMatchInfo');
	    Route::match(['post','get'],'getMatchDataFromApi', 'ApiController@getMatchDataFromApi');
	    Route::match(['post','get'],'updateMatchDataByStatus/{status}', 'ApiController@updateMatchDataByStatus');
	    Route::match(['post','get'],'updatePlayerFromCompetition', 'ApiController@updatePlayerFromCompetition');
	    Route::match(['post','get'],'updatePlayerByMatch/{match_id}', 'ApiController@getCompetitionByMatchId');
	    Route::match(['post','get'],'getSquad/{match_id}', 'ApiController@getSquad');
	    Route::match(['post','get'],'updateAllSquad', 'ApiController@updateAllSquad');
	    Route::match(['post','get'],'createContest/{match_id}', 'ApiController@createContest');
	    Route::match(['post','get'],'updateMatchDataById/{match_id}', 'ApiController@updateMatchDataById');

	    Route::match(['post','get'],'updateMatchStatus', 'ApiController@updateMatchStatus');

	    Route::match(['post','get'],'saveMatchDataByMatchId/{match_id}', 'ApiController@saveMatchDataByMatchId');
	    Route::match(['post','get'],'updateMatchInfo', 'ApiController@updateMatchInfo');
	    Route::match(['post','get'],'updateSquad/{match_id}', 'ApiController@updateSquad');
	    Route::match(['post','get'],'updateLiveMatchFromApp', 'ApiController@updateLiveMatchFromApp');
	    Route::match(['post','get'],'updatePoints', 'ApiController@updatePoints');
	    Route::match(['post','get'],'getPointsByMatch', 'ApiController@getPointsByMatch');
	    Route::match(['post','get'],'updatePointAfterComplete', 'ApiController@updatePointAfterComplete');
	    Route::match(['post','get'],'updateUserMatchPoints', 'ApiController@updateUserMatchPoints');



	    //User API
	    Route::match(['post','get'],'member/registration', 'UserController@registration');
	    Route::match(['post','get'],'member/customerLogin', 'UserController@customerLogin');
	    Route::match(['post','get'],'email_verification','UserController@emailVerification');
	    Route::match(['post','get'],'member/updateProfile', 'UserController@updateProfile');
	    Route::match(['post','get'],'member/logout', 'UserController@logout');
	    Route::match(['post','get'],'temporaryPassword', 'UserController@temporaryPassword');
	    Route::match(['post','get'],'resetPassword', 'UserController@resetPassword');


	    // auth required API
	    Route::match(['post','get'],'joinNewContestStatus', 'ApiController@joinNewContestStatus');
	    Route::match(['post','get'],'getScore', 'ApiController@getScore');
	    Route::match(['post','get'],'transactionHistory', 'PaymentController@transactionHistory');
	    Route::match(['post','get'],'getMatch', 'ApiController@getMatch');
	    Route::match(['post','get'],'getPlayer', 'ApiController@getPlayer');
	    Route::match(['post','get'],'getContestByMatch', 'ApiController@getContestByMatch');
	    Route::match(['post','get'],'cloneMyTeam', 'ApiController@cloneMyTeam');
	    Route::match(['post','get'],'createTeam', 'ApiController@createTeam');
	    Route::match(['post','get'],'getMyTeam', 'ApiController@getMyTeam');
	    Route::match(['post','get'],'joinContest', 'ApiController@joinContest');
	    Route::match(['post','get'],'getMyContest', 'ApiController@getMyContest');
	    Route::match(['post','get'],'prizeDistribution', 'PaymentController@prizeDistribution');
	    Route::match(['post','get'],'getWallet', 'ApiController@getWallet');
	    Route::match(['post','get'],'addMoney', 'ApiController@addMoney');
	    Route::match(['post','get'],'leaderBoard', 'ApiController@leaderBoard');
	    Route::match(['post','get'],'getPrizeBreakup', 'ApiController@prizeBreakup');
	    Route::match(['post','get'],'getContestStat', 'ApiController@getContestStat');
	    Route::match(['post','get'],'getPoints', 'ApiController@getPoints');
	    Route::match(['post','get'],'saveDocuments', 'ApiController@saveDocuments');    
	    Route::match(['post','get'],'isLineUp', 'ApiController@isLineUp');
	    Route::match(['post','get'],'matchAutoCancel', 'ApiController@matchAutoCancel');

	    //added by manoj
	    Route::match(['post','get'],'uploadbase64Image', 'ApiController@uploadbase64Image');
	    Route::match(['post','get'],'member/uploadImages', 'ApiController@uploadImages');
	    Route::match(['post','get'],'member/updateProfile', 'UserController@updateProfile');
	    Route::match(['post','get'],'updateProfile', 'UserController@updateProfile');

	    Route::match(['post','get'],'getAnalytics', 'ApiController@getAnalytics');

	    Route::match(['post','get'],'getNotification', 'ApiController@getNotification');
	    Route::match(['post','get'],'paytmCallBack', 'ApiController@paytmCallBack');

	    Route::match(['post','get'],'callBackUrl', 'ApiController@paytmCallBack');

	    Route::match(['post','get'],'paytmCallBack', 'ApiController@paytmCallBack');


	    Route::match(['post','get'],'paymentCallback', 'ApiController@paymentCallback');


	    Route::match(['post','get'],'checkSingnature', 'ApiController@checkSingnature'); 

	    Route::match(['post','get'],'eventLog', 'ApiController@eventLog');
	    
	    Route::match(['post','get'],'getContestByType', 'ApiController@getContestByType');

	    Route::match(['post','get'],'detectDevice', 'ApiController@detectDevice');

	    Route::match(['post','get'],'playerPoints', 'ApiController@playerPoints');
	    
	    Route::match(['post','get'],'playerStat', 'ApiController@playerStat');
	    Route::match(['post','get'],'distributePrize', 'ApiController@distributePrize');
	    Route::match(['post','get'],'affiliateProgram', 'ApiController@affiliateProgram');

	    Route::match(['post','get'],'getSquadByMatch/{match_id}', 'ApiController@getSquadByMatch');

	     Route::match(['post','get'],'getMatchFK', 'ApiController@getMatchFK');
	     Route::match(['post','get'],'createRazorPayOrder', 'ApiController@razorpayOrderId'); 

	     Route::match(['post','get'],'updatePointsByMatchID', 'ApiController@updatePointsByMatchID');
	     
	     Route::match(['post','get'],'createTeamFromAnother', 'ApiController@joinedTeamFromAnother');

	    Route::match(['post','get'],'joinContestfromRB', 'ApiController@joinTeamfromRB');

	     Route::match(['post','get'],'editTeamFromAnother', 'ApiController@editTeamFromAnother');
	    Route::match(['post','get'],'identifyUser', 'ApiController@identifyUser');  

	    Route::match(['post','get'],'deleteHeroTeam', 'ApiController@deleteHeroTeam');

	    Route::match(['post','get'],'getContest', 'MobileController@getContest');   

	    Route::match(['post','get'],'updateMatchPointFromCron', 'ApiController@updateMatchPointFromCron');   
	    
	    Route::match(['post','get'],'updateMatchRankFromCron', 'ApiController@updateMatchRankFromCron');   
	    

	});

	Route::namespace('App\Http\Controllers\Api')->group(function () {

	});

 });



Route::group([
    'prefix' => 'admin'
], function()
{ 

	Route::namespace('App\Http\Controllers\Api')->group(function () {

	   Route::match(['post','get'],'leaderBoardFK', 'MobileController@leaderBoardFK'); 
	   Route::match(['post','get'],'joinContestFK', 'MobileController@joinContestFK');
	   Route::match(['post','get'],'getAllAccounts', 'MobileController@getAllAccounts');
	   Route::match(['post','get'],'getAllMatchesByDate', 'MobileController@getAllMatchesByDate');
	   Route::match(['post','get'],'getCancelContest', 'MobileController@getCancelContest');
	   
		});

});



// if URL not found
Route::group([
    'prefix' => 'v2'
], function()
{
    // if route not found
    Route::any('{any}', function(){
        $data = [
            'status'=>0,
            'code'=>400,
            'message' => 'Bad request'
        ];
        return \Response::json($data);
    });
});
