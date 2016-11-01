<?php

/**
 * The Default Example Controller Class
 *
 * @author Shreyansh Goel
 */
use Shared\Controller as Controller;
use Framework\RequestMethods as RequestMethods;
use Framework\Registry as Registry;

class Invoice extends Controller {

	/**
	* @before _secure
	*/
    public function purchase($id = -1){

    	$layoutView = $this->getLayoutView();
    	$layoutView->set("seo", Framework\Registry::get("seo"));

    	$layoutView->set('invoice_nav', 1);
    	$layoutView->set('purchase_nav', 1);

    	$view = new Framework\View(array(
                    "file" => APP_PATH . "/application/views/invoice/purchase_or_sales.html"
                ));

        $this->actionView = $view;

        if(RequestMethods::post('create_ps')){

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

	        		$id_array = array();
			        $q_array = array();
			        $p_array = array();

		        	foreach($item_ids as $key => $item_id){

			        	$item = models\Entry::first(array(
			        		'id = ?' => $item_id,
			        		'user_id = ?' => $this->user->id
			        		));

		        		if(!empty($item)){

		        			array_push($id_array, $item_id);
		        			array_push($q_array, $quantity[$key]);
		        			array_push($p_array, $price[$key]);

		        			$item->entry2 = $item->entry2 + $quantity[$key];
		        			$item->save();

			        	}
			        }

			        if(!empty($id_array) && !empty($q_array) && !empty($p_array)){
				        
				        $u = models\User::first(array(
			        			'id = ?' => $this->user->id
			        			));


			        	$c = new models\Purchase_Invoice(array(
			        		'invoice_id' => ++$u->last_purchase_invoice_id,
			        		'user_id' => $this->user->id,
			        		'supplier_id' => RequestMethods::post('supplier_id'),
			        		'item_id' => $id_array,
			        		'quantity' => $q_array,
			        		'price' => $p_array
			        		));

			        	if($c->validate()){

			        		$c->save();
			        		$view->set('add_success', 1);

			        		$u->save();
			        	}
				    }
			    }else{
			    	echo "no items";
			    }
	        }
        }

        if(RequestMethods::post('edit_ps')){

        	
        }

        if(RequestMethods::post('delete')){

        	$c = models\Purchase_Invoice::first(array(
        		'id = ?' => RequestMethods::post('delete'),
        		'user_id' => $this->user->id,
        		));

        	$items = $c->item_id;
        	$q = $c->quantity;

        	foreach($items as $key => $ids){

        		$item = models\Entry::first(array(
        			'id = ?' => $ids,
        			'user_id = ?' => $this->user->id
        			));

        		if(!empty($item)){
        		
        			$item->entry2 = $item->entry2 - $q[$key];
        			$item->save();
        		}
        	}

        	if($c->validate()){

        		$c->delete();
        		$view->set('delete_success', 1);
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
	* @before _secure
	*/
    public function sales($id = -1){

    	$layoutView = $this->getLayoutView();
    	$layoutView->set("seo", Framework\Registry::get("seo"));

    	$layoutView->set('invoice_nav', 1);
    	$layoutView->set('sales_nav', 1);


    	$view = new Framework\View(array(
                    "file" => APP_PATH . "/application/views/invoice/purchase_or_sales.html"
                ));

        $this->actionView = $view;

        if(RequestMethods::post('add_ps')){

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

        if(RequestMethods::post('edit_ps')){

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

        if(RequestMethods::post('delete_ps')){

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
    * @before _secure
    */
    
    public function purchase_show($id = -1){

        $view = $this->getActionView();

        $invoice = models\Purchase_Invoice::first(array(
            'id = ?' => $id,
            'user_id = ?' => $this->user->id
            ));

        if(!empty($invoice)){

            $view->set('invoice', $invoice);

        }else{

            $this->redirect('/404');
        }

        
    }

}