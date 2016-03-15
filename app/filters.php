<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{

});


App::after(function($request, $response)
{
    //
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function ()
{
    $session = Session::get('qeon_session');

    if ( ! isset($session['_auth'])) {
        Session::put('url_intended', ltrim(Request::getRequestUri(), '/'));
        return Redirect::guest('login');
    }
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
    $session = Session::get('qeon_session');

    if (isset($session['_auth'])) 
    {
        $domain = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'warnet.local';
        if(preg_match('/^warnet/', $domain))
        {
            return Redirect::action('QInterface\Controllers\Warnet\homeController@index');
        } else if(preg_match('/^internal/', $domain)) {
            return Redirect::action('QInterface\Controllers\Internal\DVDController@FreeDVD');
        } else {
            return Redirect::action('QInterface\Controllers\CRM\dashboard@home');
        }
    }
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
    // Sorry, we're in a bit of a hurry
    $path = Route::getCurrentRoute()->getPath();
    if ( ! preg_match('/^\/api/', $path)) {
        if (Session::token() != Input::get('_token')) {
            throw new Illuminate\Session\TokenMismatchException;
        }
    }
});

Route::filter('privilege', function ()
{
    $session = Session::get('qeon_session');

    if(isset($session['_admin']['privilege']))
    {
        $privileges = $session['_admin']['privilege'];
        $privilegeChecker = new QInterface\Libs\PrivilegeChecker($privileges);

        try {
            if ( ! $privilegeChecker->isAuthorized()) {
                return View::make('errors.noPrivilege');
            }
        } catch (Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            App::abort('404');
        }
    } else {
        return View::make('errors.noPrivilege');
    }
    
});

Route::filter('message_ownership', function ($route, $request)
{
    $parameters = $route->getParameters();
    $message = array_shift($parameters);

    if ($message) {
        $ownerId = $message->favorite_id;
        $currentId = QInterface\Models\FMS\Favorite::findByUserId(Session::get('qeon_user_id'))->id;

        if ($ownerId !== $currentId) {
            throw new QInterface\Exceptions\FMS\MessageOwnerMismatchException(403);
        }
    }
});

Route::filter('qeon_session', function () {
    $session = (new QInterface\Libs\QISession)->read();

    Session::put('qeon_session', $session);

    if (isset($session['_auth'])) {
        $api = new QInterface\Libs\QIAPI(array(
            'user_agent' => Useragent::agent_string(),
            'ip_address' => Request::getClientIp(),
            'session'    => $session['_auth']['session'],
            'symkey'     => $session['_auth']['symkey'],
            'url_target' => 'user_info',
        ));

        $api->send();
        $info = $api->read();
        Session::put('qeon_user_id', $info['id']);
        Session::put('qeon_full_name', $info['full_name']);
    } else {
        Session::remove('qeon_user_id');
    }

    if ( ! Request::cookie('QMS_c')) {
        setcookie(
            'QMS_c',
            md5(uniqid(rand(), TRUE)),
            time() + Config::get('session.qeon.sess_expiration'),
            Config::get('session.path'),
            Config::get('session.domain')
        );
    }
});
