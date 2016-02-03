<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Events
 *
 * @author kaliostro
 */
class Events 
{
    static protected $_instance = null;
    
    protected $_listeners = array();
    
    protected function __construct()
    {
        
    }
    
    static public function getInstance()
    {
        if (self::$_instance === null)
        {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    public function connect($eventName, $callback)
    {
        if (!isset($this->_listeners[$eventName]))
        {
            $this->_listeners[$eventName] = array();
        }
        
        $this->_listeners[$eventName][] = $callback;
    }
    
    public function notify($eventName)
    {
        $args = func_get_args();
        array_shift($args);
        
        if (empty($this->_listeners[$eventName]))
        {
            return;
        }
        
        foreach($this->_listeners[$eventName] as $listener)
        {
            call_user_func_array($listener, $args);
        }
    }
    
    public function processNotify($eventName)
    {
        $args = func_get_args();
        array_shift($args);
        
        if (empty($this->_listeners[$eventName]))
        {
            return;
        }
        
        foreach($this->_listeners[$eventName] as $listener)
        {
            if (call_user_func_array($listener, $args) === false)
            {
                return false;
            }
        }
        
        return true;
    }
    
    public function addEventSubscriber(IEventSubscriber $obj)
    {
        foreach($obj->getEvents() as $eventName => $callback)
        {
            $this->connect($eventName, array($obj, $callback));
        }
    }
}

?>
