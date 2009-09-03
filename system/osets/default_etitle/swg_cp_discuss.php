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
* osets/default_etitle/swg_cp_discuss.php
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

//f// direct_output_oset_cp_discuss_board_list ($f_subs_array = NULL)
/**
* direct_output_oset_cp_discuss_board_list ()
*
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return string Valid XHTML code
* @since  v0.1.00
*/
function direct_output_oset_cp_discuss_board_list ($f_subs_array = NULL)
{
	global $direct_cachedata,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_output_oset_cp_discuss_board_list (+f_subs_array)- (#echo(__LINE__)#)"); }

	if (is_array ($f_subs_array))
	{
		$f_css_margin = " style='margin:10px'";
		$f_return = "";
		$f_toplevel_check = false;
	}
	else
	{
		$direct_settings['theme_output_page_title'] = direct_local_get ("cp_discuss_boards");
		$f_css_margin = "";
		$f_subs_array = $direct_cachedata['output_boards'];
		$f_toplevel_check = true;

$f_return = ("<script language='JavaScript1.5' type='text/javascript'><![CDATA[
function djs_cp_discuss_board_list_{$direct_cachedata['output_did']}_move_call (f_deid,f_position,f_direction) { djs_swgAJAX_call ('cp_discuss_board_list_{$direct_cachedata['output_did']}_point',djs_cp_discuss_board_list_{$direct_cachedata['output_did']}_move_response,'GET','".(direct_linker_dynamic ("url0","m=dataport&s=swgap;cp;discuss;board&a=move&dsd=ddid+{$direct_cachedata['output_did']}++ddeid+' + f_deid + '++dposition+' + f_position + '++ddirection+' + f_direction + '++dtheme+0",false,true))."',8000); }
function djs_cp_discuss_board_list_{$direct_cachedata['output_did']}_move_response () { djs_swgAJAX_response ('cp_discuss_board_list_{$direct_cachedata['output_did']}_point','swgAJAX_cp_discuss_board_list_{$direct_cachedata['output_did']}_point',''); }
]]></script><div id='swgAJAX_cp_discuss_board_list_{$direct_cachedata['output_did']}_point'>");
	}

	if ((isset ($f_subs_array['data']))&&(!empty ($f_subs_array['data'])))
	{
		$f_return .= "<div class='pageborder2'$f_css_margin><span class='pageextracontent'>";
		$f_return .= ((strlen ($f_subs_array['data']['title_alt'])) ? "<span style='font-weight:bold'>{$f_subs_array['data']['title_alt']}</span> <span style='font-size:10px'>({$f_subs_array['data']['title']})</span>" : "<span style='font-weight:bold'>{$f_subs_array['data']['title']}</span>");

		if ($f_subs_array['data']['position'] > 0)
		{
$f_return .= (" <span style='font-size:10px'>(<a id='swgAJAX_cp_discuss_board_list_{$direct_cachedata['output_did']}_{$f_subs_array['data']['subs_id']}_move_up_point' href=\"{$f_subs_array['data']['pageurl_up']}\" target='_self'>".(direct_local_get ("cp_discuss_board_up"))."</a> - <a id='swgAJAX_cp_discuss_board_list_{$direct_cachedata['output_did']}_{$f_subs_array['data']['subs_id']}_move_down_point' href=\"{$f_subs_array['data']['pageurl_down']}\" target='_self'>".(direct_local_get ("cp_discuss_board_down"))."</a><script language='JavaScript1.5' type='text/javascript'><![CDATA[
if ((djs_swgAJAX)&&(djs_swgDOM))
{
	djs_swgDOM_replace (\"<a href=\\\"javascript:djs_cp_discuss_board_list_{$direct_cachedata['output_did']}_move_call('{$f_subs_array['data']['oid']}','{$f_subs_array['data']['position']}','up')\\\">".(direct_local_get ("cp_discuss_board_up"))."</a>\",'swgAJAX_cp_discuss_board_list_{$direct_cachedata['output_did']}_{$f_subs_array['data']['subs_id']}_move_up_point');
	djs_swgDOM_replace (\"<a href=\\\"javascript:djs_cp_discuss_board_list_{$direct_cachedata['output_did']}_move_call('{$f_subs_array['data']['oid']}','{$f_subs_array['data']['position']}','down')\\\">".(direct_local_get ("cp_discuss_board_down"))."</a>\",'swgAJAX_cp_discuss_board_list_{$direct_cachedata['output_did']}_{$f_subs_array['data']['subs_id']}_move_down_point');
}
]]></script>)<br />\n");
		}
		else { $f_return .= "<br />\n<span style='font-size:10px'>"; }
	
		if (isset ($f_subs_array['data']['pageurl_edit'])) { $f_return .= "<a href=\"{$f_subs_array['data']['pageurl_edit']}\" target='_self'>".(direct_local_get ("cp_discuss_board_edit"))."</a> - "; }
		if (isset ($f_subs_array['data']['pageurl_new'])) { $f_return .= "<a href=\"{$f_subs_array['data']['pageurl_new']}\" target='_self'>".(direct_local_get ("cp_discuss_board_new"))."</a> - "; }
		$f_return .= "<a href=\"{$f_subs_array['data']['pageurl_link_new']}\" target='_self'>".(direct_local_get ("cp_discuss_board_link_new"))."</a>";
		if (isset ($f_subs_array['data']['pageurl_delete'])) { $f_return .= " - <a href=\"{$f_subs_array['data']['pageurl_delete']}\" target='_self'>".(direct_local_get ("cp_discuss_board_delete"))."</a>"; }
		if (isset ($f_subs_array['data']['pageurl_link_delete'])) { $f_return .= " - <a href=\"{$f_subs_array['data']['pageurl_link_delete']}\" target='_self'>".(direct_local_get ("cp_discuss_board_link_delete"))."</a>"; }
		$f_return .= "</span></span>";

		if (isset ($f_subs_array['subs']))
		{
			$f_return .= "<div>";
			foreach ($f_subs_array['subs'] as $f_sub_array) { $f_return .= direct_output_oset_cp_discuss_board_list ($f_sub_array); }
			$f_return .= "</div>";
		}

		$f_return .= "</div>";
	}
	elseif ($f_toplevel_check) { $f_return .= "\n<span class='pagecontent' style='font-weight:bold'>".(direct_local_get ("cp_discuss_board_list_empty"))."</span>"; }

	if ($f_toplevel_check) { $f_return .= "</div>"; }
	return $f_return;
}

//f// direct_output_oset_cp_discuss_boards ()
/**
* direct_output_oset_cp_discuss_embedded_ajax_board_list ()
*
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return string Valid XHTML code
* @since  v0.1.00
*/
function direct_output_oset_cp_discuss_boards ()
{
	global $direct_cachedata;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_output_oset_cp_discuss_boards ()- (#echo(__LINE__)#)"); }

	$direct_settings['theme_output_page_title'] = direct_local_get ("cp_discuss_boards");

	if (empty ($direct_cachedata['output_boards'])) { $f_return = "<p class='pagecontent' style='font-weight:bold'>".(direct_local_get ("cp_discuss_board_list_empty"))."</p>"; }
	else
	{
		$f_return = "";

		foreach ($direct_cachedata['output_boards'] as $f_board_array)
		{
$f_return .= ("\n<p class='pageborder2' style='text-align:center'><span class='pageextracontent'><span style='font-weight:bold'>$f_board_array[title]</span><br />
<a href=\"$f_board_array[pageurl]\" target='_self'>".(direct_local_get ("cp_discuss_boards_edit"))."</a></span></p>");
		}
	}

	return $f_return;
}

//j// EOF
?>