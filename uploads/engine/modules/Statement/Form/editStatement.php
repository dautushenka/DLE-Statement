<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once dirname(__FILE__) . '/FormAbstract.php';
require_once dirname(__FILE__) . '/Statement.php';

/**
 * Description of Statement
 *
 * @author kaliostro
 */
class editFormStatement extends FormStatement
{
    /**
     * @var Statement
     */
    protected $_statement;

    protected function _build()
    {
        parent::_build();

        $this->_fields['status']['in'] = array_keys(Statement::$statues);
        $this->_fields['answer'] = array();

        $this->_filters['answer'] = array('trim' => array());
    }

    
    public function updateStatement()
    {
        $this->_statement->setTitle($this->_data['title']);
        $this->_statement->setText($this->_data['text']);
        $this->_statement->setCategory($this->_data['category']);
        $this->_statement->setType($this->_data['type']);
        $this->_statement->setAnswer($this->_data['answer']);
        $this->_statement->setStatus($this->_data['status']);
    }

    public function setStatement(Statement $st)
    {
        $this->_statement = $st;

        $this->_original_data['id']          = $st->getId();
        $this->_original_data['answer']      = $st->getAnswer();
        $this->_original_data['text']        = $this->getParse()->decodeBBCodes($st->getText(), false);
        $this->_original_data['title']       = $st->getTitle();
        $this->_original_data['type']        = $st->getType();
        $this->_original_data['category']    = $st->getCategory();
        $this->_original_data['status']      = $st->getStatus();
    }

    protected function _getVars()
    {
        $vars = parent::_getVars();

        $status = '<select name="status"><option value=""></option>';
        foreach (Statement::$statues as $key => $value)
        {
            if ($key == $this->_original_data['status'])
            {
                $selected = " selected='selected'";
            }
            else
            {
                $selected = '';
            }

            $status .= "<option value='$key'$selected>$value</option>";
        }
        $status .= '</select>';

        return array_merge($vars, array('status' => $status));
    }
}

?>
