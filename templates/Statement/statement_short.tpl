<li id='st-{id}' class='statement short'>
    <h2><a href='{st-url}'>{title}</a></h2>
    <div class='st-rate'>
        <span class='rate'>{rate}</span><br />
        <span class='plus'>{plus_count}</span> | <span class='minus'>{minus_count}</span>
    </div>
    <div class='content'>
        <div class='username'>{username}</div>
        <div class='teaser'>{teaser}</div>
        <div>Комментариев: {comm_num}</div>
        <div class='loader'><img src='{THEME}/Statement/images/ajax-loader_vote.gif' /></div>
        <div class='vote' style='[isset]display: none[/isset]'>
            <a href='#' class='yes'>yes</a> | <a href='#' class='no'>No</a>
        </div>
        <div class='cancel' style='[not-isset]display: none[/not-isset]'>
            <a href='#' class='cancel'>Отменить</a>
        </div>
    </div>
    <div class='clear'></div>
</li>