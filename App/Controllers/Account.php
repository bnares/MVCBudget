<?php

namespace App\Controllers;

use \App\Models\User;

class Account extends \Core\Controller 
{
	
	public function validateEmailAction(){
		
		$is_valid = ! User::emailExist($_GET['email']);
		header('Content-Type: application/json');   // ajax metoda weryfikacji czy email juz ostnieje w bazie danych 
		echo json_encode($is_valid);
		//var_dump($_SERVER['HTTP_HOST']);
		
		
	}
}