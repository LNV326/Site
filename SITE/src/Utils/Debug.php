<?php

namespace Utils;

/**
 * Class for getting operation time with milliseconds
 * @author NLukyanov (LNV)
 *
 */
class Debug {
	
	protected $_startTime = 0;
	protected $_startMemUsage = 0;
	
    public function startTimer() {
        $mtime = microtime ();
        $mtime = explode (' ', $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $this->_startTime = $mtime;
    }
    
    public function endTimer() {
        $mtime = microtime ();
        $mtime = explode (' ', $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;
        $totaltime = round (($endtime - $this->_startTime), 5);
        return $totaltime;
    }
    
    public function startMemUsage() {
    	$this->_startMemUsage = memory_get_usage();
    }
    
    public function endMemUsage() {
    	return (memory_get_usage()-$this->_startMemUsage);
    }
}