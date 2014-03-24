<?php
/**
 * Crawler
 *
 * Crawler module contains general functions use by craw-bot
 * 
 * @author Igor
 */
class Crawler extends ICrawler
{
    private static $instance;
    private $seen_urls = array();
    private $main_url;
    private $page_content = '';
    
    public function __construct($main_url) 
    {
        $this->main_url = $main_url;
        $this->timer = new Timer();
        $this->curl = new Curl();
    }
    
    public static function factory($main_url)
    {
        return new Crawler($main_url);
    }
    
    public static function instance($main_url)
    {
        if(!self::$instance)
            self::$instance = self::factory($main_url);
        
        return self::$instance;
    }
    
    /**
    *  crawling
    *         
    *  Simple function, which execute crawling proccess.
    *  
    */
    public function crawling()
    {
        $this->crawling_proccess($this->main_url);
    }
    
    /**
    *  crawling_proccess
    *         
    *  Contains all proccess of crowling
    *  
    *  @param string       $url Url of site
    */
    public function crawling_proccess($url)
    {
        if(isset($this->seen_urls[$url]))
            return;
        
        //check valid url anf for curr host
        if(!$this->check($url))
            return;
        
        //set start gen time
        $this->timer->start($url);
        
        $this->seen_urls[$url] = array(
            'cnt' => 0,
            'time' => 0
        );
        
        //get dom
        $dom = $this->get_dom($url);
        
        //get images
        $imgs = $this->get_elements($dom, 'img');
        $this->seen_urls[$url]['cnt'] = $imgs->length;
        
        //get anchors
        $links = $this->get_elements($dom, 'a');
        
        //set end gen time
        $this->timer->end($url);
        
        if($links->length)
        {
            foreach ($links as $link) 
            {
                //get href from link
                $href = $link->getAttribute('href');
                if (strpos($href, 'http') !== 0) 
                {
                    $host = "http://".parse_url($url, PHP_URL_HOST);
                    $href = $host. '/' . ltrim($href, '/');
                }

                if(!$href)
                    continue;

                $this->crawling_proccess($href);
            }
        }
        
        $this->seen_urls[$url]['time'] = $this->timer->result_item($url);
    }
    
    /**
    *  check
    *         
    *  Check the received URL for a few rules
    *  
    *  @param string       $url Url of site
    *  @return boolean     result of validation
    */
    public function check($url)
    {
        //get curr link host
        $host = parse_url($url, PHP_URL_HOST);
        
        //check for valid url and domain
        if (filter_var($url, FILTER_VALIDATE_URL) === false || parse_url($this->main_url, PHP_URL_HOST) != $host)
            return false;
        
        //get url headers and check for 404. Maybe it doesn't need.. I don't know=)
        $result = $this->curl->query($url, '', '', '', true);
        if(stripos($result, 'HTTP/1.1 200 OK') === false || stripos($result, 'text/html') === false)
            return false;
        
        return true;
    }
    
    /**
    *  get_dom
    *         
    *  Get DOM of received url
    *  
    *  @param string       $url Url of site
    *  @return object      dom object
    */
    public function get_dom($url)
    {
        $dom = new DOMDocument('1.0');
        @$dom->loadHTMLFile($url);
        
        return $dom;
    }
    
    /**
    *  get_elements
    *         
    *  Get choosen element of received DOM object
    *  
    *  @param object       $dom DOM object
    *  @param string       $el choosen element
    *  @return object      dom object element
    */
    public function get_elements($dom, $el)
    {
        return $dom->getElementsByTagName($el);
    }
    
    /**
    *  show
    *         
    *  Prepare and save data to report file
    *  
    */
    public function show()
    {
        if(!sizeof($this->seen_urls))
            return false;
        
        //sort by imgs count
        array_multisort($this->seen_urls);
        
        //set variable
        $content['content'] = $this->seen_urls;
        
        //get page with view
        $this->render('template.php', $content);
        
        //save report
        $this->save_report();
        
        return true;
    }
    
    /**
    *  save_report
    *         
    *  Proccess saving data to report file
    *  
    */
    public function save_report()
    {
        $filename = 'report_'.date('d.m.Y').'.html';
        
        file_put_contents($filename, $this->page_content);
        chmod($filename, 0777);
    }
    
    /**
    *  render
    *         
    *  Get view page content with choosen variables
    *  
    *  @param string       $file view file
    *  @param array       $content setted variables
    */
    public function render($file, $content)
    {
        ob_start();
        
        //extract vars
        extract($content);
        
        //set template
        include($file);
        
        $this->page_content = ob_get_contents();
        ob_end_clean();
    }
}

?>
