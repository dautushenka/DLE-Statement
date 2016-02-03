<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModelAbstract
 *
 * @author kaliostro
 */
abstract class ModelAbstract {
    //put your code here
    
    /**
     *
     * @var db
     */
    static public $db;
    
    public function __construct(array $row = array())
    {
        foreach ($row as $key => $value)
        {
            $method = $this->_getMethod($key);
            if ($method)
            {
                $this->$method($value);
            }
        }
    }
    
    protected function _getMethod($key)
    {
        while(($pos = strpos($key, "_")) !== false)
        {
            $key = substr_replace($key, "", $pos, 1);
            $key{$pos} = strtoupper($key{$pos});
        }
        
        return method_exists($this, "set" . ucfirst($key))?"set" . ucfirst($key):false;
    }
    
    abstract public function save();
    abstract public function delete();
}

?>
