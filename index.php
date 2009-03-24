<?php
require_once dirname(__FILE__). '/__init__.php';
Rhaco::import('generic.Urls');

Urls::parser(array(
    '^$' => array('class' => 'BiBitter', 'method' => 'index'),
    '^history$' => array('class' => 'StaticPage', 'method' => 'parse', 'args' => array('history.html')),
    '^about$' => array('class' => 'StaticPage', 'method' => 'parse', 'args' => array('about.html')),
    '^chart/(.+)$' => array('class' => 'BiBitter', 'method' => 'chart'),
))->write();

