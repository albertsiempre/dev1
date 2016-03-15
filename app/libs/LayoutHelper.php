<?php

namespace QInterface\Libs;

use QInterface\Models\AMS\PrivilegeGroup;
use QInterface\Models\AMS\System;
use Illuminate\Support\Collection;
use Route;
use Input;
use Session;
use Config;

class LayoutHelper
{

    /**
     * Breadcrumb navigation links
     *
     * @access protected
     * @var    array
     */
    protected $breadcrumb = array();

    /**
     * Keep track of the length of the breadcrumb, so we don't have to call
     * count() everytime, in the view templates.
     *
     * @access protected
     * @var    int
     */
    protected $breadcrumbLength = 0;

    /**
     * Get the sidebar menu
     *
     * @access public
     * @return PrivilegeGroup
     */
    public function getSidebar()
    {
        $session = Session::get('qeon_session');

        if ( ! isset($session['_admin']['menu'][Config::get('app.system_id')])) {
            return array();
        }

        $current = Route::currentRouteAction();

        $menu = new Collection($session['_admin']['menu'][Config::get('app.system_id')]);

        foreach ($menu as $group => $links) {
            $isGroupActive = false;

            foreach ($links as $text => &$routeAction) {
                $isPrivilegeActive = false;
                $routeAction = explode('?', $routeAction);
                $query = isset($routeAction[1]) ? $routeAction[1] : '';
                $routeAction = str_replace('/', '@', $routeAction[0]);
                $isPrivilegeActive = $routeAction === $current;

                if ($isPrivilegeActive) {
                    $isGroupActive = true;
                }

                $routeAction = array(
                    'action'    => $routeAction,
                    'query'     => $query,
                    'is_active' => $isPrivilegeActive,
                );
            }

            // For some reason, using $links as a reference wouldn't work;
            // So we reassign the new value back by specifying the key itself.
            $menu[$group] = array('links' => new Collection($links), 'is_active' => $isGroupActive);
        }

        return $menu;
    }

    /**
     * Add a link to the breadcrumb
     *
     * @access protected
     * @param  string    $name   The text shown within the anchor tag
     * @param  string    $action The controller action that will be resolved
     *                           to its route
     * @param  array     $params Parameters for the route
     * @return LayoutHelper
     */
    public function addToBreadcrumb($name, $action, $params)
    {
        $this->breadcrumb[] = array($name, $action, $params);
        $this->breadcrumbLength++;

        return $this;
    }

    public function popBreadcrumb()
    {
        $this->breadcrumbLength--;
        return array_pop($this->breadcrumb);
    }

    /**
     * Get the stored breadcrumb
     *
     * @access public
     * @return array  An array containing 2 elements. The first being the
     *                breadcrumb array, followed by its length.
     */
    public function getBreadcrumb()
    {
        return array($this->breadcrumb, $this->breadcrumbLength);
    }
}