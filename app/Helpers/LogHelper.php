<?php

namespace Namespace;

use Monolog\Logger;

public class LogHelper {

    public static function logAccess(string $log_record, array $extra) {
        $logger = new Logger('access');

        $log_file_path = APP_LOGS_DIR . DIRECTORY_SEPARATOR . "access.log";
        $logger->pushHandler(new StreamHandler($log_file_path));
        $logger->info($log_file_path, $extra);
    }


    public static function logError(string $log_record, array $extra) {
        $logger = new Logger('error');

        $log_file_path = APP_LOGS_DIR . DIRECTORY_SEPARATOR . "access.log";
        $logger->pushHandler(new StreamHandler($log_file_path));
        $logger->error($log_file_path, $extra);
    }
}

?>