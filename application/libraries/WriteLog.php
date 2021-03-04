<?php
date_default_timezone_set("GMT");

/**
 *  class: Logging
 * - contains lfile, lwrite and lclose public methods
 * - lfile sets path and name of log file
 * - lwrite writes message to the log file (and implicitly opens log file)
 * - lclose closes log file
 * - first call of lwrite method will open log file implicitly
 * - message is written with the following format: [d/M/Y:H:i:s] (script name) message
 */
 /* 
 * Author: Ashish Kumar Kashyap
 * Created: 27-11-2014
 * Available: Since Release
 */
class WriteLog {

    // declare log file and file pointer as private properties
    private $log_file, $fp, $filename, $filepath , $errortype;
	
	
	public function __construct()
	{
		$ci =& get_instance();
		$ci->router->fetch_class();
		$calling_class_name = $ci->router->fetch_class();
		// obtaining log file name 
		$filename = (empty($calling_class_name)) 
							? date('dmy_')."weblog" 
							: date('dmY_').$calling_class_name;
		$this->filename = $filename.'.log';
		// setting up the file path
		$this->fpath();
		// setting up the log file
		$this->lfile();
	}
	// setting the root path for all application log files.
	private function fpath(){
		$this->filepath =  str_replace("\\", "/", FCPATH . 'applogs\\');
	}
	
    // set log file (path and name)
    private function lfile() {
        $this->log_file = $this->filepath . $this->filename;
    }
	
    /* write message to the log file
	* Params: 
	*	$message[any string, array or exception log to print in file]
	*	$error_type[0->Debug (default) -- 1->Information -- 2->Warning -- 3-> Error --] 	
	*/
    public function lwrite($message,$error_type = 0) {
        // if file pointer doesn't exist, then open log file
        if (!is_resource($this->fp)) {
            $this->lopen();
        }
        // define script name
        $script_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
        // define current time and suppress E_WARNING if using the system TZ settings
        // (don't forget to set the INI setting date.timezone)
        $time = @date('Y-m-dTh:i:s');
		$this->set_error_type($error_type);
        // write current time, script name and message to the log file
        fwrite($this->fp, "$time ". $this->errortype . print_r($message,true) . PHP_EOL);
		
		// close log file
		$this->lclose();
    }
	// setting error types if no type match then set custom error type 
	private function set_error_type($type){
		switch($type){
			case 0: 
			$this->errortype = "DEBUG(0):: ";
			break;
			case 1:
			$this->errortype =  "INFO(1):: ";
			break;
			case 2:
			$this->errortype =  "WARN(2):: ";
			break;
			case 3:
			$this->errortype =  "ERROR(3):: ";
			break;
			default:
			$this->errortype =  $type;
			break;
		}
	}
	
    // close log file (it's always a good idea to close a file when you're done with it)
    private function lclose() {
        fclose($this->fp);
    }
	
    // open log file (private method)
    private function lopen() {
        // in case of Windows set default log file
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $log_file_default = 'logfile.txt';
        }
        // set default log file for Linux and other systems
        else {
            $log_file_default = 'logfile.txt';
        }
        // define log file from lfile method or use previously set default
        $lfile = $this->log_file ? $this->log_file : $log_file_default;
        // open log file for writing only and place file pointer at the end of the file
        // (if the file does not exist, try to create it)
        $this->fp = fopen($lfile, 'a') or exit("Can't open $lfile!");
		
    }
	
	public function test(){
		
		return $ci->router->fetch_class();
	}
}