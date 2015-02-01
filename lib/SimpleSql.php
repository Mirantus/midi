<?php

/*======================================================================*\

		   SimpleSQL - The simple way to query

	Author:			Paul Williamson <webmaster@protonage.net>
	Copyright:		(C) 2004 Protonage.Net Productions (Paul Williamson),
				all rights reserved.
	Version:		1.00
	
	Summery:
		This class is meant to shortcut common MySQL database access tasks.
	
	Author Comments:
		This is my first complete class file and I'm quite pleased about 
		how it turned out. The script works perfectly on PHP > 4, hasn't 
		been tested on any versions < PHP 4. As for mysql I havn't tested 
		it on anything < API 3. 

		All programming and testing ran on a 600mhz iBook with PHP 4.3.2
		and MySQL API 3.23.49. This class file is currently running two
		large database applications on two different servers; so far it
		is flawless. If any bugs are found or comments, please e-mail me.

		I'm really bad at comming up with argument names for
		paramaters, but it shouldn't be very hard to figure it
		out. Also, please excuse my horriable spelling mistakes
		throughout the code. Everything should be pretty self-
		explanitory if your good at PHP/MySQL. And if your not then 
		your not alone, only like .001% of the world's population 
		knows what PHP is, so yeah... your pretty good if you are this far.
		
		Maybe I'll get around to writing a well developed readme/example 
		document, but for now, I like being lazy... and eating cheese.
		
		If you have any questions or comments then please feel
		free to contact me via e-mail or website (info at top).
		
		Other than that, have a great day and enjoy coding the easy way!
		
	Legal:
		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License version 2,
		as published by the Free Software Foundation.
	
		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.
	
		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

\*======================================================================*/

class SimpleSQL
{

	/**** Public Variables ****/

		/* Default Constructor Vars */

	var $db_server		=	"localhost";	//the server to connect to
	var $db_username	=	"root";		//username to authincate
	var $db_password	=	"";		//password to authincate
	var $db_name		=	"db_name";	//database name to connect to

		/* Dynamic Vars */

	var $db_table		=	"table";	//table being queried, it can
							//be changed in function arguments
													
	var $result		=	"";		//result from the query
	var $error		=	"";		//error or warning message stored here

	var $errmsgs		=	true;		//print detailed errors
	var $debug		=	false;		//print complete tracks of the functions (debugging)



	/**** Private Variables ****/
	
	var $_connected		=	false;		//connected or not?
	var $_link		=	NULL;		//contains the mysql resource in use
	var $_select_link	=	NULL;		//contains the last select resource
							//(for num_rows use)
	
		/* Regular Expressions */		
							//Modify if needed, POSIX type
	var $_reg_where		=	"((WHERE)? \w*='\w*'( AND|OR )?)*";		
	
							//Next one is a Pearl type, so be careful	
	var $_reg_break		=	"/^((`|')(.*?)((`|'),(`|')|(`|')\$))(.*)\$/si";					
							//If modified, make sure the backref
							//is ok with the rest of the function
																		
/*======================================================================*\
	Constructor function
	Note:
	   I made the db_name first because users can change 
	   the constructor vars up top and not have to worry 
	   with providing connection info, just the database
	   that the script will be connecting to.
\*======================================================================*/

	function SimpleSQL($db_name="",$db_server="",$db_username="",$db_password="")
	{		
		$this->db_name=(empty($db_name))?$this->db_name:$db_name;
		$this->db_server=(empty($db_server))?$this->db_server:$db_server;
		$this->db_username=(empty($db_username))?$this->db_username:$db_username;
		$this->db_password=(empty($db_password))?$this->db_password:$db_password;
	}


/*======================================================================*\
	Public functions
\*======================================================================*/

/*======================================================================*\
	Function:	get_content
	Purpose:	go into the database and grab the content
			in the supplied field(s) and/or row(s)
	Input:		optional arguments $DB_TABLE, $DB_WHERE
			for $DB_WHERE, match $this->_reg_where
	Output:		returns true if ok and sets $this->result
			as an array of keys and values from the table
\*======================================================================*/

	function get_content ($db_table="",$db_where="",$db_order="",$db_limit="")
	{

			/* Make sure the connection is still live */
		if (!$this->_connect(true))
		{
			echo ($this->errmsgs)?$this->error:"";
			return false;
		}
		
		/**** Error checking ****/
		
		$db_table=(!empty($db_table))?$db_table:$this->db_table;		//Make sure an argument was supplied
		$this->db_table=$db_table;						//Store it
		for($j=0;$j<2;$j++):							//Add correct mysql syntax to beginning
			$a=($j)?"db_order":"db_limit";					//for the order and limit arguments
			$b=($j)?"ORDER BY":"LIMIT";
			$$a=(empty($$a))?"":$this->_fix_sql($b,$$a);			//fix the syntax (spacing and all)
		endfor;																													
		if (!eregi($this->_reg_where,$db_where))				//Check to see if the where clause 
		{									//followed the regular expression
			echo ($this->errmsgs)?"<pre>Invalid WHERE clause," 
			.$this->_reg_where." does not match ".$db_where."":"";
			return false;
		}
			/* Sets important stuff if it's a where clause (see _fix_sql function) */
		$db_where=$this->_fix_sql("WHERE",$db_where);
		
		/**** Start the MySQL query ****/
		
		if (($this->_query("SELECT * FROM ".$db_table.$db_where.$db_order.$db_limit.";")) === FALSE)
		{
			echo ($this->errmsgs)?$this->error:"";
			return false;
		}
		
			/* Fetch the array from the database */
		for ($i=0;$row=mysql_fetch_array($this->_link,MYSQL_ASSOC);$i++)			
		{
			foreach($row as $key => $value)										
				$result[$i][$key]=$value;				//Return a nice array with field values
		}
		
		if (count($result) > 1)							//Is a multi dim array really needed?
			$this->result=$result;						//Store result and return true
		elseif (empty($i)) {
			echo ($this->errmsgs)?"<pre>SQL query: <b>"			//If nothing was found in the DB
			.$select_clause."</b>"." returned false.":"";
			return false;														
		}else
			$this->result=$result[0];
		return true;
	}



	
/*======================================================================*\
	Function:	insert
	Purpose:	put an array of data into a respected table
	Input:		Supply an ordered quoted rows and cols group
			matching in size, a closer look of the syntax
			follows: `field1`,`field2`,`field3` etc..
			or ' this follows for the first 2 arguments
			$DB_TABLE is optional
	Output:		returns true if ok and false if not
			if false, $this->error will be filled
\*======================================================================*/

	function insert($db_cols,$db_rows,$db_table="")
	{
				
			/* Make sure the connection is still live */
		if (!$this->_connect(true))
		{
			echo ($this->errmsgs)?$this->error:"";
			return false;
		}
		
		/**** Error checking ****/
	
		$db_table=(!empty($db_table))?$db_table:$this->db_table;		//Make sure an argument was supplied
		$this->db_table=$db_table;						//Store it
		if (empty($db_rows) || empty($db_cols))					//If no argument supplied, die with error
		{	
			echo ($this->errmsgs)?"Missing arguments in function " 
			."<b>insert</b>.":"";						//Show error and return false
			return false;
		}

		$db_rows_arr=$this->_break($db_rows);					//Send arguments over to _break function
		$db_cols_arr=$this->_break($db_cols);					//so it is safely in an array
		if (!$db_rows_arr || !$db_cols_arr)
		{																		
			echo ($this->errmsgs)?$this->error:"";
			return false;
		}
		
		if (count($db_rows_arr) != count($db_cols_arr))				//Make sure they have the
		{									//same number of elements
			echo ($this->errmsgs)?"Arrays do not match in size. "
			."<br />\n<br />\n<b>".$db_rows
			."</b><br />^ Has ".count($db_rows)." elements, while:"		//Show error and return false
			."<br />\n".$db_rows."<br />\n^ Has only "
			.count($db_rows)."\n":"";									
			return false; 
		}
		
		if (!$this->_field_exists($db_cols_arr))
		{
			echo ($this->errmsgs)?"The provided fields were not "
			."found in the database.":"";
			return false;
		}
		
			/* Reassemble the arrays into strings following the proper MySQL syntax */
		for ($j=0;$j<=1;$j++):							//Tells which variable the loop is on
			unset(${(empty($j))?"db_cols":"db_rows"});
			for ($i=0;$i<count($db_cols_arr);$i++):				//Pretty hard to explain code
				$c=(empty($j))?$db_cols_arr:$db_rows_arr;		//Basicly just reassembles the array
				$q=(empty($j))?"`":"'";					//into proper MySQL syntax
				$n=($i==0)?$q.$c[$i].$q.",":(($i==count($c)-1)?$q.$c[$i].$q:$q.$c[$i].$q.",");
				${(empty($j))?"db_cols":"db_rows"}.=$n;			//Reassign to the right variable
			endfor;
		endfor;
		
		/**** Start the Query ****/
		
		if ($this->_query("INSERT INTO `".$db_table."` (".$db_cols.") VALUES (".$db_rows.");")===false)
		{
			echo ($this->errmsgs)?$this->error:"";
			return false;
		}
		return true;
	}	
		
		
	
/*======================================================================*\
	Function:	update
	Purpose:	update content in a database
	Input:		Arguments are as follows:
			$DB_FIELD - Field being modified
			$DB_VALUE - New value of field
			$DB_TABLE - [optional]
			$DB_WHERE - [optional]
			$DB_LIMIT - [optional] (Default blank)
	Output:		returns true if ok and false if not
			if false, $this->error will be filled
\*======================================================================*/

	function update($db_field,$db_value,$db_table="",$db_where="",$db_limit="")
	{
	
			/* Make sure the connection is still live */
		if (!$this->_connect(true))
		{
			echo ($this->errmsgs)?$this->error:"";
			return false;
		}
		
		/**** Error Check ****/
		
		$db_table=(!empty($db_table))?$db_table:$this->db_table;		//Make sure an argument was supplied
		$this->db_table=$db_table;						//Store it
		if (empty($db_field) || empty($db_value))				//If no argument supplied, die with error
		{	
			echo ($this->errmsgs)?"Missing arguments in function " 
			."<b>update</b>.":"";						//Show error and return false
			return false;
		}
		
		$db_value=eregi_replace("'","\'",$db_value);				//Escape small quotes due to sql syntax
		
		$db_limit=$this->_fix_sql("LIMIT",$db_limit);				//Add correct mysql syntax to beginning
		if (!eregi($this->_reg_where,$db_where))				//Check to see if the where clause 
		{									//followed the regular expression
			echo ($this->errmsgs)?"<pre>Invalid WHERE clause," 
			.$this->_reg_where." does not match ".$db_where."":"";
			return false;
		}
			/* Sets important stuff if it's a where clause */
		$db_where=$this->_fix_sql("WHERE",$db_where);
		
		/**** Start the Query ****/
		
		if ($this->_query("UPDATE `".$db_table."` SET `".$db_field."`='".$db_value."'".$db_where.$db_limit.";")===false)
		{
			echo ($this->errmsgs)?$this->error:"";
			return false;
		}
		return true;				
	}
	
		
	
/*======================================================================*\
	Function:	delete
	Purpose:	delete a row in a database
	Input:		Arguments are as follows:
			$DB_WHERE
			$DB_TABLE - [optional]
			$DB_ORDER - [optional]
			$DB_LIMIT - [optional] (Default blank)
	Output:		returns true if ok and false if not
			if false, $this->error will be filled
\*======================================================================*/

	function delete($db_where,$db_table="",$db_order="",$db_limit="")
	{

			/* Make sure the connection is still live */
		if (!$this->_connect(true))
		{
			echo ($this->errmsgs)?$this->error:"";
			return false;
		}
		
			/**** Error Checking ****/
			
		if(empty($db_where))
		{	
			echo ($this->errmsgs)?"Missing argument <i>db_where</i> in function " 
			."<b>delete</b>.":"";						//Show error and return false
			return false;
		}
		$db_table=(empty($db_table))?$this->db_table:$db_table;			//check to see if db talbe exists in args
		$this->db_table=$db_table;		
		for($j=0;$j<2;$j++):							//Add correct mysql syntax to beginning
			$a=($j)?"db_order":"db_limit";					//for the order and limit arguments
			$b=($j)?"ORDER BY":"LIMIT";
			$$a=(empty($$a))?"":$this->_fix_sql($b,$$a);
		endfor;															
		if (!eregi($this->_reg_where,$db_where))				//Check to see if the where clause 
		{									//followed the regular expression
			echo ($this->errmsgs)?"<pre>Invalid WHERE clause," 
			.$this->_reg_where." does not match ".$db_where."":"";
			return false;
		}
			/* Sets important stuff if it's a where clause */
		$db_where=$this->_fix_sql("WHERE",$db_where);
		
		/**** Start the Query ****/
		
		$msg="<table>"								//fill out debug message
		."<tr><td width=250>\$db_where:</td><td>$db_where</td></tr>"
		."<tr><td width=250>\$db_table:</td><td>$db_table</td></tr>"
		."<tr><td width=250>\$db_order:</td><td>$db_order</td></tr>"
		."<tr><td width=250>\$db_limit:</td><td>$db_limit</td></tr>"
		."</table>";
		
		if ($this->_query("DELETE FROM `".$db_table."`".$db_where.$db_order.$db_limit.";")===false)
		{
			echo ($this->errmsgs)?$this->error:"";
			$this->_print_debug("delete",$msg,false);
			return false;
		}
		
		$this->_print_debug("delete",$msg,true);				//call debug function
		
		return true;	

	}
	
			
	
/*======================================================================*\
	Function:	num_rows
	Purpose:	return an integer of the number of
			rows in a query. If no arguments
			are provided, this function will
			return the num_rows of the last
			query
	Input:		Arguments are as follows:
			$DB_TABLE - [optional]
			$DB_WHERE - [optional]
			$DB_ORDER - [optional]
			$DB_LIMIT - [optional] (Default blank)
	Output:		return an integer of the number of
			rows in a query. 
\*======================================================================*/

	function num_rows($db_table="",$db_where="",$db_order="",$db_limit="")
	{
	
			/* Make sure the connection is still live */
		if (!$this->_connect(true))
		{
			echo ($this->errmsgs)?$this->error:"";
			return false;
		}
		
		$args[]=$db_table;							//put arguments into an array
		$args[]=$db_where;
		$args[]=$db_order;
		$args[]=$db_limit;
		
		$keep=false;
		for($i=0;$i<count($args);$i++):						//go through each to see if it's set
			if(!empty($args[$i])):
				$keep=true;
				break;							//if it is then set keep to true
			endif;
		endfor;
		
		if(!$keep)								//if no arguments, go ahead and get
			return @mysql_num_rows($this->_select_link);			//num_rows from last query 
		
			/**** Error Checking ****/
			
		$db_table=(empty($db_table))?$this->db_table:$db_table;			//check to see if db talbe exists in args
		$this->db_table=$db_table;		
		for($j=0;$j<2;$j++):							//Add correct mysql syntax to beginning
			$a=($j)?"db_order":"db_limit";					//for the order and limit arguments
			$b=($j)?"ORDER BY":"LIMIT";
			$$a=(empty($$a))?"":$this->_fix_sql($b,$$a);
		endfor;															
		if (!eregi($this->_reg_where,$db_where))				//Check to see if the where clause 
		{																		//followed the regular expression
			echo ($this->errmsgs)?"<pre>Invalid WHERE clause," 
			.$this->_reg_where." does not match ".$db_where."":"";
			return false;
		}
			/* Sets important stuff if it's a where clause */
		$db_where=$this->_fix_sql("WHERE",$db_where);
		
		
		/**** Start the Query ****/
		
		$msg.="<table>";							//for debugging, print if arguments present
		$msg.=(!empty($db_where))?"<tr><td width=250>\$db_where:</td><td>$db_where</td></tr>":"";
		$msg.=(!empty($db_table))?"<tr><td width=250>\$db_table:</td><td>$db_table</td></tr>":"";
		$msg.=(!empty($db_order))?"<tr><td width=250>\$db_order:</td><td>$db_order</td></tr>":"";
		$msg.=(!empty($db_limit))?"<tr><td width=250>\$db_limit:</td><td>$db_limit</td></tr>":"";
		$msg.="</table>";;
			
			/* send query and store into _select_link */
		if (($this->_query("SELECT * FROM ".$db_table.$db_where.$db_order.$db_limit.";",true)) === FALSE)
		{																		
			echo ($this->errmsgs)?$this->error:"";
			$this->_print_debug("num_rows",$msg,false);
			return false;
		}
		
		$return=mysql_num_rows($this->_select_link);				//get the resource for num_rows function
		$this->_print_debug("num_rows",$msg,$return);
		return $return;								//return int
		
	}


/*======================================================================*\
	Private functions
\*======================================================================*/


/*======================================================================*\
	Function:	_connect
	Purpose:	connect to the server provided
			function will return false if failed
			and the $error variable will be set
	Input:		$close - optional arg defaulted to false
			if true, it will reconnect to the server
	Output:		returns true if ok and sets $_connected to true
			returns false if failed and sets $error 
\*======================================================================*/

	function _connect($reopen=true)
	{
		if($this->_connected) {							//Incase we're connected, 
			if($reopen) :							//close the connection if true
				@mysql_close($this->_link);				//(@ excludes warnings)
				$this->_connected=false;				//false it
				return $this->_connect();				//reopen it
			else:
				return true;
			endif;
		}else																	
		{																		

				/* Open the mysql_connect resource and put to $this->_link, if errors, echo errors */
			if (($this->_link=mysql_connect($this->db_server,$this->db_username,$this->db_password)) === false)
			{																
				$this->error="<pre>Could not connect to server \""
				.$this->db_server."\". \nMySQL Says: <b>"		//Echo the error found
				.mysql_error()."</b>\n\n";
				$this->_connected=false;
				return false;
			}
				/* Select the database, on error echo it */
			if (!mysql_select_db($this->db_name,$this->_link))
			{
				$this->error="<pre>Could not select database \""		
				.$this->db_name."\". \nMySQL Says: <b>"			//Echo the error found
				.mysql_error()."</b>\n\n";
				$this->_connected=false;
				return false;
			}
	
			$this->_connected=true;						//set all to true and continue
			return true;
		}
	}
	

/*======================================================================*\
	Function:	_query
	Purpose:	Sends the MySQL query and returns result.
			Main function is to take care of the error
			messages and reduce the amount of code.
	Input:		$clause - The query string
			$select	- [optional] True if the query is 
			for _select_link only
	Output:		returns status of the query
\*======================================================================*/

	function _query($clause,$select=false)
	{
		$msg="<table>"
		."<tr><td width=250>\$clause:</td><td>$clause</td></tr>"
		."</table>";
								
		if (($this->{($select)?"_select_link":"_link"}=mysql_query($clause)) === false)
		{
			$this->error="<pre>There was an error executing " 
			."the following query: \"".$clause.""				//If there was an error, echo what
			."\"\n\nMySQL says: "						//the error string was
			."<b>".mysql_error()."</b>";									
			$this->_print_debug("_query",$msg,false);
			return false;
		}
											//Hand down the last select query
		$this->_select_link=(eregi("^SELECT",$clause))?$this->_link:$this->_select_link;
											//(for num_rows use)
		$this->_print_debug("_query",$msg,true);
		
		return true;								//Should be a successful entry
	}
	
/*======================================================================*\
	Function:	_kill
	Purpose:	kill all connections to the sql server
\*======================================================================*/

	function _kill()
	{
		$this->_link=NULL;
		@mysql_close();
		$this->_connected=false;
	}
	

/*======================================================================*\
	Function:	_field_exists
	Purpose:	This function will search the table and 
			return true if the field was found, false
			if not
	Input:		$db_field - field name to find in table
			this can be an array
			$db_table - optional arg, if not set then
			it will default to the currently selected table
	Output:		returns true if found, false if not
\*======================================================================*/

	function _field_exists($db_field,$db_table="")
	{
	
			/* Make sure the connection is still live */
		if (!$this->_connect(false))
			return false;
		
			/* Error checking */
		if (empty($db_field))							//Make sure field isn't blank
		{
			$this->error="No argument supplied for function _field_exists.";
			return false;
		}
		$db_table=($db_table!="")?$db_table:$this->db_table;			//Make sure an argument was supplied
		$this->db_table=$db_table;						//Store it
		
		if (($list_fields=mysql_list_fields($this->db_name,$db_table)) === false)
		{
			$this->error="There was an error listing the fields in: ".$this->db_name.".".$db_table."";
			return false;
		}
		if (is_array($db_field)) {
			$num_found=0;
			foreach($db_field as $field) :
				for($i=0;$i<mysql_num_fields($list_fields);$i++) :
					if($field==mysql_field_name($list_fields,$i)) :
						$num_found++;				//if found add 1 to num_fields
						break;
					endif;
				endfor;																
			endforeach;
			return ($num_found == count($db_field))?true:false;		//make sure all were found
		}else{
			for($i=0;$i<mysql_num_fields($list_fields);$i++) {
				if($db_field==mysql_field_name($list_fields,$i))
					return true;					//return if found
			}
			return false;
		}
	}
	

/*======================================================================*\
	Function:	_break
	Purpose:	This function will break down a string with 
			the regular expression provided and store it's
			contents in an array and return the array
			if the string is not valid, it will return false.
			The string format follows: 'var1','var2','etc..'
	Input:		$string - the string you wish to break down
	Output:		returns an array of the borken down string
			returns false if something went wrong
\*======================================================================*/

	function _break($string)
	{

			/* Error Check */
		if (empty($string))
		{
			$this->error="No argument supplied for function _break.";
			return false;
		}
		
			/* Regular Expressions */

		$bufstr=$string;							//Keep the orginal value of $string
		do																		//run once, keep going untill			
		{																		//the string is empty
			$return[]=preg_replace($this->_reg_break,"\$3",$bufstr);
			$bufstr="'".preg_replace($this->_reg_break,"\$8",$bufstr);
		}while (preg_match($this->_reg_break,$bufstr));
		if ((count($return)==1) && empty($return[0]))
		{
			$this->error="The supplied argument <b>"
			.$string."</b> did not match pearl regular "			//if for some reason the array
			."expression <b>".$this->_reg_break."</b>";			//has only 1 element with no value
			return false;							//then something went wrong with 
		}									//the string
		
		$msg="<table>"
		."<tr><td width=250>\$string:</td><td>$string</td></tr>"		//fill the debug string			
		."</table>";
		
		$this->_print_debug("_break",$msg,$return);
		
		return $return;
	}


/*======================================================================*\
	Function:	_fix_sql
	Purpose:	Simple function that fixes the SQL syntax
			for many clauses such as WHERE and ORDER BY
	Input:		$type - ie WHERE, ORDER BY
			$clause - the whole string
	Output:		returns the correct syntax string for the type
\*======================================================================*/

	function _fix_sql($type, $clause)
	{
		if(empty($clause)):return ""; endif;					//if clause is empty, no need to resume
		$type=trim(strtoupper($type));
		$return=(!empty($clause))?(!eregi("^".$type,$clause))?" ".$type." ".$clause."":" ".$clause."":"";
		$msg="<table>"								//fill debug string
		."<tr><td width=250>\$type:</td><td>$type</td></tr>"
		."<tr><td width=250>\$clause:</td><td>$clause</td></tr>"
		."</table>";
		$this->_print_debug("_fix_sql",$msg,$return);
		return $return;								//return the fixed results
	}
	
	

/*======================================================================*\
	Function:	_print_debug
	Purpose:	Just prints the debug results in a nice
			easy-to-read HTML format
\*======================================================================*/

	function _print_debug($function="",$args="",$return="")
	{
		$msg="<br /><br /><hr><h4>".$function."</h4>"				//put into HTML
		."<h5>Arguments</h5>\n<pre>".$args
		."\n</pre><h5>Return</h5><pre>"
		.$return."</pre><hr><br /><br />";
		echo($this->debug)?$msg:"";						//echo debug message if debugging enabled
	}
	
}	//End SimpleSQL class

?>