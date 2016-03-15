@extends('layout.master')

@section('dialog')
    <div class="row-fluid">
        <div class="dialog">
            <div class="block">
                <p class="block-heading">Sign In</p>
                <div class="block-body">
                    <form method="post" action="{{ Config::get('app.login') }}">
                        <label>Username</label>
                        <input type="text" class="span12" name="uname">
                        <label>Password</label>
                        <input type="password" class="span12" name="passwd">
                            @if (isset($login_info) && !empty($login_info))
                                @foreach ($login_info as $key => $val)
                                    <input type="hidden" name="{{ $key }}" value="{{ $val }}" />
                                @endforeach
                            @endif
                        <input type="submit" value="Sign In" class="btn btn-primary pull-right" />
                        <?php
                            $subDomainPrefix = Q_ENV;
                            $subDomainPrefix = $subDomainPrefix == "live" ? "" : $subDomainPrefix;
                            $domain = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'warnet.local';
                            if(preg_match('/^' . $subDomainPrefix . 'warnet/', $domain))
                            {
                                $dom = "warnet";
                            } else if(preg_match('/^' . $subDomainPrefix . 'internal/', $domain)) {
                                $dom = "internal";
                            } else {
                                $dom = "crm";
                            }
                        ?>
                        <input type="hidden" name="_l" value="{{ $dom }}" />
                        <input type="hidden" name="_p" value="{{ Session::get('url_intended') }}" />
                        <input type="hidden" name="QMS_t" value="{{ Request::cookie('QMS_c') }}" />
                        {{ Form::token() }}
                        {{-- <label class="remember-me"><input type="checkbox"> Remember me</label> --}}
                       <div class="clearfix"></div>
                    </form>
                </div>
            </div>

            {{-- <p class="pull-right" style=""><a href="http://www.portnine.com" target="blank">Theme by Portnine</a></p>
            <p><a href="reset-password.html">Forgot your password?</a></p> --}}
        </div>
    </div>
@stop