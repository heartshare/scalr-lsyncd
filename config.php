<?php
define('BASE_DIR', dirname(__FILE__).'/');

// source => target
$SYNC_FOLDERS = array(
    array('folder'=>'/var/www2'),
    array('folder'=>'/var/www3'),
);

$APP_CONFIG = array(
    'appserver_location' => '/etc/scalr/private.d/hosts/OptimisedAppServer',
    'data_dir' => BASE_DIR . 'data/',
    'lsyncd_conf_template' => BASE_DIR . 'lsyncd.conf.template',
    'path_to_lsyncd' => 'lsyncd'
);

$LSYNCD_CONFIG = array(
    'log_file' => BASE_DIR . 'data/lsyncd.log',
    'status_file' => BASE_DIR . 'data/lsyncd.status',
    'max_processes' => 5,
    'delay' => 0, // in seconds, 0 for continuous monitoring
    'ssh_private_key' => '/root/.ssh/optimisedireland.pem',
);