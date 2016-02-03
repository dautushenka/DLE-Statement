1. �� ����� uploads �������� ����� � ������ �����
2. ����� Statement �� ����� templates �������� � ����� � ����� ��������
3. ���� schema.sql  ��������� � MySQL

4. ��������� ����  engine/engine.php
�������
switch ( $do ) {

����� ���������
	case "statement" :
		include ENGINE_DIR . '/modules/Statement/statement.php';
		break;
        
���� �������
if ($titl_e) $s_navigation .= " &raquo; " . $titl_e;

����� ���������
if ($do == 'statement') $s_navigation = $front->getBreadcrumbs();

5. ��������� ���� .htaccess
�������
RewriteEngine On

����� ���������
# Statement
RewriteRule ^statement$ index.php?do=statement [L]
RewriteRule ^statement/add$ index.php?do=statement&action=newStatement [L]
RewriteRule ^statement/add/([0-9]+)$ index.php?do=statement&action=newComment&statement_id=$1 [L]
RewriteRule ^statement/([0-9]+)$ index.php?do=statement&action=view&id=$1 [L]
RewriteRule ^statement/list$ index.php?do=statement&action=list [L]
RewriteRule ^statement/list/([0-9]+)$ index.php?do=statement&action=list&page=$1 [L]


6. ������������ ������ ���������� � ����� /engine/modules/Statement/config.php
