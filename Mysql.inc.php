<?php
/*
Libs to interface web applications with Joomla database
Copyright (C) 2012  Emanuele Paiano

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


class Database
{
 private $host;
 
 private $username;
 
 private $password;
 
 private $database;
 
 private $mysql_i;
 
 private $connected=false;
 
 private $query;
 
 private $result;
 
 private $output;
 
 private $last_error;
 
 private $table;
 
 /**
 * Initialize Database object and connect 
 */
 
 function __construct($host, $username, $password, $dbname=FALSE)
 {
 	if ($dbname!=FALSE) $this->useDatabase($dbname);
	$this->setHost($host);
	$this->setUsername($username);
	$this->setPassword($password);
 }
 
  /**
 *   Connect to database 
 */
 function connect()
 {
	$this->mysql_i=new mysqli($this->host, $this->username, $this->password, $this->database);
	if (mysqli_connect_errno())
	{ 
		$this->connected=false;
		$this->last_error=mysqli_connect_error();
		
	}
	else $this->connected=true;
 }
 
  /**
 *  Close connection with remote database 
 */
 function disconnect()
 {
	if ($this->isConnected()) $this->mysql_i->close();
	$this->connected=false;
 }
 
  /**
 *  return last error from remote host
 */
 function getLastError()
 {
	return $this->last_error;
 }
 
  /**
 *  return mysql_i object and use mysqli php methods
 */
 function getMysql_i()
 {
	return $this->mysql_i;
 }
 
  /**
 * set remote host 
 */
 function setHost($value)
 {
	$this->host=$value;
 }

 /**
 * get remote host 
 */
 function getHost()
 {
	return $this->host;
 }
 
/**
 * set username used to connect to remote host 
 */
   function setUsername($value)
 {
	$this->username=$value;
 }
 
  /**
 * get username used to connet to remote host
 */
 function getUsername()
 {
	return $this->username;
 }
  
   /**
 * set password used to connet to remote host
 */
    function setPassword($value)
 {
	$this->password=$value;
 }
 
    /**
 * return password used to connet to remote host
 */
 function getPassword()
 {
	$this->password;
 }

/**
 * create an empty database
 *
 */
function createDatabase($dbname)
{
	if (!$this->isConnected())
	{
		$this->connected=TRUE;
		$conn=mysql_connect($this->host,$this->username,$this->password) or die (mysql_error());
		$sql = mysql_query('CREATE DATABASE ' . $dbname) or die (mysql_error());
 		mysql_close($conn);
		
	}
}
 
/**
 * use database to work 
 */
 function useDatabase($value){
 	$this->setDatabase($value);
 }
 
/**
 * set database name used to connet to remote host
 */
  function setDatabase($value)
 {
	$this->database=$value;
 }
 
    /**
 * return database used to connet to remote host
 */
 function getDatabase()
 {
	$this->database;
 }
 
/**
 * return true if connected 
 * false if disconnected
 */
 function isConnected()
 {
	return $this->connected;
 }
 
 /**
 *  Set internal sql query but don't execute 
 * 
 */
 function setQuery($value)
 {
	$this->query=$value;
 }
 
  /**
 *  return internal query 
 * 
 */
 function getQuery()
 {
	return $this->query;
 }

  /**
 *  exec internal query 
 * 
 */
function execInternalQuery()
{
	return execQuery($this->query);
}

  /**
 *  return last result from execQuery()
 *  isn't a string!!
 */
function getLastResult()
{
	return $this->result;
}

  /**
 *  return last result from execQuery()
 *  it's a string
 */
function getOuput()
{
	return $this->output;
}

  /**
 *  if last query was a select this method return the table
 *  in array forms: Array[integer][table_field];
  * ex. if you want to access to 3rd row to 'name' field you should use 
 *  $ar=$db->getTable();
 *  echo $ar[2]['name'];
 */ 	
function getTable()
{
	return $this->table;
}

function execQuery($value)
{
	$this->setQuery($value);
	if (!$this->isConnected()) $this->connect();
	if (!mysqli_connect_errno()) {
		$this->result = $this->mysql_i->query($this->query);
		if ($this->result!=null){
			if($this->result->num_rows>0)
			{
				unset($this->table);
				$i=0;
				while($row = $this->result->fetch_array(MYSQLI_ASSOC))
				{
					$this->table[$i]=$row;
					$i++;
				}
				
			}
		}else{
			die("There is an error: previous query didn't return output.");
		}
	}
	$this->disconnect();
}
 
 /**
 * Print table structure. Useful for debug
 *
 */
 function printTable()
{
	foreach($this->table as $key => $row) {
		echo "Row " . $key . " ";
		foreach($row as $column => $elem) 
			echo $column . ":" . $elem;
	}
}
 
 /**
 * return an array with field from getTable();
 *
 */
function getFields()
{
	$fields=array();
	$i=0;
	foreach($this->table as $key => $row) {
		foreach($row as $column => $elem) 
		{
			$fields[$i]=$column;
			$i++;
		}
	}
	return $fields;
}

}

?>
