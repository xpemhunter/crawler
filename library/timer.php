<?php 
/**
 * Timer
 *
 * Timer module used for operations items time
 * 
 * @author Igor
 */
class Timer
{
    public $aTime = array();
    public function __construct()
    {
    }

    /**
    *  start
    *         
    *  Set item's start timer
    *  
    *  @param string       $name name of item
    */
    public function start ($name = 0)
    {
        $this->aTime[$name]['current'] = microtime(true);
    }

    /**
    *  stop
    *         
    *  Temporary stopped item's timer
    *  
    *  @param string       $name name of item
    */
    public function stop ($name = 0)
    {
        if (!isset($this->aTime[$name]['total']))
            $this->aTime[$name]['total'] = 0;
        $this->aTime[$name]['total'] = $this->aTime[$name]['total']+(microtime(true)-$this->aTime[$name]['current']);
        $this->aTime[$name]['current'] = 0;
    }

    /**
    *  end
    *         
    *  Stopped item's timer and return result between start and end
    *  
    *  @param string       $name name of item
    *  @return float     difference between start and end
    */
    public function end ($name = 0)
    {
        if (!isset($this->aTime[$name]['total']))
            $this->aTime[$name]['total'] = microtime(true) - $this->aTime[$name]['current'];
        $this->aTime[$name]['current'] = 0;
        return number_format($this->aTime[$name]['total'], 2);
    }
    
    /**
    *  result_item
    *         
    *  Return result between start and end
    *  
    *  @param string       $name name of item
    *  @return float     difference between start and end
    */
    public function result_item($name)
    {
        return $this->aTime[$name]['total'];
    }

    /**
    *  results
    *         
    *  Show all items timers with result between start and end
    *  
    *  @return string     difference between start and end of all items
    */
    public function results()
    {
        $txt = '';
        foreach ($this->aTime as $name => $el)
        {
            $txt .= $name.'='.number_format($el['total'], 2)."<br />";
        }
        return $txt;
    }
}