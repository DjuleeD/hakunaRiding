<?php
class Serialization
{

	# -------------------------------------------------------------------------	#
	# attributes
	# -------------------------------------------------------------------------	#
	var $errorHandler;
	var $uid;
	# -------------------------------------------------------------------------	#

	# -------------------------------------------------------------------------	#
	# Constructor
	# -------------------------------------------------------------------------	#
	function Serialization($uid)
	{		
        if($uid)
		{
            echo "connected";
            echo $uid;
            $this->errorHandler = new Error();
            $this->uid = $uid;
            $this->o_db_serialization = new DB();
            $this->o_db_1 = new DB();
            
		}
		else exit ("Veuillez vous reconnecter");
	}
	# -------------------------------------------------------------------------	#


	# ------------------------------------------------------------------------- #
	# Serialize
	# ------------------------------------------------------------------------- #
	// passing by reference the array
    function mySerialize(&$a_objects)
	{
		foreach($a_objects as $key=>$value)
		{
			$value = urlencode(serialize($value));
			if($this->existInDB($key))
				$this->update($key,$value);
			else
				$this->create($key,$value);
		}
	}
	# ------------------------------------------------------------------------- #





	# ------------------------------------------------------------------------- #
	# Does the object exist?
	# ------------------------------------------------------------------------- #
	function existInDB($objName)
	{
		$sql="
				SELECT	temp_object.uid
				FROM	temp_object
				WHERE	temp_object.uid			=\"".$this->uid."\"
				AND		temp_object.object_name	=\"".$objName."\"
				";
		$this->o_db_serialization->query($sql);
		
        $count=$this->o_db_serialization->count();
		if($count>0)
			return true;
		else
			return false;
	}
	# ------------------------------------------------------------------------- #





	# ------------------------------------------------------------------------- #
	# create object by inserting it in db
	# ------------------------------------------------------------------------- #
	function create($objName,$objString)
	{
		$sql="
			INSERT INTO temp_object
			(
				temp_object.uid,
				temp_object.object_name,
				temp_object.object,
				temp_object.object_date
			)
			VALUES
			(
				\"".$this->uid		."\",
				\"".$objName		."\",
				\"".$objString		."\",
				\"".date("Y-m-d")	."\"
			)";
		$this->o_db_serialization->query($sql);
		
	}
	# ------------------------------------------------------------------------- #





	# ------------------------------------------------------------------------- #
	# update object in DB
	# ------------------------------------------------------------------------- #
	function update($objName,$objString)
	{
		$sql="
			UPDATE	temp_object
			SET
					temp_object.object				=\"".$objString."\",
					temp_object.object_date			=\"".date("Y-m-d")."\"
			WHERE	temp_object.uid					=\"".$this->uid."\"
			AND		temp_object.object_name			=\"".$objName."\"";
		$this->o_db_serialization->query($sql);
		
	}
	# ------------------------------------------------------------------------- #





	# ------------------------------------------------------------------------- #
	# delete object from table
	# ------------------------------------------------------------------------- #
	function delete($objName)
	{
		$sql="
			DELETE
			FROM	temp_object
			WHERE	temp_object.uid			=\"".$this->uid."\"
			AND		temp_object.object_name	=\"".$objName."\"
		";

		$this->o_db_serialization->query($sql);
		return $sql;
		
	}
	# ------------------------------------------------------------------------- #





	# ------------------------------------------------------------------------- #
	# Unserialize
	

	function myUnserialize($part = '')
	{
		
        // check if $part is useful
        // not for now
        
        $sql="
			SELECT	`uid`,
                    `object_name`,
                    `object`,
                    `object_date`,
                    `timestamp`
			FROM    temp_object
			WHERE	temp_object.uid	=\"".$this->uid."\"
			";

		$this->o_db_serialization->query($sql);
		
        $count=$this->o_db_serialization->count();

		if ($count>0)
		{
			while($row=$this->o_db_serialization->next())
			{
				global ${$row->object_name};
				${$row->object_name} = unserialize(urldecode($row->object));                       
                
			}
		}
	}
	# ------------------------------------------------------------------------- #





	function toString()
	{
		
	}
}
?>