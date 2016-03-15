<?php

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

}