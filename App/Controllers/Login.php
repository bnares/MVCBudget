<?php 

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Flash;

class Login extends \Core\Controller {
	
	public function newAction(){
		
		View::renderTemplate('Login\new.html');
	}
	
	public function createAction(){
		
		$user = User::authenticate($_POST['email'], $_POST['password']);
		
		$remember_me = isset($_POST['remember_me']);
		
		if($user){
			
			Auth::login($user, $remember_me);
			if(isset($_SESSION['return_to'])){
				$this->redirect(Auth::getReturnPage());
			}
			
			Flash::addMessage('Login sucessful');
			$this->redirect('BudgetMVC/public/?menu-glowne/mainWindow');
			
		}else{
				Flash::addMessage('Wrong email or Password. Try again', Flash::WARNING);
				View::renderTemplate('Login/new.html', [
				'email'=>$_POST['email'],
				'remember_me'=>$remember_me
				]);                //zmienna prezentowana w twigu jesli logowanie nie wyjdzie 
				
			}
	}
	
	
	public function destroyAction(){
		
		
		Auth::logout();
		$this->redirect('BudgetMVC/public/?login/show-logout-message');
		
		
	}
	
	public function showLogoutMessage(){
		
		Flash::addMessage('Loging out succesfully');
		$this->redirect('BudgetMVC/public/');
	}
	
	
}