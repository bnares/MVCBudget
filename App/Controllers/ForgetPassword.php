<?php

namespace App\Controllers;
use \Core\View;
use \App\Flash;
use \App\Models\User;
use \App\SendMail;
//require ('/App/SendMail.php');

require("C:\\xampp\htdocs\BudgetMVC\PHPMailer\src\PHPMailer.php");
require("C:\\xampp\htdocs\BudgetMVC/PHPMailer/src/SMTP.php");
require("C:\\xampp\htdocs\BudgetMVC/PHPMailer/src/Exception.php");

class ForgetPassword extends \Core\Controller{
	
	
	public function newAction(){
		
		View::renderTemplate('ForgetPassword\new.html');
	}
	
	public function newPasswordAction(){
		
		$this->email = $_POST['email'];
		
		$email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
		
		if(filter_var($email, FILTER_VALIDATE_EMAIL)){
		
			
			if(User::emailExist($email)){
				
				//var_dump("email exist in db: ".$email);
				//$this->validatePassword();
				//$to = "piotr.ostrouch@gmail.com";
				//$subject = "ZMiana Hasla";
				//$message = "Click link below ";
				//$header = "From: twoj@email.com \nContent-Type:".
             //' text/plain;charset="UTF-8"'.
             //"\nContent-Transfer-Encoding: 8bit";
				//mail($to, $subject, $message, $header);
				
				
				$mail = new \PHPMailer\PHPMailer\PHPMailer();

				$mail->IsSMTP();
				$mail->CharSet="UTF-8";
				$mail->Host = "mail.piotrostrouch.pl"; /*   "smtp.gmail.com"Zależne od hostingu poczty*/   //cl11.piotrostrouch.pl
				$mail->SMTPDebug = 1;
				$mail->Port = 465 ; /* Zależne od hostingu poczty, czasem 587 */
				$mail->SMTPSecure = 'ssl'; /* Jeżeli ma być aktywne szyfrowanie SSL */
				$mail->SMTPAuth = true;
				$mail->IsHTML(true);
				$mail->Username = "mybudget@piotrostrouch.pl"; /* login do skrzynki email często adres*/
				$mail->Password = ""; /* Hasło do poczty */
				$mail->setFrom('mybudget@piotrostrouch.pl', 'Piotr Ostrouch'); /* adres e-mail i nazwa nadawcy */
				$mail->AddAddress("piotr.ostrouch@gmail.com"); /* adres lub adresy odbiorców */
				$mail->Subject = "Testowa wiadomość SMTP"; /* Tytuł wiadomości */
				$mail->Body = "Hello, click on the link below to reset the password<br>"."<a href = ".$_SERVER['HTTP_HOST']."/BudgetMVC/public/?change-password/new> click on me to reset the password</a>";


				if(!$mail->Send()) {
				echo "Błąd wysyłania e-maila: " . $mail->ErrorInfo;
				} else {
				echo "Wiadomość została wysłana!";
				
				}

				
				
				
				
				
			}else{
				
				Flash::addMessage('email does not exist. Try again', Flash::INFO);
				View::renderTemplate('ForgetPassword/new.html', [
				'email'=>$email
				]);
			}
		
		}else{
			
				Flash::addMessage('Wrong email. Try again', Flash::WARNING);
				View::renderTemplate('ForgetPassword/new.html', [
				'email'=>$email
				]);
		}
	}
	
	
	public function validatePassword(){
		
		$errors = 0;
		$this->password = $_POST['password'];
		if(strlen($this->password)<3){
				$errors++;
				Flash::addMessage('Password must contain of at least 3 letters', Flash::INFO);
				View::renderTemplate('ForgetPassword/new.html', [
				'email'=>$_POST['email']
				]);
				exit;
		}
		
		if(preg_match('/[a-zA-Z]+/', $this->password)==0){
				
				$errors++;
				Flash::addMessage('Password must contain of at least 1 letter', Flash::INFO);
				View::renderTemplate('ForgetPassword/new.html', [
				'email'=>$_POST['email']
				]);
				exit;
			
		}
		
		if(preg_match('/[0-9]+/', $this->password)==0){
			
				$errors++;
				Flash::addMessage('Password must contain of at least 1 number', Flash::INFO);
				View::renderTemplate('ForgetPassword/new.html', [
				'email'=>$_POST['email']
				]);
				exit;
			
		}
		
		if($this->password != $_POST['passwordConfirm']){
				
				$errors++;
				Flash::addMessage('Passwords are difrent. try again', Flash::INFO);
				View::renderTemplate('ForgetPassword/new.html', [
				'email'=>$_POST['email']
				]);
				exit;
		}
		
		if($errors===0){
			var_dump($_POST);
		}
		
	}
}
