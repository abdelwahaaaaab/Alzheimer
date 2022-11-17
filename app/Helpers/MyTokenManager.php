<?php

namespace App\Helpers;

use App\Models\Client;
use App\Models\Client_Token;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class MyTokenManager {
    public static function createToken($id)
    {
        $token = Str::random(50);
        $hashToken = hash::make($token);
        $user = Client_Token::create(
            [
                'client_id' => $id,
                'token' => $hashToken, 
            ]
        );
        $tokenId = $user->id;

        return "$tokenId|$token";

    }
    public static function currentUser(Request $request)
    {
        $token = $request->bearerToken();
        if(!$token)
        {
            return NULL;
        }
        if(!str_contains($token, '|')){
            return NULL;
        }
        [$tokenId, $tokenStr] = explode('|', $token, 2);
        
        $tokenData = Client_Token::where('id', $tokenId)->first();

        if(Hash::check($tokenStr,$tokenData->token) &&  $tokenData !== NULL)
        {
            $clientData = Client::where('id', $tokenData->client_id)->first();
            return $clientData;
        }
        else
        {
            return NULL;
        }
    }

    public static function removeToken(Request $request)
    {
        $token = $request->bearerToken();
        if(!$token)
        {
            return NULL;
        }
        if(!str_contains($token, '|'))
        {
            return NULL;
        }
        [$tokenId, $tokenStr] = explode('|', $token, 2);
        Client_Token::where('id', $tokenId)->firstorfail()->delete();
    }
}