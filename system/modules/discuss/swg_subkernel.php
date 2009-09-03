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
* Subkernel for: discuss
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

if (!defined ("CLASS_direct_subkernel_discuss"))
{
//c// direct_subkernel_discuss
/**
* Subkernel for: discuss
*
* @author     direct Netware Group
* @copyright  (C) direct Netware Group - All rights reserved
* @package    sWG
* @subpackage discuss
* @uses       CLASS_direct_virtual_class
* @since      v0.1.00
* @license    http://www.direct-netware.de/redirect.php?licenses;gpl
*             GNU General Public License 2
*/
class direct_subkernel_discuss extends direct_virtual_class
{
/* -------------------------------------------------------------------------
Extend the class
------------------------------------------------------------------------- */

	//f// direct_subkernel_discuss->__construct ()
/**
	* Constructor (PHP5) __construct (direct_subkernel_discuss)
	*
	* @uses  direct_debug()
	* @uses  USE_debug_reporting
	* @since v0.1.00
*/
	public function __construct ()
	{
		if (USE_debug_reporting) { direct_debug (5,"sWG/#echo(__FILEPATH__)# -kernel_class->__construct (direct_subkernel_discuss)- (#echo(__LINE__)#)"); }

/* -------------------------------------------------------------------------
My parent should be on my side to get the work done
------------------------------------------------------------------------- */

		parent::__construct ();

/* -------------------------------------------------------------------------
Informing the system about the available function
------------------------------------------------------------------------- */

		$this->functions['subkernel_init'] = true;
	}

	//f// direct_subkernel_discuss->subkernel_init ($f_threshold_id = "")
/**
	* Running subkernel specific checkups.
	*
	* @param  string $f_threshold_id This parameter is used to determine if
	*         a request to write data is below the threshold (timeout).
	* @uses   direct_debug()
	* @uses   USE_debug_reporting
	* @return boolean True if the checkup finishes successfully
	* @since  v0.1.00
*/
	public function subkernel_init ($f_threshold_id = "")
	{
		global $direct_classes,$direct_settings;
		if (USE_debug_reporting) { direct_debug (2,"sWG/#echo(__FILEPATH__)# -kernel_class->subkernel_init ($f_threshold_id)- (#echo(__LINE__)#)"); }

		if (($direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/swg_output.php"))&&($direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/swg_db.php")))
		{
			if (direct_class_init ("db")) { $f_return = array (); }
			else { $f_return = array ("errors_core_unknown_error","FATAL ERROR:<br />Unable to instantiate &quot;db&quot;.<br /><br />sWG/#echo(__FILEPATH__)# -kernel_class->subkernel_init ()- (#echo(__LINE__)#)"); }
		}
		else { $f_return = array ("core_required_object_not_found","FATAL ERROR:<br />&quot;$direct_settings[path_system]/classes/swg_output.php&quot; was not found<br /><br />sWG/#echo(__FILEPATH__)# -kernel_class->subkernel_init ()- (#echo(__LINE__)#)"); }

		if (empty ($f_return))
		{
			if ($direct_classes['db']->v_connect ())
			{
				$direct_classes['kernel']->v_user_init ($f_threshold_id);
				$direct_classes['kernel']->v_group_init ();

				if ($direct_classes['basic_functions']->settings_get ($direct_settings['path_data']."/settings/swg_discuss.php"))
				{
					if (!$direct_settings['discuss']) { $f_return = array ("core_service_inactive","sWG/#echo(__FILEPATH__)# -kernel_class->subkernel_init ()- (#echo(__LINE__)#)"); }
				}
				else { $f_return = array ("core_required_object_not_found","FATAL ERROR:<br />&quot;$direct_settings[path_data]/settings/swg_discuss.php&quot; was not found<br /><br />sWG/#echo(__FILEPATH__)# -kernel_class->subkernel_init ()- (#echo(__LINE__)#)"); }
			}
			else { $f_return = array ("core_database_error","FATAL ERROR:<br />Error while setting up a database connection<br />sWG/#echo(__FILEPATH__)# -kernel_class->subkernel_init ()- (#echo(__LINE__)#)"); }
		}

		if (defined ("CLASS_direct_output_control")) { direct_output_theme ($direct_settings['theme']); }
		return /*#ifdef(DEBUG):direct_debug (7,"sWG/#echo(__FILEPATH__)# -kernel_class->subkernel_init ()- (#echo(__LINE__)#)",:#*/$f_return/*#ifdef(DEBUG):,true):#*/;
	}
}

/* -------------------------------------------------------------------------
Mark this class as the most up-to-date one
------------------------------------------------------------------------- */

$direct_classes['@names']['subkernel_discuss'] = "direct_subkernel_discuss";
define ("CLASS_direct_subkernel_discuss",true);

//j// Script specific commands

direct_class_init ("subkernel_discuss");
$direct_classes['kernel']->v_call_set ("v_subkernel_init",$direct_classes['subkernel_discuss'],"subkernel_init");
}

//j// EOF
?>