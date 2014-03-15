<?php
if (! class_exists('Right')) {
class Right
{

	# ------------------------------------------------------------------------------------------------ #
	# member variables
	# ------------------------------------------------------------------------------------------------ #

	protected $right;					// access rights array
	protected $read_right;
	protected $add_right;
	protected $modify_right;
	protected $delete_right;

	protected $i_user;
	protected $i_profile;
	protected $s_login;


	# ------------------------------------------------------------------------------------------------ #
	# SETTERS
	# ------------------------------------------------------------------------------------------------ #
	function set_i_user($i)						{	$this->i_user						=	$i;	}
	function set_i_profile($i)					{	$this->i_profile					=	$i;	}
	function set_s_login($i)					{	$this->s_login						=	$i;	}
	
 	# ------------------------------------------------------------------------------------------------ #


	# ------------------------------------------------------------------------------------------------ #
	# GETTERS
	# ------------------------------------------------------------------------------------------------ #
	function get_i_user()					{	return $this->i_user;					}
	function get_i_profile()				{	return $this->i_profile;				}
	
	// types : add modify delete
	function get_i_right($type,$i)			{	return $this->right[$type][$i];			}
	function get_all_rights()				{	return $this->right;					}

	function get_read_right($i)             {	return $this->read_right[$i];           }
	function get_add_right($i)              {	return $this->add_right[$i];            }
	function get_modify_right($i)			{	return $this->modify_right[$i];         }
	function get_delete_right($i)           {	return $this->delete_right[$i];         }

    
    // NAB 
	function get_s_title()					{	return $this->s_title;					}
	function get_s_lastname()				{	return $this->s_lastname;				}
	function get_s_firstname()				{	return $this->s_firstname;				}
	function get_s_position_held()			{	return $this->s_position_held;			}
	function get_i_position_held()			{	return $this->i_position_held;			}
	function get_s_address()				{	return $this->s_address;				}
	function get_s_pc()						{	return $this->s_pc;						}
	function get_s_town()					{	return $this->s_town;					}
	function get_s_mail()					{	return $this->s_mail;					}

    
    // login
	function get_s_login()					{	return $this->s_login;					}
	
	
	# ------------------------------------------------------------------------------------------------ #

	
	# ------------------------------------------------------------------------------------------------ 	#
	# Constructor
	# ------------------------------------------------------------------------------------------------ 	#
	function Right()
	{
		# ------------------------------------------------------------------------------------------------ #
		# BDD
		# ------------------------------------------------------------------------------------------------ #
		$this->o_db = new DB();
		# ------------------------------------------------------------------------------------------------ #
	}
	# ------------------------------------------------------------------------------------------------ 	#

	
	# ------------------------------------------------------------------------------------------------ 	#
	# Fillin the rights
	# ------------------------------------------------------------------------------------------------ 	#
	function fillingRights()
	{
		
		# ------------------------------------------------------------------------------------------------ #
		# Who am I?
		# ------------------------------------------------------------------------------------------------ #

			# ------------------------------------------------------------------------------------------------ #
			# Positions held in array
			# ------------------------------------------------------------------------------------------------ #
           $sql_position="
				SELECT  `id_position_held`,`label` 
                FROM    `position_held_list`
			";
			$this->o_db->query($sql_position);
			$count=$this->o_db->count();
			if ($count>0)
			{
				while ( $o_row_position = $this->o_db->next() )
				{
					$id                 = $o_row_position->id_position_held;
					$value              = $o_row_position->label;
					$a_position[$id]    = $value;
				}
			}
			$count=0;
			# ------------------------------------------------------------------------------------------------ #

			# ------------------------------------------------------------------------------------------------ #
			# Titles array
			# ------------------------------------------------------------------------------------------------ #
			$sql_title="
				SELECT  `id_title_list`, `title`, `abrev_title` 
                FROM    `title_list`
			";
			$this->o_db->query($sql_title);
			$count=$this->o_db->count();
			if ($count>0)
			{
				while ( $o_row_title = $this->o_db->next() )
				{
					$id             = $o_row_title->id_title_list;
					$value          = $o_row_title->title;
					$a_title[$id]   = $value;
				}
			}
			$count=0;
			# ------------------------------------------------------------------------------------------------ #


		$sql_staff="
            
            SELECT  `id_NAB`,
                    `id_title_list`, 
                    `lastname`, 
                    `firstname`, 
                    `email`,
                    `id_position_held` 
            FROM    `NAB`
			WHERE	`id_NAB` = \"" . $this->i_user. "\"
		";
		$this->o_db->query($sql_staff);

		$count=$this->o_db->count();
		if ($count>0)
		{
			$o_row_staff = $this->o_db->next();
			
            $this->s_title			= $a_title[$o_row_staff->id_title_list];
			$this->s_nom			= $o_row_staff->lastname;
			$this->s_prenom			= $o_row_staff->firstname;
			$this->s_position_held	= $a_position[$o_row_staff->id_position_held];
			$this->i_position_held	= $o_row_staff->id_position_held;
			
		}
		else
		{
			$this->s_title			="-";
			$this->s_lastname		="-";
			$this->s_firstname		="-";
			$this->s_position_held	="-";
			$this->i_position_held  =0;
			
		}
		$count=0;
		# ------------------------------------------------------------------------------------------------ #


		# ------------------------------------------------------------------------------------------------ #
		# Profile Access rights
		# ------------------------------------------------------------------------------------------------ #
		$sql_user_rights_profiles ="
            SELECT  `id_user_rights_profiles`,
                    `id_user_profiles`,
                    `add`,
                    `read`,
                    `modify`,
                    `delete`,
                    `id_user_right_types`
            FROM    `user_rights_profiles` 
            WHERE   id_user_profiles` = \"".$this->i_profil."\"
			
		";
		$this->o_db->query($sql_user_rights_profiles);

		$count=$this->o_db->count();
		if ($count>0)
		{
			while ( $o_row_profile = $this->o_db->next() )
			{
				
				$key				= $o_row_profile->id_user_rights_profiles;
				$value_read         = $o_row_profile->read;
                $value_add          = $o_row_profile->add;
				$value_modify		= $o_row_profile->modify;
				$value_delete       = $o_row_profile->delete;

				$this->read_right[$key]         = $value_read;
				$this->add_right[$key]          = $value_add;
				$this->modify_right[$key]       = $value_modify;
				$this->delete_right[$key]       = $value_delete;
			}
		}
		$count=0;
		# ------------------------------------------------------------------------------------------------ #


		# ------------------------------------------------------------------------------------------------ #
		# User Access Rights
		# ------------------------------------------------------------------------------------------------ #
		$sql_user_rights="
			SELECT	`id_user_rights`,
                    `id_users`,
                    `id_user_right_types`,
                    `id_user_profiles`,
                    `read`,
                    `add`, 
                    `modify`, 
                    `delete` 
			FROM    `user_rights`
			WHERE	`id_users`			=\"".$this->i_user."\"
			AND		`id_user_profiles`	=\"".$this->i_profile."\"
		";
		$this->o_db->query($sql_user_rights);

		$count=$this->o_db->count();
		if ($count>0)
		{
			while ( $o_row_user = $this->o_db->next() )
			{
				
				$key				=$o_row_user->id_user_right_types;
				$value_read         =$o_row_user->read;
				$value_add          =$o_row_user->add;
				$value_modify		=$o_row_user->modify;
				$value_delete       =$o_row_user->delete;

				$this->read_right[$key]		 = $value_read;
				$this->add_right[$key]		 = $value_add;
				$this->modify_right[$key]    = $value_modify;
				$this->delete_right[$key]	 = $value_delete;
                
                
			}
		}
		$count=0;
        
        
		# ------------------------------------------------------------------------------------------------ #
		
		$this->o_db->dispose();
        
	}
	# ------------------------------------------------------------------------------------------------ 	#




	function toString()
	{
		#debug function.
	}

}
}
?>