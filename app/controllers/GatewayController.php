<?php

namespace QInterface\Controllers;

use Auth;
use Redirect;
use Input;
use Crypt;
use Session;
use Route;
use Request;
use Config;
use QInterface\Models\AMS\User;

class GatewayController extends BaseController
{
    /**
     * Shows the login form
     *
     * @method GET
     * @route  login
     * @access public
     * @return void
     */
    public function index()
    {
        $this->layout = 'login.form';
        $this->setupLayout();
        $this->layout->title = 'Qeon Interactive';
    }

    /**
     * Logs the user in.
     *
     * @method POST
     * @route  login
     * @access public
     * @return Redirect
     */
    public function login()
    {
        $response = User::attempt(Input::get('uname'), Input::get('passwd'));

        if ($response !== false) {
            // We don't have a password in the table, but Laravel demands
            // that we supply one for authentication
            $success = Auth::attempt(array(
                'user_id' => $response['id'],
                'password' => 'placeholder',
            ));

            if ($success) {
                Auth::getUser()->writeToSession($response)
                    ->updateCredentials(array(
                        'username' => Input::get('uname'),
                        'password' => Input::get('passwd'),
                    ));
                return Redirect::intended('modules');
            } else {
                // While the login process was successful,
                // the user is not an admin so we'll redirect them back to the
                // login page
                return $this->logout();
            }
        } else {
            return $this->logout();
        }
    }

    /**
     * Logs out the user and redirect them back to the login page
     *
     * @method DELETE
     * @route  logout
     * @access public
     * @return Redirect
     */
    public function logout()
    {
        $session = Session::all();
        Auth::logout();
        Session::flush();

        if (isset($session['admin'])) {
            $success = Auth::attempt(array(
                'user_id'  => $session['admin']['hive_id'],
                'password' => 'placeholder',
            ));

            if ($success) {
                Auth::getUser()->writeToSession($session['admin']);
            }
        }

        return Redirect::action(__CLASS__ . '@index');
    }

    /**
     * Shows the accessible module based on the user privilege
     *
     * @method GET
     * @route  modules
     * @access public
     * @return void
     */
    public function modules()
    {
        $this->layout->title = 'Qeon Interactive - Module Selection';
    }

}