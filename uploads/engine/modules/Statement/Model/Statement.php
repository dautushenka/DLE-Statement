<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Statement
 *
 * @author kaliostro
 */
class Statement extends ModelAbstract 
{
    protected $_id;
    protected $_title = '';
    protected $_teaser = '';
    protected $_text = '';
    protected $_user_id = 0;
    protected $_username = '';
    protected $_email = '';
    protected $_answer = '';
    protected $_answer_id = 0;
    protected $_answer_name = '';
    protected $_category = '';
    protected $_status = 'waiting';
    protected $_plus_count = 0;
    protected $_minus_count = 0;
    protected $_comm_num = 0;
    protected $_date = 0;
    protected $_comments = '';
    protected $_new_comment = '';
    protected $_type = 'idea';

    protected $_isset = false;
    
    static public $statues = array(
        'waiting'       => 'Ожидает рассмотрения',
        'working'       => 'Делается',
        'scheduled'     => 'Запланирован',
        'canceled'      => 'Отклонен',
        'performed'     => 'Выполнен',
            
    );
    
    static public $types = array(
        'question'      => 'Вопрос',
        'idea'          => 'Предложение',
        'error'         => 'Ошибка',
        'thank'         => 'Благодарность'
    );
    
    static protected $_count;
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function setId($_id)
    {
        $this->_id = (int)$_id;
    }
    
    public function getTitle()
    {
        return $this->_title;
    }
    
    public function setTitle($_title)
    {
        $this->_title = (string)$_title;
    }
    
    public function getTeaser()
    {
        return $this->_teaser;
    }
    
    public function setTeaser($_teaser)
    {
        $this->_teaser = (string)$_teaser;
    }
    
    public function setText($_text)
    {
        $this->_text = (string)$_text;
    }
    
    public function getText()
    {
        return $this->_text;
    }
    
    public function setUserId($_user_id)
    {
        $this->_user_id = (int)$_user_id;
    }
    
    public function getUserId()
    {
        return $this->_user_id;
    }
    
    public function getUsername()
    {
        return $this->_username;
    }
    
    public function setUsername($_username)
    {
        $this->_username = (string)$_username;
    }
    
    public function getAnswer()
    {
        return $this->_answer;
    }
    
    public function setAnswer($_answer)
    {
        $this->_answer = (string)$_answer;
    }
    
    public function getAnswerId()
    {
        return $this->_answer_id;
    }
    
    public function setAnswerId($_answer_id)
    {
        $this->_answer_id = (int)$_answer_id;
    }
    
    public function getAnswerName()
    {
        return $this->_answer_name;
    }
    
    public function setAnswerName($_answer_name)
    {
        $this->_answer_name = (string)$_answer_name;
    }
    
    public function getCategory()
    {
        return $this->_category;
    }

    public function setCategory($category)
    {
        $this->_category = $category;
    }

    public function getStatus()
    {
        return $this->_status;
    }
    
    public function getStatusName()
    {
        return self::$statues[$this->getStatus()];
    }

    public function getPlusCount()
    {
        return $this->_plus_count;
    }

    public function getMinusCount()
    {
        return $this->_minus_count;
    }

    public function getCommNum()
    {
        return $this->_comm_num;
    }

    public function getDate()
    {
        return $this->_date;
    }

    public function setStatus($status)
    {
        if (!isset(self::$statues[$this->getStatus()]))
        {
            throw new Exception('Status not found');
        }
        
        $this->_status = $status;
    }

    public function setPlusCount($plus_count)
    {
        $plus_count = (int)$plus_count;
        $this->_plus_count = $plus_count < 0 ? 0 : $plus_count;
    }

    public function setMinusCount($minus_count)
    {
        $minus_count = (int)$minus_count;
        $this->_minus_count = $minus_count < 0?0:$minus_count;
    }

    public function setCommNum($comm_num)
    {
        $this->_comm_num = (int)$comm_num;
    }

    public function setDate($date)
    {
        $this->_date = (int)$date;
    }
    
    public function getIsset()
    {
        return $this->_isset;
    }

    public function setIsset($isset)
    {
        $this->_isset = empty($isset)?false:true;
    }
    
    public function getComments()
    {
        return $this->_comments;
    }

    public function setComments($comments)
    {
        $this->_comments = (string)$comments;
    }
    
    public function getNewComment()
    {
        return $this->_new_comment;
    }

    public function setNewComment($new_comment)
    {
        $this->_new_comment = (string)$new_comment;
    }
    
    public function getEmail()
    {
        return $this->_email;
    }

    public function setEmail($email)
    {
        $this->_email = $email;
    }

    public function getType()
    {
        return $this->_type;
    }

    public function setType($type)
    {
        $this->_type = $type;
    }
    
    public function getTypeName()
    {
        return self::$types[$this->getType()];
    }

    public function getCategoryName($subcats = array())
    {
        if (empty($subcats))
        {
            $subcats = FrontController::$config['categories'];
        }
        
        foreach ($subcats as $key => $value)
        {
            if (is_array($value) && $key !== $this->_category)
            {
                $return = $this->getCategoryName($value);
                if ($return)
                {
                    return $return;
                }
            }
            else if (is_array($value))
            {
                return $value[0];
            }
            else if ($key === $this->_category)
            {
                return $value;
            }
        }
        
        return '';
    }

    public function save()
    {
        $data = array(
            'title'         => "'" . self::$db->safesql($this->getTitle()) . "'",
            'teaser'        => "'" . self::$db->safesql($this->getTeaser()) . "'",
            'text'          => "'" . self::$db->safesql($this->getText()) . "'",
            'user_id'       => $this->getUserId(),
            'username'      => "'" . self::$db->safesql($this->getUsername()) . "'",
            'answer'        => "'" . self::$db->safesql($this->getAnswer()) . "'",
            'answer_id'     => $this->getAnswerId(),
            'answer_name'   => "'" . self::$db->safesql($this->getAnswerName()) . "'",
            'category'      => "'" . self::$db->safesql($this->getCategory()) . "'",
            'status'        => "'" . self::$db->safesql($this->getStatus()) . "'",
            'comm_num'      => $this->getCommNum(),
            'date'          => $this->getDate(),
            'plus_count'    => $this->getPlusCount(),
            'minus_count'   => $this->getMinusCount(),
            'type'          => "'" . $this->getType() . "'",
                
        );
        
        $set_a = array();
        foreach ($data as $column => $value)
        {
            $set_a[] = "`$column`=" . $value;
        }
        
        if ($this->getId())
        {
            self::$db->query("UPDATE " . PREFIX . "_statement SET " . implode(", ", $set_a) . " WHERE id=" . $this->getId());
        }
        else
        {
            self::$db->query("INSERT INTO " . PREFIX . "_statement SET " . implode(", ", $set_a));
            $this->setId(self::$db->insert_id());
        }
    }
    
    public function delete()
    {
        $comments = Comment::getCommetsByStatementId($this->getId());
        foreach ($comments as $comment)
        {
            $comment->delete();
        }
        
        self::$db->query('DELETE FROM ' . PREFIX . "_statement_log WHERE statement_id=" . $this->getId());
        self::$db->query('DELETE FROM ' . PREFIX . "_statement WHERE id=" . $this->getId());
    }

    public function addVote($type, $user)
    {
        self::$db->query("INSERT INTO " . PREFIX . "_statement_log 
                    (statement_id, user_id, ip_address, type) VALUES 
                    (" . $this->getId() .", {$user->user_id}, '{$user->ip_address}', '$type')");
                    
        if ((int)$type == -1)
        {
            $this->setMinusCount($this->getMinusCount() + 1);
        }
        else
        {
            $this->setPlusCount($this->getPlusCount() + 1);
        }
        
        return $this;
    }
    
    public function cancelVote($user)
    {
        if ($user->user_id)
        {
            $log = self::$db->super_query("SELECT * FROM " . PREFIX . "_statement_log WHERE (user_id=" . $user->user_id . " OR ip_address='" . $user->ip_address . "') AND statement_id=" . $this->getId());
        }
        else
        {
            $log = self::$db->super_query("SELECT * FROM " . PREFIX . "_statement_log WHERE ip_address='" . $user->ip_address . "' AND statement_id=" . $this->getId());
        }   
        
        
        if (!$log)
            throw new Exception('Не найден голос');

        if ((int)$log['type'] == -1)
        {
            $this->setMinusCount($this->getMinusCount() - 1);
        }
        else
        {
            $this->setPlusCount($this->getPlusCount() - 1);
        }
        
        self::$db->query("DELETE FROM " . PREFIX . "_statement_log WHERE id=" . $log['id']);
        
        return $this;
    }
    
    public function addSubscriber(stdClass $user)
    {
        if ($user->user_id)
        {
            $s = self::$db->super_query("SELECT * FROM " . PREFIX . "_statement_subscribers WHERE statement_id=" . $this->getId() . " AND user_id=" . $user->user_id);
        }
        else
        {
            $s = self::$db->super_query("SELECT * FROM " . PREFIX . "_statement_subscribers WHERE statement_id=" . $this->getId() . " AND email='" . $user->email . "'");
        }
        
        if ($s)
        {
            return false;
        }
        
        if ($user->user_id)
        {
            $s = self::$db->super_query("INSERT INTO " . PREFIX . "_statement_subscribers (statement_id, user_id) VALUES (" . $this->getId() . ", {$user->user_id})");
        }
        else
        {
            $username = self::$db->safesql($user->name);
            $email = self::$db->safesql($user->email);
            $s = self::$db->super_query("INSERT INTO " . PREFIX . "_statement_subscribers (statement_id, username, email) VALUES (" . $this->getId() . ", '{$username}', '{$email}')");
        }
        
        return true;
    }
    
    public function removeSubscriber(stdClass $user)
    {
        self::$db->query('DELETE FROM ' . PREFIX . "_statement_subscribers WHERE statement_id=" . $this->getId() . " AND ((user_id AND user_id={$user->user_id}) OR email='" . self::$db->safesql($user->email) ."')");
    }
    
    public function getSubscribers()
    {
        self::$db->query("SELECT ss.*, u.name, u.email AS u_email FROM " . PREFIX . "_statement_subscribers AS ss
                         LEFT JOIN " . USERPREFIX . "_users AS u
                         ON u.user_id=ss.user_id
                         WHERE statement_id=" . $this->getId());
        
        $users = array();
        while($row = self::$db->get_row())
        {
            $user = new stdClass();
            $user->user_id = $row['user_id'];
            $user->name = $row['name']?$row['name']:$row['username'];
            $user->email = $row['u_email']?$row['u_email']:$row['email'];
            
            $users[] = $user;
        }
        
        return $users;
    }
    
    /**
     *
     * @param Request $request
     * @param stdClass $user
     * @param array $limit
     * @return \self 
     */
    static public function get(Request $request, stdClass $user, array $limit = array('offset' => 0, 'limit' => 10))
    {
        $where = array();
        $where_str = '';
        
        if ($request->has('show') && $request->get('show') == 'filter')
        {
            if ($request->has('type'))
            {
                $where[] = "s.type IN ('" . implode("', '", (array)$request->get('type')) . "')";
            }

            if ($request->has('categories'))
            {
                $where[] = "category IN ('" . implode("', '", (array)$request->get('categories')) . "')";
            }

            if ($request->has('status'))
            {
                $where[] = "status IN ('" . implode("', '", (array)$request->get('status')) . "')";
            }

            if ($request->has('time'))
            {
                switch ($request->get('time'))
                {
                    case 'today':
                        $where[] = "date > " . mktime(0, 0, 0, date('n'), date('d'), date('Y'));
                        break;

                    case 'week':
                        $where[] = "date > " . time() - 7 * 24 * 3600;
                        break;

                    case 'month':
                        $where[] = "date > " . time() - 30 * 24 * 3600;
                        break;

                    default:
                        break;
                }
            }

            if ($where)
                $where_str = "WHERE " . implode(' AND ', $where) . " ";
        }
        
        switch ($request->get('order'))
        {
            default:
            case 'vote':
                $order = ' ORDER BY (plus_count - minus_count) DESC ';
                break;
            
            case 'date':
                $order = ' ORDER BY `date` DESC ';
                break;
            
            case 'comments':
                $order = ' ORDER BY `comm_num` DESC ';
                break;
        }
        
        
        
        
        $count_query = "SELECT COUNT(*)AS count FROM " . PREFIX . "_statement AS s " . $where_str;
        $count = self::$db->super_query($count_query);
        self::$_count = $count['count'];
        
        $query = "SELECT s.*, l.statement_id AS isset FROM " . PREFIX . "_statement AS s
                LEFT JOIN " . PREFIX . "_statement_log AS l
                ON l.statement_id=s.id AND ((l.user_id AND l.user_id={$user->user_id}) OR l.ip_address='" . $user->ip_address . "')
                {$where_str}{$order}LIMIT " . $limit['offset'] . ", " . $limit['limit'];
        
        self::$db->query($query);
        
        $sts = array();
        while($row = self::$db->get_row())
        {
            $sts[] = new self($row);
        }
        
        return $sts;
    }
    
    static public function getCount()
    {
        return self::$_count;
    }
    
    /**
     *
     * @param int $id
     * @param stdClass $user
     * @return Statement|null 
     */
    static public function getById($id, stdClass $user = null)
    {
        if (!$user)
        {
            $user = FrontController::getUser();
        }
        
        $row = self::$db->super_query("SELECT s.*, l.id AS isset FROM " . PREFIX . "_statement AS s
                    LEFT OUTER JOIN " . PREFIX . "_statement_log AS l
                    ON l.statement_id=s.id AND (l.user_id={$user->user_id} OR l.ip_address='" . $user->ip_address . "')
                    WHERE s.id=" . $id);
                  
        if ($row)
            return new self($row);
        else
            return null;
    }
    
    /**
     *
     * @param string $str
     * @return \self 
     */
    static public function search($str)
    {
        $str = self::$db->safesql(addcslashes($str, '%_'));
        $res = self::$db->query('SELECT * FROM ' . PREFIX . "_statement AS s WHERE title LIKE '%$str%' LIMIT 7");
        
        $sts = array();
        while($row = self::$db->get_row($res))
        {
            $sts[] = new self($row);
        }
        
        return $sts;
    }
}

?>
