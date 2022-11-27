<?php
session_start();

define( 'FB_GRAPH_VERSION', 'v15.0');
define( 'FB_GRAPH_DOMAIN', 'https://graph.facebook.com/');
define( 'FB_APP_STATE', 'eciphp');

include_once __DIR__ . '/php/functions.php';

include_once __DIR__ . '/php/config.php';

include_once __DIR__ . '/php/facebook_api.php';
