<?php 

namespace App\Controllers;

use \Core\View;

class MenuGlowne extends \Core\Controller{
	
	public function mainWindowAction(){
		
		View::renderTemplate('MenuGlowne/mainWindow.html');
	}
	
}