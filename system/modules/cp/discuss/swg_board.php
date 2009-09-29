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
* cp/discuss/swg_board.php
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

if (!isset ($direct_settings['cp_https_discuss_manage_boards'])) { $direct_settings['cp_https_discuss_manage_boards'] = false; }
if (!isset ($direct_settings['formtags_overview_document_url'])) { $direct_settings['formtags_overview_document_url'] = "m=contentor&a=view&dsd=cdid+dng_{$direct_settings['lang']}_2_90000000001"; }
if (!isset ($direct_settings['search_intext'])) { $direct_settings['search_intext'] = false; }
if (!isset ($direct_settings['search_intext_guests'])) { $direct_settings['search_intext_guests'] = false; }
if (!isset ($direct_settings['search_term_max'])) { $direct_settings['search_term_max'] = 256; }
if (!isset ($direct_settings['search_term_min'])) { $direct_settings['search_term_min'] = 4; }
if (!isset ($direct_settings['search_titledata'])) { $direct_settings['search_titledata'] = true; }
if (!isset ($direct_settings['serviceicon_default_back'])) { $direct_settings['serviceicon_default_back'] = "mini_default_back.png"; }
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
	$g_eid = (isset ($direct_settings['dsd']['ddeid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['ddeid'])) : "");

	if ($g_mode_save)
	{
		$direct_cachedata['page_this'] = "";
		$direct_cachedata['page_backlink'] = "m=cp&s=discuss;board&a=edit&dsd=ddid+{$g_did}++ddeid+".$g_eid;
		$direct_cachedata['page_homelink'] = "m=dataport&s=swgap;cp;discuss;board&a=list&dsd=ddid+{$g_did}++dtheme+1";
	}
	else
	{
		$direct_cachedata['page_this'] = "m=cp&s=discuss;board&a=edit&dsd=ddid+{$g_did}++ddeid+".$g_eid;
		$direct_cachedata['page_backlink'] = "m=dataport&s=swgap;cp;discuss;board&a=list&dsd=ddid+{$g_did}++dtheme+1";
		$direct_cachedata['page_homelink'] = "m=cp&s=discuss;index&a=boards";
	}

	$g_continue_check = $direct_classes['kernel']->service_init_default ();

	if ($g_continue_check)
	{
		if ($direct_settings['user']['type'] == "ad")
		{
			$g_rights_check = true;
			$g_right_write_check = true;
		}
		else
		{
			$g_rights_check = false;
			$g_right_write_check = false;
		}

		if ((!$g_rights_check)&&($direct_classes['kernel']->v_group_user_check_right ("cp_access")))
		{
			if (($direct_classes['kernel']->v_group_user_check_right ("cp_discuss_manage_boards_all"))||($direct_classes['kernel']->v_group_user_check_right ("cp_discuss_manage_board_".$direct_cachedata['output_did'])))
			{
				$g_rights_check = true;
				$g_right_write_check = true;
			}
			else { $g_rights_check = $direct_classes['kernel']->v_group_user_check_right ("cp_discuss_manage_board_{$direct_cachedata['output_did']}_links"); }
		}
	}

	if ($g_continue_check)
	{
	if ($g_rights_check)
	{
	//j// BOA
	if ($g_mode_save) { direct_output_related_manager ("cp_discuss_board_edit_{$g_did}_form_save","pre_module_service_action"); }
	else
	{
		direct_output_related_manager ("cp_discuss_board_edit_{$g_did}_form","pre_module_service_action");
		$direct_classes['kernel']->service_https ($direct_settings['cp_https_discuss_manage_boards'],$direct_cachedata['page_this']);
	}

	$direct_classes['basic_functions']->settings_get ($direct_settings['path_data']."/settings/swg_discuss.php",true);
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_board.php");
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/swg_formbuilder.php");
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/swg_formtags.php");
	direct_local_integration ("cp_discuss");
	direct_local_integration ("discuss");

	direct_class_init ("formbuilder");
	direct_class_init ("formtags");
	direct_class_init ("output");
	$direct_classes['output']->options_insert (2,"servicemenu",$direct_cachedata['page_backlink'],(direct_local_get ("core_back")),$direct_settings['serviceicon_default_back'],"url0");

	$g_board_object = new direct_discuss_board ();
	$g_continue_check = ((($g_board_object)&&($g_board_object->get ($g_did))) ? true : false);

	$g_board_object = new direct_discuss_board ();
	$g_board_array = (($g_board_object) ? $g_board_object->get ($g_eid) : NULL);

	if (is_array ($g_board_array))
	{
		if (((!$g_right_write_check)&&($g_board_array['ddbdatalinker_id'] == $g_board_array['ddbdatalinker_id_object']))||($g_board_array['ddbdatalinker_id_main'] != $g_did)) { $g_continue_check = false; }
	}
	else { $g_continue_check = false; }

	if ($g_continue_check)
	{
		$g_continue_check = (($g_board_array['ddbdatalinker_id'] == $g_board_array['ddbdatalinker_id_object']) ? true : false);

		if ($g_mode_save)
		{
/* -------------------------------------------------------------------------
We should have input in save mode
------------------------------------------------------------------------- */

			$direct_cachedata['i_dtitle'] = (isset ($GLOBALS['i_dtitle']) ? ($direct_classes['basic_functions']->inputfilter_basic ($GLOBALS['i_dtitle'])) : "");

			if ($g_continue_check)
			{
				$direct_cachedata['i_ddesc'] = (isset ($GLOBALS['i_ddesc']) ? ($direct_classes['basic_functions']->inputfilter_basic ($GLOBALS['i_ddesc'])) : "");

				$direct_cachedata['i_dpublic'] = (isset ($GLOBALS['i_dpublic']) ? (str_replace ("'","",$GLOBALS['i_dpublic'])) : "");
				$direct_cachedata['i_dpublic'] = str_replace ("<value value='$direct_cachedata[i_dpublic]' />","<value value='$direct_cachedata[i_dpublic]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

				$direct_cachedata['i_dviews_count'] = (isset ($GLOBALS['i_dviews_count']) ? (str_replace ("'","",$GLOBALS['i_dviews_count'])) : "");
				$direct_cachedata['i_dviews_count'] = str_replace ("<value value='$direct_cachedata[i_dviews_count]' />","<value value='$direct_cachedata[i_dviews_count]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

				$direct_cachedata['i_dlocked'] = (isset ($GLOBALS['i_dlocked']) ? (str_replace ("'","",$GLOBALS['i_dlocked'])) : "");
				$direct_cachedata['i_dlocked'] = str_replace ("<value value='$direct_cachedata[i_dlocked]' />","<value value='$direct_cachedata[i_dlocked]' /><selected value='1' />","<evars><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes></evars>");

				$direct_cachedata['i_dsubs_allowed'] = (isset ($GLOBALS['i_dsubs_allowed']) ? (str_replace ("'","",$GLOBALS['i_dsubs_allowed'])) : "");
				$direct_cachedata['i_dsubs_allowed'] = str_replace ("<value value='$direct_cachedata[i_dsubs_allowed]' />","<value value='$direct_cachedata[i_dsubs_allowed]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

				$direct_cachedata['i_dsubs_hidden'] = (isset ($GLOBALS['i_dsubs_hidden']) ? (str_replace ("'","",$GLOBALS['i_dsubs_hidden'])) : "");
				$direct_cachedata['i_dsubs_hidden'] = str_replace ("<value value='$direct_cachedata[i_dsubs_hidden]' />","<value value='$direct_cachedata[i_dsubs_hidden]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

				$direct_cachedata['i_dsubs_type'] = (isset ($GLOBALS['i_dsubs_type']) ? (str_replace ("'","",$GLOBALS['i_dsubs_type'])) : 0);
			}
		}
		else
		{
			if ($g_continue_check)
			{
				$direct_cachedata['i_dtitle'] = $g_board_array['ddbdatalinker_title'];
				$direct_cachedata['i_ddesc'] = $direct_classes['formtags']->recode_newlines (direct_output_smiley_cleanup ($g_board_array['ddbdiscuss_boards_data']),false);

				if ($g_board_array['ddbdiscuss_boards_public']) { $direct_cachedata['i_dpublic'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
				else { $direct_cachedata['i_dpublic'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

				if ($g_board_object->is_views_counting ()) { $direct_cachedata['i_dviews_count'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
				else { $direct_cachedata['i_dviews_count'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

				if ($g_board_object->is_locked ()) { $direct_cachedata['i_dlocked'] = "<evars><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes></evars>"; }
				else { $direct_cachedata['i_dlocked'] = "<evars><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes></evars>"; }

				if ($g_board_object->is_sub_allowed ()) { $direct_cachedata['i_dsubs_allowed'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
				else { $direct_cachedata['i_dsubs_allowed'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

				if ($g_board_array['ddbdatalinker_datasubs_hide']) { $direct_cachedata['i_dsubs_hidden'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
				else { $direct_cachedata['i_dsubs_hidden'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

				$direct_cachedata['i_dsubs_type'] = ((($g_eid)&&(isset ($g_board_array['ddbdatalinker_datasubs_type']))) ? str_replace ("'","",$g_board_array['ddbdatalinker_datasubs_type']) : 0);
			}
			else { $direct_cachedata['i_dtitle'] = $g_board_array['ddbdatalinker_title_alt']; }
		}

		if ($g_continue_check)
		{
$direct_cachedata['i_dsubs_type'] = str_replace ("<value value='$direct_cachedata[i_dsubs_type]' />","<value value='$direct_cachedata[i_dsubs_type]' /><selected value='1' />","<evars>
<default><value value='0' /><text><![CDATA[".(direct_local_get ("core_datasub_title_default"))."]]></text></default><attachments><value value='1' /><text><![CDATA[".(direct_local_get ("core_datasub_title_attachments"))."]]></text></attachments><downloads><value value='2' /><text><![CDATA[".(direct_local_get ("core_datasub_title_downloads"))."]]></text></downloads><links><value value='3' /><text><![CDATA[".(direct_local_get ("core_datasub_title_links"))."]]></text></links>
</evars>");
		}
		else { $direct_cachedata['i_dtitle_orig'] = direct_html_encode_special ($g_board_array['ddbdatalinker_title']); }

/* -------------------------------------------------------------------------
Build the form
------------------------------------------------------------------------- */

		if ($g_continue_check)
		{
			$direct_classes['formbuilder']->entry_add_text ("dtitle",(direct_local_get ("discuss_board")),true,"s",1,255);

			if ($direct_settings['formtags_overview_document_url']) { $direct_classes['formbuilder']->entry_add_jfield_textarea ("ddesc",(direct_local_get ("cp_discuss_board_desc")),false,"s",0,255,(direct_local_get ("formtags_overview_document")),(direct_linker ("url0",$direct_settings['formtags_overview_document_url']))); }
			else { $direct_classes['formbuilder']->entry_add_jfield_textarea ("ddesc",(direct_local_get ("cp_discuss_board_desc")),false,"s",0,255); }

			$direct_classes['formbuilder']->entry_add ("spacer");
			$direct_classes['formbuilder']->entry_add_select ("dpublic",(direct_local_get ("cp_discuss_board_public")),false,"s");
			$direct_classes['formbuilder']->entry_add_select ("dviews_count",(direct_local_get ("cp_discuss_board_views_counted")),false,"s");
			$direct_classes['formbuilder']->entry_add_select ("dlocked",(direct_local_get ("cp_discuss_board_locked")),false,"s");
			$direct_classes['formbuilder']->entry_add ("spacer");
			$direct_classes['formbuilder']->entry_add_select ("dsubs_allowed",(direct_local_get ("core_datasub_allowed")),true,"s");
			$direct_classes['formbuilder']->entry_add_select ("dsubs_hidden",(direct_local_get ("core_datasub_hide")),true,"s");
			$direct_classes['formbuilder']->entry_add_radio ("dsubs_type",(direct_local_get ("core_datasub_type")),true);
		}
		else
		{
			$direct_classes['formbuilder']->entry_add ("info","dtitle_orig",(direct_local_get ("discuss_board")));
			$direct_classes['formbuilder']->entry_add_text ("dtitle",(direct_local_get ("cp_discuss_board_title_alt")),false,"s",0,255);
		}

		$direct_cachedata['output_formelements'] = $direct_classes['formbuilder']->form_get ($g_mode_save);

		if (($g_mode_save)&&($direct_classes['formbuilder']->check_result))
		{
/* -------------------------------------------------------------------------
Save data edited
------------------------------------------------------------------------- */

			if ($g_continue_check)
			{
				$g_board_array['ddbdatalinker_title'] = $direct_cachedata['i_dtitle'];
				$g_board_array['ddbdatalinker_views_count'] = $direct_cachedata['i_dviews_count'];
				$g_board_array['ddbdiscuss_boards_data'] = direct_output_smiley_encode ($direct_classes['formtags']->encode ($direct_cachedata['i_ddesc']));
				$g_board_array['ddbdiscuss_boards_public'] = $direct_cachedata['i_dpublic'];
				$g_board_array['ddbdiscuss_boards_locked'] = $direct_cachedata['i_dlocked'];

				if ($direct_cachedata['i_dsubs_allowed'])
				{
					$g_board_array['ddbdatalinker_datasubs_type'] = $direct_cachedata['i_dsubs_type'];
					$g_board_array['ddbdatalinker_datasubs_hide'] = $direct_cachedata['i_dsubs_hidden'];
					$g_board_array['ddbdatalinker_datasubs_new'] = 1;
				}
				else { $g_board_array['ddbdatalinker_datasubs_new'] = 0; }
			}
			else { $g_board_array['ddbdatalinker_title_alt'] = $direct_cachedata['i_dtitle']; }

			if ($g_board_object->set_update ($g_board_array))
			{
				$direct_cachedata['output_job'] = direct_local_get ("cp_discuss_board_edit");
				$direct_cachedata['output_job_desc'] = direct_local_get ("cp_discuss_done_board_edit");
				$direct_cachedata['output_jsjump'] = 2000;

				$direct_cachedata['output_pagetarget'] = direct_linker ("url0","m=dataport&s=swgap;cp;discuss;board&a=list&dsd=ddid+{$g_did}++dtheme+1");
				$direct_cachedata['output_scripttarget'] = direct_linker ("url0","m=dataport&s=swgap;cp;discuss;board&a=list&dsd=ddid+{$g_did}++dtheme+1",false);

				direct_output_related_manager ("cp_discuss_board_edit_{$g_did}_form_save","post_module_service_action");
				$direct_classes['output']->oset ("default","done");
				$direct_classes['output']->options_flush (true);
				$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
				$direct_classes['output']->page_show ($direct_cachedata['output_job']);
			}
			else { $direct_classes['error_functions']->error_page ("fatal","core_database_error","sWG/#echo(__FILEPATH__)# _a=edit-save_ (#echo(__LINE__)#)"); }
		}
		else
		{
/* -------------------------------------------------------------------------
View form
------------------------------------------------------------------------- */

			$direct_cachedata['output_formbutton'] = direct_local_get ("core_save");
			$direct_cachedata['output_formtarget'] = "m=cp&s=discuss;board&a=edit-save&dsd=ddid+{$g_did}++ddeid+".$g_eid;
			$direct_cachedata['output_formtitle'] = direct_local_get ("cp_discuss_board_edit");

			direct_output_related_manager ("cp_discuss_board_edit_{$g_did}_form","post_module_service_action");
			$direct_classes['output']->oset ("default","form");
			$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
			$direct_classes['output']->page_show ($direct_cachedata['output_formtitle']);
		}
	}
	else { $direct_classes['error_functions']->error_page ("standard","discuss_did_invalid","sWG/#echo(__FILEPATH__)# _a={$direct_settings['a']}_ (#echo(__LINE__)#)"); }
	//j// EOA
	}
	else { $direct_classes['error_functions']->error_page ("login","core_access_denied","sWG/#echo(__FILEPATH__)# _a={$direct_settings['a']}_ (#echo(__LINE__)#)"); }
	}

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// $direct_settings['a'] == "link_delete"
case "link_delete":
{
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a=link_delete_ (#echo(__LINE__)#)"); }

	$g_did = (isset ($direct_settings['dsd']['ddid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['ddid'])) : "");
	$g_eid = (isset ($direct_settings['dsd']['ddeid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['ddeid'])) : "");

	$direct_cachedata['page_this'] = "";
	$direct_cachedata['page_backlink'] = "m=dataport&s=swgap;cp;discuss;board&a=list&dsd=ddid+{$g_did}++dtheme+1";
	$direct_cachedata['page_homelink'] = "m=cp&s=discuss;index&a=boards";

	$g_continue_check = $direct_classes['kernel']->service_init_default ();

	if ($g_continue_check)
	{
		$g_rights_check = (($direct_settings['user']['type'] == "ad") ? true : false);
		if ((!$g_rights_check)&&($direct_classes['kernel']->v_group_user_check_right ("cp_access"))&&(($direct_classes['kernel']->v_group_user_check_right ("cp_discuss_manage_boards_all"))||($direct_classes['kernel']->v_group_user_check_right ("cp_discuss_manage_board_".$g_did))||($direct_classes['kernel']->v_group_user_check_right ("cp_discuss_manage_board_{$g_did}_links")))) { $g_rights_check = true; }
	}

	if ($g_continue_check)
	{
	if ($g_rights_check)
	{
	//j// BOA
	$direct_classes['basic_functions']->settings_get ($direct_settings['path_data']."/settings/swg_discuss.php",true);
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_board.php");
	direct_local_integration ("cp_discuss");
	direct_local_integration ("discuss");

	direct_class_init ("output");
	$direct_classes['output']->options_insert (2,"servicemenu",$direct_cachedata['page_backlink'],(direct_local_get ("core_back")),$direct_settings['serviceicon_default_back'],"url0");

	$g_board_object = new direct_discuss_board ();
	$g_continue_check = ((($g_board_object)&&($g_board_object->get ($g_did))) ? true : false);

	$g_board_object = new direct_discuss_board ();
	$g_board_array = (($g_board_object) ? $g_board_object->get ($g_eid) : NULL);

	if (is_array ($g_board_array))
	{
		if (($g_board_array['ddbdatalinker_id'] == $g_board_array['ddbdatalinker_id_object'])||($g_board_array['ddbdatalinker_id_main'] != $g_did)) { $g_continue_check = false; }
	}
	else { $g_continue_check = false; }

	if ($g_continue_check)
	{
		if ($g_board_object->delete (true,false))
		{
			$direct_cachedata['output_job'] = direct_local_get ("cp_discuss_board_link_delete");
			$direct_cachedata['output_job_desc'] = direct_local_get ("cp_discuss_done_board_link_delete");
			$direct_cachedata['output_jsjump'] = 2000;

			$direct_cachedata['output_pagetarget'] = direct_linker ("url0","m=dataport&s=swgap;cp;discuss;board&a=list&dsd=ddid+{$g_did}++dtheme+1");
			$direct_cachedata['output_scripttarget'] = direct_linker ("url0","m=dataport&s=swgap;cp;discuss;board&a=list&dsd=ddid+{$g_did}++dtheme+1",false);

			direct_output_related_manager ("cp_discuss_board_new_{$g_did}_form_save","post_module_service_action");
			$direct_classes['output']->oset ("default","done");
			$direct_classes['output']->options_flush (true);
			$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
			$direct_classes['output']->page_show ($direct_cachedata['output_job']);
		}
		else { $direct_classes['error_functions']->error_page ("fatal","core_database_error","sWG/#echo(__FILEPATH__)# _a=link_delete_ (#echo(__LINE__)#)"); }
	}
	else { $direct_classes['error_functions']->error_page ("standard","core_tid_invalid","sWG/#echo(__FILEPATH__)# _a=link_delete_ (#echo(__LINE__)#)"); }
	//j// EOA
	}
	else { $direct_classes['error_functions']->error_page ("login","core_access_denied","sWG/#echo(__FILEPATH__)# _a=link_delete_ (#echo(__LINE__)#)"); }
	}

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// ($direct_settings['a'] == "link_new")||($direct_settings['a'] == "link_new-save")
case "link_new":
case "link_new-save":
{
	$g_mode_save = (($direct_settings['a'] == "link_new-save") ? true : false);
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a={$direct_settings['a']}_ (#echo(__LINE__)#)"); }

	$g_did = (isset ($direct_settings['dsd']['ddid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['ddid'])) : "");
	$g_eid = (isset ($direct_settings['dsd']['ddeid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['ddeid'])) : "");
	$g_position = (isset ($direct_settings['dsd']['dposition']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['dposition'])) : "");

	if ($g_mode_save)
	{
		$direct_cachedata['page_this'] = "";
		$direct_cachedata['page_backlink'] = "m=cp&s=discuss;board&a=link_new&dsd=ddid+{$g_did}++ddeid+{$g_eid}++dposition+".$g_position;
		$direct_cachedata['page_homelink'] = "m=dataport&s=swgap;cp;discuss;board&a=list&dsd=ddid+{$g_did}++dtheme+1";
	}
	else
	{
		$direct_cachedata['page_this'] = "m=cp&s=discuss;board&a=link_new&dsd=ddid+{$g_did}++ddeid+{$g_eid}++dposition+".$g_position;
		$direct_cachedata['page_backlink'] = "m=dataport&s=swgap;cp;discuss;board&a=list&dsd=ddid+{$g_did}++dtheme+1";
		$direct_cachedata['page_homelink'] = "m=cp&s=discuss;index&a=boards";
	}

	$g_continue_check = $direct_classes['kernel']->service_init_default ();

	if ($g_continue_check)
	{
		$g_rights_check = (($direct_settings['user']['type'] == "ad") ? true : false);
		if ((!$g_rights_check)&&($direct_classes['kernel']->v_group_user_check_right ("cp_access"))&&(($direct_classes['kernel']->v_group_user_check_right ("cp_discuss_manage_boards_all"))||($direct_classes['kernel']->v_group_user_check_right ("cp_discuss_manage_board_".$g_did))||($direct_classes['kernel']->v_group_user_check_right ("cp_discuss_manage_board_{$g_did}_links")))) { $g_rights_check = true; }
	}

	if ($g_continue_check)
	{
	if ($g_rights_check)
	{
	//j// BOA
	if ($g_mode_save) { direct_output_related_manager ("datalinker_subs_discuss_link_new_form_save","pre_module_service_action"); }
	else
	{
		direct_output_related_manager ("datalinker_subs_discuss_link_new_form","pre_module_service_action");
		$direct_classes['kernel']->service_https ($direct_settings['datalinker_https_subs_new'],$direct_cachedata['page_this']);
	}

	$direct_classes['basic_functions']->settings_get ($direct_settings['path_data']."/settings/swg_search.php",true);
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/swg_formbuilder.php");
	if ($g_mode_save) { $direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/functions/swg_tmp_storager.php"); }
	direct_local_integration ("search");

	direct_class_init ("formbuilder");
	direct_class_init ("output");
	$direct_classes['output']->options_insert (2,"servicemenu",$direct_cachedata['page_backlink'],(direct_local_get ("core_back")),$direct_settings['serviceicon_default_back'],"url0");

	if ($g_mode_save)
	{
/* -------------------------------------------------------------------------
We should have input in save mode
------------------------------------------------------------------------- */

		$direct_cachedata['i_sterm'] = (isset ($GLOBALS['i_sterm']) ? ($direct_classes['basic_functions']->inputfilter_basic ($GLOBALS['i_sterm'])) : "");

		$direct_cachedata['i_swords'] = (isset ($GLOBALS['i_swords']) ? (str_replace ("'","",$GLOBALS['i_swords'])) : "");
		$direct_cachedata['i_swords'] = str_replace ("<value value='$direct_cachedata[i_swords]' />","<value value='$direct_cachedata[i_swords]' /><selected value='1' />","<evars><any><value value='any' /><text><![CDATA[".(direct_local_get ("search_word_behavior_any"))."]]></text></any><all><value value='all' /><text><![CDATA[".(direct_local_get ("search_word_behavior_all"))."]]></text></all></evars>");
	}
	else
	{
		$direct_cachedata['i_sterm'] = "";
		$direct_cachedata['i_swords'] = "<evars><any><value value='any' /><selected value='1' /><text><![CDATA[".(direct_local_get ("search_word_behavior_any"))."]]></text></any><all><value value='all' /><text><![CDATA[".(direct_local_get ("search_word_behavior_all"))."]]></text></all></evars>";
	}

/* -------------------------------------------------------------------------
Build the form
------------------------------------------------------------------------- */

	$direct_classes['formbuilder']->entry_add_text ("sterm",(direct_local_get ("search_term")),true,"s",$direct_settings['search_term_min'],$direct_settings['search_term_max'],(direct_local_get ("search_helper_term")),"",true);
	$direct_classes['formbuilder']->entry_add_select ("swords",(direct_local_get ("search_word_behavior")),false,"s");

	$direct_cachedata['output_formelements'] = $direct_classes['formbuilder']->form_get ($g_mode_save);

	if (($g_mode_save)&&($direct_classes['formbuilder']->check_result))
	{
/* -------------------------------------------------------------------------
Save data edited
------------------------------------------------------------------------- */

		$g_tid = uniqid ("");

$g_task_array = array (
"core_back_return" => "m=dataport&s=swgap;cp;discuss;board&a=list&dsd=ddid+{$g_did}++dtheme+1",
"core_sid" => "cb41ecf6e90a594dcea60b6140251d62",
// md5 ("discuss")
"search_base" => "title",
"search_marker_return" => "m=cp&s=discuss;board&a=link_new-post&dsd=ddid+{$g_did}++ddeid+{$g_eid}++dposition+{$g_position}++[oid]tid+".$g_tid,
"search_result_handler" => "m=dataport&s=swgap;search;selector&dsd=dtheme+1++[oid]",
"search_selection_done" => 0,
"search_selection_quantity" => 1,
"search_services" => array ("cb41ecf6e90a594dcea60b6140251d62"),
"search_service_types" => array ("cb41ecf6e90a594dcea60b6140251d62" => array (1,2,3,4)),
"search_term" => $direct_cachedata['i_sterm'],
"search_words" => $direct_cachedata['i_swords'],
"uuid" => $direct_settings['uuid']
);

		direct_tmp_storage_write ($g_task_array,$g_tid,"cb41ecf6e90a594dcea60b6140251d62","task_cache","evars",$direct_cachedata['core_time'],($direct_cachedata['core_time'] + 900));
		$direct_classes['output']->redirect (direct_linker ("url1","m=dataport&s=swgap;search;selector&a=run&dsd=dtheme+1++tid+".$g_tid,false));
	}
	else
	{
/* -------------------------------------------------------------------------
View form
------------------------------------------------------------------------- */

		$direct_cachedata['output_formbutton'] = direct_local_get ("search_new");
		$direct_cachedata['output_formtarget'] = "m=cp&s=discuss;board&a=link_new-save&dsd=ddid+{$g_did}++ddeid+{$g_eid}++dposition+".$g_position;
		$direct_cachedata['output_formtitle'] = direct_local_get ("core_search");

		direct_output_related_manager ("datalinker_subs_discuss_link_new_form","post_module_service_action");
		$direct_classes['output']->oset ("default","form");
		$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
		$direct_classes['output']->page_show ($direct_cachedata['output_formtitle']);
	}
	//j// EOA
	}
	else { $direct_classes['error_functions']->error_page ("standard","core_service_inactive","sWG/#echo(__FILEPATH__)# _a={$direct_settings['a']}_ (#echo(__LINE__)#)"); }
	}

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// $direct_settings['a'] == "link_new-post"
case "link_new-post":
{
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a=link_new-post_ (#echo(__LINE__)#)"); }

	$g_did = (isset ($direct_settings['dsd']['ddid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['ddid'])) : "");
	$g_eid = (isset ($direct_settings['dsd']['ddeid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['ddeid'])) : "");
	$g_position = (isset ($direct_settings['dsd']['dposition']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['dposition'])) : "");
	$g_tid = (isset ($direct_settings['dsd']['tid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['tid'])) : "");

	$direct_cachedata['page_this'] = "";
	$direct_cachedata['page_backlink'] = "m=cp&s=discuss;board&a=link_new&dsd=ddid+{$g_did}++ddeid+{$g_eid}++dposition+".$g_position;
	$direct_cachedata['page_homelink'] = "m=dataport&s=swgap;cp;discuss;board&a=list&dsd=ddid+{$g_did}++dtheme+1";

	$g_continue_check = $direct_classes['kernel']->service_init_default ();

	if ($g_continue_check)
	{
		$g_rights_check = (($direct_settings['user']['type'] == "ad") ? true : false);
		if ((!$g_rights_check)&&($direct_classes['kernel']->v_group_user_check_right ("cp_access"))&&(($direct_classes['kernel']->v_group_user_check_right ("cp_discuss_manage_boards_all"))||($direct_classes['kernel']->v_group_user_check_right ("cp_discuss_manage_board_".$g_did))||($direct_classes['kernel']->v_group_user_check_right ("cp_discuss_manage_board_{$g_did}_links")))) { $g_rights_check = true; }
	}

	if ($g_continue_check)
	{
	if ($g_rights_check)
	{
	//j// BOA
	$direct_classes['basic_functions']->settings_get ($direct_settings['path_data']."/settings/swg_discuss.php",true);
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_board.php");
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/functions/swg_datalinker_structure.php");
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/functions/swg_tmp_storager.php");
	direct_local_integration ("cp_discuss");
	direct_local_integration ("discuss");

	direct_class_init ("output");
	$direct_classes['output']->options_insert (2,"servicemenu",$direct_cachedata['page_backlink'],(direct_local_get ("core_back")),$direct_settings['serviceicon_default_back'],"url0");

	if ($g_continue_check)
	{
		$g_task_array = direct_tmp_storage_get ("evars",$g_tid,"","task_cache");

		if (($g_task_array)&&(isset ($g_task_array['core_sid'],$g_task_array['uuid']))&&($g_task_array['uuid'] == $direct_settings['uuid']))
		{
			if (isset ($g_task_array['search_results_confirmed'])) { unset ($g_task_array['search_results_confirmed']); }
			if (isset ($g_task_array['search_results_possible'])) { unset ($g_task_array['search_results_possible']); }
			if (isset ($g_task_array['search_result_positions'])) { unset ($g_task_array['search_result_positions']); }
		}
		else { $g_continue_check = false; }
	}

	$g_board_object = ($g_continue_check ? new direct_discuss_board () : NULL);

	if (($g_board_object)&&($g_board_object->get ($g_did)))
	{
		if ((isset ($g_task_array['search_objects_marked']))&&(!empty ($g_task_array['search_objects_marked'])))
		{
			$g_board_id = array_shift ($g_task_array['search_objects_marked']);
			unset ($g_task_array['search_objects_marked']);
		}
		elseif ((isset ($g_task_array['datalinker_link_objects_marked']))&&(!empty ($g_task_array['datalinker_link_objects_marked'])))
		{
			$g_board_id = array_shift ($g_task_array['datalinker_link_objects_marked']);
			unset ($g_task_array['datalinker_link_objects_marked']);

			$g_datalinker_cache = direct_tmp_storage_get ("evars",$direct_settings['uuid'],"4c6924b0583e6882d3db6aff277bfc3e","link_cache");

			if (isset ($g_datalinker_cache['datalinker_objects_selected']))
			{
				if (isset ($g_datalinker_cache['datalinker_objects_selected'][$g_board_id]))
				{
					unset ($g_datalinker_cache['datalinker_objects_selected'][$g_board_id]);
					direct_tmp_storage_write ($g_datalinker_cache,$direct_settings['uuid'],"4c6924b0583e6882d3db6aff277bfc3e","link_cache","evars",$direct_cachedata['core_time'],($direct_cachedata['core_time'] + 3600));
					// md5 ("datalinker")
				}
			}
		}
		else { $g_continue_check = false; }

		if ($g_continue_check)
		{
			$g_board_structure_data_array = $g_board_object->get_structure ("","",false);
			$g_continue_check = is_array ($g_board_structure_data_array);
		}

		if ($g_continue_check)
		{
			$g_board_object = new direct_discuss_board ();
			$g_board_array = ($g_board_object ? $g_board_object->get ($g_board_id) : NULL);
			$g_continue_check = is_array ($g_board_array);
		}

		if ($g_continue_check)
		{
			$g_board_array['ddbdatalinker_id'] = uniqid ("");

			if ($g_eid) { $g_board_id = $g_eid; }
			elseif (($g_position < 1)&&($g_board_structure_data_array['structured'] === NULL)) { $g_board_id = $g_did; }

			$g_board_structure_list_array = direct_datalinker_structure_add ($g_board_structure_data_array['structured'],$g_position,$g_board_id,$g_board_array);
			$g_continue_check = is_array ($g_board_structure_list_array);
		}

		if ($g_continue_check)
		{
			$direct_classes['db']->v_transaction_begin ();

			foreach ($g_board_structure_list_array as $g_target_position => $g_board_structure_element)
			{
				if ($g_continue_check)
				{
					if (is_array ($g_board_structure_element))
					{
						$g_board_structure_element['ddbdatalinker_position'] = $g_target_position;
						$g_datalinker_object = new direct_datalinker ();
						$g_continue_check = ($g_datalinker_object->set ($g_board_structure_element) ? $g_datalinker_object->insert_link () : false);
					}
					else
					{
						$g_board_id = array_pop (explode (":",$g_board_structure_element));
						$g_board_array =& $g_board_structure_data_array['objects'][$g_board_id];

						if ($g_board_array['ddbdatalinker_position'] != $g_target_position)
						{
							$g_board_array['ddbdatalinker_position'] = $g_target_position;
							$g_datalinker_object = new direct_datalinker ();
							$g_continue_check = $g_datalinker_object->set_update ($g_board_array,true,false);
						}
					}
				}
			}

			if ($g_continue_check) { $g_continue_check = $direct_classes['db']->v_transaction_commit (); }
			else { $direct_classes['db']->v_transaction_rollback (); }
		}

		if ($g_continue_check)
		{
			$direct_cachedata['output_job'] = direct_local_get ("cp_discuss_board_link_new");
			$direct_cachedata['output_job_desc'] = direct_local_get ("cp_discuss_done_board_link_new");
			$direct_cachedata['output_jsjump'] = 2000;

			$direct_cachedata['output_pagetarget'] = direct_linker ("url0","m=dataport&s=swgap;cp;discuss;board&a=list&dsd=ddid+{$g_did}++dtheme+1");
			$direct_cachedata['output_scripttarget'] = direct_linker ("url0","m=dataport&s=swgap;cp;discuss;board&a=list&dsd=ddid+{$g_did}++dtheme+1",false);

			direct_output_related_manager ("cp_discuss_board_new_{$g_did}_form_save","post_module_service_action");
			$direct_classes['output']->oset ("default","done");
			$direct_classes['output']->options_flush (true);
			$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
			$direct_classes['output']->page_show ($direct_cachedata['output_job']);
		}
		else { $direct_classes['error_functions']->error_page ("fatal","core_database_error","sWG/#echo(__FILEPATH__)# _a=link_new-save_ (#echo(__LINE__)#)"); }
	}
	else { $direct_classes['error_functions']->error_page ("standard","core_tid_invalid","sWG/#echo(__FILEPATH__)# _a=link_new-post_ (#echo(__LINE__)#)"); }
	//j// EOA
	}
	else { $direct_classes['error_functions']->error_page ("login","core_access_denied","sWG/#echo(__FILEPATH__)# _a=link_new-post_ (#echo(__LINE__)#)"); }
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
	$g_eid = (isset ($direct_settings['dsd']['ddeid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['ddeid'])) : "");
	$g_position = (isset ($direct_settings['dsd']['dposition']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['dposition'])) : "");

	if ($g_mode_save)
	{
		$direct_cachedata['page_this'] = "";
		$direct_cachedata['page_backlink'] = "m=cp&s=discuss;board&a=new&dsd=ddid+{$g_did}++ddeid+{$g_eid}++dposition+".$g_position;
		$direct_cachedata['page_homelink'] = "m=dataport&s=swgap;cp;discuss;board&a=list&dsd=ddid+{$g_did}++dtheme+1";
	}
	else
	{
		$direct_cachedata['page_this'] = "m=cp&s=discuss;board&a=new&dsd=ddid+{$g_did}++ddeid+{$g_eid}++dposition+".$g_position;
		$direct_cachedata['page_backlink'] = "m=dataport&s=swgap;cp;discuss;board&a=list&dsd=ddid+{$g_did}++dtheme+1";
		$direct_cachedata['page_homelink'] = "m=cp&s=discuss;index&a=boards";
	}

	$g_continue_check = $direct_classes['kernel']->service_init_default ();

	if ($g_continue_check)
	{
		$g_rights_check = (($direct_settings['user']['type'] == "ad") ? true : false);

		if ((!$g_rights_check)&&($direct_classes['kernel']->v_group_user_check_right ("cp_access")))
		{
			$g_rights_check = ($g_did ? $direct_classes['kernel']->v_group_user_check_right ("cp_discuss_manage_board_".$g_did) : false);
			if (!$g_rights_check) { $g_rights_check = $direct_classes['kernel']->v_group_user_check_right ("cp_discuss_manage_boards_all"); }
		}
	}

	if ($g_continue_check)
	{
	if ($g_rights_check)
	{
	//j// BOA
	if ($g_mode_save)
	{
		if ($g_did) { direct_output_related_manager ("cp_discuss_board_new_{$g_did}_form_save","pre_module_service_action"); }
		else { direct_output_related_manager ("cp_discuss_board_new_form_save","pre_module_service_action"); }
	}
	elseif ($g_did) { direct_output_related_manager ("cp_discuss_board_new_{$g_did}_form","pre_module_service_action"); }
	else { direct_output_related_manager ("cp_discuss_board_new_form","pre_module_service_action"); }

	if (!$g_mode_save) { $direct_classes['kernel']->service_https ($direct_settings['cp_https_discuss_manage_boards'],$direct_cachedata['page_this']); }
	$direct_classes['basic_functions']->settings_get ($direct_settings['path_data']."/settings/swg_discuss.php",true);
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_board.php");
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/swg_formbuilder.php");

	if ($g_mode_save)
	{
		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/swg_formtags.php");
		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/functions/swg_datalinker_structure.php");
	}

	direct_local_integration ("cp_discuss");
	direct_local_integration ("discuss");

	direct_class_init ("formbuilder");
	if ($g_mode_save) { direct_class_init ("formtags"); }
	direct_class_init ("output");
	$direct_classes['output']->options_insert (2,"servicemenu",$direct_cachedata['page_backlink'],(direct_local_get ("core_back")),$direct_settings['serviceicon_default_back'],"url0");

	$g_board_object = new direct_discuss_board ();

	if (($g_board_object)&&((!$g_did)||($g_board_object->get ($g_did))))
	{
		if (!$g_mode_save)
		{
			if ($g_eid)
			{
				$g_source_board_object = new direct_discuss_board ();
				$g_source_board_array = ($g_source_board_object ? $g_source_board_object->get ($g_eid) : NULL);
				if (!is_array ($g_source_board_array)) { $g_eid = ""; }
			}
			elseif ($g_did)
			{
				$g_source_board_array = $g_board_object->get ();
				$g_source_board_object = $g_board_object;
			}
		}

		if ($g_mode_save)
		{
/* -------------------------------------------------------------------------
We should have input in save mode
------------------------------------------------------------------------- */

			$direct_cachedata['i_dtitle'] = (isset ($GLOBALS['i_dtitle']) ? ($direct_classes['basic_functions']->inputfilter_basic ($GLOBALS['i_dtitle'])) : "");
			$direct_cachedata['i_ddesc'] = (isset ($GLOBALS['i_ddesc']) ? ($direct_classes['basic_functions']->inputfilter_basic ($GLOBALS['i_ddesc'])) : "");

			$direct_cachedata['i_dzone'] = (isset ($GLOBALS['i_dzone']) ? (str_replace ("'","",$GLOBALS['i_dzone'])) : "");
			$direct_cachedata['i_dzone'] = str_replace ("<value value='$direct_cachedata[i_dzone]' />","<value value='$direct_cachedata[i_dzone]' /><selected value='1' />","<evars><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes></evars>");

			$direct_cachedata['i_dpublic'] = (isset ($GLOBALS['i_dpublic']) ? (str_replace ("'","",$GLOBALS['i_dpublic'])) : "");
			$direct_cachedata['i_dpublic'] = str_replace ("<value value='$direct_cachedata[i_dpublic]' />","<value value='$direct_cachedata[i_dpublic]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

			$direct_cachedata['i_dviews_count'] = (isset ($GLOBALS['i_dviews_count']) ? (str_replace ("'","",$GLOBALS['i_dviews_count'])) : "");
			$direct_cachedata['i_dviews_count'] = str_replace ("<value value='$direct_cachedata[i_dviews_count]' />","<value value='$direct_cachedata[i_dviews_count]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

			$direct_cachedata['i_dlocked'] = (isset ($GLOBALS['i_dlocked']) ? (str_replace ("'","",$GLOBALS['i_dlocked'])) : "");
			$direct_cachedata['i_dlocked'] = str_replace ("<value value='$direct_cachedata[i_dlocked]' />","<value value='$direct_cachedata[i_dlocked]' /><selected value='1' />","<evars><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes></evars>");

			$direct_cachedata['i_dsubs_allowed'] = (isset ($GLOBALS['i_dsubs_allowed']) ? (str_replace ("'","",$GLOBALS['i_dsubs_allowed'])) : "");
			$direct_cachedata['i_dsubs_allowed'] = str_replace ("<value value='$direct_cachedata[i_dsubs_allowed]' />","<value value='$direct_cachedata[i_dsubs_allowed]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

			$direct_cachedata['i_dsubs_hidden'] = (isset ($GLOBALS['i_dsubs_hidden']) ? (str_replace ("'","",$GLOBALS['i_dsubs_hidden'])) : "");
			$direct_cachedata['i_dsubs_hidden'] = str_replace ("<value value='$direct_cachedata[i_dsubs_hidden]' />","<value value='$direct_cachedata[i_dsubs_hidden]' /><selected value='1' />","<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>");

			$direct_cachedata['i_dsubs_type'] = (isset ($GLOBALS['i_dsubs_type']) ? (str_replace ("'","",$GLOBALS['i_dsubs_type'])) : 0);
		}
		else
		{
			$direct_cachedata['i_dtitle'] = "";
			$direct_cachedata['i_ddesc'] = "";
			$direct_cachedata['i_dzone'] = "<evars><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes></evars>";

			if (((!$g_did)&&(!$g_eid))||($g_source_board_array['ddbdiscuss_boards_public'])) { $direct_cachedata['i_dpublic'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
			else { $direct_cachedata['i_dpublic'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

			if (((!$g_did)&&(!$g_eid))||($g_source_board_object->is_views_counting ())) { $direct_cachedata['i_dviews_count'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
			else { $direct_cachedata['i_dviews_count'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

			if ((($g_did)||($g_eid))&&($g_source_board_object->is_locked ())) { $direct_cachedata['i_dlocked'] = "<evars><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes></evars>"; }
			else { $direct_cachedata['i_dlocked'] = "<evars><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes></evars>"; }

			if ((($g_did)||($g_eid))&&($g_source_board_object->is_sub_allowed ())) { $direct_cachedata['i_dsubs_allowed'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
			else { $direct_cachedata['i_dsubs_allowed'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

			if (((!$g_did)&&(!$g_eid))||($g_source_board_array['ddbdatalinker_datasubs_hide'])) { $direct_cachedata['i_dsubs_hidden'] = "<evars><yes><value value='1' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }
			else { $direct_cachedata['i_dsubs_hidden'] = "<evars><yes><value value='1' /><text><![CDATA[".(direct_local_get ("core_yes"))."]]></text></yes><no><value value='0' /><selected value='1' /><text><![CDATA[".(direct_local_get ("core_no"))."]]></text></no></evars>"; }

			$direct_cachedata['i_dsubs_type'] = (((($g_did)||($g_eid))&&(isset ($g_source_board_array['ddbdatalinker_datasubs_type']))) ? str_replace ("'","",$g_source_board_array['ddbdatalinker_datasubs_type']) : 0);
		}

$direct_cachedata['i_dsubs_type'] = str_replace ("<value value='$direct_cachedata[i_dsubs_type]' />","<value value='$direct_cachedata[i_dsubs_type]' /><selected value='1' />","<evars>
<default><value value='0' /><text><![CDATA[".(direct_local_get ("core_datasub_title_default"))."]]></text></default><attachments><value value='1' /><text><![CDATA[".(direct_local_get ("core_datasub_title_attachments"))."]]></text></attachments><downloads><value value='2' /><text><![CDATA[".(direct_local_get ("core_datasub_title_downloads"))."]]></text></downloads><links><value value='3' /><text><![CDATA[".(direct_local_get ("core_datasub_title_links"))."]]></text></links>
</evars>");

/* -------------------------------------------------------------------------
Build the form
------------------------------------------------------------------------- */

		$direct_classes['formbuilder']->entry_add_text ("dtitle",(direct_local_get ("discuss_board")),true,"s",1,255);

		if ($direct_settings['formtags_overview_document_url']) { $direct_classes['formbuilder']->entry_add_jfield_textarea ("ddesc",(direct_local_get ("cp_discuss_board_desc")),false,"s",0,255,(direct_local_get ("formtags_overview_document")),(direct_linker ("url0",$direct_settings['formtags_overview_document_url']))); }
		else { $direct_classes['formbuilder']->entry_add_jfield_textarea ("ddesc",(direct_local_get ("cp_discuss_board_desc")),false,"s",0,255); }

		$direct_classes['formbuilder']->entry_add ("spacer");
		$direct_classes['formbuilder']->entry_add_select ("dzone",(direct_local_get ("cp_discuss_board_zone")),false,"s");
		$direct_classes['formbuilder']->entry_add_select ("dpublic",(direct_local_get ("cp_discuss_board_public")),false,"s");
		$direct_classes['formbuilder']->entry_add_select ("dviews_count",(direct_local_get ("cp_discuss_board_views_counted")),false,"s");
		$direct_classes['formbuilder']->entry_add_select ("dlocked",(direct_local_get ("cp_discuss_board_locked")),false,"s");
		$direct_classes['formbuilder']->entry_add ("spacer");
		$direct_classes['formbuilder']->entry_add_select ("dsubs_allowed",(direct_local_get ("core_datasub_allowed")),true,"s");
		$direct_classes['formbuilder']->entry_add_select ("dsubs_hidden",(direct_local_get ("core_datasub_hide")),true,"s");
		$direct_classes['formbuilder']->entry_add_radio ("dsubs_type",(direct_local_get ("core_datasub_type")),true);

		$direct_cachedata['output_formelements'] = $direct_classes['formbuilder']->form_get ($g_mode_save);

		if (($g_mode_save)&&($direct_classes['formbuilder']->check_result))
		{
/* -------------------------------------------------------------------------
Save data edited
------------------------------------------------------------------------- */

			if ($g_did)
			{
				$g_board_structure_data_array = $g_board_object->get_structure ("","",false);
				$g_continue_check = is_array ($g_board_structure_data_array);
			}
			else { $g_board_structure_data_array = array ("structured" => NULL); }

			if ($g_continue_check)
			{
				$direct_cachedata['i_ddesc'] = direct_output_smiley_encode ($direct_classes['formtags']->encode ($direct_cachedata['i_ddesc']));
				$direct_cachedata['i_dsubs_allowed'] = ($direct_cachedata['i_dsubs_allowed'] ? 1 : 0);

				$g_board_id = uniqid ("");
				$g_board_type = ($direct_cachedata['i_dzone'] ? 1 : 3);

$g_insert_array = array (
"ddbdatalinker_id" => $g_board_id,
"ddbdatalinker_sid" => "cb41ecf6e90a594dcea60b6140251d62",
// md5 ("discuss")
"ddbdatalinker_type" => $g_board_type,
"ddbdatalinker_subs" => 0,
"ddbdatalinker_objects" => 0,
"ddbdatalinker_sorting_date" => 0,
"ddbdatalinker_symbol" => "",
"ddbdatalinker_title" => $direct_cachedata['i_dtitle'],
"ddbdatalinker_views_count" => $direct_cachedata['i_dviews_count'],
"ddbdiscuss_boards_data" => $direct_cachedata['i_ddesc'],
"ddbdiscuss_boards_posts" => 0,
"ddbdiscuss_boards_public" => $direct_cachedata['i_dpublic'],
"ddbdiscuss_boards_locked" => $direct_cachedata['i_dlocked']
);

				if ($direct_cachedata['i_dsubs_allowed'])
				{
					$g_insert_array['ddbdatalinker_datasubs_type'] = $direct_cachedata['i_dsubs_type'];
					$g_insert_array['ddbdatalinker_datasubs_hide'] = $direct_cachedata['i_dsubs_hidden'];
					$g_insert_array['ddbdatalinker_datasubs_new'] = 1;
				}

				if ($g_eid) { $g_board_id = $g_eid; }
				elseif (($g_position < 1)&&($g_board_structure_data_array['structured'] === NULL)) { $g_board_id = $g_did; }

				$g_board_structure_list_array = direct_datalinker_structure_add ($g_board_structure_data_array['structured'],$g_position,$g_board_id,$g_insert_array);
				$g_continue_check = is_array ($g_board_structure_list_array);
			}

			if ($g_continue_check)
			{
				$direct_classes['db']->v_transaction_begin ();

				foreach ($g_board_structure_list_array as $g_target_position => $g_board_structure_element)
				{
					if ($g_continue_check)
					{
						if (is_array ($g_board_structure_element))
						{
							$g_board_structure_element['ddbdatalinker_position'] = $g_target_position;
							$g_continue_check = $g_board_object->set_insert ($g_board_structure_element);
						}
						else
						{
							$g_board_id = array_pop (explode (":",$g_board_structure_element));
							$g_board_array =& $g_board_structure_data_array['objects'][$g_board_id];

							if ($g_board_array['ddbdatalinker_position'] != $g_target_position)
							{
								$g_board_array['ddbdatalinker_position'] = $g_target_position;
								$g_datalinker_object = new direct_datalinker ();
								$g_continue_check = $g_datalinker_object->set_update ($g_board_array,true,false);
							}
						}
					}
				}

				if ($g_continue_check) { $g_continue_check = $direct_classes['db']->v_transaction_commit (); }
				else { $direct_classes['db']->v_transaction_rollback (); }
			}

			if ($g_continue_check)
			{
				if (!$g_did) { $g_did = $g_insert_array['ddbdatalinker_id']; }

				$direct_cachedata['output_job'] = direct_local_get ("cp_discuss_board_new");
				$direct_cachedata['output_job_desc'] = direct_local_get ("cp_discuss_done_board_new");
				$direct_cachedata['output_jsjump'] = 2000;

				$direct_cachedata['output_pagetarget'] = direct_linker ("url0","m=dataport&s=swgap;cp;discuss;board&a=list&dsd=ddid+{$g_did}++dtheme+1");
				$direct_cachedata['output_scripttarget'] = direct_linker ("url0","m=dataport&s=swgap;cp;discuss;board&a=list&dsd=ddid+{$g_did}++dtheme+1",false);

				direct_output_related_manager ("cp_discuss_board_new_{$g_did}_form_save","post_module_service_action");
				$direct_classes['output']->oset ("default","done");
				$direct_classes['output']->options_flush (true);
				$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
				$direct_classes['output']->page_show ($direct_cachedata['output_job']);
			}
			else { $direct_classes['error_functions']->error_page ("fatal","core_database_error","sWG/#echo(__FILEPATH__)# _a=new-save_ (#echo(__LINE__)#)"); }
		}
		else
		{
/* -------------------------------------------------------------------------
View form
------------------------------------------------------------------------- */

			$direct_cachedata['output_formbutton'] = direct_local_get ("core_save");
			$direct_cachedata['output_formtarget'] = "m=cp&s=discuss;board&a=new-save&dsd=ddid+{$g_did}++ddeid+{$g_eid}++dposition+".$g_position;
			$direct_cachedata['output_formtitle'] = direct_local_get ("cp_discuss_board_new");

			direct_output_related_manager ("cp_discuss_board_new_{$g_did}_form","post_module_service_action");
			$direct_classes['output']->oset ("default","form");
			$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
			$direct_classes['output']->page_show ($direct_cachedata['output_formtitle']);
		}
	}
	else { $direct_classes['error_functions']->error_page ("standard","discuss_did_invalid","sWG/#echo(__FILEPATH__)# _a={$direct_settings['a']}_ (#echo(__LINE__)#)"); }
	//j// EOA
	}
	else { $direct_classes['error_functions']->error_page ("login","core_access_denied","sWG/#echo(__FILEPATH__)# _a={$direct_settings['a']}_ (#echo(__LINE__)#)"); }
	}

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// EOS
}

//j// EOF
?>