<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FrontController
 *
 * @author kaliostro
 */
class FrontController extends ControllerAbstract 
{
    public function configure()
    {
        $mailsEvent = new MailsEvent();
        $subscribeEvent = new SubscribeEvent();
        
        $this->eventManager->addEventSubscriber($mailsEvent);
        $this->eventManager->addEventSubscriber($subscribeEvent);
    }
    
    public function indexAction()
    {
        $this->listAction();
    }
    
    public function newStatementAction()
    {
        /*
        if (!$this->_user->user_id)
            return $this->_msg('Нужна авторизация', 'Чтобы создавать новые идеи вы должны быть авторизованы');
        */
        
        require_once ST_DIR . '/Form/Statement.php';
        
        $form = new FormStatement($this->_user);
        $form->bindRequest($this->_request);
        
        $this->eventManager->notify('statement.new.start', $this, $form);
        
        if (
            $this->_request->isMethod('post') && 
            $this->_request->has('send') &&
            $form->isValid()
            )
        {
            $statement = $form->getModel();
            
            $statement->setDate(time());
            $text = $statement->getText();
            $text = strip_tags($text);
            if (mb_strlen($text) > $this->_config['teaser_length'])
            {
                $count = $this->_config['teaser_length'];
                while(isset($text[$count - 1]) && $text[$count -1] != '.')
                {
                    $count++;
                }
                $statement->setTeaser(mb_substr($text, 0, $count));
            }
            else
            {
                $statement->setTeaser($statement->getText());
            }
            
            $this->eventManager->notify('statement.new.presave', $this, $statement);
            
            $statement->save();
            
            $this->eventManager->notify('statement.new.completed', $this, $statement);
            
            return $this->_redirect('@view', array('id' => $statement->getId()));
        }
        
        $view = $this->getViews();
        
        $vars  = $form->getVars();
        $vars['bbcode'] = $view->getBBCodes('st_text', 'statement_form');
        $vars['ulogin'] = $view->getBaseURL() . "engine/modules/Statement/Resources/addition/ulogin_xd.html";
        
        $this->eventManager->notify('statement.new.render', $this, $vars);
        
        $view->returnContent($view->render('newStatement', $vars));
        
        $this->setPgeTitle($this->t('Добавить своё предложение'));
        $this->addToBreadcrumbs($this->t('Предложения'), self::getURLByRoute('index'));
        $this->addToBreadcrumbs($this->t('Добавить своё предложение'));
    }

    public function editStatementAction()
    {
        $statement = Statement::getById($this->_request->getClean('id', 'int', 0), $this->_user);

        if (!$statement)
        {
            throw new Exception('Предложение не найдено', 404);
        }

        if (
            !in_array($this->_user->user_group, $this->_config['moder_groups']) &&
            !in_array($this->_user->user_id, $this->_config['moders'])
        )
        {
            throw new Exception('Access Denied', 403);
        }

        /*
        if (!$this->_user->user_id)
            return $this->_msg('Нужна авторизация', 'Чтобы создавать новые идеи вы должны быть авторизованы');
        */

        require_once ST_DIR . '/Form/editStatement.php';

        $form = new editFormStatement($this->_user);
        $form->setStatement($statement);
        $form->bindRequest($this->_request);

        $this->eventManager->notify('statement.edit.start', $this, $form);

        if (
            $this->_request->isMethod('post') &&
            $form->isValid()
            )
        {
            $old_answer = $statement->getAnswer();
            $form->updateStatement();

            if ($statement->getAnswer() != $old_answer)
            {
                $statement->setAnswerId($this->_user->user_id);
                $statement->setAnswerName($this->_user->name);
            }

            $text = $statement->getText();
            $text = strip_tags($text);
            if (mb_strlen($text) > $this->_config['teaser_length'])
            {
                $count = $this->_config['teaser_length'];
                while(isset($text[$count - 1]) && $text[$count -1] != '.')
                {
                    $count++;
                }
                $statement->setTeaser(mb_substr($text, 0, $count));
            }
            else
            {
                $statement->setTeaser($statement->getText());
            }

            $this->eventManager->notify('statement.edit.presave', $this, $statement);

            $statement->save();

            $this->eventManager->notify('statement.edit.completed', $this, $statement);

            return $this->_redirect('@view', array('id' => $statement->getId()));
        }

        $view = $this->getViews();

        $vars  = $form->getVars();
        $vars['bbcode'] = $view->getBBCodes('st_text', 'statement_form');

        $this->eventManager->notify('statement.new.render', $this, $vars);

        $view->returnContent($view->render('editStatement', $vars));

        $this->setPgeTitle($this->t('Добавить своё предложение'));
        $this->addToBreadcrumbs($this->t('Предложения'), self::getURLByRoute('index'));
        $this->addToBreadcrumbs($this->t('Добавить своё предложение'));
    }
    
    public function listAction()
    {
        $view = $this->getViews();
        $list_head = $view->render('list_head');
        
        require_once ST_DIR . '/Form/Filter.php';
        $filter = new FormFilter();
        $filter->bindRequest($this->_request);
        
        $filter_html = $view->render('filter', $filter->getVars());
        
        if ($filter->isValid())
        {
            $sts = Statement::get($this->_request, $this->_user, 
                                            array(
                                                'offset' => ($this->_request->getClean('page', 'int', 1) - 1) * $this->_config['statement_per_page'], 
                                                'limit' => $this->_config['statement_per_page']
                                                )
                                );
        }
        else
        {
            throw new Exception('Не верные данные формы');
        }
     
        $rows = '';
        foreach ($sts as $st)
        {
            $rows .= $view->renderStatement($st, 'statement_short');
        }
        
        $pages = $view->PageNavigation('list', Statement::getCount(), $this->_config['statement_per_page']);
        $sts = $view->render('list', array('rows' => $rows, 'pages' => $pages));
        
        if ($this->_request->isAJAX())
        {
            $this->renderText($sts, 'text/html');
        }
        $view->returnContent($view->render('listAction', array('head' => $list_head, 'filter' => $filter_html, 'statements' => $sts)));
        
        $this->setPgeTitle($this->t('Предложения'));
        $this->addToBreadcrumbs($this->t('Предложения'));
    }
    
    public function viewAction()
    {
        $st = Statement::getById($this->_request->getClean('id', 'int', 0), $this->_user);
        
        if (!$st)
        {
            throw new Exception('Предложение не найдено', 404);
            //return $this->_msg ('Ошибка', 'Предложение не найдено');
        }
        
        $view = $this->getViews();
        
        $comments = Comment::getCommetsByStatementId($st->getId());
        
        if ($comments)
        {
            $comms = '';
            foreach ($comments as $comment)
            {
                $comms .= $view->renderComment($comment, 'comment');
            }
            $comms_list = $view->render('comment_list', array('rows' => $comms));
            
            $st->setComments($comms_list);
        }

        /*
        require_once ST_DIR . '/Form/Comment.php';
        
        $form = new FormComment($this->_user);
        $form->setStatement($st);
        
        $vars = $form->getVars();
        $vars['bbcode'] = $view->getBBCodes('comm_text', 'comment_form');
        $vars['ulogin'] = $view->getBaseURL() . "engine/modules/Statement/Resources/addition/ulogin_xd.html";
        */
        $st->setNewComment($this->newCommentAction(true, $st));

        $view->returnContent($view->renderStatement($st, 'Statement'));

        $this->setPgeTitle($st->getTitle());
        $this->addToBreadcrumbs($this->t('Предложения'), self::getURLByRoute('index'));
        $this->addToBreadcrumbs($st->getTitle());
    }
    
    public function newCommentAction($viewAction = false, Statement $st = null)
    {
        if (!$st)
        {
            $statement_id = $this->_request->getClean('statement_id');

            if ($statement_id)
                $st = Statement::getById($statement_id, $this->_user);

            if (!$statement_id || !$st)
                throw new Exception('Unknow statement');
        }

        require_once ST_DIR . '/Form/Comment.php';
        
        $form = new FormComment($this->_user);
        $form->setStatement($st);
        $form->bindRequest($this->_request);
        
        if (
            $this->_request->isMethod('post') && 
            $form->isValid()
            )
        {
            $comment = $form->getModel();
            $comment->setDate(time());
            
            $comment->save();
            
            $st->setCommNum($st->getCommNum() + 1);
            $st->save();
            
            $this->eventManager->notify('comment.new.completed', $this, $comment, $st);
            
            return $this->_redirect('@view', array('id' => $st->getId()));
        }
        
        $view = $this->getViews();
        $vars = $form->getVars();
        $vars['bbcode'] = $view->getBBCodes('comm_text', 'comment_form');
        $vars['ulogin'] = $view->getBaseURL() . "engine/modules/Statement/Resources/addition/ulogin_xd.html";
        
        $this->eventManager->notify('comment.new.render', $this, $vars);
        
        if ($viewAction)
        {
            return $view->render('newComment', $vars);
        }
        else
        {
            $view->returnContent($view->render('newComment', $vars));
            $this->setPgeTitle(sprintf($this->t('Добавить коментарий к предложению "%s"'), $st->getTitle()));
            $this->addToBreadcrumbs($this->t('Предложения'), self::getURLByRoute('index'));
            $this->addToBreadcrumbs(sprintf($this->t('Добавить коментарий к предложению "%s"'), $st->getTitle()));
        }
    }
    
    public function setVoteAction()
    {
        $statement_id = $this->_request->getClean('statement_id');
        $vote = $this->_request->getClean('vote') === 1?1:-1;
        
        if (!$statement_id)
            return $this->renderText('{"result":"error", "code":1}');
        
        $st = Statement::getById($statement_id, $this->_user);
        
        if (!$st)
            return $this->renderText('{"result":"error", "code":2}');
        
        if ($st->getIsset())
            return $this->renderText('{"result":"error", "code":3}');
        
        $st->addVote($vote, $this->_user)->save();
        
        $return = new stdClass();
        $return->result = 'ok';
        $return->minus = -$st->getMinusCount();
        $return->plus = $st->getPlusCount();
        $return->rate = $st->getPlusCount() - $st->getMinusCount();
        
        return $this->renderText(json_encode($return));
    }
    
    public function cancelVoteAction()
    {
        $statement_id = $this->_request->getClean('statement_id');
        
        if (!$statement_id)
            return $this->renderText('{"result":"error", "code":1}');
        
        $st = Statement::getById($statement_id, $this->_user);
        
        if (!$st)
            return $this->renderText('{"result":"error", "code":2}');
        
        if (!$st->getIsset())
            return $this->renderText('{"result":"error", "code":4}');
        
        $st->cancelVote($this->_user)->save();
        
        $return = new stdClass();
        $return->result = 'ok';
        $return->minus = -$st->getMinusCount();
        $return->plus = $st->getPlusCount();
        $return->rate = $st->getPlusCount() - $st->getMinusCount();
        
        return $this->renderText(json_encode($return));
    }
    
    public function quickSearchAction()
    {
        $str = $this->_request->get('s');
        
        if (mb_strlen($str) < 3 )
            return $this->renderText('', 'text/html');
            
        $sts = Statement::search($str);
        
        if (!$sts)
            return $this->renderText('', 'text/html');
        
        $view = $this->getViews();
        
        $rows = '';
        foreach ($sts as $st)
        {
            $rows .= $view->renderStatement($st, 'statement_quick');
        }
        
        $return = $view->render('list_quick', array('rows' => $rows));
        
        $this->renderText($return, 'text/html');
    }
    
    public function setAnswerAction()
    {
        $statement_id = $this->_request->getClean('statement_id');
        $answer = $this->_request->get('answer', '');
        
        if (!$statement_id || !$answer)
            return $this->renderText('{"result":"error", "code":1}');
        
        if (
            !in_array($this->_user->user_group, $this->_config['moder_groups']) &&
            !in_array($this->_user->user_id, $this->_config['moders'])
            )
            return $this->renderText('{"result":"error", "code":5}');  
        
        $st = Statement::getById($statement_id, $this->_user);
        
        $st->setAnswer($answer);
        $st->setStatus($this->_request->get('status'));
        $st->setAnswerName($this->_user->name);
        $st->setAnswerId($this->_user->user_id);
        $st->save();
        
        $this->eventManager->notify('statement.setAnswer.completed', $this, $st);
        
        return $this->renderText('{"result":"ok"}');  
    }
    
    public function deleteAction()
    {
        $statement_id = $this->_request->getClean('statement_id');
        
        if (!$statement_id)
            return $this->renderText('{"result":"error", "code":1}');
        
        if (
            !in_array($this->_user->user_group, $this->_config['moder_groups']) &&
            !in_array($this->_user->user_id, $this->_config['moders'])
            )
            return $this->renderText('{"result":"error", "code":5}');
        
        $st = Statement::getById($statement_id, $this->_user);
        $st->delete();
        
        return $this->renderText('{"result":"ok"}');
    }
    
    public function deleteCommentAction()
    {
        $id = $this->_request->getClean('id');
        
        if (!$id)
            return $this->renderText('{"result":"error", "code":1}');
        
        $comment = Comment::getById($id);
        
        if (!$comment)
            return $this->renderText('{"result":"error", "code":1}');
        
        if (
            !in_array($this->_user->user_group, $this->_config['moder_groups']) &&
            !in_array($this->_user->user_id, $this->_config['moders']) &&
            !(
                ($this->_user->user_id == $comment->getUserId() && $comment->getUserId()) ||
                $this->_user->ip_address == $comment->getIpAddress()
                    &&
                $comment->getDate() > (time() - $this->_config['owner_comment_del_time'])
                    
             )
            )
            return $this->renderText('{"result":"error", "code":5}');
        
        $st = $comment->getStatement();
        $st->setCommNum($st->getCommNum() - 1);
        $st->save();
        
        $comment->delete();
        
        return $this->renderText('{"result":"ok"}');
    }

    public function uLoginAction()
    {
        global $db, $config;

        require ST_DIR . "/includes/ulogin.php";

        if ($GLOBALS['is_logged'])
        {
            return $this->renderText('{"result":"ok"}');
        }
        else
        {
            return $this->renderText('{"result":"error"}');
        }
    }
}

?>
