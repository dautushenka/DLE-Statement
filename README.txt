1. Из папки uploads заливаем файлы в корень сайта
2. Папку Statement из папки templates заливаем в папку с вашим шаблоном
3. Файл schema.sql  выполняем в MySQL

4. Открываем файл  engine/engine.php
Находим
switch ( $do ) {

После вставляем
	case "statement" :
		include ENGINE_DIR . '/modules/Statement/statement.php';
		break;
        
Ниже находим
if ($titl_e) $s_navigation .= " &raquo; " . $titl_e;

После вставляем
if ($do == 'statement') $s_navigation = $front->getBreadcrumbs();

5. ОТкрываем файл .htaccess
Находим
RewriteEngine On

После добавляем
# Statement
RewriteRule ^statement$ index.php?do=statement [L]
RewriteRule ^statement/add$ index.php?do=statement&action=newStatement [L]
RewriteRule ^statement/add/([0-9]+)$ index.php?do=statement&action=newComment&statement_id=$1 [L]
RewriteRule ^statement/([0-9]+)$ index.php?do=statement&action=view&id=$1 [L]
RewriteRule ^statement/list$ index.php?do=statement&action=list [L]
RewriteRule ^statement/list/([0-9]+)$ index.php?do=statement&action=list&page=$1 [L]


6. Конфигурация модуля находиться в файле /engine/modules/Statement/config.php
