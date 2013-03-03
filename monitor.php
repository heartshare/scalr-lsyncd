#!/usr/bin/env php
<?php

require 'config.php';
require 'includes/utilities.php';
date_default_timezone_set('Europe/London');

echo "\n\nInitialising...\n";

// Generate list of App Servers
echo " -Building list of app servers\n";
$servers = array();
if ($handle = opendir($APP_CONFIG['appserver_location'])) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            $servers[]['private_ip_address'] = $entry;
        }
    }
    closedir($handle);
}

$appServersHash = md5(serialize($servers)); //used to determine if apps have changed since lsyncd daemon was started
$knownAppServersHash = file_get_contents($APP_CONFIG['data_dir'] . 'servers');
if($appServersHash == $knownAppServersHash){
    echo " -App Servers have not changed since Lsyncd was started\n";
    keepLsyncdAlive($APP_CONFIG);
    echo "\n\n\n";
    exit;
}
file_put_contents($APP_CONFIG['data_dir'] . 'servers',$appServersHash);


$servercount = count($servers);

// Generate lsyncd.conf
$data = array(
    'app' => array(
        'generation_time' => date('r')
    ),
    'lsyncd' => $LSYNCD_CONFIG,
    'servers' => $servers,
    'folders' => $SYNC_FOLDERS
);
require 'Mustache/Autoloader.php';
Mustache_Autoloader::register();
$m = new Mustache_Engine;
$lsyncdConf = $m->render(file_get_contents($APP_CONFIG['lsyncd_conf_template']), $data);
file_put_contents($APP_CONFIG['data_dir'] . 'lsyncd.conf',$lsyncdConf);

// Restart Lsyncd
echo " -Restarting Lsyncd\n";
restartLsyncd($APP_CONFIG);

// Completed
echo " -All systems go: $servercount app servers in sync!";
echo "\n\n\n";