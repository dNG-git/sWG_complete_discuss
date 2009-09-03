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

if (!isset ($direct_settings['discuss_topics_latest_timeshifts'])) { $direct_settings['discuss_topics_latest_timeshifts'] = array (6,12,24,36,48,120,168); }
if (!isset ($direct_settings['discuss_topics_per_page'])) { $direct_settings['discuss_topics_per_page'] = 15; }
$direct_settings['additional_copyright'][] = array ("Module discuss #echo(sWGdiscussVersion)# - (C) ","http://www.direct-netware.de/redirect.php?swg","direct Netware Group"," - All rights reserved");

if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a={$direct_settings['a']}_ (#echo(__LINE__)#)"); }

$direct_classes['basic_functions']->settings_get ($direct_settings['path_data']."/settings/swg_discuss.php");

if ($direct_classes['kernel']->service_init_rboolean ())
{
if ($direct_settings['discuss'])
{
//j// BOA
$g_did = (isset ($direct_settings['dsd']['ddid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['ddid'])) : "boards");

if ($direct_settings['a'] == "latest")
{
	$g_hours = (isset ($direct_settings['dsd']['dhours']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['dhours'])) : 24);
	if (!in_array ($g_hours,$direct_settings['discuss_topics_latest_timeshifts'])) { $g_hours = 24; }
}

$g_order = (isset ($direct_settings['dsd']['dorder']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['dorder'])) : "last-time-sticky-desc");
$g_latest = (isset ($direct_settings['dsd']['dlatest']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['dlatest'])) : 1);

if ($g_order != "time-desc") { $g_order = "last-time-sticky-desc"; }

$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_board.php");
$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/dhandler/swg_discuss_topic.php");
direct_local_integration ("discuss");

$g_board_object = new direct_discuss_board ();
$g_topics_array = NULL;

$g_board_array = ($g_board_object ? $g_board_object->get ($g_did) : NULL);

if (is_array ($g_board_array))
{
	if ($direct_settings['a'] == "latest") { $g_topics_array = $g_board_object->get_subboard_topics_since_date (($direct_cachedata['core_time'] - ($g_hours * 3600)),0,$direct_settings['discuss_topics_per_page'],$g_order); }
	elseif ($direct_settings['a'] == "topics") { $g_topics_array = $g_board_object->get_topics (0,$direct_settings['discuss_topics_per_page'],$g_order); }
}

if ((is_array ($g_topics_array))&&($g_board_object->is_readable ()))
{
	direct_class_init ("output");

	$g_parsed_array = $g_board_object->parse ("m=discuss&a=[a]&dsd=[oid][page{$direct_cachedata['output_page']}]");

	$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
	header ("Content-type: application/atom+xml; charset=".$direct_local['lang_charset']);

	$g_board_title = ((isset ($g_parsed_array['title_alt'])) ? $g_parsed_array['title_alt'] : $g_parsed_array['title']);

echo ("<?xml version='1.0' encoding='$direct_local[lang_charset]' ?><feed xmlns='http://www.w3.org/2005/Atom'><generator>$direct_settings[product_lcode_txt] by the direct Netware Group</generator>
<subtitle type='xhtml'>".($direct_classes['xml_bridge']->array2xml_item_encoder (array ("tag" => "div","value" => $g_board_title,"attributes" => array ("xmlns" => "http://www.w3.org/1999/xhtml"))))."</subtitle>");

	if ($direct_settings['a'] == "latest")
	{
echo ("<title type='xhtml'>".($direct_classes['xml_bridge']->array2xml_item_encoder (array ("tag" => "div","value" => (direct_local_get ("discuss_topics_latest")),"attributes" => array ("xmlns" => "http://www.w3.org/1999/xhtml"))))."</title>
".($direct_classes['xml_bridge']->array2xml_item_encoder (array ("tag" => "link","attributes" => array ("href" => (direct_linker_dynamic ("url1","m=discuss&a=latest&dsd=ddid+{$g_did}++dhours+".$g_hours,false,false)),"rel" => "alternate","type" => "application/xhtml+xml"))))."
".($direct_classes['xml_bridge']->array2xml_item_encoder (array ("tag" => "link","attributes" => array ("href" => (direct_linker_dynamic ("url1","m=dataport&s=atom;discuss;topics&a=latest&dsd=ddid+{$g_did}++dhours+{$g_hours}++dorder+{$g_order}++dlatest+".$g_latest,false,false)),"rel" => "self","type" => "application/atom+xml"))))."
".($direct_classes['xml_bridge']->array2xml_item_encoder (array ("tag" => "updated","value" => gmdate ("c",$direct_cachedata['core_time']))))."
".($direct_classes['xml_bridge']->array2xml_item_encoder (array ("tag" => "id","value" => direct_linker_dynamic ("url1","m=dataport&s=atom;discuss;topics&a=latest&dsd=ddid+{$g_did}++dhours+".$g_hours,false,false)))));
	}
	else
	{
		if (strlen ($g_parsed_array['desc'])) { echo "<subtitle type='xhtml'>".($direct_classes['xml_bridge']->array2xml_item_encoder (array ("tag" => "div","value" => $g_parsed_array['desc'],"attributes" => array ("xmlns" => "http://www.w3.org/1999/xhtml"))))."</subtitle>"; }

echo ($direct_classes['xml_bridge']->array2xml_item_encoder (array ("tag" => "link","attributes" => array ("href" => (direct_linker_dynamic ("url1","m=discuss&a=topics&dsd=ddid+".$g_did,false,false)),"rel" => "alternate","type" => "application/xhtml+xml")))."
".($direct_classes['xml_bridge']->array2xml_item_encoder (array ("tag" => "link","attributes" => array ("href" => (direct_linker_dynamic ("url1","m=dataport&s=atom;discuss;topics&a=topics&dsd=ddid+{$g_did}++dorder+{$g_order}++dlatest+".$g_latest,false,false)),"rel" => "self","type" => "application/atom+xml"))))."
".($direct_classes['xml_bridge']->array2xml_item_encoder (array ("tag" => "updated","value" => gmdate ("c",$g_board_array['ddbdatalinker_sorting_date']))))."
".($direct_classes['xml_bridge']->array2xml_item_encoder (array ("tag" => "id","value" => direct_linker_dynamic ("url1","m=dataport&s=atom;discuss;topics&a=topics&dsd=ddid+".$g_did,false,false)))));
	}

	if (!empty ($g_topics_array))
	{
		$g_username_unknown = "<name type='xhtml'>".($direct_classes['xml_bridge']->array2xml_item_encoder (array ("tag" => "div","value" => (direct_local_get ("core_unknown")),"attributes" => array ("xmlns" => "http://www.w3.org/1999/xhtml"))))."</name>";

		foreach ($g_topics_array as $g_topic_object)
		{
			$g_topic_array = $g_topic_object->get ();
			$g_parsed_array = ((is_array ($g_topic_array)) ? $g_topic_object->parse ("m=discuss&a=[a]&dsd=[oid][page{$direct_cachedata['output_page']}]") : NULL);

			if (is_array ($g_parsed_array))
			{
echo ("<entry>
<title type='xhtml'>".($direct_classes['xml_bridge']->array2xml_item_encoder (array ("tag" => "div","value" => $g_parsed_array['title'],"attributes" => array ("xmlns" => "http://www.w3.org/1999/xhtml"))))."</title>");

				if (($g_latest)&&(strlen ($g_parsed_array['last_preview']))) { echo "<summary type='xhtml'>".($direct_classes['xml_bridge']->array2xml_item_encoder (array ("tag" => "div","value" => $g_parsed_array['last_preview'],"attributes" => array ("xmlns" => "http://www.w3.org/1999/xhtml"))))."</summary>"; }
				elseif (strlen ($g_parsed_array['desc'])) { echo "<summary type='xhtml'>".($direct_classes['xml_bridge']->array2xml_item_encoder (array ("tag" => "div","value" => $g_parsed_array['desc'],"attributes" => array ("xmlns" => "http://www.w3.org/1999/xhtml"))))."</summary>"; }

				if (strlen ($g_parsed_array['username']))
				{
					$g_username_xml = $direct_classes['xml_bridge']->array2xml_item_encoder (array ("tag" => "name","value" => $g_parsed_array['username']));
					if ($g_topic_array['ddbdiscuss_topics_last_id']) { $g_username_xml .= $direct_classes['xml_bridge']->array2xml_item_encoder (array ("tag" => "uri","value" => direct_linker ("url1","m=account&s=profile&a=view&dsd=auid+".$g_topic_array['ddbdiscuss_topics_last_id'],false,false))); }
				}
				else { $g_username_xml = $g_username_unknown; }

				if ($g_parsed_array['views_counted'])
				{
					$g_target = ($g_latest ? urlencode (base64_encode ("m=discuss&a=jump&dsd=idata+t;{$g_topic_array['ddbdatalinker_id']};latest++ddid+".$g_did)) : urlencode (base64_encode ("m=discuss&a=jump&dsd=idata+t;{$g_topic_array['ddbdatalinker_id']};first++ddid+".$g_did)));
					echo $direct_classes['xml_bridge']->array2xml_item_encoder (array ("tag" => "link","attributes" => array ("href" => (direct_linker_dynamic ("url1","m=datalinker&a=count&dsd=deid+{$g_topic_array['ddbdatalinker_id']}++source+".$g_target,false,false)),"rel" => "alternate","type" => "application/xhtml+xml")));
				}
				elseif ($g_latest) { echo $direct_classes['xml_bridge']->array2xml_item_encoder (array ("tag" => "link","attributes" => array ("href" => (direct_linker_dynamic ("url1","m=discuss&a=jump&dsd=idata+t;{$g_topic_array['ddbdatalinker_id']};latest++ddid+".$g_did,false,false)),"rel" => "alternate","type" => "application/xhtml+xml"))); }
				else { echo $direct_classes['xml_bridge']->array2xml_item_encoder (array ("tag" => "link","attributes" => array ("href" => (direct_linker_dynamic ("url1","m=discuss&a=jump&dsd=idata+t;{$g_topic_array['ddbdatalinker_id']};first++ddid+".$g_did,false,false)),"rel" => "alternate","type" => "application/xhtml+xml"))); }

echo ($direct_classes['xml_bridge']->array2xml_item_encoder (array ("tag" => "published","value" => gmdate ("c",$g_topic_array['ddbdiscuss_topics_time'])))."
".$direct_classes['xml_bridge']->array2xml_item_encoder (array ("tag" => "updated","value" => gmdate ("c",$g_topic_array['ddbdatalinker_sorting_date'])))."
<author>$g_username_xml</author>
".($direct_classes['xml_bridge']->array2xml_item_encoder (array ("tag" => "total","value" => ($g_parsed_array['posts'] - 1),"attributes" => array ("xmlns" => "http://purl.org/syndication/thread/1.0"))))."
".($direct_classes['xml_bridge']->array2xml_item_encoder (array ("tag" => "id","value" => direct_linker_dynamic ("url1","m=discuss&a=jump&dsd=idata+t;{$g_topic_array['ddbdatalinker_id']};latest++ddid+".$g_did,false,false))))."
</entry>");
			}
		}
	}

	echo "</feed>";
}

$direct_cachedata['core_service_activated'] = true;
//j// EOA
}
}

//j// EOF
?>