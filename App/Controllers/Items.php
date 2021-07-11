<?php

namespace App\Controllers;


use \Core\View;
use \App\Auth;

class Items extends \Core\Controller {     //testowa funkcja ktora ogranicza dostep do poszczegolnej strony jesli nie jestes zalogowany
	
	public function indexAction(){
		if(! Auth::isLoggedIn()){
			$this->redirect('BudgetMVC\public\?Login\new');
		}
		
		View::renderTemplate('MenuGlowne/mainWindow.html');
	}
	
}