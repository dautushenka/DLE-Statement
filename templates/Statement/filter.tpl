<div id='filter'>
    <form action="" method="get" id="filter_form">
        <table>
            <tr>
                <td colspan="4">
                    <div class='show'>
                        <b>����������: </b>
                    <label><input type='radio' name='show' value='all' checked="checked" /> ���</label> &nbsp;
                    <label><input type='radio' name='show' value='filter' /> � ������ �������</label>
                    </div>
                    <div class='order'>
                        <b>����������: </b>
                    <label><input type='radio' name='order' value='vote' checked="checked" /> �� �������</label> &nbsp;
                    <label><input type='radio' name='order' value='date' /> �� ����</label> &nbsp;
                    <label><input type='radio' name='order' value='comments' /> �� ������������</label>
                    </div>
                </td>
            </tr>
            <tr class='filter'>
                <td>
                    ���:<br />
                    <label><input type="checkbox" name="type[]" value="idea" /> ���� </label><br />
                    <label><input type="checkbox" name="type[]" value="question" /> ������ </label><br />
                    <label><input type="checkbox" name="type[]" value="error" /> �������� </label><br />
                    <label><input type="checkbox" name="type[]" value="thank" /> �������������</label>
                </td>
                <td>
                    ���������:<br />
                    {categories}
                </td>
                <td>
                    ������:<br />
                    <label><input type="checkbox" name="status[]" value="waiting" /> ������� ������������ </label><br />
                    <label><input type="checkbox" name="status[]" value="working" />  �������� </label><br />
                    <label><input type="checkbox" name="status[]" value="scheduled" />  ������������� </label><br />
                    <label><input type="checkbox" name="status[]" value="canceled" /> ��������� </label><br />
                    <label><input type="checkbox" name="status[]" value="performed" /> ��������� </label><br />
                </td>
                <td>
                    ����� ��������:<br />
                    <label><input type="radio" name="time" value="today" /> ������� </label><br />
                    <label><input type="radio" name="time" value="week" /> �� ������ </label><br />
                    <label><input type="radio" name="time" value="month" /> �� ����� </label><br />
                   <label> <input type="radio" name="time" value="all"  checked="checked"/> �� ��� ����� </label>
                </td>
            </tr>
            <tr class='filter'>
                <td colspan="4" style="text-align: right">
                    <input type="submit" value="���������" />
                </td>
            </tr>
        </table>
        <input type="hidden" name="do" value="statement" />
    </form>
    <div><div class='loader'><img src='{THEME}/Statement/images/ajax-loader_big.gif' /></div></div>
</div>