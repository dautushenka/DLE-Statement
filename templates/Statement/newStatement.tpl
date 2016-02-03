<script type='text/javascript'>
    $(function(){
        $('#statement_form').BotProtected('{antibot}');
    });
</script>

<div>
    <form action="" method="post" id="statement_form">
        {errors}
        <ul>
            <li>
                <b>� ��������� ��� ����...</b><br />
                <input type="text" name="title" value="{title}" />
            </li>
            <li>
                <b>���</b><br />
                {types}
                {type_error}
            </li>
            <li>
                <b>���������</b><br />
                {categories}
                {category_error}
            </li>
            <li>
                <b>����� ��������� �������� ������ �������:</b><br />
                {bbcode}
                <textarea name="text" id='st_text'>{text}</textarea>
                {text_error}
            </li>
            <li>
                <b>����������� �� ������ �����������: <input type='checkbox' name='subscribe' value='1'/></b>
            </li>
            [group=5]
            <li class='guest'>
                <hr />
                <h4>������� �����: </h4>
                <script src="http://ulogin.ru/js/ulogin.js"></script>
                <div id="uLogin" x-ulogin-params="display=panel&fields=first_name,last_name,photo,email,nickname&providers=vkontakte,odnoklassniki,mailru,google,yandex,facebook&hidden=twitter,livejournal,openid&redirect_uri={ulogin}&callback=uLoginCallback"></div>
            </li>
            <li class='guest'>
                <b>���� ���:</b><br />
                
                <input type='text' name='name' value="{name}" />
                {name_error}
            </li>
            <li class='guest'>
                <b>Email:</b><br />
                <input type='text' name='email' value="{email}" />
                {email_error}
            </li>
            [/group]
            <li>
                <input type="hidden" name='send' value="1" />
                <input type="hidden" name='do' value="statement" />
                <input type="hidden" name='action' value="newStatement" />
                <input type="hidden" name='_csrf_token' value="{_csrf_token}" />
                <input type="submit" name='' value="���������" />
            </li>
        </ul>
    </form>
</div>