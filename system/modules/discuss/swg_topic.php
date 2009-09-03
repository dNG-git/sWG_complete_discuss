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
* discuss/swg_topic.php
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

//j// Script specific commands

if (!isset ($direct_settings['datalinker_title_min'])) { $direct_settings['datalinker_title_min'] = 3; }
if (!isset ($direct_settings['datalinker_title_max'])) { $direct_settings['datalinker_title_max'] = 255; }
if (!isset ($direct_settings['discuss_account_status_ex'])) { $direct_settings['discuss_account_status_ex'] = false; }
if (!isset ($direct_settings['discuss_datacenter_symbols'])) { $direct_settings['discuss_datacenter_symbols'] = false; }
if (!isset ($direct_settings['discuss_datacenter_path_symbols'])) { $direct_settings['discuss_datacenter_path_symbols'] = $direct_settings['path_themes']."/$direct_settings[theme]/"; }
if (!isset ($direct_settings['discuss_https_topic'])) { $direct_settings['discuss_https_topic'] = false; }
if (!isset ($direct_settings['discuss_latest_topics_on_page'])) { $direct_settings['discuss_latest_topics_on_page'] = 5; }
if (!isset ($direct_settings['discuss_post_min'])) { $direct_settings['discuss_post_min'] = 12; }
if (!isset ($direct_settings['discuss_preview_length'])) { $direct_settings['discuss_preview_length'] = 125; }
if (!isset ($direct_settings['discuss_topic_desc_min'])) { $direct_settings['discuss_topic_desc_min'] = 2; }
if (!isset ($direct_settings['discuss_topic_desc_max'])) { $direct_settings['discuss_topic_desc_max'] = 255; }
// TODO if (!isset ($direct_settings['discuss_topic_edit_boards_movements_allowed'])) { $direct_settings['discuss_topic_edit_board_movements_allowed'] = true; }
if (!isset ($direct_settings['discuss_topic_edit_credits_onetime'])) { $direct_settings['discuss_topic_edit_credits_onetime'] = 0; }
// TODO hmm?!? if (!isset ($direct_settings['discuss_topic_edit_non_boards_movements_allowed'])) { $direct_settings['discuss_topic_edit_non_board_movements_allowed'] = false; }
if (!isset ($direct_settings['discuss_topic_min'])) { $direct_settings['discuss_topic_min'] = $direct_settings['datalinker_title_min']; }
if (!isset ($direct_settings['discuss_topic_max'])) { $direct_settings['discuss_topic_max'] = $direct_settings['datalinker_title_max']; }
if (!isset ($direct_settings['discuss_topic_new_credits_onetime'])) { $direct_settings['discuss_topic_new_credits_onetime'] = 0; }
if (!isset ($direct_settings['discuss_topic_new_credits_periodically'])) { $direct_settings['discuss_topic_new_credits_periodically'] = 0; }
if (!isset ($direct_settings['discuss_vote'])) { $direct_settings['discuss_vote'] = false; }
if (!isset ($direct_settings['formtags_overview_document_url'])) { $direct_settings['formtags_overview_document_url'] = "m=contentor&a=view&dsd=cdid+dng_{$direct_settings['lang']}_2_90000000001"; }
if (!isset ($direct_settings['serviceicon_default_back'])) { $direct_settings['serviceicon_default_back'] = "mini_default_back.png"; }
if (!isset ($direct_settings['swg_data_limit'])) { $direct_settings['swg_data_limit'] = 16777216; }
$direct_settings['additional_copyright'][] = array ("Module discuss #echo(sWGdiscussVersion)# - (C) ","http://www.direct-netware.de/redirect.php?swg","direct Netware Group"," - All rights reserved");

//j// BOS
switch ($direct_settings['a'])
{
//j// ($direct_settings['a'] == "edit")||($direct_settings['a'] == "edit-save")
case "edit":
case "edit-save":
{
	$g_mode_save = (($direct_settings['a'] == "edit-save") ? true : false);
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a={$direct_settings['a']}_ (#echo(__LINE__)#)"); }

	$g_did = (isset ($direct_settings['dsd']['ddid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['ddid'])) : "");
	$g_tid = (isset ($direct_settings['dsd']['dtid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['dtid'])) : "");
	$g_connector = (isset ($direct_settings['dsd']['connector']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['connector'])) : "");
	$g_source = (isset ($direct_settings['dsd']['source']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['source'])) : "");
	$g_target = (isset ($direct_settings['dsd']['target']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['target'])) : "");

	$g_did_dsd = (strlen ($g_did) ? "ddid+{$g_did}++" : "");
	$g_connector_url = ($g_connector ? base64_decode ($g_connector) : "m=discuss&a=[a]&dsd={$g_did_dsd}[oid]");
	$g_source_url = ($g_source ? base64_decode ($g_source) : "m=discuss&a=posts&dsd={$g_did_dsd}[oid]");

	if ($g_target) { $g_target_url = base64_decode ($g_target); }
	else
	{
		$g_target = $g_source;
		$g_target_url = ($g_source ? $g_source_url : "");
	}

	$g_back_link = (((!$g_source)&&($g_connector_url)) ? preg_replace (array ("#\[a\]#","#\[oid\]#","#\[(.*?)\]#"),(array ("posts","dtid+{$g_tid}++","")),$g_connector_url) : str_replace ("[oid]","dtid+{$g_tid}++",$g_source_url));

	if ($g_mode_save)
	{
		$direct_cachedata['page_this'] = "";
		$direct_cachedata['page_backlink'] = "m=discuss&s=topic&a=edit&dsd={$g_did_dsd}dtid+{$g_tid}++connector+".(urlencode ($g_connector))."++source+".(urlencode ($g_source))."++target+".(urlencode ($g_target));
		$direct_cachedata['page_homelink'] = $g_back_link;
	}
	else
	{
		$direct_cachedata['page_this'] = "m=discuss&s=topic&a=edit&dsd={$g_did_dsd}dtid+{$g_tid}++connector+".(urlencode ($g_connector))."++source+".(urlencode ($g_source))."++target+".(urlencode ($g_target));
		$direct_cachedata['page_backlink'] = $g_back_link;
		$direct_cachedata['page_homelink'] = $g_back_link;
	}

	if ($direct_classes['kernel']->service_init_default ())
	{
	//j// BOA
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_board.php");
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_topic.php");
	direct_local_integration ("discuss");

	$g_datasub_check = false;
	$g_topic_object = new direct_discuss_topic ();
	$g_rights_check = false;

	$g_board_array = NULL;
	$g_topic_array = ($g_topic_object ? $g_topic_object->get ($g_tid) : NULL);

	if (is_array ($g_topic_array))
	{
		if ($g_topic_array['ddbdatalinker_id_main'])
		{
			$g_board_object = new direct_discuss_board ();
			if ($g_board_object) { $g_board_array = $g_board_object->get ($g_topic_array['ddbdatalinker_id_main']); }
			if ((is_array ($g_board_array))&&($g_topic_object->is_writable ())) { $g_rights_check = $g_board_object->is_writable (); }
		}
		elseif ($g_rights_check)
		{
			$g_datasub_check = true;
			$g_rights_check = $g_topic_object->is_writable ();
		}
	}

	if ((!$g_datasub_check)&&(!is_array ($g_board_array))) { $direct_classes['error_functions']->error_page ("standard","discuss_tid_invalid","sWG/#echo(__FILEPATH__)# _a={$direct_settings['a']}_ (#echo(__LINE__)#)"); }
	elseif ($g_rights_check)
	{
		if ($g_mode_save)
		{
			if ($g_datasub_check) { direct_output_related_manager ("discuss_topic_edit_{$g_tid}_form_save","pre_module_service_action"); }
			else { direct_output_related_manager ("discuss_topic_edit_{$g_board_array['ddbdatalinker_id']}_{$g_tid}_form_save","pre_module_service_action"); }
		}
		elseif ($g_datasub_check) { direct_output_related_manager ("discuss_topic_edit_{$g_tid}_form","pre_module_service_action"); }
		else { direct_output_related_manager ("discuss_topic_edit_{$g_board_array['ddbdatalinker_id']}_{$g_tid}_form","pre_module_service_action"); }

		if (!$g_mode_save) { $direct_classes['kernel']->service_https ($direct_settings['discuss_https_topic'],$direct_cachedata['page_this']); }
		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/swg_formbuilder.php");
		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/swg_formtags.php");
		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/functions/swg_credits_manager.php");
		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/functions/swg_tmp_storager.php");

		if (($direct_settings['discuss_datacenter_symbols'])&&(isset ($direct_settings['discuss_datacenter_symbols_did'])))
		{
			$direct_classes['basic_functions']->settings_get ($direct_settings['path_data']."/settings/swg_datacenter.php",true);
			$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_datacenter.php");
		}

		direct_class_init ("formbuilder");
		direct_class_init ("formtags");
		direct_class_init ("output");
		$direct_classes['output']->options_insert (2,"servicemenu",$direct_cachedata['page_backlink'],(direct_local_get ("core_back")),$direct_settings['serviceicon_default_back'],"url0");

		$g_credits_periodically = 0;
		if (!$g_datasub_check) { direct_credits_payment_get_specials ("discuss_topic_edit",$g_board_array['ddbdatalinker_id_object'],$direct_settings['discuss_topic_edit_credits_onetime'],$g_credits_periodically); }
		$direct_cachedata['output_credits_information'] = direct_credits_payment_info ($direct_settings['discuss_topic_edit_credits_onetime'],0);
		$direct_cachedata['output_credits_payment_data'] = direct_credits_payment_check (true,$direct_settings['discuss_topic_edit_credits_onetime']);

		if ($g_datasub_check) { $g_rights_check = (($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 3) ? true : false); }
		else { $g_rights_check = $g_board_object->is_moderator (); }

		if ($g_mode_save)
		{
/* -------------------------------------------------------------------------
We should have input in save mode
------------------------------------------------------------------------- */

			$direct_cachedata['i_dtitle'] = (isset ($GLOBALS['i_dtitle']) ? ($direct_classes['basic_functions']->inputfilter_basic ($GLOBALS['i_dtitle'])) : "");
			$direct_cachedata['i_ddesc'] = (isset ($GLOBALS['i_ddesc']) ? ($direct_classes['basic_functions']->inputfilter_basic ($GLOBALS['i_ddesc'])) : "");
			if (($direct_settings['discuss_datacenter_symbols'])&&(isset ($direct_settings['discuss_datacenter_symbols_did']))) { $direct_cachedata['i_dsymbol'] = (isset ($GLOBALS['i_dsymbol']) ? (urlencode ($GLOBALS['i_dsymbol'])) : ""); }

			if ($g_rights_check)
			{
				$direct_cachedata['i_dsubs_allowed'] = (isset ($GLOBALS['i_dsubs_allowed']) ? (str_replace ("'","",$GLOBALS['i_dsubs_allowed'])) : "");
				$direct_cachedata['i_dsubs_allowed'] = str_replace ("<value value='$direct_cachedata[i_dsubs_allowed]' />","<value value='$direct_cachedata[i_dsubs_allowed]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

				$direct_cachedata['i_dsubs_hidden'] = (isset ($GLOBALS['i_dsubs_hidden']) ? (str_replace ("'","",$GLOBALS['i_dsubs_hidden'])) : "");
				$direct_cachedata['i_dsubs_hidden'] = str_replace ("<value value='$direct_cachedata[i_dsubs_hidden]' />","<value value='$direct_cachedata[i_dsubs_hidden]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

				$direct_cachedata['i_dsubs_type'] = (isset ($GLOBALS['i_dsubs_type']) ? (str_replace ("'","",$GLOBALS['i_dsubs_type'])) : 0);

				$direct_cachedata['i_dsticky'] = (isset ($GLOBALS['i_dsticky']) ? (str_replace ("'","",$GLOBALS['i_dsticky'])) : "");
				$direct_cachedata['i_dsticky'] = str_replace ("<value value='$direct_cachedata[i_dsticky]' />","<value value='$direct_cachedata[i_dsticky]' /><selected value='1' />","<evars><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes></evars>");

				$direct_cachedata['i_dlocked'] = (isset ($GLOBALS['i_dlocked']) ? (str_replace ("'","",$GLOBALS['i_dlocked'])) : "");
				$direct_cachedata['i_dlocked'] = str_replace ("<value value='$direct_cachedata[i_dlocked]' />","<value value='$direct_cachedata[i_dlocked]' /><selected value='1' />","<evars><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes></evars>");
			}
			elseif ((!$g_datasub_check)&&($g_topic_object->is_sub_allowed ()))
			{
				$direct_cachedata['i_dsubs_allowed'] = (isset ($GLOBALS['i_dsubs_allowed']) ? (str_replace ("'","",$GLOBALS['i_dsubs_allowed'])) : "");
				$direct_cachedata['i_dsubs_allowed'] = str_replace ("<value value='$direct_cachedata[i_dsubs_allowed]' />","<value value='$direct_cachedata[i_dsubs_allowed]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

				$direct_cachedata['i_dsubs_hidden'] = (isset ($GLOBALS['i_dsubs_hidden']) ? (str_replace ("'","",$GLOBALS['i_dsubs_hidden'])) : "");
				$direct_cachedata['i_dsubs_hidden'] = str_replace ("<value value='$direct_cachedata[i_dsubs_hidden]' />","<value value='$direct_cachedata[i_dsubs_hidden]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

				$direct_cachedata['i_dsubs_type'] = (isset ($GLOBALS['i_dsubs_type']) ? (str_replace ("'","",$GLOBALS['i_dsubs_type'])) : 0);
			}
		}
		else
		{
			$direct_cachedata['i_dtitle'] = $g_topic_array['ddbdatalinker_title'];
			$direct_cachedata['i_ddesc'] = $direct_classes['formtags']->recode_newlines (direct_output_smiley_cleanup ($g_topic_array['ddbdiscuss_topics_desc']),false);

			if (($direct_settings['discuss_datacenter_symbols'])&&(isset ($direct_settings['discuss_datacenter_symbols_did'])))
			{
				$direct_cachedata['i_dsymbol'] = uniqid ("");

$g_task_array = array (
"core_sid" => "d4d66a02daefdb2f70ff2507a78fd5ec",
// md5 ("datacenter")
"datacenter_marker_type" => "files-only",
"datacenter_selection_did" => $direct_settings['contentor_datacenter_symbols_did'],
"datacenter_selection_done" => 0,
"datacenter_selection_path" => $direct_settings['contentor_datacenter_path_symbols'],
"datacenter_selection_quantity" => 1,
"uuid" => $direct_settings['uuid']
);

				$g_symbol_marked_object = ($g_topic_array['ddbdatalinker_symbol'] ? new direct_datacenter () : NULL);
				$g_symbol_marked_array = ($g_symbol_marked_object ? $g_symbol_marked_object->get_aid ("ddbdatacenter_plocation",$g_topic_array['ddbdatalinker_symbol']) : NULL);
				if ($g_symbol_marked_array) { $g_task_array['datacenter_objects_marked'] = array ($g_symbol_marked_array['ddbdatacenter_id'] => $g_symbol_marked_array['ddbdatacenter_id']); }

				direct_tmp_storage_write ($g_task_array,$direct_cachedata['i_dsymbol'],"d4d66a02daefdb2f70ff2507a78fd5ec","task_cache","evars",$direct_cachedata['core_time'],($direct_cachedata['core_time'] + 3600));
				// md5 ("datacenter")
			}

			if ($g_rights_check)
			{
				if ($g_topic_array['ddbdatalinker_datasubs_new']) { $direct_cachedata['i_dsubs_allowed'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
				else { $direct_cachedata['i_dsubs_allowed'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

				if ($g_topic_array['ddbdatalinker_datasubs_hide']) { $direct_cachedata['i_dsubs_hidden'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
				else { $direct_cachedata['i_dsubs_hidden'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

				$direct_cachedata['i_dsubs_type'] = (isset ($g_topic_array['ddbdatalinker_datasubs_type']) ? str_replace ("'","",$g_topic_array['ddbdatalinker_datasubs_type']) : 0);

				if ($g_topic_object->is_sticky ()) { $direct_cachedata['i_dsticky'] = "<evars><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes></evars>"; }
				else { $direct_cachedata['i_dsticky'] = "<evars><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes></evars>"; }

				if ($g_topic_object->is_locked ()) { $direct_cachedata['i_dlocked'] = "<evars><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes></evars>"; }
				else { $direct_cachedata['i_dlocked'] = "<evars><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes></evars>"; }
			}
			elseif ((!$g_datasub_check)&&($g_topic_object->is_sub_allowed ()))
			{
				if ($g_topic_array['ddbdatalinker_datasubs_new']) { $direct_cachedata['i_dsubs_allowed'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
				else { $direct_cachedata['i_dsubs_allowed'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

				if ($g_topic_array['ddbdatalinker_datasubs_hide']) { $direct_cachedata['i_dsubs_hidden'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
				else { $direct_cachedata['i_dsubs_hidden'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

				$direct_cachedata['i_dsubs_type'] = (isset ($g_topic_array['ddbdatalinker_datasubs_type']) ? str_replace ("'","",$g_topic_array['ddbdatalinker_datasubs_type']) : 0);
			}
		}

		if (isset ($direct_cachedata['i_dsubs_type']))
		{
$direct_cachedata['i_dsubs_type'] = str_replace ("<value value='$direct_cachedata[i_dsubs_type]' />","<value value='$direct_cachedata[i_dsubs_type]' /><selected value='1' />","<evars>
<default><value value='0' /><text><![CDATA[".(direct_local_get ("core_datasub_title_default"))."]]></text></default><attachments><value value='1' /><text><![CDATA[".(direct_local_get ("core_datasub_title_attachments"))."]]></text></attachments><downloads><value value='2' /><text><![CDATA[".(direct_local_get ("core_datasub_title_downloads"))."]]></text></downloads><links><value value='3' /><text><![CDATA[".(direct_local_get ("core_datasub_title_links"))."]]></text></links>
</evars>");
		}

/* -------------------------------------------------------------------------
Build the form
------------------------------------------------------------------------- */

		if (($direct_settings['discuss_account_status_ex'])&&(!$direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']))) { $direct_classes['formbuilder']->entry_add_embed ("dstatus_ex",NULL,true,"m=dataport&s=swgap;account;status_ex&dsd=",true,"s"); }
		$direct_classes['formbuilder']->entry_add_text ("dtitle",(direct_local_get ("discuss_topic")),true,"m",$direct_settings['discuss_topic_min'],$direct_settings['discuss_topic_max']);

		if ($direct_settings['formtags_overview_document_url']) { $direct_classes['formbuilder']->entry_add_jfield_text ("ddesc",(direct_local_get ("discuss_topic_desc")),false,"l",$direct_settings['discuss_topic_desc_min'],$direct_settings['discuss_topic_desc_max'],(direct_local_get ("formtags_overview_document")),(direct_linker ("url0",$direct_settings['formtags_overview_document_url']))); }
		else { $direct_classes['formbuilder']->entry_add_jfield_text ("ddesc",(direct_local_get ("discuss_topic_desc")),false,"l",$direct_settings['discuss_topic_desc_min'],$direct_settings['discuss_topic_desc_max']); }

		if (isset ($direct_cachedata['i_dsymbol']))
		{
			$direct_classes['formbuilder']->entry_add ("spacer");
			$direct_classes['formbuilder']->entry_add_embed ("dsymbol",(direct_local_get ("discuss_symbol")),false,"m=dataport&s=swgap;datacenter;selector_icons&dsd=",false,"s");
		}

		if ($g_rights_check)
		{
			$direct_classes['formbuilder']->entry_add ("spacer");
			$direct_classes['formbuilder']->entry_add_select ("dsubs_allowed",(direct_local_get ("core_datasub_allowed")),true,"s");
			$direct_classes['formbuilder']->entry_add_select ("dsubs_hidden",(direct_local_get ("core_datasub_hide")),true,"s");
			$direct_classes['formbuilder']->entry_add_radio ("dsubs_type",(direct_local_get ("core_datasub_type")),true);
			$direct_classes['formbuilder']->entry_add ("spacer");
			$direct_classes['formbuilder']->entry_add_select ("dsticky",(direct_local_get ("discuss_topic_stick")),false,"s");
			$direct_classes['formbuilder']->entry_add_select ("dlocked",(direct_local_get ("discuss_topic_lock")),false,"s");
		}
		elseif (isset ($direct_cachedata['i_dsubs_allowed']))
		{
			$direct_classes['formbuilder']->entry_add ("spacer");
			$direct_classes['formbuilder']->entry_add_select ("dsubs_allowed",(direct_local_get ("core_datasub_allowed")),true,"s");
			$direct_classes['formbuilder']->entry_add_select ("dsubs_hidden",(direct_local_get ("core_datasub_hide")),true,"s");
			$direct_classes['formbuilder']->entry_add_radio ("dsubs_type",(direct_local_get ("core_datasub_type")),true);
		}

		$direct_cachedata['output_formbutton'] = direct_local_get ("core_save");
		$direct_cachedata['output_formelements'] = $direct_classes['formbuilder']->form_get ($g_mode_save);
		$direct_cachedata['output_formtarget'] = "m=discuss&s=topic&a=edit-save&dsd={$g_did_dsd}dtid+{$g_tid}++connector+".(urlencode ($g_connector))."++source+".(urlencode ($g_source))."++target+".(urlencode ($g_target));
		$direct_cachedata['output_formtitle'] = direct_local_get ("discuss_topic_edit");

		if (($g_mode_save)&&($direct_classes['formbuilder']->check_result))
		{
/* -------------------------------------------------------------------------
Save data edited
------------------------------------------------------------------------- */

			$direct_cachedata['i_dsticky'] = (((isset ($direct_cachedata['i_dsticky']))&&($direct_cachedata['i_dsticky'])) ? 1 : 0);
			$direct_cachedata['i_dlocked'] = (((isset ($direct_cachedata['i_dlocked']))&&($direct_cachedata['i_dlocked'])) ? 1 : 0);

			if (direct_credits_payment_check (false,$direct_settings['discuss_topic_edit_credits_onetime']))
			{
				if ($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type'])) { $g_continue_check = true; }
				else
				{
					$g_uuid_storage_array = direct_tmp_storage_get ("evars",$direct_settings['uuid'],"","task_cache");

					if (($g_uuid_storage_array)&&(isset ($g_uuid_storage_array['account_status_ex_type'],$g_uuid_storage_array['account_status_ex_verified'])))
					{
						if ($g_uuid_storage_array['account_status_ex_verified']) { $g_continue_check = true; }
						else
						{
							direct_local_integration ("account");
							$direct_classes['error_functions']->error_page ("standard","account_status_ex_verify_first","sWG/#echo(__FILEPATH__)# _a=edit-save_ (#echo(__LINE__)#)");
							$g_continue_check = false;
						}
					}
					else
					{
						$direct_classes['error_functions']->error_page ("login","core_access_denied","sWG/#echo(__FILEPATH__)# _a=edit-save_ (#echo(__LINE__)#)");
						$g_continue_check = false;
					}
				}

				if ($g_continue_check)
				{
					if (isset ($direct_cachedata['i_dsymbol']))
					{
						$g_task_array = direct_tmp_storage_get ("evars",$direct_cachedata['i_dsymbol'],"d4d66a02daefdb2f70ff2507a78fd5ec","selector_cache");
						// md5 ("datacenter")
						$direct_cachedata['i_dsymbol'] = "";

						if ((is_array ($g_task_array['datacenter_objects_marked']))&&(!empty ($g_task_array['datacenter_objects_marked'])))
						{
							$g_symbol_marked_id = array_shift ($g_task_array['datacenter_objects_marked']);
							$g_symbol_marked_object = ($g_symbol_marked_id ? new direct_datacenter () : NULL);
							if (($g_symbol_marked_object)&&($g_symbol_marked_object->get ($g_symbol_marked_id))) { $direct_cachedata['i_dsymbol'] = $g_symbol_marked_object->get_plocation (); }
						}
					}
					else { $direct_cachedata['i_dsymbol'] = ""; }

					$g_topic_array['ddbdatalinker_position'] = $direct_cachedata['i_dsticky'];
					$g_topic_array['ddbdatalinker_symbol'] = $direct_cachedata['i_dsymbol'];
					$g_topic_array['ddbdatalinker_title'] = $direct_cachedata['i_dtitle'];

					if (isset ($direct_cachedata['i_dsubs_allowed']))
					{
						if ($direct_cachedata['i_dsubs_allowed'])
						{
							$g_topic_array['ddbdatalinker_datasubs_type'] = $direct_cachedata['i_dsubs_type'];
							$g_topic_array['ddbdatalinker_datasubs_hide'] = $direct_cachedata['i_dsubs_hidden'];
							$g_topic_array['ddbdatalinker_datasubs_new'] = 1;
						}
						else { $g_topic_array['ddbdatalinker_datasubs_new'] = 0; }
					}

					$g_topic_array['ddbdiscuss_topics_desc'] = direct_output_smiley_encode ($direct_classes['formtags']->encode ($direct_cachedata['i_ddesc']));
					$g_topic_array['ddbdiscuss_topics_locked'] = $direct_cachedata['i_dlocked'];

					if ($g_topic_object->set_update ($g_topic_array))
					{
						direct_credits_payment_exec ("discuss","topic_edit",$g_tid,$direct_settings['user']['id'],$direct_settings['discuss_topic_edit_credits_onetime'],0);

						$direct_cachedata['output_job'] = direct_local_get ("discuss_topic_edit");
						$direct_cachedata['output_job_desc'] = direct_local_get ("discuss_done_topic_edit");

						if ($g_target_url)
						{
							$direct_cachedata['output_jsjump'] = 2000;
							$g_target_link = str_replace ("[oid]","dtid_d+{$g_tid}++",$g_target_url);
						}
						elseif ($g_connector_url)
						{
							$direct_cachedata['output_jsjump'] = 2000;
							$g_target_link = str_replace (array ("[a]","[oid]"),(array ("posts","dtid+{$g_tid}++")),$g_connector_url);
						}
						else { $direct_cachedata['output_jsjump'] = 0; }

						if ($direct_cachedata['output_jsjump'])
						{
							$direct_cachedata['output_pagetarget'] = str_replace ('"',"",(direct_linker ("url0",$g_target_link)));
							$direct_cachedata['output_scripttarget'] = str_replace ('"',"",(direct_linker ("url0",$g_target_link,false)));
						}

						if ($g_datasub_check) { direct_output_related_manager ("discuss_topic_edit_{$g_tid}_form_save","post_module_service_action"); }
						else { direct_output_related_manager ("discuss_topic_edit_{$g_board_array['ddbdatalinker_id']}_{$g_tid}_form_save","post_module_service_action"); }

						$direct_classes['output']->oset ("default","done");
						$direct_classes['output']->options_flush (true);
						$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
						$direct_classes['output']->page_show ($direct_cachedata['output_job']);
					}
					else { $direct_classes['error_functions']->error_page ("fatal","core_database_error","sWG/#echo(__FILEPATH__)# _a=edit-save_ (#echo(__LINE__)#)"); }
				}
			}
			else { $direct_classes['error_functions']->error_page ("standard","core_credits_insufficient","SERVICE ERROR:<br />".(-1 * $direct_settings['discuss_topic_edit_credits_onetime'])." Credits are required but not available. This error has been reported by the sWG Credits Manager.<br />sWG/#echo(__FILEPATH__)# _a=edit-save_ (#echo(__LINE__)#)"); }
		}
		else
		{
/* -------------------------------------------------------------------------
View form
------------------------------------------------------------------------- */

			if ($g_datasub_check) { direct_output_related_manager ("discuss_topic_edit_{$g_tid}_form","post_module_service_action"); }
			else { direct_output_related_manager ("discuss_topic_edit_{$g_board_array['ddbdatalinker_id']}_{$g_tid}_form","post_module_service_action"); }

			$direct_classes['output']->oset ("default","form");
			$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
			$direct_classes['output']->page_show ($direct_cachedata['output_formtitle']);
		}
	}
	else { $direct_classes['error_functions']->error_page ("login","core_access_denied","sWG/#echo(__FILEPATH__)# _a={$direct_settings['a']}_ (#echo(__LINE__)#)"); }
	//j// EOA
	}

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// ($direct_settings['a'] == "new")||($direct_settings['a'] == "new-save")
case "new":
case "new-save":
{
	$g_mode_save = (($direct_settings['a'] == "new-save") ? true : false);
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a={$direct_settings['a']}_ (#echo(__LINE__)#)"); }

	$g_did = (isset ($direct_settings['dsd']['ddid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['ddid'])) : "");
	$g_connector = (isset ($direct_settings['dsd']['connector']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['connector'])) : "");
	$g_source = (isset ($direct_settings['dsd']['source']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['source'])) : "");
	$g_target = (isset ($direct_settings['dsd']['target']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['target'])) : "");

	$g_connector_url = ($g_connector ? base64_decode ($g_connector) : "m=discuss&a=[a]&dsd=[oid]");
	$g_source_url = ($g_source ? base64_decode ($g_source) : "m=discuss&a=topics&dsd=[oid]");

	if ($g_target) { $g_target_url = base64_decode ($g_target); }
	else
	{
		$g_target = $g_source;
		$g_target_url = ($g_source ? $g_source_url : "");
	}

	$g_back_link = (((!$g_source)&&($g_connector_url)) ? preg_replace (array ("#\[a\]#","#\[oid\]#","#\[(.*?)\]#"),(array ("topics","ddid+{$g_did}++","")),$g_connector_url) : str_replace ("[oid]","ddid+{$g_did}++",$g_source_url));

	if ($g_mode_save)
	{
		$direct_cachedata['page_this'] = "";
		$direct_cachedata['page_backlink'] = "m=discuss&s=topic&a=new&dsd=ddid+{$g_did}++connector+".(urlencode ($g_connector))."++source+".(urlencode ($g_source))."++target+".(urlencode ($g_target));
		$direct_cachedata['page_homelink'] = $g_back_link;
	}
	else
	{
		$direct_cachedata['page_this'] = "m=discuss&s=topic&a=new&dsd=ddid+{$g_did}++connector+".(urlencode ($g_connector))."++source+".(urlencode ($g_source))."++target+".(urlencode ($g_target));
		$direct_cachedata['page_backlink'] = $g_back_link;
		$direct_cachedata['page_homelink'] = $g_back_link;
	}

	if ($direct_classes['kernel']->service_init_default ())
	{
	//j// BOA
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_board.php");
	direct_local_integration ("discuss");

	$g_datasub_check = false;
	$g_board_object = new direct_discuss_board ();
	$g_rights_check = false;

	$g_board_array = ($g_board_object ? $g_board_object->get ($g_did) : NULL);

	if (is_array ($g_board_array)) { $g_rights_check = $g_board_object->is_writable (); }
	else
	{
		$g_datasub_check = $g_board_object->is_sub_allowed ();
		$g_rights_check = $g_datasub_check;
	}

	if ((!$g_datasub_check)&&(!is_array ($g_board_array))) { $direct_classes['error_functions']->error_page ("standard","discuss_did_invalid","sWG/#echo(__FILEPATH__)# _a={$direct_settings['a']}_ (#echo(__LINE__)#)"); }
	elseif ($g_rights_check)
	{
		if ($g_mode_save) { direct_output_related_manager ("discuss_topic_new_{$g_did}_form_save","pre_module_service_action"); }
		else
		{
			direct_output_related_manager ("discuss_topic_new_{$g_did}_form","pre_module_service_action");
			$direct_classes['kernel']->service_https ($direct_settings['discuss_https_topic'],$direct_cachedata['page_this']);
		}

		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/swg_formbuilder.php");
		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/functions/swg_credits_manager.php");
		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/functions/swg_tmp_storager.php");

		if ($g_mode_save)
		{
			$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_topic.php");
			$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_post.php");
			$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/swg_formtags.php");
		}

		if (($direct_settings['discuss_datacenter_symbols'])&&(isset ($direct_settings['discuss_datacenter_symbols_did'])))
		{
			$direct_classes['basic_functions']->settings_get ($direct_settings['path_data']."/settings/swg_datacenter.php",true);
			$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_datacenter.php");
		}

		direct_class_init ("formbuilder");
		if ($g_mode_save) { direct_class_init ("formtags"); }
		direct_class_init ("output");
		$direct_classes['output']->options_insert (2,"servicemenu",$direct_cachedata['page_backlink'],(direct_local_get ("core_back")),$direct_settings['serviceicon_default_back'],"url0");

		if (!$g_datasub_check) { direct_credits_payment_get_specials ("discuss_topic_new",$g_board_array['ddbdatalinker_id_object'],$direct_settings['discuss_topic_new_credits_onetime'],$direct_settings['discuss_topic_new_credits_periodically']); }
		$direct_cachedata['output_credits_information'] = direct_credits_payment_info ($direct_settings['discuss_topic_new_credits_onetime'],$direct_settings['discuss_topic_new_credits_periodically']);
		$direct_cachedata['output_credits_payment_data'] = direct_credits_payment_check (true,$direct_settings['discuss_topic_new_credits_onetime']);

		if ($g_datasub_check) { $g_rights_check = (($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 3) ? true : false); }
		else { $g_rights_check = $g_board_object->is_moderator (); }

		if ($g_mode_save)
		{
/* -------------------------------------------------------------------------
We should have input in save mode
------------------------------------------------------------------------- */

			$direct_cachedata['i_dtitle'] = (isset ($GLOBALS['i_dtitle']) ? ($direct_classes['basic_functions']->inputfilter_basic ($GLOBALS['i_dtitle'])) : "");
			$direct_cachedata['i_ddesc'] = (isset ($GLOBALS['i_ddesc']) ? ($direct_classes['basic_functions']->inputfilter_basic ($GLOBALS['i_ddesc'])) : "");
			if (($direct_settings['discuss_datacenter_symbols'])&&(isset ($direct_settings['discuss_datacenter_symbols_did']))) { $direct_cachedata['i_dsymbol'] = (isset ($GLOBALS['i_dsymbol']) ? (urlencode ($GLOBALS['i_dsymbol'])) : ""); }
			$direct_cachedata['i_dpost'] = (isset ($GLOBALS['i_dpost']) ? ($direct_classes['basic_functions']->inputfilter_basic ($GLOBALS['i_dpost'])) : "");

			$direct_cachedata['i_dsmilies'] = (isset ($GLOBALS['i_dsmilies']) ? (str_replace ("'","",$GLOBALS['i_dsmilies'])) : "");
			$direct_cachedata['i_dsmilies'] = str_replace ("<value value='$direct_cachedata[i_dsmilies]' />","<value value='$direct_cachedata[i_dsmilies]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

			$direct_cachedata['i_dpreview'] = (isset ($GLOBALS['i_dpreview']) ? (str_replace ("'","",$GLOBALS['i_dpreview'])) : "");
			$direct_cachedata['i_dpreview'] = str_replace ("<value value='$direct_cachedata[i_dpreview]' />","<value value='$direct_cachedata[i_dpreview]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

			if ($g_rights_check)
			{
				$direct_cachedata['i_dsubs_allowed'] = (isset ($GLOBALS['i_dsubs_allowed']) ? (str_replace ("'","",$GLOBALS['i_dsubs_allowed'])) : "");
				$direct_cachedata['i_dsubs_allowed'] = str_replace ("<value value='$direct_cachedata[i_dsubs_allowed]' />","<value value='$direct_cachedata[i_dsubs_allowed]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

				$direct_cachedata['i_dsubs_hidden'] = (isset ($GLOBALS['i_dsubs_hidden']) ? (str_replace ("'","",$GLOBALS['i_dsubs_hidden'])) : "");
				$direct_cachedata['i_dsubs_hidden'] = str_replace ("<value value='$direct_cachedata[i_dsubs_hidden]' />","<value value='$direct_cachedata[i_dsubs_hidden]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

				$direct_cachedata['i_dsubs_type'] = (isset ($GLOBALS['i_dsubs_type']) ? (str_replace ("'","",$GLOBALS['i_dsubs_type'])) : 0);

				$direct_cachedata['i_dsticky'] = (isset ($GLOBALS['i_dsticky']) ? (str_replace ("'","",$GLOBALS['i_dsticky'])) : "");
				$direct_cachedata['i_dsticky'] = str_replace ("<value value='$direct_cachedata[i_dsticky]' />","<value value='$direct_cachedata[i_dsticky]' /><selected value='1' />","<evars><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes></evars>");

				$direct_cachedata['i_dlocked'] = (isset ($GLOBALS['i_dlocked']) ? (str_replace ("'","",$GLOBALS['i_dlocked'])) : "");
				$direct_cachedata['i_dlocked'] = str_replace ("<value value='$direct_cachedata[i_dlocked]' />","<value value='$direct_cachedata[i_dlocked]' /><selected value='1' />","<evars><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes></evars>");

				$direct_cachedata['i_dgright'] = (isset ($GLOBALS['i_dgright']) ? (str_replace ("'","",$GLOBALS['i_dgright'])) : "");
				$direct_cachedata['i_dpright'] = (isset ($GLOBALS['i_dpright']) ? (str_replace ("'","",$GLOBALS['i_dpright'])) : "");
			}
			elseif ((!$g_datasub_check)&&($g_board_object->is_sub_allowed ()))
			{
				$direct_cachedata['i_dsubs_allowed'] = (isset ($GLOBALS['i_dsubs_allowed']) ? (str_replace ("'","",$GLOBALS['i_dsubs_allowed'])) : "");
				$direct_cachedata['i_dsubs_allowed'] = str_replace ("<value value='$direct_cachedata[i_dsubs_allowed]' />","<value value='$direct_cachedata[i_dsubs_allowed]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

				$direct_cachedata['i_dsubs_hidden'] = (isset ($GLOBALS['i_dsubs_hidden']) ? (str_replace ("'","",$GLOBALS['i_dsubs_hidden'])) : "");
				$direct_cachedata['i_dsubs_hidden'] = str_replace ("<value value='$direct_cachedata[i_dsubs_hidden]' />","<value value='$direct_cachedata[i_dsubs_hidden]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

				$direct_cachedata['i_dsubs_type'] = (isset ($GLOBALS['i_dsubs_type']) ? (str_replace ("'","",$GLOBALS['i_dsubs_type'])) : 0);
			}
		}
		else
		{
			$direct_cachedata['i_dtitle'] = "";
			$direct_cachedata['i_ddesc'] = "";

			if (($direct_settings['discuss_datacenter_symbols'])&&(isset ($direct_settings['discuss_datacenter_symbols_did'])))
			{
				$direct_cachedata['i_dsymbol'] = uniqid ("");

$g_task_array = array (
"core_sid" => "d4d66a02daefdb2f70ff2507a78fd5ec",
// md5 ("datacenter")
"datacenter_marker_type" => "files-only",
"datacenter_selection_did" => $direct_settings['contentor_datacenter_symbols_did'],
"datacenter_selection_done" => 0,
"datacenter_selection_path" => $direct_settings['contentor_datacenter_path_symbols'],
"datacenter_selection_quantity" => 1,
"uuid" => $direct_settings['uuid']
);

				if ($direct_settings['discuss_datacenter_symbols_preselect']) { $g_task_array['datacenter_objects_marked'] = array ($direct_settings['discuss_datacenter_symbols_preselect'] => $direct_settings['discuss_datacenter_symbols_preselect']); }

				direct_tmp_storage_write ($g_task_array,$direct_cachedata['i_dsymbol'],"d4d66a02daefdb2f70ff2507a78fd5ec","task_cache","evars",$direct_cachedata['core_time'],($direct_cachedata['core_time'] + 3600));
				// md5 ("datacenter")
			}

			$direct_cachedata['i_dpost'] = "";
			$direct_cachedata['i_dsmilies'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>";
			$direct_cachedata['i_dpreview'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>";

			if ($g_rights_check)
			{
				if ((!$g_datasub_check)&&($g_board_array['ddbdatalinker_datasubs_new'])) { $direct_cachedata['i_dsubs_allowed'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
				else { $direct_cachedata['i_dsubs_allowed'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

				if (($g_datasub_check)||($g_board_array['ddbdatalinker_datasubs_hide'])) { $direct_cachedata['i_dsubs_hidden'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
				else { $direct_cachedata['i_dsubs_hidden'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

				$direct_cachedata['i_dsubs_type'] = (((!$g_datasub_check)&&(isset ($g_board_array['ddbdatalinker_datasubs_type']))) ? str_replace ("'","",$g_board_array['ddbdatalinker_datasubs_type']) : 0);
				$direct_cachedata['i_dsticky'] = "<evars><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes></evars>";
				$direct_cachedata['i_dlocked'] = "<evars><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes></evars>";

				$direct_cachedata['i_dgright'] = ((($g_datasub_check)||($g_board_array['ddbdiscuss_boards_public'])) ? "-" : "r");
				$direct_cachedata['i_dpright'] = ((($g_datasub_check)||($g_board_array['ddbdiscuss_boards_public'])) ? "r" : "-");
			}
			elseif ((!$g_datasub_check)&&($g_board_object->is_sub_allowed ()))
			{
				if ((!$g_datasub_check)&&($g_board_array['ddbdatalinker_datasubs_new'])) { $direct_cachedata['i_dsubs_allowed'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
				else { $direct_cachedata['i_dsubs_allowed'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

				if (($g_datasub_check)||($g_board_array['ddbdatalinker_datasubs_hide'])) { $direct_cachedata['i_dsubs_hidden'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
				else { $direct_cachedata['i_dsubs_hidden'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

				$direct_cachedata['i_dsubs_type'] = (((!$g_datasub_check)&&(isset ($g_board_array['ddbdatalinker_datasubs_type']))) ? str_replace ("'","",$g_board_array['ddbdatalinker_datasubs_type']) : 0);
			}
		}

		if (isset ($direct_cachedata['i_dsubs_type']))
		{
$direct_cachedata['i_dsubs_type'] = str_replace ("<value value='$direct_cachedata[i_dsubs_type]' />","<value value='$direct_cachedata[i_dsubs_type]' /><selected value='1' />","<evars>
<default><value value='0' /><text><![CDATA[".(direct_local_get ("core_datasub_title_default"))."]]></text></default><attachments><value value='1' /><text><![CDATA[".(direct_local_get ("core_datasub_title_attachments"))."]]></text></attachments><downloads><value value='2' /><text><![CDATA[".(direct_local_get ("core_datasub_title_downloads"))."]]></text></downloads><links><value value='3' /><text><![CDATA[".(direct_local_get ("core_datasub_title_links"))."]]></text></links>
</evars>");
		}

		if ($g_rights_check)
		{
			if (isset ($direct_cachedata['i_dgright']))
			{
$direct_cachedata['i_dgright'] = str_replace ("<value value='$direct_cachedata[i_dgright]' />","<value value='$direct_cachedata[i_dgright]' /><selected value='1' />","<evars>
<norights><value value='-' /><text><![CDATA[".(direct_local_get ("discuss_post_dright_0"))."]]></text></norights><read><value value='r' /><text><![CDATA[".(direct_local_get ("discuss_post_dright_r"))."]]></text></read><write><value value='w' /><text><![CDATA[".(direct_local_get ("discuss_post_dright_w"))."]]></text></write>
</evars>");
			}

			if (isset ($direct_cachedata['i_dpright']))
			{
$direct_cachedata['i_dpright'] = str_replace ("<value value='$direct_cachedata[i_dpright]' />","<value value='$direct_cachedata[i_dpright]' /><selected value='1' />","<evars>
<norights><value value='-' /><text><![CDATA[".(direct_local_get ("discuss_post_dright_0"))."]]></text></norights><read><value value='r' /><text><![CDATA[".(direct_local_get ("discuss_post_dright_r"))."]]></text></read><write><value value='w' /><text><![CDATA[".(direct_local_get ("discuss_post_dright_w"))."]]></text></write>
</evars>");
			}
		}

/* -------------------------------------------------------------------------
Build the form
------------------------------------------------------------------------- */

		if (($direct_settings['discuss_account_status_ex'])&&(!$direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']))) { $direct_classes['formbuilder']->entry_add_embed ("dstatus_ex",NULL,true,"m=dataport&s=swgap;account;status_ex&dsd=",true,"s"); }
		$direct_classes['formbuilder']->entry_add_text ("dtitle",(direct_local_get ("discuss_topic")),true,"m",$direct_settings['discuss_topic_min'],$direct_settings['discuss_topic_max']);

		if ($direct_settings['formtags_overview_document_url']) { $direct_classes['formbuilder']->entry_add_jfield_text ("ddesc",(direct_local_get ("discuss_topic_desc")),false,"l",$direct_settings['discuss_topic_desc_min'],$direct_settings['discuss_topic_desc_max'],(direct_local_get ("formtags_overview_document")),(direct_linker ("url0",$direct_settings['formtags_overview_document_url']))); }
		else { $direct_classes['formbuilder']->entry_add_jfield_text ("ddesc",(direct_local_get ("discuss_topic_desc")),false,"l",$direct_settings['discuss_topic_desc_min'],$direct_settings['discuss_topic_desc_max']); }

		if (isset ($direct_cachedata['i_dsymbol']))
		{
			$direct_classes['formbuilder']->entry_add ("spacer");
			$direct_classes['formbuilder']->entry_add_embed ("dsymbol",(direct_local_get ("discuss_symbol")),false,"m=dataport&s=swgap;datacenter;selector_icons&dsd=",false,"s");
		}

		$direct_classes['formbuilder']->entry_add ("spacer");

		if ($direct_settings['formtags_overview_document_url']) { $direct_classes['formbuilder']->entry_add_jfield_textarea ("dpost",(direct_local_get ("discuss_post")),true,"l",$direct_settings['discuss_post_min'],$direct_settings['swg_data_limit'],(direct_local_get ("formtags_overview_document")),(direct_linker ("url0",$direct_settings['formtags_overview_document_url']))); }
		else { $direct_classes['formbuilder']->entry_add_jfield_textarea ("dpost",(direct_local_get ("discuss_post")),true,"l",$direct_settings['discuss_post_min'],$direct_settings['swg_data_limit']); }

		$direct_classes['formbuilder']->entry_add ("spacer");
		$direct_classes['formbuilder']->entry_add_select ("dsmilies",(direct_local_get ("discuss_smilies_parse")),true,"s");
		$direct_classes['formbuilder']->entry_add_radio ("dpreview",(direct_local_get ("core_preview")));

		if ($g_rights_check)
		{
			$direct_classes['formbuilder']->entry_add ("spacer");
			$direct_classes['formbuilder']->entry_add_select ("dsubs_allowed",(direct_local_get ("core_datasub_allowed")),true,"s");
			$direct_classes['formbuilder']->entry_add_select ("dsubs_hidden",(direct_local_get ("core_datasub_hide")),true,"s");
			$direct_classes['formbuilder']->entry_add_radio ("dsubs_type",(direct_local_get ("core_datasub_type")),true);
			$direct_classes['formbuilder']->entry_add ("spacer");
			$direct_classes['formbuilder']->entry_add_select ("dsticky",(direct_local_get ("discuss_topic_stick")),false,"s");
			$direct_classes['formbuilder']->entry_add_select ("dlocked",(direct_local_get ("discuss_topic_lock")),false,"s");
			$direct_classes['formbuilder']->entry_add ("spacer");
			$direct_classes['formbuilder']->entry_add_select ("dgright",(direct_local_get ("discuss_post_gright")),false,"s");
			$direct_classes['formbuilder']->entry_add_select ("dpright",(direct_local_get ("discuss_post_pright")),false,"s");
		}
		elseif (isset ($direct_cachedata['i_dsubs_allowed']))
		{
			$direct_classes['formbuilder']->entry_add ("spacer");
			$direct_classes['formbuilder']->entry_add_select ("dsubs_allowed",(direct_local_get ("core_datasub_allowed")),true,"s");
			$direct_classes['formbuilder']->entry_add_select ("dsubs_hidden",(direct_local_get ("core_datasub_hide")),true,"s");
			$direct_classes['formbuilder']->entry_add_radio ("dsubs_type",(direct_local_get ("core_datasub_type")),true);
		}

		$direct_cachedata['output_formbutton'] = direct_local_get ("core_save");
		$direct_cachedata['output_formelements'] = $direct_classes['formbuilder']->form_get ($g_mode_save);
		$direct_cachedata['output_formtarget'] = "m=discuss&s=topic&a=new-save&dsd=ddid+{$g_did}++connector+".(urlencode ($g_connector))."++source+".(urlencode ($g_source))."++target+".(urlencode ($g_target));
		$direct_cachedata['output_formtitle'] = direct_local_get ("discuss_topic_new");

		if (($g_mode_save)&&($direct_classes['formbuilder']->check_result))
		{
/* -------------------------------------------------------------------------
Save data edited
------------------------------------------------------------------------- */

			$direct_cachedata['i_dsubs_allowed'] = (((isset ($direct_cachedata['i_dsubs_allowed']))&&($direct_cachedata['i_dsubs_allowed'])) ? 1 : 0);
			$direct_cachedata['i_dsticky'] = (((isset ($direct_cachedata['i_dsticky']))&&($direct_cachedata['i_dsticky'])) ? 1 : 0);
			$direct_cachedata['i_dlocked'] = (((isset ($direct_cachedata['i_dlocked']))&&($direct_cachedata['i_dlocked'])) ? 1 : 0);
			if (!isset ($direct_cachedata['i_dgright'])) { $direct_cachedata['i_dgright'] = ($g_datasub_check ? "-" : "r"); }
			if (!isset ($direct_cachedata['i_dpright'])) { $direct_cachedata['i_dpright'] = ((($g_datasub_check)||($g_board_array['ddbdiscuss_boards_public'])) ? "r" : "-"); }

			if (direct_credits_payment_check (false,$direct_settings['discuss_topic_new_credits_onetime']))
			{
				$direct_cachedata['i_ddesc'] = $direct_classes['formtags']->encode ($direct_cachedata['i_ddesc']);
				$direct_cachedata['i_dpost'] = $direct_classes['formtags']->encode ($direct_cachedata['i_dpost']);
				$g_post_preview = $direct_classes['formtags']->cleanup ($direct_cachedata['i_dpost'],true);
				if ($direct_cachedata['i_dsmilies']) { $direct_cachedata['i_dpost'] = direct_output_smiley_encode ($direct_cachedata['i_dpost']); }

				if ($direct_cachedata['i_dpreview'])
				{
					$direct_cachedata['output_title'] = direct_html_encode_special ($direct_cachedata['i_dtitle']);
					$direct_cachedata['output_desc'] = $direct_classes['formtags']->decode ($direct_cachedata['i_ddesc']);
					$direct_cachedata['output_post'] = $direct_classes['formtags']->decode ($direct_cachedata['i_dpost']);
					$direct_cachedata['output_username'] = $direct_settings['user']['name_html'];

					$direct_cachedata['output_preview_function_file'] = "swgi_discuss";
					$direct_cachedata['output_preview_function'] = "oset_discuss_topic_preview";

					direct_output_related_manager ("discuss_topic_new_{$g_did}_form_save","post_module_service_action");
					$direct_classes['output']->oset ("default","form_preview");
					$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
					$direct_classes['output']->page_show ($direct_cachedata['output_formtitle']);
					$g_continue_check = false;
				}
				elseif ($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type'])) { $g_continue_check = true; }
				else
				{
					$g_uuid_storage_array = direct_tmp_storage_get ("evars",$direct_settings['uuid'],"","task_cache");

					if (($g_uuid_storage_array)&&(isset ($g_uuid_storage_array['account_status_ex_type'],$g_uuid_storage_array['account_status_ex_verified'])))
					{
						if ($g_uuid_storage_array['account_status_ex_verified']) { $g_continue_check = true; }
						else
						{
							direct_local_integration ("account");
							$direct_classes['error_functions']->error_page ("standard","account_status_ex_verify_first","sWG/#echo(__FILEPATH__)# _a=new-save_ (#echo(__LINE__)#)");
							$g_continue_check = false;
						}
					}
					else
					{
						$direct_classes['error_functions']->error_page ("login","core_access_denied","sWG/#echo(__FILEPATH__)# _a=new-save_ (#echo(__LINE__)#)");
						$g_continue_check = false;
					}
				}

				if ($g_continue_check)
				{
					$direct_classes['db']->v_transaction_begin ();

					$g_post_preview = str_replace ("\n"," ",$g_post_preview);

					if (strlen ($g_post_preview) > $direct_settings['discuss_preview_length'])
					{
						$g_post_preview = mb_substr ($g_post_preview,0,($direct_settings['discuss_preview_length'] - 4));
						$g_post_preview .= " ...";
					}

					if (isset ($direct_cachedata['i_dsymbol']))
					{
						$g_task_array = direct_tmp_storage_get ("evars",$direct_cachedata['i_dsymbol'],"d4d66a02daefdb2f70ff2507a78fd5ec","selector_cache");
						// md5 ("datacenter")
						$direct_cachedata['i_dsymbol'] = "";

						if ((is_array ($g_task_array['datacenter_objects_marked']))&&(!empty ($g_task_array['datacenter_objects_marked'])))
						{
							$g_symbol_marked_id = array_shift ($g_task_array['datacenter_objects_marked']);
							$g_symbol_marked_object = ($g_symbol_marked_id ? new direct_datacenter () : NULL);
							if (($g_symbol_marked_object)&&($g_symbol_marked_object->get ($g_symbol_marked_id))) { $direct_cachedata['i_dsymbol'] = $g_symbol_marked_object->get_plocation (); }
						}
					}
					else { $direct_cachedata['i_dsymbol'] = ""; }

					$g_post_id = uniqid ("");
					$g_topic_id = uniqid ("");

$g_insert_array = array (
"ddbdatalinker_id" => $g_post_id,
"ddbdatalinker_id_parent" => "",
"ddbdatalinker_id_main" => $g_topic_id,
"ddbdatalinker_sid" => "cb41ecf6e90a594dcea60b6140251d62",
// md5 ("discuss")
"ddbdatalinker_type" => 6,
"ddbdatalinker_position" => 0,
"ddbdatalinker_subs" => 0,
"ddbdatalinker_objects" => 0,
"ddbdatalinker_sorting_date" => $direct_cachedata['core_time'],
"ddbdatalinker_symbol" => $direct_cachedata['i_dsymbol'],
"ddbdatalinker_title" => $direct_cachedata['i_dtitle'],
"ddbdiscuss_posts_user_id" => $direct_settings['user']['id'],
"ddbdiscuss_posts_user_ip" => $direct_settings['user_ip'],
"ddbdiscuss_posts_locked" => 0,
"ddbdata_data" => $direct_cachedata['i_dpost'],
"ddbdata_mode_user" => "w",
"ddbdata_mode_group" => $direct_cachedata['i_dgright'],
"ddbdata_mode_all" => $direct_cachedata['i_dpright']
);

					if ($direct_cachedata['i_dsubs_allowed'])
					{
						$g_insert_array['ddbdatalinker_datasubs_type'] = $direct_cachedata['i_dsubs_type'];
						$g_insert_array['ddbdatalinker_datasubs_hide'] = $direct_cachedata['i_dsubs_hidden'];
						$g_insert_array['ddbdatalinker_datasubs_new'] = 1;
					}
					elseif (!$g_datasub_check)
					{
						$g_insert_array['ddbdatalinker_datasubs_type'] = $g_board_array['ddbdatalinker_datasubs_type'];
						$g_insert_array['ddbdatalinker_datasubs_hide'] = $g_board_array['ddbdatalinker_datasubs_hide'];
						$g_insert_array['ddbdatalinker_datasubs_new'] = $g_board_array['ddbdatalinker_datasubs_new'];
					}

					$g_post_object = new direct_discuss_post ();
					$g_continue_check = ($g_post_object ? $g_post_object->set_insert ($g_insert_array) : false);

					if ($g_continue_check)
					{
$g_insert_array = array (
"ddbdatalinker_id" => $g_topic_id,
"ddbdatalinker_sid" => "cb41ecf6e90a594dcea60b6140251d62",
// md5 ("discuss")
"ddbdatalinker_type" => 5,
"ddbdatalinker_position" => $direct_cachedata['i_dsticky'],
"ddbdatalinker_subs" => 0,
"ddbdatalinker_objects" => 1,
"ddbdatalinker_sorting_date" => $direct_cachedata['core_time'],
"ddbdatalinker_symbol" => $direct_cachedata['i_dsymbol'],
"ddbdatalinker_title" => $direct_cachedata['i_dtitle'],
"ddbdatalinker_datasubs_type" => $g_insert_array['ddbdatalinker_datasubs_type'],
"ddbdatalinker_datasubs_hide" => $g_insert_array['ddbdatalinker_datasubs_hide'],
"ddbdatalinker_datasubs_new" => $g_insert_array['ddbdatalinker_datasubs_hide'],
"ddbdiscuss_topics_desc" => $direct_cachedata['i_ddesc'],
"ddbdiscuss_topics_time" => $direct_cachedata['core_time'],
"ddbdiscuss_topics_last_id" => $direct_settings['user']['id'],
"ddbdiscuss_topics_last_ip" => $direct_settings['user_ip'],
"ddbdiscuss_topics_last_preview" => $g_post_preview,
"ddbdiscuss_topics_locked" => $direct_cachedata['i_dlocked']
);

						if ($g_datasub_check) { $g_insert_array['ddbdatalinker_id_parent'] = $g_did; }
						else
						{
							$g_insert_array['ddbdatalinker_id_parent'] = $g_board_array['ddbdatalinker_id_object'];
							$g_insert_array['ddbdatalinker_id_main'] = $g_board_array['ddbdatalinker_id_main'];
							$g_insert_array['ddbdatalinker_views'] = 0;
							$g_insert_array['ddbdatalinker_views_count'] = $g_board_array['ddbdatalinker_views_count'];
						}

						$g_topic_object = new direct_discuss_topic ();
						$g_continue_check = ($g_topic_object ? $g_topic_object->set_insert ($g_insert_array) : false);
					}

					if ((!$g_datasub_check)&&($g_continue_check))
					{
						$g_board_array['ddbdatalinker_objects'] += 1;
						$g_board_array['ddbdatalinker_sorting_date'] = $direct_cachedata['core_time'];
						$g_board_array['ddbdiscuss_boards_posts'] += 1;
						$g_board_array['ddbdiscuss_boards_last_tid'] = $g_topic_id;
						$g_board_array['ddbdiscuss_boards_last_id'] = $direct_settings['user']['id'];
						$g_board_array['ddbdiscuss_boards_last_ip'] = $direct_settings['user_ip'];
						$g_board_array['ddbdiscuss_boards_last_preview'] = $g_post_preview;
						$g_continue_check = $g_board_object->set_update ($g_board_array);
					}

					if (($g_continue_check)&&($direct_classes['db']->v_transaction_commit ()))
					{
						direct_credits_payment_exec ("discuss","topic_new",$g_topic_id,$direct_settings['user']['id'],$direct_settings['discuss_topic_new_credits_onetime'],$direct_settings['discuss_topic_new_credits_periodically']);

						$direct_cachedata['output_job'] = direct_local_get ("discuss_topic_new");
						$direct_cachedata['output_job_desc'] = direct_local_get ("discuss_done_topic_new");

						if ($g_target_url)
						{
							$direct_cachedata['output_jsjump'] = 2000;
							$g_target_link = str_replace ("[oid]","dtid_d+{$g_topic_id}++",$g_target_url);
						}
						elseif ($g_connector_url)
						{
							$direct_cachedata['output_jsjump'] = 2000;
							$g_target_link = str_replace (array ("[a]","[oid]"),(array ("posts","dtid+{$g_topic_id}++")),$g_connector_url);
						}
						else { $direct_cachedata['output_jsjump'] = 0; }

						if ($direct_cachedata['output_jsjump'])
						{
							$direct_cachedata['output_pagetarget'] = str_replace ('"',"",(direct_linker ("url0",$g_target_link)));
							$direct_cachedata['output_scripttarget'] = str_replace ('"',"",(direct_linker ("url0",$g_target_link,false)));
						}

						direct_output_related_manager ("discuss_topic_new_{$g_did}_form_save","post_module_service_action");
						$direct_classes['output']->oset ("default","done");
						$direct_classes['output']->options_flush (true);
						$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
						$direct_classes['output']->page_show ($direct_cachedata['output_job']);
					}
					else
					{
						$direct_classes['db']->v_transaction_rollback ();
						$direct_classes['error_functions']->error_page ("fatal","core_database_error","sWG/#echo(__FILEPATH__)# _a=edit-save_ (#echo(__LINE__)#)");
					}
				}
			}
			else { $direct_classes['error_functions']->error_page ("standard","core_credits_insufficient","SERVICE ERROR:<br />".(-1 * $direct_settings['discuss_topic_new_credits_onetime'])." Credits are required but not available. This error has been reported by the sWG Credits Manager.<br />sWG/#echo(__FILEPATH__)# _a=edit-save_ (#echo(__LINE__)#)"); }
		}
		else
		{
/* -------------------------------------------------------------------------
View form
------------------------------------------------------------------------- */

			direct_output_related_manager ("discuss_topic_new_{$g_did}_form","post_module_service_action");
			$direct_classes['output']->oset ("default","form");
			$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
			$direct_classes['output']->page_show ($direct_cachedata['output_formtitle']);
		}
	}
	else { $direct_classes['error_functions']->error_page ("login","core_access_denied","sWG/#echo(__FILEPATH__)# _a={$direct_settings['a']}_ (#echo(__LINE__)#)"); }
	//j// EOA
	}

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// ($direct_settings['a'] == "state")
case "state":
{
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a=state_ (#echo(__LINE__)#)"); }

	$g_change_type = (isset ($direct_settings['dsd']['dchange']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['dchange'])) : "");
	$g_did = (isset ($direct_settings['dsd']['ddid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['ddid'])) : "");
	$g_tid = (isset ($direct_settings['dsd']['dtid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['dtid'])) : "");
	$g_connector = (isset ($direct_settings['dsd']['connector']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['connector'])) : "");
	$g_source = (isset ($direct_settings['dsd']['source']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['source'])) : "");
	$g_target = (isset ($direct_settings['dsd']['target']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['target'])) : "");

	$g_did_dsd = (strlen ($g_did) ? "ddid+{$g_did}++" : "");
	$g_connector_url = ($g_connector ? base64_decode ($g_connector) : "m=discuss&a=[a]&dsd={$g_did_dsd}[oid]");
	$g_source_url = ($g_source ? base64_decode ($g_source) : "m=discuss&a=posts&dsd={$g_did_dsd}[oid]");

	if ($g_target) { $g_target_url = base64_decode ($g_target); }
	else
	{
		$g_target = $g_source;
		$g_target_url = ($g_source ? $g_source_url : "");
	}

	$g_back_link = (((!$g_source)&&($g_connector_url)) ? preg_replace (array ("#\[a\]#","#\[oid\]#","#\[(.*?)\]#"),(array ("posts","dtid+{$g_tid}++","")),$g_connector_url) : str_replace ("[oid]","dtid+{$g_tid}++",$g_source_url));

	$direct_cachedata['page_this'] = "m=discuss&s=post&a=state&dsd={$g_did_dsd}dtid+{$g_tid}++dchange+{$g_change_type}++connector+".(urlencode ($g_connector))."++source+".(urlencode ($g_source))."++target+".(urlencode ($g_target));
	$direct_cachedata['page_backlink'] = $g_back_link;
	$direct_cachedata['page_homelink'] = $g_back_link;

	if ($direct_classes['kernel']->service_init_default ())
	{
	//j// BOA
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_board.php");
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_topic.php");
	direct_local_integration ("discuss");

	$g_datasub_check = false;
	$g_rights_check = false;
	$g_topic_object = new direct_discuss_topic ();

	$g_topic_array = ($g_topic_object ? $g_topic_object->get ($g_tid) : NULL);

	if (is_array ($g_topic_array))
	{
		if ($g_topic_array['ddbdatalinker_id_main'])
		{
			$g_board_object = new direct_discuss_board ();
			if ($g_board_object->get ($g_topic_array['ddbdatalinker_id_main'])) { $g_rights_check = $g_board_object->is_moderator (); }
		}
		else
		{
			$g_datasub_check = true;
			if ($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 3) { $g_rights_check = true; }
		}
	}

	if ($g_rights_check)
	{
		if ($g_datasub_check) { direct_output_related_manager ("discuss_topic_state_".$g_tid,"pre_module_service_action"); }
		else { direct_output_related_manager ("discuss_topic_state_{$g_topic_array['ddbdatalinker_id_main']}_".$g_tid,"pre_module_service_action"); }

		direct_class_init ("output");
		$direct_classes['output']->options_insert (2,"servicemenu",$direct_cachedata['page_backlink'],(direct_local_get ("core_back")),$direct_settings['serviceicon_default_back'],"url0");

		switch ($g_change_type)
		{
		case "lock":
		{
			$direct_cachedata['output_job'] = direct_local_get ("discuss_post_lock");
			$g_continue_check = $g_topic_object->define_lock (true,true);

			break 1;
		}
		case "unlock":
		{
			$direct_cachedata['output_job'] = direct_local_get ("discuss_post_unlock");
			$g_continue_check = ($g_topic_object->define_lock (false,true) ? false : true);

			break 1;
		}
		}

		if ($g_continue_check)
		{
			$direct_cachedata['output_job_desc'] = direct_local_get ("discuss_done_post_change_state");

			if ($g_target_url)
			{
				$direct_cachedata['output_jsjump'] = 2000;
				$g_target_link = str_replace ("[oid]","cdid_d+{$g_did}++",$g_target_url);
			}
			elseif ($g_connector_url)
			{
				$direct_cachedata['output_jsjump'] = 2000;
				$g_target_link = str_replace (array ("[a]","[oid]"),(array ("view","cdid_d+{$g_did}++")),$g_connector_url);
			}
			else { $direct_cachedata['output_jsjump'] = 0; }

			if ($direct_cachedata['output_jsjump'])
			{
				$direct_cachedata['output_pagetarget'] = str_replace ('"',"",(direct_linker ("url0",$g_target_link)));
				$direct_cachedata['output_scripttarget'] = str_replace ('"',"",(direct_linker ("url0",$g_target_link,false)));
			}

			if ($g_datasub_check) { direct_output_related_manager ("discuss_post_state_".$g_pid,"post_module_service_action"); }
			else { direct_output_related_manager ("discuss_post_state_{$g_topic_array['ddbdatalinker_id_main']}_{$g_topic_array['ddbdatalinker_id']}_".$g_pid,"post_module_service_action"); }

			$direct_classes['output']->oset ("default","done");
			$direct_classes['output']->options_flush (true);
			$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
			$direct_classes['output']->page_show ($direct_cachedata['output_job']);
		}
		else { $direct_classes['error_functions']->error_page ("fatal","core_database_error","sWG/#echo(__FILEPATH__)# _a=state_ (#echo(__LINE__)#)"); }
	}
	else { $direct_classes['error_functions']->error_page ("login","core_access_denied","sWG/#echo(__FILEPATH__)# _a=state_ (#echo(__LINE__)#)"); }
	//j// EOA
	}

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// $direct_settings['a'] == "vote"
case "vote":
{
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a=vote_ (#echo(__LINE__)#)"); }

	$g_did = (isset ($direct_settings['dsd']['ddid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['ddid'])) : "");
	$g_dtid = (isset ($direct_settings['dsd']['dtid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['dtid'])) : "");
	$g_page = (isset ($direct_settings['dsd']['page']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['page'])) : "");
	$g_source = (isset ($direct_settings['dsd']['source']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['source'])) : "");
	$g_target = (isset ($direct_settings['dsd']['target']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['target'])) : "");

	$g_did_dsd = (strlen ($g_did) ? "ddid+{$g_did}++" : "");
	$g_source_url = ($g_source ? base64_decode ($g_source) : "m=discuss&a=posts&dsd={$g_did_dsd}[oid]page+".$g_page);

	if ($g_target) { $g_target_url = base64_decode ($g_target); }
	else
	{
		$g_target = $g_source;
		$g_target_url = $g_source_url;
	}

	$direct_cachedata['page_this'] = "m=datacenter&s=control_objects&a=upload&dsd=doid+{$g_oid}++source+".(urlencode ($g_source))."++target+".(urlencode ($g_target));
	$direct_cachedata['page_backlink'] = str_replace ("[oid]","dtid+{$g_dtid}++",$g_source_url);
	$direct_cachedata['page_homelink'] = $direct_cachedata['page_backlink'];

	$g_continue_check = $direct_classes['kernel']->service_init_default ();
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->settings_get ($direct_settings['path_data']."/settings/swg_vote.php",true); }
	if (($g_continue_check)&&((!$direct_settings['discuss_vote'])||(!$direct_settings['vote']))) { $g_continue_check = false; }

	if ($g_continue_check)
	{
	//j// BOA
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_board.php");
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_topic.php");
	direct_local_integration ("discuss");

	$g_board_array = NULL;
	$g_datasub_check = false;
	$g_topic_object = new direct_discuss_topic ();

	$g_topic_array = ($g_topic_object ? $g_topic_object->get ($g_dtid) : NULL);

	if (is_array ($g_topic_array))
	{
		if ($g_topic_array['ddbdatalinker_id_main'])
		{
			$g_board_object = new direct_discuss_board ();
			if ($g_board_object) { $g_board_array = $g_board_object->get ($g_topic_array['ddbdatalinker_id_main']); }
		}
		else { $g_datasub_check = true; }
	}

	if ($g_datasub_check) { $g_moderator_check = (($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 3) ? true : false); }
	else { $g_moderator_check = $g_board_object->is_moderator (); }

	if ((!$g_datasub_check)&&(!is_array ($g_board_array))) { $direct_classes['error_functions']->error_page ("standard","discuss_tid_invalid","sWG/#echo(__FILEPATH__)# _a=vote_ (#echo(__LINE__)#)"); }
	elseif (($g_topic_object->is_editable ())&&(($g_datasub_check)||($g_board_object->is_writable ()))&&(($g_topic_array['ddbdatalinker_subs'] > 0)||($g_topic_object->is_sub_allowed ())||($g_moderator_check)))
	{
		$direct_classes['kernel']->service_https ($direct_settings['vote_https_control'],$direct_cachedata['page_this']);
		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/functions/swg_tmp_storager.php");

		$g_tid = uniqid ("");

$g_task_array = array (
"core_back_return" => $g_source_url,
"core_sid" => "4ca5d171acaac2c5ca261c97b0d40383",
// md5 ("vote")
"vote_setup_confirm" => 1,
"vote_setup_mode" => "new",
"vote_setup_vid" => "",
"vote_setup_pid" => $g_topic_array['ddbdatalinker_id_object'],
"vote_setup_done" => 0,
"vote_setup_return" => $g_target_url,
"uuid" => $direct_settings['uuid']
);

		direct_tmp_storage_write ($g_task_array,$g_tid,"4ca5d171acaac2c5ca261c97b0d40383","task_cache","evars",$direct_cachedata['core_time'],($direct_cachedata['core_time'] + 900));
		// md5 ("vote")
		direct_class_init ("output");
		$direct_classes['output']->redirect (direct_linker ("url1","m=vote&s=control&a=settings&dsd=tid+".$g_tid,false));
	}
	else { $direct_classes['error_functions']->error_page ("login","core_access_denied","sWG/#echo(__FILEPATH__)# _a=upload_ (#echo(__LINE__)#)"); }
	//j// EOA
	}

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// EOS
}

//j// EOF
?>