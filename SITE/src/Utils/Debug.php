<?php

namespace Utils;

/**
 * Class for getting operation time with milliseconds
 * @author NLukyanov (LNV)
 *
 */
class Debug {
	
	static protected $starttime;
	
    public function startTimer() {
        $mtime = microtime ();
        $mtime = explode (' ', $mtime);
        $mtime = $mtime[1] + $mtime[0];
        self::$starttime = $mtime;
    }
    
    public function endTimer() {
        $mtime = microtime ();
        $mtime = explode (' ', $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;
        $totaltime = round (($endtime - self::$starttime), 5);
        return $totaltime;
    }
}