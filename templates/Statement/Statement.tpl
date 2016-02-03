<div id='st-{id}' class='statement full'>
    <h1>{title}<sup>{type}</sup></h1>
    <div class='st-rate'>
        <span class='rate'>{rate}</span><br />
        <span class='plus'>{plus_count}</span> | <span class='minus'>{minus_count}</span>
        <div class='loader'><img src='{THEME}/Statement/images/ajax-loader_vote.gif' /></div>
        <div class='vote' style='[isset]display: none[/isset]'>
            <a href='#' class='yes'>yes</a> | <a href='#' class='no'>No</a>
        </div>
        <div class='cancel' style='[not-isset]display: none[/not-isset]'>
            <a href='#' class='cancel'>��������</a>
        </div>
    </div>
    <div class='content'>
        <div class='username'>{username}</div>
        <div class='teaser'>{text}</div>
        [answer]
        <div class='answer-container'>
            <span>����������� �����</span> | {answer_name}
            <div class='answer'>{answer}</div>
        </div>
        [moder]<a href='#' class='edit_answer'>�������� �����</a>[/moder]
        [/answer]
        [moder]
            [not-answer]<a href='#' class='add_answer'>�������� �����</a>[/not-answer] |
            <a href='{edit_link}' class=''>������������� �����������</a> |
            <a href='#' class='delete_st'>������� �����������</a>
        [/moder]
        <div class='status'>{status}</div>
        <div class='category'>{category}</div>
    </div>
    <div class='clear'></div>
    {comments}
    {new_comments}
</div>
        
<!-- Templates -->
<div>
    <div id='answer' style='display: none'>
        <textarea class="answer"></textarea><br />
        <select name='status' id='status'>
            <option value='waiting'>������� ������������</option>
            <option value='working'>��������</option>
            <option value='scheduled'>������������</option>
            <option value='canceled'>��������</option>
            <option value='performed'>��������</option>
        </select>
    </div>
</div>