<?php

if (! $mode)
    $mode = $_GET['mode'];
if ($mode == 'forbidden')
   {
    $msg="Vous n'avez pas pas les autorisations nécessaires pour accéder à cette partie.";
//   $msg='/imfr_public/img/interface/defaut/interdit_forbidden.png';
    }
elseif($mode=='timeout')
    {   
        $msg="Vous avez dépassé le temps d'inactivité autorisé. Veuillez vous reconnecter."; 
//		 $msg='/imfr_public/img/interface/defaut/interdit_timeout.png'; 
    }
elseif($mode=='connect')
    {   
        $msg="Vous n'êtes pas connecté. Veuillez vous authentifier.";  
//		$msg='/imfr_public/img/interface/defaut/interdit_connect.png';
    }
    
    echo '<!DOCTYPE HTML>';
    echo '<head>';
    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

    echo "<title>Hakuna Riding - Accès refusé </title>";
    echo "<link rel=\"icon\" type=\"image/png\" href=\"favicon.png\" />";
    // ajouter la librairie jquery
    // echo "<script language=\"javascript\" src=\"library/\"></script>";

    echo "<style type=\"text/css\">";

    echo "</style>";


    echo "</head>";
    
    echo "<body >";
        echo "<div style='background: none repeat scroll 0 0 #D3E6EA;border-radius: 15px 15px 15px 15px;    box-shadow: 0 0 10px;    margin: 30px auto;    width: 80%;min-width:520px;'>";
                echo "<div style='  padding: 10px 0;'>";
                    echo "<center>";
                        echo "<div style='width:510px' >";
                            echo '<p> '.$msg.'</p>';
                        echo "</div >";

                        echo "<div style='clear:both'> </div>";
                        echo "<div style='font-weight:bold;color:#666666;font-size:12px; font-family:trebuchet ms;display:block;margin-top:10px;'><a href='https://".$_SERVER['SERVER_NAME']."/index.php'>Identification</a>";
                        echo "</div>";
                echo "</div >";
                echo "</center>";

        echo "</div>";

    echo "</body>";
    echo "</html>";  
    
    
	?>


    