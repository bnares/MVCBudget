<?php

namespace App\Controllers;
use \App\Auth;

class Authenticated extends \Core\Controller{
	
	
	protected function before(){
		
		$this->requireLogin();   //sprawdzam czy uzytkownik moze wejsc na stone glowne zalogowanego uzytkownika np mainWindow.html. robie to w metodzie before ktora wykonuje sie przed wywolaniem kazdej metody z klasy ktorej uzycie jest przeznaczone tylko dla zalogowanych uzytkownikow
		
	}
}