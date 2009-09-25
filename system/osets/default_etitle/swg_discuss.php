<?php
//j// BOF

/*n// NOTE
----------------------------------------------------------------------------
secured WebGine
net-based application engine
----------------------------------------------------------------------------
(C) direct Netware Group - All rights reserved
http://www.direct-netware.de/redirect.php?swg

The following license agreement remains valid unless any additions or
changes are being made by direct Netware Group in a written form.

This program is free software; you can redistribute it and/or modify it
under the terms of the GNU General Public License as published by the
Free Software Foundation; either version 2 of the License, or (at your
option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
more details.

You should have received a copy of the GNU General Public License along with
this program; if not, write to the Free Software Foundation, Inc.,
59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.
----------------------------------------------------------------------------
http://www.direct-netware.de/redirect.php?licenses;gpl
----------------------------------------------------------------------------
#echo(sWGdiscussVersion)#
sWG/#echo(__FILEPATH__)#
----------------------------------------------------------------------------
NOTE_END //n*/
/**
* osets/default_etitle/swg_discuss.php
*
* @internal   We are using phpDocumentor to automate the documentation process
*             for creating the Developer's Manual. All sections including
*             these special comments will be removed from the release source
*             code.
*             Use the following line to ensure 76 character sizes:
* ----------------------------------------------------------------------------
* @author     direct Netware Group
* @copyright  (C) direct Netware Group - All rights reserved
* @package    sWG
* @subpackage discuss
* @uses       direct_product_iversion
* @since      v0.1.00
* @license    http://www.direct-netware.de/redirect.php?licenses;gpl
*             GNU General Public License 2
*/

/* -------------------------------------------------------------------------
All comments will be removed in the "production" packages (they will be in
all development packets)
------------------------------------------------------------------------- */

//j// Basic configuration

/* -------------------------------------------------------------------------
Direct calls will be honored with an "exit ()"
------------------------------------------------------------------------- */

if (!defined ("direct_product_iversion")) { exit (); }

//j// Functions and classes

//f// direct_output_oset_discuss_boards ()
/**
* direct_output_oset_discuss_boards ()
*
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return string Valid XHTML code
* @since  v0.1.00
*/
function direct_output_oset_discuss_boards ()
{
	global $direct_cachedata,$direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_output_oset_discuss_boards ()- (#echo(__LINE__)#)"); }

	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/osets/$direct_settings[theme_oset]/swgi_discuss.php");

	$direct_settings['theme_output_page_title'] = ((strlen ($direct_cachedata['output_board']['title_alt'])) ? $direct_cachedata['output_board']['title_alt'] : $direct_cachedata['output_board']['title']);
	$f_colspan = ($direct_cachedata['output_board']['data'] ? " colspan='2'" : "");

$f_return = ("<table cellspacing='1' summary='' class='pageborder1' style='width:100%;table-layout:auto'>
<thead class='pagehide'><tr>
<td$f_colspan align='left' class='pagetitlecellbg' style='padding:$direct_settings[theme_td_padding]'><span class='pagetitlecellcontent'>".(direct_local_get ("discuss_board"))."</span></td>
</tr></thead><tbody>");

	if ($direct_cachedata['output_board']['pageurl_parent'])
	{
$f_return .= ("<tr>
<td$f_colspan align='left' class='pageextrabg' style='padding:$direct_settings[theme_form_td_padding]'><span class='pageextracontent' style='font-size:10px'><a href=\"{$direct_cachedata['output_board']['pageurl_parent']}\" target='_self'>".(direct_local_get ("core_level_up"))."</a></span></td>
</tr>");
	}

	if ($direct_cachedata['output_board']['data'])
	{
		$f_return .= "<tr>\n<td valign='middle' align='left' class='pagebg' style='width:84%;padding:$direct_settings[theme_td_padding]'>";
		if (($direct_settings['discuss_datacenter_symbols'])&&($direct_cachedata['output_board']['symbol'])) { $f_return .= "<img src='{$direct_cachedata['output_board']['symbol']}' border='0' alt='' title='' style='float:left;margin-right:5px' />"; }

$f_return .= ("<span class='pagecontent' style='font-size:10px'>{$direct_cachedata['output_board']['data']}</span></td>
<td valign='middle' align='center' class='pageextrabg' style='width:16%;padding:$direct_settings[theme_td_padding]'><span class='pageextracontent' style='font-size:10px'><span style='font-weight:bold'>".(direct_local_get ("discuss_topics")).":</span> {$direct_cachedata['output_board']['topics']}<br />
<span style='font-weight:bold'>".(direct_local_get ("discuss_posts")).":</span> {$direct_cachedata['output_board']['posts']}</span></td>
</tr></tbody>
</table>");
	}
	else
	{
$f_return .= ("<tr>
<td align='center' class='pagebg' style='padding:$direct_settings[theme_td_padding]'><span class='pagecontent' style='font-size:10px'><span style='font-weight:bold'>".(direct_local_get ("discuss_topics")).":</span> {$direct_cachedata['output_board']['topics']} - <span style='font-weight:bold'>".(direct_local_get ("discuss_posts")).":</span> {$direct_cachedata['output_board']['posts']}</span></td>
</tr></tbody>
</table>");
	}

	if (!empty ($direct_cachedata['output_boards'])) { $f_return .= "<span style='font-size:8px'>&#0160;</span>".(direct_oset_discuss_boards_parse ($direct_cachedata['output_boards'])); }
	return $f_return;
}

//f// direct_output_oset_discuss_posts ()
/**
* direct_output_oset_discuss_posts ()
*
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return string Valid XHTML code
* @since  v0.1.00
*/
function direct_output_oset_discuss_posts ()
{
	global $direct_cachedata,$direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_output_oset_discuss_posts ()- (#echo(__LINE__)#)"); }

	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/osets/$direct_settings[theme_oset]/swgi_discuss.php");
	$direct_settings['theme_output_page_title'] = $direct_cachedata['output_topic']['title'];

	$f_return = "<table cellspacing='1' summary='' class='pageborder1' style='width:100%;table-layout:auto'>";

	if (isset ($direct_cachedata['output_board']))
	{
		if ($direct_cachedata['output_board']['data'])
		{
			$f_return .= "\n<thead><tr>\n<td colspan='2' align='left' class='pagetitlecellbg' style='padding:$direct_settings[theme_td_padding]'><span class='pagetitlecellcontent'><a href=\"{$direct_cachedata['output_board']['pageurl']}\" target='_self'>";
			$f_return .= ((strlen ($direct_cachedata['output_board']['title_alt'])) ? $direct_cachedata['output_board']['title_alt'] : $direct_cachedata['output_board']['title']);
			$f_return .= "</a></span></td>";
		}
		else { $f_return .= "\n<thead class='pagehide'><tr>\n<td colspan='2' align='left' class='pagetitlecellbg' style='padding:$direct_settings[theme_td_padding]'><span class='pagetitlecellcontent'>".(direct_local_get ("discuss_board"))."</span></td>"; }

		$f_return .= "\n</tr></thead><tbody>";

		if ($direct_cachedata['output_board']['pageurl_parent'])
		{
$f_return .= ("<tr>
<td colspan='2' align='left' class='pageextrabg' style='padding:$direct_settings[theme_form_td_padding]'><span class='pageextracontent' style='font-size:10px'><a href=\"{$direct_cachedata['output_board']['pageurl_parent']}\" target='_self'>".(direct_local_get ("core_level_up"))."</a></span></td>
</tr>");
		}

		$f_return .= "<tr>\n<td valign='middle' align='left' class='pagebg' style='width:84%;padding:$direct_settings[theme_td_padding]'>";
		if (($direct_settings['discuss_datacenter_symbols'])&&($direct_cachedata['output_board']['symbol'])) { $f_return .= "<img src='{$direct_cachedata['output_board']['symbol']}' border='0' alt='' title='' style='float:left;margin-right:5px' />"; }

		if ($direct_cachedata['output_board']['data']) { $f_return .= "<span class='pagecontent' style='font-size:10px'>{$direct_cachedata['output_board']['data']}</span>"; }
		else
		{
			$f_return .= "<span class='pagecontent' style='font-weight:bold'><a href=\"{$direct_cachedata['output_board']['pageurl']}\" target='_self'>";
			$f_return .= ((strlen ($direct_cachedata['output_board']['title_alt'])) ? $direct_cachedata['output_board']['title_alt'] : $direct_cachedata['output_board']['title']);
			$f_return .= "</a></span>";
		}

$f_return .= ("</td>
<td valign='middle' align='center' class='pageextrabg' style='width:16%;padding:$direct_settings[theme_td_padding]'><span class='pageextracontent' style='font-size:10px'><span style='font-weight:bold'>".(direct_local_get ("discuss_topics")).":</span> {$direct_cachedata['output_board']['topics']}<br />
<span style='font-weight:bold'>".(direct_local_get ("discuss_posts")).":</span> {$direct_cachedata['output_board']['posts']}</span></td>
</tr></tbody>
</table><span style='font-size:8px'>&#0160;</span><table cellspacing='1' summary='' class='pageborder1' style='width:100%;table-layout:auto'>");
	}

	$f_colspan = ($direct_cachedata['output_topic']['desc'] ? " colspan='2'" : "");

$f_return .= ("\n<thead class='pagehide'><tr>
<td$f_colspan align='left' class='pagetitlecellbg' style='padding:$direct_settings[theme_td_padding]'><span class='pagetitlecellcontent'>".(direct_local_get ("discuss_topic"))."</span></td>
</tr></thead><tbody><tr>\n");

	$f_return .= ($direct_cachedata['output_topic']['desc'] ? "<td valign='middle' align='left' class='pagebg' style='width:84%;padding:$direct_settings[theme_td_padding]'>" : "<td align='center' class='pagebg' style='padding:$direct_settings[theme_td_padding]'>");
	if (($direct_settings['discuss_datacenter_symbols'])&&($direct_cachedata['output_topic']['symbol'])) { $f_return .= "<img src='{$direct_cachedata['output_topic']['symbol']}' border='0' alt='' title='' style='float:left;margin-right:5px' />"; }
	if ($direct_cachedata['output_topic']['locked']) { $f_return .= "<img src='".(direct_linker_dynamic ("url0","s=cache&dsd=dfile+$direct_settings[path_themes]/$direct_settings[theme]/status_locked.png",true,false))."' border='0' alt='".(direct_local_get ("discuss_topic_locked"))."' title='".(direct_local_get ("discuss_topic_locked"))."' style='float:right' />"; }

	if ($direct_cachedata['output_topic']['new'])
	{
		$f_return .= "<img src='".(direct_linker_dynamic ("url0","s=cache&dsd=dfile+$direct_settings[path_themes]/$direct_settings[theme]/status_comment_new.png",true,false))."' border='0' alt='".(direct_local_get ("discuss_topic_new_posts"))."' title='".(direct_local_get ("discuss_topic_new_posts"))."' style='float:right' />";

		if (isset ($direct_cachedata['output_board']))
		{
$f_return .= ("<script language='JavaScript1.5' type='text/javascript'><![CDATA[
djs_discuss_posts_new_updater ('discuss_boards_{$direct_cachedata['output_board']['oid']}_unread','discuss_topics_{$direct_cachedata['output_topic']['oid']}','{$direct_cachedata['output_topic']['posts']}');
]]></script>");
		}
	}

	if ($direct_cachedata['output_topic']['sticky']) { $f_return .= "<img src='".(direct_linker_dynamic ("url0","s=cache&dsd=dfile+$direct_settings[path_themes]/$direct_settings[theme]/status_sticky.png",true,false))."' border='0' alt='".(direct_local_get ("discuss_topic_sticky"))."' title='".(direct_local_get ("discuss_topic_sticky"))."' style='float:right' />"; }

	if ($direct_cachedata['output_topic']['desc'])
	{
$f_return .= "<span class='pagecontent' style='font-size:10px'>{$direct_cachedata['output_topic']['desc']}</span></td>
<td valign='middle' align='center' class='pageextrabg' style='width:16%;padding:$direct_settings[theme_td_padding]'><span class='pageextracontent' style='font-size:10px'><span style='font-weight:bold'>".(direct_local_get ("discuss_posts")).":</span> {$direct_cachedata['output_topic']['posts']}</span></td>
</tr>";
	}
	else { $f_return .= "<span class='pagecontent' style='font-size:10px'><span style='font-weight:bold'>".(direct_local_get ("discuss_posts")).":</span> {$direct_cachedata['output_topic']['posts']}</span></td>\n</tr>"; }

	if (empty ($direct_cachedata['output_posts']))
	{
		$f_return .= "</tbody>\n</table>";
		if ((isset ($direct_cachedata['output_boards']))&&(!empty ($direct_cachedata['output_boards']))) { $f_return .= "<span style='font-size:8px'>&#0160;</span>".(direct_oset_discuss_boards_parse ($direct_cachedata['output_boards'])); }
		$f_return .= "\n<p class='pagecontent' style='font-weight:bold'>".(direct_local_get ("discuss_post_list_empty"))."</p>";
	}
	else
	{
		if ($direct_cachedata['output_pages'] > 1)
		{
$f_return .= ("<tr>
<td$f_colspan align='center' class='pageextrabg' style='padding:$direct_settings[theme_td_padding]'><span class='pageextracontent' style='font-size:10px'>".(direct_output_pages_generator ($direct_cachedata['output_page_url'],$direct_cachedata['output_pages'],$direct_cachedata['output_page']))."</span></td>
</tr>");
		}

		$f_return .= "</tbody>\n</table>";

		if ($direct_cachedata['output_topic']['subs_available'])
		{
			$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/osets/$direct_settings[theme_oset]/swgi_datalinker_iview.php");
			$f_source = urlencode (base64_encode ($direct_cachedata['page_this']));

			$f_return .= (($direct_cachedata['output_topic']['subs'] > 1) ? "<span style='font-size:8px'>&#0160;</span><div class='pageborder2' style='width:100%;height:$direct_settings[theme_datalinker_iview_height];overflow:auto'>" : "<span style='font-size:8px'>&#0160;</span><div class='pageborder2'>");
			$f_return .= direct_datalinker_oset_iview_subs ($direct_cachedata['output_topic'],5,$f_source,NULL,"default",false,false);
			$f_return .= "</div>";
		}

		if ((isset ($direct_cachedata['output_boards']))&&(!empty ($direct_cachedata['output_boards']))) { $f_return .= "<span style='font-size:8px'>&#0160;</span>".(direct_oset_discuss_boards_parse ($direct_cachedata['output_boards'])); }
		$f_return .= "<span style='font-size:8px'>&#0160;</span>".(direct_oset_discuss_posts_parse ($direct_cachedata['output_posts']));
		if ($direct_cachedata['output_pages'] > 1) { $f_return .= "\n<p class='pageborder2' style='text-align:center'><span class='pageextracontent' style='font-size:10px'>".(direct_output_pages_generator ($direct_cachedata['output_page_url'],$direct_cachedata['output_pages'],$direct_cachedata['output_page']))."</span></p>"; }
	}

	return $f_return;
}

//f// direct_output_oset_discuss_topics ()
/**
* direct_output_oset_discuss_topics ()
*
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return string Valid XHTML code
* @since  v0.1.00
*/
function direct_output_oset_discuss_topics ()
{
	global $direct_cachedata,$direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_output_oset_discuss_topics ()- (#echo(__LINE__)#)"); }

	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/osets/$direct_settings[theme_oset]/swgi_discuss.php");

	$direct_settings['theme_output_page_title'] = ((strlen ($direct_cachedata['output_board']['title_alt'])) ? $direct_cachedata['output_board']['title_alt'] : $direct_cachedata['output_board']['title']);
	$f_colspan = ($direct_cachedata['output_board']['data'] ? " colspan='2'" : "");

$f_return = ("<table cellspacing='1' summary='' class='pageborder1' style='width:100%;table-layout:auto'>
<thead class='pagehide'><tr>
<td$f_colspan align='left' class='pagetitlecellbg' style='padding:$direct_settings[theme_td_padding]'><span class='pagetitlecellcontent'>".(direct_local_get ("discuss_board"))."</span></td>
</tr></thead><tbody>");

	if ($direct_cachedata['output_board']['pageurl_parent'])
	{
$f_return .= ("<tr>
<td$f_colspan align='left' class='pageextrabg' style='padding:$direct_settings[theme_form_td_padding]'><span class='pageextracontent' style='font-size:10px'><a href=\"{$direct_cachedata['output_board']['pageurl_parent']}\" target='_self'>".(direct_local_get ("core_level_up"))."</a></span></td>
</tr>");
	}

	if ($direct_cachedata['output_board']['data'])
	{
		$f_return .= "<tr>\n<td valign='middle' align='left' class='pagebg' style='width:84%;padding:$direct_settings[theme_td_padding]'>";
		if (($direct_settings['discuss_datacenter_symbols'])&&($direct_cachedata['output_board']['symbol'])) { $f_return .= "<img src='{$direct_cachedata['output_board']['symbol']}' border='0' alt='' title='' style='float:left;margin-right:5px' />"; }

$f_return .= ("<span class='pagecontent' style='font-size:10px'>{$direct_cachedata['output_board']['data']}</span></td>
<td valign='middle' align='center' class='pageextrabg' style='width:16%;padding:$direct_settings[theme_td_padding]'><span class='pageextracontent' style='font-size:10px'><span style='font-weight:bold'>".(direct_local_get ("discuss_topics")).":</span> {$direct_cachedata['output_board']['topics']}<br />
<span style='font-weight:bold'>".(direct_local_get ("discuss_posts")).":</span> {$direct_cachedata['output_board']['posts']}</span>");
	}
	else { $f_return .= "<tr>\n<td align='center' class='pagebg' style='padding:$direct_settings[theme_td_padding]'><span class='pagecontent' style='font-size:10px'><span style='font-weight:bold'>".(direct_local_get ("discuss_topics")).":</span> {$direct_cachedata['output_board']['topics']} - <span style='font-weight:bold'>".(direct_local_get ("discuss_posts")).":</span> {$direct_cachedata['output_board']['posts']}</span>"; }

$f_return .= ("<script language='JavaScript1.5' type='text/javascript'><![CDATA[
if (djs_discuss_boards_new_check ('discuss_boards_{$direct_cachedata['output_board']['oid']}','{$direct_cachedata['output_board']['posts']}')) { djs_swgDOM_replace (\"<a href='{$f_tempdata['array1']['last_post_jump']}' target='_self'><img src='".(direct_linker_dynamic ("url0","s=cache&dsd=dfile+$direct_settings[path_themes]/$direct_settings[theme]/status_comment_new_viewed.png",true,false))."' border='0' alt='".(direct_local_get ("discuss_board_new_posts"))."' title='".(direct_local_get ("discuss_board_new_posts"))."' style='float:right' /></a>\",'{$f_tempdata['array1']['id']}_new'); }
]]></script></td>
</tr>");

	if (empty ($direct_cachedata['output_topics']))
	{
		$f_return .= "</tbody>\n</table>";
		if ((isset ($direct_cachedata['output_boards']))&&(!empty ($direct_cachedata['output_boards']))) { $f_return .= "<span style='font-size:8px'>&#0160;</span>".(direct_oset_discuss_boards_parse ($direct_cachedata['output_boards'])); }
		$f_return .= "\n<p class='pagecontent' style='font-weight:bold'>".(direct_local_get ("discuss_topic_list_empty"))."</p>";
	}
	else
	{
		if ($direct_cachedata['output_pages'] > 1)
		{
$f_return .= ("<tr>
<td$f_colspan align='center' class='pageextrabg' style='padding:5px'><span class='pageextracontent' style='font-size:10px'>".(direct_output_pages_generator ($direct_cachedata['output_page_url'],$direct_cachedata['output_pages'],$direct_cachedata['output_page']))."</span></td>
</tr>");
		}

		$f_return .= "</tbody>\n</table>";
		if ((isset ($direct_cachedata['output_boards']))&&(!empty ($direct_cachedata['output_boards']))) { $f_return .= "<span style='font-size:8px'>&#0160;</span>".(direct_oset_discuss_boards_parse ($direct_cachedata['output_boards'])); }
		$f_return .= "\n<span style='font-size:8px'>&#0160;</span>".(direct_oset_discuss_topics_parse ($direct_cachedata['output_topics']));

		if ($direct_cachedata['output_pages'] > 1) { $f_return .= "\n<p class='pageborder2' style='text-align:center'><span class='pageextracontent' style='font-size:10px'>".(direct_output_pages_generator ($direct_cachedata['output_page_url'],$direct_cachedata['output_pages'],$direct_cachedata['output_page']))."</span></p>"; }
	}

	return $f_return;
}

//f// direct_output_oset_discuss_topics_latest ()
/**
* direct_output_oset_discuss_topics_latest ()
*
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return string Valid XHTML code
* @since  v0.1.00
*/
function direct_output_oset_discuss_topics_latest ()
{
	global $direct_cachedata,$direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_output_oset_discuss_topics_latest ()- (#echo(__LINE__)#)"); }

	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/osets/$direct_settings[theme_oset]/swgi_discuss.php");

	$direct_settings['theme_output_page_title'] = direct_local_get ("discuss_topics_latest");

	if (empty ($direct_cachedata['output_topics'])) { $f_return = "<p class='pagecontent' style='font-weight:bold'>".(direct_local_get ("discuss_topics_latest_empty"))."</p>"; }
	else
	{
		$f_return = (($direct_cachedata['output_pages'] > 1) ? "<p class='pageborder2' style='text-align:center'><span class='pageextracontent' style='font-size:10px'>".(direct_output_pages_generator ($direct_cachedata['output_page_url'],$direct_cachedata['output_pages'],$direct_cachedata['output_page']))."</span></p>\n" : "");
		$f_return .= direct_oset_discuss_topics_latest_parse ($direct_cachedata['output_topics']);
		if ($direct_cachedata['output_pages'] > 1) { $f_return .= "\n<p class='pageborder2' style='text-align:center'><span class='pageextracontent' style='font-size:10px'>".(direct_output_pages_generator ($direct_cachedata['output_page_url'],$direct_cachedata['output_pages'],$direct_cachedata['output_page']))."</span></p>"; }
	}

	return $f_return;
}

//j// Script specific commands

if (!isset ($direct_settings['theme_datalinker_iview_height'])) { $direct_settings['theme_datalinker_iview_height'] = "300px"; }
if (!isset ($direct_settings['theme_td_padding'])) { $direct_settings['theme_td_padding'] = "5px"; }
if (!isset ($direct_settings['theme_form_td_padding'])) { $direct_settings['theme_form_td_padding'] = "3px"; }

//j// EOF
?>