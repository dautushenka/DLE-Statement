<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Request
 *
 * @author kaliostro
 */
class Request {
    
    public function __construct()
    {
        if ($this->isAJAX() && strtoupper($GLOBALS['config']['charset']) != 'UTF-8')
        {
            $_POST = $this->_convert_encoding($_POST);
            $_REQUEST = $this->_convert_encoding($_REQUEST);
            $_GET = $this->_convert_encoding($_GET);
        }
    }
    
    public function has($name)
    {
        return isset($_REQUEST[$name]);
    }
    
    public function hasPost($name)
    {
        return isset($_POST[$name]);
    }
    
    public function get($name, $default = null)
    {
        return $this->has($name)?$_REQUEST[$name]:$default;
    }
    
    public function getPost($name, $default = null)
    {
        return $this->hasPost($name)?$_POST[$name]:$default;
    }
    
    public function getClean($name, $type = 'int', $default = null)
    {
        if (!$this->has($name))
            return $default;
        
        $value = $_REQUEST[$name];
        
        switch ($type)
        {
            case 'int':
                return (int)$value;
                break;
            
            case 'string':
                return (string)$value;
                break;
            
            case 'float':
                return $value + 0;

            case 'bool':
                return $value?true:false;
                
            default:
                throw new Exception('Не определен тип');
                break;
        }
    }
    
    public function isMethod($method)
    {
        switch ($method)
        {
            case 'post':
                return !empty($_POST);
                break;
            
            case 'get':
                return !empty($_GET);
                break;
            
            default:
                throw new Exception('Не известный тип метода');
                break;
        }
    }
    
    public function isAJAX()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
            return true;
        else
            return false;
    }
    
    public function getSession($id = null)
    {
        return Session::getInstance();
    }
    
    protected function _convert_encoding($text)
    {
        if (is_array($text))
        {
            $return = array();
            foreach ($text as $key => $value)
            {
                $return[$key] = $this->_convert_encoding($value);
            }
        }
        else
        {
            $return = mb_convert_encoding($text, $GLOBALS['config']['charset'], 'UTF-8');
        }
        
        return $return;
    }
}

?>
