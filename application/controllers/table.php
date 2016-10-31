<?php

/**
 * The Default Example Controller Class
 *
 * @author Shreyansh Goel
 */
use Shared\Controller as Controller;
use Framework\RequestMethods as RequestMethods;
use Framework\Registry as Registry;

class Table extends Controller {

	/**
	* @before _secure
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
	* @before _secure
	*/
    public function other_tables($id = -1){

    	$layoutView = $this->getLayoutView();
    	$layoutView->set("seo", Framework\Registry::get("seo"));

    	$layoutView->set('other_tables_nav', 1);

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
	* @before _secure
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
	* @before _secure
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

		    		$view->set('success', 1);
		    		
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

		    		
		    		if($table->validate()){

		    			$table->save();

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
	* @before _secure
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

	    	$view->set('numbers', $numbers)->set('edit_table', $edit_table);

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


	    		if($edit_table->validate()){

	    			$edit_table->save();

	    			self::redirect('/users/other_tables/' . $edit_table->id . '?edit_table_success=1');

	    		}else{
	    			echo "Validation Not Good";
	    		}
	    	}
    
    	}
    }
}