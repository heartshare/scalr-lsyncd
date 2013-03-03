<?php

class ProcessManager
{
    public function execProcess($command)
    {
        $command = '(' . $command.' > /dev/null 2>&1 & echo $!)&';
        exec($command, $op);
        return (int)$op[0];
    }


    /**
     * Check if the PID is a running process
     * @warning will only work on *nix systems
     * @param string $pid
     * @return boolean
     */
    public function isProcessRunning($pid)
    {
        return($pid !== '') && file_exists("/proc/$pid");
    }

    public function killProcess($pid)
    {
        return posix_kill($pid, SIGKILL);
    }
}