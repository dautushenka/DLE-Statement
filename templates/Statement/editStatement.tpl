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
                <b>����������� �����</b><br />
                <textarea name='answer'>{answer}</textarea>
            </li>
            <li>
                <b>������</b><br />
                {status}
            </li>
            <li>
                <input type="hidden" name='id' value="{id}" />
                <input type="hidden" name='do' value="statement" />
                <input type="hidden" name='submit' value="1" />
                <input type="hidden" name='action' value="editStatement" />
                <input type="hidden" name='_csrf_token' value="{_csrf_token}" />
                <input type="submit" value="���������" />
            </li>
        </ul>
    </form>
</div>