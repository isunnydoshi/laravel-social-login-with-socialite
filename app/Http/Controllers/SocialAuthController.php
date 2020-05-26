<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use Auth;
use App\User;

class SocialAuthController extends Controller
{	
	/**
    * Create a redirect method to social auth api.
    *
    * @return void
    */
    public function redirect($provider)
    {
    	return Socialite::driver($provider)->redirect();
    }

    /**
     * Return a callback method from social auth api.
     *
     * @return callback URL from facebook
     */
    public function callback($provider)
    {
    	$userSocial = Socialite::driver($provider)->user();

    	$users = User::where('provider',$provider)
    						->where('provider_id',$userSocial->getId())					   	 
                           ->first();

		if($users){
            Auth::login($users);
            return redirect('/');
        }else{
				$user = User::create([
                'name'          => $userSocial->getName(),
                'email'         => $userSocial->getEmail(),
                'image'         => $userSocial->getAvatar(),
                'provider_id'   => $userSocial->getId(),
                'provider'      => $provider,
            ]);
         	return redirect()->route('home');
        }
    }
}
