<?php


/**
 * Description of SubscribeEvent
 *
 * @author kaliostro
 */
class SubscribeEvent implements IEventSubscriber
{
    public function __construct()
    {
        ;
    }
    
    public function getEvents()
    {
        return array(
            'statement.new.completed'       => 'onNewStatement',
            'comment.new.completed'         => 'onNewComment',
            'statement.setAnswer.completed' => 'onSetAnswer',
            'FrontController.dispatch'      => 'onDispatch',
            'comment.new.render'            => 'onNewCommentRender'
        );
    }
    
    public function onNewStatement(FrontController $sender, Statement $st)
    {
        $user = FrontController::getUser();
        
        if (!$user->user_id)
        {
            $guest = clone $user;
            $guest->email = $st->getEmail();
            $guest->name = $st->getUsername();
            
            $this->_subscribe($st, $guest);
        }
        else
        {
            $this->_subscribe($st, $user);
        }
    }

    public function onNewComment(FrontController $sender, Comment $comment, Statement $st)
    {
        $users = $st->getSubscribers();
        
        if ($users)
        {
            require_once ST_DIR . '/Classes/Mailer.php';
            $mailer = new Mailer();
            
            $vars = array(
                    'text'          => strip_tags($comment->getText()),
                    'st_link'       => FrontController::getURLByRoute('view', array('id' => $st->getId()), true),
                    'title'         => $st->getTitle(),
                    'comment_user'  => $comment->getUsername()
                );
            
            foreach ($users as $user)
            {
                if ($user->user_id == $comment->getUserId() ||
                    $user->email == $comment->getEmail())
                {
                    continue;
                }
            
                $vars['username'] = $user->name;
                $vars['unSubscribeLink'] = $this->_getSubscribeLink($user->email, $user->user_id, $st->getId());
                
                $mailer->sendMail('SubscribeNewComment', $user->email, $vars);
            }
        }
        
        $user = FrontController::getUser();
        
        if (!$user->user_id)
        {
            $guest = clone $user;
            $guest->email = $comment->getEmail();
            $guest->name = $comment->getUsername();
            
            $this->_subscribe($st, $guest);
        }
        else
        {
            $this->_subscribe($st, $user);
        }
    }
    
    public function onSetAnswer(FrontController $sender, Statement $st)
    {
        $users = $st->getSubscribers();
        
        if ($users)
        {
            require_once ST_DIR . '/Classes/Mailer.php';
            $mailer = new Mailer();
            
            $vars = array(
                    'answer'        => strip_tags($st->getAnswer()),
                    'st_link'       => FrontController::getURLByRoute('view', array('id' => $st->getId()), true),
                    'title'         => $st->getTitle(),
                    'status'        => $st->getStatusName()
                );
            
            foreach ($users as $user)
            {
                $vars['username'] = $user->name;
                $vars['unSubscribeLink'] = $this->_getSubscribeLink($user->email, $user->user_id, $st->getId());
                
                $mailer->sendMail('SubscribeStatementSetAnswer', $user->email, $vars);
            }
        }
        
    }
    
    public function onDispatch(FrontController $sender, $action)
    {
        if ($action == 'unsubscribe')
        {
            $request = FrontController::getRequest();
            
            if ($request->get('hash') === $this->_getSubscribeHash($request->get('e'), $request->get('u'), $request->get('s')))
            {
                $st = Statement::getById($request->getClean('s'), FrontController::getUser());
                
                if (empty($st))
                {
                    $sender->msg('Error', 'Запрашиваемой идеи не найдено');
                }
                
                $user = new stdClass();
                $user->name = '';
                $user->user_id = $request->getClean('u');
                $user->email = $request->get('e');
                
                $st->removeSubscriber($user);
                
                $sender->msg('Подписка', 'Вы были удачно отписаны от рассылки');
            }
            else
            {
                $sender->msg('Error', 'Ошибка проверки хеша');
            }
            
            return false;
        }
        
        return true;
    }
    
    public function onNewCommentRender(FrontController $sender, array $vars)
    {
       
    }
    
    protected function _subscribe(Statement $st, stdClass $user)
    {
        $request = FrontController::getRequest();
        
        
        if (!$request->getClean('subscribe'))
        {
            return;
        }
        
        $st->addSubscriber($user);
    }
    
    protected function _getSubscribeHash($email, $user_id, $st_id)
    {
        return md5(sha1(DBPASS . $st_id . $user_id . $email . DBUSER));
    }
    
    protected function _getSubscribeLink($email, $user_id, $st_id)
    {
        return FrontController::getURLByRoute('unsubscribe', 
                                              array('user_id'       => $user_id, 
                                                    'statement_id'  => $st_id, 
                                                    'email'         => $email, 
                                                    'hash'          => $this->_getSubscribeHash($email, $user_id, $st_id)),
                                              true);
    }
}

?>
