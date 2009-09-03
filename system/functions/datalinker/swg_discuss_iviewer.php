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
* Provides the iviewer which calls parser and returns standardized values for
* output.
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
* @subpackage datalinker
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

//f// direct_datalinker_discuss_iviewer ($f_viewer_data,&$f_object)
/**
* This iviewer is responsible for discuss objects. It will check the read
* rights and return standardized values.
*
* @param  array $f_viewer_data Found iviewer entry
* @param  direct_datalinker &$f_object DataLinker object
* @uses   direct_basic_functions::include_file()
* @uses   direct_datalinker_discuss()
* @uses   direct_datalinker_discuss_iviewer_board()
* @uses   direct_datalinker_discuss_iviewer_posts()
* @uses   direct_datalinker_discuss_iviewer_topic()
* @uses   direct_debug()
* @uses   USE_debug_reporting
* @return array Parsed entry (ready for output)
* @since  v0.1.00
*/
function direct_datalinker_discuss_iviewer ($f_viewer_data,&$f_object)
{
	global $direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_datalinker_discuss_iviewer (+f_viewer_data,+f_object)- (#echo(__LINE__)#)"); }

	$f_return = array ();

	if (isset ($f_viewer_data['handler']))
	{
		if ($direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/functions/datalinker/swg_discuss.php")) { $f_object_iview =& direct_datalinker_discuss ($f_object); }
		else { $f_object_iview = NULL; }

		if ($f_object_iview)
		{
			switch ($f_viewer_data['action'])
			{
			case "board":
			{
				$f_return = direct_datalinker_discuss_iviewer_board ($f_viewer_data,$f_object_iview);
				break 1;
			}
			case "post":
			{
				$f_return = direct_datalinker_discuss_iviewer_post ($f_viewer_data,$f_object_iview);
				break 1;
			}
			case "topic":
			{
				$f_return = direct_datalinker_discuss_iviewer_topic ($f_viewer_data,$f_object_iview);
				break 1;
			}
			}
		}
	}

	/*PHPr*/ return $f_return; /*PHPd return direct_debug (7,"sWG/#echo(__FILEPATH__)# -direct_datalinker_discuss_iviewer ()- (#echo(__LINE__)#)",$f_return,true);*/
}

//f// direct_datalinker_discuss_iviewer_board ($f_viewer_data,&$f_object)
/**
* iviewer for direct_discuss_board objects.
*
* @param  array $f_viewer_data Found iviewer entry
* @param  direct_datalinker &$f_object DataLinker object
* @uses   direct_basic_functions::include_file()
* @uses   direct_discuss_board::get()
* @uses   direct_discuss_board::is_readable()
* @uses   direct_discuss_board::parse()
* @uses   direct_debug()
* @uses   direct_linker()
* @uses   direct_local_get()
* @uses   direct_local_integration()
* @uses   USE_debug_reporting
* @return array Parsed entry (ready for output)
* @since  v0.1.00
*/
function direct_datalinker_discuss_iviewer_board ($f_viewer_data,&$f_object)
{
	global $direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_datalinker_discuss_iviewer_board (+f_viewer_data,+f_object)- (#echo(__LINE__)#)"); }

	direct_local_integration ("discuss");

$f_return = array (
"object_id" => "",
"object_title_type" => direct_local_get ("discuss_board"),
"object_title" => direct_local_get ("core_datasub_no_access_title"),
"object_symbol" => "",
"object_desc" => direct_local_get ("core_datasub_no_access"),
"object_entries" => "",
"object_last_username" => "",
"object_last_userpageurl" => "",
"object_last_useravatar" => "",
"object_preview" => "",
"object_content" => "",
"object_last_time" => "",
"object_url" => "",
"object_available" => false,
"object_view_url" => "",
"object_extended_available" => false,
"object_new" => false
);

	if (isset ($f_viewer_data['handler']))
	{
		$f_object_array = $f_object->get ();
		$f_parent_array = NULL;
		$f_parent_check = false;

		if (is_array ($f_object_array))
		{
			$f_parent_object = new direct_discuss_board ();

			if (($f_object_array['ddbdatalinker_id_main'])&&($f_parent_object))
			{
				$f_parent_array = $f_parent_object->get ($f_object_array['ddbdatalinker_id_parent']);
				if ($f_parent_array) { $f_parent_check = $f_parent_object->is_readable (); }
			}
		}

		if (!is_array ($f_object_array)) { $f_return['object_desc'] = direct_local_get ("errors_discuss_did_invalid"); }
		elseif ($f_object->is_readable ())
		{
			$f_parsed_array = $f_object->parse ("m=discuss&a=[a]&dsd=[oid][page]");

			$f_return['object_id'] = $f_parsed_array['oid'];
			$f_return['object_title'] = $f_parsed_array['title'];

			if (($direct_settings['datalinker_datacenter_symbols'])&&($f_viewer_data['symbol']))
			{
				$f_symbol_path = $direct_classes['basic_functions']->varfilter ($direct_settings['datalinker_datacenter_path_symbols'],"settings");
				$f_return['object_symbol'] = direct_linker_dynamic ("url0","s=cache&dsd=dfile+".$f_symbol_path.$f_viewer_data['symbol']);
			}

			$f_return['object_desc'] = $f_parsed_array['data'];
			$f_return['object_entries'] = $f_parsed_array['topics'];
			$f_return['object_last_username'] = $f_parsed_array['username'];
			$f_return['object_last_userpageurl'] = $f_parsed_array['userpageurl'];
			$f_return['object_last_useravatar'] = $f_parsed_array['useravatar_small'];
			$f_return['object_preview'] = $f_parsed_array['last_preview'];

			if ($f_object_array['ddbdatalinker_sorting_date']) { $f_return['object_last_time'] = $direct_classes['basic_functions']->datetime ("shortdate&time",$f_object_array['ddbdatalinker_sorting_date'],$direct_settings['user']['timezone'],(direct_local_get ("datetime_dtconnect"))); }
			else { $f_return['object_last_time'] = direct_local_get ("core_unknown"); }

			$f_return['object_url'] = $f_parsed_array['pageurl'];
			$f_return['object_available'] = true;
			$f_return['object_new'] = $f_parsed_array['new'];

			if (($f_parent_check)&&(is_array ($f_parent_array)))
			{
				$f_parsed_array = $f_parent_object->parse ("m=discuss&a=[a]&dsd=[oid][page]");
				$f_return['category_id'] = $f_parsed_array['oid'];
				$f_return['category_title_type'] = direct_local_get ("discuss_board");
				$f_return['category_title'] = $f_parsed_array['title'];
				$f_return['category_desc'] = $f_parsed_array['data'];
				$f_return['category_url'] = $f_parsed_array['pageurl'];
				$f_return['category_entries'] = $f_parsed_array['objects'];
				$f_return['category_new'] = $f_parsed_array['new'];
			}
		}
	}

	/*PHPr*/ return $f_return; /*PHPd return direct_debug (7,"sWG/#echo(__FILEPATH__)# -direct_datalinker_discuss_iviewer_board ()- (#echo(__LINE__)#)",$f_return,true);*/
}

//f// direct_datalinker_discuss_iviewer_post ($f_viewer_data,&$f_object)
/**
* iviewer for direct_discuss_post objects.
*
* @param  array $f_viewer_data Found iviewer entry
* @param  direct_datalinker &$f_object DataLinker object
* @uses   direct_basic_functions::include_file()
* @uses   direct_class_init()
* @uses   direct_discuss_board::get()
* @uses   direct_discuss_board::is_readable()
* @uses   direct_discuss_post::get()
* @uses   direct_discuss_post::is_readable()
* @uses   direct_discuss_post::parse()
* @uses   direct_discuss_topic::get()
* @uses   direct_discuss_topic::parse()
* @uses   direct_debug()
* @uses   direct_formtags::cleanup()
* @uses   direct_html_encode_special()
* @uses   direct_linker()
* @uses   direct_local_get()
* @uses   direct_local_integration()
* @uses   USE_debug_reporting
* @return array Parsed entry (ready for output)
* @since  v0.1.00
*/
function direct_datalinker_discuss_iviewer_post ($f_viewer_data,&$f_object)
{
	global $direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_datalinker_discuss_iviewer_post (+f_viewer_data,+f_object)- (#echo(__LINE__)#)"); }

	direct_local_integration ("discuss");

$f_return = array (
"object_id" => "",
"object_title_type" => direct_local_get ("discuss_post"),
"object_title" => direct_local_get ("core_datasub_no_access_title"),
"object_symbol" => "",
"object_desc" => direct_local_get ("core_datasub_no_access"),
"object_entries" => "",
"object_last_username" => "",
"object_last_userpageurl" => "",
"object_last_useravatar" => "",
"object_preview" => "",
"object_content" => "",
"object_last_time" => "",
"object_url" => "",
"object_available" => false,
"object_view_url" => "",
"object_extended_available" => false,
"object_new" => false
);

	if (isset ($f_viewer_data['handler']))
	{
		$f_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_board.php");
		if ($f_continue_check) { $f_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_topic.php"); }
		if ($f_continue_check) { $f_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/swg_formtags.php"); }
		if ($f_continue_check) { $f_continue_check = direct_class_init ("formtags"); }

		$f_board_object = NULL;
		$f_post_array = ($f_continue_check ? $f_object->get () : NULL);
		$f_subs_check = false;
		$f_topic_array = NULL;

		if (is_array ($f_post_array))
		{
			$f_topic_object = new direct_discuss_topic ();

			if ($f_post_array['ddbdatalinker_id_main'])
			{
				if ($f_topic_object) { $f_topic_array = $f_topic_object->get ($f_post_array['ddbdatalinker_id_main']); }
			}
			else { $f_subs_check = true; }
		}

		if ((is_array ($f_topic_array))&&($f_topic_array['ddbdatalinker_id_main']))
		{
			$f_board_object = new direct_discuss_board ();
			$f_board_object->get ($f_topic_array['ddbdatalinker_id_main']);
		}

		if ((!$f_subs_check)&&(!is_array ($f_topic_array))) { $f_return['object_desc'] = direct_local_get ("errors_discuss_pid_invalid"); }
		elseif (($f_object->is_readable ())&&((!is_object ($f_board_object))||($f_board_object->is_readable ())))
		{
			$f_extended_check = false;
			$f_parsed_array = $f_object->parse ("m=discuss&a=[a]&dsd=[oid][page]");

			if (strlen ($f_post_array['ddbdata_data']) > $direct_settings['discuss_iview_preview_length'])
			{
				$f_extended_check = true;
				$f_preview = mb_substr ($direct_classes['formtags']->cleanup ($f_post_array['ddbdata_data']),0,($direct_settings['discuss_iview_preview_length'] - 4));
				$f_preview = str_replace ("\n","<br />",(direct_html_encode_special ($f_preview)));
				$f_preview .= " ...";

				$f_return['object_view_url'] = direct_linker ("url0","m=datalinker&dsd=deid+".$f_post_array['ddbdatalinker_id']);
			}
			else { $f_preview = $f_parsed_array['text']; }

			$f_return['object_id'] = $f_parsed_array['oid'];
			$f_return['object_title'] = $f_parsed_array['title'];

			if (($direct_settings['datalinker_datacenter_symbols'])&&($f_viewer_data['symbol']))
			{
				$f_symbol_path = $direct_classes['basic_functions']->varfilter ($direct_settings['datalinker_datacenter_path_symbols'],"settings");
				$f_return['object_symbol'] = direct_linker_dynamic ("url0","s=cache&dsd=dfile+".$f_symbol_path.$f_viewer_data['symbol']);
			}

			$f_return['object_desc'] = $f_parsed_array['desc'];
			$f_return['object_last_username'] = $f_parsed_array['username'];
			$f_return['object_last_userpageurl'] = $f_parsed_array['userpageurl'];
			$f_return['object_last_useravatar'] = $f_parsed_array['useravatar_small'];
			$f_return['object_preview'] = $f_preview;
			$f_return['object_content'] = $f_parsed_array['text'];

			if ($f_post_array['ddbdatalinker_sorting_date']) { $f_return['object_last_time'] = $direct_classes['basic_functions']->datetime ("shortdate&time",$f_post_array['ddbdatalinker_sorting_date'],$direct_settings['user']['timezone'],(direct_local_get ("datetime_dtconnect"))); }
			else { $f_return['object_last_time'] = direct_local_get ("core_unknown"); }

			$f_return['object_url'] = $f_parsed_array['pageurl'];
			$f_return['object_available'] = true;
			$f_return['object_extended_available'] = $f_extended_check;
			$f_return['object_new'] = $f_parsed_array['new'];

			if (!$f_subs_check)
			{
				$f_parsed_array = $f_topic_object->parse ("m=discuss&a=[a]&dsd=[oid][page]");
				$f_return['category_id'] = $f_parsed_array['oid'];
				$f_return['category_title_type'] = direct_local_get ("discuss_topic");
				$f_return['category_title'] = $f_parsed_array['title'];
				$f_return['category_desc'] = $f_parsed_array['data'];
				$f_return['category_url'] = ((isset ($f_parsed_array['pageurl_counted'])) ? $f_parsed_array['pageurl_counted'] : $f_parsed_array['pageurl']);
				$f_return['category_entries'] = $f_parsed_array['topics'];
				$f_return['category_new'] = $f_parsed_array['new'];
			}
		}
	}

	/*PHPr*/ return $f_return; /*PHPd return direct_debug (7,"sWG/#echo(__FILEPATH__)# -direct_datalinker_discuss_iviewer_post ()- (#echo(__LINE__)#)",$f_return,true);*/
}

//f// direct_datalinker_discuss_iviewer_topic ($f_viewer_data,&$f_object)
/**
* iviewer for direct_discuss_topic objects.
*
* @param  array $f_viewer_data Found iviewer entry
* @param  direct_datalinker &$f_object DataLinker object
* @uses   direct_basic_functions::include_file()
* @uses   direct_discuss_board::get()
* @uses   direct_discuss_board::is_readable()
* @uses   direct_discuss_board::parse()
* @uses   direct_discuss_topic::get()
* @uses   direct_discuss_topic::parse()
* @uses   direct_debug()
* @uses   direct_linker()
* @uses   direct_local_get()
* @uses   direct_local_integration()
* @uses   USE_debug_reporting
* @return array Parsed entry (ready for output)
* @since  v0.1.00
*/
function direct_datalinker_discuss_iviewer_topic ($f_viewer_data,&$f_object)
{
	global $direct_classes,$direct_settings;
	if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -direct_datalinker_discuss_iviewer_topic (+f_viewer_data,+f_object)- (#echo(__LINE__)#)"); }

	direct_local_integration ("discuss");

$f_return = array (
"object_id" => "",
"object_title_type" => direct_local_get ("discuss_topic"),
"object_title" => direct_local_get ("core_datasub_no_access_title"),
"object_symbol" => "",
"object_desc" => direct_local_get ("core_datasub_no_access"),
"object_entries" => "",
"object_last_username" => "",
"object_last_userpageurl" => "",
"object_last_useravatar" => "",
"object_preview" => "",
"object_content" => "",
"object_last_time" => "",
"object_url" => "",
"object_available" => false,
"object_view_url" => "",
"object_extended_available" => false,
"object_new" => false
);

	if (isset ($f_viewer_data['handler']))
	{
		$f_board_array = NULL;
		$f_subs_check = false;
		$f_topic_array = (($direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_board.php")) ? $f_object->get () : NULL);

		if (is_array ($f_topic_array))
		{
			$f_board_object = new direct_discuss_board ();

			if ($f_topic_array['ddbdatalinker_id_main'])
			{
				if ($f_board_object) { $f_board_array = $f_board_object->get ($f_topic_array['ddbdatalinker_id_main']); }
			}
			else { $f_subs_check = true; }
		}

		if ((!$f_subs_check)&&(!is_array ($f_board_array))) { $f_return['object_desc'] = direct_local_get ("errors_discuss_tid_invalid"); }
		elseif (($f_subs_check)||($f_board_object->is_readable ()))
		{
			$f_parsed_array = $f_object->parse ("m=discuss&a=[a]&dsd=[oid][page]");

			$f_return['object_id'] = $f_parsed_array['oid'];
			$f_return['object_title'] = $f_parsed_array['title'];

			if (($direct_settings['datalinker_datacenter_symbols'])&&($f_viewer_data['symbol']))
			{
				$f_symbol_path = $direct_classes['basic_functions']->varfilter ($direct_settings['datalinker_datacenter_path_symbols'],"settings");
				$f_return['object_symbol'] = direct_linker_dynamic ("url0","s=cache&dsd=dfile+".$f_symbol_path.$f_viewer_data['symbol']);
			}

			$f_return['object_desc'] = $f_parsed_array['desc'];
			$f_return['object_entries'] = $f_parsed_array['posts'];
			$f_return['object_last_username'] = $f_parsed_array['username'];
			$f_return['object_last_userpageurl'] = $f_parsed_array['userpageurl'];
			$f_return['object_last_useravatar'] = $f_parsed_array['useravatar_small'];
			$f_return['object_preview'] = $f_parsed_array['last_preview'];

			if ($f_topic_array['ddbdatalinker_sorting_date']) { $f_return['object_last_time'] = $direct_classes['basic_functions']->datetime ("shortdate&time",$f_topic_array['ddbdatalinker_sorting_date'],$direct_settings['user']['timezone'],(direct_local_get ("datetime_dtconnect"))); }
			else { $f_return['object_last_time'] = direct_local_get ("core_unknown"); }

			$f_return['object_url'] = ((isset ($f_parsed_array['last_post_jump_counted'])) ? $f_parsed_array['last_post_jump_counted'] : $f_parsed_array['last_post_jump']);
			$f_return['object_available'] = true;
			$f_return['object_new'] = $f_parsed_array['new'];

			if (!$f_subs_check)
			{
				$f_parsed_array = $f_board_object->parse ("m=discuss&a=[a]&dsd=[oid][page]");
				$f_return['category_id'] = $f_parsed_array['oid'];
				$f_return['category_title_type'] = direct_local_get ("discuss_board");
				$f_return['category_title'] = $f_parsed_array['title'];
				$f_return['category_desc'] = $f_parsed_array['data'];
				$f_return['category_url'] = $f_parsed_array['pageurl'];
				$f_return['category_entries'] = $f_parsed_array['topics'];
				$f_return['category_new'] = $f_parsed_array['new'];
			}
		}
	}

	/*PHPr*/ return $f_return; /*PHPd return direct_debug (7,"sWG/#echo(__FILEPATH__)# -direct_datalinker_discuss_iviewer_topic ()- (#echo(__LINE__)#)",$f_return,true);*/
}

//j// Script specific commands

if (!isset ($direct_settings['datalinker_datacenter_symbols'])) { $direct_settings['datalinker_datacenter_symbols'] = false; }
if (!isset ($direct_settings['datalinker_datacenter_path_symbols'])) { $direct_settings['datalinker_datacenter_path_symbols'] = $direct_settings['path_themes']."/$direct_settings[theme]/"; }
if (!isset ($direct_settings['discuss_iview_preview_length'])) { $direct_settings['discuss_iview_preview_length'] = 500; }

//j// EOF
?>