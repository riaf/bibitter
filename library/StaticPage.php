<?php
Rhaco::import('tag.HtmlParser');

class StaticPage
{
    /**
     * static template view
     */
    function parse($template_name){
        $args = func_get_args();
        $args = array_shift($args);
        $parser = new HtmlParser($template_name);
        $parser->setVariable($args);
        return $parser;
    }
}