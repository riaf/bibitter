<?php
require dirname(dirname(__FILE__)). '/__settings__.php';
if(!isset($argv[1]) || $argv[1] !== def('cmd_password')){
    die('abort');
}
import('Bibitter');

$bibitter = new Bibitter();
$bibitter->crawl();
