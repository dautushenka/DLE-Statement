<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require dirname(__FILE__) . '/FormAbstract.php';

/**
 * Description of Statement
 *
 * @author kaliostro
 */
class FormFilter extends FormAbstract
{
    protected $_csrf_protected = false;
    
    protected $_bot_protected = false;
    
    protected $_fields = array(
            'type' => array('in' => array('question', 'idea', 'error', 'thank')),
            'status' => array('in' => array('waiting','working','scheduled','canceled','performed')),
            'categories' => array(),
    );
    
    protected function _build()
    {
        $this->_fields['categories'] += array(array($this, 'validCategory'));
    }
    
    public function validCategory($value)
    {
        if (!$value)
            return false;
        
        if (!function_exists('getAllCats'))
        {
            function getAllCats($subcats)
            {
                $return = array();
                foreach ($subcats as $key => $value)
                {
                    if (is_array($value))
                    {
                        $return = array_merge($return, getAllCats($value));
                    }
                    else if (!is_numeric($key))
                        $return[] = $key;
                }

                return $return;
            }
        }
        
        $cat_all = getAllCats(FrontController::$config['categories']);
            
        
        foreach ((array)$value as $val)
        {
            if (!in_array($val, $cat_all))
                return 'Указана не верная категория';
        }
        
        return false;
    }
    
    protected function _getVars()
    {
        return array('categories' => $this->_getCategoriesInput(FrontController::$config['categories']));
    }
    
    protected function _getCategoriesInput($categories)
    {
        $return = '';
        
        foreach ($categories as $key => $value)
        {
            if (is_numeric($key))
                continue;
            
            if (is_array($value))
            {
                $return .= "<fieldset><legend>{$key}</legend>";
                $return .= $this->_getCategoriesInput($value);
                $return .= "</fieldset>";
            }
            else
                $return .= "<label><input type='checkbox' name='categories[]' value='$key' />" . $value . "</label><br />";
        }
        
        return $return;
    }
}

?>
