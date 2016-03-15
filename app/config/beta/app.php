<?php

use QInterface\Libs\URL;

$domain = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'warnet.local';
$rootDomain = URL::getRootDomain($domain);
$subDomainPrefix = Q_ENV != "live" ? Q_ENV : "";

$domains = array(
    'accounts'  => "https://" . $subDomainPrefix . "accounts." . $rootDomain,
    'warnet'    => "http://" . $subDomainPrefix . "warnet." . $rootDomain,
    'internal'  => "http://" . $subDomainPrefix . "internal." . $rootDomain,
    'crm'       => "http://" . $subDomainPrefix . "crm." . $rootDomain
);

$config = array(
    'change_password' => $domains['accounts'] . '/account/settings/security',
    'my_account' => $domains['accounts'] . '/account/settings',
    'login' => $domains['accounts'] . '/a/login',
    'version' => '0.7'
);

if(preg_match('/^' . $subDomainPrefix . 'warnet/', $domain))
{
    $config["system_id"] = 7;
    $config['url'] = $domains['warnet'];
    $config['logout'] = $domains['accounts'] . '/a/logout/?_l=warnet';
} else if(preg_match('/^' . $subDomainPrefix . 'internal/', $domain)) {
    $config["system_id"] = 8;
    $config['url'] = $domains['internal'];
    $config['logout'] = $domains['accounts'] . '/a/logout/?_l=internal';
} else {
    $config["system_id"] = 2;
    $config['url'] = $domains['crm'];
    $config['logout'] = $domains['accounts'] . '/a/logout/?_l=crm';
}

return $config;
