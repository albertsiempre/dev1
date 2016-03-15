<?php

return array(
    'admin_email' => 'kemal.fadillah@qeon.co.id',
    'of'          => array(
        'bosh_url'         => 'http://perseus.qeon.tk:7070/http-bind/',
        'server_name'      => 'perseus.qeon.tk',
        'host'             => 'perseus.qeon.tk',
        'auth_script'      => app_path() . '/libs/perseus/authenticate.js',
        'broadcast_script' => app_path() . '/libs/perseus/broadcast.js',
    ),
    'article' => array(
        'base_url' => 'http://fms.kweon.tk/articles/',
    ),
    'external_gateway' => 'QInterface\Controllers\FMS\ExternalController@redirect',
);