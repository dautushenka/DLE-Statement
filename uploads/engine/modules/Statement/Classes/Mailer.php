<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mailer
 *
 * @author kaliostro
 */
class Mailer {
    
    protected $_mail = null;
    
    public function __construct()
    {
        require_once ENGINE_DIR . '/classes/mail.class.php';
        $this->_mail = new dle_mail($GLOBALS['config']);
    }
    
    public function sendMail($template, $to, $vars = array())
    {
        static $cache = array();
        
        $to = (array)$to;
        
        if (!$to)
        {
            return false;
        }
        
        if (empty($cache[$template]))
        {
            $cache[$template] = $content = file_get_contents(ST_DIR . '/Resources/mail/' . $template . ".txt");
        }
        else
        {
            $content = $cache[$template];
        }
        
        foreach($vars as $k => $v)
        {
            $content = str_replace('%' . $k . '%', $v, $content);
        }
        
        $subj = preg_replace('#^\[subj\](.+?)\[/subj\].+#si', '\\1', $content);
        $content = preg_replace("#^\[subj\](.+?)\[/subj\](\n)?(\r)?#si", '', $content);
        
        foreach ($to as $t)
        {
            $this->_mail->send($t, $subj, $content);
        }
    }
}

?>
