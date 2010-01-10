<?php
import('org.rhaco.storage.db.Dao');

class BibitterCounter extends Dao
{
    protected $times;
    protected $updated;
    static protected $__times__ = 'type=integer,require=true';
    static protected $__updated__ = 'type=timestamp,primary=true';
    
    protected function __init__(){
        $this->updated = time();
    }
    
    public function get_history_by_hourly(){
        $stime = strtotime(date('Y-m-d H:00:00')) - 86400 + 3600;
        $s = intval(date('H', $stime));
        $etime = $stime + 3600;
        $hist = array();
        for($i=0;$i<24;$i++){
            $hist[$s] = (int) C(BibitterCounter)->find_sum('times', 
                Q::gte('updated', $stime), Q::lt('updated', $etime));
            $stime += 3600;
            $etime += 3600;
            $s = ($s+1 >= 24)? 0: $s+1;
        }
        return $hist;
    }
}
