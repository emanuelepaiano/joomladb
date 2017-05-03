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

require "includes.php";

/** 
 * interface used to create Joomla objects
 * 
 * */
interface JoomlaTbl{
	/**
	 * Function to return category attributes from database
	 * @param none
	 * @return array string contains label of Fields
	 * @example $field=$category->getAttributesList()
	 */ 
	function getAttributesList();
	
	/**
	 * Function to return an attribute value (ex id, title, fulltext, etc)
	 * @param $fieldLabel a field in the mysql table
	 * @return string contains value of Fiels
	 * @example echo $article->getValueOf('title') - print article title on page
	 */ 
	function getValueOf($fieldLabel);
	
	/**
	 * extract article from database using filter $field=$value
	 * @param $field - field to check
	 * @param $value - value to field
	 * @return none
	 * @example $article->fetchByField('title', 'FAQ')
	 */ 
	function fetchByField($field, $value);
	
}

/**
 *	Generic Database Object. You should use subclasses (Article, Category, etc)
 *  to create obiects
 *	
 */	
class JoomlaDB{

	/**
	 * Global var used to set joomla database login settings (see dbconf.php)
	 * */	
	private $jver;
	
	/**
	 * Global var used to set joomla database tables settings (see dbconf.php)
	 * */	
	private $dbObject;    

	/**
	 * Global var used to set joomla database name (see dbconf.php)
	 * */
	private $dbname;
    
	/**
	 * Global var used to set database user (see dbconf.php)
	 * */
	private $dbuser;
	
	/**
	 * Global var used to set database password (see dbconf.php)
	 * */
	private $dbpass;
	
	/**
	 * Global var used to set database host (see dbconf.php)
	 * */
	private $dbhost;

	/**
	 * Global var used to set joomla database prefix tables (see dbconf.php)
	 * */
	private $table_prefix;

	/**
	 * Global var used to set this object instanced from this class for subclasses
	 * */
	private $dbController;

	/**
	 * JoomlaDB main constructor you should't use this class. Use subclass like Article, Category, etc
	 * @param $dbConf: database configuration available in dbConf.php file
	 * @param $jver: database version available in dbConf.php file
	 * @example $article=new Article($jdb1, $jtbl25): create an article using jdb1 configuration with joomla 2.5
	 * */
	function __construct($dbConf, $jver)
	{
	 $this->setDB($dbConf, $jver);
	}
	
	/**
	 * get JoomlaDB Object. Used for Subclass
	 * @param none
	 * @return JoomlaDB
	 * */
	 function getController()
	{
		return $this->dbController;
	}
	
	/**
	 * used to get tables configuration
	 * @param none
	 * @return array
	 * */
	function getJver()
	{
		return $this->jver;
	}
	
	/**
	 * initialize database
	 * @param none
	 * @return none
	 * */
	function newDBConnection()
	{
		$this->dbController=new Database($this->dbhost, $this->dbuser, $this->dbpass,$this->dbname);
	}
	
	/**
	 * Reset database settings.
	 * @param $dbConf: database configuration available in dbConf.php file
	 * @param $jver: database version available in dbConf.php file
	 * */
	function setDB($dbConf, $jver) 
	{ 

	 $this->jver=$jver;

	 $this->dbObject=$dbConf;

	 $this->setHost($dbConf['dbhost']);

	 $this->setUser($dbConf['dbuser']);

	 $this->setPass($dbConf['dbpass']);

	 $this->setName($dbConf['dbname']);

	 $this->setPrefix($dbConf['table_prefix']);
	
	 $this->newDBConnection();

    }

	/**
	 * Return database configuration
	 * */
	function getDBConf()
	{
		return $this->dbObject;
	}
	
	/**
	 *  set prefix table (ex. uuyp1 for uuyp1_content, etc..)
	 *  @param $value: new prefix value 
	 * */
	function setPrefix($value)
	{
	  $this->table_prefix=$value;	
	}
	
	/**
	 *  set database name
	 *  @param $value: new name value 
	 * */
	function setName($value)
	{
	  $this->dbname=$value;
   	}
    
	/**
	 *  return database object
	 *   
	 * */
	function getDB()
	{
	  return $this->dbObject;	
	}
    
	/**
	 *  return database name
	 *   
	 * */
	function getName(){
	  return $this->dbname;
  	}

	/**
	 *  return table prefix
	 *   
	 * */
	function getPrefix()
	{
	  return $this->table_prefix;	
	}

	/**
	 *  set database username
	 *  @param $value: new user value 
	 * */
	function setUser($value)
	{
	  $this->dbuser=$value;
    }

	/**
	 *  return database username 
	 * */
	function getUser()
	{
	  return $this->dbuser;	
	}

	/**
	 *  set database password
	 *  @param $value: new password value 
	 * */
	function setPass($value)
	{
	  $this->dbpass=$value;
    }

	/**
	 *  return password value 
	 * */
	function getPass()
	{
	  return $this->dbpass;	
	}

	/**
	 *  set database remote (or local) host 
	 *  @param $value: new hostname value (or ip address)
	 * */
	function setHost($value)
	{
	  $this->dbhost=$value;
    }

	/**
	 *  get database remote (or local) host 
	 * */
	function getHost()
	{
	  return $this->dbhost;	
	}
}

/**
 *	Article Database Object. Used to manage your joomla articles
 *  @example $article=new Article($jdb1, $jtbl25);
 *	
 */	
class Article extends JoomlaDB implements JoomlaTbl
{
	/* table readed from db */
	private $table;
	
	/* table fields from db */
	private $tableFields;
	
	/* table current version joomla */
	private $jver;
	
	/**
	 * Function to return an attribute value (ex id, title, fulltext, etc)
	 * @param $fieldLabel a field in the mysql table
	 * @return string contains value of Fiels
	 * @example echo \$article->getValueOf('title') - print article title on page
	 */ 
	function getValueOf($fieldLabel)
	{
		if ($this->table!=null)
		{
			 if (in_array($fieldLabel, $this->tableFields)) 
			 		return $this->table[0]["$fieldLabel"];
			 else die('Error: key ' . $fieldLabel . ' not found in article table!'); 
		}else die("You should call fetchByField() first.");
	}
	
	
	/**
	 * Function used from getAttributesList() to initialize $tableFields
	 * see getAttributesList() instead
	 */ 
	private function fetchAttributesFromDb()
	{
		$this->jver=$this->getJver();
		$this->getController()->execQuery('select * from ' . $this->getPrefix() . $this->jver['article']);
		$this->tableFields=$this->getController()->getFields();
	}
	
	
	/**
	 * Function to return article attributes from database
	 * @param none
	 * @return array string contains label of Fields
	 * @example $field=$article->getAttributesList()
	 */ 
	function getAttributesList()
	{
		$this->fetchFromDbAttributes();
		return $this->tableFields;
	}
	
	
	/**
	 * extract article from database using filter $field=$value
	 * @param $field - field to check
	 * @param $value - value to field
	 * @return none
	 * @example $article->fetchByField('title', 'FAQ')
	 */ 
	function fetchByField($field, $value)
	{
		$this->jver=$this->getJver();
		$this->fetchAttributesFromDb();
		$this->tableFields=$this->getController()->getFields();
		$this->getController()->execQuery('select * from ' . $this->getPrefix() . $this->jver['article'] .' where ' . $field . '="' . $value . '"');
		if ($this->getController()->getTable()!=null) 
			$this->table=$this->getController()->getTable();
		else die('Warning: Article with ' . $field . '=' . $value .'not found!!');
	}

	/**
	 * generate article href link to use into <a></a> tag
	 * @param $seoFriendly - pass true if you are Seo Friendly Enabled (Global Setting -> url settings) false otherwise
	 * @return string path to article
	 * @example echo "<a href='../joomla/" . $article->generateHref() . "'>click</a>";
	 */ 
	function generateHref($seoFriendly=TRUE)
	{
		if ($seoFriendly)
		{
			$id=$this->getValueOf('id');
			$alias=$this->getValueOf('alias');
			return "index.php/". $id ."-". $alias;
		}else{
			die("Non-seo friendly links not implemented yet");
		}
	}
	
	
} 

/**
 *	Category Database Object. Used to manage your joomla categories
 *  @example $category=new Category($jdb1, $jtbl25);
 *	
 */	
class Category extends JoomlaDB implements JoomlaTbl
{
	/* table current version joomla */
	private $jver;
	
	/* table readed from db */
	private $table;
	
	/* table fields from db */
	private $tableFields;
	
	/* Articles list in this category */
	private $articleSet;
	
	/* Sub-categories list in this category */
	private $categorySet;
	
	/**
	 * Function used from getAttributesList() to initialize $tableFields
	 * see getAttributesList() instead
	 */ 
	private function fetchAttributesFromDb()
	{
		$this->jver=$this->getJver();
		$this->getController()->execQuery('select * from ' . $this->getPrefix() . $this->jver['category']);
		$this->tableFields=$this->getController()->getFields();
	}
	
	/**
	 * used by getSubCategories function
	 */ 
	private function fetchSubCategories()
	{
		$id=$this->getValueOf('id');
		$this->getController()->execQuery('select * from ' . $this->getPrefix() . $this->jver['category'] .' where parent_id="' . $id . '"');
		if ($this->getController()->getTable()!=null) 
		{
			$categories=$this->getController()->getTable();
			foreach ($categories as $key => $value) {
				$this->categorySet[$key]=new Category($this->getDBConf(), $this->getJver());
				$this->categorySet[$key]->fetchByField('id', $categories[$key]['id']);
			}
		}
	}
	
	/**
	 * used by getArticles function
	 */ 	
	private function fetchArticles()
	{
		$id=$this->getValueOf('id');
		$this->getController()->execQuery('select * from ' . $this->getPrefix() . $this->jver['article'] .' where catid="' . $id . '"');
		if ($this->getController()->getTable()!=null) 
		{
			$arts=$this->getController()->getTable();
			foreach ($arts as $key => $value) 
			{
				$this->articleSet[$key]=new Article($this->getDBConf(), $this->getJver());
				$this->articleSet[$key]->fetchByField('id', $arts[$key]['id']);
			}
		}
	}
	
	
	
	/**
	 * Function to return an attribute value (ex id, title, fulltext, etc)
	 * @param $fieldLabel a field in the mysql table
	 * @return string contains value of Fiels
	 * @example echo $category->getValueOf('title') - print category title on page
	 */ 
	function getValueOf($fieldLabel)
	{
		if ($this->table!=null)
		{
			 if (in_array($fieldLabel, $this->tableFields)) 
			 		return $this->table[0]["$fieldLabel"];
			 else die('Error: key ' . $fieldLabel . ' not found in category table!'); 
		}else die("You should call fetchByField() first.");
	}
	
	/**
	 * Function to return category attributes from database
	 * @param none
	 * @return array string contains label of Fields
	 * @example $field=$category->getAttributesList()
	 */ 
	function getAttributesList()
	{
		$this->fetchFromDbAttributes();
		return $this->tableFields;
	}
	
	
	/**
	 * extract category from database using filter $field=$value
	 * @param $field - field to check
	 * @param $value - value to field
	 * @return none
	 * @example $category->fetchByField('title', 'FAQ')
	 */ 
	function fetchByField($field, $value)
	{
		$this->jver=$this->getJver();
		$this->fetchAttributesFromDb();
		$this->tableFields=$this->getController()->getFields();
		$this->getController()->execQuery('select * from ' . $this->getPrefix() . $this->jver['category'] .' where ' . $field . '="' . $value . '"');
		if ($this->getController()->getTable()!=null) 
			$this->table=$this->getController()->getTable();
		else die('Warning: Category with ' . $field . '=' . $value .'not found!!');
	}
	
	/**
	 * extract subcategories from database in current category
	 * @return array table contains Categories
	 * @example $subcats=$category->getSubCategories();
	 * @example echo  $subcats[0]->getValueOf('title');
	 */ 
	function getSubCategories()
	{
		if ($this->categorySet==null) $this->fetchSubCategories();
		if ($this->categorySet!=null) return $this->categorySet;
		else return NULL;
	}
	
	/**
	 * extract articles from database in current category
	 * @return array table contains Categories
	 * @example $articles=$category->getArticles();
	 * @example echo $articles[0]->getValueOf('title');
	 */ 
	function getArticles()
	{
		if ($this->articleSet==null) $this->fetchArticles();
		if ($this->articleSet!=null) return $this->articleSet;
		else return NULL;
	}
	
	
	
}

/**
 *	User Database Object. Used to manage your joomla users
 *  @example $user=new User($jdb1, $jtbl25);
 *	
 */	
class User extends JoomlaDB implements JoomlaTbl
{
	/* table readed from db */
	private $table;
	
	/* table fields from db */
	private $tableFields;
	
	/* table current version joomla */
	private $jver;
	
	/**
	 * Function used from getAttributesList() to initialize $tableFields
	 * see getAttributesList() instead
	 */ 
	private function fetchAttributesFromDb()
	{
		$this->jver=$this->getJver();
		$this->getController()->execQuery('select * from ' . $this->getPrefix() . $this->jver['user']);
		$this->tableFields=$this->getController()->getFields();
	}
	
	/**
	 * Function to return users attributes from database
	 * @param none
	 * @return array string contains label of Fields
	 * @example $field=$user->getAttributesList()
	 */ 
	function getAttributesList()
	{
		$this->fetchFromDbAttributes();
		return $this->tableFields;
	}
	
	/**
	 * Function to return an attribute value (ex id, title, fulltext, etc)
	 * @param $fieldLabel a field in the mysql table
	 * @return string contains value of Fiels
	 * @example echo $user->getValueOf('email') - print user mail on page
	 */ 
	function getValueOf($fieldLabel)
	{
		if ($this->table!=null)
		{
			 if (in_array($fieldLabel, $this->tableFields)) 
			 		return $this->table[0]["$fieldLabel"];
			 else die('Error: key ' . $fieldLabel . ' not found in user table!'); 
		}else die("You should call fetchByField() first.");
	}
	
	/**
	 * extract user from database using filter $field=$value
	 * @param $field - field to check
	 * @param $value - value to field
	 * @return none
	 * @example $user->fetchByField('username', 'admin')
	 */ 
	function fetchByField($field, $value)
	{
		$this->jver=$this->getJver();
		$this->fetchAttributesFromDb();
		$this->tableFields=$this->getController()->getFields();
		$this->getController()->execQuery('select * from ' . $this->getPrefix() . $this->jver['user'] .' where ' . $field . '="' . $value . '"');
		if ($this->getController()->getTable()!=null) 
			$this->table=$this->getController()->getTable();
		else die('Warning: User with ' . $field . '=' . $value .'not found!!');
	}
	
}

/**
 *	Message Database Object. Used to manage your joomla messages
 *  @example $message=new Message($jdb1, $jtbl25);
 *	
 */	
class Message extends JoomlaDB implements JoomlaTbl
{
	/* table readed from db */
	private $table;
	
	/* table fields from db */
	private $tableFields;
	
	/* table current version joomla */
	private $jver;
	
	/**
	 * Function used from getAttributesList() to initialize $tableFields
	 * see getAttributesList() instead
	 */ 
	private function fetchAttributesFromDb()
	{
		$this->jver=$this->getJver();
		$this->getController()->execQuery('select * from ' . $this->getPrefix() . $this->jver['message']);
		$this->tableFields=$this->getController()->getFields();
	}
	
	/**
	 * Function to return users attributes from database
	 * @param none
	 * @return array string contains label of Fields
	 * @example $field=$message->getAttributesList()
	 */ 
	function getAttributesList()
	{
		$this->fetchFromDbAttributes();
		return $this->tableFields;
	}
	
	/**
	 * Function to return an attribute value (ex id, title, fulltext, etc)
	 * @param $fieldLabel a field in the mysql table
	 * @return string contains value of Fiels
	 * @example echo $message->getValueOf('email') - print user mail on page
	 */ 
	function getValueOf($fieldLabel)
	{
		if ($this->table!=null)
		{
			 if (in_array($fieldLabel, $this->tableFields)) 
			 		return $this->table[0]["$fieldLabel"];
			 else die('Error: key ' . $fieldLabel . ' not found in message table!'); 
		}else die("You should call fetchByField() first.");
	}
	
	/**
	 * extract user from database using filter $field=$value
	 * @param $field - field to check
	 * @param $value - value to field
	 * @return none
	 * @example $message->fetchByField('username', 'admin')
	 */ 
	function fetchByField($field, $value)
	{
		$this->jver=$this->getJver();
		$this->fetchAttributesFromDb();
		$this->tableFields=$this->getController()->getFields();
		$this->getController()->execQuery('select * from ' . $this->getPrefix() . $this->jver['message'] .' where ' . $field . '="' . $value . '"');
		if ($this->getController()->getTable()!=null) 
			$this->table=$this->getController()->getTable();
		else die('Warning: Message with ' . $field . '=' . $value .'not found!!');
	}
	
	/**
	 * extract current message sender user from database
	 * @param none
	 * @return none
	 * @example $user=$message->getFromUser()
	 */ 
	function getFromUser()
	{
		$user=new User($this->getDB(), $this->getJver());
		$user->fetchByField('id', $this->getValueOf('user_id_from'));
		return $user;
	}
	
	/**
	 * extract current message target user from database
	 * @param none
	 * @return none
	 * @example $user=$message->getToUser()
	 */ 
	function getToUser()
	{
		$user=new User($this->getDB(), $this->getJver());
		$user->fetchByField('id', $this->getValueOf('user_id_to'));
		return $user;
	}
	
}


/**
 *	Contact Database Object. Used to manage your joomla contacts
 *  @example $contact=new Contact($jdb1, $jtbl25);
 *	
 */	
class Contact extends JoomlaDB implements JoomlaTbl
{
	/* table readed from db */
	private $table;
	
	/* table fields from db */
	private $tableFields;
	
	/* table current version joomla */
	private $jver;
	
	/**
	 * Function used from getAttributesList() to initialize $tableFields
	 * see getAttributesList() instead
	 */ 
	private function fetchAttributesFromDb()
	{
		$this->jver=$this->getJver();
		$this->getController()->execQuery('select * from ' . $this->getPrefix() . $this->jver['contact']);
		$this->tableFields=$this->getController()->getFields();
	}
	
	/**
	 * Function to return contacts attributes from database
	 * @param none
	 * @return array string contains label of Fields
	 * @example $field=$contact->getAttributesList()
	 */ 
	function getAttributesList()
	{
		$this->fetchFromDbAttributes();
		return $this->tableFields;
	}
	
	/**
	 * Function to return an attribute value (ex id, title, fulltext, etc)
	 * @param $fieldLabel a field in the mysql table
	 * @return string contains value of Fiels
	 * @example echo $contact->getValueOf('telephone') - print telephone contact on page
	 */ 
	function getValueOf($fieldLabel)
	{
		if ($this->table!=null)
		{
			 if (in_array($fieldLabel, $this->tableFields)) 
			 		return $this->table[0]["$fieldLabel"];
			 else die('Error: key ' . $fieldLabel . ' not found in contacts table!'); 
		}else die("You should call fetchByField() first.");
	}
	
	/**
	 * extract contact from database using filter $field=$value
	 * @param $field - field to check
	 * @param $value - value to field
	 * @return none
	 * @example $contact->fetchByField('name', 'Supporto')
	 */ 
	function fetchByField($field, $value)
	{
		$this->jver=$this->getJver();
		$this->fetchAttributesFromDb();
		$this->tableFields=$this->getController()->getFields();
		$this->getController()->execQuery('select * from ' . $this->getPrefix() . $this->jver['contact'] .' where ' . $field . '="' . $value . '"');
		if ($this->getController()->getTable()!=null) 
			$this->table=$this->getController()->getTable();
		else die('Warning: Contact with ' . $field . '=' . $value .'not found!!');
	}
	
}


/**
 *	Generic Table Database Class. Used to manage all unimplemented joomla tables
 *  @example $obj=new GenericTable($jdb1, $jtbl25);
 *	
 */	
class GenericTable extends JoomlaDB implements JoomlaTbl
	{
	/* table readed from db */
	private $table;
	
	/* table fields from db */
	private $tableFields;
	
	/* table current version joomla */
	private $jver;
	
	/* table end suffix name like '_users' */
	private $suffix;
	
	/**
	 * Function used to set end suffix for table not found in jver in dbconf.php
	 * @example $obj->setSuffix('_users');
	 */ 
	function setSuffix($value)
	{
		$this->suffix=$value;
	}
	
	/**
	 * Function used from getAttributesList() to initialize $tableFields
	 * see getAttributesList() instead
	 */ 
	private function fetchAttributesFromDb()
	{
		$this->jver=$this->getJver();
		$this->getController()->execQuery('select * from ' . $this->getPrefix() . $this->suffix);
		$this->tableFields=$this->getController()->getFields();
	}
	
	/**
	 * Function to return objects attributes from database
	 * @param none
	 * @return array string contains label of Fields
	 * @example $field=$contact->getAttributesList()
	 */ 
	function getAttributesList()
	{
		$this->fetchFromDbAttributes();
		return $this->tableFields;
	}
	
	/**
	 * Function to return an attribute value (ex id, title, fulltext, etc)
	 * @param $fieldLabel a field in the mysql table
	 * @return string contains value of Fiels
	 * @example echo $obj->getValueOf('telephone') - print telephone contact on page
	 */ 
	function getValueOf($fieldLabel)
	{
		if ($this->table!=null)
		{
			 if (in_array($fieldLabel, $this->tableFields)) 
			 		return $this->table[0]["$fieldLabel"];
			 else die('Error: key ' . $fieldLabel . ' not found in ' . $this->getPrefix() . $this->suffix .' table!'); 
		}else die("You should call fetchByField() first.");
	}
	
	/**
	 * extract generic info from database using filter $field=$value
	 * @param $field - field to check
	 * @param $value - value to field
	 * @return none
	 * @example $obj->fetchByField('id', '1')
	 */ 
	function fetchByField($field, $value)
	{
		$this->jver=$this->getJver();
		$this->fetchAttributesFromDb();
		$this->tableFields=$this->getController()->getFields();
		$this->getController()->execQuery('select * from ' . $this->getPrefix() . $this->suffix .' where ' . $field . '="' . $value . '"');
		if ($this->suffix!=null)
		{
			if ($this->getController()->getTable()!=null) 
				$this->table=$this->getController()->getTable();
			else die('Warning: ' . $this->getPrefix() . $this->suffix .' with ' . $field . '=' . $value .' not found!!');
		}else die('You must call setSuffix() for this table first!');
	}

}
?>
