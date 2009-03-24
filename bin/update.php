<?php
set_time_limit(50);
chdir(dirname(__FILE__));
require_once dirname(__FILE__). '/__init__.php';
Rhaco::import('model.CountLog');
Rhaco::import('arbo.network.services.TwitterAPI');

$db = new DbUtil(CountLog::connection());
$last_log = $db->get(new CountLog(), new C(Q::orderDesc(CountLog::columnCreated())));
if(!Variable::istype('CountLog', $last_log)){
    $last_log = new CountLog();
}

$twitter = new TwitterAPI();
$timelines = array();
$tl_ids = array();
$_lid = $last_log->sinceId;
for($i=0;$i<10;$i++){
    $_tls = $twitter->status_public_timeline($_lid);
    foreach($_tls as $_tl){
        if(in_array($_tl['id'], $tl_ids)) continue;
        $tl_ids[] = $_tl['id'];
        $_lid = ($_lid < $_tl['id'])? $_tl['id']: $_lid;
        $timelines[] = $_tl;
    }
    sleep(1.5);
}

$counter = 0;
$since_id = $last_log->sinceId;
foreach($timelines as $timeline){
    if($timeline['id'] <= $last_log->sinceId) continue;
    $counter += intval(substr_count($timeline['text'], '!'));
    $counter += intval(mb_substr_count($timeline['text'], '！'));
    $since_id = ($since_id < $timeline['id'])? $timeline['id']: $since_id;
}

if($counter < 1) exit;

$new_log = new CountLog();
$new_log->setSinceId($since_id);
$new_log->setTimes($counter);
$db->insert($new_log);
