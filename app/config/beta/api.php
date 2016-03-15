<?php

// $base_url = 'http://atlas.qeon.co.id';
// $base_url = 'http://www.qeon.tk/game_api/';
$subDomainPrefix = Q_ENV != "live" ? Q_ENV : "";
$base_url = 'http://' . $subDomainPrefix . 'atlas.qeon.co.id/';

return array(
    'url' => 'http://ams.kweon.tk/api/v1',
    'qeon' => array(
        'base_url' => $base_url,
    ),
);