<?php

namespace App;

class Flash{
	
	const WARNING = 'warning';
	
	const SUCCESS = 'success';
	
	const INFO = 'info';
	
	public static function addMessage($message, $type = 'success'){
		
		if(!isset($_SESSION['flash_notifications'])){
			
			$_SESSION['flash_notifications']=[];
		}
		
		$_SESSION['flash_notifications'][]=['mes'=>$message,
		'type'=>$type];
	}
	
	
	public static function getMessage(){
		
		if(isset($_SESSION['flash_notifications'])){
			$message = $_SESSION['flash_notifications'];
			unset($_SESSION['flash_notifications']);
			return $message;
		}
	}
	
}