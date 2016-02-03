<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once dirname(__FILE__) . '/FormAbstract.php';

/**
 * Description of Statement
 *
 * @author kaliostro
 */
class FormStatement extends FormAbstract
{
    protected $_fields = array(
            'title' => array('required' => true, 'minlength' => 5, 'maxlength' => 110),
            'text' => array('required' => true),
            'type' => array('in' => array('question', 'idea', 'error', 'thank'), 'required' => true),
            'category' => array(),
    );
    
    protected $_filters = array(
            'title' => array('strip_html_js' => array(), 'trim' => array()),
            'text' => array('strip_html_js' => array(), 'trim' => array(), 'bbcode' => array()),
            'name' => array('strip_html_js' => array(), 'trim' => array()),
            'email' => array('trim' => array()),
    );
    
    public function __construct(stdClass $user)
    {
        $this->_user = $user;
        
        parent::__construct();
    }
    
    protected function _build()
    {
        $this->_fields['category'] += array(array($this, 'validCategory'));
        $this->_fields['type']['in'] = array_keys(Statement::$types);
        
        if (!$this->_user->user_id)
        {
            $this->_fields['name'] = array(array($this, 'validName'));
            $this->_fields['email'] = array(array($this, 'validEmail'));
        }
    }
    
    public function validCategory($value, $subcats = array())
    {
        if (empty($subcats))
            $subcats = FrontController::$config['categories'];
        
        foreach ($subcats as $name => $title)
        {
            if (is_array($title) && !$this->validCategory($value, $title))
                return false;
            else if ($name === $value)
                return false;
        }
        
        return 'Не выбрана категория';
    }
    
    public function getModel()
    {
        $model = new Statement();
        $model->setTitle($this->_data['title']);
        $model->setText($this->_data['text']);
        $model->setCategory($this->_data['category']);
        $model->setType($this->_data['type']);
        
        if ($this->_user->user_id)
        {
            $model->setUserId($this->_user->user_id);
            $model->setUsername($this->_user->name);
        }
        else
        {
            $model->setUserId(0);
            $model->setUsername($this->_data['name']);
            $model->setEmail($this->_data['email']);
        }
        
        return $model;
    }
    
    protected function _getVars()
    {
        $categories = '<select name="category"><option value=""></option>';
        $categories .= $this->_getCategoriesOptions(FrontController::$config['categories']);
        $categories .= "</select>";

        $types = '<select name="type"><option value=""></option>';
        foreach (Statement::$types as $key => $value)
        {
            if ($key == $this->_original_data['type'])
            {
                $selected = " selected='selected'";
            }
            else
            {
                $selected = '';
            }

            $types .= "<option value='$key'$selected>$value</option>";
        }
        $types .= '</select>';

        return array('categories' => $categories, 'types' => $types);
    }
    
    protected function _getCategoriesOptions($categories, $sep = '')
    {
        $return = '';
        
        foreach ($categories as $key => $value)
        {
            if (is_numeric($key))
                continue;
            
            if (is_array($value))
            {
                $return .= "><optgroup label=\"" . $sep . $key . "\">";
                $return .= $this->_getCategoriesOptions ($value, $sep . "&nbsp;&nbsp;") . "</optgroup>";
            }
            else
            {
                $return .= "<option value='$key'";
            
                if ($key === $this->_original_data['category'])
                    $return .= " selected='selected'";
                
                $return .= ">" . $sep . $value . "</option>";
            }
        }
        
        return $return;
    }
}

?>
