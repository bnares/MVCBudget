<?php

namespace App\Models;

use PDO;

class User extends \Core\Model {
	
	public $errors = [];
	public $success = [];
	
	public function __construct($data=[]){
		
		foreach($data as $key=>$value){
			$this->$key=$value;
		}
	}
	
	public  function save(){
		
		$password_hash = password_hash($this->pass, PASSWORD_DEFAULT);
		$sql = 'INSERT INTO users VALUE (NULL,:username, :password, :email)';
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':username', $this->login, PDO::PARAM_STR);
		$stmt->bindValue(':password', $password_hash, PDO::PARAM_STR);
		$stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
		return $stmt->execute();
	}
	
	public function validate(){
		
		if($this->login==''){
			$this->errors[]="Name must be required";
		}
		
		if(strlen($this->login)<3){
			$this->errors[] = "login Must must consist of at least 3 characters";
		}
		
		if($this->pass != $this->confirmPass){
			
			$this->errors[]="Passwords do not match";
		}
		
		if(strlen($this->pass)<3){
			$this->errors[] = "Password must be longer than 3 characters";
		}
		
		if(preg_match('/[a-z]+/i',$this->pass)==0){
			$this->errors[] = "Password must contain at least one letter";
		}
		
		if(preg_match('/\d+/', $this->pass)==0){
			$this->errors[]="Password must containt at least one number";
		}
		
		if(filter_var($this->email, FILTER_VALIDATE_EMAIL)===false)
		{
			$this->errors[] = "Email is not valid";
		}
		
		if(static::emailExist($this->email)){
			$this->errors[]='Email already exist';
		}
	}
	
	public static function findByEmail($email){
		
		$sql = "SELECT * FROM users WHERE email = :email";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		$stmt->bindValue(':email', $email, PDO::PARAM_STR);
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		$stmt->execute();
		return $stmt->fetch();
	}
	
	public static function emailExist($email){
		
		if(static::findByEmail($email)){
			return true;  //jesli znalazles rekord to zworc false
		}
		return false;  //jesli rekordu nie ma zwroc true
	}
	
	
	
}