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
* osets/default_etitle/swgi_discuss.php
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

//f// direct_oset_discuss_boards_parse ($f_boards)
/**
* direct_datalinker_oset_iview ()
*
* @param  array $f_boards Boards to be parsed
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return string Valid XHTML code
* @since  v0.1.00
*/
function direct_oset_discuss_boards_parse ($f_boards)
{
	global $direct_cachedata,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_oset_discuss_boards_parse (+f_boards)- (#echo(__LINE__)#)"); }

$f_return = ("<table cellspacing='1' summary='' class='pageborder1' style='width:100%'>
<thead><tr>
<td valign='middle' align='center' class='pagetitlecellbg' style='width:45%;padding:$direct_settings[theme_td_padding]'><span class='pagetitlecellcontent'>".(direct_local_get ("discuss_board"))."</span></td>
<td valign='middle' align='center' class='pagetitlecellbg' style='width:10%;padding:$direct_settings[theme_td_padding]'><span class='pagetitlecellcontent' style='font-size:10px'>".(direct_local_get ("discuss_topics"))."</span></td>
<td valign='middle' align='center' class='pagetitlecellbg' style='width:10%;padding:$direct_settings[theme_td_padding]'><span class='pagetitlecellcontent' style='font-size:10px'>".(direct_local_get ("discuss_posts"))."</span></td>
<td valign='middle' align='center' class='pagetitlecellbg' style='width:35%;padding:$direct_settings[theme_td_padding]'><span class='pagetitlecellcontent' style='font-size:10px'>".(direct_local_get ("discuss_latest_post"))."</span></td>
</tr></thead><tbody>");

	foreach ($f_boards as $f_board_array)
	{
		$f_return .= "<tr>\n<td valign='middle' align='left' class='pagebg' style='width:45%;padding:$direct_settings[theme_td_padding]'><p class='pagecontent'>";

		if (($direct_settings['discuss_datacenter_symbols'])&&($f_board_array['symbol'])) { $f_return .= "<img src='{$f_board_array['symbol']}' border='0' alt='' title='' style='float:left;margin-right:5px' />"; }
		if ($f_board_array['locked']) { $f_return .= "<img src='".(direct_linker_dynamic ("url0","s=cache&dsd=dfile+$direct_settings[path_themes]/$direct_settings[theme]/status_locked.png",true,false))."' border='0' alt='".(direct_local_get ("discuss_board_locked"))."' title='".(direct_local_get ("discuss_board_locked"))."' style='float:right' />"; }

		if ($f_board_array['new'])
		{
$f_return .= ("<a id='{$f_board_array['id']}_new' href='{$f_board_array['last_post_jump']}' target='_self'><img src='".(direct_linker_dynamic ("url0","s=cache&dsd=dfile+$direct_settings[path_themes]/$direct_settings[theme]/status_comment_new.png",true,false))."' border='0' alt='".(direct_local_get ("discuss_board_new_posts"))."' title='".(direct_local_get ("discuss_board_new_posts"))."' style='float:right' /></a><script language='JavaScript1.5' type='text/javascript'><![CDATA[
if (djs_discuss_boards_new_check ('discuss_boards_{$f_board_array['oid']}','{$f_board_array['posts']}')) { djs_swgDOM_replace (\"<a href='{$f_board_array['last_post_jump']}' target='_self'><img src='".(direct_linker_dynamic ("url0","s=cache&dsd=dfile+$direct_settings[path_themes]/$direct_settings[theme]/status_comment_new_viewed.png",true,false))."' border='0' alt='".(direct_local_get ("discuss_board_new_posts"))."' title='".(direct_local_get ("discuss_board_new_posts"))."' style='float:right' /></a>\",'{$f_board_array['id']}_new'); }
]]></script>");
		}

		$f_return .= "<span style='font-weight:bold'><a href=\"{$f_board_array['pageurl']}\" target='_self'>";
		$f_return .= ((strlen ($f_board_array['title_alt'])) ? $f_board_array['title_alt'] : $f_board_array['title']);
		$f_return .= "</a></span>";

		if ($f_board_array['data']) { $f_return .= "</p>\n<p class='pagecontent' style='font-size:10px'>".$f_board_array['data']; }

$f_return .= ("</p></td>
<td valign='middle' align='center' class='pageextrabg' style='width:10%;padding:$direct_settings[theme_td_padding]'><span class='pageextracontent'>{$f_board_array['topics']}</span></td>
<td valign='middle' align='center' class='pagebg' style='width:10%;padding:$direct_settings[theme_td_padding]'><span class='pagecontent'>{$f_board_array['posts']}</span></td>");

		if (($f_board_array['subboards_last_time'])||(($f_board_array['last_tid'])&&($f_board_array['last_title'])&&($f_board_array['username'])))
		{
			$f_return .= "\n<td valign='middle' align='left' class='pageextrabg' style='width:35%;padding:$direct_settings[theme_td_padding]'>";

			if ($f_board_array['last_tid'])
			{
				$f_return .= "<p class='pageextracontent' style='font-size:10px'><span style='font-weight:bold'>{$f_board_array['last_time']}</span><br />\n<span style='font-weight:bold'><a href='{$f_board_array['last_post_jump']}' target='_self'>".$f_board_array['last_title']."</a>";
				$f_return .= ($f_board_array['last_preview'] ? ":</span> {$f_board_array['last_preview']} ({$f_board_array['username']})</p>" : "</span> ({$f_board_array['username']})</p>");
			}

			$f_return .= ($f_board_array['subboards_last_time'] ? "\n<p class='pageextracontent' style='font-size:10px'>".(direct_local_get ("discuss_subboards_last_time_1"))."<span style='font-weight:bold'>{$f_board_array['subboards_last_time']}</span>".(direct_local_get ("discuss_subboards_last_time_2"))."</p></td>" : "</td>");
		}
		else { $f_return .= "\n<td valign='middle' align='center' class='pagebg' style='width:35%;padding:$direct_settings[theme_td_padding]'><p class='pagecontent' style='font-size:10px;font-weight:bold'>".(direct_local_get ("discuss_without_posts"))."</p></td>"; }

		$f_return .= "\n</tr>";
	}

	return $f_return."</tbody>\n</table>";
}

//f// direct_oset_discuss_post_preview ()
/**
* direct_oset_discuss_post_preview ()
*
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return string Valid XHTML code
* @since  v0.1.00
*/
function direct_oset_discuss_post_preview ()
{
	global $direct_cachedata,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_oset_discuss_post_preview ()- (#echo(__LINE__)#)"); }

$f_return = ("<table cellspacing='1' summary='' class='pageborder1' style='width:100%;table-layout:auto'>
<thead><tr>
<td colspan='2' align='left' class='pagetitlecellbg' style='padding:$direct_settings[theme_td_padding]'><span class='pagetitlecellcontent'>$direct_cachedata[output_title]</span></td>
</tr></thead><tbody><tr>
<td valign='top' align='center' class='pageextrabg' style='width:25%;padding:$direct_settings[theme_form_td_padding]'><p class='pageextracontent' style='font-weight:bold'>$direct_cachedata[output_username]</p></td>
<td valign='middle' align='left' class='pagebg' style='width:75%;padding:$direct_settings[theme_form_td_padding]'><span class='pagecontent'>$direct_cachedata[output_post]</span></td>
</tr></tbody>
</table>");

	return $f_return;
}

//f// direct_oset_discuss_posts_parse ($f_posts)
/**
* direct_oset_discuss_posts_parse ()
*
* @param  array $f_posts Posts to be parsed
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return string Valid XHTML code
* @since  v0.1.00
*/
function direct_oset_discuss_posts_parse ($f_posts)
{
	global $direct_cachedata,$direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_oset_discuss_posts_parse (+f_posts)- (#echo(__LINE__)#)"); }

	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/osets/$direct_settings[theme_oset]/swgi_account_profile.php");

	$f_return = "";

	foreach ($f_posts as $f_post_array)
	{
		if ($f_return) { $f_return .= "<span style='font-size:8px'>&#0160;</span>"; }
		if (isset ($f_post_array['jumptarget'])) { $f_return .= "<a id=\"{$f_post_array['jumptarget']}\" name=\"{$f_post_array['jumptarget']}\"></a>"; }

		$f_options_check = $direct_classes['output']->options_check ($f_post_array['options']);
		$f_options_rowspan = ($f_options_check ? 3 : 2);

$f_return .= ("<table cellspacing='1' summary='' class='pageborder1' style='width:100%;table-layout:auto'>
<thead><tr>
<td valign='middle' align='center' class='pagebg' style='width:20%;padding:$direct_settings[theme_td_padding]'><span class='pagecontent' style='font-size:10px'>{$f_post_array['time']}</span></td>
<td valign='middle' align='left' class='pagetitlecellbg' style='width:80%;padding:$direct_settings[theme_td_padding]'><span class='pagetitlecellcontent'>");

		if (($direct_settings['discuss_datacenter_symbols'])&&($f_post_array['symbol'])) { $f_return .= "<img src='{$f_post_array['symbol']}' border='0' alt='' title='' style='margin-right:5px;vertical-align:middle' />"; }
		if ($f_post_array['locked']) { $f_return .= "<img src='".(direct_linker_dynamic ("url0","s=cache&dsd=dfile+$direct_settings[path_themes]/$direct_settings[theme]/status_locked.png",true,false))."' border='0' alt='".(direct_local_get ("discuss_topic_locked"))."' title='".(direct_local_get ("discuss_topic_locked"))."' style='float:right' />"; }
		if ($f_post_array['new']) { $f_return .= "<img src='".(direct_linker_dynamic ("url0","s=cache&dsd=dfile+$direct_settings[path_themes]/$direct_settings[theme]/status_comment_new.png",true,false))."' border='0' alt='".(direct_local_get ("discuss_post_new"))."' title='".(direct_local_get ("discuss_post_new"))."' style='float:right' />"; }

$f_return .= ($f_post_array['title']."</span></td>
</tr></thead><tbody><tr>
<td rowspan='$f_options_rowspan' valign='top' align='center' class='pageextrabg' style='width:20%;padding:$direct_settings[theme_td_padding]'>".(direct_account_oset_parse_user_fullv ($f_post_array,"pageextracontent","","","user"))."</td>");

		if ($f_options_check) { $f_return .= "<td align='center' class='pageextrabg' style='padding:$direct_settings[theme_td_padding]'><span class='pageextracontent' style='font-size:11px'>".($direct_classes['output']->options_generator ("h",$f_post_array['options']))."</span></td>\n</tr><tr>"; }
		$f_return .= "<td valign='middle' align='left' class='pagebg' style='width:80%;padding:$direct_settings[theme_td_padding]'><p class='pagecontent' style='text-align:justify'>{$f_post_array['text']}</p>";

		if (($f_post_array['subs_allowed'])||($f_post_array['subs_available']))
		{
			$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/osets/$direct_settings[theme_oset]/swgi_datalinker_iview.php");
			$f_source = urlencode (base64_encode ($direct_cachedata['page_this']));
			$f_return .= "<img src='".(direct_linker_dynamic ("url0","s=cache&dsd=dfile+$direct_settings[path_mmedia]/spacer.png",true,false))."' width='100%' height='1' alt='' title='' class='pagehr' style='$direct_settings[formtags_hr_style]' />".(direct_datalinker_oset_iview_subs ($f_post_array,5,$f_source,"minimal"));
		}

		$f_return .= "</td>\n</tr>";

		if ($f_post_array['usersignature'])
		{
$f_return .= ("<tr>
<td colspan='2' align='center' class='pagebg' style='padding:$direct_settings[theme_td_padding]'><span class='pagecontent'>{$f_post_array['usersignature']}</span></td>
</tr>");
		}

		$f_return .= "</tbody>\n</table>";
	}

	return $f_return;
}

//f// direct_oset_discuss_topic_preview ()
/**
* direct_oset_discuss_topic_preview ()
*
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return string Valid XHTML code
* @since  v0.1.00
*/
function direct_oset_discuss_topic_preview ()
{
	global $direct_cachedata,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_oset_discuss_topic_preview ()- (#echo(__LINE__)#)"); }

$f_return = ("<table cellspacing='1' summary='' class='pageborder1' style='width:100%;table-layout:auto'>
<thead><tr>
<td colspan='2' align='left' class='pagetitlecellbg' style='padding:$direct_settings[theme_td_padding]'><span class='pagetitlecellcontent'>$direct_cachedata[output_title]</span></td>
</tr></thead><tbody>");

	if (strlen ($direct_cachedata['output_desc']))
	{
$f_return .= ("<tr>
<td valign='top' align='center' class='pageextrabg' style='width:25%;padding:$direct_settings[theme_form_td_padding]'><span class='pageextracontent' style='font-weight:bold'>".(direct_local_get ("discuss_topic_desc")).":</span></td>
<td valign='middle' align='left' class='pagebg' style='width:75%;padding:$direct_settings[theme_form_td_padding]'><span class='pagecontent'>$direct_cachedata[output_desc]</span></td>
</tr>");
	}

$f_return .= ("<tr>
<td valign='top' align='center' class='pageextrabg' style='width:25%;padding:$direct_settings[theme_form_td_padding]'><p class='pageextracontent' style='font-weight:bold'>$direct_cachedata[output_username]</p></td>
<td valign='middle' align='left' class='pagebg' style='width:75%;padding:$direct_settings[theme_form_td_padding]'><span class='pagecontent'>$direct_cachedata[output_post]</span></td>
</tr></tbody>
</table>");

	return $f_return;
}

//f// direct_oset_discuss_topics_parse ($f_topics)
/**
* direct_oset_discuss_topics_parse ()
*
* @param  array $f_topics Topics to be parsed
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return string Valid XHTML code
* @since  v0.1.00
*/
function direct_oset_discuss_topics_parse ($f_topics)
{
	global $direct_cachedata,$direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_oset_discuss_topics_parse (+f_topics)- (#echo(__LINE__)#)"); }

$f_return = ("<table cellspacing='1' summary='' class='pageborder1' style='width:100%'>
<thead><tr>
<td valign='middle' align='center' class='pagetitlecellbg' style='width:55%;padding:$direct_settings[theme_td_padding]'><span class='pagetitlecellcontent'>".(direct_local_get ("discuss_topic"))."</span></td>
<td valign='middle' align='center' class='pagetitlecellbg' style='width:10%;padding:$direct_settings[theme_td_padding]'><span class='pagetitlecellcontent' style='font-size:10px'>".(direct_local_get ("discuss_posts"))."</span></td>
<td valign='middle' align='center' class='pagetitlecellbg' style='width:35%;padding:$direct_settings[theme_td_padding]'><span class='pagetitlecellcontent' style='font-size:10px'>".(direct_local_get ("discuss_latest_post"))."</span></td>
</tr></thead><tbody>");

	foreach ($f_topics as $f_topic_array)
	{
		$f_return .= "<tr>\n<td valign='middle' align='left' class='pagebg' style='width:55%;padding:$direct_settings[theme_td_padding]'><p class='pagecontent'>";

		if (($direct_settings['discuss_datacenter_symbols'])&&($f_topic_array['symbol'])) { $f_return .= "<img src='{$f_topic_array['symbol']}' border='0' alt='' title='' style='float:left;margin-right:5px' />"; }
		if ($f_topic_array['locked']) { $f_return .= "<img src='".(direct_linker_dynamic ("url0","s=cache&dsd=dfile+$direct_settings[path_themes]/$direct_settings[theme]/status_locked.png",true,false))."' border='0' alt='".(direct_local_get ("discuss_topic_locked"))."' title='".(direct_local_get ("discuss_topic_locked"))."' style='float:right' />"; }

		if ($f_topic_array['new'])
		{
			$f_return .= ((isset ($f_topic_array['last_post_jump_counted'])) ? "<a id='{$f_topic_array['id']}_new' href='{$f_topic_array['last_post_jump_counted']}' target='_self'>" : "<a id='{$f_topic_array['id']}_new' href='{$f_topic_array['last_post_jump']}' target='_self'>");
			$f_return .= "<img src='".(direct_linker_dynamic ("url0","s=cache&dsd=dfile+$direct_settings[path_themes]/$direct_settings[theme]/status_comment_new.png",true,false))."' border='0' alt='".(direct_local_get ("discuss_topic_new_posts"))."' title='".(direct_local_get ("discuss_topic_new_posts"))."' style='float:right' /></a><script language='JavaScript1.5' type='text/javascript'><![CDATA[\n";
			$f_return .= ((isset ($direct_cachedata['output_board'])) ? "if (!djs_swgStorage_new_check ('{$f_topic_array['oid']}','{$direct_cachedata['output_board']['oid']}','{$f_topic_array['posts']}')) { djs_swgDOM_structure_delete ('{$f_topic_array['id']}_new'); }" : "if (!djs_swgStorage_new_check ('{$f_topic_array['oid']}',null,'{$f_topic_array['posts']}')) { djs_swgDOM_structure_delete ('{$f_topic_array['id']}_new'); }");
			$f_return .= "\n]]></script>";
		}

		if ($f_topic_array['sticky']) { $f_return .= "<img src='".(direct_linker_dynamic ("url0","s=cache&dsd=dfile+$direct_settings[path_themes]/$direct_settings[theme]/status_sticky.png",true,false))."' border='0' alt='".(direct_local_get ("discuss_topic_sticky"))."' title='".(direct_local_get ("discuss_topic_sticky"))."' style='float:right' />"; }
		$f_return .= ((isset ($f_topic_array['pageurl_counted'])) ? "<span style='font-weight:bold'><a href=\"{$f_topic_array['pageurl_counted']}\" target='_self'>{$f_topic_array['title']}</a></span>" : "<span style='font-weight:bold'><a href=\"{$f_topic_array['pageurl']}\" target='_self'>{$f_topic_array['title']}</a></span>");

		if (($f_topic_array['desc'])||(isset ($f_topic_array['pages_counted']))||(isset ($f_topic_array['pages_counted'])))
		{
			$f_return .= "</p>\n<p class='pagecontent' style='font-size:10px'>";
			if ($f_topic_array['desc']) { $f_return .= $f_topic_array['desc']; }

			if (isset ($f_topic_array['pages_counted']))
			{
				if ($f_topic_array['desc']) { $f_return .= "<br />\n"; }
				$f_return .= $f_topic_array['pages_counted'];
			}
			elseif ($f_topic_array['pages'])
			{
				if ($f_topic_array['desc']) { $f_return .= "<br />\n"; }
				$f_return .= $f_topic_array['pages'];
			}
		}

		$f_return .= "</p></td>\n<td valign='middle' align='center' class='pageextrabg' style='width:10%;padding:$direct_settings[theme_td_padding]'><span class='pageextracontent'>".$f_topic_array['posts'];
		if ($f_topic_array['views_counted']) { $f_return .= "<br /><span style='font-size:10px'>".(direct_local_get ("discuss_topic_views_1")).$f_topic_array['views'].(direct_local_get ("discuss_topic_views_2"))."</span>"; }
		$f_return .= "</span></td>";

		if ($f_topic_array['last_post_jump'])
		{
			$f_return .= "\n<td valign='middle' align='left' class='pagebg' style='width:35%;padding:$direct_settings[theme_td_padding]'><span class='pagecontent' style='font-size:10px'><span style='font-weight:bold'>{$f_topic_array['last_time']}</span>";
			if (($f_topic_array['last_preview'])&&($f_topic_array['username'])) { $f_return .= "<br />\n{$f_topic_array['last_preview']} ({$f_topic_array['username']})"; }
			$f_return .= "</span></td>";
		}
		else { $f_return .= "\n<td valign='middle' align='center' class='pagebg' style='width:35%;padding:$direct_settings[theme_td_padding]'><span class='pagecontent' style='font-size:10px;font-weight:bold'>".(direct_local_get ("discuss_without_posts"))."</span></td>"; }

		$f_return .= "\n</tr>";
	}

	return $f_return."</tbody>\n</table>";
}

//f// direct_oset_discuss_topics_latest_parse ($f_topics)
/**
* direct_oset_discuss_topics_latest_parse ()
*
* @param  array $f_topics Topics to be parsed
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return string Valid XHTML code
* @since  v0.1.00
*/
function direct_oset_discuss_topics_latest_parse ($f_topics)
{
	global $direct_cachedata,$direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_oset_discuss_topics_latest_parse (+f_topics)- (#echo(__LINE__)#)"); }

$f_return = ("<table cellspacing='1' summary='' class='pageborder1' style='width:100%'>
<thead><tr>
<td valign='middle' align='center' class='pagetitlecellbg' style='width:55%;padding:$direct_settings[theme_td_padding]'><span class='pagetitlecellcontent'>".(direct_local_get ("discuss_topic"))."</span></td>
<td valign='middle' align='center' class='pagetitlecellbg' style='width:10%;padding:$direct_settings[theme_td_padding]'><span class='pagetitlecellcontent' style='font-size:10px'>".(direct_local_get ("discuss_posts"))."</span></td>
<td valign='middle' align='center' class='pagetitlecellbg' style='width:35%;padding:$direct_settings[theme_td_padding]'><span class='pagetitlecellcontent' style='font-size:10px'>".(direct_local_get ("discuss_latest_post"))."</span></td>
</tr></thead><tbody>");

	foreach ($f_topics as $f_topic_array)
	{
		$f_return .= "<tr>\n<td valign='middle' align='left' class='pagebg' style='width:55%;padding:$direct_settings[theme_td_padding]'><p class='pagecontent'>";
		if (($direct_settings['discuss_datacenter_symbols'])&&($f_topic_array['symbol'])) { $f_return .= "<img src='{$f_topic_array['symbol']}' border='0' alt='' title='' style='float:left;margin-right:5px' />"; }
		if ($f_topic_array['locked']) { $f_return .= "<img src='".(direct_linker_dynamic ("url0","s=cache&dsd=dfile+$direct_settings[path_themes]/$direct_settings[theme]/status_locked.png",true,false))."' border='0' alt='".(direct_local_get ("discuss_topic_locked"))."' title='".(direct_local_get ("discuss_topic_locked"))."' style='float:right' />"; }

		if ($f_topic_array['new'])
		{
			$f_return .= ((isset ($f_topic_array['last_post_jump_counted'])) ? "<a id='{$f_topic_array['id']}_new' href='{$f_topic_array['last_post_jump_counted']}' target='_self'>" : "<a id='{$f_topic_array['id']}_new' href='{$f_topic_array['last_post_jump']}' target='_self'>");

$f_return .= ("<img src='".(direct_linker_dynamic ("url0","s=cache&dsd=dfile+$direct_settings[path_themes]/$direct_settings[theme]/status_comment_new.png",true,false))."' border='0' alt='".(direct_local_get ("discuss_topic_new_posts"))."' title='".(direct_local_get ("discuss_topic_new_posts"))."' style='float:right' /></a><script language='JavaScript1.5' type='text/javascript'><![CDATA[
djs_discuss_topics_new_check ('{$f_topic_array['id']}_new','','discuss_topics_{$f_topic_array['oid']}','{$f_topic_array['posts']}');
]]></script>");
		}

		if ($f_topic_array['sticky']) { $f_return .= "<img src='".(direct_linker_dynamic ("url0","s=cache&dsd=dfile+$direct_settings[path_themes]/$direct_settings[theme]/status_sticky.png",true,false))."' border='0' alt='".(direct_local_get ("discuss_topic_sticky"))."' title='".(direct_local_get ("discuss_topic_sticky"))."' style='float:right' />"; }
		$f_return .= ((isset ($f_topic_array['pageurl_counted'])) ? "<span style='font-weight:bold'><a href=\"{$f_topic_array['pageurl_counted']}\" target='_self'>{$f_topic_array['title']}</a></span>" : "<span style='font-weight:bold'><a href=\"{$f_topic_array['pageurl']}\" target='_self'>{$f_topic_array['title']}</a></span>");
		$f_return .= "</p>\n<p class='pagecontent' style='font-size:10px'>";
		if ($f_topic_array['desc']) { $f_return .= $f_topic_array['desc']."<br />\n"; }
		if ($f_topic_array['pages']) { $f_return .= $f_topic_array['pages']." - "; }

$f_return .= ("<span style='font-weight:bold'><a href=\"{$f_topic_array['pageurl_main']}\" target='_self'>".(direct_local_get ("discuss_board"))."</a></span></p></td>
<td valign='middle' align='center' class='pagebg' style='width:10%;padding:$direct_settings[theme_td_padding]'><span class='pagecontent'>{$f_topic_array['posts']}</span></td>");

		if (($f_topic_array['last_post_jump'])&&($f_topic_array['username']))
		{
			$f_return .= "\n<td valign='middle' align='left' class='pageextrabg' style='width:35%;padding:$direct_settings[theme_td_padding]'><span class='pageextracontent' style='font-size:10px'><span style='font-weight:bold'>{$f_topic_array['last_time']}</span>";
			if ($f_topic_array['last_preview']) { $f_return .= "<br />\n{$f_topic_array['last_preview']} ({$f_topic_array['username']})"; }
			$f_return .= "</span></td>";
		}
		else { $f_return .= "\n<td valign='middle' align='center' class='pagebg' style='width:35%;padding:$direct_settings[theme_td_padding]'><span class='pagecontent' style='font-size:10px;font-weight:bold'>".(direct_local_get ("discuss_without_posts"))."</span></td>"; }

		$f_return .= "\n</tr>";
	}

	return $f_return."</tbody>\n</table>";
}

//j// Script specific commands

if (!isset ($direct_settings['theme_hr_style'])) { $direct_settings['theme_hr_style'] = "display:block;height:1px;overflow:hidden"; }
if (!isset ($direct_settings['theme_td_padding'])) { $direct_settings['theme_td_padding'] = "5px"; }
if (!isset ($direct_settings['theme_form_td_padding'])) { $direct_settings['theme_form_td_padding'] = "3px"; }

//j// EOF
?>