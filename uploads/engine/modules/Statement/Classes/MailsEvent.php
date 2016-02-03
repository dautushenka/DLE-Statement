<?php


/**
 * Description of MailsEvent
 *
 * @author kaliostro
 */
class MailsEvent implements IEventSubscriber
{
    public function __construct()
    {
        ;
    }
    
    public function getEvents()
    {
        return array(
            'statement.new.completed' => 'onNewStatement',
            'comment.new.completed' => 'onNewComment',
            'statement.setAnswer.completed' => 'onSetAnswer'
        );
    }
    
    /**
     *
     * @staticvar null $mail
     * @return \dle_mail 
     */
    public function getMailer()
    {
        static $mail = null;
        
        if (!$mail)
        {
            require_once ST_DIR . '/Classes/Mailer.php';
            $mail = new Mailer();
        }
        
        return $mail;
    }
    
    protected function _getModerEmails()
    {
        $config = FrontController::$config;
        $db = ModelAbstract::$db;
        $mails = array();
        
        if (!empty($config['moders']))
        {
            $db->query('SELECT email FROM ' . USERPREFIX . "_users WHERE id IN (" . implode(",", $config['moders']) . ")");
            while($row = $db->get_row())
            {
                $mails[] = $row['email'];
            }
        }
        
        if (!empty($config['moder_groups']))
        {
            $db->query('SELECT email FROM ' . USERPREFIX . "_users WHERE user_group IN (" . implode(",", $config['moder_groups']) . ")");
            while($row = $db->get_row())
            {
                $mails[] = $row['email'];
            }
        }
        
        return $mails;
    }

    public function onNewStatement(FrontController $sender, Statement $st)
    {
        $vars = array(
            'username' => $st->getUsername(),
            'st_link' => FrontController::getURLByRoute('@view', array('id' => $st->getId()), true),
            'title' => $st->getTitle(),
            'message' => strip_tags($st->getText())
        );
        
        $this->getMailer()->sendMail('newStatement', $this->_getModerEmails(), $vars);
    }

    public function onNewComment(FrontController $sender, Comment $comment, Statement $st)
    {
        $vars = array(
            'username' => $comment->getUsername(),
            'text' => strip_tags($comment->getText()),
            'st_link' => FrontController::getURLByRoute('@view', array('id' => $st->getId()), true),
            'title' => $st->getTitle(),
        );
        
        $this->getMailer()->sendMail('newComment', $this->_getModerEmails(), $vars);
    }
    
    public function onSetAnswer(FrontController $sender, Statement $st)
    {
        
    }
}

?>
