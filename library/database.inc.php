<?php

class DB
{

	# -------------------------------------------------------------------------#
	# Variables
	# -------------------------------------------------------------------------#
	var $mysqli; 	# ids for DB connection
	var $result; 	# previous request value returned

	
	# -------------------------------------------------------------------------#
	# Constructor
	# -------------------------------------------------------------------------#
	function DB()
	{
		$error = 0;

		# ---------------------------------------------------------------------#
		# Connection parameters
		# ---------------------------------------------------------------------#
		require($_SERVER['DOCUMENT_ROOT']."/library/database.php");

		# ---------------------------------------------------------------------#
		# Connection
		# ---------------------------------------------------------------------#
		$this->mysqli	= 	new mysqli($sql_serveur,$sql_user,$sql_pwd,$sql_base);		# Connection to server
		
        /* check connection */
        if ($this->mysqli->connect_errno) {
            printf("La connexion a échoué: %s\n", $this->mysqli->connect_error);
            exit();
        }  
						
	}


	# ------------------------------------------------------------------------------------------------ #
	# mysql_free_result is deprecated as of PHP 5.5.0, and will be removed in the future
	# ------------------------------------------------------------------------------------------------ #
	function dispose()
	{
		$this->result->close();
		return $this->mysqli = null;
	}





	# ------------------------------------------------------------------------------------------------ #
	# Requests
	# ------------------------------------------------------------------------------------------------ #

	# - request - mysql_query is deprecated as of PHP 5.5.0, and will be removed in the future.
    
	function query($request)
	{
		return ($this->result = $this->mysqli->query($request));
	}

	
    # - request : Count the number of results - mysql_num_rows is deprecated
	function count()
	{
		return (int)$this->result->num_rows;
	}

	# - request : Array
	function next($type="object")
	{
		if($type=="array")
		{
			return $this->result->fetch_array(MYSQLI_BOTH);
		}
		else
		{
			return $this->result->fetch_object();
		}
	}

	# - request : last SQL error number
	function errno()
	{
		return ($this->mysqli->connect_errno);
	}

	# - request : last SQL error message 
	function error()
	{
		return ($this->mysqli->connect_error);
	}

	# - request : Adjusts the result pointer to 0
	function rewind()
	{
		return ($this->result->data_seek(0));
	}
	function moveToLast()
	{
		return ($this->result->data_seek($this->count()-1));
	}

	# - request : last id inserted (AI)
	function lastId()
	{
		return $this->mysqli->insert_id;
	}

	
	function affectedRows ( ) {
		return( $this->mysqli->affected_rows );
	}


	



}

?>