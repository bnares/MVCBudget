<?php

namespace App;


use \App\Models\User;
use \App\Flash;
use \App\Models\RememberedLogin;

class Auth{
	
	public static function login($user, $remember_me){
		session_regenerate_id(true); //zmien kod sesji po zalogowaniu dzieki temu zmniejszasz prawdopodobienstwo ataku
		$_SESSION['user_id'] = $user->id;
		if($remember_me){
			
			if($user->rememberLogin()){
				setcookie("remember_me",$user->remember_token, $user->expiry_timestamp, '/');  //kolejno : nazwa ciasteczka, wartosc ciasteczka, czas wygasniecia ciasteczka, '/' - sciezka do ciasteczka. ciasteczo ma byc widoczne wszedzie a nie tylko w folderze gdzie jest zalozone
			}
		}
	}
	
	
	
	public static function logout(){
		
		$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(
			session_name(),
			'',
			time() - 42000,
			$params["path"],
			$params["domain"],
			$params["secure"],
			$params["httponly"]
		);
	}

// Finally, destroy the session.
		session_destroy();
		static::forgetLogin();
		
	}
	
	public static function isLoggedIn(){
		return isset($_SESSION['user_id']);
	}
	
	public static function rememberRequestedPage(){
		$_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
		
	}
	
	public static function getReturnPage(){
		return $_SESSION['return_to'] ?? '/BudgetMVC/public/';
	}
	
	public static function getUser(){
		if(isset($_SESSION['user_id'])){
			//var_dump($_SESSION['user_id']);
			$user = User::findByID($_SESSION['user_id']);
			return $user;
		}else{
			
			return static::loginFromRememberCookie();
		}
		
	}
	
	
	protected static function loginFromRememberCookie(){
		
		$cookie = $_COOKIE['remember_me'] ?? false;
		
		if($cookie){
			$remembered_login = RememberedLogin::findByToken($cookie); //$cookie zawiera numer przed shasowaniem haskowanie $cookir odbywa sie w funkcji findByToken().hash kazdego wyrazu musi byc zawsze taki sam
			
			if($remembered_login && ! $remembered_login->hasExpired()){
				$user = $remembered_login->getUser();
				static::login($user, false);
				return $user;
			}
		}
	}
	
	protected static function forgetLogin(){
		
		$cookie = $_COOKIE['remember_me'] ?? false;
		
		if($cookie){
			$remembered_login = RememberedLogin::findByToken($cookie);
			
			if($remembered_login){
				
				$remembered_login->deleteRow();
			}
			
			setcookie('remember_me', '', time()-3600*24*5, '/');
		}
	}
	
	
	
}