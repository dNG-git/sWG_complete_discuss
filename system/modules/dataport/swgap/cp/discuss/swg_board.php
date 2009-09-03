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
* dataport/swgap/cp/discuss/swg_board.php
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
* @subpackage datacenter
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

if (!isset ($direct_settings['serviceicon_cp_discuss_board_link_new'])) { $direct_settings['serviceicon_cp_discuss_board_link_new'] = "mini_default_option.png"; }
if (!isset ($direct_settings['serviceicon_cp_discuss_board_new'])) { $direct_settings['serviceicon_cp_discuss_board_new'] = "mini_default_option.png"; }
if (!isset ($direct_settings['serviceicon_default_back'])) { $direct_settings['serviceicon_default_back'] = "mini_default_back.png"; }
$direct_settings['additional_copyright'][] = array ("Module discuss #echo(sWGdiscussVersion)# - (C) ","http://www.direct-netware.de/redirect.php?swg","direct Netware Group"," - All rights reserved");

//j// BOS
switch ($direct_settings['a'])
{
//j// $direct_settings['a'] == "list"
case "list":
{
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a=list_ (#echo(__LINE__)#)"); }

	$direct_cachedata['output_did'] = (isset ($direct_settings['dsd']['ddid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['ddid'])) : "");

	if ((isset ($direct_settings['dsd']['dtheme']))&&($direct_settings['dsd']['dtheme']))
	{
		$g_dtheme = true;

		if ($direct_settings['dsd']['dtheme'] == 2)
		{
			$direct_cachedata['output_dtheme_mode'] = 2;
			$g_dtheme_embedded = true;
		}
		else
		{
			$direct_cachedata['output_dtheme_mode'] = 1;
			$g_dtheme_embedded = false;
		}

		$direct_cachedata['page_this'] = "m=dataport&s=swgap;cp;discuss;board&a=list&dsd=ddid+{$direct_cachedata['output_did']}++dtheme+".$direct_cachedata['output_dtheme_mode'];
		$direct_cachedata['page_backlink'] = "m=cp&s=discuss;index&a=boards";
		$direct_cachedata['page_homelink'] = "m=cp";

		$g_continue_check = $direct_classes['kernel']->service_init_default ();
	}
	else
	{
		$direct_cachedata['output_dtheme_mode'] = 0;
		$g_dtheme = false;
		$g_dtheme_embedded = false;

		$g_continue_check = $direct_classes['kernel']->service_init_rboolean ();
	}

	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->settings_get ($direct_settings['path_data']."/settings/swg_cp.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->settings_get ($direct_settings['path_data']."/settings/swg_discuss.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_board.php"); }

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
			if ($direct_classes['kernel']->v_group_user_check_right ("cp_discuss_manage_board_".$direct_cachedata['output_did']))
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
	if ($g_dtheme)
	{
		direct_output_related_manager ("cp_discuss_board_list_".$direct_cachedata['output_did'],"pre_module_service_action");
		$direct_classes['kernel']->service_https ($direct_settings['cp_https_discuss_manage_boards'],$direct_cachedata['page_this']);
	}
	else { direct_output_related_manager ("cp_discuss_board_list_".$direct_cachedata['output_did'],"pre_module_service_action_ajax"); }

	direct_local_integration ("cp_discuss");
	direct_local_integration ("discuss");

	direct_class_init ("output");
	if ($g_dtheme) { $direct_classes['output']->options_insert (2,"servicemenu",$direct_cachedata['page_backlink'],(direct_local_get ("core_back")),$direct_settings['serviceicon_default_back'],"url0"); }

	$g_board_object = new direct_discuss_board ();

	$g_board_array = ($g_board_object ? $g_board_object->get ($direct_cachedata['output_did']) : NULL);
	$g_board_structure_data_array = ((is_array ($g_board_array)) ? $g_board_object->get_structure ("cb41ecf6e90a594dcea60b6140251d62",(array (1,2,3,4)),false) : NULL);

	$direct_cachedata['output_boards'] = array ();

	if (is_array ($g_board_structure_data_array))
	{
		$g_board_structure_list_array = explode ("\n",$g_board_structure_data_array['structured']);
		$g_position = 0;

		foreach ($g_board_structure_list_array as $g_board_structure_element)
		{
			$g_board_tree_array = explode (":",$g_board_structure_element);
			$g_board_id = array_pop ($g_board_tree_array);
			$g_board_structure_array =& $direct_cachedata['output_boards'];

			if ($g_board_id != NULL)
			{
				foreach ($g_board_tree_array as $g_board_tree_element)
				{
					if (isset ($g_board_structure_array['subs']))
					{
						if (!isset ($g_board_structure_array['subs'][$g_board_tree_element])) { $g_board_structure_array['subs'][$g_board_tree_element] = array (); }
						$g_board_structure_array =& $g_board_structure_array['subs'][$g_board_tree_element];
					}
					else
					{
						$g_board_structure_array['subs'] = array ($g_board_tree_element => array ());
						$g_board_structure_array =& $g_board_structure_array['subs'][$g_board_tree_element];
					}
				}
			}
			else { $g_continue_check = false; }

			if (isset ($g_board_structure_data_array['objects'][$g_board_id])) { $g_board_entry_array = $g_board_structure_data_array['objects'][$g_board_id]; }
			elseif ($g_board_array['ddbdatalinker_id'] == $g_board_id) { $g_board_entry_array = $g_board_array; }
			else { $g_board_entry_array = NULL; }

			if (($g_continue_check)&&(is_array ($g_board_entry_array)))
			{
				$g_datalinker_object = new direct_datalinker ();

				if ($g_datalinker_object->set ($g_board_entry_array))
				{
					if (isset ($g_board_structure_array['subs']))
					{
						if (isset ($g_board_structure_array['subs'][$g_board_id])) { $g_board_structure_array['subs'][$g_board_id]['data'] = $g_datalinker_object->parse (); }
						else { $g_board_structure_array['subs'][$g_board_id] = array ("data" => $g_datalinker_object->parse ()); }
					}
					else { $g_board_structure_array['subs'] = array ($g_board_id => array ("data" => $g_datalinker_object->parse ())); }

					$g_board_structure_array =& $g_board_structure_array['subs'][$g_board_id];

					$g_board_structure_array['data']['pageurl_up'] = direct_linker ("url0","m=dataport&s=swgap;cp;discuss;board&a=move&dsd=ddid+{$direct_cachedata['output_did']}++ddeid+{$g_board_entry_array['ddbdatalinker_id']}++dposition+$g_position++ddirection+up++dtheme+1");
					$g_board_structure_array['data']['pageurl_down'] = direct_linker ("url0","m=dataport&s=swgap;cp;discuss;board&a=move&dsd=ddid+{$direct_cachedata['output_did']}++ddeid+{$g_board_entry_array['ddbdatalinker_id']}++dposition+$g_position++ddirection+down++dtheme+1");

					if (($g_board_entry_array['ddbdatalinker_id'] != $g_board_entry_array['ddbdatalinker_id_object'])||($g_right_write_check)) { $g_board_structure_array['data']['pageurl_edit'] = direct_linker ("url0","m=cp&s=discuss;board&a=edit&dsd=ddid+{$direct_cachedata['output_did']}++ddeid+".$g_board_entry_array['ddbdatalinker_id']); }
					if ($g_right_write_check) { $g_board_structure_array['data']['pageurl_new'] = direct_linker ("url0","m=cp&s=discuss;board&a=new&dsd=ddid+{$direct_cachedata['output_did']}++ddeid+{$g_board_entry_array['ddbdatalinker_id']}++dposition+".$g_position); }
					$g_board_structure_array['data']['pageurl_link_new'] = direct_linker ("url0","m=cp&s=discuss;board&a=link_new&dsd=ddid+{$direct_cachedata['output_did']}++ddeid+{$g_board_entry_array['ddbdatalinker_id']}++dposition+".$g_position);

					if ($g_board_entry_array['ddbdatalinker_id'] == $g_board_entry_array['ddbdatalinker_id_object'])
					{
						if (($g_position)&&($g_right_write_check)) { $g_board_structure_array['data']['pageurl_delete'] = direct_linker ("url0","m=cp&s=discuss;board&a=delete&dsd=ddid+{$direct_cachedata['output_did']}++ddeid+".$g_board_entry_array['ddbdatalinker_id']); }
					}
					else { $g_board_structure_array['data']['pageurl_link_delete'] = direct_linker ("url0","m=cp&s=discuss;board&a=link_delete&dsd=ddid+{$direct_cachedata['output_did']}++ddeid+".$g_board_entry_array['ddbdatalinker_id']); }

					$g_board_structure_array['data']['position'] = $g_position;
					$g_position++;
				}
			}
			else { $g_continue_check = false; }
		}

		if ($direct_cachedata['output_boards']['subs'][$direct_cachedata['output_did']]) { $direct_cachedata['output_boards'] = $direct_cachedata['output_boards']['subs'][$direct_cachedata['output_did']]; }
		elseif ($f_position < 1)
		{
			if ($g_right_write_check) { $direct_classes['output']->options_insert (1,"servicemenu","m=cp&s=discuss;board&a=new&dsd=ddid+{$direct_cachedata['output_did']}++dposition+0",(direct_local_get ("cp_discuss_board_new")),$direct_settings['serviceicon_cp_discuss_board_new'],"url0"); }
			$direct_classes['output']->options_insert (1,"servicemenu","m=cp&s=discuss;board&a=link_new&dsd=ddid+{$direct_cachedata['output_did']}++dposition+0",(direct_local_get ("cp_discuss_board_link_new")),$direct_settings['serviceicon_cp_discuss_board_link_new'],"url0");
		}

		if ($g_dtheme)
		{
			direct_output_related_manager ("cp_discuss_board_list_".$direct_cachedata['output_did'],"post_module_service_action");
			$direct_classes['output']->oset ("cp_discuss","board_list");
			$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
			$direct_classes['output']->page_show (direct_local_get ("cp_discuss_boards_edit"));
		}
		else
		{
			$direct_classes['output']->header (false);
			header ("Content-type: text/xml; charset=".$direct_local['lang_charset']);

echo ("<?xml version='1.0' encoding='$direct_local[lang_charset]' ?>
".(direct_output_smiley_decode ($direct_classes['output']->oset_content ("cp_discuss_embedded","ajax_board_list"))));
		}
	}
	else { $direct_classes['error_functions']->error_page ("standard","discuss_did_invalid","sWG/#echo(__FILEPATH__)# _a=list_ (#echo(__LINE__)#)"); }
	//j// EOA
	}
	elseif ($g_dtheme) { $direct_classes['error_functions']->error_page ("login","core_access_denied","sWG/#echo(__FILEPATH__)# _a=list_ (#echo(__LINE__)#)"); }
	}

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// $direct_settings['a'] == "move"
case "move":
{
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a=move_ (#echo(__LINE__)#)"); }

	$g_did = (isset ($direct_settings['dsd']['ddid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['ddid'])) : "");
	$g_eid = (isset ($direct_settings['dsd']['ddeid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['ddeid'])) : "");
	$g_position = (isset ($direct_settings['dsd']['dposition']) ? ($direct_classes['basic_functions']->inputfilter_number ($direct_settings['dsd']['dposition'])) : "");
	$g_direction = (isset ($direct_settings['dsd']['ddirection']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['ddirection'])) : "");

	if ((isset ($direct_settings['dsd']['dtheme']))&&($direct_settings['dsd']['dtheme']))
	{
		$g_dtheme = true;
		$g_dtheme_mode = (($direct_settings['dsd']['dtheme'] == 2) ? 2 : 1);

		$direct_cachedata['page_this'] = "";
		$direct_cachedata['page_backlink'] = "m=dataport&s=swgap;cp;discuss;board&a=list&dsd=ddid+{$g_did}++dtheme+".$g_dtheme_mode;
		$direct_cachedata['page_homelink'] = "m=cp&s=discuss;index";

		$g_continue_check = $direct_classes['kernel']->service_init_default ();
	}
	else
	{
		$g_dtheme = false;
		$g_dtheme_mode = 0;

		$g_continue_check = $direct_classes['kernel']->service_init_rboolean ();
	}

	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->settings_get ($direct_settings['path_data']."/settings/swg_cp.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->settings_get ($direct_settings['path_data']."/settings/swg_discuss.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_datalinker.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_board.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/functions/swg_datalinker_structure.php"); }

	if ($g_continue_check)
	{
		$g_rights_check = (($direct_settings['user']['type'] == "ad") ? true : false);
		if ((!$g_rights_check)&&($direct_classes['kernel']->v_group_user_check_right ("cp_access"))&&(($direct_classes['kernel']->v_group_user_check_right ("cp_discuss_manage_board_".$g_did))||($direct_classes['kernel']->v_group_user_check_right ("cp_discuss_manage_board_{$g_did}_links")))) { $g_rights_check = true; }
	}

	if ($g_continue_check)
	{
	if ($g_rights_check)
	{
	//j// BOA
	if ($g_dtheme) { direct_output_related_manager ("cp_discuss_board_move_".$g_did,"pre_module_service_action"); }
	else { direct_output_related_manager ("cp_discuss_board_move_".$g_did,"pre_module_service_action_ajax"); }

	direct_local_integration ("cp_discuss");
	direct_local_integration ("discuss");

	$g_board_object = new direct_discuss_board ();

	$g_board_array = ($g_board_object ? $g_board_object->get ($g_did) : NULL);
	$g_board_structure_data_array = ((is_array ($g_board_array)) ? $g_board_object->get_structure ("","",false) : NULL);

	if (is_array ($g_board_structure_data_array))
	{
		$g_board_structure_array = direct_datalinker_structure_move ($g_board_structure_data_array['structured'],$g_position,$g_eid,$g_direction);

		if (is_array ($g_board_structure_array))
		{
			foreach ($g_board_structure_array as $g_target_position => $g_board_structure_element)
			{
				if ($g_continue_check)
				{
					$g_change_check = false;

					if (is_array ($g_board_structure_element))
					{
						$g_change_check = true;
						$g_board_structure_array =& $g_board_structure_data_array['objects'][$g_board_structure_element[1]];
						$g_board_structure_array['ddbdatalinker_id_parent'] = $g_board_structure_element[0];
						$g_board_structure_array['ddbdatalinker_position'] = $g_target_position;

						if (($g_board_structure_array['ddbdatalinker_type'] == 3)&&(($g_board_structure_element[2])||(stripos ($g_board_structure_filtered,":{$g_board_structure_element[1]}:") !== false))) { $g_board_structure_array['ddbdatalinker_type'] = 2; }
						elseif (($g_board_structure_array['ddbdatalinker_type'] == 2)&&(!$g_board_structure_element[2])&&(stripos ($g_board_structure_filtered,":{$g_board_structure_element[1]}:") === false)) { $g_board_structure_array['ddbdatalinker_type'] = 3; }
					}
					else
					{
						$g_board_id = array_pop (explode (":",$g_board_structure_element));
						$g_board_structure_array =& $g_board_structure_data_array['objects'][$g_board_id];

						if (($g_board_structure_array['ddbdatalinker_type'] == 3)&&(stripos ($g_board_structure_filtered,":{$g_board_id}:") !== false))
						{
							$g_change_check = true;
							$g_board_structure_array['ddbdatalinker_type'] = 2;
						}
						elseif (($g_board_structure_array['ddbdatalinker_type'] == 2)&&(stripos ($g_board_structure_filtered,":{$g_board_id}:") === false))
						{
							$g_change_check = true;
							$g_board_structure_array['ddbdatalinker_type'] = 3;
						}

						if ($g_board_structure_array['ddbdatalinker_position'] != $g_target_position)
						{
							$g_change_check = true;
							$g_board_structure_array['ddbdatalinker_position'] = $g_target_position;
						}
					}

					if ($g_change_check)
					{
						$g_datalinker_object = new direct_datalinker ();
						$g_continue_check = $g_datalinker_object->set_update ($g_board_structure_array,true,false);
					}
				}
			}
		}
	}
	//j// EOA
	}
	}

	direct_class_init ("output");
	$direct_classes['output']->redirect (direct_linker ("url1","m=dataport&s=swgap;cp;discuss;board&a=list&dsd=ddid+{$g_did}++dtheme+".$g_dtheme_mode,false));

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// EOS
}

//j// EOF
?>