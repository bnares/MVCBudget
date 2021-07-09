<?php 

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

class Login extends \Core\Controller {
	
	public function newAction(){
		
		View::renderTemplate('Login\new.html');
	}
	
	public function createAction(){
		//echo "<br>START";
		//echo "<br>".$_POST['email'];
		$user = User::authenticate($_POST['email'], $_POST['password']);
		if($user){
			
			session_regenerate_id(true); //zmien kod sesji po zalogowaniu dzieki temu zmniejszasz prawdopodobienstwo ataku
			$_SESSION['user_id'] = $user->id;
			$this->redirect('BudgetMVC/public/?menu-glowne/mainWindow');
			
		}else{
				
				View::renderTemplate('Login/new.html', [
				'email'=>$_POST['email']]);                //zmienna prezentowana w twigu jesli logowanie nie wyjdzie 
				
			}
	}
	
	
	public function destroyAction(){
		
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
		$this->redirect('BudgetMVC/public/');
		
	}
	
	
}