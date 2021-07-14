<?php

namespace App\Models;

use PDO;
use \App\Token;

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
	
	
	public static function findByID($id){
		
		$sql = "SELECT * FROM users WHERE id = :id";
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		$stmt->execute();
		return $stmt->fetch();
	}
	
	public static function emailExist($email){
		
		if(static::findByEmail($email)){
			return true;  //jesli znalazles rekord to zworc true
		}
		return false;  //jesli rekordu nie ma zwroc false
	}
	
	public static function authenticate($email, $password){
		$user = static::findByEmail($email);
		if($user){
				
			if(password_verify($password, $user->password)){
				return $user;
				
			}
		}
		return false;
		
	}
	
	public function rememberLogin(){
		
		$token = new Token();
		$hashed_token = $token->getHash();
		$this->remember_token = $token->getValue();
		
		$this->expiry_timestamp = time()+60*60*30*24;
		
		$sql = 'INSERT INTO remembered_logins VALUE (:token_hash, :user_id, :expires_at)';
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':token_hash',$hashed_token, PDO::PARAM_STR);
		$stmt ->bindValue(':user_id', $this->id, PDO::PARAM_INT);
		$stmt->bindValue('expires_at',date('Y-m-d H:i:s', $this-> expiry_timestamp), PDO::PARAM_STR);
		return $stmt->execute();
		
	}
	
	
}