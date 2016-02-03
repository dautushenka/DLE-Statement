<?php

require dirname(__FILE__) . '/FormAbstract.php';

/**
 * Description of Comment
 *
 * @author kaliostro
 */
class FormComment extends FormAbstract {
    
    /**
     *
     * @var Statement
     */
    protected $_st;
    
    protected $_fields = array(
            'text' => array('required' => true),
    );
    
    protected $_filters = array(
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
        if (!$this->_user->user_id)
        {
            $this->_fields['name'] = array(array($this, 'validName'));
            $this->_fields['email'] = array(array($this, 'validEmail'));
        }
    }
    
    public function getModel()
    {
        $model = new Comment();
        $model->setText($this->_data['text']);
        $model->setStatementId($this->_st->getId());
        
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
        
        $model->setIpAddress($this->_user->ip_address);
        
        return $model;
    }
    
    protected function _getVars()
    {
        return array('statement_id' => $this->_st->getId());
    }
    
    public function setStatement(Statement $st)
    {
        $this->_st = $st;
    }
}

?>
