<?php
Rhaco::import("model.table.CountLogTable");
/**
 * 
 */
class CountLog extends CountLogTable{
    function recent(&$db, $term=3600){
        $counter = $db->sum(new CountLog(), CountLog::columnTimes(), new C(Q::gte(CountLog::columnCreated(), time() - $term)));
        return number_format($counter);
    }
    function historyByHourly(&$db){
        $stime = strtotime(date('Y-m-d H:00:00', time())) - 86400 - 3600;
        $s = intval(date('H', $stime));
        $etime = $stime + 3600;
        $hist = array();
        for($i=0;$i<24;$i++){
            $hist[$s] = $db->sum(new CountLog(), CountLog::columnTimes(),
                new C(Q::gte(CountLog::columnCreated(), $stime), Q::lt(CountLog::columnCreated(), $etime)));
            $stime += 3600;
            $etime += 3600;
            $s = ($s+1 > 24)? 0: $s+1;
        }
        return $hist;
    }
}
