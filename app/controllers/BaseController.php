<?php

namespace QInterface\Controllers;

use QInterface\Libs\LayoutHelper;
use Controller;
use View;
use Session;
use Auth;
use Route;
use URL;
use Useragent;
use Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BaseController extends Controller
{

    /**
     * The default template to use
     *
     * @access protected
     * @var    mixed
     */
    protected $layout = 'layout.master';

    /**
     * LayoutHelper instance
     *
     * @access protected
     * @var    LayoutHelper
     */
    protected $layoutHelper;

    /**
     * Add a link to the breadcrumb after creating the LayoutHelper object.
     *
     * @access public
     */
    public function __construct()
    {
        $this->layoutHelper = new LayoutHelper;

        $this->addToBreadcrumb(
            'Home',
            'QInterface\Controllers\GatewayController@index'
        );
    }

    /**
     * Setup the layout used by the controller.
     *
     * @access protected
     * @return BaseController
     */
    protected function setupLayout()
    {
        if ( ! is_null($this->layout))
        {
            $this->layout = View::make($this->layout);
        }

        View::share('success', Session::get('success'));
        View::share('sidebar', $this->layoutHelper->getSidebar());

        return $this;
    }

    /**
     * Add a link to the breadcrumb
     *
     * @access protected
     * @param  string    $name   The text shown within the anchor tag
     * @param  string    $action The controller action that will be resolved
     *                           to its route
     * @param  array     $params Parameters for the route
     * @return BaseController
     */
    protected function addToBreadcrumb($name, $action, $params = array())
    {
        $this->layoutHelper->addToBreadcrumb($name, $action, $params);

        return $this->updateBreadcrumb();
    }

    protected function popBreadcrumb()
    {
        $this->layoutHelper->popBreadcrumb();

        return $this->updateBreadcrumb();
    }

    /**
     * Update the breadcrumb in the view
     *
     * @access protected
     * @return BaseController
     */
    protected function updateBreadcrumb()
    {
        list($breadcrumb, $breadcrumbLength) = $this->layoutHelper->getBreadcrumb();

        View::share('breadcrumb_links', $breadcrumb);
        View::share('breadcrumb_counter', $breadcrumbLength);

        return $this;
    }

    /*
     | Generate URL
     | param String $route_alias : route alias name.
     */
    protected function generate_link($route_alias, $param = array(), $query = NULL)
    {
        $query_string = is_array($query) ? '?' . http_build_query($query) : '';
        $url = URL::route($route_alias, $param) . '/' . $query_string;
        $url = substr($url, 0, strlen($url) - 1);
        return $url;
    }

    /*
     | Generate API Param
     | param Array $extra_param.
     | param String $rawPath
     */
    protected function generateParam($extra_param = array(), $rawPath = null)
    {
        $session = Session::get('qeon_session');
        $basic = array(
            'user_agent'   => Useragent::agent_string(),
            'ip_address'   => Request::getClientIp(),
            'session'      => $session['_auth']['session'],
            'symkey'       => $session['_auth']['symkey']
        );

        if(!is_null($rawPath))
        {
            $basic["rawPath"] = $rawPath;
        }

        return array_merge($basic, array(
            'extra_params'   =>  !is_array($extra_param) ? array() : $extra_param
        ));
    }

}