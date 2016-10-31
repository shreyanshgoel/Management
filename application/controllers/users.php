<?php

/**
 * The Default Example Controller Class
 *
 * @author Shreyansh Goel
 */
use Shared\Controller as Controller;
use Framework\RequestMethods as RequestMethods;
use Framework\Registry as Registry;

class Users extends Controller {

	/**
	* @before _secure
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
	* @before _secure
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
	* @before _secure
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
	* @before _secure
	*/
    public function import_from_excel($input = NULL){
     		
     	
    }
    

    /**
	* @before _secure
	*/
    public function suppliers($id = -1){

    	$layoutView = $this->getLayoutView();
    	$layoutView->set("seo", Framework\Registry::get("seo"));

    	$layoutView->set('suppliers_nav', 1);

    	$view = $this->getActionView();

        if(RequestMethods::post('add_sc')){

        	$c = new models\Supplier_or_Customer(array(
        		'user_id' => $this->user->id,
        		'type' => '1',
        		'name' => RequestMethods::post('name'),
        		'phone' => RequestMethods::post('phone'),
        		'email' => RequestMethods::post('email'),
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
        	$c->email = RequestMethods::post('email');

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
	* @before _secure
	*/
    public function customers($id = -1){

    	$layoutView = $this->getLayoutView();
    	$layoutView->set("seo", Framework\Registry::get("seo"));

    	$layoutView->set('customers_nav', 1);

    	$view = $this->getActionView();

        if(RequestMethods::post('add_sc')){

        	$c = new models\Supplier_or_Customer(array(
        		'user_id' => $this->user->id,
        		'type' => '2',
        		'name' => RequestMethods::post('name'),
        		'phone' => RequestMethods::post('phone'),
        		'email' => RequestMethods::post('email'),
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
        	$c->email = RequestMethods::post('email');

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
	* @before _secure
	*/
    public function calendar(){
        
        
    }

}
