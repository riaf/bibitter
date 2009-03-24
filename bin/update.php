<?php
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
$timelines = $twitter->status_public_timeline($last_log->sinceId + 1);

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
