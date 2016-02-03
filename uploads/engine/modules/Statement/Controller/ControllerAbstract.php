<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControllerAbstract
 *
 * @author kaliostro
 * @abstract
 */
abstract class ControllerAbstract {
    
    /**
     *
     * @var Request
     */
   static protected $_request;
    
    /**
     *
     * @var stdClass
     */
    static protected $_user = null;
    
    /**
     *
     * @var dle_template
     */
    protected $_tpl = null;
    
    protected $_breadcrumbs = array();
    
    /**
     *
     * @var Events
     */
    public $eventManager;

    /**
     *
     * @var db
     */
    public static $db;
    
    public static $config;
    
    protected static $_routing;

    public function __construct(Request $request, array $member_id)
    {
        self::$_request = $request;
        self::$config = include ST_DIR . '/config.php';
        self::$_routing = include ST_DIR . '/routing.php';
        self::$_user = (object)$member_id;
        $this->_tpl = $tpl;
        
        if (empty($this->_user->user_id))
        {
            self::$_user->user_id = 0;
            self::$_user->user_group = 5;
        }
        
        self::$_user->ip_address = $_SERVER['REMOTE_ADDR'];
        
        $this->eventManager = Events::getInstance();
        
        $this->addToBreadcrumbs($GLOBALS['config']['home_title'], $GLOBALS['config']['http_home_url']);
        
        $this->configure();
    }
    
    public function configure()
    {
        
    }
    
    public function setTpl(dle_template $tpl)
    {
        $this->_tpl = $tpl;
    }
    
    public function setDb(db $db)
    {
        self::$db = $db;
    }
    
    public function dispatch()
    {
        $action = $this->_request->get('action', '');
        foreach ($this->_routing as $values)
        {
            if ($values['act'] == $action)
                return call_user_func (array($this, $values['action'] . 'Action'));
        }
        
        if (!$this->eventManager->processNotify('FrontController.dispatch', $this, $action))
        {
            return;
        }
        
        throw new Exception('Запрашиваемая страница не найдена', 404);
    }
    
    public function setPgeTitle($title)
    {
        if (mb_strlen($title) > 110)
        {
            $title = mb_substr ($title, 0, 110) . "...";
        }
        
        $GLOBALS['metatags']['title'] = $title;
        
        return $this;
    }
    
    public function addToBreadcrumbs($text, $link = '')
    {
        if (mb_strlen($text) > $this->_config['breadcrumb_item_length'])
        {
            $text = mb_substr($text, 0, $this->_config['breadcrumb_item_length']) . "...";
        }
        
        if ($link)
        {
            $this->_breadcrumbs[] = "<a href='" . $link . "'>" . $text . "</a>";
        }
        else
        {
            $this->_breadcrumbs[] = $text;
        }
        
        return $this;
    }
    
    public function getBreadcrumbs()
    {
        return implode(' &raquo; ', $this->_breadcrumbs);
    }
    
    public function t($text)
    {
        return t($text);
    }
    
    /**
     *
     * @staticvar null $views
     * @return \Views 
     */
    public function getViews()
    {
        static $views = null;
        
        if (!$views)
        {
            require_once ST_DIR . '/Classes/Views.php';
            $views = new Views($this->_tpl, $this->_user);
        }
        
        return $views;
    }
    
    public function msg($title, $description, $result = 'ok')
    {
        if ($this->_request->isAJAX())
        {
            $this->renderText('{"result":"' . $result . '", "message":"' . toUTF8(t($description)) . '", "title":"' . toUTF8(t($title)) . '"}', 'application/json', 'UTF-8');
        }
        else
        {
            msgbox(t($title), t($description));
        }
    }
    
    protected function _redirect($url, $params = array(), $code = 302)
    {
        if ($url{0} == "@")
            $url = $this->_getURLByRoute($url, $params);
        
        header('Location:' . $url, true, $code);
        die('Вы были перенаправлены по адресу <a href="' . $url . '">' . $url . '</a>' );
    }
    
    public function renderText($text, $content_type = 'application/json', $charset = false)
    {
        header( "Content-type: $content_type; charset=" . ($charset?$charset:$GLOBALS['config']['charset'] ));
        echo $text;
        exit();
    }
    
    protected function _getURLByRoute($route, $params = array())
    {
        return self::getURLByRoute($route, $params);
    }
    
    static public function getURLByRoute($route, $params = array(), $absolute = false)
    {
        $route = str_replace("@", '', $route);
        
        if (empty(self::$_routing[$route]))
            throw new Exception('Route ' . $route . ' not found');
        
        if ($GLOBALS['config']['allow_alt_url'] == 'yes')
            $url = self::$_routing[$route]['alt'];
        else
            $url = self::$_routing[$route]['url'];
        
        foreach ($params as $key => $value)
        {
            $url = str_replace("{{$key}}", $value, $url);
        }

        if ($absolute)
        {
            return $GLOBALS['config']['http_home_url'] . trim($url, "/");
        }
        else
        {
            return $url;
        }
    }
    
    /**
     *
     * @return Request
     */
    static public function getRequest()
    {
        return self::$_request;
    }
    
    static public function getUser()
    {
        return self::$_user;
    }
    
    public function __get($name)
    {
        switch ($name)
        {
            case '_routing':
                return self::$_routing;
                break;
            
            case '_request':
                return self::$_request;
                break;
            
            case '_config':
                return self::$config;
                break;
            
            case '_db':
                return self::$db;
                break;
            
            case '_user':
                return self::$_user;
                break;
        }
    }
}

?>
