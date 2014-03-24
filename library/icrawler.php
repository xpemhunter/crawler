<?php
/**
 *
 * @author igor
 */
abstract class ICrawler 
{
    abstract public function crawling();
    
    abstract public function crawling_proccess($url);
    
    abstract public function check($url);
    
    abstract public function get_dom($url);
    
    abstract public function get_elements($dom, $el);
    
    abstract public function show();
}

?>
