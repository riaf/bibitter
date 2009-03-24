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
}