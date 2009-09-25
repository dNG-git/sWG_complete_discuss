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
* discuss/swg_index.php
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

if (!isset ($direct_settings['discuss_front_did'])) { $direct_settings['discuss_front_did'] = "boards"; }
if (!isset ($direct_settings['discuss_posts_per_page'])) { $direct_settings['discuss_posts_per_page'] = 10; }
if (!isset ($direct_settings['discuss_topics_latest_timeshifts'])) { $direct_settings['discuss_topics_latest_timeshifts'] = array (6,12,24,36,48,120,168); }
if (!isset ($direct_settings['discuss_topics_per_page'])) { $direct_settings['discuss_topics_per_page'] = 15; }
if (!isset ($direct_settings['discuss_vote'])) { $direct_settings['discuss_vote'] = false; }
if (!isset ($direct_settings['serviceicon_default_back'])) { $direct_settings['serviceicon_default_back'] = "mini_default_back.png"; }
if (!isset ($direct_settings['serviceicon_discuss_topic_edit'])) { $direct_settings['serviceicon_discuss_topic_edit'] = "mini_default_option.png"; }
if (!isset ($direct_settings['serviceicon_discuss_topic_new'])) { $direct_settings['serviceicon_discuss_topic_new'] = "mini_default_option.png"; }
if (!isset ($direct_settings['serviceicon_discuss_topics_latest'])) { $direct_settings['serviceicon_discuss_topics_latest'] = "mini_default_option.png"; }
if (!isset ($direct_settings['serviceicon_discuss_topics_latest_boards'])) { $direct_settings['serviceicon_discuss_topics_latest_boards'] = "mini_default_option.png"; }
if (!isset ($direct_settings['serviceicon_discuss_post_new'])) { $direct_settings['serviceicon_discuss_post_new'] = "mini_default_option.png"; }
if (!isset ($direct_settings['serviceicon_vote_new'])) { $direct_settings['serviceicon_vote_new'] = "mini_default_option.png"; }
$direct_settings['additional_copyright'][] = array ("Module discuss #echo(sWGdiscussVersion)# - (C) ","http://www.direct-netware.de/redirect.php?swg","direct Netware Group"," - All rights reserved");

if (($direct_settings['a'] == "boards")||($direct_settings['a'] == "index")) { $direct_settings['a'] = "topics"; }
//j// BOS
switch ($direct_settings['a'])
{
//j// $direct_settings['a'] == "jump"
case "jump":
{
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a=jump_ (#echo(__LINE__)#)"); }

	$g_data = (isset ($direct_settings['dsd']['idata']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['idata'])) : "");
	$g_did = (isset ($direct_settings['dsd']['ddid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['ddid'])) : "");
	$g_connector = (isset ($direct_settings['dsd']['connector']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['connector'])) : "");
	$g_source = (isset ($direct_settings['dsd']['source']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['source'])) : "");
	$g_target = (isset ($direct_settings['dsd']['target']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['target'])) : "");

	$g_data_array = explode (";",$g_data);

	if (count ($g_data_array) > 1)
	{
		$g_data_id = $g_data_array[1];

		switch ($g_data_array[0])
		{
		case "b":
		{
			$g_data_mode = "b";
			$g_data_target = (isset ($g_data_array[2]) ? $g_data_array[2] : 1);

			break 1;
		}
		case "p":
		{
			$g_data_mode = "p";
			$g_data_target = (isset ($g_data_array[2]) ? $g_data_array[2] : 1);

			break 1;
		}
		default:
		{
			$g_data_mode = "t";
			$g_data_target = (isset ($g_data_array[2]) ? $g_data_array[2] : "latest");
		}
		}
	}
	else
	{
		$g_data_id = (isset ($g_data_array[0]) ? $g_data_array[0] : "");
		$g_data_mode = "t";
		$g_data_target = "latest";
	}

	$g_did_dsd = (((strlen ($g_did))&&($g_data_mode != "b")) ? "ddid+{$g_did}++" : "");
	$g_connector_url = ($g_connector ? base64_decode ($g_connector) : "m=discuss&a=[a]&dsd={$g_did_dsd}[oid][page]");
	$g_source_url = ($g_source ? base64_decode ($g_source) : "m=contentor&a=view&dsd=[oid]");

	if ($g_target) { $g_target_url = base64_decode ($g_target); }
	else
	{
		$g_target = $g_source;
		$g_target_url = ($g_source ? $g_source_url : "");
	}

	switch ($g_data_mode)
	{
	case "b":
	{
		$g_back_link = (((!$g_source)&&($g_connector_url)) ? preg_replace (array ("#\[a\]#","#\[oid\]#","#\[(.*?)\]#"),(array ("topics","ddid+{$g_data_id}++","")),$g_connector_url) : str_replace ("[oid]","ddid+{$g_data_id}++",$g_source_url));
		break 1;
	}
	case "p":
	{
		$g_back_link = ($g_source ? str_replace ("[oid]","dpid+{$g_data_id}++",$g_source_url) : "");
		break 1;
	}
	default: { $g_back_link = (((!$g_source)&&($g_connector_url)) ? preg_replace (array ("#\[a\]#","#\[oid\]#","#\[(.*?)\]#"),(array ("posts","dtid+{$g_data_id}++","")),$g_connector_url) : str_replace ("[oid]","dtid+{$g_data_id}++",$g_source_url)); }
	}

	$direct_cachedata['page_this'] = "m=discuss&a=jump&dsd=idata+".$g_data;
	$direct_cachedata['page_backlink'] = $g_back_link;
	$direct_cachedata['page_homelink'] = $g_back_link;

	if (($direct_classes['kernel']->service_init_rboolean ())&&($g_data_id))
	{
	//j// BOA
	direct_class_init ("output");

	$g_target_url = direct_linker ("url0","m=datalinker&dsd=deid+{$g_data_id}++source+".(urlencode ($g_source)));

	switch ($g_data_array[0])
	{
	case "b":
	{
		$g_target_url = str_replace (array ("[a]","[oid]"),(array ("topics","ddid+{$g_data_id}++")),$g_connector_url);
		if (strlen ($g_data_target)) { $g_target_url = preg_replace ("#\[page(.*?)\]#","page+{$g_data_target}++",$g_target_url); }
		$g_target_url = preg_replace ("#\[(.*?)\]#","",$g_target_url);

		break 1;
	}
	case "p":
	{
		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_topic.php");
		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_post.php");

		$g_post_object = new direct_discuss_post ();

		$g_post_array = ($g_post_object ? $g_post_object->get ($g_data_id) : NULL);

		if (is_array ($g_post_array))
		{
			if ($g_post_array['ddbdatalinker_id_main'])
			{
				$g_target_position = 1;
				$g_topic_object = new direct_discuss_topic ();

				$g_topic_array = ($g_topic_object ? $g_topic_object->get ($g_post_array['ddbdatalinker_id_main']) : NULL);

				if (is_array ($g_topic_array))
				{
					$g_data_id = $g_post_array['ddbdatalinker_id_main'];

					$g_target_position = $g_topic_object->get_posts_since_date (($g_post_array['ddbdatalinker_sorting_date'] + 1),0,1,"",true);
					$g_target_position = ($g_topic_array['ddbdatalinker_objects'] - $g_target_position);

					if ($g_target_position > 0) { $g_data_target = ceil ($g_target_position / $direct_settings['discuss_posts_per_page']); }
					if ($g_target_position < 1) { $g_target_position = 1; }

					$g_target_position = ($g_target_position % $direct_settings['discuss_posts_per_page']);
					if ($g_target_position < 1) { $g_target_position = $direct_settings['discuss_posts_per_page']; }

					$g_target_url = str_replace (array ("[a]","[oid]"),(array ("posts","dtid+{$g_data_id}++")),$g_connector_url);
					$g_target_url = preg_replace (array ("#\[page(.*?)\]#","#\[(.*?)\]#"),(array ("page+{$g_data_target}++","")),$g_target_url)."#j".$g_target_position;
				}
			}
		}

		break 1;
	}
	default:
	{
		$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_topic.php");

		$g_topic_object = new direct_discuss_topic ();

		$g_topic_array = ($g_topic_object ? $g_topic_object->get ($g_data_id) : NULL);

		if (is_array ($g_topic_array))
		{
			if ($g_data_target == "first")
			{
				$g_data_target = 1;
				$g_target_position = 1;
			}
			else
			{
				if ($g_data_target == "latest")
				{
					$g_target_position = $g_topic_object->get_posts_since_date ($direct_cachedata['kernel_lastvisit'],0,1,"",true);
					if ($g_target_position) { $g_target_position--; }
				}
				else { $g_target_position = ($g_topic_array['ddbdatalinker_objects'] - 1); }

				$g_target_position = ($g_topic_array['ddbdatalinker_objects'] - $g_target_position);

				if ($g_target_position > 0) { $g_data_target = ceil ($g_target_position / $direct_settings['discuss_posts_per_page']); }
				if ($g_target_position < 1) { $g_target_position = 1; }

				$g_target_position = ($g_target_position % $direct_settings['discuss_posts_per_page']);
				if ($g_target_position < 1) { $g_target_position = $direct_settings['discuss_posts_per_page']; }
			}

			$g_target_url = str_replace (array ("[a]","[oid]"),(array ("posts","dtid+{$g_data_id}++")),$g_connector_url);
			$g_target_url = preg_replace (array ("#\[page(.*?)\]#","#\[(.*?)\]#"),(array ("page+{$g_data_target}++","")),$g_target_url)."#j".$g_target_position;
		}
	}
	}

	$direct_classes['output']->redirect (direct_linker ("url1",$g_target_url,false));
	//j// EOA
	}
	else { $direct_classes['error_functions']->error_page ("standard","core_unsupported_command","sWG/#echo(__FILEPATH__)# _a=jump_ (#echo(__LINE__)#)"); }

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// $direct_settings['a'] == "latest"
case "latest":
{
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a=latest_ (#echo(__LINE__)#)"); }

	$g_did = (isset ($direct_settings['dsd']['ddid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['ddid'])) : "");
	$direct_cachedata['output_hours'] = (isset ($direct_settings['dsd']['dhours']) ? ($direct_classes['basic_functions']->inputfilter_number ($direct_settings['dsd']['dhours'])) : 24);
	$direct_cachedata['output_page'] = (isset ($direct_settings['dsd']['page']) ? ($direct_classes['basic_functions']->inputfilter_number ($direct_settings['dsd']['page'])) : 1);

	if (!in_array ($direct_cachedata['output_hours'],$direct_settings['discuss_topics_latest_timeshifts'])) { $direct_cachedata['output_hours'] = 24; }

	$direct_cachedata['page_this'] = "m=discuss&a=latest&dsd=ddid+{$g_did}++dhours+{$direct_cachedata['output_hours']}++page+".$direct_cachedata['output_page'];
	$direct_cachedata['page_backlink'] = "m=discuss";
	$direct_cachedata['page_homelink'] = "m=discuss";

	if ($direct_classes['kernel']->service_init_default ())
	{
	//j// BOA
	direct_output_related_manager ("discuss_index_latest_".$g_did,"pre_module_service_action");
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_board.php");
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_topic.php");
	direct_local_integration ("discuss");

	direct_class_init ("output");
	$direct_classes['output']->servicemenu ("discuss");

	$g_board_object = new direct_discuss_board ();
	if ((!$g_did)&&(isset ($direct_settings['discuss_front_did']))&&($direct_settings['discuss_front_did'])) { $g_did = $direct_settings['discuss_front_did']; }
	$g_topics_array = NULL;

	$g_board_array = ($g_board_object ? $g_board_object->get ($g_did) : NULL);

	if (is_array ($g_board_array))
	{
		$direct_classes['output']->options_insert (1,"servicemenu","m=discuss&dsd=ddid+".$g_board_array['ddbdatalinker_id_main'],(direct_local_get ("discuss_topics_latest_boards")),$direct_settings['serviceicon_discuss_topics_latest_boards'],"url0");

		if ((!$direct_cachedata['output_page'])||($direct_cachedata['output_page'] < 1)) { $direct_cachedata['output_page'] = 1; }
		$g_offset = (($direct_cachedata['output_page'] - 1) * $direct_settings['discuss_topics_per_page']);

		$g_topics_array = $g_board_object->get_subboard_topics_since_date (($direct_cachedata['core_time'] - ($direct_cachedata['output_hours'] * 3600)),$g_offset,$direct_settings['discuss_topics_per_page']);
	}
	else { $direct_classes['output']->options_insert (1,"servicemenu","m=discuss",(direct_local_get ("discuss_topics_latest_boards")),$direct_settings['serviceicon_discuss_topics_latest_boards'],"url0"); }

	$direct_cachedata['output_page_url'] = "m=discuss&a=latest&dsd=ddid+{$g_did}++dhours+{$direct_cachedata['output_hours']}++";

	if (is_array ($g_topics_array))
	{
		$direct_cachedata['output_board'] = $g_board_object->parse ("m=discuss&a=[a]&dsd=[oid][page{$direct_cachedata['output_page']}]");

		$g_topics = $g_board_object->get_subboard_topics_since_date (($direct_cachedata['core_time'] - ($direct_cachedata['output_hours'] * 3600)),"","","",true);
		$direct_cachedata['output_pages'] = ceil ($g_topics / $direct_settings['discuss_topics_per_page']);

		$direct_cachedata['output_topics'] = array ();

		foreach ($g_topics_array as $g_topic_id => $g_topic_object)
		{
			$g_topic_array = $g_topic_object->parse ("m=discuss&a=[a]&dsd=ddid+{$g_did}++[oid][page]");

			if ($g_did)
			{
				$g_topic_array['pageurl'] = direct_linker ("url0","m=discuss&a=jump&dsd=idata+t;{$g_topic_id};first++ddid+".$g_did);
				$g_target_url = urlencode (base64_encode ("m=discuss&a=jump&dsd=idata+t;{$g_topic_id};first++ddid+".$g_did));
				$g_topic_array['pageurl_counted'] = direct_linker ("url0","m=datalinker&a=count&dsd=deid+{$g_topic_id}++source+".$g_target_url);

				$g_topic_array['last_post_jump'] = direct_linker ("url0","m=discuss&a=jump&dsd=idata+t;{$g_topic_id};latest++ddid+".$g_did);
				$g_target_url = urlencode (base64_encode ("m=discuss&a=jump&dsd=idata+t;{$g_topic_id};latest++ddid+".$g_did));
				$g_topic_array['last_post_jump_counted'] = direct_linker ("url0","m=datalinker&a=count&dsd=deid+{$g_topic_id}++source+".$g_target_url);
			}

			$direct_cachedata['output_topics'][] = $g_topic_array;
		}
	}
	else
	{
		$direct_cachedata['output_page'] = 1;
		$direct_cachedata['output_pages'] = 1;
		$direct_cachedata['output_topics'] = array ();
	}

	direct_output_related_manager ("discuss_index_topics_".$g_did,"post_module_service_action");
	$direct_classes['output']->oset ("discuss","topics_latest");
	$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
	$direct_classes['output']->header_elements ("<script language='JavaScript1.5' src='".(direct_linker_dynamic ("url0","s=cache&dsd=dfile+$direct_settings[path_mmedia]/swg_storage.php.js++dbid+".$direct_settings['product_buildid'],true,false))."' type='text/javascript'><!-- // DOM storage // --></script>","javascript_swg_storage.php.js");
	$direct_classes['output']->page_show (direct_local_get ("discuss_topics_latest"));
	//j// EOA
	}

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// $direct_settings['a'] == "posts"
case "posts":
{
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a=posts_ (#echo(__LINE__)#)"); }

	$g_did = (isset ($direct_settings['dsd']['ddid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['ddid'])) : "");
	$g_tid_d = (isset ($direct_settings['dsd']['dtid_d']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['dtid_d'])) : "");
	$g_tid = (isset ($direct_settings['dsd']['dtid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['dtid'])) : $g_tid_d);
	$direct_cachedata['output_page'] = (isset ($direct_settings['dsd']['page']) ? $direct_settings['dsd']['page'] : 1);
	$g_printview = (isset ($direct_settings['dsd']['printview']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['printview'])) : "");

	$g_did_dsd = ((strlen ($g_did)) ? "ddid+{$g_did}++" : "");
	if ($direct_cachedata['output_page'] != "last") { $direct_cachedata['output_page'] = $direct_classes['basic_functions']->inputfilter_number ($direct_cachedata['output_page']); }

	$direct_cachedata['page_this'] = "m=discuss&a=posts&dsd={$g_did_dsd}dtid+{$g_tid}++page+".$direct_cachedata['output_page'];
	$direct_cachedata['page_backlink'] = "m=discuss&a=posts&dsd={$g_did_dsd}dtid+".$g_tid;
	$direct_cachedata['page_homelink'] = "m=discuss&dsd=".$g_did_dsd;

	if ($direct_classes['kernel']->service_init_default ())
	{
	//j// BOA
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_board.php");
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_topic.php");
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_post.php");
	direct_local_integration ("discuss");

	if ((isset ($direct_settings['discuss_vote']))&&($direct_settings['discuss_vote']))
	{
		$direct_classes['basic_functions']->settings_get ($direct_settings['path_data']."/settings/swg_vote.php",true);
		direct_local_integration ("vote");
	}

	$g_board_array = NULL;
	$g_datasub_check = false;
	$g_posts_array = NULL;
	if ((!$g_tid)&&(isset ($direct_settings['discuss_front_tid']))&&($direct_settings['discuss_front_tid'])) { $g_tid = $direct_settings['discuss_front_tid']; }
	$g_topic_object = new direct_discuss_topic ();

	$g_topic_array = ($g_topic_object ? $g_topic_object->get ($g_tid) : NULL);

	if (is_array ($g_topic_array))
	{
		$direct_cachedata['output_pages'] = ceil ($g_topic_array['ddbdatalinker_objects'] / $direct_settings['discuss_posts_per_page']);
		if ($direct_cachedata['output_pages'] < 1) { $direct_cachedata['output_pages'] = 1; }

		if ($direct_cachedata['output_page'] == "last") { $direct_cachedata['output_page'] = $direct_cachedata['output_pages']; }
		elseif ((!$direct_cachedata['output_page'])||($direct_cachedata['output_page'] < 1)) { $direct_cachedata['output_page'] = 1; }

		$g_offset = (($direct_cachedata['output_page'] - 1) * $direct_settings['discuss_posts_per_page']);
		$g_posts_array = $g_topic_object->get_posts ($g_offset,$direct_settings['discuss_posts_per_page']);
	}

	if (is_array ($g_posts_array))
	{
		if ($g_topic_array['ddbdatalinker_id_main'])
		{
			$g_board_object = new direct_discuss_board ();

			if ($g_board_object)
			{
				if ($g_did) { $g_board_array = $g_board_object->get ($g_did); }

				if ((!is_array ($g_board_array))||($g_topic_array['ddbdatalinker_id_main'] != $g_board_array['ddbdatalinker_id_object']))
				{
					$g_board_array = $g_board_object->get ($g_topic_array['ddbdatalinker_id_main']);
					$g_did = $g_topic_array['ddbdatalinker_id_main'];
					$g_did_dsd = "";
				}
			}
		}
		else { $g_datasub_check = true; }
	}

	if ((!$g_datasub_check)&&(!is_array ($g_board_array))) { $direct_classes['error_functions']->error_page ("standard","discuss_tid_invalid","sWG/#echo(__FILEPATH__)# _a=posts_ (#echo(__LINE__)#)"); }
	elseif (($g_datasub_check)||($g_board_object->is_readable ()))
	{
		direct_output_related_manager ("discuss_index_posts_{$g_did}_".$g_tid,"pre_module_service_action");
		if ($g_printview) { direct_output_theme_subtype ("printview"); }
		direct_class_init ("output");

		$direct_cachedata['output_source'] = urlencode (base64_encode ($direct_cachedata['page_this']));

		if ($g_printview) { $direct_classes['output']->options_insert (2,"servicemenu",$direct_cachedata['page_this'],(direct_local_get ("core_back")),$direct_settings['serviceicon_default_back'],"url0"); }
		else
		{
			$direct_classes['output']->servicemenu ("discuss");

			$g_parent_writable_check = false;
			$g_parent_topic_writable_check = false;

			if ($g_datasub_check) { $g_parent_writable_check = true; }
			elseif ($g_board_object->is_writable ())
			{
				$g_parent_writable_check = true;
				$g_parent_topic_writable_check = true;
			}

			if ($g_datasub_check) { $g_moderator_check = (($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 3) ? true : false); }
			else { $g_moderator_check = $g_board_object->is_moderator (); }

			if ((($g_parent_topic_writable_check)&&($g_topic_object->is_editable ()))||($g_moderator_check)) { $direct_classes['output']->options_insert (1,"servicemenu","m=discuss&s=topic&a=edit&dsd={$g_did_dsd}dtid+".$g_tid,(direct_local_get ("discuss_topic_edit")),$direct_settings['serviceicon_discuss_topic_edit'],"url0"); }
			if ($g_parent_topic_writable_check) { $direct_classes['output']->options_insert (1,"servicemenu","m=discuss&s=topic&a=new&dsd=ddid+".$g_did,(direct_local_get ("discuss_topic_new")),$direct_settings['serviceicon_discuss_topic_new'],"url0"); }
			if ($g_topic_object->is_writable ()) { $direct_classes['output']->options_insert (1,"servicemenu","m=discuss&s=post&a=new&dsd={$g_did_dsd}dtid+".$g_tid,(direct_local_get ("discuss_post_new")),$direct_settings['serviceicon_discuss_post_new'],"url0"); }
			if (($direct_settings['discuss_vote'])&&($direct_settings['vote'])&&($g_parent_writable_check)&&($g_topic_object->is_editable ())&&((($g_topic_array['ddbdatalinker_subs'] < 1)&&($g_topic_object->is_sub_allowed ()))||($g_moderator_check))) { $direct_classes['output']->options_insert (1,"servicemenu","m=discuss&s=topic&a=vote&dsd={$g_did_dsd}dtid+{$g_tid}++page+".$direct_cachedata['output_page'],(direct_local_get ("vote_new")),$direct_settings['serviceicon_vote_new'],"url0"); }
			if (!$g_datasub_check) { $direct_classes['output']->options_insert (1,"servicemenu","m=discuss&a=latest&dsd=".$g_did_dsd,(direct_local_get ("discuss_topics_latest")),$direct_settings['serviceicon_discuss_topics_latest'],"url0"); }
		}

		$direct_cachedata['output_board'] = ($g_datasub_check ? NULL : $g_board_object->parse ("m=discuss&a=[a]&dsd=[oid][page]"));
		$direct_cachedata['output_topic'] = $g_topic_object->parse ("m=discuss&a=[a]&dsd={$g_did_dsd}[oid][page{$direct_cachedata['output_page']}]");
		$direct_cachedata['output_posts'] = array ();
		$g_locked_check = $g_topic_object->is_locked ();
		$g_post = 1;

		foreach ($g_posts_array as $g_post_object)
		{
/* -------------------------------------------------------------------------
Wer are definately in the board where the user has some kind of access. We
will allow him to read posts here without checking for the group right.
------------------------------------------------------------------------- */

			if ($g_post_object->is_readable_group ()) { $g_post_object->define_readable (true); }
			$direct_cachedata['output_posts'][$g_post] = $g_post_object->parse ("m=discuss&a=[a]&dsd={$g_did_dsd}[oid][page]",$g_locked_check,$g_moderator_check);
			$direct_cachedata['output_posts'][$g_post]['jumptarget'] = "j".$g_post;
			$g_post++;
		}

		direct_output_related_manager ("discuss_index_posts_{$g_did}_".$g_tid);
		$direct_classes['output']->oset ("discuss","posts");
		$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
		$direct_classes['output']->header_elements ("<script language='JavaScript1.5' src='".(direct_linker_dynamic ("url0","s=cache&dsd=dfile+$direct_settings[path_mmedia]/swg_storage.php.js++dbid+".$direct_settings['product_buildid'],true,false))."' type='text/javascript'><!-- // DOM storage // --></script>","javascript_swg_storage.php.js");
		$direct_classes['output']->page_show ($direct_cachedata['output_topic']['title']);
	}
	else { $direct_classes['error_functions']->error_page ("login","core_access_denied","sWG/#echo(__FILEPATH__)# _a=posts_ (#echo(__LINE__)#)"); }
	//j// EOA
	}

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// $direct_settings['a'] == "topics"
case "topics":
{
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a=topics_ (#echo(__LINE__)#)"); }

	$g_did_d = (isset ($direct_settings['dsd']['ddid_d']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['ddid_d'])) : "");
	$g_did = (isset ($direct_settings['dsd']['ddid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['ddid'])) : $g_did_d);
	$direct_cachedata['output_page'] = (isset ($direct_settings['dsd']['page']) ? ($direct_classes['basic_functions']->inputfilter_number ($direct_settings['dsd']['page'])) : 1);

	$direct_cachedata['page_this'] = "m=discuss&a=topics&dsd=ddid+{$g_did}++page+".$direct_cachedata['output_page'];
	$direct_cachedata['page_backlink'] = "m=discuss";
	$direct_cachedata['page_homelink'] = "m=discuss";

	if ($direct_classes['kernel']->service_init_default ())
	{
	//j// BOA
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_board.php");
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_topic.php");
	direct_local_integration ("discuss");

	$g_board_object = new direct_discuss_board ();
	if ((!$g_did)&&(isset ($direct_settings['discuss_front_did']))&&($direct_settings['discuss_front_did'])) { $g_did = $direct_settings['discuss_front_did']; }
	$g_topics_array = NULL;

	$g_board_array = ($g_board_object ? $g_board_object->get ($g_did) : NULL);

	if (is_array ($g_board_array))
	{
		if ($g_board_array['ddbdatalinker_type'] > 1)
		{
			if ((!$direct_cachedata['output_page'])||($direct_cachedata['output_page'] < 1)) { $direct_cachedata['output_page'] = 1; }
			$g_offset = (($direct_cachedata['output_page'] - 1) * $direct_settings['discuss_topics_per_page']);

			$g_topics_array = $g_board_object->get_topics ($g_offset,$direct_settings['discuss_topics_per_page'],"last-time-sticky-desc");
		}
		else { $g_topics_array = array (); }
	}

	if (!is_array ($g_topics_array)) { $direct_classes['error_functions']->error_page ("standard","discuss_did_invalid","sWG/#echo(__FILEPATH__)# _a=topics_ (#echo(__LINE__)#)"); }
	elseif ($g_board_object->is_readable ())
	{
		direct_output_related_manager ("discuss_index_topics_".$g_did,"pre_module_service_action");
		direct_class_init ("output");
		$direct_classes['output']->servicemenu ("discuss");

		if ($g_board_object->is_writable ()) { $direct_classes['output']->options_insert (1,"servicemenu","m=discuss&s=topic&a=new&dsd=ddid+".$g_board_array['ddbdatalinker_id'],(direct_local_get ("discuss_topic_new")),$direct_settings['serviceicon_discuss_topic_new'],"url0"); }
		$direct_classes['output']->options_insert (1,"servicemenu","m=discuss&a=latest&dsd=ddid+".$g_board_array['ddbdatalinker_id_main'],(direct_local_get ("discuss_topics_latest")),$direct_settings['serviceicon_discuss_topics_latest'],"url0");

		$direct_cachedata['output_boards'] = array ();
		$g_subboards_array = $g_board_object->get_subboards ();

		if (!empty ($g_subboards_array))
		{
			$g_board_object->reflect_subboards ($g_did);
			foreach ($g_subboards_array as $g_subboard_object) { $direct_cachedata['output_boards'][] = $g_subboard_object->parse ("m=discuss&a=[a]&dsd=[oid][page]"); }
		}

		$direct_cachedata['output_board'] = $g_board_object->parse ("m=discuss&a=[a]&dsd=[oid][page{$direct_cachedata['output_page']}]");

		$direct_cachedata['output_page_url'] = "m=discuss&a=topics&dsd=ddid+{$g_did}++";
		$direct_cachedata['output_pages'] = ceil ($g_board_array['ddbdatalinker_objects'] / $direct_settings['discuss_topics_per_page']);

		direct_output_related_manager ("discuss_index_topics_".$g_did,"post_module_service_action");
		$direct_classes['output']->header_elements ("<link rel='alternate' type='application/atom+xml' href='".(direct_linker_dynamic ("url1","m=dataport&s=atom;discuss;topics&a=latest&dsd=ddid+".$g_board_array['ddbdatalinker_id_main'],true,false))."' title=\"".(direct_local_get ("discuss_topics_latest"))."\" />");
		if ($g_board_array['ddbdatalinker_id'] != $g_board_array['ddbdatalinker_id_main']) { $direct_classes['output']->header_elements ("<link rel='alternate' type='application/atom+xml' href='".(direct_linker_dynamic ("url1","m=dataport&s=atom;discuss;topics&a=latest&dsd=ddid+".$g_board_array['ddbdatalinker_id'],true,false))."' title=\"{$direct_cachedata['output_board']['title']} (".(direct_local_get ("discuss_topics_latest")).")\" />"); }

		if ($g_board_array['ddbdatalinker_type'] > 1)
		{
			$direct_classes['output']->header_elements ("<link rel='alternate' type='application/atom+xml' href='".(direct_linker_dynamic ("url1","m=dataport&s=atom;discuss;topics&a=topics&dsd=ddid+".$g_board_array['ddbdatalinker_id'],true,false))."' title=\"{$direct_cachedata['output_board']['title']}\" />");

			$direct_cachedata['output_topics'] = array ();
			foreach ($g_topics_array as $g_topic_object) { $direct_cachedata['output_topics'][] = $g_topic_object->parse ("m=discuss&a=[a]&dsd=ddid+{$g_board_array['ddbdatalinker_id']}++[oid][page]"); }

			$direct_classes['output']->oset ("discuss","topics");
		}
		else { $direct_classes['output']->oset ("discuss","boards"); }

		$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
		$direct_classes['output']->header_elements ("<script language='JavaScript1.5' src='".(direct_linker_dynamic ("url0","s=cache&dsd=dfile+$direct_settings[path_mmedia]/swg_storage.php.js++dbid+".$direct_settings['product_buildid'],true,false))."' type='text/javascript'><!-- // DOM storage // --></script>","javascript_swg_storage.php.js");
		$direct_classes['output']->page_show ($direct_cachedata['output_board']['title']);
	}
	else { $direct_classes['error_functions']->error_page ("login","core_access_denied","sWG/#echo(__FILEPATH__)# _a=topics_ (#echo(__LINE__)#)"); }
	//j// EOA
	}

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// EOS
}

//j// EOF
?>