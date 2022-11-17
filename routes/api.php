<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Client;
use App\Models\Client_Token;
use App\Helpers\MyTokenManager;

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
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/
Route::post('/register', function(Request $request)
{
    $request->validate(
        [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|unique:clients',
            'password' => 'required|confirmed|min:6|max:255',
            'password_confirmation' => 'required|min:6'
        ]
    );
    $EncPassword = Hash::make($request->password);
    $user = Client::create(
    [
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email' => $request->email,
        'password' => $EncPassword
    ]);
    $userId = $user->id;
    $token = MyTokenManager::createToken($userId);
    return
    [
        'message' => 'Sign-up Successfully',
        'token' => $token,
    ];
    
});

Route::post('/login', function(Request $request)
{
    $request->validate(
        [
            'email' => 'required|email',
            'password' => 'required|min:6|max:255'
        ]
    );

    $user = Client::where('email', $request->email)->first();
    if($user !== NULL && Hash::check($request->password ,$user->password) )
    {
        $userId = $user->id;
        $token =  MyTokenManager::createToken($userId);
        return [
            'message' => 'Login Successfully',
            'token' => $token
        ];
    }
    else
    {
        return [
            'error' => 'E-mail or password is Incorrect'
        ];
    }
   

});

Route::group(['middleware' => 'AuthAPI'], function()
{

    Route::get('/logout', function(Request $request)
    {
        MyTokenManager::removeToken($request);
        return ['message' => 'You are Log Out'];
    });

});