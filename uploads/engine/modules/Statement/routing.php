<?php

return array(
    
    'index' => array(
        'url' => '/?do=statement',
        'alt' => '/statement',
        'act' => '',
        'action' => 'index'
    ),
    
    'newStatement' => array(
        'url' => '/?do=statement&action=newStatement',
        'alt' => '/statement/add',
        'act' => 'newStatement',
        'action' => 'newStatement'
    ),

    'editStatement' => array(
        'url' => '/?do=statement&action=editStatement&id={id}',
        'alt' => '/statement/{id}/edit',
        'act' => 'editStatement',
        'action' => 'editStatement'
    ),
    
    'newComment' => array(
        'url' => '/?do=statement&action=newComment&statement_id={statement_id}',
        'alt' => '/comment/add/{statement_id}',
        'act' => 'newComment',
        'action' => 'newComment'
    ),
    
    'view' => array(
        'url' => '/?do=statement&action=view&id={id}',
        'alt' => '/statement/{id}',
        'act' => 'view',
        'action' => 'view'
    ),
    
    'set_vote' => array(
        'act' => 'setvote',
        'action' => 'setVote'
    ),
    
    'cancel_vote' => array(
        'act' => 'cancel',
        'action' => 'cancelVote'
    ),
    
    'save_answer' => array(
        'act' => 'saveanswer',
        'action' => 'setAnswer'
    ),
    
    'delete' => array(
        'act' => 'delete',
        'action' => 'delete'
    ),
    
    'delete' => array(
        'act' => 'del_comm',
        'action' => 'deleteComment'
    ),
    
    'list' => array(
        'url' => '/?do=statement&action=list',
        'alt' => '/statement/list',
        'act' => 'list',
        'action' => 'list'
    ),
    
    'list_pages' => array(
        'url' => '/?do=statement&action=list&page={page}',
        'alt' => '/statement/list/{page}',
        'act' => 'list',
        'action' => 'list'
    ),
    
    'search' => array(
        'act' => 'search',
        'action' => 'quickSearch'
    ),

    'ulogin' => array(
        'act' => 'ulogin',
        'action' => 'uLogin'
    ),
    
    'unsubscribe' => array(
        'url' => '/?do=statement&action=unsubscribe&u={user_id}&e={email}&s={statement_id}&hash={hash}',
    ),
        
);

?>
