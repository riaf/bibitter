<?php
Rhaco::import('tag.HtmlParser');
Rhaco::import('model.CountLog');

class BiBitter
{
    function index(){
        $db = new DbUtil(CountLog::connection());
        
        $parser = new HtmlParser('index.html');
        $parser->setVariable('recent_counter', CountLog::recent($db));
        return $parser;
    }
    function total(){
        $db = new DbUtil(CountLog::connection());
        $parser = new HtmlParser('index.html');
        $parser->setVariable('recent_counter', number_format($db->sum(new CountLog(), CountLog::columnTimes())));
        return $parser;
    }
    
    function chart($type='hourly'){
        $db = new DbUtil(CountLog::connection());
        $url = Rhaco::url();
        switch($type){
            case 'hourly':
            default:
                $hist = CountLog::historyByHourly($db);
                $url = BiBitter::_getChartUrl($hist, array(
                    'chtt' => 'Recently BiBitter Chart by hourly',
                    'chl' => implode('|', array_keys($hist)))
                );
        }
        Header::redirect($url);
    }
    
    function _getChartUrl($history, $extra=null){
        $url = 'http://chart.apis.google.com/chart?chs=500x300&cht=ls';
        $url .= '&chd='. self::_getEncodedChartData($history);
        $url .= '&chf=bg,s,efefef';
        if(!is_null($extra)){
            $url .= '&'. http_build_query($extra);
        }
        return $url;
    }
    function _getEncodedChartData($values) {
        $maxValue = max($values);
        if($maxValue == 0) $maxValue = 1;
        $simpleEncoding = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $chartData = "s:";
        foreach($values as $value){
            if($value > -1){
                $chartData .= substr($simpleEncoding, 61 * ($value / $maxValue), 1);
            } else {
                $chartData .= '_';
            }
        }
        $chxl = 0;
        while($chxl < $maxValue){
            $chxl += 100;
        }
        return $chartData. "&chxt=y&chxl=0:|". implode('|', range(0, $chxl, 100));
    }
}