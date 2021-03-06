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

        if(RequestMethods::get('theme')){

            $user = models\User::first(array(
                'id = ?' => $this->user->id
                ));

            if($user){

                $user->theme_color = RequestMethods::get('theme');
                $user->save();
            }

            $this->redirect('/users/dashboard');
        }
    	
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
		        		'entry' . $i . ' = ?' => new \MongoDB\BSON\Regex($search_words[$j], 'i')
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

        $cp = 1;

        $view->set('update_success', $success);

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

                if(RequestMethods::post('change_email')){

                    $user->tmp_email = null;
                }

	    		if(RequestMethods::post('email') != $user->email){

	    			$user->tmp_email = RequestMethods::post('email');

                    $string = \Framework\StringMethods::uniqueRandomString(44);
                    $user->email_confirm_string = $string;

	    		}

	    		$user->designation = RequestMethods::post('designation');
	    		$user->company_name = RequestMethods::post('company_name');
	    		$user->state = RequestMethods::post('state');
                $user->address = RequestMethods::post('address');

	    		if($user->validate()){

	    			$user->save();

                    //mail the url to confirm the email

                    $this->redirect('/users/profile/1');


	    		}else{

	    			$view->set('validation', 1);
	    		}
	    	}else{

	    		$view->set('exist', 1);
	    	}

    	}

		if(RequestMethods::post('change_password')){

            $cp = 2;

			$old = sha1(RequestMethods::post('old'));

			if($this->user->password == $old){

				$pass = RequestMethods::post('new');
				$confirm = RequestMethods::post('confirm');

				if($pass == $confirm){

					$user = models\User::first(array('id = ?' => $this->user->id));

					$user->password = sha1($pass);

					$user->save();

					$message = "Password Changed<strong>Successfully!</strong>";

                    $view->set('cp_success', 1);

				}else{

                    $message = "New passwords do not match!";
				}

			}else{

                $message = "Wrong Old Password!";
			}

            $view->set('message', $message);


		}

        if(RequestMethods::post('update_invoice_setting')){

            $cp = 3;

            if ($_FILES['logo']['name']) {
             
                $img = $this->_upload('logo', 'logo', ['extension' => 'jpe?g|png', 'name' => $this->user->id, 'size' => '6000000']);

                if($img){

                    $user = models\User::first(array('id = ?' => $this->user->id));

                    $user->logo_ext = $img;

                    $user->save();

                    $this->redirect('/users/profile/2');
                }


            }

        }

        if($success == 2){

            $cp = 3;
        }

        $state = models\State::all();

		$view->set('cp', $cp)->set('state', $state);
       
        
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

        switch (RequestMethods::post('action')) {
            case 'add_sc':
                $this->operate_sc(1, 'add');
                break;
            
            case 'edit_sc':
                $this->operate_sc(1, 'edit');
                break;
            default:
                if(RequestMethods::post('delete')){

                    $this->operate_sc(1, 'delete');
                }
                break;
        }

        $table = models\Supplier_or_Customer::all(array(
        	'user_id = ?' => $this->user->id,
        	'type = ?' => '1'
        	));

        $states = models\State::all();

        $view->set('table', $table)->set('states', $states);
        
    } 

    /**
	* @before _secure
	*/
    public function customers($id = -1){

    	$layoutView = $this->getLayoutView();
    	$layoutView->set("seo", Framework\Registry::get("seo"));

    	$layoutView->set('customers_nav', 1);

    	$view = $this->getActionView();

        switch (RequestMethods::post('action')) {
            case 'add_sc':
                $this->operate_sc(2, 'add');
                break;
            
            case 'edit_sc':
                $this->operate_sc(2, 'edit');
                break;
            default:
                if(RequestMethods::post('delete')){

                    $this->operate_sc(2, 'delete');
                }
                break;
        }

        $table = models\Supplier_or_Customer::all(array(
        	'user_id = ?' => $this->user->id,
        	'type = ?' => '2'
        	));

        $states = models\State::all();

        $view->set('table', $table)->set('states', $states);
        
    } 

    /**
    * @before _secure
    */
    protected function operate_sc($type = null, $op = null){

        $view = $this->getActionView();

        switch ($op) {
            case 'add':
                $c = models\User::first(array(
                    'email = ?' => RequestMethods::post('email')
                    ));

                if(!empty($c)){

                    $exist = models\Supplier_or_Customer::first(array(
                        'contact_id = ?' => $c->id,
                        'type = ?' => $type
                        ));

                    if(empty($exist)){

                        $c2 = new models\Supplier_or_Customer(array(
                            'user_id' => $this->user->id,
                            'contact_id' => $c->id,
                            'type' => $type
                            ));
                        $c2->save();

                        $contact = models\Contact::first(array(
                            'second_id = ?' => $c->id
                            ));

                        if(empty($contact)){

                            $c2 = new models\Contact(array(
                            'first_id' => $this->user->id,
                            'second_id' => $c->id,
                            'first_id_status' => true
                            ));

                            $c2->save();

                        }
                        
                        $view->set('add_success', 1);
                    }

                }else{

                    $c = new models\Supplier_or_Customer(array(
                        'user_id' => $this->user->id,
                        'type' => $type,
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
                break;
            case 'edit':
                $c = models\Supplier_or_Customer::first(array(
                    'id = ?' => RequestMethods::post('edit_sc_id'),
                    'user_id' => $this->user->id,
                    'type' => $type,
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
                break;
            case 'delete':
                $c = models\Supplier_or_Customer::first(array(
                    'id = ?' => RequestMethods::post('delete'),
                    'user_id' => $this->user->id,
                    'type' => $type,
                    ));

                if(!empty($c)){

                    $c->delete();
                    $view->set('delete_success', 1);
                }
                break;
        }
    }

    /**
    * @before _secure
    */
    public function contact_book(){

        $view = $this->getActionView();

        if(RequestMethods::post('delete')){

            $c = models\Contact::first(array(
                'first_id = ?' => $this->user->id,
                'second_id = ?' => RequestMethods::post('delete')
                ));

            if(!empty($c)){
                $c->delete();
            }
        }

        $contacts = models\Contact::all(array(
            'first_id = ?' => $this->user->id
            ));

        $view->set('contacts', $contacts);
        
        
    }
 
    /**
	* @before _secure
	*/
    public function calendar(){
        
        
    }

    /**
    * @before _secure
    */
    public function wallet(){


    }


    /**
    * @before _secure
    */
    public function notes($id = '-1'){

        $view = $this->getActionView();

        $all_notes = models\Note::all(array(
            'user_id = ?' => $this->user->id
            ));

        $view->set('all_notes', $all_notes);

        switch ($id) {
            case 'new':
                if(RequestMethods::post('action') == 'save'){

                    $note = new models\Note(array(
                        'note_id' => uniqid(),
                        'title' => RequestMethods::post('title'),
                        'text' => RequestMethods::post('text'),
                        'user_id' => $this->user->id
                        ));

                    if($note->validate()){

                        $note->save();

                        $this->redirect('/users/notes/' . $note->note_id);

                    }

                }

                $view->set('new', 1);
                break;
            
            case '-1':
                $note = models\Note::first(array(
                    'user_id = ?' => $this->user->id
                    ));

                if($note){

                    $this->redirect('/users/notes/' . $note->note_id);

                }else{

                    $this->redirect('/users/notes/new');
                }
                break;
            
            default:
                $note = models\Note::first(array(
                    'note_id = ?' => $id,
                    'user_id = ?' => $this->user->id
                    ));

                if($note){

                    if(RequestMethods::post('action') == 'save'){

                        $note->title = RequestMethods::post('title');
                        $note->text = RequestMethods::post('text');

                        if($note->validate()){
                            $note->save();
                        }

                    }

                    if(RequestMethods::post('action') == 'delete'){

                        $note->delete();
                        $this->redirect('/users/notes');
                        
                    }

                    $view->set('note', $note);

                }else{

                    $this->redirect('/404');

                }
                break;                
                
        }
        
    }

}
