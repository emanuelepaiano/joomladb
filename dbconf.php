<?php 

/*
Libs to interface web applications with Joomla database
Copyright (C) 2011  Emanuele Paiano

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/


/* Global Configurations Joomla databases */

/* database1 */

$jdb1=array(

/* Set Mysql Host */

'dbhost'=>"localhost",

/* Set Mysql username */

'dbuser'=>"root",

/* Set Mysql password */

'dbpass'=>"area51",

/* Set Joomla database name */

'dbname'=>"joomla",

/* Set Joomla tables prefix */

'table_prefix'=>"uuyp1"

);


/* Global Configurations Joomla table end suffix Don't Touch!!*/

$jtbl25=array(

/* Used to indicate Joomla version */
'version'=>'2.5',

/* articles table name */
'article' =>'_content',

/* categories table name */
'category' =>'_categories',

/* users table name */
'user' =>'_users',

/* messages table name */
'message' =>'_messages',

/* contacts table name */
'contact' =>'_contact_details'

);

?>
