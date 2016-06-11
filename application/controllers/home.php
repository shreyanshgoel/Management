<?php

/**
 * The Default Example Controller Class
 *
 * @author Faizan Ayubi, Hemant Mann
 */
use Shared\Controller as Controller;

class Home extends Controller {

    public function index() {
    	$layoutView = $this->getLayoutView();
    	$layoutView->set("seo", Framework\Registry::get("seo"));
    }

    public function install(){

        $models = Shared\Markup::models();

        foreach($models as $key => $value){

            $this->sync($value);
        }
    }

    public function sync($model){

        try {
            $this->noview();
            $db = Framework\Registry::get("database");
            
            $model = "models\\" . $model; 

            $model = new $model;
            $db->sync($model);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        
    }

}
