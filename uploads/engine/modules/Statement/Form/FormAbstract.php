<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FormAbstract
 *
 * @author kaliostro
 */
abstract class FormAbstract 
{
    protected $_data = array();
    protected $_original_data = array();
    
    protected $_fields = array();
    
    protected $_filters = array();
    
    protected $_errors = array();
    
    protected $_user;
    
    protected $_parse;
    
    protected $_csrf_protected = true;
    
    protected $_bot_protected = true;

    public function __construct()
    {
        if ($this->_csrf_protected)
        {
            $this->_fields['_csrf_token'] = array(array($this, 'validCSRF'));
        }
        
        if ($this->_bot_protected)
        {
            $this->_fields['antibot'] = array(array($this, 'checkBot'));
        }
        
        $this->_build();
    }
    
    protected function _build() {}
    
    public function isValid()
    {
        $this->_errors = array();
        
        foreach ($this->_fields as $field => $validators)
        {
            if (empty($validators))
            {
                continue;
            }
            
            $result = array();
            
            foreach ($validators as $validator => $settings)
            {
                $value = isset($this->_data[$field])?$this->_data[$field]:null;
                
                if ($validator === 'required' && $settings)
                {
                    if (empty($value))
                            {
                                $result[] = 'Обязательное поле';
                            }
                }
                else if (is_numeric($validator))
                {
                    if (is_array($settings))
                    {
                        if ($error = call_user_func($settings, $value))
                                {
                                    $result[] = $error;
                                }
                    }
                    else if ($error = $settings($value))
                    {
                        $result[] = $error;
                    }
                }
                else
                {
                    $value = (array)$value;
                    
                    foreach($value as $val)
                    {
                        if ($validator === 'minlength')
                        {
                            if (mb_strlen($val) < $settings)
                                    {
                                        $result[] = sprintf('Минимальная длина данного поля %d символов', $settings);
                                    }
                        }
                        else if ($validator === 'maxlength')
                        {
                            if (mb_strlen($val) > $settings)
                                    {
                                        $result[] = sprintf('Максимальная длина данного поля %d символов', $settings);
                                    }
                        }
                        else if ($validator === 'in')
                        {
                            if ($val && !in_array($val, $settings))
                                    {
                                        $result[] = 'Не верное значение';
                                    }
                        }
                    }
                }
            }

            if ($result)
                    {
                        $this->_errors[$field] = $result;
                    }
        }
        
        return !(bool)$this->_errors;
    }
    
    public function bindRequest(Request $request)
    {
        foreach ($this->_fields as $key => $value)
        {
            if ($request->has($key))
            {
                $this->_data[$key] = $this->_original_data[$key] = $request->get($key, '');
            }
        }
        
        $this->_filterData();
    }

    public function setData(array $data)
    {
        foreach ($data as $key => $value)
        {
            $this->_data[$key] = $value;
        }
    }

    
    public function getVars()
    {
        $vars = $this->_original_data;
        
        if ($this->_errors)
        {
            $vars['errors'] = '<ul>';
            foreach ($this->_errors as $field => $errors)
            {
                $vars['errors'] .= "<li>";
                $vars['errors'] .= $vars[$field . '_error'] = $this->_generateErrors($errors);
                $vars['errors'] .= "</li>";
            }
        }
        
        if ($this->_csrf_protected)
        {
            $vars['_csrf_token'] = md5(Session::getInstance()->getId() . DBPASS . get_class($this));
        }
        
        if ($this->_bot_protected)
        {
            $vars['antibot'] = md5(sha1(uniqid() . DBNAME . get_class($this)));
            Session::getInstance()->set(get_class($this) . "_antibot", $vars['antibot']);
        }
        
        $vars = array_merge($vars, $this->_getVars());
        
        return $vars;
    }

    /**
     * @return ParseFilter
     */
    public function getParse()
    {
        if (!($this->_parse instanceof ParseFilter))
        {
            require_once ENGINE_DIR . '/classes/parse.class.php';
            $this->_parse = new ParseFilter();
        }
        
        return $this->_parse;
    }
    
    public function getErrors()
    {
        return $this->_errors;
    }
    
    protected function _getVars()
    {
        return array();
    }

    protected function _generateErrors($errors)
    {
        $text = '<ul>';
        foreach ($errors as $error)
        {
            $text .= "<li>$error</li>\n";
        }
        
        return $text . "</ul>";
    }
    
    protected function _filterData()
    {
        foreach ($this->_data as $key => &$value)
        {
            if (empty($this->_filters[$key]))
            {
                continue;
            }
            
            $parse = $this->getParse();
            $parse->safe_mode = true;
            $parse->allow_url = $GLOBALS['user_group'][$this->_user->group]['allow_url'];
            $parse->allow_image = $GLOBALS['user_group'][$this->_user->group]['allow_image'];
            
            foreach ($this->_filters[$key] as $filter => $settings)
            {
                if (is_numeric($filter))
                {
                    if (is_array($settings))
                    {
                        $value = call_user_func($settings, $value);
                    }
                    else
                    {
                        $value = $settings($value);
                    }
                }
                else if ($filter === 'strip_html_js')
                {
                    $value = $parse->process($value);
                }
                else if ($filter === 'trim')
                {
                    $value = trim($value);
                }
                else if ($filter === 'bbcode')
                {
                    $value = $parse->BB_Parse($value, false);
                }
                else
                {
                    throw new Exception('Не найден заданный фильтр');
                }
            }
        }
    }
    
    public function setUser(stdClass $user)
    {
        $this->_user = $user;
    }
    
    public function validName($value)
    {
        if (empty($value))
        {
            return 'Укажите ваше имя';
        }
        
        return false;
    }
    
    public function validEmail($value)
    {
        if ((empty($value) || !filter_var($value, FILTER_VALIDATE_EMAIL)))
        {
            return 'Укажите верный email-адрес';
        }
        
        return false;
    }
    
    public function validCSRF($value)
    {
        if ($value !== md5(Session::getInstance()->getId() . DBPASS . get_class($this)))
        {
            throw new Exception('CSRF Protection');
        }
    }
    
    public function checkBot($value)
    {
        if ($value !== Session::getInstance()->get(get_class($this) . "_antibot"))
        {
            throw new Exception('Bot Protection');
        }
    }
}

?>
