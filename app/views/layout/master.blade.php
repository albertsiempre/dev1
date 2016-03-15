<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    {{ HTML::style('/main/styles/core.css') }}
    {{ HTML::style('/main/libs/simplePagination/simplePagination.css') }}
    {{ HTML::style('/main/admin/lib/bootstrap/css/bootstrap.css') }}
    {{ HTML::style('/main/admin/stylesheets/theme.css') }}
    {{ HTML::style('/main/admin/lib/font-awesome/css/font-awesome.css') }}
    {{ HTML::style('/main/admin/lib/font-awesome-4.1.0/css/font-awesome.min.css') }}
    {{ HTML::style('/main/admin/lib/simplePagination/simplePagination.css') }}

    {{ HTML::script('/main/scripts/jquery-1.8.3.min.js') }}
    {{ HTML::script('/main/scripts/popup.js') }}
    {{ HTML::script('/main/admin/lib/simplePagination/simplePagination.js') }}
    {{ HTML::script('/main/admin/lib/bootstrap/js/bootstrap.js') }}
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="{{ asset('main/images/favicon.ico') }}">
    <script type="text/javascript">
      var needRefresh = false;
    </script>
  </head>

  <!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
  <!--[if IE 7 ]> <body class="ie ie7 "> <![endif]-->
  <!--[if IE 8 ]> <body class="ie ie8 "> <![endif]-->
  <!--[if IE 9 ]> <body class="ie ie9 "> <![endif]-->
  <!--[if (gt IE 9)|!(IE)]><!--> 
  <body class="">
  <!--<![endif]-->
      <div class="navbar">
          <div class="navbar-inner">
              <?php
                $session = Session::get('qeon_session');
                if(isset($session['_name']['user']['full']))
                {
                  ?>
                    <ul class="nav pull-right">
                        <li><a href="{{ Config::get('app.change_password') }}" class="hidden-phone visible-tablet visible-desktop" role="button">Change Password</a></li>
                        <li id="fat-menu" class="dropdown">
                            <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="icon-user"></i>{{ isset($session['_name']['user']['full']) ? $session['_name']['user']['full'] : 'Admin' }}<i class="icon-caret-down"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a tabindex="-1" href="{{ Config::get('app.my_account') }}">My Account</a></li>
                                <li class="divider"></li>
                                <li><a tabindex="-1" href="{{ Config::get('app.logout') }}" id="user_logout">Logout</a></li>
                            </ul>
                        </li>
                        <?php
                            if(isset($session['_admin']['system']))
                            {
                                ?>
                                    <li id="fat-menu" class="dropdown">
                                        <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
                                            <i class="icon-sitemap"></i> Systems
                                            <i class="icon-caret-down"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <?php
                                                foreach($session['_admin']['system'] as $menu)
                                                {
                                                  ?>
                                                    <li><a href="{{ isset($menu['url']) ? $menu['url'] : '' }}">{{ isset($menu['name']) ? $menu['name'] : '' }}</a></li>
                                                  <?php
                                                }
                                            ?>
                                        </ul>
                                    </li>
                                <?php
                            }
                        ?>
                    </ul>
                  <?php
                }
              ?>
              

              <span class="brand"><img src="{{ asset('main/images/Qeon/logo.png') }}" alt="Qeon Interactive" style="width: 20px;"/></span>
          </div>
      </div>
      @yield('dialog')
      @yield('content')
  </body>
</html>


