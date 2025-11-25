<?php

namespace App\Helpers;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LogHelper
{

    public static function logAccess(string $log_record, array $extra)
    {
        $logger = new Logger('access');

        $log_file_path = APP_LOGS_DIR . DIRECTORY_SEPARATOR . "access.log";
        $logger->pushHandler(new StreamHandler($log_file_path));
        $logger->info($log_record, $extra);
    }


    public static function logError(string $log_record, array $extra)
    {
        $logger = new Logger('app_error');

        $log_file_path = APP_LOGS_DIR . DIRECTORY_SEPARATOR . "error.log";
        $logger->pushHandler(new StreamHandler($log_file_path));
        $logger->error($log_record, $extra);
    }
}
