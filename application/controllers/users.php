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

    	$view = $this->getActionView();

    	$t_count = models\Table::count(array(
    		'user_id = ?' => $this->user->id
    		));

    	$e_count = 0;

    	$tables = models\Table::all(array(
    		'user_id = ?' => $this->user->id
    		));

    	foreach($tables as $t){

    		$e = models\Entry::count(array(
    			'table_id = ?' => $t->id
    			));

    		$e_count = $e_count + $e;
    	}

    	$view->set('t_count', $t_count)->set('e_count', $e_count)->set('tables', $tables);

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

    	if(RequestMethods::post('savechanges')){

    		$entry = models\Entry::first(array(
    			'id = ?' => RequestMethods::post('edit_entry_number')
    			));

    		if(!empty($entry)){
	    		
	    		$t = models\Table::first(array(
	    			'id = ?' => $entry->table_id,
	    			'user_id = ?' => $this->user->id
	    			));

	    		if(!empty($t)){

	    			$entry->entry1 = RequestMethods::post('edit_entry1');
	    			$entry->entry2 = RequestMethods::post('edit_entry2');
	    			$entry->entry3 = RequestMethods::post('edit_entry3');
	    			$entry->entry4 = RequestMethods::post('edit_entry4');
	    			$entry->entry5 = RequestMethods::post('edit_entry5');
	    			$entry->entry6 = RequestMethods::post('edit_entry6');
	    			$entry->entry7 = RequestMethods::post('edit_entry7');
	    			$entry->entry8 = RequestMethods::post('edit_entry8');
	    			$entry->entry9 = RequestMethods::post('edit_entry9');
	    			$entry->entry10 = RequestMethods::post('edit_entry10');

	    			$entry->save();
	    			$view->set('edit_success', 1);
	    		}
	    	}
    	}

    	if(RequestMethods::post('save')){

    		if(!empty($table)){

    			$n = RequestMethods::post('row-number');
    			$i = 1;
    			$j = 0;
    			while($i < $n){

    				if( !empty(RequestMethods::post('entry' . $i . '_1')) || 
    					!empty(RequestMethods::post('entry' . $i . '_2')) || 
    					!empty(RequestMethods::post('entry' . $i . '_3')) ||
    					!empty(RequestMethods::post('entry' . $i . '_4')) || 
    					!empty(RequestMethods::post('entry' . $i . '_5')) || 
    					!empty(RequestMethods::post('entry' . $i . '_6')) || 
    					!empty(RequestMethods::post('entry' . $i . '_7')) || 
    					!empty(RequestMethods::post('entry' . $i . '_8')) || 
    					!empty(RequestMethods::post('entry' . $i . '_9')) || 
						!empty(RequestMethods::post('entry' . $i . '_10'))
						){

    						$entry = new models\Entry(array(
	    						'table_id' => $id,
	    						'entry1' => RequestMethods::post('entry' . $i . '_1'),
	    						'entry2' => RequestMethods::post('entry' . $i . '_2'),
	    						'entry3' => RequestMethods::post('entry' . $i . '_3'),
	    						'entry4' => RequestMethods::post('entry' . $i . '_4'),
	    						'entry5' => RequestMethods::post('entry' . $i . '_5'),
	    						'entry6' => RequestMethods::post('entry' . $i . '_6'),
	    						'entry7' => RequestMethods::post('entry' . $i . '_7'),
	    						'entry8' => RequestMethods::post('entry' . $i . '_8'),
	    						'entry9' => RequestMethods::post('entry' . $i . '_9'),
	    						'entry10' => RequestMethods::post('entry' . $i . '_10'),

	    					));

    						$entry->save();
    						$j++;

						}
    				
    				$i++;
    			}

    			$view->set('add_row_success', 1)->set('number_of_rows', $j);
    		}
    	}

    	if(RequestMethods::post('delete')){

    		$entry = models\Entry::first(array(
    			'id = ?' => RequestMethods::post('delete')
    			));

    		if(!empty($entry)){

	    		$t = models\Table::first(array(
	    			'id = ?' => $entry->table_id,
	    			'user_id = ?' => $this->user->id
	    			));


	    		if(!empty($t)){

	    			$entry->delete();

	    			$view->set('delete_success', 1);
	    		}
	    	}
    	}

    	if(!empty($table)){

    		$entries = models\Entry::all(array(
    			'table_id = ?' => $id
    			));

    		$view->set('table', $table)->set('entries', $entries);
    	}else{

    		self::redirect('/404');
    	}


    }

    /**
	* @before secure_user
	*/
    public function edit($id = -1) {
    	
    	$view = $this->getActionView();

    	$entry = models\Entry::first(array(
    		'id = ?' => $id
    		));

    	if(!empty($entry)){

    		$table = models\Table::first(array(
    			'id = ?' => $entry->table_id,
    			'user_id = ?' => $this->user->id
    			));

    		if(!empty($table)){

    			$data = array($entry, $table);

    			$view->set($data);

    		}else{

    			$view->set(false);
    		}

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

	    	$exist = models\Table::first(array(
	    		'user_id = ?' => $this->user->id,
	    		'table_name = ?' => RequestMethods::post('table_name')
	    		));

	    	if(empty($exist)){

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
	    			$view->set('success', 1);

	    		}else{
	    			echo "Validation Not Good";
	    		}
	    	}else{

	    		$view->set('exist', 1);
	    	}
    	}
    }

    /**
	* @before secure_user
	*/
	public function edit_table($id = -1) {
    	
    	$layoutView = $this->getLayoutView();
    	$layoutView->set("seo", Framework\Registry::get("seo"));

    	$view = $this->getActionView();

    	$edit_table = models\Table::first(array(
    		'id = ?' => $id,
    		'user_id = ?' => $this->user->id
    		));

    	if(!empty($edit_table)){

    		$numbers = array(1,2,3,4,5,6,7,8,9,10);

    		$type = models\Table_Type::first(array(
    			'table_id = ?' => $id
    			));

	    	$view->set('numbers', $numbers)->set('edit_table', $edit_table)->set('type', $type);

    	}
    	

    	if(RequestMethods::post('savechanges') && !empty($edit_table)){


    		$e_c1 = $edit_table->column1_name;
			$e_c2 = $edit_table->column2_name;
			$e_c3 = $edit_table->column3_name;
			$e_c4 = $edit_table->column4_name;
			$e_c5 = $edit_table->column5_name;
			$e_c6 = $edit_table->column6_name;
			$e_c7 = $edit_table->column7_name;
			$e_c8 = $edit_table->column8_name;
			$e_c9 = $edit_table->column9_name;
			$e_c10 = $edit_table->column10_name;

    		$i = 1;
    		$j = 1;
    		while($i < 11){

    			$name = 'name' . $i;
    			$e_c = 'e_c' . $i;

	    		if(!empty(RequestMethods::post($name))){

	    			$c[$j] = RequestMethods::post($name);
	    			$j++;
	    		}else{

	    			if(!empty($$e_c)){

	    				$c[$j] = $$e_c;
	    				$j++;

	    			}
	    		}
    			
    			$i++;
	    	}

	    	while($j < 11){

	    		$c[$j] = NULL;
	    		$j++;
	    	}

	    	if(!empty(RequestMethods::post('table_name'))){

	    		$table_name = RequestMethods::post('table_name');

	    	}else{

	    		$table_name = $edit_table->table_name;
	    	}

    		$edit_table->table_name = $table_name;
    		$edit_table->column1_name = $c[1];
    		$edit_table->column2_name = $c[2];
    		$edit_table->column3_name = $c[3];
    		$edit_table->column4_name = $c[4];
    		$edit_table->column5_name = $c[5];
    		$edit_table->column6_name = $c[6];
    		$edit_table->column7_name = $c[7];
    		$edit_table->column8_name = $c[8];
    		$edit_table->column9_name = $c[9];
    		$edit_table->column10_name = $c[10];

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

    		$type->type1 = $c[1];
			$type->type2 = $c[2];
    		$type->type3 = $c[3];
    		$type->type4 = $c[4];
    		$type->type5 = $c[5];
    		$type->type6 = $c[6];
    		$type->type7 = $c[7];
    		$type->type8 = $c[8];
    		$type->type9 = $c[9];
    		$type->type10 = $c[10];


    		if($edit_table->validate()){

    			$edit_table->save();

    			$type->save();

    			self::redirect('/users/tables/' . $edit_table->id . '?edit_table_success=1');

    		}else{
    			echo "Validation Not Good";
    		}
    
    	}
    }



	/**
	* @before secure_user
	*/
    public function search_in_tables(){
        
        
    }

    /**
	* @before secure_user
	*/
    public function analytics(){
        
        
    }

    /**
	* @before secure_user
	*/
    public function profile(){
        
        
    }
 

	/**
	* @before secure_user
	*/
    public function logout(){
        
        $this->setUser(false);

        header("Location: /");
    }

}
