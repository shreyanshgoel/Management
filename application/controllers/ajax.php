<?php

/**
 * The Default Example Controller Class
 *
 * @author Shreyansh Goel
 */
use Shared\Controller as Controller;
use Framework\RequestMethods as RequestMethods;
use Framework\Registry as Registry;

class Ajax extends Controller {

	/**
	* @before _secure
	*/
    public function edit($id = -1) {
    	
    	$view = $this->getActionView();

    	$entry = models\Entry::first(array(
    		'id = ?' => $id,
    		'user_id = ?' => $this->user->id
    		));

    	if(!empty($entry)){

    		$table = models\Table::first(array(
    			'id = ?' => $entry->table_id
    			));
    		
			$data = array($entry, $table);

			$view->set($data);
    		
    	}else{

			$view->set(false);
		}
 

    }

    /**
	* @before _secure
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
    * @before _secure
    */
    public function edit_ps() {
        
        $view = $this->getActionView();

        $id = RequestMethods::post('id');

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
	* @before _secure
	*/
    public function states() {
    	
    	$view = $this->getActionView();

    	$states = models\State::all();

    	$view->set($states);

    }

    /**
	* @before _secure
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

		}
    	

    }

    /**
	* @before _secure
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
    * @before _secure
    */
    public function calendar_save($id = -1) {
        
        $start_date = RequestMethods::post('date');

        $title = RequestMethods::post('title');

        $color = RequestMethods::post('color');

        $calendar = new models\Calendar(array(
            'title' => $title,
            'start_date' => $start_date,
            'color' => $color,
            'user_id' => $this->user->id
            ));

        $calendar->save();

    }

    /**
    * @before _secure
    */
    public function calendar_edit($id = -1) {
        
        $start_date = RequestMethods::post('date');

        $title = RequestMethods::post('title');

        $color = RequestMethods::post('color');

        $calendar = new models\Calendar(array(
            'title' => $title,
            'start_date' => $start_date,
            'color' => $color,
            'user_id' => $this->user->id
            ));

        $calendar->save();

    }

    /**
    * @before _secure
    */
    public function calendar_events() {
        
        $view = $this->noview();

        $calendar = models\Calendar::all(array(
            'user_id = ?' => $this->user->id
            ));

        $events = array();

        foreach($calendar as $c){

            $e = array();
            $e['id'] = $c->id;
            $e['color'] = $c->color;
            $e['title'] = $c->title;
            $e['start'] = $c->start_date;

            array_push($events, $e);


        }

        echo json_encode($events);


    }

    /**
    * @before _secure
    */
    public function check_email() {

        $view = $this->getActionView();

        $check = models\User::all(array(
            'email = ?' => RequestMethods::post('email')
            ));

        if(!empty($check)){

            $view->set(array(1));

        }else{

            $view->set(array(0));
        }
        


    }
}