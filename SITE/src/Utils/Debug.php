<?php

namespace Utils;

/**
 * Class for getting operation time with milliseconds
 * @author NLukyanov (LNV)
 *
 */
class Debug {
	
	static protected $_startTime;
	static protected $_startMemUsage;
	
    public function startTimer() {
        $mtime = microtime ();
        $mtime = explode (' ', $mtime);
        $mtime = $mtime[1] + $mtime[0];
        self::$_startTime = $mtime;
    }
    
    public function endTimer() {
        $mtime = microtime ();
        $mtime = explode (' ', $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;
        $totaltime = round (($endtime - self::$_startTime), 5);
        return $totaltime;
    }
    
    public function startMemUsage() {
    	self::$_startMemUsage = memory_get_usage();
    }
    
    public function endMemUsage() {
    	return (memory_get_usage()-self::$_startMemUsage);
    }
}