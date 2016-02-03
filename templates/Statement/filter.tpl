<div id='filter'>
    <form action="" method="get" id="filter_form">
        <table>
            <tr>
                <td colspan="4">
                    <div class='show'>
                        <b>Показывать: </b>
                    <label><input type='radio' name='show' value='all' checked="checked" /> Все</label> &nbsp;
                    <label><input type='radio' name='show' value='filter' /> С учетом фильтра</label>
                    </div>
                    <div class='order'>
                        <b>Сортировка: </b>
                    <label><input type='radio' name='order' value='vote' checked="checked" /> По голосам</label> &nbsp;
                    <label><input type='radio' name='order' value='date' /> По дате</label> &nbsp;
                    <label><input type='radio' name='order' value='comments' /> По комментариям</label>
                    </div>
                </td>
            </tr>
            <tr class='filter'>
                <td>
                    Тип:<br />
                    <label><input type="checkbox" name="type[]" value="idea" /> Идея </label><br />
                    <label><input type="checkbox" name="type[]" value="question" /> Вопрос </label><br />
                    <label><input type="checkbox" name="type[]" value="error" /> Проблема </label><br />
                    <label><input type="checkbox" name="type[]" value="thank" /> Благодарность</label>
                </td>
                <td>
                    Категория:<br />
                    {categories}
                </td>
                <td>
                    Статус:<br />
                    <label><input type="checkbox" name="status[]" value="waiting" /> Ожидают рассмотрения </label><br />
                    <label><input type="checkbox" name="status[]" value="working" />  Делаются </label><br />
                    <label><input type="checkbox" name="status[]" value="scheduled" />  Запланированы </label><br />
                    <label><input type="checkbox" name="status[]" value="canceled" /> Отклонены </label><br />
                    <label><input type="checkbox" name="status[]" value="performed" /> Выполнены </label><br />
                </td>
                <td>
                    Время создания:<br />
                    <label><input type="radio" name="time" value="today" /> Сегодня </label><br />
                    <label><input type="radio" name="time" value="week" /> За неделю </label><br />
                    <label><input type="radio" name="time" value="month" /> За месяц </label><br />
                   <label> <input type="radio" name="time" value="all"  checked="checked"/> За все время </label>
                </td>
            </tr>
            <tr class='filter'>
                <td colspan="4" style="text-align: right">
                    <input type="submit" value="Применить" />
                </td>
            </tr>
        </table>
        <input type="hidden" name="do" value="statement" />
    </form>
    <div><div class='loader'><img src='{THEME}/Statement/images/ajax-loader_big.gif' /></div></div>
</div>