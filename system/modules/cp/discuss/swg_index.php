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
* cp/discuss/swg_index.php
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
* @subpackage cp_nim
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
if (!isset ($direct_settings['serviceicon_default_back'])) { $direct_settings['serviceicon_default_back'] = "mini_default_back.png"; }
$direct_settings['additional_copyright'][] = array ("Module discuss #echo(sWGdiscussVersion)# - (C) ","http://www.direct-netware.de/redirect.php?swg","direct Netware Group"," - All rights reserved");

if ($direct_settings['a'] == "index") { $direct_settings['a'] = "boards"; }
//j// BOS
switch ($direct_settings['a'])
{
//j// $direct_settings['a'] == "boards"
case "boards":
{
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a=boards_ (#echo(__LINE__)#)"); }

	$direct_cachedata['page_this'] = "m=cp&s=discuss;index&a=boards";
	$direct_cachedata['page_backlink'] = "m=cp";
	$direct_cachedata['page_homelink'] = "m=cp";

	if ($direct_classes['kernel']->service_init_default ())
	{
	if (($direct_classes['kernel']->v_group_user_check_right ("cp_discuss_manage_boards"))||($direct_settings['user']['type'] == "ad"))
	{
	//j// BOA
	direct_output_related_manager ("cp_discuss_index_boards","pre_module_service_action");
	$direct_classes['kernel']->service_https ($direct_settings['cp_https_discuss_manage_boards'],$direct_cachedata['page_this']);
	$direct_classes['basic_functions']->settings_get ($direct_settings['path_data']."/settings/swg_discuss.php",true);
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_datalinker_uhome.php");
	direct_local_integration ("cp_discuss");
	direct_local_integration ("discuss");

	direct_class_init ("output");
	$direct_classes['output']->options_insert (2,"servicemenu",$direct_cachedata['page_backlink'],(direct_local_get ("core_back")),$direct_settings['serviceicon_default_back'],"url0");

	$g_boards_array = NULL;
	$g_datalinker_object = new direct_datalinker_uhome ();

	if ($g_datalinker_object)
	{
		$g_datalinker_object->define_extra_conditions ("<element1 attribute='$direct_settings[datalinker_table].ddbdatalinker_type' value='4' type='number' operator='&lt;=' />");
		$g_boards_array = $g_datalinker_object->get_subs ("direct_datalinker",NULL,"","cb41ecf6e90a594dcea60b6140251d62","",0,"","position-asc");
	}

	if (is_array ($g_boards_array))
	{
		$direct_cachedata['output_boards'] = array ();

		foreach ($g_boards_array as $g_datalinker_object)
		{
			$g_datalinker_array = $g_datalinker_object->get ();
			$g_rights_check = (($direct_settings['user']['type'] == "ad") ? true : false);

			if ((!$g_rights_check)&&(($direct_classes['kernel']->v_group_user_check_right ("cp_discuss_manage_board_".$g_datalinker_array['ddbdatalinker_id_main']))||($direct_classes['kernel']->v_group_user_check_right ("cp_discuss_manage_board_{$g_datalinker_array['ddbdatalinker_id_main']}_links")))) { $g_rights_check = true; }

			if ($g_rights_check)
			{
				$g_parsed_array = $g_datalinker_object->parse ();
				$g_parsed_array['pageurl'] = direct_linker ("url0","m=dataport&s=swgap;cp;discuss;board&a=list&dsd=ddid+{$g_parsed_array['oid']}++dtheme+1");
				$direct_cachedata['output_boards'][] = $g_parsed_array;
			}
		}

		if ((empty ($direct_cachedata['output_boards']))||(count ($direct_cachedata['output_boards']) > 1))
		{
			direct_output_related_manager ("cp_discuss_index_boards","post_module_service_action");
			$direct_classes['output']->oset ("cp_discuss","boards");
			$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
			$direct_classes['output']->page_show (direct_local_get ("cp_discuss_boards"));
		}
		else
		{
			$g_page_link = direct_linker ("url1","m=dataport&s=swgap;cp;discuss;board&a=list&dsd=ddid+{$direct_cachedata['output_boards'][0]['oid']}++dtheme+1",false);
			$direct_classes['output']->redirect ($g_page_link);
		}
	}
	else { $direct_classes['error_functions']->error_page ("fatal","core_unknown_error","sWG/#echo(__FILEPATH__)# _a=boards_ (#echo(__LINE__)#)"); }
	//j// EOA
	}
	else { $direct_classes['error_functions']->error_page ("login","core_access_denied","sWG/#echo(__FILEPATH__)# _a=boards_ (#echo(__LINE__)#)"); }
	}

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// EOS
}

//j// EOF
?>