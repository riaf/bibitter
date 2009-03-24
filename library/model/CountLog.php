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
    function history(){
        return null;
    }
}
