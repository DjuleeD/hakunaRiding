<?php

	# ------------------------------------------------------------------------- #
	# connection
	# ------------------------------------------------------------------------- #
	require_once($_SERVER['DOCUMENT_ROOT']."/library/database.inc.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/library/database.php");

    
	# ------------------------------------------------------------------------- #
	# DataBase
	# ------------------------------------------------------------------------- #
	$o_db = new DB();
    
    session_start();
    $prefix = "djuleedev_";
    $uid = uniqid($prefix, true);
    session_id($uid) ;
    
    $RL_s_msgErreurLogin = "";
    
	# ------------------------------------------------------------------------- #
	# getting variables
	# ------------------------------------------------------------------------- #
	$s_login		=	mysql_escape_string($_POST["login"]);
	$s_pwd			=	mysql_escape_string($_POST["pwd"]);
    $s_pwd          =   hash("sha256", $s_pwd);
	
    
    
	$s_action		=	$_POST["Action"];
	

    
    
	# ------------------------------------------------------------------------- #
	# class
	# ------------------------------------------------------------------------- #
	require_once($_SERVER['DOCUMENT_ROOT']."/library/class/Right.class.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/library/class/Error.class.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/library/class/Serialization.class.php");


	# ------------------------------------------------------------------------- #
	# Connection Authorization 
	# ------------------------------------------------------------------------- #
    if ($s_action == "FORGOT")
	{
        header('Location:'.$_SERVER['SERVER_ROOT'].'/forgotPwd.php?uid='.$uid);
	}
    if ($s_action == "CONNECTION")
	{

		
		$sql_search_user="
            SELECT  `id_users`,
                    `login`,
                    `id_user_profiles` 
            FROM    `user_pwds` 
            WHERE   `login`     = \"".$s_login."\"
            AND     `pwd`       = \"".$s_pwd."\"
            AND     `archive`   = 0
               
			";
        
		$o_db->query($sql_search_user);
		$count = $o_db->count();
		
		if ($count > 0)
		{
			
			# ------------------------------------------------------------------ #
			# Access rights 
			# ------------------------------------------------------------------ #
			while ($o_Result_db = $o_db->next())
			{

                $i_user			=	$o_Result_db->id_users;

                // new object from Right class
                $o_right = new Right();

                // setting the values via the setters

                $o_right->set_i_user					($o_Result_db->id_users);
                $o_right->set_i_profile					($o_Result_db->id_user_profiles);
                $o_right->set_s_login					($s_login);

                
                
                $o_right->fillingRights();
   
			}

            
			$sql_delete = "
				DELETE FROM     temp_object
				WHERE           object_date < \"".date('Y-m-d')."\"
			";
            $o_db->query($sql_delete);
			
//			$sql_delete = "
//				#DELETE FROM	 ou_sont_les_personnes
//				#WHERE		connection < \"".date('Y-m-d')."\" >
//			";
			

			# ----------------------------------------------------------------- #
			# Object serialization
			# ----------------------------------------------------------------- #
			$o_serialization = new Serialization($uid);

            echo "<pre>";
            print_r($o_serialization);
            echo "</pre>";
			$a_objet["o_right"] = $o_right;
			$o_serialization->mySerialize($a_object);
			unset($a_object);


			# ----------------------------------------------------------------- #
			# Redirection to menu
			# ----------------------------------------------------------------- #
			header('Location:'.$_SERVER['SERVER_ROOT'].'/menu.php?uid='.$uid.'&user_id='.$i_user.'');
            
            
		}
		else
		{
			
            $sql_profile = "
				SELECT	id_user_profiles, label
				FROM	user_profiles
				WHERE	id_user_profiles = 1
			";
			$o_db->query($sql_profile);
            $count = $o_db->count();
		

			if($count >0)
				$profile = 1;
		
            $sql_admin= "
                    SELECT	*
                    FROM	NAB_works_council
                    WHERE	admin_login				 = \"".$s_login."\"
                    AND		admin_pwd				 = \"".$s_pwd."\"
                ";

			$o_db->query($sql_admin);
            $count = $o_db->count();
		
			if($count_admin>0)
			{

                $o_Result_db = $o_db->next();
				
                $o_right = new Right();
				$o_right->set_i_user(1);
				$o_right->set_i_profile($profile);
				$o_right->set_s_login($s_login);

				$o_right->fillingRights();
                
				# ------------------------------------------------------------- #
				# Object serialization 
				# ------------------------------------------------------------- #
				$uid = session_id();
                $o_serialization= new Serialization($uid);
				$a_object["o_right"]=$o_right;
				$o_serialization->mySerialize($a_object);
				unset($a_object);
				# ------------------------------------------------------------- #
				# Redirection to Menu
				# ------------------------------------------------------------- #
				header('Location:'.$_SERVER['SERVER_ROOT'].'/menu.php?uid='.$uid.'&user_id=admin');
			}
			else
				$RL_s_msgErreurLogin	=	"IDENTIFICATION INCORRECTE<br />";

}
	} # Connection Authorisation 


echo '<!DOCTYPE HTML>';
echo '<head>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

echo "<title>Hakuna Riding - Votre CE online </title>";
echo "<link rel=\"icon\" type=\"image/png\" href=\"favicon.png\" />";
// ajouter la librairie jquery
// echo "<script language=\"javascript\" src=\"library/\"></script>";

echo "<style type=\"text/css\">";

echo "</style>";


echo "</head>";

//require ('./library/fct_fenetreMobile.js.php');
//require ('./maintenance/js/MLFct_js.inc.php');

$b_bloquage=0;
echo "<body>";

    echo "<div class=\"header_logo\">";
    
    echo '</div>';
    
    if ($RL_s_msgErreurLogin != ""){
        echo '<p>'.$RL_s_msgErreurLogin .'</p>';
    }
            
    echo "<div id =\"login_main_content\">";
        echo "<form name=\"authentification\" 	method=\"post\" 	action=\"login.php\">";
			echo "<input name=\"Action\" 	type=\"\">";

            echo '<table>';
                echo "<tr>";

                    echo "<td  align='left' nowrap='nowrap' class=''>Utilisateur : </td>";
                    echo "<td  align='left' nowrap='nowrap' ><input  type='text' autocomplete='off' name='login' id='logIn_INPUT_USER' border='2px' border-color = '#cccccc'  size='25'  /></td>";

                echo "</tr>";

                echo "<tr>";
                    echo "<td align='left' nowrap='nowrap' class='mentionlink' >Mot de passe :</td>";
                    echo " <td align='left' nowrap='nowrap' ><input  type='password'   size='25' autocomplete='off' name='pwd' id='logIn_INPUT_PWD' maxlength='30' /></td>";
             
                echo " </tr>";
             
                echo "<tr>";
                    echo "<td align='right' >";

                    if($b_bloquage == 0)
                    {
                        echo "<input value=\"CONNECTION\" type=\"submit\" onclick=\"this.form.Action.value=this.value;\" border=\"0\" >";
                        echo "<input value=\"FORGOT\" type=\"submit\" onclick=\"this.form.Action.value=this.value;\" border=\"0\" >";
                    }

                    echo "</td>";
                echo "</tr>";
                
          echo "</table>";
          echo "</form>";
    echo '</div>';
    
    echo "<div class =\"footer\">";
    
    echo '</div>';
    
    
echo "</body>";

echo "</html>";







