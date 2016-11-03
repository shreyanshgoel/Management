<?php

/**
 * The Default Example Controller Class
 *
 * @author Shreyansh Goel
 */
use Shared\Controller as Controller;
use Framework\RequestMethods as RequestMethods;
use Framework\Registry as Registry;

class Account extends Controller {

	
/*	public function register(){
		$this->setLayout("layouts/empty");
		
		$token = RequestMethods::post('token', '');

		if(RequestMethods::post('register') && $this->verifyToken($token)){

			$pass = RequestMethods::post("password");
			$cpass = RequestMethods::post("confirm");

			$user = new models\User(array(
	            "full_name" => RequestMethods::post("full_name"),
	            "email" => RequestMethods::post("email"),
	            "mobile" => RequestMethods::post("mobile"),
	            "password" => sha1($pass),
	            "live" => true
	        ));
			$exist = models\User::all(array(
				'email = ?' => RequestMethods::post("email")
				));

			if (empty($exist)){
			
				if($pass == $cpass){

					if($user->validate()){
						
						$user->save();

						$login = models\User::first(array(
							'email = ?' => RequestMethods::post("email")
							));

						$this->user = $login;

						self::enroll();

						header("Location: /users/dashboard");

					}else{

						echo "<script>alert('validation not good')</script>";
					}

				}else{
					echo "<script>alert('Passwords do not match')</script>";
				}
			
			}else{
				echo "<script>alert('User exists')</script>";
			}
		}

	}

	*/


	/**
	* @before _session
	* @after _csrfToken
	*/

	public function login(){
		$this->setLayout("layouts/empty");

		$token = RequestMethods::post('token', '');

		if(RequestMethods::post('login') && $this->verifyToken($token)){

			$email = RequestMethods::post("email");
	        $pass = sha1(RequestMethods::post("password"));
	        
	        $login_e = false;
	        
	        if (empty($email)){
	            
	            $login_e = "Empty email";
	        }
	        
	        if (empty($pass)){

	         	$login_e = "Empty password";
	        }
	        
	        if (!$login_e){

	            $user = models\User::first(array(
	                "email = ?" => $email,
	                "live = ?" => true
	            ));

	            if (!empty($user)){

	            	if($user->password == $pass){
	            	
		                $this->user = $user;
		                header('Location: /users/dashboard');
		            
		            }else{

		        		echo "<script>alert('email and password do not match')</script>";    	
		            }
		            	            
	            }else{
	            
	                echo "<script>alert('email does not exist')</script>";
	            } 
	        
	        }else{

	        	echo "<script>alert($login_e)</script>";
	        }
		}
		
	}

	protected function enroll(){

		if(!$this->user){

			header("Location: /users/login");

		}

		//first inventory table
		$table = new models\Table(array(
	    			'user_id' => $this->user->id,
	    			'type' => 'inventory',
	    			'table_number' => '1',
	    			'table_name' => 'sample',
	    			'column1_name' => 'name',
	    			'column2_name' => 'stock quantity',
	    			'column3_name' => 'stock sold',
	    			'column4_name' => 'sales price',
	    			'column5_name' => 'bulk quantity',
	    			'column6_name' => 'sales discount',
	    			'column7_name' => 'item description',
	    			'column8_name' => 'item valuation(stock * sales price)',
	    			'column9_name' => NULL,
	    			'column10_name' => NULL
	    			));

		$table->save();

		header("Location: /users/dashboard");	


	}
}