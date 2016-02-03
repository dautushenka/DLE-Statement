<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Comment
 *
 * @author kaliostro
 */
class Comment extends ModelAbstract {
    
    protected $_id;
    
    protected $_statement_id;
    
    protected $_user_id = 0;
    
    protected $_username;
    
    protected $_email;
    
    protected $_text;
    
    protected $_date;
    
    protected $_ip_address;

    public function getId()
    {
        return $this->_id;
    }

    public function getStatementId()
    {
        return $this->_statement_id;
    }

    public function getUserId()
    {
        return $this->_user_id;
    }

    public function getUsername()
    {
        return $this->_username;
    }

    public function getText()
    {
        return $this->_text;
    }

    public function getDate()
    {
        return $this->_date;
    }

    public function getIpAddress()
    {
        return $this->_ip_address;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function setId($id)
    {
        $this->_id = (int)$id;
    }

    public function setStatementId($statement_id)
    {
        $this->_statement_id = (int)$statement_id;
    }

    public function setUserId($user_id)
    {
        $this->_user_id = (int)$user_id;
    }

    public function setUsername($username)
    {
        $this->_username = $username;
    }

    public function setText($text)
    {
        $this->_text = $text;
    }

    public function setDate($date)
    {
        $this->_date = $date;
    }

    public function setIpAddress($ip_address)
    {
        $this->_ip_address = $ip_address;
    }

    public function setEmail($email)
    {
        $this->_email = $email;
    }
    
    /**
     *
     * @return Statement
     */
    public function getStatement()
    {
        return Statement::getById($this->getStatementId());
    }

    public function save()
    {
        $data = array(
            'text'          => "'" . self::$db->safesql($this->getText()) . "'",
            'statement_id'  => $this->getStatementId(),
            'user_id'       => $this->getUserId(),
            'username'      => "'" . self::$db->safesql($this->getUsername()) . "'",
            'email'         => "'" . self::$db->safesql($this->getEmail()) . "'",
            'date'          => $this->getDate(),
            'ip_address'    => "'" . self::$db->safesql($this->getIpAddress()) . "'",
                
        );
        
        $set_a = array();
        foreach ($data as $column => $value)
        {
            $set_a[] = "`$column`=" . $value;
        }
        
        if ($this->getId())
        {
            self::$db->query("UPDATE " . PREFIX . "_statement_comm SET " . implode(", ", $set_a) . " WHERE id=" . $this->getId());
        }
        else
        {
            self::$db->query("INSERT INTO " . PREFIX . "_statement_comm SET " . implode(", ", $set_a));
            $this->setId(self::$db->insert_id());
        }
    }
    
    public function delete()
    {
        self::$db->query('DELETE FROM ' . PREFIX . "_statement_comm WHERE id=" . $this->getId());
    }

    /**
     *
     * @param int $statement
     * @return array
     */
    static public function getCommetsByStatementId($statement_id)
    {
        $res = self::$db->query('SELECT * FROM ' . PREFIX . "_statement_comm WHERE statement_id={$statement_id} ORDER BY `date`");
        
        $comms = array();
        while($row = self::$db->get_row($res))
        {
            $comms[] = new self($row);
        }
        
        return $comms;
    }
    
    /**
     *
     * @param int $id
     * @return Comment|null 
     */
    static public function getById($id)
    {
        $comment = self::$db->super_query('SELECT * FROM ' . PREFIX . "_statement_comm WHERE id={$id}");
        
        return $comment?new self($comment):null;;
    }
}

?>
