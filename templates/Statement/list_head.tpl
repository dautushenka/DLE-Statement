<div id='head'>
    <div>
        <h2>я хочу предложить...</h2>
        <form action="/?do=statement&action=newStatement" method="post">
            <input type="text" name="title" id='search' />
            <div id='search_result'>
                <div class='result'></div>
                <div class='loader'><img src='{THEME}/Statement/images/ajax-loader.gif' /></div>
                <input type="submit" value="ƒобавить свое предложение" />
            </div>
            <input type="hidden" name='do' value="statement" />
            <input type="hidden" name='action' value="newStatement" />
        </form>
    </div>
    <div class="clear"></div>
</div>