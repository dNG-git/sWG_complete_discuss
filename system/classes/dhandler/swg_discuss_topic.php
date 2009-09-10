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

$g_continue_check = ((defined ("CLASS_direct_discuss_topic")) ? false : true);
if (!defined ("CLASS_direct_datalinker")) { $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_datalinker.php"); }
if (!defined ("CLASS_direct_datalinker")) { $g_continue_check = false; }

if ($g_continue_check)
{
//c// direct_discuss_topic
/**
* This abstraction layer provides topic (discuss) specific functions.
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
class direct_discuss_topic extends direct_datalinker
{
/**
	* @var array $class_posts Cached post objects
*/
	protected $class_posts;
/**
	* @var string $data_did Board ID to be used
*/
	protected $data_did;
/**
	* @var boolean $data_editable True if the current user is allowed to edit
	*      topic data
*/
	protected $data_editable;
/**
	* @var boolean $data_locked True if this topic is locked
*/
	protected $data_locked;
/**
	* @var boolean $data_sticky True if this topic is sticky
*/
	protected $data_sticky;
/**
	* @var boolean $data_writable True if the current user is allowed write
	*      new posts
*/
	protected $data_writable;

/* -------------------------------------------------------------------------
Extend the class
------------------------------------------------------------------------- */

	//f// direct_discuss_topic->__construct ()
/**
	* Constructor (PHP5) __construct (direct_discuss_topic)
	*
	* @param mixed $f_data String containing the allowed board ID or an array
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->__construct (direct_discuss_topic)- (#echo(__LINE__)#)"); }

		if (!defined ("CLASS_direct_formtags")) { $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/swg_formtags.php"); }
		if (!isset ($direct_classes['formtags'])) { direct_class_init ("formtags"); }

/* -------------------------------------------------------------------------
My parent should be on my side to get the work done
------------------------------------------------------------------------- */

		parent::__construct ();

/* -------------------------------------------------------------------------
Informing the system about available functions 
------------------------------------------------------------------------- */

		$this->functions['add_posts'] = true;
		$this->functions['define_did'] = true;
		$this->functions['define_editable'] = true;
		$this->functions['define_lock'] = true;
		$this->functions['define_stick'] = true;
		$this->functions['define_writable'] = true;
		$this->functions['get_posts'] = true;
		$this->functions['get_posts_since_date'] = true;
		$this->functions['is_editable'] = true;
		$this->functions['is_locked'] = true;
		$this->functions['is_sticky'] = true;
		$this->functions['is_writable'] = true;
		$this->functions['parse'] = isset ($direct_classes['formtags']);
		$this->functions['remove_posts'] = true;

/* -------------------------------------------------------------------------
Set up an additional topic class element :)
------------------------------------------------------------------------- */

		$this->class_posts = array ();
		$this->data_did = "";
		$this->data_editable = false;
		$this->data_locked = false;
		$this->data_sid = "cb41ecf6e90a594dcea60b6140251d62";
		$this->data_sticky = false;
		$this->data_writable = false;

		if (is_string ($f_data)) { $this->data_did = $f_data; }
		elseif (isset ($f_data['did'])) { $this->data_did = $f_data['did']; }
	}

	//f// direct_discuss_topic->add_posts ($f_count,$f_update = true)
/**
	* Increases the posts counter.
	*
	* @param  number $f_count Number to be added to the post counter
	* @param  boolean $f_update True to update the database entry
	* @uses   direct_datalinker::update()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	public function add_posts ($f_count,$f_update = true)
	{
		if (USE_debug_reporting) { direct_debug (8,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->add_posts ($f_count,+f_update)- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (9,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->add_posts ()- (#echo(__LINE__)#)",(:#*/$this->add_objects ($f_count,$f_update)/*#ifdef(DEBUG):),true):#*/;
	}

	//f// direct_discuss_topic->define_did ($f_did)
/**
	* Sets the board ID of this topic.
	*
	* @param  string $f_did Board ID to use
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return string Accepted board ID
	* @since  v0.1.00
*/
	public function define_did ($f_did)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->define_did (+f_did)- (#echo(__LINE__)#)"); }

		if (is_string ($f_did)) { $this->data_did = $f_did; }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->define_did ()- (#echo(__LINE__)#)",:#*/$this->data_did/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_topic->define_editable ($f_state = NULL)
/**
	* Sets the right to edit topic meta data.
	*
	* @param  mixed $f_state Boolean indicating the state or NULL to switch
	*         automatically
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean Accepted state
	* @since  v0.1.00
*/
	public function define_editable ($f_state = NULL)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->define_editable (+f_state)- (#echo(__LINE__)#)"); }

		if (((is_bool ($f_state))||(is_string ($f_state)))&&($f_state)) { $this->data_editable = true; }
		elseif (($f_state === NULL)&&(!$this->data_editable)) { $this->data_editable = true; }
		else { $this->data_editable = false; }

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->define_editable ()- (#echo(__LINE__)#)",:#*/$this->data_editable/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_topic->define_lock ($f_state = NULL,$f_update = false)
/**
	* Sets the locking state of this topic.
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->define_lock (+f_state,+f_update)- (#echo(__LINE__)#)"); }
		$f_return = false;

		if (count ($this->data) > 1)
		{
			if (((is_bool ($f_state))||(is_string ($f_state)))&&($f_state)) { $f_return = true; }
			elseif (($f_state === NULL)&&(!$this->data['ddbdiscuss_topics_locked'])) { $f_return = true; }
			$this->data_locked = $f_return;

			$this->data['ddbdiscuss_topics_locked'] = ($f_return ? 1 : 0);
			$this->data_changed['ddbdiscuss_topics_locked'] = true;
			if ($f_update) { $this->update (); }
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->define_lock ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_topic->define_stick ($f_state = NULL,$f_update = false)
/**
	* Sets the sticking state of this topic.
	*
	* @param  mixed $f_state Boolean indicating the state or NULL to switch
	*         automatically
	* @param  boolean $f_update True to update the database entry
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean Accepted state
	* @since  v0.1.00
*/
	public function define_stick ($f_state = NULL,$f_update = false)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -contentor_doc->define_stick (+f_state,+f_update)- (#echo(__LINE__)#)"); }
		$f_return = false;

		if (count ($this->data) > 1)
		{
			if (((is_bool ($f_state))||(is_string ($f_state)))&&($f_state)) { $f_return = true; }
			elseif (($f_state === NULL)&&(!$this->data['ddbdatalinker_position'])) { $f_return = true; }
			$this->data_sticky = $f_return;

			$this->data['ddbdatalinker_position'] = ($f_return ? 1 : 0);
			$this->data_changed['ddbdatalinker_position'] = true;	
			if ($f_update) { parent::update (true,false); }
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -contentor_doc->define_stick ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_topic->define_writable ($f_state = NULL)
/**
	* Sets the writing right state of this topic.
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->define_writable (+f_state)- (#echo(__LINE__)#)"); }

		if (((is_bool ($f_state))||(is_string ($f_state)))&&($f_state)) { $this->data_writable = true; }
		elseif (($f_state === NULL)&&(!$this->data_writable)) { $this->data_writable = true; }
		else { $this->data_writable = false; }

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->define_writable ()- (#echo(__LINE__)#)",:#*/$this->data_writable/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_board->get_aid ($f_attributes = NULL,$f_values = "")
/**
	* Request and load the topic object based on a custom attribute ID.
	* Please note that only attributes of type "string" are supported.
	*
	* @param  mixed $f_attributes Attribute name(s) (array or string)
	* @param  mixed $f_values Attribute value(s) (array or string)
	* @uses   direct_datalinker::define_extra_attributes()
	* @uses   direct_datalinker::define_extra_conditions()
	* @uses   direct_datalinker::define_extra_joins()
	* @uses   direct_datalinker::get_aid()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return mixed Topic data array; false on error
	* @since  v0.1.00
*/
	public function get_aid ($f_attributes = NULL,$f_values = "")
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (3,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->get_aid (+f_attributes,+f_values)- (#echo(__LINE__)#)"); }

		$f_return = false;

		if (count ($this->data) > 1) { $f_return = $this->data; }
		elseif ((is_array ($f_values))||(is_string ($f_values)))
		{
			$this->define_extra_attributes (array ($direct_settings['discuss_topics_table'].".*",$direct_settings['users_table'].".ddbusers_type",$direct_settings['users_table'].".ddbusers_banned",$direct_settings['users_table'].".ddbusers_deleted",$direct_settings['users_table'].".ddbusers_name",$direct_settings['users_table'].".ddbusers_title",$direct_settings['users_table'].".ddbusers_avatar",$direct_settings['users_table'].".ddbusers_signature",$direct_settings['users_table'].".ddbusers_rating"));

$f_select_joins = array (
array ("type" => "left-outer-join","table" => $direct_settings['discuss_topics_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['discuss_topics_table']}.ddbdiscuss_topics_id' value='{$direct_settings['datalinker_table']}.ddbdatalinker_id_object' type='attribute' /></sqlconditions>"),
array ("type" => "left-outer-join","table" => $direct_settings['users_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['users_table']}.ddbusers_id' value='{$direct_settings['discuss_topics_table']}.ddbdiscuss_topics_last_id' type='attribute' /></sqlconditions>")
);

			$this->define_extra_joins ($f_select_joins);

			if (strlen ($this->data_did)) { $this->define_extra_conditions ($direct_classes['db']->define_row_conditions_encode ($direct_settings['datalinker_table'].".ddbdatalinker_id_parent",$this->data_did,"string")); }

			$f_result_array = parent::get_aid ($f_attributes,$f_values);

			if (($f_result_array)&&(isset ($f_result_array['ddbdiscuss_topics_id'])))
			{
				$this->data = $f_result_array;
				$this->data_locked = ($this->data['ddbdiscuss_topics_locked'] ? true : false);
				$this->data_sticky = ($this->data['ddbdatalinker_position'] ? true : false);

				if ($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 3)
				{
					$this->data_editable = true;
					$this->data_writable = true;
				}
				elseif (($direct_settings['discuss_account_status_ex'])||($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type'])))
				{
					if (!$this->data_locked) { $this->data_writable = true; }
					if (($this->data_writable)&&($direct_settings['user']['id'] == $this->data['ddbdiscuss_topics_owner_id'])) { $this->data_editable = true; }
				}

				$f_return = $this->data;
			}
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->get_aid ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_topic->get_posts ($f_offset = 0,$f_perpage = "",$f_sorting_mode = "time-asc")
/**
	* Returns all subobjects for the DataLinker with the given service ID and
	* type.
	*
	* @param  integer $f_offset Offset for the result list
	* @param  integer $f_perpage Object count limit for the result list
	* @param  string $f_sorting_mode Sorting algorithm
	* @uses   direct_datalinker::define_extra_attributes()
	* @uses   direct_datalinker::define_extra_joins()
	* @uses   direct_datalinker::get_subs()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return array Array with pointers to the posts
	* @since  v0.1.00
*/
	public function get_posts ($f_offset = 0,$f_perpage = "",$f_sorting_mode = "time-asc")
	{
		global $direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->get_posts ($f_offset,$f_perpage,$f_sorting_mode)- (#echo(__LINE__)#)"); }

		$f_return = array ();
		$f_cache_signature = md5 ($this->data['ddbdatalinker_id_object'].$f_offset.$f_perpage.$f_sorting_mode);

		if (isset ($this->class_posts[$f_cache_signature])) { $f_return =& $this->class_posts[$f_cache_signature]; }
		elseif (isset ($this->data['ddbdatalinker_id_object']))
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

			$this->class_posts[$f_cache_signature] = parent::get_subs ("direct_discuss_post",$this->data['ddbdatalinker_id_object'],NULL,"cb41ecf6e90a594dcea60b6140251d62",6,$f_offset,$f_perpage,$f_sorting_mode);
			// md5 ("discuss")

			$f_return =& $this->class_posts[$f_cache_signature];
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->get_posts ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_topic->get_posts_since_date ($f_date,$f_offset = 0,$f_perpage = "",$f_sorting_mode = "time-desc",$f_count_only = false)
/**
	* Returns all subobjects for the DataLinker with the given service ID and
	* type that are newer than a specific date.
	*
	* @param  integer $f_date UNIX timestamp for the oldest valid post date
	* @param  integer $f_offset Offset for the result list
	* @param  integer $f_perpage Object count limit for the result list
	* @param  string $f_sorting_mode Sorting algorithm
	* @param  boolean $f_count_only True to return the number of posts
	* @uses   direct_datalinker::define_extra_attributes()
	* @uses   direct_datalinker::define_extra_conditions()
	* @uses   direct_datalinker::define_extra_joins()
	* @uses   direct_datalinker::get_subs()
	* @uses   direct_db::define_row_conditions_encode()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return mixed Array with pointers to the posts since the last visit or
	*         post quantity
	* @since  v0.1.00
*/
	public function get_posts_since_date ($f_date,$f_offset = 0,$f_perpage = "",$f_sorting_mode = "time-desc",$f_count_only = false)
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->get_posts_since_date ($f_date,$f_offset,$f_perpage,$f_sorting_mode,+f_count_only)- (#echo(__LINE__)#)"); }

		if ($f_count_only)
		{
			$f_return = 0;
			$f_cache_signature = md5 ("psdc".$this->data['ddbdatalinker_id_object']);
		}
		else
		{
			$f_return = array ();
			$f_cache_signature = md5 ("psd".$this->data['ddbdatalinker_id_object'].$f_offset.$f_perpage.$f_sorting_mode);
		}

		if (isset ($this->class_posts[$f_cache_signature])) { $f_return =& $this->class_posts[$f_cache_signature]; }
		elseif (($f_count_only)&&($f_date < 1)) { $f_return = $this->data['ddbdatalinker_objects']; }
		elseif (isset ($this->data['ddbdatalinker_id_object']))
		{
			if ($f_count_only) { $f_select_attributes = array ("count-rows({$direct_settings['datalinker_table']}.ddbdatalinker_id)"); }
			else
			{
$f_select_attributes = array ($direct_settings['discuss_posts_table'].".*",$direct_settings['data_table'].".ddbdata_data",$direct_settings['data_table'].".ddbdata_sid",$direct_settings['data_table'].".ddbdata_mode_user",$direct_settings['data_table'].".ddbdata_mode_group",$direct_settings['data_table'].".ddbdata_mode_all",
$direct_settings['users_table'].".ddbusers_type",$direct_settings['users_table'].".ddbusers_banned",$direct_settings['users_table'].".ddbusers_deleted",$direct_settings['users_table'].".ddbusers_name",$direct_settings['users_table'].".ddbusers_title",$direct_settings['users_table'].".ddbusers_avatar",$direct_settings['users_table'].".ddbusers_signature",$direct_settings['users_table'].".ddbusers_rating");
			}

			$this->define_extra_attributes ($f_select_attributes);

			if (!$f_count_only)
			{
$this->define_extra_joins (array (
array ("type" => "left-outer-join","table" => $direct_settings['discuss_posts_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['discuss_posts_table']}.ddbdiscuss_posts_id' value='{$direct_settings['datalinker_table']}.ddbdatalinker_id_object' type='attribute' /></sqlconditions>"),
array ("type" => "left-outer-join","table" => $direct_settings['users_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['users_table']}.ddbusers_id' value='{$direct_settings['discuss_posts_table']}.ddbdiscuss_posts_user_id' type='attribute' /></sqlconditions>"),
array ("type" => "left-outer-join","table" => $direct_settings['data_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['data_table']}.ddbdata_id' value='{$direct_settings['datalinker_table']}.ddbdatalinker_id_object' type='attribute' /></sqlconditions>")
));
			}

			$this->define_extra_conditions ($direct_classes['db']->define_row_conditions_encode ($direct_settings['datalinkerd_table'].".ddbdatalinker_sorting_date",$f_date,"number",">"));

			if ($f_count_only) { $this->class_posts[$f_cache_signature] = parent::get_subs ("",$this->data['ddbdatalinker_id_object'],NULL,"cb41ecf6e90a594dcea60b6140251d62",6,0,1,"time-desc"); }
			else { $this->class_posts[$f_cache_signature] = parent::get_subs ("direct_discuss_post",$this->data['ddbdatalinker_id_object'],NULL,"cb41ecf6e90a594dcea60b6140251d62",6,$f_offset,$f_perpage,$f_sorting_mode); }
			// md5 ("discuss")

			$f_return =& $this->class_posts[$f_cache_signature];
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->get_posts_since_date ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_topic->is_editable ()
/**
	* Returns true if the current user is allowed to read documents in this
	* category.
	*
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True or false
	* @since  v0.1.00
*/
	public function is_editable ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->is_editable ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->is_editable ()- (#echo(__LINE__)#)",:#*/$this->data_editable/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_topic->is_locked ()
/**
	* Returns true if the topic is locked.
	*
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True or false
	* @since  v0.1.00
*/
	public function is_locked ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->is_locked ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->is_locked ()- (#echo(__LINE__)#)",:#*/$this->data_locked/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_topic->is_sticky ()
/**
	* Returns true if the topic is sticky.
	*
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True or false
	* @since  v0.1.00
*/
	public function is_sticky ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -contentor_doc->is_sticky ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->data_writable ()- (#echo(__LINE__)#)",:#*/$this->data_sticky/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_topic->is_writable ()
/**
	* Returns true if the current user is allowed to create new and edit his own
	* documents in this category.
	*
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True or false
	* @since  v0.1.00
*/
	public function is_writable ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->is_writable ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->data_writable ()- (#echo(__LINE__)#)",:#*/$this->data_writable/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_topic->parse ($f_connector,$f_connector_type = "url0",$f_prefix = "")
/**
	* Parses this topic and returns valid (X)HTML.
	*
	* @param  string $f_connector Connector for links
	* @param  string $f_connector_type Linking mode: "url0" for internal links,
	*         "url1" for external ones, "form" to create hidden fields or
	*         "optical" to remove parts of a very long string.
	* @param  string $f_prefix Key prefix
	* @uses   direct_basic_functions::varfilter()
	* @uses   direct_datalinker::parse()
	* @uses   direct_debug()
	* @uses   direct_formtags::decode()
	* @uses   direct_html_encode_special()
	* @uses   direct_kernel_system::v_user_parse()
	* @uses   direct_linker()
	* @uses   direct_output_pages_generator()
	* @uses   USE_debug_reporting
	* @return array Output data
	* @since  v0.1.00
*/
	public function parse ($f_connector,$f_connector_type = "url0",$f_prefix = "")
	{
		global $direct_cachedata,$direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->parse ($f_connector,$f_connector_type,$f_prefix)- (#echo(__LINE__)#)"); }

		$f_return = parent::parse ($f_prefix);

		if (($f_return)&&(count ($this->data) > 1))
		{
			$f_return[$f_prefix."id"] = "swgdhandlerdiscusstopic".$this->data['ddbdatalinker_id'];
			if (($f_connector_type != "asis")&&(strpos ($f_connector,"javascript:") === 0)) { $f_connector_type = "asis"; }
			$f_pageurl_oid = ($this->data['ddbdatalinker_id_main'] ? $this->data['ddbdatalinker_id'] : $this->data['ddbdatalinker_id_object']);

			$f_pageurl = str_replace ("[a]","posts",$f_connector);
			$f_pageurl = (($f_connector_type == "asis") ? str_replace ("[oid]",$f_pageurl_oid,$f_pageurl) : str_replace ("[oid]","dtid+$f_pageurl_oid++",$f_pageurl));
			$f_pageurl = preg_replace ("#\[(.*?)\]#","",$f_pageurl);
			$f_return[$f_prefix."pageurl"] = direct_linker ($f_connector_type,$f_pageurl);

			if ($f_return[$f_prefix."views_counted"])
			{
				$f_source = urlencode (base64_encode ($f_pageurl));
				$f_return[$f_prefix."pageurl_counted"] = direct_linker ("url0","m=datalinker&a=count&dsd=deid+$f_pageurl_oid++source+".$f_source);
			}

			if ($this->data['ddbdatalinker_id_parent'])
			{
				$f_pageurl = str_replace ("[a]","topics",$f_connector);
				$f_pageurl = (($f_connector_type == "asis") ? str_replace ("[oid]",$this->data['ddbdatalinker_id_parent'],$f_pageurl) : str_replace ("[oid]","ddid+{$this->data['ddbdatalinker_id_parent']}++",$f_pageurl));
				$f_pageurl = preg_replace ("#\[(.*?)\]#","",$f_pageurl);
				$f_return[$f_prefix."pageurl_parent"] = direct_linker ($f_connector_type,$f_pageurl);
			}
			else { $f_return[$f_prefix."pageurl_parent"] = ""; }

			if ($this->data['ddbdatalinker_id_main'])
			{
				$f_pageurl = str_replace ("[a]","topics",$f_connector);
				$f_pageurl = (($f_connector_type == "asis") ? str_replace ("[oid]",$this->data['ddbdatalinker_id_main'],$f_pageurl) : str_replace ("[oid]","ddid+{$this->data['ddbdatalinker_id_main']}++",$f_pageurl));
				$f_pageurl = preg_replace ("#\[(.*?)\]#","",$f_pageurl);
				$f_return[$f_prefix."pageurl_main"] = direct_linker ($f_connector_type,$f_pageurl);
			}
			else { $f_return[$f_prefix."pageurl_main"] = ""; }

			$f_return[$f_prefix."owner_id"] = $this->data['ddbdiscuss_topics_owner_id'];

			if ($this->data['ddbdatalinker_symbol'])
			{
				$f_symbol_path = $direct_classes['basic_functions']->varfilter ($direct_settings['discuss_datacenter_path_symbols'],"settings");
				$f_return[$f_prefix."symbol"] = direct_linker_dynamic ("url0","s=cache&dsd=dfile+".$f_symbol_path.$this->data['ddbdatalinker_symbol'],true,false);
			}
			else { $f_return[$f_prefix."symbol"] = ""; }

			if ($this->data['ddbdatalinker_sorting_date']) { $f_return[$f_prefix."time"] = $direct_classes['basic_functions']->datetime ("shortdate&time",$this->data['ddbdatalinker_sorting_date'],$direct_settings['user']['timezone'],(direct_local_get ("datetime_dtconnect"))); }
			else { $f_return[$f_prefix."time"] = direct_local_get ("core_unknown"); }

			$f_return[$f_prefix."desc"] = $direct_classes['formtags']->decode ($this->data['ddbdiscuss_topics_desc']);

			$f_pageurl = str_replace ("[a]","posts",$f_connector);
			$f_pageurl = (($f_connector_type == "asis") ? str_replace ("[oid]",$f_pageurl_oid,$f_pageurl) : str_replace ("[oid]","dtid+$f_pageurl_oid++",$f_pageurl));
			$f_pageurl = preg_replace ("#\[(.*?)\]#","",$f_pageurl);

			$f_pages = ceil ($this->data['ddbdatalinker_objects'] / $direct_settings['discuss_posts_per_page']);
			$f_return[$f_prefix."pages"] = direct_output_pages_generator ($f_pageurl,$f_pages,"",false,$f_connector_type);

			if ($f_return[$f_prefix."views_counted"])
			{
				$f_source = urlencode (base64_encode ($f_source));
				$f_return[$f_prefix."pages_counted"] = direct_output_pages_generator ("m=datalinker&a=count&dsd=deid+$f_pageurl_oid++dadsd+page++source+{$f_source}++",$f_pages,"",false,$f_connector_type);
			}

			if (($this->data['ddbdiscuss_topics_last_id'])&&($this->data['ddbusers_name']))
			{
				$f_return[$f_prefix."last_id"] = $this->data['ddbdiscuss_topics_last_id'];
				$f_user_array = $direct_classes['kernel']->v_user_parse ($this->data['ddbdiscuss_topics_last_id'],$this->data,$f_prefix."user");
			}
			else
			{
				$f_return[$f_prefix."last_id"] = "";

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

			if ($this->data['ddbdatalinker_sorting_date'])
			{
				$f_return[$f_prefix."last_time"] = $direct_classes['basic_functions']->datetime ("shortdate&time",$this->data['ddbdatalinker_sorting_date'],$direct_settings['user']['timezone'],(direct_local_get ("datetime_dtconnect")));
				$f_return[$f_prefix."last_preview"] = direct_html_encode_special ($this->data['ddbdiscuss_topics_last_preview']);

				$f_pageurl = "m=discuss&a=jump&dsd=idata+t;$f_pageurl_oid;latest++connector+".(urlencode (base64_encode ($f_connector)));
				$f_return[$f_prefix."last_post_jump"] = direct_linker ("url0",$f_pageurl);

				$f_source = urlencode (base64_encode ($f_pageurl));
				$f_return[$f_prefix."last_post_jump_counted"] = direct_linker ("url0","m=datalinker&a=count&dsd=deid+$f_pageurl_oid++source+".$f_source);
			}
			else
			{
				$f_return[$f_prefix."last_time"] = direct_local_get ("core_unknown");
				$f_return[$f_prefix."last_preview"] = "";
				$f_return[$f_prefix."last_post_jump"] = "";
			}

			$f_return[$f_prefix."posts"] = $this->data['ddbdatalinker_objects'];
			$f_return[$f_prefix."locked"] = $this->data_locked;
			$f_return[$f_prefix."sticky"] = $this->data_sticky;
			$f_return[$f_prefix."new"] = (($direct_cachedata['kernel_lastvisit'] < $this->data['ddbdatalinker_sorting_date']) ? true : false);
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->parse ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_topic->remove_posts ($f_count,$f_update = true)
/**
	* Decreases the post counter.
	*
	* @param  number $f_count Number to be removed from the post counter
	* @param  boolean $f_update True to update the database entry
	* @uses   direct_datalinker::update()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	public function remove_posts ($f_count,$f_update = true)
	{
		if (USE_debug_reporting) { direct_debug (8,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->remove_posts ($f_count,+f_update)- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (9,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->remove_posts ()- (#echo(__LINE__)#)",(:#*/$this->remove_objects ($f_count,$f_update)/*#ifdef(DEBUG):),true):#*/;
	}

	//f// direct_discuss_topic->set ($f_data)
/**
	* Sets (and overwrites existing) data for this topic.
	*
	* @param  array $f_data Topic data
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->set (+f_data)- (#echo(__LINE__)#)"); }

		if (!isset ($f_data['ddbdatalinker_sorting_date'],$f_data['ddbdiscuss_topics_last_id'],$f_data['ddbdiscuss_topics_last_ip'],$f_data['ddbdiscuss_topics_last_preview']))
		{
			$f_data['ddbdatalinker_sorting_date'] = 0;
			$f_data['ddbdiscuss_topics_last_id'] = "";
			$f_data['ddbdiscuss_topics_last_ip'] = "";
			$f_data['ddbdiscuss_topics_last_preview'] = "";
		}

		$f_return = parent::set ($f_data);

		if (($f_return)&&(isset ($f_data['ddbdiscuss_topics_time'],$f_data['ddbdiscuss_topics_locked'])))
		{
			if (!isset ($f_data['ddbdiscuss_topics_desc'])) { $f_data['ddbdiscuss_topics_desc'] = ""; }

			if (!isset ($f_data['ddbdiscuss_topics_owner_id'],$f_data['ddbdiscuss_topics_owner_ip']))
			{
				$f_data['ddbdiscuss_topics_owner_id'] = $direct_settings['user']['id'];
				$f_data['ddbdiscuss_topics_owner_ip'] = $direct_settings['user_ip'];
			}

			if (!$direct_settings['swg_ip_save2db']) { $f_data['ddbdiscuss_topics_owner_ip'] = "unknown"; }

			if (!isset ($f_data['ddbusers_type'],$f_data['ddbusers_banned'],$f_data['ddbusers_deleted'],$f_data['ddbusers_name'],$f_data['ddbusers_title'],$f_data['ddbusers_avatar'],$f_data['ddbusers_signature'],$f_data['ddbusers_rating']))
			{
				$f_user_array = $direct_classes['kernel']->v_user_get ($f_data['ddbdiscuss_topics_last_id']);
				if ($f_user_array) { $f_data = array_merge ($f_data,$f_user_array); }
			}

			$this->set_extras ($f_data,(array ("ddbdiscuss_topics_owner_id","ddbdiscuss_topics_owner_ip","ddbdiscuss_topics_desc","ddbdiscuss_topics_time","ddbdiscuss_topics_last_id","ddbdiscuss_topics_last_ip","ddbdiscuss_topics_last_preview","ddbdiscuss_topics_locked","ddbusers_type","ddbusers_banned","ddbusers_deleted","ddbusers_name","ddbusers_title","ddbusers_avatar","ddbusers_signature","ddbusers_rating")));
			$this->data_did = $f_data['ddbdatalinker_id_parent'];
			$this->data_locked = ($this->data['ddbdiscuss_topics_locked'] ? true : false);
			$this->data_sticky = ($this->data['ddbdatalinker_position'] ? true : false);

			if ($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 3)
			{
				$this->data_editable = true;
				$this->data_writable = true;
			}
			elseif ($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']))
			{
				if (!$this->data_locked) { $this->data_writable = true; }
				if (($this->data_writable)&&($direct_settings['user']['id'] == $this->data['ddbdiscuss_topics_owner_id'])) { $this->data_editable = true; }
			}
		}
		else { $f_return = false; }

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->set ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_topic->set_insert ($f_data,$f_insert_mode_deactivate = true)
/**
	* Sets (and overwrites existing) the DataLinker entry and saves it to the
	* database. Note: If "set()" fails because of permission problems 
	* "update()" has to be called manually to write data to the database.
	* Please make sure that this is the intended behavior. You can use
	* "is_empty()" to check for the current data state of this object.
	*
	* @param  array $f_data DataLinker entry
	* @uses   direct_discuss_topic::set()
	* @uses   direct_discuss_topic::update()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	public function set_insert ($f_data,$f_insert_mode_deactivate = true)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_topic->set_insert (+f_data,+f_insert_mode_deactivate)- (#echo(__LINE__)#)"); }

		if ($this->set ($f_data))
		{
			$this->data_insert_mode = true;
			return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->set_insert ()- (#echo(__LINE__)#)",(:#*/$this->update ($f_insert_mode_deactivate)/*#ifdef(DEBUG):),true):#*/;
		}
		else { return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->set_insert ()- (#echo(__LINE__)#)",:#*/false/*#ifdef(DEBUG):,true):#*/; }
	}

	//f// direct_discuss_topic->set_update ($f_data)
/**
	* Updates (and overwrites) the existing DataLinker entry and saves it to the
	* database. Note: If "set()" fails because of permission problems 
	* "update()" has to be called manually to write data to the database.
	* Please make sure that this is the intended behavior. You can use
	* "is_empty()" to check for the current data state of this object.
	*
	* @param  array $f_data DataLinker entry
	* @uses   direct_discuss_topic::set()
	* @uses   direct_discuss_topic::update()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	public function set_update ($f_data)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_topic->set_update (+f_data)- (#echo(__LINE__)#)"); }

		if ($this->set ($f_data))
		{
			$this->data_insert_mode = false;
			return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->set_update ()- (#echo(__LINE__)#)",(:#*/$this->update ()/*#ifdef(DEBUG):),true):#*/;
		}
		else { return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->set_update ()- (#echo(__LINE__)#)",:#*/false/*#ifdef(DEBUG):,true):#*/; }
	}

	//f// direct_discuss_topic->update ($f_insert_mode_deactivate = true)
/**
	* Writes the topic data to the database.
	*
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
	public function update ($f_insert_mode_deactivate = true)
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (3,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->update (+f_insert_mode_deactivate)- (#echo(__LINE__)#)"); }

		if (empty ($this->data_changed)) { $f_return = true; }
		else
		{
			$direct_classes['db']->v_transaction_begin ();
			$f_return = parent::update (true,true,false);

			if (($f_return)&&(count ($this->data) > 1))
			{
				if ($this->is_changed (array ("ddbdiscuss_topics_owner_id","ddbdiscuss_topics_owner_ip","ddbdiscuss_topics_desc","ddbdiscuss_topics_time","ddbdiscuss_topics_last_id","ddbdiscuss_topics_last_ip","ddbdiscuss_topics_last_preview","ddbdiscuss_topics_locked")))
				{
					if ($this->data_insert_mode) { $direct_classes['db']->init_insert ($direct_settings['discuss_topics_table']); }
					else { $direct_classes['db']->init_update ($direct_settings['discuss_topics_table']); }

					$f_update_values = "<sqlvalues>";
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdatalinker_id_object']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['discuss_topics_table'].".ddbdiscuss_topics_id",$this->data['ddbdatalinker_id_object'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdiscuss_topics_owner_id']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['discuss_topics_table'].".ddbdiscuss_topics_owner_id",$this->data['ddbdiscuss_topics_owner_id'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdiscuss_topics_owner_ip']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['discuss_topics_table'].".ddbdiscuss_topics_owner_ip",$this->data['ddbdiscuss_topics_owner_ip'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdiscuss_topics_desc']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['discuss_topics_table'].".ddbdiscuss_topics_desc",$this->data['ddbdiscuss_topics_desc'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdiscuss_topics_time']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['discuss_topics_table'].".ddbdiscuss_topics_time",$this->data['ddbdiscuss_topics_time'],"number"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdiscuss_topics_last_id']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['discuss_topics_table'].".ddbdiscuss_topics_last_id",$this->data['ddbdiscuss_topics_last_id'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdiscuss_topics_last_ip']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['discuss_topics_table'].".ddbdiscuss_topics_last_ip",$this->data['ddbdiscuss_topics_last_ip'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdiscuss_topics_last_preview']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['discuss_topics_table'].".ddbdiscuss_topics_last_preview",$this->data['ddbdiscuss_topics_last_preview'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdiscuss_topics_locked']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['discuss_topics_table'].".ddbdiscuss_topics_locked",$this->data['ddbdiscuss_topics_locked'],"string"); }
					$f_update_values .= "</sqlvalues>";

					$direct_classes['db']->define_set_attributes ($f_update_values);
					if (!$this->data_insert_mode) { $direct_classes['db']->define_row_conditions ("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['discuss_topics_table'].".ddbdiscuss_topics_id",$this->data['ddbdatalinker_id_object'],"string"))."</sqlconditions>"); }
					$f_return = $direct_classes['db']->query_exec ("co");

					if ($f_return)
					{
						if (function_exists ("direct_dbsync_event"))
						{
							if ($this->data_insert_mode) { direct_dbsync_event ($direct_settings['discuss_topics_table'],"insert",("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['discuss_topics_table'].".ddbdiscuss_topics_id",$this->data['ddbdatalinker_id_object'],"string"))."</sqlconditions>")); }
							else { direct_dbsync_event ($direct_settings['discuss_topics_table'],"update",("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['discuss_topics_table'].".ddbdiscuss_topics_id",$this->data['ddbdatalinker_id_object'],"string"))."</sqlconditions>")); }
						}

						if (!$direct_settings['swg_auto_maintenance']) { $direct_classes['db']->optimize_random ($direct_settings['discuss_topics_table']); }
					}
				}
			}

			if (($f_insert_mode_deactivate)&&($this->data_insert_mode)) { $this->data_insert_mode = false; }

			if ($f_return) { $direct_classes['db']->v_transaction_commit (); }
			else { $direct_classes['db']->v_transaction_rollback (); }
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_topic_handler->update ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}
}

/* -------------------------------------------------------------------------
Mark this class as the most up-to-date one
------------------------------------------------------------------------- */

define ("CLASS_direct_discuss_topic",true);

//j// Script specific commands

if (!isset ($direct_settings['discuss_account_status_ex'])) { $direct_settings['discuss_account_status_ex'] = false; }
if (!isset ($direct_settings['discuss_datacenter_path_symbols'])) { $direct_settings['discuss_datacenter_path_symbols'] = $direct_settings['path_themes']."/$direct_settings[theme]/"; }
if (!isset ($direct_settings['discuss_posts_per_page'])) { $direct_settings['discuss_posts_per_page'] = 10; }
if (!isset ($direct_settings['swg_auto_maintenance'])) { $direct_settings['swg_auto_maintenance'] = false; }
if (!isset ($direct_settings['swg_ip_save2db'])) { $direct_settings['swg_ip_save2db'] = true; }
}

//j// EOF
?>