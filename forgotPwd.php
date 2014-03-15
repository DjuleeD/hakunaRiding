<?php
	# ------------------------------------------------------------------------- #
	# récupération variables
	# ------------------------------------------------------------------------- #
	$get_login		=	$_GET["login"];
	$s_login		=	$_POST["login"];
	$s_nom			=	$_POST["nom"];
	$s_action		=	$_POST["Action"];
	$s_errorMsg = '';



    
	# ------------------------------------------------------------------------- #
	# connection
	# ------------------------------------------------------------------------- #
//	include("library/param.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/library/database.inc.php");

	# ------------------------------------------------------------------------- #
	# DB
	# ------------------------------------------------------------------------- #
	$o_db	=	new DB();


	# ------------------------------------------------------------------------- #
	# Connection - Authorization
	# ------------------------------------------------------------------------- #

	if ($s_action == "SEND")
	{

		# - Who is he/she?
        
		$sql_search_user="
            
            SELECT  `NAB`.`id_NAB`, 
                    `NAB`.`email`
            FROM    `user_pwds` 
            JOIN    `NAB`
            ON      `user_pwds`.`id_users`  = `NAB`.`id_NAB`
			WHERE	`user_pwds`.`login`     = \"".$s_login."\"
			AND		`NAB`.`lastname`        = \"".strtoupper($s_nom)."\"
			";
		$o_db->query($sql_search_user);
		$count=$o_db->count();

		if ($count > 0)
		{
			$o_row = $o_db->next();
			if ($o_row->email!="")
			{
				$string = "abcdefghijklmnopqrstuvwxyzABCDEFPQRSTUVWXY23456789"; //possible chars
				srand((double)microtime()*1000000); 
				for($i=0; $i<8; $i++)
				{ 
                    //8-char-password
					$s_new_pwd .= $string[rand()%strlen($string)]; 
				}
				

							$sSubject = "Hakuna Riding : Mot de passe";

							$sMessage = "Bonjour,\r\n";
							$sMessage .= "\r\n";
							$sMessage .= "Voici vos identifiants pour accéder au site : ";
							$sMessage .= "\r\n";
							$sMessage .= "Login : ".$s_login."\r\n";
							$sMessage .= "Mot de passe : ".$s_new_pwd."\r\n";
							$sMessage .= "\r\n";
							$sMessage .= "\r\n";

							$sMessage .= "Cordialement,";
							$sMessage .= "\r\n";
							$sMessage .= "L'équipe de Hakuna Riding,";
							$sMessage .= "\r\n";
							$today_date=date('d-M-Y');
							$sMessage .= "\r\n";
							$sMessage .= "Lyon, le ".$today_date."";
							$sMessage .= "\r\n";
							$sMessage .= "\r\n";


							$sMessage .= "-----";
							$sMessage .= "\r\n";
							$sMessage .= "Hakuna Riding";
							$sMessage .= "\r\n";
							$sMessage .= "1800 Avenue André Lasquin";
							$sMessage .= "\r\n";
							$sMessage .= "74700 Sallanches";
							$sMessage .= "\r\n";
							$sMessage .= "Tél : 04 50 93 74 07";
							$sMessage .= "\r\n";
							$sMessage .= "\r\n";
							$sMessage .= "eMail : info@djuleedevelopment.com";
							$sMessage .= "\r\n";


							$sHeader = "From: info@gestibase.com\r\n";

				$sTO = $o_row->email;

				mail($sTO, $sSuject, $sMessage, $sHeader);

                $s_new_pwd   = hash("sha256", $s_new_pwd);
				$num_users	 = $o_row->id_NAB;
                
				$sql_pwd="
                    
                    UPDATE	`user_pwds` 
					SET		`pwd`           = \"".$s_new_pwd."\" 
					WHERE	`login`         = \"".$s_login."\"
					AND		`id_users`      = \"".$num_users."\"
					";
				$o_db->query($sql_pwd);

				### back to identification page
				?>
				<script language="JavaScript">
					alert('Un eMail avec votre nouveau mot de passe vous sera envoyé à l\'adresse \'<?php echo $sTO; ?>\' .');
					document.location.replace('login.php');
				</script>
				<?php
			}
			else
			{
				$s_errorMsg="::: EMAIL INCORRECT :::<br />";
			}
		}
		else
		{
			$s_errorMsg="::: LOGIN ou NOM INCORRECT :::<br />";
		}
	}

?>

<!DOCTYPE HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<link rel="stylesheet" type="text/css" href="library/styles/styles_Interface.css">-->
<title>CE Hakuna Riding : mot de passe oublié </title>
</head>
<body>
<?php
	//include("library/inc/alertImfr_ifConfirmImfr/alertImfr.inc.php");	
	echo "<table width=\"100%\" height=\"100%\">";
	echo "<tr>";
		echo "<td height=\"50\">&nbsp;</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td align=\"center\" valign=\"middle\">";

		# --------------------------------------------------------------------- #
		# 
		# --------------------------------------------------------------------- #
		echo "<div align=\"center\" style=\"width:550px\">";
		// $RL_s_idLayer_titrePlateauParDefaut = "Recherche mot de passe";
		// include("library/inc/plateaux/gris_mono/pltGrisMono_HAUT.inc.php");

		echo "<table width=\"100%\">";
		echo "<tr>";
			echo "<td>";
				// echo "<img src=\"img/logoMFR.png\" />";
			echo "</td>";
			
			echo "<td>";
				# ------------------------------------------------------------- #
				# 
				# ------------------------------------------------------------- #
			echo "<div align=\"center\" style=\"width:350px\">";
//			 $RL_s_titreEncart	= "Mot de passe &eacute;gar&eacute;";
//			include("library/inc/plateaux/encartBleuFin/encartBleuFin_HAUT.inc.php");

			echo "<form name	=\"forgotPWD\" 	method=\"post\" 	action=\"forgotPwd.php\">";
			echo "<input name	=\"Action\" 	type=\"hidden\">";

			echo "<table width=\"100%\"  border=\"0\" cellspacing=\"10\" cellpadding=\"0\">";
			echo "<tr>";
				echo "<td align=\"left\" valign=\"top\">&nbsp;</td>";
				echo "<td>";
					echo "<table width=\"100%\" border=\"0\" cellspacing=\"10\" cellpadding=\"0\">";

			if( $s_errorMsg != '' )
			{
					
					echo "<tr>";
						echo "<td colspan=\"2\" align=\"center\" valign=\"middle\" class=\"INTERF_msgAlert_ErreurLogin\">".$s_errorMsg."</td>";
					echo "</tr>";			
			
			}
					
					echo "<tr>";
						echo "<td colspan=\"2\" valign=\"middle\" class=\"\">Le syst&egrave;me va g&eacute;n&eacute;rer un mot de passe et vous le communiquer par eMail, &agrave; l'adresse que vous avez sp&eacute;cifi&eacute;e lors de votre inscription.</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td width=\"50%\" align=\"right\" valign=\"middle\" class=\"\">Nom de famille</td>";
						echo "<td width=\"50%\" align=\"left\" 	valign=\"middle\">";
							echo "<input type=\"text\" name=\"nom\" class=\"\">";
						echo "</td>";
					echo "<tr>";
						echo "<td width=\"50%\" align=\"right\" valign=\"middle\" class=\"\">Login utilisateur</td>";
						echo "<td width=\"50%\" align=\"left\" 	valign=\"middle\">";
							echo "<input type=\"text\" name=\"login\" class=\"\">";
						echo "</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td colspan=\"2\" align=\"center\">";
						echo "<input 
							value=\"SEND\" 
							type=\"submit\" 
							onclick=\"this.form.Action.value=this.value\" 
							border=\"0\"
							>";
						echo "</td>";
					echo "</tr>";
					echo "</table>";
				echo "</td>";
			echo "</tr>";
			echo "</table>";
			echo "</form>";

			# ----------------------------------------------------------------- #
			# 
			# ----------------------------------------------------------------- #
			echo "</div>";
//			include("library/inc/plateaux/encartBleuFin/encartBleuFin_BAS.inc.php");
			echo "</td>";
		echo "</tr>";
		echo "</table>";
		# --------------------------------------------------------------------- #
		# Fermeture du plateau
		# --------------------------------------------------------------------- #
		echo "</div>";
//		include("./library/inc/plateaux/gris_mono/pltGrisMono_BAS.inc.php");
	
		echo "</td>";
	echo "</tr>";
	echo "</table>";
echo "</body>";
echo "</html>";

?>


