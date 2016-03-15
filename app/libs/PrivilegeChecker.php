<?php

namespace QInterface\Libs;

use Route;
use Auth;
use Config;
use Session;
use QInterface\Libs\URL;
use QInterface\Models\AMS\System;
use QInterface\Models\AMS\SystemController;
use QInterface\Models\AMS\ControllerMethod;

class PrivilegeChecker
{
    /**
     * A collection of privileges
     *
     * @access protected
     * @var    privileges
     */
    protected $privileges;

    /**
     * System id
     *
     * @access protected
     * @var    int
     */
    protected $system;

    /**
     * Controller to check against
     *
     * @access protected
     * @var    string
     */
    protected $controller;

    /**
     * Method to check against
     *
     * @access protected
     * @var    string
     */
    protected $method;

    /**
     * Initiate some mandatory properties.
     *
     * @access public
     * @param  array    $privileges
     * @param  int      $system
     * @param  string   $controller
     * @param  string   $method
     */
    public function __construct($privileges = null, $system = null, $controller = null, $method = null)
    {
        if($privileges == null)
        {
            $session = Session::get('qeon_session');
            $privileges = isset($session['_admin']['privilege']) ? $session['_admin']['privilege'] : null;
        }

        $this->privileges = $privileges;

        $this->system = $system ?: Config::get('app.system_id');

        $routeAction = explode('@', Route::currentRouteAction());
        $this->controller = $controller ?: $routeAction[0];
        $this->method = $method ?: $routeAction[1];
    }

    /**
     * Check whether the administrator is authorized to access certain methods
     *
     * @access public
     * @return bool
     */
    public function isAuthorized()
    {
        if (isset($this->privileges[$this->system][$this->controller][$this->method])) {
            return true;
        }

        return false;
    }

    public function getPrivilegeGroup()
    {
        if(isset($this->privileges[$this->system][$this->controller][$this->method]))
        {
            $arr = $this->privileges[$this->system][$this->controller][$this->method];
            return key($arr);
        }

        return false;
    }

    /* BUAT QWARNET */
    public function isSales()
    {
        if(isset($this->privileges[$this->system][$this->controller][$this->method]))
        {
            $arr = $this->privileges[$this->system][$this->controller][$this->method];
            $group = key($arr);
            return preg_match("/Sales/", $group) ? true : false;
        }

        return false;
    }
}