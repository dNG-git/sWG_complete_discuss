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
* OOP (Object Oriented Programming) requires an abstract data
* handling. The sWG is OO (where it makes sense).
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

/* -------------------------------------------------------------------------
Testing for required classes
------------------------------------------------------------------------- */

$g_continue_check = ((defined ("CLASS_direct_discuss_post")) ? false : true);
if (!defined ("CLASS_direct_datalinker")) { $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_datalinker.php"); }
if (!defined ("CLASS_direct_datalinker")) { $g_continue_check = false; }

if ($g_continue_check)
{
//c// direct_discuss_post
/**
* This abstraction layer provides post (discuss) specific functions.
*
* @author     direct Netware Group
* @copyright  (C) direct Netware Group - All rights reserved
* @package    sWG
* @subpackage discuss
* @uses       CLASS_direct_datalinker
* @since      v0.1.00
* @license    http://www.direct-netware.de/redirect.php?licenses;gpl
*             GNU General Public License 2
*/
class direct_discuss_post extends direct_datalinker
{
/**
	* @var boolean $data_locked True if this post is locked
*/
	protected $data_locked;
/**
	* @var boolean $data_readable True if the current user is allowed to
	*      read this document
*/
	protected $data_readable;
/**
	* @var boolean $data_readable_group True if the current user is in a
	*      group that is allowed to read this document
*/
	protected $data_readable_group;
/**
	* @var string $data_tid Topic ID to be used
*/
	protected $data_tid;
/**
	* @var boolean $data_writable True if the current user is allowed to
	*      write to this document
*/
	protected $data_writable;
/**
	* @var boolean $data_writable_group True if the current user is in a
	*      group that is allowed to write to this document
*/
	protected $data_writable_group;

/* -------------------------------------------------------------------------
Extend the class
------------------------------------------------------------------------- */

	//f// direct_discuss_post->__construct ()
/**
	* Constructor (PHP5) __construct (direct_discuss_post)
	*
	* @param mixed $f_data String containing the allowed topic ID or an array
	*        with options
	* @uses  direct_basic_functions::include_file()
	* @uses  direct_class_init()
	* @uses  direct_debug()
	* @uses  USE_debug_reporting
	* @since v0.1.00
*/
	public function __construct ($f_data = "")
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->__construct (direct_discuss_post)- (#echo(__LINE__)#)"); }

		if (!defined ("CLASS_direct_formtags")) { $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/swg_formtags.php"); }
		if (!isset ($direct_classes['formtags'])) { direct_class_init ("formtags"); }

/* -------------------------------------------------------------------------
My parent should be on my side to get the work done
------------------------------------------------------------------------- */

		parent::__construct ();

/* -------------------------------------------------------------------------
Informing the system about available functions 
------------------------------------------------------------------------- */

		$this->functions['define_lock'] = true;
		$this->functions['define_readable'] = true;
		$this->functions['define_tid'] = true;
		$this->functions['define_writable'] = true;
		$this->functions['get_rights'] = true;
		$this->functions['is_locked'] = true;
		$this->functions['is_readable'] = true;
		$this->functions['is_readable_group'] = true;
		$this->functions['is_writable'] = true;
		$this->functions['is_writable_group'] = true;
		$this->functions['parse'] = isset ($direct_classes['formtags']);

/* -------------------------------------------------------------------------
Set up an additional post class element :)
------------------------------------------------------------------------- */

		$this->data_locked = false;
		$this->data_readable = false;
		$this->data_readable_group = false;
		$this->data_sid = "cb41ecf6e90a594dcea60b6140251d62";
		$this->data_tid = "";
		$this->data_writable = false;
		$this->data_writable_group = false;

		if (is_string ($f_data)) { $this->data_tid = $f_data; }
		elseif (isset ($f_data['tid'])) { $this->data_tid = $f_data['tid']; }
	}

	//f// direct_discuss_post->define_lock ($f_state = NULL,$f_update = false)
/**
	* Sets the locking state of this post.
	*
	* @param  mixed $f_state Boolean indicating the state or NULL to switch
	*         automatically
	* @param  boolean $f_update True to update the database entry
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean Accepted state
	* @since  v0.1.00
*/
	public function define_lock ($f_state = NULL,$f_update = false)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->define_lock (+f_state,+f_update)- (#echo(__LINE__)#)"); }
		$f_return = false;

		if (count ($this->data) > 1)
		{
			if (((is_bool ($f_state))||(is_string ($f_state)))&&($f_state)) { $f_return = true; }
			elseif (($f_state === NULL)&&(!$this->data['ddbdiscuss_posts_locked'])) { $f_return = true; }
			$this->data_locked = $f_return;

			$this->data['ddbdiscuss_posts_locked'] = ($f_return ? 1 : 0);
			$this->data_changed['ddbdiscuss_posts_locked'] = true;
			if ($f_update) { $this->update (false,true); }
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->define_lock ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_post->define_readable ($f_state = NULL)
/**
	* Sets the writing right state of this post.
	*
	* @param  mixed $f_state Boolean indicating the state or NULL to switch
	*         automatically
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean Accepted state
	* @since  v0.1.00
*/
	public function define_readable ($f_state = NULL)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->define_readable (+f_state)- (#echo(__LINE__)#)"); }

		if (((is_bool ($f_state))||(is_string ($f_state)))&&($f_state)) { $this->data_readable = true; }
		elseif (($f_state === NULL)&&(!$this->data_readable)) { $this->data_readable = true; }
		else { $this->data_readable = false; }

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->define_readable ()- (#echo(__LINE__)#)",:#*/$this->data_readable/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_post->define_tid ($f_tid)
/**
	* Sets the topic ID of this post.
	*
	* @param  string $f_tid Topic ID to use
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return string Accepted topic ID
	* @since  v0.1.00
*/
	public function define_tid ($f_tid)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->define_tid (+f_tid)- (#echo(__LINE__)#)"); }

		if (is_string ($f_tid)) { $this->data_tid = $f_tid; }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->define_tid ()- (#echo(__LINE__)#)",:#*/$this->data_tid/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_post->define_writable ($f_state = NULL)
/**
	* Sets the writing right state of this post.
	*
	* @param  mixed $f_state Boolean indicating the state or NULL to switch
	*         automatically
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean Accepted state
	* @since  v0.1.00
*/
	public function define_writable ($f_state = NULL)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->define_writable (+f_state)- (#echo(__LINE__)#)"); }

		if (((is_bool ($f_state))||(is_string ($f_state)))&&($f_state)) { $this->data_writable = true; }
		elseif (($f_state === NULL)&&(!$this->data_writable)) { $this->data_writable = true; }
		else { $this->data_writable = false; }

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->define_writable ()- (#echo(__LINE__)#)",:#*/$this->data_writable/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_post->get_aid ($f_attributes = NULL,$f_values = "")
/**
	* Request and load the post object based on a custom attribute ID. Please
	* note that only attributes of type "string" are supported.
	*
	* @param  mixed $f_attributes Attribute name(s) (array or string)
	* @param  mixed $f_values Attribute value(s) (array or string)
	* @uses   direct_datalinker::define_extra_attributes()
	* @uses   direct_datalinker::define_extra_conditions()
	* @uses   direct_datalinker::define_extra_joins()
	* @uses   direct_datalinker::get_aid()
	* @uses   direct_debug()
	* @uses   direct_discuss_post::get_rights()
	* @uses   USE_debug_reporting
	* @return mixed Post data array; false on error
	* @since  v0.1.00
*/
	public function get_aid ($f_attributes = NULL,$f_values = "")
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (3,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->get_aid (+f_attributes,+f_values)- (#echo(__LINE__)#)"); }

		$f_return = false;

		if (count ($this->data) > 1) { $f_return = $this->data; }
		elseif ((is_array ($f_values))||(is_string ($f_values)))
		{
$f_select_attributes = array ($direct_settings['discuss_posts_table'].".*",$direct_settings['data_table'].".ddbdata_data",$direct_settings['data_table'].".ddbdata_sid",$direct_settings['data_table'].".ddbdata_mode_user",$direct_settings['data_table'].".ddbdata_mode_group",$direct_settings['data_table'].".ddbdata_mode_all",
$direct_settings['users_table'].".ddbusers_type",$direct_settings['users_table'].".ddbusers_banned",$direct_settings['users_table'].".ddbusers_deleted",$direct_settings['users_table'].".ddbusers_name",$direct_settings['users_table'].".ddbusers_title",$direct_settings['users_table'].".ddbusers_avatar",$direct_settings['users_table'].".ddbusers_signature",$direct_settings['users_table'].".ddbusers_rating");

			$this->define_extra_attributes ($f_select_attributes);

$f_select_joins = array (
array ("type" => "left-outer-join","table" => $direct_settings['discuss_posts_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['discuss_posts_table']}.ddbdiscuss_posts_id' value='{$direct_settings['datalinker_table']}.ddbdatalinker_id_object' type='attribute' /></sqlconditions>"),
array ("type" => "left-outer-join","table" => $direct_settings['users_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['users_table']}.ddbusers_id' value='{$direct_settings['discuss_posts_table']}.ddbdiscuss_posts_user_id' type='attribute' /></sqlconditions>"),
array ("type" => "left-outer-join","table" => $direct_settings['data_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['data_table']}.ddbdata_id' value='{$direct_settings['datalinker_table']}.ddbdatalinker_id_object' type='attribute' /></sqlconditions>")
);

			$this->define_extra_joins ($f_select_joins);

			if (strlen ($this->data_tid)) { $this->define_extra_conditions ($direct_classes['db']->define_row_conditions_encode ($direct_settings['datalinker_table'].".ddbdatalinker_id_main",$this->data_tid,"string")); }

			$f_result_array = parent::get_aid ($f_attributes,$f_values);

			if (($f_result_array)&&($f_result_array['ddbdatalinker_sid'] == $this->data_sid)&&($f_result_array['ddbdatalinker_type'] == 6))
			{
				$this->data = $f_result_array;
				$this->data_locked = ($this->data['ddbdiscuss_posts_locked'] ? true : false);

				$f_result_array = $this->get_rights ();
				$this->data_readable = $f_result_array[0];
				$this->data_readable_group = $f_result_array[1];
				$this->data_writable = $f_result_array[2];
				$this->data_writable_group = $f_result_array[3];

				if (($this->data_readable)||($this->data_readable_group)) { $f_return = $this->data; }
			}
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->get_aid ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_contentor_doc->get_rights ()
/**
	* Check the user rights based on the defined object.
	*
	* @uses   direct_debug()
	* @uses   direct_kernel_system::v_group_user_check_group()
	* @uses   direct_kernel_system::v_group_user_check_right()
	* @uses   direct_kernel_system::v_usertype_get_int()
	* @uses   USE_debug_reporting
	* @return array Array with the results to read, read as group member,
	*         write and write as group member
	* @since  v0.1.00
*/
	protected function get_rights ()
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->get_rights ()- (#echo(__LINE__)#)"); }

		$f_return = array (false,false,false,false);

		if ($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 3)
		{
			$f_return[0] = true;
			$f_return[2] = true;
		}
		elseif ($direct_settings['user']['type'] != "gt")
		{
			if ($direct_settings['user']['id'] == $this->data['ddbdiscuss_posts_user_id'])
			{
				if ($this->data['ddbdata_mode_user'] == "w")
				{
					$f_return[0] = true;
					if (!$this->data_locked) { $f_return[2] = true; }
				}
				elseif ($this->data['ddbdata_mode_user'] == "r") { $f_return[0] = true; }
			}
		}

		if ($this->data['ddbdata_mode_group'] == "r") { $f_return[1] = true; }
		elseif ($this->data['ddbdata_mode_group'] == "w")
		{
			$f_return[1] = true;
			if (!$this->data_locked) { $f_return[3] = true; }
		}

		if ($this->data['ddbdata_mode_all'] == "r") { $f_return[0] = true; }
		elseif ($this->data['ddbdata_mode_all'] == "w")
		{
			$f_return[0] = true;
			if (!$this->data_locked) { $f_return[2] = true; }
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->get_rights ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_post->is_locked ()
/**
	* Returns true if the document is locked.
	*
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True or false
	* @since  v0.1.00
*/
	public function is_locked ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->is_locked ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->is_locked ()- (#echo(__LINE__)#)",:#*/$this->data_locked/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_post->is_readable ()
/**
	* Returns true if the current user is allowed to read this document.
	*
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True or false
	* @since  v0.1.00
*/
	public function is_readable ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->is_readable ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->is_readable ()- (#echo(__LINE__)#)",:#*/$this->data_readable/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_post->is_readable_group ()
/**
	* Returns true if the current user is in a group that is allowed to read this
	* document.
	*
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True or false
	* @since  v0.1.00
*/
	public function is_readable_group ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_post_handler-> ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->is_readable_group ()- (#echo(__LINE__)#)",:#*/$this->data_readable_group/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_post->is_writable ()
/**
	* Returns true if the current user is allowed to write to this document.
	*
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True or false
	* @since  v0.1.00
*/
	public function is_writable ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->is_writable ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->is_writable ()- (#echo(__LINE__)#)",:#*/$this->data_writable/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_post->is_writable_group ()
/**
	* Returns true if the current user is in a group that is allowed to write to
	* this document.
	*
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True or false
	* @since  v0.1.00
*/
	public function is_writable_group ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->is_writable_group ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->is_writable_group ()- (#echo(__LINE__)#)",:#*/$this->data_writable_group/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_post->parse ($f_connector,$f_topic_locked = false,$f_is_moderator = false,$f_connector_type = "url0",$f_prefix = "")
/**
	* Parses this post and returns valid (X)HTML.
	*
	* @param  string $f_connector Connector for links
	* @param  boolean $f_topic_locked Topic locking status
	* @param  boolean $f_is_moderator User moderator status
	* @param  string $f_connector_type Linking mode: "url0" for internal links,
	*         "url1" for external ones, "form" to create hidden fields or
	*         "optical" to remove parts of a very long string.
	* @param  string $f_prefix Key prefix
	* @uses   direct_basic_functions::datetime()
	* @uses   direct_basic_functions::varfilter()
	* @uses   direct_datalinker::parse()
	* @uses   direct_debug()
	* @uses   direct_formtags::decode()
	* @uses   direct_kernel_system::v_user_parse()
	* @uses   direct_kernel_system::v_usertype_get_int()
	* @uses   direct_linker()
	* @uses   direct_local_get()
	* @uses   direct_output_control::options_insert()
	* @uses   USE_debug_reporting
	* @return array Output data
	* @since  v0.1.00
*/
	public function parse ($f_connector,$f_topic_locked = false,$f_is_moderator = false,$f_connector_type = "url0",$f_prefix = "")
	{
		global $direct_cachedata,$direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->parse ($f_connector,+f_topic_locked,+f_is_moderator,$f_connector_type,$f_prefix)- (#echo(__LINE__)#)"); }

		$f_return = parent::parse ($f_prefix);

		if (($f_return)&&($this->data_readable)&&(count ($this->data) > 1))
		{
			$f_return[$f_prefix."id"] = "swgdhandlerdiscusspost".$this->data['ddbdatalinker_id'];
			if (($f_connector_type != "asis")&&(strpos ($f_connector,"javascript:") === 0)) { $f_connector_type = "asis"; }

			if ($this->data['ddbdatalinker_id_main']) { $f_pageurl = (($f_connector_type == "asis") ? str_replace ("[oid]",$this->data['ddbdatalinker_id'],$f_pageurl) : "m=datalinker&a=view&dsd=deid+".$this->data['ddbdatalinker_id']); }
			else { $f_pageurl = (($f_connector_type == "asis") ? str_replace ("[oid]",$this->data['ddbdatalinker_id_object'],$f_pageurl) : "m=datalinker&a=view&dsd=deid+".$this->data['ddbdatalinker_id_object']); }

			$f_pageurl = preg_replace ("#\[(.*?)\]#","",$f_pageurl);
			$f_return[$f_prefix."pageurl"] = direct_linker ($f_connector_type,$f_pageurl);

			if ($this->data['ddbdatalinker_id_parent']) { $f_pageurl = (($f_connector_type == "asis") ? str_replace ("[oid]",$this->data['ddbdatalinker_id_parent'],$f_pageurl) : "m=datalinker&a=view&dsd=deid+".$this->data['ddbdatalinker_id_parent']); }
			else { $f_return[$f_prefix."pageurl_parent"] = ""; }

			if ($this->data['ddbdatalinker_id_main'])
			{
				$f_pageurl = str_replace ("[a]","posts",$f_connector);
				$f_pageurl = (($f_connector_type == "asis") ? str_replace ("[oid]",$this->data['ddbdatalinker_id_main'],$f_pageurl) : str_replace ("[oid]","dtid+{$this->data['ddbdatalinker_id_main']}++",$f_pageurl));
				$f_pageurl = preg_replace ("#\[(.*?)\]#","",$f_pageurl);
				$f_return[$f_prefix."pageurl_main"] = direct_linker ($f_connector_type,$f_pageurl);
			}
			else { $f_return[$f_prefix."pageurl_main"] = ""; }

			if ($this->data['ddbdatalinker_symbol'])
			{
				$f_symbol_path = $direct_classes['basic_functions']->varfilter ($direct_settings['discuss_datacenter_path_symbols'],"settings");
				$f_return[$f_prefix."symbol"] = direct_linker_dynamic ("url0","s=cache&dsd=dfile+".$f_symbol_path.$this->data['ddbdatalinker_symbol'],true,false);
			}
			else { $f_return[$f_prefix."symbol"] = ""; }

			if ($this->data['ddbdatalinker_sorting_date']) { $f_return[$f_prefix."time"] = $direct_classes['basic_functions']->datetime ("longdate&time",$this->data['ddbdatalinker_sorting_date'],$direct_settings['user']['timezone'],(direct_local_get ("datetime_dtconnect"))); }
			else { $f_return[$f_prefix."time"] = direct_local_get ("core_unknown"); }

			$f_return[$f_prefix."text"] = $direct_classes['formtags']->decode ($this->data['ddbdata_data']);

			if ($this->data_locked)
			{
				$f_return[$f_prefix."text"] = "<span style='font-weight:bold'>".(direct_local_get ("discuss_post_locked"))."</span>";
				if (($f_is_moderator)||($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 3)) { $f_return[$f_prefix."text"] .= "<br /><br />\n".$f_return[$f_prefix."text"]; }
			}

			if (($this->data['ddbdiscuss_posts_user_id'])&&($this->data['ddbusers_name'])) { $f_user_array = $direct_classes['kernel']->v_user_parse ($this->data['ddbdiscuss_posts_user_id'],$this->data,$f_prefix."user"); }
			else
			{
$f_user_array = array (
$f_prefix."userid" => "",
$f_prefix."username" => "",
$f_prefix."userpageurl" => "",
$f_prefix."usertype" => direct_local_get ("core_unknown"),
$f_prefix."usertitle" => "",
$f_prefix."useravatar" => "",
$f_prefix."useravatar_small" => "",
$f_prefix."useravatar_large" => "",
$f_prefix."userrating" => direct_local_get ("core_unknown"),
$f_prefix."usersignature" => ""
);
			}

			$f_return = array_merge ($f_return,$f_user_array);

			$f_return[$f_prefix."userip"] = (($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 3) ? $this->data['ddbdiscuss_posts_user_ip'] : "");
			$f_return[$f_prefix."locked"] = $this->data_locked;
			$f_return[$f_prefix."new"] = (($direct_cachedata['kernel_lastvisit'] < $this->data['ddbdatalinker_sorting_date']) ? true : false);

			if ($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']))
			{
				$f_moderator_check = false;
				$f_owner_check = false;

				if (($f_is_moderator)||($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 3)) { $f_moderator_check = true; }
				elseif ((!$f_topic_locked)&&(!$this->data_locked))
				{
					if (($direct_settings['user']['id'] == $this->data['ddbdiscuss_posts_user_id'])) { $f_owner_check = true; }
				}

				$f_target_connector = urlencode (base64_encode ($f_connector));

				if (($f_owner_check)||($f_moderator_check)) { $direct_classes['output']->options_insert (1,"discuss{$this->data['ddbdatalinker_id']}options","m=discuss&s=post&a=edit&dsd=dpid+{$this->data['ddbdatalinker_id']}++connector+".$f_target_connector,(direct_local_get ("discuss_post_edit")),$direct_settings['serviceicon_discuss_post_edit'],"url0"); }
				if ((!$this->data_locked)||($f_moderator_check)) { $direct_classes['output']->options_insert (1,"discuss{$this->data['ddbdatalinker_id']}options","m=discuss&s=post&a=reply&dsd=dtid+{$this->data['ddbdatalinker_id_main']}++dpid+{$this->data['ddbdatalinker_id']}++connector+".$f_target_connector,(direct_local_get ("discuss_post_reply")),$direct_settings['serviceicon_discuss_post_reply'],"url0"); }

				if ($f_moderator_check)
				{
					if ($this->data_locked) { $direct_classes['output']->options_insert (1,"discuss{$this->data['ddbdatalinker_id']}options","m=discuss&s=post&a=state&dsd=dpid+{$this->data['ddbdatalinker_id']}++dchange+unlock++connector+".$f_target_connector,(direct_local_get ("discuss_post_unlock")),$direct_settings['serviceicon_discuss_post_unlock'],"url0"); }
					else { $direct_classes['output']->options_insert (1,"discuss{$this->data['ddbdatalinker_id']}options","m=discuss&s=post&a=state&dsd=dpid+{$this->data['ddbdatalinker_id']}++dchange+lock++connector+".$f_target_connector,(direct_local_get ("discuss_post_lock")),$direct_settings['serviceicon_discuss_post_lock'],"url0"); }
				}

				if (($f_owner_check)||($f_moderator_check)||(!$this->data_locked)) { $f_return[$f_prefix."options"] = "discuss{$this->data['ddbdatalinker_id']}options"; }
			}
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->parse ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_post->set ($f_data)
/**
	* Sets (and overwrites existing) data for this post.
	*
	* @param  array $f_data Post data
	* @uses   direct_datalinker::set()
	* @uses   direct_debug()
	* @uses   direct_kernel_system::v_user_get()
	* @uses   direct_kernel_system::v_usertype_get_int()
	* @uses   USE_debug_reporting
	* @return boolean True on success (data valid and current user has read
	*         rights)
	* @since  v0.1.00
*/
	public function set ($f_data)
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->set (+f_data)- (#echo(__LINE__)#)"); }

		$f_return = parent::set ($f_data);

		if (($f_return)&&(isset ($f_data['ddbdiscuss_posts_locked'],$f_data['ddbdata_data'],$f_data['ddbdata_mode_user'],$f_data['ddbdata_mode_group'],$f_data['ddbdata_mode_all'])))
		{
			if (!isset ($f_data['ddbdiscuss_posts_user_id'],$f_data['ddbdiscuss_posts_user_ip']))
			{
				$f_data['ddbdiscuss_posts_user_id'] = $direct_settings['user']['id'];
				$f_data['ddbdiscuss_posts_user_ip'] = $direct_settings['user_ip'];
			}

			if (!$direct_settings['swg_ip_save2db']) { $f_data['ddbdiscuss_posts_user_ip'] = "unknown"; }

			if (!isset ($f_data['ddbusers_type'],$f_data['ddbusers_banned'],$f_data['ddbusers_deleted'],$f_data['ddbusers_name'],$f_data['ddbusers_title'],$f_data['ddbusers_avatar'],$f_data['ddbusers_signature'],$f_data['ddbusers_rating']))
			{
				$f_user_array = $direct_classes['kernel']->v_user_get ($f_data['ddbdiscuss_posts_user_id']);
				if ($f_user_array) { $f_data = array_merge ($f_data,$f_user_array); }
			}

			if (!isset ($f_data['ddbdata_sid'])) { $f_data['ddbdata_sid'] = $this->data['ddbdatalinker_sid']; }

			$this->set_extras ($f_data,(array ("ddbdiscuss_posts_user_id","ddbdiscuss_posts_user_ip","ddbdiscuss_posts_locked","ddbusers_type","ddbusers_banned","ddbusers_deleted","ddbusers_name","ddbusers_title","ddbusers_avatar","ddbusers_signature","ddbusers_rating","ddbdata_data","ddbdata_sid","ddbdata_mode_user","ddbdata_mode_group","ddbdata_mode_all")));
			$this->data_tid = $f_data['ddbdatalinker_id_main'];
			$this->data_locked = ($this->data['ddbdiscuss_posts_locked'] ? true : false);

			$f_result_array = $this->get_rights ();
			$this->data_readable = $f_result_array[0];
			$this->data_readable_group = $f_result_array[1];
			$this->data_writable = $f_result_array[2];
			$this->data_writable_group = $f_result_array[3];

			if ((!$this->data_readable)&&(!$this->data_readable_group)) { $f_return = false; }
		}
		else { $f_return = false; }

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->set ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_post->set_update ($f_data,$f_post_content = true,$f_post_settings = true)
/**
	* Updates (and overwrites) the existing DataLinker entry and saves it to the
	* database. Note: If "set()" fails because of permission problems 
	* "update()" has to be called manually to write data to the database.
	* Please make sure that this is the intended behavior. You can use
	* "is_empty()" to check for the current data state of this object.
	*
	* @param  array $f_data Post entry
	* @param  boolean $f_post_content True to update the data entry
	* @param  boolean $f_post_settings True to update the settings entry
	* @uses   direct_discuss_post::set()
	* @uses   direct_discuss_post::update()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @since  v0.1.00
*/
	public function set_update ($f_data,$f_post_content = true,$f_post_settings = true)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->set_update (+f_data,+f_post_content,+f_post_settings)- (#echo(__LINE__)#)"); }

		if ($this->set ($f_data))
		{
			$this->data_insert_mode = false;
			return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->set_update ()- (#echo(__LINE__)#)",(:#*/$this->update ($f_post_content,$f_post_settings)/*#ifdef(DEBUG):),true):#*/;
		}
		else { return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->set_update ()- (#echo(__LINE__)#)",:#*/false/*#ifdef(DEBUG):,true):#*/; }
	}

	//f// direct_discuss_post->update ($f_post_content = true,$f_post_settings = true,$f_insert_mode_deactivate = true)
/**
	* Writes the object data to the database.
	*
	* @param  boolean $f_post_content True to update the data entry
	* @param  boolean $f_post_settings True to update the settings entry
	* @uses   direct_db::define_values()
	* @uses   direct_db::define_values_keys()
	* @uses   direct_db::define_values_encode()
	* @uses   direct_db::init_replace()
	* @uses   direct_db::optimize_random()
	* @uses   direct_db::query_exec()
	* @uses   direct_db::v_transaction_begin()
	* @uses   direct_db::v_transaction_commit()
	* @uses   direct_db::v_transaction_rollback()
	* @uses   direct_dbsync_event()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	public function update ($f_post_content = true,$f_post_settings = true,$f_insert_mode_deactivate = true)
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (3,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->update (+f_post_content,+f_post_settings,+f_insert_mode_deactivate)- (#echo(__LINE__)#)"); }

		if (empty ($this->data_changed)) { $f_return = true; }
		else
		{
			$direct_classes['db']->v_transaction_begin ();
			$f_return = parent::update ($f_post_settings,$f_post_settings,false);

			if (($f_return)&&(count ($this->data) > 1))
			{
				if (($f_post_settings)&&($this->is_changed (array ("ddbdiscuss_posts_user_id","ddbdiscuss_posts_user_ip","ddbdiscuss_posts_locked"))))
				{
					if ($this->data_insert_mode) { $direct_classes['db']->init_insert ($direct_settings['discuss_posts_table']); }
					else { $direct_classes['db']->init_update ($direct_settings['discuss_posts_table']); }

					$f_update_values = "<sqlvalues>";
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdatalinker_id_object']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['discuss_posts_table'].".ddbdiscuss_posts_id",$this->data['ddbdatalinker_id_object'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdiscuss_posts_user_id']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['discuss_posts_table'].".ddbdiscuss_posts_user_id",$this->data['ddbdiscuss_posts_user_id'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdiscuss_posts_user_ip']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['discuss_posts_table'].".ddbdiscuss_posts_user_ip",$this->data['ddbdiscuss_posts_user_ip'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdiscuss_posts_locked']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['discuss_posts_table'].".ddbdiscuss_posts_locked",$this->data['ddbdiscuss_posts_locked'],"string"); }
					$f_update_values .= "</sqlvalues>";

					$direct_classes['db']->define_set_attributes ($f_update_values);
					if (!$this->data_insert_mode) { $direct_classes['db']->define_row_conditions ("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['discuss_posts_table'].".ddbdiscuss_posts_id",$this->data['ddbdatalinker_id_object'],"string"))."</sqlconditions>"); }
					$f_return = $direct_classes['db']->query_exec ("co");

					if ($f_return)
					{
						if (function_exists ("direct_dbsync_event"))
						{
							if ($this->data_insert_mode) { direct_dbsync_event ($direct_settings['discuss_posts_table'],"insert",("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['discuss_posts_table'].".ddbdiscuss_posts_id",$this->data['ddbdatalinker_id_object'],"string"))."</sqlconditions>")); }
							else { direct_dbsync_event ($direct_settings['discuss_posts_table'],"update",("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['discuss_posts_table'].".ddbdiscuss_posts_id",$this->data['ddbdatalinker_id_object'],"string"))."</sqlconditions>")); }
						}

						if (!$direct_settings['swg_auto_maintenance']) { $direct_classes['db']->optimize_random ($direct_settings['discuss_posts_table']); }
					}
				}

				if (($f_return)&&($f_post_content)&&($this->is_changed (array ("ddbdatalinker_id_main","ddbdiscuss_posts_user_id","ddbdata_data","ddbdata_sid","ddbdata_mode_user","ddbdata_mode_group","ddbdata_mode_all"))))
				{
					if (isset ($this->data['ddbdata_data']))
					{
						if ($this->data_insert_mode) { $direct_classes['db']->init_insert ($direct_settings['data_table']); }
						else { $direct_classes['db']->init_update ($direct_settings['data_table']); }

						$f_update_values = "<sqlvalues>";
						if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdatalinker_id_object']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['data_table'].".ddbdata_id",$this->data['ddbdatalinker_id_object'],"string"); }
						if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdatalinker_id_main']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['data_table'].".ddbdata_id_cat",$this->data['ddbdatalinker_id_main'],"string"); }
						if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdiscuss_posts_user_id']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['data_table'].".ddbdata_owner",$this->data['ddbdiscuss_posts_user_id'],"string"); }
						if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdata_data']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['data_table'].".ddbdata_data",$this->data['ddbdata_data'],"string"); }
						if ($this->data_insert_mode) { $f_update_values .= "<element1 attribute='{$direct_settings['data_table']}.ddbdata_sid' value='{$this->data_sid}' type='string' />"; }
						if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdata_mode_user']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['data_table'].".ddbdata_mode_user",$this->data['ddbdata_mode_user'],"string"); }
						if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdata_mode_group']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['data_table'].".ddbdata_mode_group",$this->data['ddbdata_mode_group'],"string"); }
						if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdata_mode_all']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['data_table'].".ddbdata_mode_all",$this->data['ddbdata_mode_all'],"string"); }
						$f_update_values .= "</sqlvalues>";

						$direct_classes['db']->define_set_attributes ($f_update_values);
						if (!$this->data_insert_mode) { $direct_classes['db']->define_row_conditions ("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['data_table'].".ddbdata_id",$this->data['ddbdatalinker_id_object'],"string"))."</sqlconditions>"); }
						$f_return = $direct_classes['db']->query_exec ("co");

						if ($f_return)
						{
							if (function_exists ("direct_dbsync_event"))
							{
								if ($this->data_insert_mode) { direct_dbsync_event ($direct_settings['data_table'],"insert",("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['data_table'].".ddbdata_id",$this->data['ddbdatalinker_id_object'],"string"))."</sqlconditions>")); }
								else { direct_dbsync_event ($direct_settings['data_table'],"update",("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['data_table'].".ddbdata_id",$this->data['ddbdatalinker_id_object'],"string"))."</sqlconditions>")); }
							}

							if (!$direct_settings['swg_auto_maintenance']) { $direct_classes['db']->optimize_random ($direct_settings['data_table']); }
						}
					}
					else { $f_return = false; }
				}
			}

			if (($f_insert_mode_deactivate)&&($this->data_insert_mode)) { $this->data_insert_mode = false; }

			if ($f_return) { $direct_classes['db']->v_transaction_commit (); }
			else { $direct_classes['db']->v_transaction_rollback (); }
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_post_handler->update ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}
}

/* -------------------------------------------------------------------------
Mark this class as the most up-to-date one
------------------------------------------------------------------------- */

define ("CLASS_direct_discuss_post",true);

//j// Script specific commands

if (!isset ($direct_settings['discuss_datacenter_path_symbols'])) { $direct_settings['discuss_datacenter_path_symbols'] = $direct_settings['path_themes']."/$direct_settings[theme]/"; }
if (!isset ($direct_settings['serviceicon_discuss_post_edit'])) { $direct_settings['serviceicon_discuss_post_edit'] = "mini_default_option.png"; }
if (!isset ($direct_settings['serviceicon_discuss_post_lock'])) { $direct_settings['serviceicon_discuss_post_lock'] = "mini_default_option.png"; }
if (!isset ($direct_settings['serviceicon_discuss_post_reply'])) { $direct_settings['serviceicon_discuss_post_reply'] = "mini_default_option.png"; }
if (!isset ($direct_settings['serviceicon_discuss_post_unlock'])) { $direct_settings['serviceicon_discuss_post_unlock'] = "mini_default_option.png"; }
if (!isset ($direct_settings['swg_auto_maintenance'])) { $direct_settings['swg_auto_maintenance'] = false; }
if (!isset ($direct_settings['swg_ip_save2db'])) { $direct_settings['swg_ip_save2db'] = true; }
}

//j// EOF
?>