<?php

/**
 * Description of Session
 *
 * @author kaliostro
 */
class Session 
{
    static protected $_instance;
    
    protected function __construct($id = null)
    {
        if (session_id() == '')
        {
            if ($id)
            {
                session_id($id);
            }
            session_start();
        }
        
        /* //PHP 5.4.0
        switch (session_status())
        {
            case PHP_SESSION_DISABLED:
                throw new Exception('Session is disabled');
                break;
            
            case PHP_SESSION_NONE:
                if ($id)
                {
                    session_id($id);
                }
                session_start();
                break;
        }
         * 
         */
    }
    
    static public function getInstance($id = null)
    {
        if (self::$_instance === null)
        {
            self::$_instance = new self($id);
        }
        
        return self::$_instance;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    
    public function get($key)
    {
        return isset($_SESSION[$key])?$_SESSION[$key]:null;
    }
    
    public function getId()
    {
        return session_id();
    }
    
    public function __get($key)
    {
        return $this->get($key);
    }
    
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }
}

?>
