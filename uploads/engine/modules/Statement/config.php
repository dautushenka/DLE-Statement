<?php

return array(
    
    'categories' => array(  //Категории alt_name => Название (alt_name должно быть уникальным)
        'service' => 'Сервисный центр',
        'AppsBox' => array( // Название группы
            'AppsBox_idea' => 'Идеи',
            'AppsBox_problem' => 'Проблемы'
        ),
    ),
    
    'moder_groups' => array(1, 3), //Группы доверенных лиц
    'moders' => array(), // или перечисление доверенных лиц
    
    'comment_per_page' => 20, // Комментариев на страницу
    'statement_per_page' => 10, // Предложений на страницу
    
    'teaser_length' => 150, // Количество символов в описании
    
    'breadcrumb_item_length' => 75, // Максимальная блина ссылки в speedbar
    
    'owner_comment_del_time' => 30 * 60 // Время в секундах в течении которых пользователь может удалить свой коммент
);

?>
