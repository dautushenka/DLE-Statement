<?PHP

if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}

$i = 0;
$output = "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\"><tr>";

$smilies = explode(",", $config['smilies']);
$count_smilies = count($smilies);

foreach($smilies as $smile)
{
    $i++; $smile = trim($smile);

    $output .= "<td style=\"padding:2px;\" align=\"center\"><a href=\"#\" onclick=\"dle_smiley(':$smile:'); return false;\"><img style=\"border: none;\" alt=\"$smile\" src=\"".$config['http_home_url']."engine/data/emoticons/$smile.gif\" /></a></td>";

    if ($i%4 == 0 AND $i < $count_smilies) $output .= "</tr><tr>";
}

$output .= "</tr></table>";


if ($user_group[$member_id['user_group']]['allow_url'])
{
    $url_link = "<div class=\"editor_button\"  onclick=\"tag_url()\"><img title=\"$lang[bb_t_url]\" src=\"{THEME}/bbcodes/link.gif\" width=\"23\" height=\"25\" border=\"0\" alt=\"\" /></div><div class=\"editor_button\"  onclick=\"tag_leech()\"><img title=\"$lang[bb_t_leech]\" src=\"{THEME}/bbcodes/leech.gif\" width=\"23\" height=\"25\" border=\"0\" alt=\"\" /></div>";
} 
else $url_link = "";

if ($user_group[$member_id['user_group']]['allow_image'])
{
    $image_link = "<div class=\"editor_button\" onclick=\"tag_image()\"><img title=\"$lang[bb_b_img]\" src=\"{THEME}/bbcodes/image.gif\" width=\"23\" height=\"25\" border=\"0\" alt=\"\" /></div>";
} 
else $image_link = "";

$code = <<<HTML
<div style="width:465px;border:1px solid #BBB;" class="editor">
<div style="width:100%;overflow: hidden;border-bottom:1px solid #BBB;background-image:url('{THEME}/bbcodes/bg.gif')">
<div id="b_b" class="editor_button" onclick="simpletag('b')"><img title="$lang[bb_t_b]" src="{THEME}/bbcodes/b.gif" width="23" height="25" border="0" alt="" /></div>
<div id="b_i" class="editor_button" onclick="simpletag('i')"><img title="$lang[bb_t_i]" src="{THEME}/bbcodes/i.gif" width="23" height="25" border="0" alt="" /></div>
<div id="b_u" class="editor_button" onclick="simpletag('u')"><img title="$lang[bb_t_u]" src="{THEME}/bbcodes/u.gif" width="23" height="25" border="0" alt="" /></div>
<div id="b_s" class="editor_button" onclick="simpletag('s')"><img title="$lang[bb_t_s]" src="{THEME}/bbcodes/s.gif" width="23" height="25" border="0" alt="" /></div>
<div class="editor_button"><img src="{THEME}/bbcodes/brkspace.gif" width="5" height="25" border="0" alt="" /></div>
<div id="b_left" class="editor_button" onclick="simpletag('left')"><img title="$lang[bb_t_l]" src="{THEME}/bbcodes/l.gif" width="23" height="25" border="0" alt="" /></div>
<div id="b_center" class="editor_button" onclick="simpletag('center')"><img title="$lang[bb_t_c]" src="{THEME}/bbcodes/c.gif" width="23" height="25" border="0" alt="" /></div>
<div id="b_right" class="editor_button" onclick="simpletag('right')"><img title="$lang[bb_t_r]" src="{THEME}/bbcodes/r.gif" width="23" height="25" border="0" alt="" /></div>
<div class="editor_button"><img src="{THEME}/bbcodes/brkspace.gif" width="5" height="25" border="0" alt="" /></div>
<div id="b_emo" class="editor_button"  onclick="ins_emo(this);"><img title="$lang[bb_t_emo]" src="{THEME}/bbcodes/emo.gif" width="23" height="25" border="0" alt="" /></div>
{$url_link}
{$image_link}
<div id="b_color" class="editor_button" onclick="ins_color(this);"><img title="$lang[bb_t_color]" src="{THEME}/bbcodes/color.gif" width="23" height="25" border="0" alt="" /></div>
<div class="editor_button"><img src="{THEME}/bbcodes/brkspace.gif" width="5" height="25" border="0" alt="" /></div>
<div id="b_hide" class="editor_button" onclick="simpletag('hide')"><img title="$lang[bb_t_hide]" src="{THEME}/bbcodes/hide.gif" width="23" height="25" border="0" alt="" /></div>
<div id="b_quote" class="editor_button" onclick="simpletag('quote')"><img title="$lang[bb_t_quote]" src="{THEME}/bbcodes/quote.gif" width="23" height="25" border="0" alt="" /></div>
<div class="editor_button" onclick="translit()"><img title="$lang[bb_t_translit]" src="{THEME}/bbcodes/translit.gif" width="23" height="25" border="0" alt="" /></div>
<div id="b_spoiler" class="editor_button" onclick="simpletag('spoiler')"><img title="$lang[bb_t_spoiler]" src="{THEME}/bbcodes/spoiler.gif" width="23" height="25" border="0" alt="" /></div>
</div>
<div id="dle_emos" style="display: none;" title="{$lang['bb_t_emo']}"><div style="width:100%;height:100%;overflow: auto;">{$output}</div></div>
</div>
HTML;

if (!in_array("engine/classes/js/bbcodes.js", $js_array))
{
    $js_array[] = "engine/classes/js/bbcodes.js";
}

$image_align = array ();
$image_align[$config['image_align']] = "selected";


$bb_code = <<<HTML
<script language="javascript" type="text/javascript">
<!--
var text_enter_url       = "$lang[bb_url]";
var text_enter_size       = "$lang[bb_flash]";
var text_enter_flash       = "$lang[bb_flash_url]";
var text_enter_page      = "$lang[bb_page]";
var text_enter_url_name  = "$lang[bb_url_name]";
var text_enter_page_name = "$lang[bb_page_name]";
var text_enter_image    = "$lang[bb_image]";
var text_enter_email    = "$lang[bb_email]";
var text_code           = "$lang[bb_code]";
var text_quote          = "$lang[bb_quote]";
var error_no_url        = "$lang[bb_no_url]";
var error_no_title      = "$lang[bb_no_title]";
var error_no_email      = "$lang[bb_no_email]";
var prompt_start        = "$lang[bb_prompt_start]";
var img_title   		= "$lang[bb_img_title]";
var email_title  	    = "$lang[bb_email_title]";
var text_pages  	    = "$lang[bb_bb_page]";
var image_align  	    = "{$config['image_align']}";
var bb_t_emo  	        = "{$lang['bb_t_emo']}";
var bb_t_col  	        = "{$lang['bb_t_col']}";
var text_enter_list     = "{$lang['bb_list_item']}";
var text_alt_image      = "{$lang['bb_alt_image']}";
var img_align  	        = "{$lang['images_align']}";
var img_align_sel  	    = "<select name='dleimagealign' id='dleimagealign' class='ui-widget-content ui-corner-all'><option value='' {$image_align[0]}>{$lang['images_none']}</option><option value='left' {$image_align['left']}>{$lang['images_left']}</option><option value='right' {$image_align['right']}>{$lang['images_right']}</option><option value='center' {$image_align['center']}>{$lang['images_center']}</option></select>";

var selField  = "{$field_id}";
var fombj    = document.getElementById( '$form_id' );
-->
</script>
{$code}
HTML;
?>