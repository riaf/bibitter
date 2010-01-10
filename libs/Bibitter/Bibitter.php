<?php
module('model.BibitterCounter');

class Bibitter extends Flow
{
    public function current(){
        $this->vars('times', (int) C(BibitterCounter)->find_sum('times', Q::gt('updated', time()-1800)));
        return $this;
    }
    public function history(){
        $hist = C(BibitterCounter)->get_history_by_hourly();
        $url = $this->get_chart_url($hist, array(
            'chtt' => 'Recently BiBitter Chart by hourly',
            'chl' => implode('|', array_keys($hist)))
        );
        $this->vars('chart_url', $url);
        return $this;
    }
    public function crawl(){
        $times = 0;
        $last_update = time();
        $stream = fopen(sprintf('http://%s:%s@stream.twitter.com/1/statuses/sample.json',
            def('twitter_account'), def('twitter_password')), 'r');
        while($json = fgets($stream)){
            $status = json_decode($json, true);
            if(!isset($status['text'])) continue;
            $text = $status['text'];
            $times += substr_count($text, '!');
            $times += mb_substr_count($text, '！');
            if($times > 0 && time() - def('update_span', 180) > $last_update){
                try {
                    $counter = new BibitterCounter();
                    $counter->times($times);
                    $counter->save();
                    C($counter)->commit();
                    unset($counter);
                } catch(Exception $e) {
                    echo $e->getMessage(), PHP_EOL;
                    Log::d($counter);
                    exit;
                    continue;
                }
                Log::debug(sprintf('updated: %d times on %s.', $times, date('Y-m-d H:i:s')));
                Log::flush();
                $times = 0;
                $last_update = time();
            }
        }
    }
    private function get_chart_url($history, $extra=null){
        $url = 'http://chart.apis.google.com/chart?chs=500x300&cht=ls';
        $url .= '&chd='. $this->get_encoded_chart_data($history);
        $url .= '&chf=bg,s,efefef';
        if(!is_null($extra)){
            $url .= '&'. http_build_query($extra);
        }
        return $url;
    }
    private function get_encoded_chart_data($values) {
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
            $chxl += 1000;
        }
        return $chartData. "&chxt=y&chxl=0:|". implode('|', range(0, $chxl, 1000));
    }
}
