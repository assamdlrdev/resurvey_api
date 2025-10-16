<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class LogController extends CI_Controller
{
    /**
     * Author: Udipta Gogoi
     * Date: 26-Dec-22
     */
    private static $levelsIcon = [
        'CRITICAL' => 'glyphicon glyphicon-warning-sign',
        'NOTICE' => 'glyphicon glyphicon-warning-sign',
        'WARNING' => 'glyphicon glyphicon-warning-sign',
        'ALERT' => 'glyphicon glyphicon-warning-sign',
        'INFO'  => 'glyphicon glyphicon-info-sign',
        'ERROR' => 'glyphicon glyphicon-warning-sign',
        'DEBUG' => 'glyphicon glyphicon-exclamation-sign',
        'EMERGENCY' => 'glyphicon glyphicon-warning-sign',
        'ALL'   => 'glyphicon glyphicon-minus',
        'SUCCESS'   => 'glyphicon glyphicon-check',
    ];

    private static $levelClasses = [
        'CRITICAL' => 'danger',
        'INFO'  => 'info',
        'ERROR' => 'danger',
        'DEBUG' => 'warning',
        'NOTICE' => 'info',
        'WARNING' => 'warning',
        'EMERGENCY' => 'danger',
        'ALERT' => 'warning',
        'ALL'   => 'muted',
        'SUCCESS'   => 'success',
    ];

    const LOG_LINE_HEADER_PATTERN = '/^([A-Z]+)\s*\-\s*([\-\d]+\s+[\:\d]+)\s*\-\->\s*(.+)$/';

    //this is the path (folder) on the system where the files are stored
    private $folderPath = APPPATH . 'logs/';

    //this is the pattern to pick all files in the $filePath
    private $writeFilePattern = "*.php";

    //this is a combination of the FOLDER_PATH and FILE_PATTERN
    private $fullFilePath = "";

    /**
     * Name of the view to pass to the renderer
     *
     * @var string
     */
    private $viewName = "log_history";

    const MAX_LOG_SIZE = 52428800; //50MB
    const MAX_STRING_LENGTH = 300; //300 chars

    public function __construct() {
        parent::__construct();
        $this->init();
    }

    /**
     * Bootstrap the library
     * sets the configuration variables
     * @throws \Exception
     */
    private function init() {
        // $viewerConfig = config('config');

        // if($viewerConfig) {
        //     if(isset($viewerConfig->viewName)) {
        //         $this->viewName = $viewerConfig->viewName;
        //     }
        //     if(isset($viewerConfig->logFilePattern)) {
        //         $this->logFilePattern = $viewerConfig->logFilePattern;
        //     }
        //     if(isset($viewerConfig->logFolderPath)) {
        //         $this->logFolderPath = $viewerConfig->logFolderPath;
        //     }
        // }

        //concatenate to form Full Log Path
        $this->fullFilePath = $this->folderPath . $this->writeFilePattern;
    }

    public function logs() {

        if(!is_null($this->input->get("del"))) {
            $this->deleteFiles(base64_decode($this->input->get("del")));
            redirect($this->uri->uri_string());
            return;
        }

        $dlFile = $this->input->get("dl");
        if(!is_null($dlFile) && file_exists($this->folderPath . "/" . basename(base64_decode($dlFile))) ) {
            $file = $this->folderPath . "/" . basename(base64_decode($dlFile));
            $this->downloadFile($file);
        }

        //it will either get the value of f or return null
        $fileName =  $this->input->get("f");
        //get the log files from the log directory
        $files = $this->getFiles();
        //let's determine what the current log file is
        if(!is_null($fileName)) {
            $currentFile = $this->folderPath . "/" . basename(base64_decode($fileName));
        }else if(!empty($files)) {
            $currentFile = $this->folderPath . $files[0];
        } else {
            $currentFile = null;
        }

        //if the resolved current file is too big
        //just trigger a download of the file
        //otherwise process its content as log

        if(!is_null($currentFile) && file_exists($currentFile)) {

            $fileSize = filesize($currentFile);

            if(is_int($fileSize) && $fileSize > self::MAX_LOG_SIZE) {
                //trigger a download of the current file instead
                $logs = null;
            }
            else {
                $logs =  $this->processFileData($this->getFileData($currentFile));
            }
        }
        else {
            $logs = [];
        }

        $data['logs'] = $logs;
        $data['files'] =  !empty($files) ? $files : [];
        $data['currentFile'] = !is_null($currentFile) ? basename($currentFile) : "";
        return $this->load->view($this->viewName, $data);
    }
    private function getFiles($basename = true)
    {

        $files = glob($this->fullFilePath);

        $files = array_reverse($files);
        $files = array_filter($files, 'is_file');
        if ($basename && is_array($files)) {
            foreach ($files as $k => $file) {
                $files[$k] = basename($file);
            }
        }
        return array_values($files);
    }
    private function getFileData($fileName) {
        $size = filesize($fileName);
        if(!$size || $size > self::MAX_LOG_SIZE){
            return null;
        }
        return file($fileName, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }
    private function processFileData($logs) {

        if(is_null($logs)) {
            return null;
        }

        $superLog = [];

        foreach ($logs as $log) {

            if($this->getLogHeaderLine($log, $level, $logDate, $logMessage)) {
                //this is actually the start of a new log and not just another line from previous log
                $data = [
                    "level" => $level,
                    "date" => $logDate,
                    "icon" => self::$levelsIcon[$level],
                    "class" => self::$levelClasses[$level],
                ];

                if(strlen($logMessage) > self::MAX_STRING_LENGTH) {
                    $data['content'] = substr($logMessage, 0, self::MAX_STRING_LENGTH);
                    $data["extra"] = substr($logMessage, (self::MAX_STRING_LENGTH + 1));
                } else {
                    $data["content"] = $logMessage;
                }

                array_push($superLog, $data);

            } else if(!empty($superLog)) {
                //this log line is a continuation of previous logline
                //so let's add them as extra
                $prevLog = $superLog[count($superLog) - 1];
                $extra = (array_key_exists("extra", $prevLog)) ? $prevLog["extra"] : "";
                $prevLog["extra"] = $extra . "<br>" . $log;
                $superLog[count($superLog) - 1] = $prevLog;
            }
        }

        return $superLog;
    }
    private function getLogHeaderLine($logLine, &$level, &$dateTime, &$message) {
        $matches = [];
        if(preg_match(self::LOG_LINE_HEADER_PATTERN, $logLine, $matches)) {
            $level = $matches[1];
            $dateTime = $matches[2];
            $message = $matches[3];
        }
        return $matches;
    }
    private function downloadFile($file) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
    private function deleteFiles($fileName) {

        if($fileName == "all") {
            array_map("unlink", glob($this->fullFilePath));
        }
        else {
            unlink($this->folderPath . "/" . basename($fileName));
        }
        return;
    }

}
