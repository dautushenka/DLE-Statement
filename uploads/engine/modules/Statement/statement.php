<?php

if( ! defined( 'DATALIFEENGINE' )) {
    die( "Hacking attempt!" );
}

define('ST_DIR', ENGINE_DIR . "/modules/Statement");

require_once ST_DIR . '/Interfaces/IEventSubscriber.php';

require_once ST_DIR . '/includes/functions.php';
require_once ST_DIR . '/Classes/Request.php';
require_once ST_DIR . '/Classes/Session.php';
require_once ST_DIR . '/Controller/ControllerAbstract.php';
require_once ST_DIR . '/Controller/FrontController.php';
require_once ST_DIR . '/Model/ModelAbstract.php';
require_once ST_DIR . '/Model/Statement.php';
require_once ST_DIR . '/Model/Comment.php';

require_once ST_DIR . '/Classes/Events.php';
require_once ST_DIR . '/Classes/MailsEvent.php';
require_once ST_DIR . '/Classes/SubscribeEvent.php';

mb_internal_encoding($config['charset']);

ModelAbstract::$db = $db;
$request = new Request();
$front = new FrontController($request, $member_id);
$front->setTpl($tpl);

$js_array[] = 'engine/modules/Statement/Resources/js/statement.js';

try
{
    $front->dispatch();
}
catch (Exception $e)
{
    switch ($e->getCode())
    {
        case 404:
            header( "HTTP/1.0 404 Not Found" );
            $front->msg('Error', $e->getMessage(), 'error');
            break;
        
        default:
            $front->msg('Error', $e->getMessage(), 'error');
            break;
    }
}

?>
