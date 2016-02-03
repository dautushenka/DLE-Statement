<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Views
 *
 * @author kaliostro
 */

class Views {
    
    const TEMPLATE_PATH = 'Statement/';
    
    /**
     *
     * @var dle_template
     */
    protected $_tpl;
    
    /**
     *
     * @var stdClass
     */
    protected $_user;
    
    /**
     *
     * @param dle_template $tpl
     * @param stdClass $user 
     */
    public function __construct(dle_template $tpl, stdClass $user)
    {
        $this->_tpl = $tpl;
        $this->_user = $user;
    }
    
    public function renderStatement(Statement $st, $template)
    {
        $vars = array(
            
            'id'            => $st->getId(),
            'st-url'        => FrontController::getURLByRoute('view', array('id' => $st->getId())),
            'title'         => $st->getTitle(),
            'teaser'        => $st->getTeaser(),
            'text'          => $st->getText(),
            'username'      => $st->getUsername(),
            'answer'        => nl2br($st->getAnswer()),
            'answer_name'   => $st->getAnswerName(),
            'plus_count'    => $st->getPlusCount(),
            'minus_count'   => $st->getMinusCount(),
            'comm_num'      => $st->getCommNum(),
            'status'        => t($st->getStatusName()),
            'status_icon'   => $st->getStatus(),
            'date'          => date('j.m.Y H:i', $st->getDate()),
            'category'      => $st->getCategoryName(),
            'rate'          => $st->getPlusCount() - $st->getMinusCount(),
            'comments'      => $st->getComments(),
            'new_comments'  => $st->getNewComment(),
            'type'          => t($st->getTypeName()),
            'type_icon'     => $st->getType(),

            'edit_link'     => FrontController::getURLByRoute('editStatement', array('id' => $st->getId()))

        );
        
        $blocks = array();
        
        if ($st->getIsset())
        {
            $blocks['isset'] = true;
        }
        
        if ($st->getAnswerId())
        {
            $blocks['answer'] = true;
        }
        
        if (
            in_array($this->_user->user_group, FrontController::$config['moder_groups']) ||
            in_array($this->_user->user_id, FrontController::$config['moders'])
            )
        {
            $blocks['moder'] = true;
        }
        
        return $this->render($template, $vars, $blocks);
    }
    
    public function renderComment(Comment $comm, $template)
    {
        $vars = array(
                'id'        => $comm->getId(),
                'username'  => $comm->getUsername(),
                'text'      => $comm->getText(),
                'date'      => date('j.m.Y H:i', $comm->getDate()),
                'ip'        => $comm->getIpAddress(),
        );
        
        $user = FrontController::getUser();
        $config = FrontController::$config;
        
        if (
            in_array($this->_user->user_group, $config['moder_groups']) ||
            in_array($this->_user->user_id, $config['moders']) ||
            (
                ($user->user_id == $comm->getUserId() && $comm->getUserId()) ||
                $user->ip_address == $comm->getIpAddress()
                    &&
                $comm->getDate() > (time() - $config['owner_comment_del_time'])
                    
            )
            )
        {
            $blocks['perm_del'] = true;
        }
        
        return $this->render($template, $vars, $blocks);
    }
    
    public function renderFilterCategories(array $categories)
    {
        $return = '';
        
        foreach ($categories as $key => $value)
        {
            if (is_numeric($key))
                continue;
            
            if (is_array($value))
            {
                $return .= "<fieldset><legend>{$value[0]}</legend>";
                $return .= $this->renderFilterCategories($value);
                $return .= "</fieldset>";
            }
            else
                $return .= "<label><input type='checkbox' name='categories[]' value='$value' />" . $value . "</label><br />";
        }
        
        return $return;
    }
    
    public function render($template, $vars = array(), $blocks = array())
    {
        $this->_loadTmpl($template);
        
        $this->_fillTmpl($vars, $blocks);
        
        return $this->_compile($template);
    }
    
    public function renderMulti($template, $rows)
    {
        $this->_loadTmpl($template);
        
        $return = '';
        foreach ($rows as $row)
        {
            $this->_fillTmpl($row[0], $row[1]);
            $return .= $this->_compile($template);
        }
        $this->_tpl->clear();
        
        return $return;
    }
    
    public function setToContent($html)
    {
        $this->_tpl->result['content'] .= $html;
    }
    
    public function returnContent($content)
    {
        $this->_loadTmpl('container');
        $this->_tpl->set("{content}", $content);
        $this->_tpl->compile('content');
    }
    
    public function getBBCodes($field_id, $form_id)
    {
        global $lang, $config, $js_array, $user_group, $member_id;
        
        include ST_DIR . "/includes/bbcode.php";
        
        return $bb_code;
    }

    public function getBaseURL()
    {
        return $GLOBALS['config']['http_home_url'];
    }
    
    public function PageNavigation($route, $count_all, $per_page, array $options = array())
    {
        $default_options = array(
								
                                );
        $options = array_merge($default_options, $options);
        $request = FrontController::getRequest();
        $page = $request->getClean('page');
        	
        if ((int)$page <= 0) $page = 1;
        $i = ($per_page * $page > $count_all)?$count_all:$per_page * $page;
        	
        $this->_tpl->load_template('navigation.tpl');

        $vars = array();
        $blocks = array();
        $no_prev = false;
        $no_next = false;
        $cstart = ((int)$page - 1) * $per_page;
        
        if (isset($cstart) and $cstart != "" and $cstart > 0)
        {
            $prev = $cstart / $per_page;
            	
            if ($prev > 1)
            {
                $page_url = FrontController::getURLByRoute($route . "_pages", array('page' => $prev));
            }
            else
            {
                $page_url = FrontController::getURLByRoute($route);
            }

            $blocks['prev-link'] = "<a href=\"" . $page_url . "\">\\1</a>";
        }
        else
        {
            $no_prev = TRUE;
        }

        if($per_page)
        {
            if($count_all > $per_page)
            {
                $enpages_count = @ceil($count_all/$per_page);
                $pages = "";

                $cstart = ($cstart / $per_page) + 1;

                if ($enpages_count <= 10 )
                {
                    for( $j = 1; $j <= $enpages_count; $j++)
                    {
                        if ($j > 1)
                        {
                            $page_url = FrontController::getURLByRoute($route . "_pages", array('page' => $j));
                        }
                        else
                        {
                            $page_url = FrontController::getURLByRoute($route);
                        }

                        if($j != $cstart)
                        {
                            $pages .= "<a href=\"" . $page_url . "\">$j</a> ";
                        }
                        else
                        {
                            $pages .= "<span>$j</span> ";
                        }
                    }
                }
                else
                {
                    $start =1;
                    $end = 10;
                    $nav_prefix = "... ";

                    if ($cstart > 0)
                    {
                        if ($cstart > 5)
                        {
                            $start = $cstart - 4;
                            $end = $start + 8;

                            if ($end >= $enpages_count)
                            {
                                $start = $enpages_count - 9;
                                $end = $enpages_count - 1;
                                $nav_prefix = "";
                            }
                            else
                            {
                                $nav_prefix = "... ";
                            }
                        }
                    }

                    if ($start >= 2)
                    {
                        $pages .= "<a href=\"" . FrontController::getURLByRoute($route) . "\">1</a> ... ";
                    }

                    for( $j = $start; $j <= $end; $j++)
                    {
                        if ($j > 1)
                        {
                            $page_url =  FrontController::getURLByRoute($route . "_pages", array('page' => $j));
                        }
                        else
                        {
                            $page_url =  FrontController::getURLByRoute($route);
                        }
                        	
                        if($j != $cstart)
                        {
                            $pages .= "<a href=\"" . $page_url . "\">$j</a> ";
                        }
                        else
                        {
                            $pages .= "<span>$j</span> ";
                        }
                    }

                    if ($cstart != $enpages_count)
                    {
                        $pages .= $nav_prefix."<a href=\"" . FrontController::getURLByRoute($route . "_pages", array('page' => $enpages_count)) . "\">{$enpages_count}</a>";
                    }
                    else
                    {
                        $pages .= "<span>{$enpages_count}</span> ";
                    }
                }
            }
            $vars['pages'] = $pages;
        }

        if($per_page AND $per_page < $count_all AND $i < $count_all)
        {
            $next_page = @floor($i / $per_page) + 1;
            $blocks['next-link'] = "<a href=\"" . FrontController::getURLByRoute($route . "_pages", array('page' => $next_page)) . "\">\\1</a>";
        }
        else
        {
            $no_next = TRUE;
        }

        if  (!$no_prev OR !$no_next)
        {
            $this->_fillTmpl($vars, $blocks);
            return $this->_compile('PageNavigation');
        }
        
        return '';
    }
    
    protected function _loadTmpl($name)
    {
        $this->_tpl->load_template(self::TEMPLATE_PATH . $name . ".tpl");
    }
    
    protected function _fillTmpl($vars = array(), $blocks = array())
    {
        foreach ($vars as $key => $val)
        {
            if (is_array($val))
            {
                foreach ($val as $key => $value)
                {
                    $this->_tpl->set("{{$key}}", $value);
                }
                continue;
            }
            
            $this->_tpl->set("{{$key}}", $val);
        }
        
        foreach ($blocks as $key => $value)
        {
            if (is_bool($value) || is_integer($value))
            {
                $this->_tpl->set_block ("#\[{$key}\](.+?)\[/{$key}\]#si", $value?'\\1':'');
            }
            else
            {
                $this->_tpl->set_block ("#\[{$key}\](.+?)\[/{$key}\]#si", $value);
            }
        }
    }
    
    protected function _compile($name)
    {
        $this->_tpl->compile($name);
        
        $return = $this->_tpl->result[$name];
        unset($this->_tpl->result[$name]);
        
        $blocks = array();
        
        $return = str_ireplace( '{THEME}', $GLOBALS['config']['http_home_url'] . 'templates/' . $GLOBALS['config']['skin'], $return );
        $return = preg_replace('#{[\w_]+}#i', '', $return);
        
        //preg_match_all('#\[(.{3,})\].*?\[/\1\]#si', $return, $blocks);
        // for 5.2
        preg_match_all("#\[/(.{3,}?)\]#i", $return, $blocks);
        
        foreach ($blocks[1] as $name)
        {
            if (strpos($name, 'not-') === false)
            {
                $return = preg_replace("#\[not\-$name\](.*?)\[/not\-$name\]#si", '\\1', $return);
                $return = preg_replace("#\[$name\](.*?)\[/$name\]#si", '', $return);
            }
            else
            {
                $return = preg_replace("#\[$name\](.*?)\[/$name\]#si", '', $return);
            }
        }
        
        //$return = preg_replace('#\[(.{3,}?)\].+?\[/\1\]#si', '', $return);
        
        return $return;
    }
     
}

?>
