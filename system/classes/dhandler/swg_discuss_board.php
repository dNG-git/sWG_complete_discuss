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

$g_continue_check = ((defined ("CLASS_direct_discuss_board")) ? false : true);
if (!defined ("CLASS_direct_datalinker")) { $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_datalinker.php"); }
if (!defined ("CLASS_direct_datalinker")) { $g_continue_check = false; }

if ($g_continue_check)
{
//c// direct_discuss_board
/**
* This abstraction layer provides board (discuss) specific functions.
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
class direct_discuss_board extends direct_datalinker
{
/**
	* @var array $class_subboards Cached subboard objects
*/
	protected $class_subboards;
/**
	* @var array $class_topics Cached topic objects
*/
	protected $class_topics;
/**
	* @var boolean $data_locked True if this board is locked
*/
	protected $data_locked;
/**
	* @var boolean $data_moderator True if the user is a moderator for this
	*      board
*/
	protected $data_moderator;
/**
	* @var boolean $data_readable True if the current user is allowed to read
	*      topics in this board
*/
	protected $data_readable;
/**
	* @var array $data_structure Cached structure objects
*/
	protected $data_structure;
/**
	* @var boolean $data_structure_reflected True if subboard counts are
	*      already reflected.
*/
	protected $data_structure_reflected;
/**
	* @var array $data_subboards_structure Cached structure objects
*/
	protected $data_subboards_structure;
/**
	* @var boolean $data_writable True if the current user is allowed to
	*      create new and edit his own topics in this board
*/
	protected $data_writable;

/* -------------------------------------------------------------------------
Extend the class
------------------------------------------------------------------------- */

	//f// direct_discuss_board->__construct ()
/**
	* Constructor (PHP5) __construct (direct_discuss_board)
	*
	* @uses  direct_basic_functions::include_file()
	* @uses  direct_class_init()
	* @uses  direct_debug()
	* @uses  USE_debug_reporting
	* @since v0.1.00
*/
	public function __construct ()
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->__construct (direct_discuss_board)- (#echo(__LINE__)#)"); }

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
		$this->functions['add_topics'] = true;
		$this->functions['define_lock'] = true;
		$this->functions['define_readable'] = true;
		$this->functions['define_writable'] = true;
		$this->functions['get_rights'] = true;
		$this->functions['get_subboard_link_list'] = true;
		$this->functions['get_subboard_topics_since_date'] = true;
		$this->functions['get_subboards'] = true;
		$this->functions['get_topics'] = true;
		$this->functions['get_topics_since_date'] = true;
		$this->functions['is_locked'] = true;
		$this->functions['is_moderator'] = true;
		$this->functions['is_readable'] = true;
		$this->functions['is_writable'] = true;
		$this->functions['parse'] = isset ($direct_classes['formtags']);
		$this->functions['reflect_subboards'] = true;
		$this->functions['remove_posts'] = true;
		$this->functions['remove_topics'] = true;
		$this->functions['set_reflect_subboards_data'] = true;

/* -------------------------------------------------------------------------
Set up an additional board class elements :)
------------------------------------------------------------------------- */

		$this->class_subboards = array ();
		$this->class_topics = array ();
		$this->data_locked = false;
		$this->data_moderator = false;
		$this->data_readable = false;
		$this->data_sid = "cb41ecf6e90a594dcea60b6140251d62";
		$this->data_structure = array ();
		$this->data_structure_reflected = false;
		$this->data_subboards_structure = array ();
		$this->data_writable = false;
	}

	//f// direct_discuss_board->add_posts ($f_count,$f_update = true)
/**
	* Increases the posts counter.
	*
	* @param  number $f_count Number to be added to the post counter
	* @param  boolean $f_update True to update the database entry
	* @uses   direct_debug()
	* @uses   direct_discuss_board::update()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	public function add_posts ($f_count,$f_update = true)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->add_posts ($f_count,+f_update)- (#echo(__LINE__)#)"); }
		$f_return = false;

		if (isset ($this->data['ddbdiscuss_boards_posts']))
		{
			$this->data['ddbdiscuss_boards_posts'] += $f_count;
			$this->data_changed['ddbdiscuss_boards_posts'] = true;
			$f_return = ($f_update ? $this->update () : true);
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->add_posts ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_board->add_topics ($f_count,$f_update = true)
/**
	* Increases the topics counter.
	*
	* @param  number $f_count Number to be added to the topic counter
	* @param  boolean $f_update True to update the database entry
	* @uses   direct_datalinker::update()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	public function add_topics ($f_count,$f_update = true)
	{
		if (USE_debug_reporting) { direct_debug (8,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->add_topics ($f_count,+f_update)- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (9,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->add_topics ()- (#echo(__LINE__)#)",(:#*/$this->add_objects ($f_count,$f_update)/*#ifdef(DEBUG):),true):#*/;
	}

	//f// direct_discuss_board->define_lock ($f_state = NULL,$f_update = false)
/**
	* Sets the locking state of this board.
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->define_lock (+f_state,+f_update)- (#echo(__LINE__)#)"); }
		$f_return = false;

		if (count ($this->data) > 1)
		{
			if (((is_bool ($f_state))||(is_string ($f_state)))&&($f_state)) { $f_return = true; }
			elseif (($f_state === NULL)&&(!$this->data['ddbdiscuss_boards_locked'])) { $f_return = true; }
			$this->data_locked = $f_return;

			$this->data['ddbdiscuss_boards_locked'] = ($f_return ? 1 : 0);
			$this->data_changed['ddbdiscuss_boards_locked'] = true;
			if ($f_update) { $this->update (); }
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->define_lock ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_board->define_readable ($f_state = NULL)
/**
	* Sets the reading right state of this board.
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->define_readable (+f_state)- (#echo(__LINE__)#)"); }

		if (((is_bool ($f_state))||(is_string ($f_state)))&&($f_state)) { $this->data_readable = true; }
		elseif (($f_state === NULL)&&(!$this->data_readable)) { $this->data_readable = true; }
		else { $this->data_readable = false; }

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->define_readable ()- (#echo(__LINE__)#)",:#*/$this->data_readable/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_board->define_writable ($f_state = NULL)
/**
	* Sets the writing right state of this board.
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->define_writable (+f_state)- (#echo(__LINE__)#)"); }

		if (((is_bool ($f_state))||(is_string ($f_state)))&&($f_state)) { $this->data_writable = true; }
		elseif (($f_state === NULL)&&(!$this->data_writable)) { $this->data_writable = true; }
		else { $this->data_writable = false; }

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->define_writable ()- (#echo(__LINE__)#)",:#*/$this->data_writable/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_board->get_aid ($f_attributes = NULL,$f_values = "")
/**
	* Request and load the board object based on a custom attribute ID.
	* Please note that only attributes of type "string" are supported.
	*
	* @param  mixed $f_attributes Attribute name(s) (array or string)
	* @param  mixed $f_values Attribute value(s) (array or string)
	* @uses   direct_datalinker::define_extra_attributes()
	* @uses   direct_datalinker::define_extra_conditions()
	* @uses   direct_datalinker::define_extra_joins()
	* @uses   direct_datalinker::get_aid()
	* @uses   direct_debug()
	* @uses   direct_discuss_board::get_rights()
	* @uses   USE_debug_reporting
	* @return mixed Category data array; false on error
	* @since  v0.1.00
*/
	public function get_aid ($f_attributes = NULL,$f_values = "")
	{
		global $direct_settings;
		if (USE_debug_reporting) { direct_debug (3,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->get_aid (+f_attributes,+f_values)- (#echo(__LINE__)#)"); }

		$f_return = false;

		if (count ($this->data) > 1) { $f_return = $this->data; }
		elseif ((is_array ($f_values))||(is_string ($f_values)))
		{
			$this->define_extra_attributes (array ($direct_settings['discuss_boards_table'].".*",$direct_settings['users_table'].".ddbusers_type",$direct_settings['users_table'].".ddbusers_banned",$direct_settings['users_table'].".ddbusers_deleted",$direct_settings['users_table'].".ddbusers_name",$direct_settings['users_table'].".ddbusers_avatar","discuss_boards_datalinkerd_table.ddbdatalinker_title AS discuss_boards_datalinkerd_title"));

$f_select_joins = array (
array ("type" => "left-outer-join","table" => $direct_settings['discuss_boards_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['discuss_boards_table']}.ddbdiscuss_boards_id' value='{$direct_settings['datalinker_table']}.ddbdatalinker_id_object' type='attribute' /></sqlconditions>"),
array ("type" => "left-outer-join","table" => $direct_settings['users_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['users_table']}.ddbusers_id' value='{$direct_settings['discuss_boards_table']}.ddbdiscuss_boards_last_id' type='attribute' /></sqlconditions>"),
array ("type" => "left-outer-join","table" => $direct_settings['datalinkerd_table']." AS discuss_boards_datalinkerd_table","condition" => "<sqlconditions><element1 attribute='discuss_boards_datalinkerd_table.ddbdatalinkerd_id' value='{$direct_settings['discuss_boards_table']}.ddbdiscuss_boards_last_tid' type='attribute' /></sqlconditions>")
);

			$this->define_extra_joins ($f_select_joins);

			$f_result_array = parent::get_aid ($f_attributes,$f_values);

			if (($f_result_array)&&($f_result_array['ddbdatalinker_sid'] == $this->data_sid)&&($f_result_array['ddbdatalinker_type'] < 5))
			{
				$this->data = $f_result_array;
				$this->data_locked = ($this->data['ddbdiscuss_boards_locked'] ? true : false);

				$f_result_array = $this->get_rights ();
				$this->data_readable = $f_result_array[0];
				$this->data_writable = $f_result_array[1];
				$this->data_moderator = $f_result_array[2];

				if ($this->data_readable) { $f_return = $this->data; }
			}
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->get_aid ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_board->get_rights ()
/**
	* Check the user rights based on the defined object.
	*
	* @uses   direct_debug()
	* @uses   direct_kernel_system::v_group_user_check_group()
	* @uses   direct_kernel_system::v_group_user_check_right()
	* @uses   direct_kernel_system::v_usertype_get_int()
	* @uses   USE_debug_reporting
	* @return array Array with the results to read, write and moderate
	* @since  v0.1.00
*/
	protected function get_rights ()
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->get_rights ()- (#echo(__LINE__)#)"); }

		$f_return = array (false,false,false);

		if (($direct_settings['user']['type'] == "mo")&&(!$this->data_locked)) { $f_return[2] = $direct_classes['kernel']->v_group_user_check_right ("discuss_{$this->data['ddbdatalinker_id_object']}_moderate"); }
		elseif ($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 3) { $f_return[2] = true; }

		if ($f_return[2])
		{
			$f_return[0] = true;
			if ($this->data['ddbdatalinker_type'] > 1) { $f_return[1] = true; }
		}
		elseif ($this->data['ddbdiscuss_boards_public'])
		{
			$f_return[0] = true;
			if (($this->data['ddbdatalinker_type'] > 1)&&(!$this->data_locked)&&(($direct_settings['discuss_account_status_ex'])||($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type'])))) { $f_return[1] = true; }
		}
		else
		{
			$f_return[0] = $direct_classes['kernel']->v_group_user_check_right ("discuss_{$this->data['ddbdatalinker_id_object']}_read");

			if (($this->data['ddbdatalinker_type'] > 1)&&(!$this->data_locked))
			{
				$f_return[1] = $direct_classes['kernel']->v_group_user_check_right ("discuss_{$this->data['ddbdatalinker_id_object']}_write");
				if ($f_return[1]) { $f_return[0] = true; }
			}
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->get_rights ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_board->get_subboard_link_list ()
/**
	* Returns an array with all (read) board IDs as keys and an array of class
	* pointers as values.
	*
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return array Sub board linked list 
	* @since  v0.1.00
*/
	protected function &get_subboard_link_list ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->get_subboard_link_list ()- (#echo(__LINE__)#)"); }
		$f_return = array ();

		if (!empty ($this->class_subboards))
		{
			foreach ($this->class_subboards as &$f_subboard_array)
			{
				if (is_array ($f_subboard_array))
				{
					foreach ($f_subboard_array as $f_did => &$f_subboard_object)
					{
						if (isset ($f_return[$f_did])) { $f_return[$f_did][] = $f_subboard_object; }
						else { $f_return[$f_did] = array ($f_subboard_object); }
					}
				}
			}
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->get_subboard_link_list ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_board->get_subboard_topics_since_date ($f_date,$f_offset = 0,$f_perpage = "",$f_sorting_mode = "last-time-desc",$f_count_only = false)
/**
	* Returns all subobjects for the DataLinker with the given service ID and
	* type that are newer than a specific date.
	*
	* @param  integer $f_date UNIX timestamp for the oldest valid topic date
	* @param  integer $f_offset Offset for the result list
	* @param  integer $f_perpage Object count limit for the result list
	* @param  string $f_sorting_mode Sorting algorithm
	* @param  boolean $f_count_only True to return the number of topics
	* @uses   direct_datalinker::define_custom_sorting()
	* @uses   direct_datalinker::define_extra_attributes()
	* @uses   direct_datalinker::define_extra_conditions()
	* @uses   direct_datalinker::define_extra_joins()
	* @uses   direct_datalinker::get_structure()
	* @uses   direct_datalinker::get_subs()
	* @uses   direct_db::define_row_conditions_encode()
	* @uses   direct_debug()
	* @uses   direct_kernel_system::v_group_user_check_right()
	* @uses   direct_kernel_system::v_usertype_get_int()
	* @uses   USE_debug_reporting
	* @return mixed Array with pointers to the posts since the last visit or
	*         post quantity
	* @since  v0.1.00
*/
	public function get_subboard_topics_since_date ($f_date,$f_offset = 0,$f_perpage = "",$f_sorting_mode = "last-time-desc",$f_count_only = false)
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->get_subboard_topics_since_date ($f_date,$f_offset,$f_perpage,$f_sorting_mode,+f_count_only)- (#echo(__LINE__)#)"); }

		if ($f_count_only)
		{
			$f_return = 0;
			$f_cache_signature = md5 ("stsdc".$this->data['ddbdatalinker_id_main']);
		}
		else
		{
			$f_return = array ();
			$f_cache_signature = md5 ("stsd".$this->data['ddbdatalinker_id_main'].$f_offset.$f_perpage.$f_sorting_mode);
		}

		if (isset ($this->class_topics[$f_cache_signature])) { $f_return =& $this->class_topics[$f_cache_signature]; }
		elseif ((isset ($this->data['ddbdatalinker_id_main']))&&($this->data['ddbdatalinker_type'] < 3))
		{
			if (!$this->data['ddbdatalinker_subs']) { $this->data_subboards_structure = array ($this->data['ddbdatalinker_id_object']); }
			elseif (empty ($this->data_subboards_structure))
			{
				if (empty ($this->data_structure))
				{
/* -------------------------------------------------------------------------
Get structure from database (based on ddbdatalinker_id_main)
------------------------------------------------------------------------- */

					$this->define_extra_attributes (array ($direct_settings['discuss_boards_table'].".ddbdiscuss_boards_posts",$direct_settings['discuss_boards_table'].".ddbdiscuss_boards_public",$direct_settings['discuss_boards_table'].".ddbdiscuss_boards_locked"));
					$this->define_extra_joins (array (array ("type" => "left-outer-join","table" => $direct_settings['discuss_boards_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['discuss_boards_table']}.ddbdiscuss_boards_id' value='{$direct_settings['datalinker_table']}.ddbdatalinker_id_object' type='attribute' /></sqlconditions>")));
					$this->define_extra_conditions ("<element1 attribute='{$direct_settings['datalinker_table']}.ddbdatalinker_type' value='4' type='number' operator='&lt;=' />");
					$this->data_structure = $this->get_structure ("cb41ecf6e90a594dcea60b6140251d62","",false);
				}

				if ($this->data_structure)
				{
					$f_blacklist_array = array ();
					$f_structure_entries = explode ("\n",$this->data_structure['structured']);
/* -------------------------------------------------------------------------
Parse structure. Add topic and post numbers as well as the latest date to
parent boards if applicable. The current user must be allowed to access the
board. 
------------------------------------------------------------------------- */

					foreach ($f_structure_entries as $f_structure_entry)
					{
						$f_board_array = explode (":",$f_structure_entry);
						$f_board_array = array_reverse ($f_board_array);

						if ((!empty ($f_board_array))&&(isset ($this->data_structure['objects'][$f_board_array[0]]))&&(!in_array ($f_board_array[0],$f_blacklist_array)))
						{
							if (($this->data_structure['objects'][$f_board_array[0]]['ddbdiscuss_boards_public'])||($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 3)) { $f_rights_check = true; }
							else
							{
								$f_rights_check = false;

								if ($direct_settings['user']['type'] != "gt")
								{
									if (($direct_settings['user']['type'] == "mo")&&(!$this->data_structure['objects'][$f_board_array[0]]['ddbdiscuss_boards_locked'])) { $f_rights_check = $direct_classes['kernel']->v_group_user_check_right ("discuss_{$this->data_structure['objects'][$f_board_array[0]]['ddbdatalinker_id_object']}_moderate"); }
									if (!$f_rights_check) { $f_rights_check = $direct_classes['kernel']->v_group_user_check_right ("discuss_{$this->data_structure['objects'][$f_board_array[0]]['ddbdatalinker_id_object']}_read"); }
								}
							}

							if ($f_rights_check)
							{
								if (in_array ($this->data['ddbdatalinker_id'],$f_board_array)) { $this->data_subboards_structure[] = $this->data_structure['objects'][$f_board_array[0]]['ddbdatalinker_id_object']; }
							}
							else
							{
/* -------------------------------------------------------------------------
Block the board and all subboards from parsing. The current user is not
allowed to view its content.
------------------------------------------------------------------------- */

								$f_blacklist_array[] = $f_board_array[0];

								if (preg_match_all ("#\:{$f_board_array[0]}\:(.*?)$#im",$this->data_structure['structured'],$f_results_array,PREG_SET_ORDER))
								{
									foreach ($f_results_array as $f_blacklist_entries)
									{
										$f_blacklist_entry = explode (":",$f_blacklist_entries[1]);

										if (isset ($f_blacklist_entry[0]))
										{
											$f_blacklist_entry = array_reverse ($f_blacklist_entry);
											$f_blacklist_array[] = $f_blacklist_entry[0];
										}
									}
								}
							}
						}
					}
				}
			}

			if (!empty ($this->data_subboards_structure))
			{
				if ($f_count_only) { $f_select_attributes = array ("count-rows({$direct_settings['datalinker_table']}.ddbdatalinker_id)"); }
				else { $f_select_attributes = array ($direct_settings['discuss_topics_table'].".*",$direct_settings['users_table'].".ddbusers_type",$direct_settings['users_table'].".ddbusers_banned",$direct_settings['users_table'].".ddbusers_deleted",$direct_settings['users_table'].".ddbusers_name",$direct_settings['users_table'].".ddbusers_title",$direct_settings['users_table'].".ddbusers_avatar",$direct_settings['users_table'].".ddbusers_signature",$direct_settings['users_table'].".ddbusers_rating"); }

				$this->define_extra_attributes ($f_select_attributes);

				if (!$f_count_only)
				{
$this->define_extra_joins (array (
array ("type" => "left-outer-join","table" => $direct_settings['discuss_topics_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['discuss_topics_table']}.ddbdiscuss_topics_id' value='{$direct_settings['datalinker_table']}.ddbdatalinker_id_object' type='attribute' /></sqlconditions>"),
array ("type" => "left-outer-join","table" => $direct_settings['users_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['users_table']}.ddbusers_id' value='{$direct_settings['discuss_topics_table']}.ddbdiscuss_topics_last_id' type='attribute' /></sqlconditions>")
));
				}

				$f_select_conditions = ($direct_classes['db']->define_row_conditions_encode ($direct_settings['datalinkerd_table'].".ddbdatalinker_sorting_date",$f_date,"number",">"))."<sub1 type='sublevel' condition='and'>";
				foreach ($this->data_subboards_structure as $f_subboard_id) { $f_select_conditions .= $direct_classes['db']->define_row_conditions_encode ($direct_settings['datalinker_table'].".ddbdatalinker_id_main",$f_subboard_id,"string","==","or"); }
				$f_select_conditions .= "</sub1>";

				$this->define_extra_conditions ($f_select_conditions);

				if ($f_count_only) { $this->class_topics[$f_cache_signature] = parent::get_subs ("",NULL,NULL,"cb41ecf6e90a594dcea60b6140251d62",5,0,1,"time-desc"); }
				// md5 ("discuss")
				else
				{
					switch ($f_sorting_mode)
					{
					case "time-asc":
					{
$this->define_custom_sorting ("<sqlordering>
<element1 attribute='{$direct_settings['discuss_topics_table']}.ddbdiscuss_topics_time' type='asc' />
<element2 attribute='{$direct_settings['datalinker_table']}.ddbdatalinker_position' type='asc' />
<element3 attribute='{$direct_settings['datalinkerd_table']}.ddbdatalinker_title' type='asc' />
</sqlordering>");

						break 1;
					}
					case "time-desc":
					{
$this->define_custom_sorting ("<sqlordering>
<element1 attribute='{$direct_settings['discuss_topics_table']}.ddbdiscuss_topics_time' type='desc' />
<element2 attribute='{$direct_settings['datalinker_table']}.ddbdatalinker_position' type='desc' />
<element3 attribute='{$direct_settings['datalinkerd_table']}.ddbdatalinker_title' type='asc' />
</sqlordering>");

						break 1;
					}
					case "time-sticky-asc":
					{
$this->define_custom_sorting ("<sqlordering>
<element1 attribute='{$direct_settings['datalinker_table']}.ddbdatalinker_position' type='desc' />
<element2 attribute='{$direct_settings['discuss_topics_table']}.ddbdiscuss_topics_time' type='asc' />
<element3 attribute='{$direct_settings['datalinkerd_table']}.ddbdatalinker_title' type='asc' />
</sqlordering>");

						break 1;
					}
					case "time-sticky-desc":
					{
$this->define_custom_sorting ("<sqlordering>
<element1 attribute='{$direct_settings['datalinker_table']}.ddbdatalinker_position' type='desc' />
<element2 attribute='{$direct_settings['discuss_topics_table']}.ddbdiscuss_topics_time' type='desc' />
<element3 attribute='{$direct_settings['datalinkerd_table']}.ddbdatalinker_title' type='asc' />
</sqlordering>");

						break 1;
					}
					case "last-time-asc":
					{
						$f_sorting_mode = "time-asc";
						break 1;
					}
					case "last-time-desc":
					{
						$f_sorting_mode = "time-desc";
						break 1;
					}
					case "last-time-sticky-asc":
					{
						$f_sorting_mode = "time-sticky-asc";
						break 1;
					}
					case "last-time-sticky-desc":
					{
						$f_sorting_mode = "time-sticky-desc";
						break 1;
					}
					}

					$this->class_topics[$f_cache_signature] = parent::get_subs ("direct_discuss_topic",NULL,NULL,"cb41ecf6e90a594dcea60b6140251d62",5,$f_offset,$f_perpage,$f_sorting_mode);
				}

				$f_return =& $this->class_topics[$f_cache_signature];
			}
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->get_subboard_topics_since_date ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_board->get_subboards ($f_offset = 0,$f_perpage = "",$f_sorting_mode = "position-asc")
/**
	* Returns all subobjects for the DataLinker with the given service ID and
	* type.
	*
	* @param  integer $f_offset Offset for the result list
	* @param  integer $f_perpage Object count limit for the result list
	* @param  string $f_sorting_mode Sorting algorithm
	* @uses   direct_datalinker::define_extra_attributes()
	* @uses   direct_datalinker::define_extra_conditions()
	* @uses   direct_datalinker::define_extra_joins()
	* @uses   direct_datalinker::get_subs()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return array Array with pointers to the subboards 
	* @since  v0.1.00
*/
	public function get_subboards ($f_offset = 0,$f_perpage = "",$f_sorting_mode = "position-asc")
	{
		global $direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->get_subboards ($f_offset,$f_perpage,$f_sorting_mode)- (#echo(__LINE__)#)"); }

		$f_return = array ();
		$f_cache_signature = md5 ($this->data['ddbdatalinker_id_object'].$f_offset.$f_perpage.$f_sorting_mode);

		if (isset ($this->class_subboards[$f_cache_signature])) { $f_return =& $this->class_subboards[$f_cache_signature]; }
		elseif ((isset ($this->data['ddbdatalinker_id_object']))&&($this->data['ddbdatalinker_type'] < 3))
		{
			$this->define_extra_attributes (array ($direct_settings['discuss_boards_table'].".*",$direct_settings['users_table'].".ddbusers_type",$direct_settings['users_table'].".ddbusers_banned",$direct_settings['users_table'].".ddbusers_deleted",$direct_settings['users_table'].".ddbusers_name",$direct_settings['users_table'].".ddbusers_avatar","discuss_boards_datalinkerd_table.ddbdatalinker_title AS discuss_boards_datalinkerd_title"));

$f_select_joins = array (
array ("type" => "left-outer-join","table" => $direct_settings['discuss_boards_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['discuss_boards_table']}.ddbdiscuss_boards_id' value='{$direct_settings['datalinker_table']}.ddbdatalinker_id_object' type='attribute' /></sqlconditions>"),
array ("type" => "left-outer-join","table" => $direct_settings['users_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['users_table']}.ddbusers_id' value='{$direct_settings['discuss_boards_table']}.ddbdiscuss_boards_last_id' type='attribute' /></sqlconditions>"),
array ("type" => "left-outer-join","table" => $direct_settings['datalinkerd_table']." AS discuss_boards_datalinkerd_table","condition" => "<sqlconditions><element1 attribute='discuss_boards_datalinkerd_table.ddbdatalinkerd_id' value='{$direct_settings['discuss_boards_table']}.ddbdiscuss_boards_last_tid' type='attribute' /></sqlconditions>")
);

			$this->define_extra_joins ($f_select_joins);
			$this->define_extra_conditions ("<element1 attribute='{$direct_settings['datalinker_table']}.ddbdatalinker_type' value='4' type='number' operator='&lt;=' />");

			$this->class_subboards[$f_cache_signature] = parent::get_subs ("direct_discuss_board",$this->data['ddbdatalinker_id_main'],$this->data['ddbdatalinker_id'],"cb41ecf6e90a594dcea60b6140251d62","",$f_offset,$f_perpage,$f_sorting_mode);
			// md5 ("discuss")

			$f_return =& $this->class_subboards[$f_cache_signature];
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->get_subboards ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_board->get_topics ($f_offset = 0,$f_perpage = "",$f_sorting_mode = "last-time-desc")
/**
	* Returns all subobjects for the DataLinker with the given service ID and
	* type.
	*
	* @param  integer $f_offset Offset for the result list
	* @param  integer $f_perpage Object count limit for the result list
	* @param  string $f_sorting_mode Sorting algorithm
	* @uses   direct_datalinker::define_custom_sorting()
	* @uses   direct_datalinker::define_extra_attribtes()
	* @uses   direct_datalinker::define_extra_joins()
	* @uses   direct_datalinker::get_subs()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return array Array with pointers to the topics
	* @since  v0.1.00
*/
	public function get_topics ($f_offset = 0,$f_perpage = "",$f_sorting_mode = "last-time-desc")
	{
		global $direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->get_topics ($f_offset,$f_perpage,$f_sorting_mode)- (#echo(__LINE__)#)"); }

		$f_return = array ();
		$f_cache_signature = md5 ($this->data['ddbdatalinker_id_object'].$f_offset.$f_perpage.$f_sorting_mode);

		if (isset ($this->class_topics[$f_cache_signature])) { $f_return =& $this->class_topics[$f_cache_signature]; }
		elseif (isset ($this->data['ddbdatalinker_id_object']))
		{
			$this->define_extra_attributes (array ($direct_settings['discuss_topics_table'].".*",$direct_settings['users_table'].".ddbusers_type",$direct_settings['users_table'].".ddbusers_banned",$direct_settings['users_table'].".ddbusers_deleted",$direct_settings['users_table'].".ddbusers_name",$direct_settings['users_table'].".ddbusers_title",$direct_settings['users_table'].".ddbusers_avatar",$direct_settings['users_table'].".ddbusers_signature",$direct_settings['users_table'].".ddbusers_rating"));

$f_select_joins = array (
array ("type" => "left-outer-join","table" => $direct_settings['discuss_topics_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['discuss_topics_table']}.ddbdiscuss_topics_id' value='{$direct_settings['datalinker_table']}.ddbdatalinker_id_object' type='attribute' /></sqlconditions>"),
array ("type" => "left-outer-join","table" => $direct_settings['users_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['users_table']}.ddbusers_id' value='{$direct_settings['discuss_topics_table']}.ddbdiscuss_topics_last_id' type='attribute' /></sqlconditions>")
);

			$this->define_extra_joins ($f_select_joins);

			switch ($f_sorting_mode)
			{
			case "time-asc":
			{
$this->define_custom_sorting ("<sqlordering>
<element1 attribute='{$direct_settings['discuss_topics_table']}.ddbdiscuss_topics_time' type='asc' />
<element2 attribute='{$direct_settings['datalinker_table']}.ddbdatalinker_position' type='asc' />
<element3 attribute='{$direct_settings['datalinkerd_table']}.ddbdatalinker_title' type='asc' />
</sqlordering>");

				break 1;
			}
			case "time-desc":
			{
$this->define_custom_sorting ("<sqlordering>
<element1 attribute='{$direct_settings['discuss_topics_table']}.ddbdiscuss_topics_time' type='desc' />
<element2 attribute='{$direct_settings['datalinker_table']}.ddbdatalinker_position' type='desc' />
<element3 attribute='{$direct_settings['datalinkerd_table']}.ddbdatalinker_title' type='asc' />
</sqlordering>");

				break 1;
			}
			case "time-sticky-asc":
			{
$this->define_custom_sorting ("<sqlordering>
<element1 attribute='{$direct_settings['datalinker_table']}.ddbdatalinker_position' type='desc' />
<element2 attribute='{$direct_settings['discuss_topics_table']}.ddbdiscuss_topics_time' type='asc' />
<element3 attribute='{$direct_settings['datalinkerd_table']}.ddbdatalinker_title' type='asc' />
</sqlordering>");

				break 1;
			}
			case "time-sticky-desc":
			{
$this->define_custom_sorting ("<sqlordering>
<element1 attribute='{$direct_settings['datalinker_table']}.ddbdatalinker_position' type='desc' />
<element2 attribute='{$direct_settings['discuss_topics_table']}.ddbdiscuss_topics_time' type='desc' />
<element3 attribute='{$direct_settings['datalinkerd_table']}.ddbdatalinker_title' type='asc' />
</sqlordering>");

				break 1;
			}
			case "last-time-asc":
			{
				$f_sorting_mode = "time-asc";
				break 1;
			}
			case "last-time-desc":
			{
				$f_sorting_mode = "time-desc";
				break 1;
			}
			case "last-time-sticky-asc":
			{
				$f_sorting_mode = "time-sticky-asc";
				break 1;
			}
			case "last-time-sticky-desc":
			{
				$f_sorting_mode = "time-sticky-desc";
				break 1;
			}
			}

			$this->class_topics[$f_cache_signature] = parent::get_subs ("direct_discuss_topic",NULL,$this->data['ddbdatalinker_id_object'],"cb41ecf6e90a594dcea60b6140251d62",5,$f_offset,$f_perpage,$f_sorting_mode);
			// md5 ("discuss")

			$f_return =& $this->class_topics[$f_cache_signature];
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->get_topics ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_board->get_topics_since_date ($f_date,$f_offset = 0,$f_perpage = "",$f_sorting_mode = "",$f_count_only = false)
/**
	* Returns all subobjects for the DataLinker with the given service ID and
	* type that are newer than a specific date.
	*
	* @param  integer $f_date UNIX timestamp for the oldest valid topic date
	* @param  integer $f_offset Offset for the result list
	* @param  integer $f_perpage Object count limit for the result list
	* @param  string $f_sorting_mode Sorting algorithm
	* @param  boolean $f_count_only True to return the number of topics
	* @uses   direct_datalinker::define_custom_sorting()
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
	public function get_topics_since_date ($f_date,$f_offset = 0,$f_perpage = "",$f_sorting_mode = "",$f_count_only = false)
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->get_topics_since_date ($f_date,$f_offset,$f_perpage,$f_sorting_mode,+f_count_only)- (#echo(__LINE__)#)"); }

		if ($f_count_only)
		{
			$f_return = 0;
			$f_cache_signature = md5 ("tsdc".$this->data['ddbdatalinker_id_object']);
		}
		else
		{
			$f_return = array ();
			$f_cache_signature = md5 ("tsd".$this->data['ddbdatalinker_id_object'].$f_offset.$f_perpage.$f_sorting_mode);
		}

		if (isset ($this->class_topics[$f_cache_signature])) { $f_return =& $this->class_topics[$f_cache_signature]; }
		elseif (($f_count_only)&&($f_date < 1)) { $f_return = $this->data['ddbdatalinker_objects']; }
		elseif (isset ($this->data['ddbdatalinker_id_object']))
		{
			if ($f_count_only) { $f_select_attributes = array ("count-rows({$direct_settings['datalinker_table']}.ddbdatalinker_id)"); }
			else { $f_select_attributes = array ($direct_settings['discuss_topics_table'].".*",$direct_settings['users_table'].".ddbusers_type",$direct_settings['users_table'].".ddbusers_banned",$direct_settings['users_table'].".ddbusers_deleted",$direct_settings['users_table'].".ddbusers_name",$direct_settings['users_table'].".ddbusers_title",$direct_settings['users_table'].".ddbusers_avatar",$direct_settings['users_table'].".ddbusers_signature",$direct_settings['users_table'].".ddbusers_rating"); }

			$this->define_extra_attributes ($f_select_attributes);

			if (!$f_count_only)
			{
$this->define_extra_joins (array (
array ("type" => "left-outer-join","table" => $direct_settings['discuss_topics_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['discuss_topics_table']}.ddbdiscuss_topics_id' value='{$direct_settings['datalinker_table']}.ddbdatalinker_id_object' type='attribute' /></sqlconditions>"),
array ("type" => "left-outer-join","table" => $direct_settings['users_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['users_table']}.ddbusers_id' value='{$direct_settings['discuss_topics_table']}.ddbdiscuss_topics_last_id' type='attribute' /></sqlconditions>")
));
			}

			$this->define_extra_conditions ($direct_classes['db']->define_row_conditions_encode ($direct_settings['datalinkerd_table'].".ddbdatalinker_sorting_date",$f_date,"number",">"));

			if ($f_count_only) { $this->class_topics[$f_cache_signature] = parent::get_subs ("",$this->data['ddbdatalinker_id_object'],NULL,"cb41ecf6e90a594dcea60b6140251d62",5,0,1,"time-desc"); }
			// md5 ("discuss")
			else
			{
				switch ($f_sorting_mode)
				{
				case "time-asc":
				{
$this->define_custom_sorting ("<sqlordering>
<element1 attribute='{$direct_settings['discuss_topics_table']}.ddbdiscuss_topics_time' type='asc' />
<element2 attribute='{$direct_settings['datalinker_table']}.ddbdatalinker_position' type='asc' />
<element3 attribute='{$direct_settings['datalinkerd_table']}.ddbdatalinker_title' type='asc' />
</sqlordering>");

					break 1;
				}
				case "time-desc":
				{
$this->define_custom_sorting ("<sqlordering>
<element1 attribute='{$direct_settings['discuss_topics_table']}.ddbdiscuss_topics_time' type='desc' />
<element2 attribute='{$direct_settings['datalinker_table']}.ddbdatalinker_position' type='desc' />
<element3 attribute='{$direct_settings['datalinkerd_table']}.ddbdatalinker_title' type='asc' />
</sqlordering>");

					break 1;
				}
				case "time-sticky-asc":
				{
$this->define_custom_sorting ("<sqlordering>
<element1 attribute='{$direct_settings['datalinker_table']}.ddbdatalinker_position' type='desc' />
<element2 attribute='{$direct_settings['discuss_topics_table']}.ddbdiscuss_topics_time' type='asc' />
<element3 attribute='{$direct_settings['datalinkerd_table']}.ddbdatalinker_title' type='asc' />
</sqlordering>");

					break 1;
				}
				case "time-sticky-desc":
				{
$this->define_custom_sorting ("<sqlordering>
<element1 attribute='{$direct_settings['datalinker_table']}.ddbdatalinker_position' type='desc' />
<element2 attribute='{$direct_settings['discuss_topics_table']}.ddbdiscuss_topics_time' type='desc' />
<element3 attribute='{$direct_settings['datalinkerd_table']}.ddbdatalinker_title' type='asc' />
</sqlordering>");

					break 1;
				}
				case "last-time-asc":
				{
					$f_sorting_mode = "time-asc";
					break 1;
				}
				case "last-time-desc":
				{
					$f_sorting_mode = "time-desc";
					break 1;
				}
				case "last-time-sticky-asc":
				{
					$f_sorting_mode = "time-sticky-asc";
					break 1;
				}
				case "last-time-sticky-desc":
				{
					$f_sorting_mode = "time-sticky-desc";
					break 1;
				}
				}

				$this->class_topics[$f_cache_signature] = parent::get_subs ("direct_discuss_topic",$this->data['ddbdatalinker_id_object'],NULL,"cb41ecf6e90a594dcea60b6140251d62",5,$f_offset,$f_perpage,$f_sorting_mode);
			}

			$f_return =& $this->class_topics[$f_cache_signature];
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->get_topics_since_date ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_board->is_locked ()
/**
	* Returns true if the board is locked.
	*
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True or false
	* @since  v0.1.00
*/
	public function is_locked ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->is_locked ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->is_locked ()- (#echo(__LINE__)#)",:#*/$this->data_locked/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_board->is_moderator ()
/**
	* Returns true if the current user is a moderator of this category.
	*
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True or false
	* @since  v0.1.00
*/
	public function is_moderator ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->is_moderator ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->is_moderator ()- (#echo(__LINE__)#)",:#*/$this->data_moderator/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_board->is_readable ()
/**
	* Returns true if the current user is allowed to read documents in this
	* category.
	*
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True or false
	* @since  v0.1.00
*/
	public function is_readable ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->is_readable ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->is_readable ()- (#echo(__LINE__)#)",:#*/$this->data_readable/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_board->is_writable ()
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
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->is_writable ()- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->is_writable ()- (#echo(__LINE__)#)",:#*/$this->data_writable/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_board->parse ($f_connector,$f_connector_type = "url0",$f_prefix = "")
/**
	* Parses this board and returns valid (X)HTML.
	*
	* @param  string $f_connector Connector for links
	* @param  string $f_connector_type Linking mode: "url0" for internal links,
	*         "url1" for external ones, "form" to create hidden fields or
	*         "optical" to remove parts of a very long string.
	* @param  string $f_prefix Key prefix
	* @uses   direct_basic_functions::datetime()
	* @uses   direct_basic_functions::varfilter()
	* @uses   direct_datalinker::parse()
	* @uses   direct_debug()
	* @uses   direct_formtags::decode()
	* @uses   direct_html_encode_special()
	* @uses   direct_kernel_system::v_user_parse()
	* @uses   direct_linker()
	* @uses   USE_debug_reporting
	* @return array Output data
	* @since  v0.1.00
*/
	public function parse ($f_connector,$f_connector_type = "url0",$f_prefix = "")
	{
		global $direct_cachedata,$direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->parse ($f_connector,$f_connector_type,$f_prefix)- (#echo(__LINE__)#)"); }

		$f_return = parent::parse ($f_prefix);

		if (($f_return)&&($this->data_readable)&&(count ($this->data) > 1))
		{
			$f_return[$f_prefix."id"] = "swgdhandlerdiscussboard".$this->data['ddbdatalinker_id'];
			if (($f_connector_type != "asis")&&(strpos ($f_connector,"javascript:") === 0)) { $f_connector_type = "asis"; }

			$f_pageurl = str_replace ("[a]","topics",$f_connector);

			if ($this->data['ddbdatalinker_id_main']) { $f_pageurl = (($f_connector_type == "asis") ? str_replace ("[oid]",$this->data['ddbdatalinker_id'],$f_pageurl) : str_replace ("[oid]","ddid+{$this->data['ddbdatalinker_id']}++",$f_pageurl)); }
			else { $f_pageurl = (($f_connector_type == "asis") ? str_replace ("[oid]",$this->data['ddbdatalinker_id_object'],$f_pageurl) : str_replace ("[oid]","ddid+{$this->data['ddbdatalinker_id_object']}++",$f_pageurl)); }

			$f_pageurl = preg_replace ("#\[(.*?)\]#","",$f_pageurl);
			$f_return[$f_prefix."pageurl"] = direct_linker ($f_connector_type,$f_pageurl);

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

			switch ($this->data['ddbdatalinker_type'])
			{
			case "1":
			{
				$f_return[$f_prefix."type"] = "zone";
				break 1;
			}
			case "2":
			{
				$f_return[$f_prefix."type"] = "forumzone";
				break 1;
			}
			case "4":
			{
				$f_return[$f_prefix."type"] = "link";
				break 1;
			}
			default: { $f_return[$f_prefix."type"] = "forum"; }
			}

			if ($this->data['ddbdatalinker_symbol'])
			{
				$f_symbol_path = $direct_classes['basic_functions']->varfilter ($direct_settings['discuss_datacenter_path_symbols'],"settings");
				$f_return[$f_prefix."symbol"] = direct_linker_dynamic ("url0","s=cache&dsd=dfile+".$f_symbol_path.$this->data['ddbdatalinker_symbol'],true,false);
			}
			else { $f_return[$f_prefix."symbol"] = ""; }

			$f_return[$f_prefix."data"] = (($this->data['ddbdatalinker_type'] == 4) ? "" : $direct_classes['formtags']->decode ($this->data['ddbdiscuss_boards_data']));

			if (($this->data['ddbdiscuss_boards_last_id'])&&($this->data['ddbusers_name']))
			{
				$f_return[$f_prefix."last_id"] = $this->data['ddbdiscuss_boards_last_id'];
				$f_user_array = $direct_classes['kernel']->v_user_parse ($this->data['ddbdiscuss_boards_last_id'],$this->data,$f_prefix."user");
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

			if (($this->data['ddbdiscuss_boards_last_tid'])&&($this->data['ddbdatalinker_sorting_date']))
			{
				$f_pageurl = str_replace ("[a]","posts",$f_connector);

				if ($f_connector_type == "asis")
				{
					$f_pageurl = str_replace (array ("[pid]","[oid]"),(array ($this->data['ddbdatalinker_id'],$this->data['ddbdiscuss_boards_last_tid'])),$f_pageurl);
					$f_pageurl = preg_replace ("#\[page(.*?)\]#","last",$f_pageurl);
				}
				else
				{
					$f_pageurl = str_replace ("[oid]","ddid+{$this->data['ddbdatalinker_id']}++dtid+{$this->data['ddbdiscuss_boards_last_tid']}++",$f_pageurl);
					$f_pageurl = preg_replace ("#\[page(.*?)\]#","page+last++",$f_pageurl);
				}

				$f_pageurl = preg_replace ("#\[(.*?)\]#","",$f_pageurl);
				$f_return[$f_prefix."last_tid"] = direct_linker ($f_connector_type,$f_pageurl);

				$f_return[$f_prefix."last_title"] = $direct_classes['formtags']->decode ($this->data['discuss_boards_datalinkerd_title']);
				$f_return[$f_prefix."last_time"] = $direct_classes['basic_functions']->datetime ("shortdate&time",$this->data['ddbdatalinker_sorting_date'],$direct_settings['user']['timezone'],(direct_local_get ("datetime_dtconnect")));
				$f_return[$f_prefix."last_preview"] = direct_html_encode_special ($this->data['ddbdiscuss_boards_last_preview']);

				$f_target_connector = str_replace ("[oid]","ddid+{$this->data['ddbdatalinker_id']}++[oid]",$f_connector);
				$f_target_connector = urlencode (base64_encode ($f_target_connector));

				if ($f_return[$f_prefix."views_counted"])
				{
					$f_source = urlencode (base64_encode ("m=discuss&a=jump&dsd=idata+t;{$this->data['ddbdiscuss_boards_last_tid']};latest++connector+".$f_target_connector));
					$f_return[$f_prefix."last_post_jump"] = direct_linker ("url0","m=datalinker&a=count&dsd=deid+{$this->data['ddbdiscuss_boards_last_tid']}++source+".$f_source);
				}
				else { $f_return[$f_prefix."last_post_jump"] = direct_linker ("url0","m=discuss&a=jump&dsd=idata+t;{$this->data['ddbdiscuss_boards_last_tid']};latest++connector+".$f_target_connector); }
			}
			else
			{
				$f_return[$f_prefix."last_tid"] = "";
				$f_return[$f_prefix."last_title"] = "";
				$f_return[$f_prefix."last_time"] = direct_local_get ("core_unknown");
				$f_return[$f_prefix."last_preview"] = "";
				$f_return[$f_prefix."last_post_jump"] = "";
			}

			if ((isset ($this->data['reflect_subboards_last_time']))&&($this->data['ddbdatalinker_sorting_date'] < $this->data['reflect_subboards_last_time'])) { $f_return[$f_prefix."subboards_last_time"] = $direct_classes['basic_functions']->datetime ("shortdate&time",$this->data['reflect_subboards_last_time'],$direct_settings['user']['timezone'],(direct_local_get ("datetime_dtconnect"))); }
			else { $f_return[$f_prefix."subboards_last_time"] = ""; }

			$f_return[$f_prefix."topics"] = ((isset ($this->data['reflect_subboards_topics'])) ? $this->data['reflect_subboards_topics'] : $this->data['ddbdatalinker_objects']);
			$f_return[$f_prefix."posts"] = ((isset ($this->data['reflect_subboards_posts'])) ? $this->data['reflect_subboards_posts'] : $this->data['ddbdiscuss_boards_posts']);
			$f_return[$f_prefix."public"] = $this->data['ddbdiscuss_boards_public'];
			$f_return[$f_prefix."locked"] = $this->data_locked;

			$f_last_change_time = ((isset ($this->data['reflect_subboards_last_time'])) ? $this->data['reflect_subboards_last_time'] : $this->data['ddbdatalinker_sorting_date']);
			$f_return[$f_prefix."new"] = (($direct_cachedata['kernel_lastvisit'] < $f_last_change_time) ? true : false);
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->parse ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_board->reflect_subboards ($f_did = "")
/**
	* "reflect_subboards ()" will read and parse the board structure to
	* show all boards with the sum of topics and posts for their subboards.
	*
	* @param  string $f_connector Connector for links
	* @uses   direct_datalinker::define_extra_attributes()
	* @uses   direct_datalinker::define_extra_conditions()
	* @uses   direct_datalinker::define_extra_joins()
	* @uses   direct_datalinker::get_structure()
	* @uses   direct_db::define_row_conditions_encode()
	* @uses   direct_debug()
	* @uses   direct_discuss_board::get_subboard_link_list()
	* @uses   direct_discuss_board::set_reflect_subboards_data()
	* @uses   direct_kernel_system::v_group_user_check_right()
	* @uses   USE_debug_reporting
	* @return array Output data
	* @since  v0.1.00
*/
	public function reflect_subboards ($f_did = "")
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->reflect_subboards ($f_did)- (#echo(__LINE__)#)"); }

		$f_return = false;

		if ($this->data_structure_reflected) { $f_return = true; }
		elseif (isset ($this->data['ddbdatalinker_id_main']))
		{
			if (empty ($this->data_structure))
			{
/* -------------------------------------------------------------------------
Get structure from database (based on ddbdatalinker_id_main)
------------------------------------------------------------------------- */

				$this->define_extra_attributes (array ($direct_settings['discuss_boards_table'].".ddbdiscuss_boards_posts",$direct_settings['discuss_boards_table'].".ddbdiscuss_boards_public",$direct_settings['discuss_boards_table'].".ddbdiscuss_boards_locked"));
				$this->define_extra_joins (array (array ("type" => "left-outer-join","table" => $direct_settings['discuss_boards_table'],"condition" => "<sqlconditions><element1 attribute='{$direct_settings['discuss_boards_table']}.ddbdiscuss_boards_id' value='{$direct_settings['datalinker_table']}.ddbdatalinker_id_object' type='attribute' /></sqlconditions>")));
				$this->define_extra_conditions ("<element1 attribute='{$direct_settings['datalinker_table']}.ddbdatalinker_type' value='4' type='number' operator='&lt;=' />");
				$this->data_structure = $this->get_structure ("cb41ecf6e90a594dcea60b6140251d62","",false);
			}

			if ($this->data_structure)
			{
				$f_blacklist_array = array ();
				$f_structure_entries = explode ("\n",$this->data_structure['structured']);

/* -------------------------------------------------------------------------
Link $this->data into the corresponding structure entry
------------------------------------------------------------------------- */

				$this->data_structure['objects'][$this->data['ddbdatalinker_id']] =& $this->data;

/* -------------------------------------------------------------------------
Parse structure. Add topic and post numbers as well as the latest date to
parent boards if applicable. The current user must be allowed to access the
board. 
------------------------------------------------------------------------- */

				foreach ($f_structure_entries as $f_structure_entry)
				{
					$f_board_array = explode (":",$f_structure_entry);
					$f_board_array = array_reverse ($f_board_array);

					if ((!empty ($f_board_array))&&(isset ($this->data_structure['objects'][$f_board_array[0]]))&&(!in_array ($f_board_array[0],$f_blacklist_array)))
					{
						if (($this->data_structure['objects'][$f_board_array[0]]['ddbdiscuss_boards_public'])||($direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']) > 3)) { $f_rights_check = true; }
						else
						{
							$f_rights_check = false;

							if ($direct_settings['user']['type'] != "gt")
							{
								if (($direct_settings['user']['type'] == "mo")&&(!$this->data_structure['objects'][$f_board_array[0]]['ddbdiscuss_boards_locked'])) { $f_rights_check = $direct_classes['kernel']->v_group_user_check_right ("discuss_{$this->data_structure['objects'][$f_board_array[0]]['ddbdatalinker_id_object']}_moderate"); }
								if (!$f_rights_check) { $f_rights_check = $direct_classes['kernel']->v_group_user_check_right ("discuss_{$this->data_structure['objects'][$f_board_array[0]]['ddbdatalinker_id_object']}_read"); }
							}
						}

						if ($f_rights_check)
						{
							foreach ($f_board_array as $f_board_parent)
							{
								if (isset ($this->data_structure['objects'][$f_board_parent]['reflect_subboards_topics'],$this->data_structure['objects'][$f_board_parent]['reflect_subboards_posts']))
								{
									if ($this->data_structure['objects'][$f_board_parent]['reflect_subboards_last_time'] < $this->data_structure['objects'][$f_board_array[0]]['ddbdatalinker_sorting_date']) { $this->data_structure['objects'][$f_board_parent]['reflect_subboards_last_time'] = $this->data_structure['objects'][$f_board_array[0]]['ddbdatalinker_sorting_date']; }

									$this->data_structure['objects'][$f_board_parent]['reflect_subboards_topics'] += $this->data_structure['objects'][$f_board_array[0]]['ddbdatalinker_objects'];
									$this->data_structure['objects'][$f_board_parent]['reflect_subboards_posts'] += $this->data_structure['objects'][$f_board_array[0]]['ddbdiscuss_boards_posts'];
								}
								elseif (isset ($this->data_structure['objects'][$f_board_parent]))
								{
									$this->data_structure['objects'][$f_board_parent]['reflect_subboards_last_time'] = $this->data_structure['objects'][$f_board_parent]['ddbdatalinker_sorting_date'];
									$this->data_structure['objects'][$f_board_parent]['reflect_subboards_topics'] = $this->data_structure['objects'][$f_board_parent]['ddbdatalinker_objects'];
									$this->data_structure['objects'][$f_board_parent]['reflect_subboards_posts'] = $this->data_structure['objects'][$f_board_parent]['ddbdiscuss_boards_posts'];
								}
							}
						}
						else
						{
/* -------------------------------------------------------------------------
Block the board and all subboards from parsing. The current user is not
allowed to view its content.
------------------------------------------------------------------------- */

							$f_blacklist_array[] = $f_board_array[0];

							if (preg_match_all ("#\:{$f_board_array[0]}\:(.*?)$#im",$this->data_structure['structured'],$f_results_array,PREG_SET_ORDER))
							{
								foreach ($f_results_array as $f_blacklist_entries)
								{
									$f_blacklist_entry = explode (":",$f_blacklist_entries[1]);

									if (isset ($f_blacklist_entry[0]))
									{
										$f_blacklist_entry = array_reverse ($f_blacklist_entry);
										$f_blacklist_array[] = $f_blacklist_entry[0];
									}
								}
							}
						}
					}
				}

				$f_return = true;
				$this->data_structure_reflected = true;
			}
		}

		if ($f_return)
		{
/* -------------------------------------------------------------------------
Update reflections in all "class_subboards" entries
------------------------------------------------------------------------- */

			$f_subboards_array =& $this->get_subboard_link_list ();

			foreach ($f_subboards_array as $f_subboard_id => $f_subboard_array)
			{
				if ((is_array ($f_subboard_array))&&(isset ($this->data_structure['objects'][$f_subboard_id])))
				{
					foreach ($f_subboard_array as $f_subboard_object) { $f_subboard_object->set_reflect_subboards_data ($this->data_structure['objects'][$f_subboard_id]['reflect_subboards_topics'],$this->data_structure['objects'][$f_subboard_id]['reflect_subboards_posts'],$this->data_structure['objects'][$f_subboard_id]['reflect_subboards_last_time']); }
				}
			}
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->reflect_subboards ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_board->remove_posts ($f_count,$f_update = true)
/**
	* Decreases the post counter.
	*
	* @param  number $f_count Number to be removed from the post counter
	* @param  boolean $f_update True to update the database entry
	* @uses   direct_debug()
	* @uses   direct_discuss_board::update()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	public function remove_posts ($f_count,$f_update = true)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->remove_posts ($f_count,+f_update)- (#echo(__LINE__)#)"); }
		$f_return = false;

		if (isset ($this->data['ddbdiscuss_boards_posts']))
		{
			$this->data['ddbdiscuss_boards_posts'] -= $f_count;
			if ($this->data['ddbdiscuss_boards_posts'] < 0) { $this->data['ddbdiscuss_boards_posts'] = 0; }
			$this->data_changed['ddbdiscuss_boards_posts'] = true;
			$f_return = ($f_update ? $this->update () : true);
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->remove_posts ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_board->remove_topics ($f_count,$f_update = true)
/**
	* Decreases the topic counter.
	*
	* @param  number $f_count Number to be removed from the topic counter
	* @param  boolean $f_update True to update the database entry
	* @uses   direct_datalinker::update()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	public function remove_topics ($f_count,$f_update = true)
	{
		if (USE_debug_reporting) { direct_debug (8,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->remove_topics ($f_count,+f_update)- (#echo(__LINE__)#)"); }
		return /*#ifdef(DEBUG):direct_debug (9,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->remove_topics ()- (#echo(__LINE__)#)",(:#*/$this->remove_objects ($f_count,$f_update)/*#ifdef(DEBUG):),true):#*/;
	}

	//f// direct_discuss_board->set ($f_data)
/**
	* Sets (and overwrites existing) data for this board.
	*
	* @param  array $f_data Board data
	* @uses   direct_datalinker::set()
	* @uses   direct_debug()
	* @uses   direct_kernel_system::v_group_user_check_right()
	* @uses   direct_kernel_system::v_usertype_get_int()
	* @uses   USE_debug_reporting
	* @return boolean True on success (data valid and current user has read
	*         rights)
	* @since  v0.1.00
*/
	public function set ($f_data)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->set (+f_data)- (#echo(__LINE__)#)"); }

		if (!isset ($f_data['ddbdatalinker_sorting_date'],$f_data['ddbdiscuss_boards_last_id'],$f_data['ddbdiscuss_boards_last_tid']))
		{
			$f_data['ddbdatalinker_sorting_date'] = 0;
			$f_data['ddbdiscuss_boards_last_id'] = "";
			$f_data['ddbdiscuss_boards_last_tid'] = "";
		}

		$f_return = parent::set ($f_data);

		if (($f_return)&&(isset ($f_data['ddbdiscuss_boards_data'],$f_data['ddbdiscuss_boards_posts'],$f_data['ddbdiscuss_boards_public'],$f_data['ddbdiscuss_boards_locked'])))
		{
			if (!isset ($f_data['ddbdiscuss_boards_last_preview'])) { $f_data['ddbdiscuss_boards_last_preview'] = ""; }

			$this->set_extras ($f_data,(array ("ddbdiscuss_boards_data","ddbdiscuss_boards_last_id","ddbdiscuss_boards_last_tid","ddbdiscuss_boards_last_preview","ddbdiscuss_boards_posts","ddbdiscuss_boards_public","ddbdiscuss_boards_locked","ddbusers_type","ddbusers_banned","ddbusers_deleted","ddbusers_name","ddbusers_avatar","discuss_boards_datalinkerd_title")));
			$this->data_locked = ($this->data['ddbdiscuss_boards_locked'] ? true : false);

			$f_result_array = $this->get_rights ();
			$this->data_readable = $f_result_array[0];
			$this->data_writable = $f_result_array[1];
			$this->data_moderator = $f_result_array[2];

			if (!$this->data_readable) { $f_return = false; }
		}
		else { $f_return = false; }

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->set ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}

	//f// direct_discuss_board->set_reflect_subboards_data ($f_topics,$f_posts,$f_last_time = NULL)
/**
	* Sets (and overwrites) the reflection values for this board.
	*
	* @param  integer $f_topics Topic count
	* @param  integer $f_posts Post count
	* @param  integer $f_last_time UNIX timestamp of the latest post
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @since  v0.1.00
*/
	public function set_reflect_subboards_data ($f_topics,$f_posts,$f_last_time = NULL)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->set_reflect_subboards_data ($f_topics,$f_posts,+f_last_time)- (#echo(__LINE__)#)"); }

		if ((is_array ($this->data))&&(!empty ($this->data)))
		{
			if ($f_last_time != NULL) { $this->data['reflect_subboards_last_time'] = $f_last_time; }
			$this->data['reflect_subboards_topics'] = $f_topics;
			$this->data['reflect_subboards_posts'] = $f_posts;
		}
	}

	//f// direct_discuss_board->set_insert ($f_data,$f_insert_mode_deactivate = true)
/**
	* Sets (and overwrites existing) the DataLinker entry and saves it to the
	* database. Note: If "set()" fails because of permission problems 
	* "update()" has to be called manually to write data to the database.
	* Please make sure that this is the intended behavior. You can use
	* "is_empty()" to check for the current data state of this object.
	*
	* @param  array $f_data DataLinker entry
	* @uses   direct_discuss_board::set()
	* @uses   direct_discuss_board::update()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	public function set_insert ($f_data,$f_insert_mode_deactivate = true)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_board->set_insert (+f_data,+f_insert_mode_deactivate)- (#echo(__LINE__)#)"); }

		if ($this->set ($f_data))
		{
			$this->data_insert_mode = true;
			return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->set_insert ()- (#echo(__LINE__)#)",(:#*/$this->update ($f_insert_mode_deactivate)/*#ifdef(DEBUG):),true):#*/;
		}
		else { return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->set_insert ()- (#echo(__LINE__)#)",:#*/false/*#ifdef(DEBUG):,true):#*/; }
	}

	//f// direct_discuss_board->set_update ($f_data)
/**
	* Updates (and overwrites) the existing DataLinker entry and saves it to the
	* database. Note: If "set()" fails because of permission problems 
	* "update()" has to be called manually to write data to the database.
	* Please make sure that this is the intended behavior. You can use
	* "is_empty()" to check for the current data state of this object.
	*
	* @param  array $f_data DataLinker entry
	* @uses   direct_discuss_board::set()
	* @uses   direct_discuss_board::update()
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True on success
	* @since  v0.1.00
*/
	public function set_update ($f_data)
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -discuss_board->set_update (+f_data)- (#echo(__LINE__)#)"); }

		if ($this->set ($f_data))
		{
			$this->data_insert_mode = false;
			return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->set_update ()- (#echo(__LINE__)#)",(:#*/$this->update ()/*#ifdef(DEBUG):),true):#*/;
		}
		else { return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->set_update ()- (#echo(__LINE__)#)",:#*/false/*#ifdef(DEBUG):,true):#*/; }
	}

	//f// direct_discuss_board->update ($f_insert_mode_deactivate = true)
/**
	* Writes the board data to the database.
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
		if (USE_debug_reporting) { direct_debug (3,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->update (+f_insert_mode_deactivate)- (#echo(__LINE__)#)"); }

		if (empty ($this->data_changed)) { $f_return = true; }
		else
		{
			$direct_classes['db']->v_transaction_begin ();
			$f_return = parent::update (true,true,false);

			if (($f_return)&&(count ($this->data) > 1))
			{
				if ($this->is_changed (array ("ddbdiscuss_boards_data","ddbdiscuss_boards_last_id","ddbdiscuss_boards_last_tid","ddbdiscuss_boards_last_preview","ddbdiscuss_boards_posts","ddbdiscuss_boards_public","ddbdiscuss_boards_locked")))
				{
					if ($this->data_insert_mode) { $direct_classes['db']->init_insert ($direct_settings['discuss_boards_table']); }
					else { $direct_classes['db']->init_update ($direct_settings['discuss_boards_table']); }

					$f_update_values = "<sqlvalues>";
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdatalinker_id_object']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['discuss_boards_table'].".ddbdiscuss_boards_id",$this->data['ddbdatalinker_id_object'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdiscuss_boards_data']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['discuss_boards_table'].".ddbdiscuss_boards_data",$this->data['ddbdiscuss_boards_data'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdiscuss_boards_last_id']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['discuss_boards_table'].".ddbdiscuss_boards_last_id",$this->data['ddbdiscuss_boards_last_id'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdiscuss_boards_last_tid']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['discuss_boards_table'].".ddbdiscuss_boards_last_tid",$this->data['ddbdiscuss_boards_last_tid'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdiscuss_boards_last_preview']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['discuss_boards_table'].".ddbdiscuss_boards_last_preview",$this->data['ddbdiscuss_boards_last_preview'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdiscuss_boards_posts']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['discuss_boards_table'].".ddbdiscuss_boards_posts",$this->data['ddbdiscuss_boards_posts'],"number"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdiscuss_boards_public']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['discuss_boards_table'].".ddbdiscuss_boards_public",$this->data['ddbdiscuss_boards_public'],"string"); }
					if (($this->data_insert_mode)||(isset ($this->data_changed['ddbdiscuss_boards_locked']))) { $f_update_values .= $direct_classes['db']->define_set_attributes_encode ($direct_settings['discuss_boards_table'].".ddbdiscuss_boards_locked",$this->data['ddbdiscuss_boards_locked'],"string"); }
					$f_update_values .= "</sqlvalues>";

					$direct_classes['db']->define_set_attributes ($f_update_values);
					if (!$this->data_insert_mode) { $direct_classes['db']->define_row_conditions ("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['discuss_boards_table'].".ddbdiscuss_boards_id",$this->data['ddbdatalinker_id_object'],"string"))."</sqlconditions>"); }
					$f_return = $direct_classes['db']->query_exec ("co");

					if ($f_return)
					{
						if (function_exists ("direct_dbsync_event"))
						{
							if ($this->data_insert_mode) { direct_dbsync_event ($direct_settings['discuss_boards_table'],"insert",("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['discuss_boards_table'].".ddbdiscuss_boards_id",$this->data['ddbdatalinker_id_object'],"string"))."</sqlconditions>")); }
							else { direct_dbsync_event ($direct_settings['discuss_boards_table'],"update",("<sqlconditions>".($direct_classes['db']->define_row_conditions_encode ($direct_settings['discuss_boards_table'].".ddbdiscuss_boards_id",$this->data['ddbdatalinker_id_object'],"string"))."</sqlconditions>")); }
						}

						if (!$direct_settings['swg_auto_maintenance']) { $direct_classes['db']->optimize_random ($direct_settings['discuss_boards_table']); }
					}
				}
			}

			if (($f_insert_mode_deactivate)&&($this->data_insert_mode)) { $this->data_insert_mode = false; }

			if ($f_return) { $direct_classes['db']->v_transaction_commit (); }
			else { $direct_classes['db']->v_transaction_rollback (); }
		}

		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -discuss_board_handler->update ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}
}

/* -------------------------------------------------------------------------
Mark this class as the most up-to-date one
------------------------------------------------------------------------- */

define ("CLASS_direct_discuss_board",true);

//j// Script specific commands

if (!isset ($direct_settings['discuss_account_status_ex'])) { $direct_settings['discuss_account_status_ex'] = false; }
if (!isset ($direct_settings['discuss_datacenter_path_symbols'])) { $direct_settings['discuss_datacenter_path_symbols'] = $direct_settings['path_themes']."/$direct_settings[theme]/"; }
if (!isset ($direct_settings['swg_auto_maintenance'])) { $direct_settings['swg_auto_maintenance'] = false; }
}

//j// EOF
?>