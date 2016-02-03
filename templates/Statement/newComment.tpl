<script type='text/javascript'>
    $(function(){
        $('#comment_form').BotProtected('{antibot}');
    });
</script>
<div>
    {errors}
    <form action="" method="post" id="comment_form">
        <ul>
            [group=5]
            <li class='guest'>
                <hr />
                <h4>Войдите через: </h4>
                <script src="http://ulogin.ru/js/ulogin.js"></script>
                <div id="uLogin" x-ulogin-params="display=panel&fields=first_name,last_name,photo,email,nickname&providers=vkontakte,odnoklassniki,mailru,google,yandex,facebook&hidden=twitter,livejournal,openid&redirect_uri={ulogin}&callback=uLoginCallback"></div>
            </li>
            <li class='guest'>
                <hr />
                <h4>Или укажите:</h4>
                <b>Ваше имя:</b><br />
                <input type='text' name='name' value="{name}" />
                {name_error}
            </li>
            <li class='guest'>
                <b>Email:</b><br />
                <input type='text' name='email' value="{email}" />
                {email_error}
            </li>
            [/group]
            <li class='comment'>
                <b>Текст комментария</b><br />
                {bbcode}
                <textarea name="text" id='comm_text'>{text}</textarea>
                {text_error}<br/>
                <b>Подписаться на данное предложение: <input type='checkbox' name='subscribe' value='1'/></b>
            </li>
            <li>
                <input type="hidden" name='do' value="statement" />
                <input type="hidden" name='statement_id' value="{statement_id}" />
                <input type="hidden" name='action' value="newComment" />
                <input type="hidden" name='_csrf_token' value="{_csrf_token}" />
                <input type="submit" value="Отправить" />
            </li>
        </ul>
    </form>
</div>