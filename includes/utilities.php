<?php
require 'ProcessManager.php';

function restartLsyncd($APP_CONFIG)
{
    $pm = new ProcessManager();
    $pidfile = $APP_CONFIG['data_dir'] . 'lsyncd.pid';

    if(file_exists($pidfile)){
        $pid = file_get_contents($pidfile);

        if($pm->isProcessRunning($pid)){
            echo " -Stopping existing Lsyncd.\n";
            $pm->killProcess($pid);
        }
    }
    echo " -Starting Lsyncd.\n";
    startLsyncd($APP_CONFIG);
}

function startLsyncd($APP_CONFIG)
{
    $pm = new ProcessManager();
    $pidfile = $APP_CONFIG['data_dir'] . 'lsyncd.pid';

    $command = $APP_CONFIG['path_to_lsyncd'] . ' ' . $APP_CONFIG['data_dir'] . 'lsyncd.conf';
    $pid = $pm->execProcess($command)+1; //the PID number is out by one
    file_put_contents($pidfile, $pid);
    echo " -Lsyncd started. PID: $pid\n";
}

/**
 * Check if Lsyncd is still alive
 * If it is not, start it
 *
 * @param array $APP_CONF Application configuration
 * @return void
 */
function keepLsyncdAlive($APP_CONF)
{
    $processManager = new ProcessManager();
    $pidFile = $APP_CONF['data_dir'] . 'lsyncd.pid';

    echo " -Checking if Lsyncd is still running.\n";

    if (file_exists($pidFile)) {
        $pid = file_get_contents($pidFile);

        if ($processManager->isProcessRunning($pid)) {
            echo " -Lsyncd is still running fine.";
            return;
        }
    }

    echo " -Lsyncd is not active.\n";
    echo " -Starting Lsyncd.\n";
    startLsyncd($APP_CONF);
}