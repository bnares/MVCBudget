<?php 

namespace App\Controllers;

use \Core\View;
use \App\Auth;

class MenuGlowne extends \App\Controllers\Authenticated {
	
	
	
	
	public function mainWindowAction(){
		
		//$this->requireLogin();   //sprawdzam czy uzytkownik moze wejsc na stone glowne zalogowanego uzytkownika mainWindow.html
		View::renderTemplate('MenuGlowne/mainWindow.html');
	}
	
}