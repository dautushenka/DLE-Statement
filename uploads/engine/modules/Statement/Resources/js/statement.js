$.fn.BotProtected = function($value)
{
    $(this).submit(function()
    {
        if (!$("input[name=antibot]", this).size())
        {
            var input = $('<input />');
            input.attr('type', 'hidden');
            input.attr('name', 'antibot');
            input.attr('value', $value);

            $(this).append(input);
        }

        return true;
    });
};

function uLoginCallback(token)
{
    $.post('/?do=statement&action=ulogin', {token:token}, function(data)
    {
        if (data.result == 'ok')
        {
            $('body').data('openid' ,true);
            $('#statement form').submit();
        }
        else
        {
            $('#st-dialog').text('Войти не удалось, возможно у существует пользователь с вашими данными');
            $('#st-dialog').dialog({title: "Ошибка", resizable: false, draggable: false, buttons: {"Ok": function() {$(this).dialog("close");}}});
        }
    }, 'json');

}

$(function()
{
    var StatementErrors = {
        1: 'Переданы не верные параметры',
        2: 'Идея не найдена',
        3: 'Вы уже голосовали',
        4: 'Вы не голосовали',
        5: 'Доступ запрещен'
    };
    
    $('body').ajaxComplete(function()
    {
        $('.loader').hide();
    });
    
    function getError(data)
    {
        if (data.code == undefined)
        {
            return data.message;
        }
        else
        {
            return StatementErrors[data.code];
        }
    }

    function setErrorElement(elm)
    {
        $func = function(next)
        {
            $(this).toggleClass('error');
            setTimeout(next, 300);
        };

        elm.clearQueue()
           .queue($func)
           .queue($func)
           .queue($func)
           .queue($func)
           .queue($func);

        elm.bind('keyup change', function()
        {
            if (this.value.trim())
            {
                $(this).removeClass('error');
            }
            else
            {
                $(this).addClass('error');
            }
        });
    }
    
    $('a.yes, a.no, a.cancel').live('click', function()
        {
            var options = {
                statement_id: $(this).parents('.statement').attr('id').split("-")[1]
            };
            var st = $('#st-' + options.statement_id);
            var cancel = true;
            
            if ($(this).is('.cancel'))
                var url = '/?do=statement&action=cancel';
            else
                {
                    var url = '/?do=statement&action=setvote';
                    options.vote = $(this).is('.yes')?1:-1;
                    cancel = false;
                }
            
            $(this).parents('.statement').find('.loader').show();
            st.find('div.vote').hide();
            st.find('div.cancel').hide();
            
            $.getJSON(url, options, function(data)
            {
                if (data.result == 'ok')
                    {
                        var rate = st.find('.st-rate');
                        rate.find('.rate').text(data.rate);
                        rate.find('.plus').text(data.plus);
                        rate.find('.minus').text(data.minus);
                    }
                else
                    {
                        $('#st-dialog').text(getError(data));
                        $('#st-dialog').dialog({title: "Ошибка", resizable: false, draggable: false, buttons: {"Ok": function() {$(this).dialog("close");}}});
                    }
                    
                if (cancel)
                {
                    st.find('div.vote').show();
                }
                else
                {
                    st.find('div.cancel').show();
                }
            });
            
            return false;
        });
        
    $('#search').keyup(function()
    {
        var value = this.value;
        
        if (value.length < 3)
            return;
        
        if (this.XMLHttpRequest != undefined)
            this.XMLHttpRequest.abort();
        
        $('#statement #head .loader').show();
        
        this.XMLHttpRequest = $.post('/?do=statement&action=search', {s: value}, function(data)
        {
            $('#search_result').show();
            $('#search_result .result').html(data);
        }, 'html');
    });
    
    $('#search_result .result li a.show').live('click', function()
        {
            $(this).parents('li').find('div.detail').toggle();
            
            return false;
        });
        
    $('a.add_answer, a.edit_answer').click(function()
        {
            if ($(this).is('.edit_answer'))
            {
                $('#answer textarea').val($('div.answer').text());
                $('#status option').each(function()
                {
                    if (this.innerHTML == $('.status').text())
                        {
                            this.selected = 'selected';
                        }
                });
            }
                
            var save = function()
            {
                var options = {
                    statement_id: $('.statement').attr('id').split("-")[1],
                    answer: $('#answer textarea').val(),
                    status: $('#status').val()
                };
                $.post('/?do=statement&action=saveanswer', options, function(data)
                {
                    if (data.result == 'ok')
                        {
                            if ($('div.answer').size())
                                {
                                    $('div.answer').html($('#answer textarea').val().replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1<br />$2'));
                                    $('.status').html($('#status option:selected').text());
                                    dialog.dialog('close');
                                }
                            else
                                {
                                    location.assign(window.location.href);
                                    //window.location.reload();
                                }
                            
                        }
                    else
                        {
                            $('#st-dialog').text(getError(data));
                            $('#st-dialog').dialog({title: "Ошибка", resizable: false, draggable: false, buttons: {"Ok": function() {$(this).dialog("close");}}});
                        }
                }, 'json');
            }
                
            var dialog = $('#answer').dialog({title: "Ответ", buttons: {"Отмена": function() {$(this).dialog("close");}, 'Сохранить': save}});
            
            return false;
        });
        
    $('#filter_form').submit(function()
        {
            $('#statement #filter .loader').show();
            $('#statements').html('');
            $.post("/?do=statement", $(this).serialize(), function(data)
            {
                $('#statements').html(data);
            }, 'html');
            
            return false;
        });
        
    $('#filter_form input[name="order"]').change(function()
    {
        $('#filter_form').submit();
    });
        
    $('#filter_form input[name="show"]').change(function()
    {
        $('#statement #filter_form table tr.filter').fadeToggle();
        $('#filter_form').submit();
    });
    
    $('#statements .navigation a').live('click', function()
    {
        $('#statement #filter .loader').show();
        $('#statements').html('');
        $.post(this.href, $('#filter_form').serialize(), function(data)
        {
            $('#statements').html(data);
        }, 'html');

        return false;
    });
    
    $('a.delete_st').click(function()
    {
        var confirm = function()
        {
            $.post("/?do=statement&action=delete", {statement_id:$('.statement').attr('id').split("-")[1]}, function(data)
            {
                if (data.result == 'ok')
                    {
                        location.href = '/?do=statement';
                    }
                else
                    {
                        $('#st-dialog').text(getError(data));
                        $('#st-dialog').dialog({title: "Ошибка", resizable: false, draggable: false, buttons: {"Ok": function() {$(this).dialog("close");}}});
                    }
            }, 'json');
        }
        
        $('#st-dialog').text("Вы действительно хотите удалить данное предложение?");
        $('#st-dialog').dialog({title: "Подтверждение", resizable: false, draggable: false, buttons: {"Да": confirm, "Нет": function() {$(this).dialog("close");}}});
        
        return false;
    });
    
    $('a.delete_comm').click(function()
    {
        var a = this;
        var confirm = function()
        {
            $.post("/?do=statement&action=del_comm", {id:$(a).parents('.comment[id]').attr('id').split("-")[1]}, function(data)
            {
                $('#st-dialog').dialog('close');
                if (data.result == 'ok')
                    {
                        $(a).parents('.comment[id]').slideUp(function(){
                            $(this).remove();
                        });
                    }
                else
                    {
                        $('#st-dialog').text(getError(data));
                        $('#st-dialog').dialog({title: "Ошибка", resizable: false, draggable: false, buttons: {"Ok": function() {$(this).dialog("close");}}});
                    }
            }, 'json');
        }
        
        $('#st-dialog').text("Вы действительно хотите удалить данный комментарий?");
        $('#st-dialog').dialog({title: "Подтверждение", resizable: false, draggable: false, buttons: {"Да": confirm, "Нет": function() {$(this).dialog("close");}}});
        
        return false;
    });

    $('#comment_form').submit(function()
    {
        if (!$('#comm_text').val().trim())
        {
            setErrorElement($('#comm_text'))
            return false;
        }

        if (!$('.guest').size())
        {
            return true;
        }

        if ($('body').data('openid'))
        {
            return true;
        }

        if ($('.guest:visible', this).size())
        {
            if (!$('input[name=name]', this).val().trim())
            {
                setErrorElement($('input[name=name]', this));
                return false;
            }

            if (!$('input[name=email]', this).val().trim() || /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/.test($('input[name=email]', this).val()) == false)
            {
                setErrorElement($('input[name=email]', this));
                return false;
            }
            return true;
        }

        $(".guest", this).slideDown();
        $(".comment", this).slideUp();

        return false;
    });

    $('#statement_form').submit(function(event)
    {
        var validate_fields = ['input[name=title]', 'select[name=type]', 'select[name=category]', '#st_text'];
        var have_error = false;
        var form = this;

        for(var i in validate_fields)
        {
            if (!$(validate_fields[i], form).val().trim())
            {
                have_error = true;
                setErrorElement($(validate_fields[i], form))
            }
        }

        if (have_error)
        {
            return false;
        }

        if (!$('.guest', form).size() || $('body').data('openid'))
        {
            return true;
        }

        if ($('.guest:visible', form).size())
        {
            if (!$('input[name=name]', form).val().trim())
            {
                setErrorElement($('input[name=name]', form));
                return false;
            }

            if (!$('input[name=email]', form).val().trim() || /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/.test($('input[name=email]', this).val()) == false)
            {
                setErrorElement($('input[name=email]', form));
                return false;
            }
            return true;
        }

        $(".guest", form).slideDown();

        return false;
    });
});