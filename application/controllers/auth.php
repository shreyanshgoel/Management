<?php

/**
 * The Default Example Controller Class
 *
 * @author Shreyansh Goel
 */
use Shared\Controller as Controller;
use Framework\RequestMethods as RequestMethods;

class Auth extends Controller {

    public function confirm_email(){

        $token = RequestMethods::get('token_id');

        if(!empty($token)){

            $user = models\User::first(array(
                'id = ?' => RequestMethods::get('user_id')
                ));

            if($user){

                if($user->email_confirm_string == $token){

                    $user->email = $user->tmp_email;

                    $user->email_confirm_string = null;

                    $user->tmp_email = null;

                    $user->save();

                    return;

                }
            
            }


        }
        
        $this->redirect('/404');
        
    }

}