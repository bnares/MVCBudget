<?php 

namespace App\Controllers;
use \App\Auth;
use \Core\View;

class DodajPrzychod extends \App\Controllers\Authenticated{
	
	
	
	public function displayAction(){
		
		
		View::renderTemplate('DodajPrzychod/przychod.html');
		
		
	}
	
	
	
}