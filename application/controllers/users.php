<?php

/**
 * The Default Example Controller Class
 *
 * @author Shreyansh Goel
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
		            "password" => $crypt,
		            "live" => true
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
	    			'column2_name' => 'unit',
	    			'column3_name' => 'stock quantity',
	    			'column4_name' => 'stock sold',
	    			'column5_name' => 'sales price',
	    			'column6_name' => 'bulk quantity',
	    			'column7_name' => 'sales discount',
	    			'column8_name' => 'item description',
	    			'column9_name' => 'item valuation(stock * sales price)',
	    			'column10_name' => ''
	    			));

		$table->save();

		$count = models\Table::count();

		$type = new models\Table_Type(array(
	    			'table_id' => $count,
	    			'type1' => '1',
	    			'type2' => '1',
	    			'type3' => '2',
	    			'type4' => '2',
	    			'type5' => '1',
	    			'type6' => '2',
	    			'type7' => '1',
	    			'type8' => '1',
	    			'type9' => '2',
	    			'type10' => ''
	    			));

		header("Location: /users/dashboard");	


	}

	/**
	* @before secure_user
	*/
    public function dashboard() {

    	$layoutView = $this->getLayoutView();
    	$layoutView->set("seo", Framework\Registry::get("seo"));

    	$layoutView->set('dashboard',1);

    	$view = $this->getActionView();

    	$t_count = models\Table::count(array(
    		'user_id = ?' => $this->user->id
    		));

    	$e_count = 0;

    	$tables = models\Table::all(array(
    		'user_id = ?' => $this->user->id
    		));

		$e_count = models\Entry::count(array(
			'user_id = ?' => $this->user->id
			));

    	$view->set('t_count', $t_count)->set('e_count', $e_count)->set('tables', $tables);

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

    		$type_of_table = RequestMethods::post('typeoftable');

    		if($type_of_table == "Inventory"){

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
		    			'type' => 'inventory',
		    			'table_number' => $count,
		    			'table_name' => RequestMethods::post('table_name'),
		    			'column1_name' => 'name',
		    			'column2_name' => 'unit',
		    			'column3_name' => 'stock quantity',
		    			'column4_name' => 'stock sold',
		    			'column5_name' => 'sales price',
		    			'column6_name' => 'bulk quantity',
		    			'column7_name' => 'sales discount',
		    			'column8_name' => 'item description',
		    			'column9_name' => 'item valuation(stock * sales price)',
		    			'column10_name' => ''
		    			));

		    		$table->save();

		    		$view->set('success', 1);
		    		
		    		$count = models\Table::count();

		    		$type = new models\Table_Type(array(
		    			'table_id' => $count,
		    			'type1' => '1',
		    			'type2' => '1',
		    			'type3' => '2',
		    			'type4' => '2',
		    			'type5' => '1',
		    			'type6' => '2',
		    			'type7' => '1',
		    			'type8' => '1',
		    			'type9' => '2',
		    			'type10' => ''
		    			));

		    		$type->save();
		    	}

    		}

    		if($type_of_table == "Other"){
	    		
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

		    	
		    	$flag = 0;

	    
		    	if(empty($exist)){

		    		$table = new models\Table(array(
		    			'user_id' => $this->user->id,
		    			'type' => RequestMethods::post('typeoftable'),
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
		    }else{
		    	$view->set('invalid', 1);
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

    		$type_of_table = RequestMethods::post('typeoftable');

    		if($type_of_table == 'Inventory'){

    			if(!empty(RequestMethods::post('table_name'))){

		    		$table_name = RequestMethods::post('table_name');

		    	}else{

		    		$table_name = $edit_table->table_name;
		    	}

    			$edit_table->table_name = $table_name;


	    		$edit_table->save();

	    		self::redirect('/users/inventory/' . $edit_table->id . '?edit_table_success=1');

    		}


    		if($type_of_table == 'Other'){


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

	    			self::redirect('/users/other_tables/' . $edit_table->id . '?edit_table_success=1');

	    		}else{
	    			echo "Validation Not Good";
	    		}
	    	}
    
    	}
    }

	/**
	* @before secure_user
	*/
    public function search_in_tables(){
        
    	$view = $this->getActionView();

        if(RequestMethods::get('search')){

        	$search = RequestMethods::get('search');

        	$search_words = explode(' ', $search);

        	$i = 1;

        	$j = 1;

        	while($i < 11){

        		$len = count($search_words);

        		$j = 0;

        		while ($j < $len) {
        			
		        	$result = models\Entry::all(array(
		        		'entry' . $i . ' Like ?' => '%' . $search_words[$j] . '%'
		        		));

		        	foreach ($result as $r){

		        		$t = models\Table::first(array(
		        			'id = ?' => $r->table_id,
		        			'user_id = ?' => $this->user->id 
		        			));

		        		if(!empty($t)){

		        			$search_result[$j++] = $r->id;

		        		}

	        		}

	        		$j++;

		        }

	        	$i++;

        	}

        	$numbers = array(1,2,3,4,5,6,7,8,9,10);	

        	$view->set('numbers', $numbers)->set('search', $search);

        	if(isset($search_result)){
        		$view->set('search_result', $search_result);
        	}else{
        		$view->set('search_result', '');
        	}
        }
    }


    /**
	* @before secure_user
	*/
    public function profile($success = -1){

    	$layoutView = $this->getLayoutView();
    	$layoutView->set("seo", Framework\Registry::get("seo"));

    	$layoutView->set('profile',1);

    	$view = $this->getActionView();

    	if(RequestMethods::post('profile_update')){

    		$user = models\User::first(array(
    			'id = ?' => $this->user->id
    			));

    		$exist = models\User::first(array(
    			'id = ?' => ['$ne' => $this->user->id],
    			'email = ?' => RequestMethods::post('email')
    			));

    		if(empty($exist)){

	    		$user->full_name = RequestMethods::post('full_name');

	    		if(RequestMethods::post('email')){

	    			$user->email = RequestMethods::post('email');
	    			$user->email_confirm = 0;

	    		}

	    		$user->designation = RequestMethods::post('designation');
	    		$user->company_name = RequestMethods::post('company_name');
	    		$user->location = RequestMethods::post('location');

	    		if($user->validate()){

	    			$user->save();
	    			self::redirect('/users/profile/1');
	    		}else{

	    			$view->set('validation', 1);
	    		}
	    	}else{

	    		$view->set('exist', 1);
	    	}

    	}

    	$view->set('update_success', $success);


    	$cp = -1;

		if(RequestMethods::post('change_password')){

			$cp = 0;

			$old = RequestMethods::post('old');

			$c = strcmp($this->user->password, crypt($old, $this->user->password));

			if($c == 0){

				$pass = RequestMethods::post('new');
				$confirm = RequestMethods::post('confirm');

				if($pass == $confirm){

					$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
			        $cost=10;
					$salt = sprintf("$2a$%02d\$", $cost) . $salt;
					$crypt = crypt($pass, $salt);

					$user = models\User::first(array('id = ?' => $this->user->id));

					$user->password = $crypt;

					$user->save();

					$cp = 1;

				}else{

					$cp = 3;
				}

			}else{

				$cp = 2;
			}


		}

		$view->set('cp', $cp);
       
        
    }

    /**
	* @before secure_user
	*/
    public function edit($id = -1) {
    	
    	$view = $this->getActionView();

    	$entry = models\Entry::first(array(
    		'id = ?' => $id,
    		'user_id = ?' => $this->user->id
    		));

    	if(!empty($entry)){
    		
			$data = array($entry, $table);

			$view->set($data);
    		
    	}else{

			$view->set(false);
		}
 

    }
  

    /**
	* @before secure_user
	*/
    public function get_entries($id = -1) {
    	$layoutView = $this->getLayoutView();
    	$layoutView->set("seo", Framework\Registry::get("seo"));
    	
    	$view = $this->getActionView();

    	$table = models\Table::first(array(
    		'id = ?' => $id,
    		'user_id = ?' => $this->user->id
    		));

    	if(RequestMethods::post('savechanges')){

    		$entry = models\Entry::first(array(
    			'id = ?' => RequestMethods::post('edit_entry_number'),
    			'user_id = ?' => $this->user->id
    			));

    		if(!empty($entry)){
	    		
	    	
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
	    						'user_id' => $this->user->id,
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
    			'id = ?' => RequestMethods::post('delete'),
    			'user_id = ?' => $this->user->id 
    			));

    		if(!empty($entry)){
	    
    			$entry->delete();

    			$view->set('delete_success', 1);

	    	}
    	}

    	if(RequestMethods::post('delete_table')){

    		$del_table = models\Table::first(array(
    			'id = ?' => RequestMethods::post('delete_table'),
    			'user_id = ?' => $this->user->id
    			));

    		if(!empty($del_table)){

    			$del_table->delete();

    			self::redirect('/users/dashboard');
    		}

    	}

    	if(RequestMethods::post('allow-add-operation')){

    		$number = RequestMethods::post('number-of-columns');

    		$view->set('allowed', $number);
    	}

    	if(RequestMethods::post('try-add-operation')){



    		if(!empty($table)){

    			$work = -1;

    			$i = 1;

    			$flag = 0;

    			$exist = models\Operation::first(array(
    				'result_col = ?' => RequestMethods::post('result'),
    				'table_id = ?' => $id
    				));


    			if(RequestMethods::post('result') && empty($exist)){
	    		
	    			while($i < 10){

	    				$j = $i +1;
	    			
	    				if(!empty(RequestMethods::post('o_col_' . $i)) && 
	    					!empty(RequestMethods::post('o_col_' . $j)) && 
	    					RequestMethods::post('o_col_' . $i) != RequestMethods::post('result')&& 
	    					RequestMethods::post('o_col_' . $j) != RequestMethods::post('result')){

	    					if(RequestMethods::post('o_' . $i) == 1 ||
	    					   RequestMethods::post('o_' . $i) == 2 ||
	    					   RequestMethods::post('o_' . $i) == 3 ||
	    					   RequestMethods::post('o_' . $i) == 4){
		    					$op[$i] = RequestMethods::post('o_' . $i);
		    					$flag++;
		    				}else{

		    					$op[$i] = -1;
		    				}
	    				}else{

		    				$op[$i] = -1;
		    			}

		    			$i++;

	    			}
	    		}

    			if($flag != 0){
    				
    				$op_add = new models\Operation(array(
    					'table_id' => $id,
    					'o_col_1' => RequestMethods::post('o_col_1'),
    					'operation_1' => $op[1],
    					'o_col_2' => RequestMethods::post('o_col_2'),
    					'operation_2' => $op[2],
    					'o_col_3' => RequestMethods::post('o_col_3'),
    					'operation_3' => $op[3],
    					'o_col_4' => RequestMethods::post('o_col_4'),
    					'operation_4' => $op[4],
    					'o_col_5' => RequestMethods::post('o_col_5'),
    					'operation_5' => $op[5],
    					'o_col_6' => RequestMethods::post('o_col_6'),
    					'operation_6' => $op[6],
    					'o_col_7' => RequestMethods::post('o_col_7'),
    					'operation_7' => $op[7],
    					'o_col_8' => RequestMethods::post('o_col_8'),
    					'operation_8' => $op[8],
    					'o_col_9' => RequestMethods::post('o_col_9'),
    					'result_col' => RequestMethods::post('result')
    					));

    				if($op_add->validate()){
    					$op_add->save();
    					$view->set('add_operation_success', 1);
    					$work++;
    				}
    				
    			}
    		}

    		if($work == -1){

    			$view->set('add_operation_error', 1);
    		}
    	}

    	if(RequestMethods::post('delete_operation')){

    		if(!empty($table)){

    			$op = models\Operation::first(array(
    				'id = ?' => RequestMethods::post('delete_operation'),
    				'table_id = ?' => $id
    				));

    			if(!empty($op)){

    				$op->delete();

    				$view->set('delete_operation_success', 1);
    			}
    		}
    	}

    	if(RequestMethods::post('excel_add')){
    		if(!empty($table)){
	    		if(!empty($_FILES['sheet']['name'])){

	    			$target_file = APP_PATH . '/public/uploads/sheets/' . $this->user->id;

	    			if($_FILES['sheet']['size'] < 5000000){

	    				$ext = pathinfo($_FILES["sheet"]["name"],PATHINFO_EXTENSION);

	    				move_uploaded_file($_FILES['sheet']["tmp_name"], $target_file . '.' . $ext);
	    			}
	  

		     		$excel = new PhpExcelReader;

		     		$file = $target_file . '.' . $ext;

					$excel->read($file);

					$sheet = $excel->sheets[0];

		     		$i = 0;

					$x = 1;
					  
				  	while($x <= $sheet['numRows']) {
				    
				    	$y = 1;

				    	$flag = 0;
				    	
				    	$j = 0;

				    	while($y <= $sheet['numCols']) {
				    
					      $sheet_data[$i][$j++] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
					      $y++;

					      $flag = 1;
				    
				    	}

				    	if($flag = 1){
				    		$i++;
				    	}
				    
				    $x++;
				  	
				  	}

				  	$k = 0;

				  	while($k <= $i){

				  		$i = 1;

				  		$flag = 0;

				  		while($i <= 10){
				  			
				  			if(isset($sheet_data[$k][$i])){

				  				$entry[$i] = $sheet_data[$k][$i];
				  				$flag = 1;
				  			}else{

				  				$entry[$i] = NULL;

				  			}

				  			$i++;

				  		}

				  		if($flag == 1){

				  			$e = new models\Entry(array(
				  				'table_id' => $table->id,
				  				'entry1' => $entry[1],
				  				'entry2' => $entry[2],
				  				'entry3' => $entry[3],
				  				'entry4' => $entry[4],
				  				'entry5' => $entry[5],
				  				'entry6' => $entry[6],
				  				'entry7' => $entry[7],
				  				'entry8' => $entry[8],
				  				'entry9' => $entry[9],
				  				'entry10' => $entry[10]
				  				));

				  			$e->save();
				  		}

				  		$k++;

				  	}

				}
			}

    	}

    	if(!empty($table)){

    		$entries = models\Entry::all(array(
    			'table_id = ?' => $id
    			));

    		$operations = models\Operation::all(array(
    			'table_id = ?' => $id
    			));

    		$numbers = array(1,2,3,4,5,6,7,8,9,10);

    		$view->set('table', $table)->set('entries', $entries)->set('numbers', $numbers)->set('operations', $operations);
    	}else{

    		self::redirect('/404');
    	}


    }

    /**
	* @before secure_user
	*/
    public function import_from_excel($input = NULL){
     		
     	
    }


    /**
	* @before secure_user
	*/
    public function inventory($id = -1){

    	$layoutView = $this->getLayoutView();
    	$layoutView->set("seo", Framework\Registry::get("seo"));

    	$layoutView->set('inventory',1);

    	$view = new Framework\View(array(
                    "file" => APP_PATH . "/application/views/users/other_tables.html"
                ));

        $this->actionView = $view;

    	self::get_entries($id);

    	$table = models\Table::first(array(
    		'id = ?' => $id,
    		'user_id = ?' => $this->user->id
    		));

    	$layoutView->set('table_name', $table->table_name);

        $view->set('outer', 'inventory_tables');
        
    }

    /**
	* @before secure_user
	*/
    public function other_tables($id = -1){

    	$layoutView = $this->getLayoutView();
    	$layoutView->set("seo", Framework\Registry::get("seo"));

    	$layoutView->set('other_tables', 1);

    	$view = new Framework\View(array(
                    "file" => APP_PATH . "/application/views/users/other_tables.html"
                ));

        $this->actionView = $view;

    	self::get_entries($id);

    	$table = models\Table::first(array(
    		'id = ?' => $id,
    		'user_id = ?' => $this->user->id
    		));

    	$layoutView->set('table_name', $table->table_name);

        $view->set('outer', 'other_tables');
        
    } 

    /**
	* @before secure_user
	*/
    public function edit_sc($id = -1) {
    	
    	$view = $this->getActionView();

    	$edit_sc = models\Supplier_or_Customer::all(array(
    		'id = ?' => $id,
    		'user_id = ?' => $this->user->id
    		));

    	if(!empty($edit_sc)){

    		$view->set($edit_sc);

		}else{

			$view->set(false);
		}

    }

    /**
	* @before secure_user
	*/
    public function states() {
    	
    	$view = $this->getActionView();

    	$states = models\State::all();

    	$view->set($states);

    }

    /**
	* @before secure_user
	*/
    public function suppliers($id = -1){

    	$view = new Framework\View(array(
                    "file" => APP_PATH . "/application/views/users/supplier_or_customer.html"
                ));

        $this->actionView = $view;

        if(RequestMethods::post('add_sc')){

        	$c = new models\Supplier_or_Customer(array(
        		'user_id' => $this->user->id,
        		'type' => '1',
        		'name' => RequestMethods::post('name'),
        		'phone' => RequestMethods::post('phone'),
        		'state' => RequestMethods::post('state'),
        		'address' => RequestMethods::post('address')
        		));

        	if($c->validate()){

        		$c->save();
        		$view->set('add_success', 1);
        	}
        }

        if(RequestMethods::post('edit_sc')){

        	$c = models\Supplier_or_Customer::first(array(
        		'id = ?' => RequestMethods::post('edit_sc_id'),
        		'user_id' => $this->user->id,
        		'type' => '1',
        		));

        	$c->name = RequestMethods::post('name');
        	$c->phone = RequestMethods::post('phone');
        	$c->state = RequestMethods::post('state');
        	$c->address = RequestMethods::post('address');

        	if($c->validate()){

        		$c->save();
        		$view->set('edit_success', 1);
        	}
        }

        if(RequestMethods::post('delete')){

        	$c = models\Supplier_or_Customer::first(array(
        		'id = ?' => RequestMethods::post('delete'),
        		'user_id' => $this->user->id,
        		'type' => '1',
        		));

        	if(!empty($c)){

        		$c->delete();
        		$view->set('delete_success', 1);
        	}
        }

        $table = models\Supplier_or_Customer::all(array(
        	'user_id = ?' => $this->user->id,
        	'type = ?' => '1'
        	));

        $states = models\State::all();

        $view->set('outer', 'suppliers')->set('table', $table)->set('states', $states);
        
    } 

    /**
	* @before secure_user
	*/
    public function customers($id = -1){

    	$view = new Framework\View(array(
                    "file" => APP_PATH . "/application/views/users/supplier_or_customer.html"
                ));

        $this->actionView = $view;

        if(RequestMethods::post('add_sc')){

        	$c = new models\Supplier_or_Customer(array(
        		'user_id' => $this->user->id,
        		'type' => '2',
        		'name' => RequestMethods::post('name'),
        		'phone' => RequestMethods::post('phone'),
        		'state' => RequestMethods::post('state'),
        		'address' => RequestMethods::post('address')
        		));

        	if($c->validate()){

        		$c->save();
        		$view->set('add_success', 1);
        	}
        }

        if(RequestMethods::post('edit_sc')){

        	$c = models\Supplier_or_Customer::first(array(
        		'id = ?' => RequestMethods::post('edit_sc_id'),
        		'user_id' => $this->user->id,
        		'type' => '2',
        		));

        	$c->name = RequestMethods::post('name');
        	$c->phone = RequestMethods::post('phone');
        	$c->state = RequestMethods::post('state');
        	$c->address = RequestMethods::post('address');

        	if($c->validate()){

        		$c->save();
        		$view->set('edit_success', 1);
        	}
        }

        if(RequestMethods::post('delete')){

        	$c = models\Supplier_or_Customer::first(array(
        		'id = ?' => RequestMethods::post('delete'),
        		'user_id' => $this->user->id,
        		'type' => '2',
        		));

        	if(!empty($c)){

        		$c->delete();
        		$view->set('delete_success', 1);
        	}
        }

        $table = models\Supplier_or_Customer::all(array(
        	'user_id = ?' => $this->user->id,
        	'type = ?' => '2'
        	));

        $states = models\State::all();

        $view->set('outer', 'customers')->set('table', $table)->set('states', $states);
        
    } 

    /**
	* @before secure_user
	*/
    public function get_inventory_items($id = -1) {
    	
    	$view = $this->getActionView();

    	$items = models\Entry::all(array(
    		'table_id = ?' => $id,
    		'user_id = ?' => $this->user->id
    		));

    	if(!empty($items)){

    		$view->set($items);

		}else{

			$view->set(false);
		}
    	

    }

    /**
	* @before secure_user
	*/
    public function get_item_quantity_and_price($id = -1) {
    	
    	$view = $this->getActionView();


		$quantity = models\Entry::all(array(
		'id = ?' => $id,
		'user_id = ?' => $this->user->id
		));

    	if(!empty($quantity)){

    		$view->set($quantity);

		}else{

			$view->set(false);
		}
	
    	

    }

    /**
	* @before secure_user
	*/
    public function purchase_invoice($id = -1){

    	$view = new Framework\View(array(
                    "file" => APP_PATH . "/application/views/users/purchase_or_sales.html"
                ));

        $this->actionView = $view;

        if(RequestMethods::post('create_ps')){

        	$count = models\Purchase_Invoice::count(array('user_id = ?' => $this->user->id));

        	$count++;

        	$supplier = models\Supplier_or_Customer::first(array(
        		'id = ?' => RequestMethods::post('supplier_id'),
        		'user_id = ?' => $this->user->id,
        		'type = ?' => 1
        		));

        	if(!empty($supplier)){
	        	
	        	$item_ids = RequestMethods::post('item_id');
	        	$price = RequestMethods::post('price');
	        	$quantity = RequestMethods::post('quantity');

	        	if(!empty($item_ids)){

		        	foreach($item_ids as $key => $item_id){

			        	$item = models\Entry::first(array(
			        		'id = ?' => $item_id,
			        		'user_id = ?' => $this->user->id
			        		));

			        	$id_array = array();
			        	$q_array = array();
			        	$p_array = array();

		        		if(!empty($item)){

		        			array_push($id_array, $item_id);
		        			array_push($q_array, $quantity[$key]);
		        			array_push($p_array, $price[$key]);

		        			$item->quantity = $item->quantity + $quantity[$key];
		        			$item->save();

			        	}
			        }

			        if(!empty($id_array) && !empty($q_array) && !empty($p_array)){
				        	
			        	$c = new models\Purchase_Invoice(array(
			        		'invoice_id' => $count,
			        		'user_id' => $this->user->id,
			        		'supplier_id' => RequestMethods::post('supplier_id'),
			        		'item_id' => $id_array,
			        		'quantity' => $q_array,
			        		'price' => $p_array
			        		));

			        	if($c->validate()){

			        		$c->save();
			        		$view->set('add_success', 1);
			        	}
				    }
			    }else{
			    	echo "no items";
			    }
	        }
        }

        if(RequestMethods::post('edit_sc')){

        	$c = models\Supplier_or_Customer::first(array(
        		'id = ?' => RequestMethods::post('edit_sc_id'),
        		'user_id' => $this->user->id,
        		'type' => '2',
        		));

        	$c->name = RequestMethods::post('name');
        	$c->phone = RequestMethods::post('phone');
        	$c->state = RequestMethods::post('state');
        	$c->address = RequestMethods::post('address');

        	if($c->validate()){

        		$c->save();
        		$view->set('edit_success', 1);
        	}
        }

        if(RequestMethods::post('delete')){

        	$c = models\Supplier_or_Customer::first(array(
        		'id = ?' => RequestMethods::post('delete'),
        		'user_id' => $this->user->id,
        		'type' => '2',
        		));

        	$c->name = RequestMethods::post('name');
        	$c->phone = RequestMethods::post('phone');
        	$c->state = RequestMethods::post('state');
        	$c->address = RequestMethods::post('address');

        	if($c->validate()){

        		$c->save();
        		$view->set('edit_success', 1);
        	}
        }

        $inventory = models\Table::all(array(
        	'user_id = ?' => $this->user->id,
        	'type = ?' => 'inventory'
        	));

        $s_or_c = models\Supplier_or_Customer::all(array(
        	'user_id = ?' => $this->user->id,
        	'type = ?' => '1'
        	));

        $table = models\Purchase_Invoice::all(array(
        	'user_id = ?' => $this->user->id,
        	));

        $view->set('outer', 'purchase')->set('table', $table)->set('inventory', $inventory)->set('s_or_c', $s_or_c);
        
    } 

    /**
	* @before secure_user
	*/
    public function sales_invoice($id = -1){

    	$view = new Framework\View(array(
                    "file" => APP_PATH . "/application/views/users/purchase_or_sales.html"
                ));

        $this->actionView = $view;

        if(RequestMethods::post('add_sc')){

        	$c = new models\Supplier_or_Customer(array(
        		'user_id' => $this->user->id,
        		'type' => '2',
        		'name' => RequestMethods::post('name'),
        		'phone' => RequestMethods::post('phone'),
        		'state' => RequestMethods::post('state'),
        		'address' => RequestMethods::post('address')
        		));

        	if($c->validate()){

        		$c->save();
        		$view->set('add_success', 1);
        	}
        }

        if(RequestMethods::post('edit_sc')){

        	$c = models\Supplier_or_Customer::first(array(
        		'id = ?' => RequestMethods::post('edit_sc_id'),
        		'user_id' => $this->user->id,
        		'type' => '2',
        		));

        	$c->name = RequestMethods::post('name');
        	$c->phone = RequestMethods::post('phone');
        	$c->state = RequestMethods::post('state');
        	$c->address = RequestMethods::post('address');

        	if($c->validate()){

        		$c->save();
        		$view->set('edit_success', 1);
        	}
        }

        $inventory = models\Table::all(array(
        	'user_id = ?' => $this->user->id,
        	'type = ?' => 'inventory'
        	));

        $s_or_c = models\Supplier_or_Customer::all(array(
        	'user_id = ?' => $this->user->id,
        	'type = ?' => '1'
        	));

        $table = models\Sales_Invoice::all(array(
        	'user_id = ?' => $this->user->id,
        	), array('DISTINCT invoice_id'));

        $view->set('outer', 'sales')->set('table', $table)->set('inventory', $inventory)->set('s_or_c', $s_or_c);
        
    } 

	/**
	* @before secure_user
	*/
    public function mailbox(){
        
        
    }

    /**
	* @before secure_user
	*/
    public function inbox(){
        
        
    }

    /**
	* @before secure_user
	*/
    public function compose(){
        
        
    }

    /**
	* @before secure_user
	*/
    public function email_detail(){
        
        
    }
 
    /**
	* @before secure_user
	*/
    public function calendar(){
        
        
    }
 

	/**
	* @before secure_user
	*/
    public function logout(){
        
        $this->setUser(false);

        header("Location: /");
    }

}
