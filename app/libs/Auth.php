<?php

namespace QInterface\Libs;

use Session;

class Auth extends \Illuminate\Support\Facades\Auth
{
    /**
     * Check the user is logged in
     *
     * @access public
     * @return boolean
     */
    public static function check()
    {
        $session = Session::get('qeon_session');

        return isset($session['_auth']);
    }

    /**
     * Get user's display name
     *
     * @access public
     * @return string
     */
    public static function getDisplayName()
    {
        return Session::get('qeon_full_name');
    }
}