<?php

return array(
    'path'=>'/',
	'domain' => '.qeon.co.id',
    'qeon'   => array(
        'sess_encrypt_cookie'        => true,
        'sess_use_dbconfig'          => true,
        'sess_time_update_tolerance' => 10,
        'sess_dbconfig'              => 'session',
        'sess_use_database'          => true,
        'sess_table_name'            => 'WebSessions',
        'sess_expiration'            => 7200,
        'sess_match_ip'              => false,
        'sess_match_useragent'       => true,
        'sess_cookie_name'           => 'QMSSESSID',
        'time_reference'             => 'local',
        'cookie_prefix'              => '',
        'encryption_key'             => '3nCrYp710n-=-K3Y3nCrYp710n-=-K3Y',
    ),

);
