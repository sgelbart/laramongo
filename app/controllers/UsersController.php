<?php
/*
|--------------------------------------------------------------------------
| Confide Controller Template
|--------------------------------------------------------------------------
|
| This is the default Confide controller template for controlling user
| authentication. Feel free to change to your needs.
|
*/

class UsersController extends BaseController {

    protected $layout = 'layouts.admin';

    /**
     * Displays the login form
     *
     */
    public function login()
    {
        $this->layout->content = View::make('users.login');
    }

    /**
     * Attempt to do login
     *
     */
    public function do_login()
    {
        $input = array(
            'email' => Input::get( 'email' ),
            'password' => Input::get( 'password' ),
            'remamber' => Input::get( 'remember' ),
        );

        if ( User::logAttempt( $input ) ) 
        {
            return Redirect::to('/admin');
        }
        else
        {
            $err_msg = Lang::get('confide::confide.alerts.wrong_credentials');
            return Redirect::action('UsersController@login')
                ->withInput(Input::except('password'))
                ->with( 'error', $err_msg );
        }
    }

    /**
     * Log the user out of the application.
     *
     */
    public function logout()
    {
        Auth::logout();
        
        return Redirect::to('/');
    }

}
