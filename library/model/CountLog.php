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
    function history(&$db, $type='hourly'){
        return null;
    }
    function historyByHourly(&$db){
        $stime = strtotime('yesterday', strtotime(date('Y-m-d', time())));
        $etime = $stime + 3600;
        $hist = array();
        for($i=0;$i<24;$i++){
            $hist[$i] = $db->sum(new CountLog(), CountLog::columnTimes(),
                new C(Q::gte(CountLog::columnCreated(), $stime), Q::lt(CountLog::columnCreated(), $etime)));
            $stime += 3600;
            $etime += 3600;
        }
        return $hist;
    }
}
