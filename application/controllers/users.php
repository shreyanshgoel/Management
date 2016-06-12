<?php

/**
 * The Default Example Controller Class
 *
 * @author Faizan Ayubi, Hemant Mann
 */
use Shared\Controller as Controller;
use Framework\RequestMethods as RequestMethods;

class Users extends Controller {

	public function NotLoggedIn(){

		if($this->user){

			header("Location: /");

		}
	}

	/**
	* @before NotLoggedIn
	*/
	public function register(){
		$this->setLayout("layouts/empty");
		;
		if(!$this->user){
			if(RequestMethods::post('register')){
				echo "string";
				$pass = RequestMethods::post("password");
				$cpass = RequestMethods::post("confirm");

				$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
		        $cost=10;
				$salt = sprintf("$2a$%02d\$", $cost) . $salt;
				$crypt = crypt($pass, $salt);

				$user = new models\User(array(
		            "full_name" => RequestMethods::post("full_name"),
		            "email" => RequestMethods::post("email"),
		            "mobile" => RequestMethods::post("mobile"),
		            "password" => $crypt
		        ));
				$exist = models\User::all(array(
					'email = ?' => RequestMethods::post("email")
					));

				echo "string";
				if (empty($exist)){
				
					if($pass == $cpass){

						if($user->validate()){
							
							$user->save();

							$login = models\User::first(array(
								'email = ?' => RequestMethods::post("email")
								));

							$this->user = $login;

							$session = Framework\Registry::get('session');

							if($session->get('guest')){

								$cart = models\Cart::all(array(
									'user_id = ?' => $session->get('guest'),
									'live = ?' => 1
									));

								if(!empty($cart)){

									foreach($cart as $c){

										$c->user_id == $this->user_id;

										$c->save();
									}
								}
							}

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
		}else{

				header("Location: /");
		}

	}


	/**
	* @before NotLoggedIn
	*/

	public function login(){
		$this->setLayout("layouts/empty");

		if(!$this->user){

			if(RequestMethods::post('login')){

				$email = RequestMethods::post("email");
		        $pass = RequestMethods::post("password");
		        
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

		            if (!empty($user) && strcmp($user->password, crypt($pass, $user->password)) == 0){
		            	
			                $this->user = $user;
			                header('Location: /users/dashboard');
			            	            
		            }else{
		            
		                echo "<script>alert('email and password do not match')</script>";
		            } 
		        
		        }else{

		        	echo "<script>alert($login_e)</script>";
		        }
			}
		}else{

				header('Location: /');
		}

		
	}


	public function secure_user(){

		if(!$this->user){

			header("Location: /");

		}
	}

	/**
	* @before secure_user
	*/
    public function dashboard() {
    	$layoutView = $this->getLayoutView();
    	$layoutView->set("seo", Framework\Registry::get("seo"));

    }

    /**
	* @before secure_user
	*/
    public function tables($id = -1) {
    	$layoutView = $this->getLayoutView();
    	$layoutView->set("seo", Framework\Registry::get("seo"));
    	
    	$view = $this->getActionView();

    	$table = models\Table::first(array(
    		'id = ?' => $id,
    		'user_id = ?' => $this->user->id
    		));

    	

    	if(!empty($table)){

    		$entries = models\Entry::all(array(
    			'table_id = ?' => $id
    			));

    		$n1 = $table->column1_name;
    		$n2 = $table->column2_name;
    		$n3 = $table->column3_name;
    		$n4 = $table->column4_name;
    		$n5 = $table->column5_name;
    		$n6 = $table->column6_name;
    		$n7 = $table->column7_name;
    		$n8 = $table->column8_name;
    		$n9 = $table->column9_name; 
    		$n10 = $table->column10_name;

    		$c = "['S No.'";

    		$i = 1;
    		while($i < 11){

    			$n = 'n' . $i;

    			if($$n != NULL){

    				$c = $c . ", '" . $$n . "' ";
    			}

    			$i++;
    		}

    		$c = $c . ']';

    		$view->set('entries', $entries)->set('c', $c);

    		$obj = array();
	        $data = $view->data;

	        if ($data) {
	            foreach ($data as $keys => $values) {
	                switch (gettype($values)) {
	                    case 'object':
	                        if (get_class($values) == "stdClass") {
	                            $obj[$keys] = $values;
	                        } elseif (is_a($values, 'Framework\Model')) {
	                            $obj[$keys] = $values->getJsonData();
	                        } else {
	                            $obj[$keys] = $values;
	                        }
	                        break;
	                    case 'array':
	                        foreach ($values as $key => $value) {
	                            if (gettype($value) == "object") {
	                                if (get_class($value) == "stdClass") {
	                                    $obj[$keys][] = $value;
	                                } elseif (is_a($value, 'Framework\Model')) {
	                                    $obj[$keys][] = $value->getJsonData();
	                                } else {
	                                    $obj[$keys][] = $value;
	                                }
	                            } else{
	                                $obj[$keys] = $values;
	                            }
	                        }
	                        break;

	                    case 'string':
	                    case 'integer':
	                    case 'boolean':
	                        $obj[$keys] = $values;
	                        break;

	                    default:
	                        break;

	                }
	            }
	        }
	        
	        $view->set('json', json_encode($obj['entries'], JSON_PRETTY_PRINT));

    	}else{

    		self::redirect('/404');
    	}


    }

    /**
	* @before secure_user
	*/
    public function create_table() {
    	$layoutView = $this->getLayoutView();
    	$layoutView->set("seo", Framework\Registry::get("seo"));

    	$view = $this->getActionView();

    	$numbers = array(1,2,3,4,5,6,7,8,9,10);

    	$view->set('numbers', $numbers);

    	if(RequestMethods::post('create')){

    		$i = 1;
    		$j = 1;
    		while($i < 11){

    			$name = 'name' . $i;

	    		if(!empty(RequestMethods::post($name))){

	    			$c[$j] = RequestMethods::post($name);
	    			$j++;
	    		}

	    		$i++;
	    	}

	    	while($j < 11){

	    		$c[$j] = NULL;
	    		$j++;
	    	}

	    	$count = models\Table::count(array(
	    		'user_id = ?' => $this->user->id
	    		));
	    	$count++;

    		$table = new models\Table(array(
    			'user_id' => $this->user->id,
    			'table_number' => $count,
    			'table_name' => RequestMethods::post('table_name'),
    			'column1_name' => $c[1],
    			'column2_name' => $c[2],
    			'column3_name' => $c[3],
    			'column4_name' => $c[4],
    			'column5_name' => $c[5],
    			'column6_name' => $c[6],
    			'column7_name' => $c[7],
    			'column8_name' => $c[8],
    			'column9_name' => $c[9],
    			'column10_name' => $c[10]
    			));

    		$count = models\Table::count();
	    	$count++;

	    	$i = 1;

	    	while($i < 11){

	    		$name = 'type' . $i;

	    		if(RequestMethods::post($name) == 1 || RequestMethods::post($name) == 2 || RequestMethods::post($name) == 3){

	    			$c[$i] = RequestMethods::post($name);
	 
	    		}else{
	    			$c[$i] = 1;
	    		}

	    		$i++;

	    	}

    		$type = new models\Table_Type(array(
    			'table_id' => $count,
    			'type1' => $c[1],
    			'type2' => $c[2],
    			'type3' => $c[3],
    			'type4' => $c[4],
    			'type5' => $c[5],
    			'type6' => $c[6],
    			'type7' => $c[7],
    			'type8' => $c[8],
    			'type9' => $c[9],
    			'type10' => $c[10]
    			));

    		if($table->validate()){

    			$table->save();

    			$type->save();

    		}else{
    			echo "Validation Not Good";
    		}
    	}
    }

	/**
	* @before secure_user
	*/

    public function logout(){
        
        $this->setUser(false);

        header("Location: /users/login");
    }

}
