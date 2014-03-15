<?php
class Error
{
    
    #Constructor
    function Error()
    {
        
        /*
        FONCTIONS DE RECUPERATION DE PARAMETRES :
        Ces fonctions sont préférables au passage d'argument classique,
car elles permettent de faire facilement
        de la surcharge de fonction et évitent également les problèmes
liés aux passages d'arguments optionnels
        (qu'il faut obligatoirement faire figurer à la fin de la liste
d'argument)
        
        func_get_arg(x) : Récupère l'argument de rang 'x' passé à la
fonction
        func_num_args() : Récupère le nombre d'arguments passés à la
fonction
        */
 
        if(!func_num_args())
        {
            
        }
        else $this->showError("cl. error -> mess. 1","Instanc. error :
Nombre d'argument invalide");
    }
    
    function showError($page,$message)
    {
        /*
        overloading possible cases :
        
        arg 0 : string file
        arg 1 : string message
        */
        
        echo "/////////////////ERROR HANDLER/////////////////<br>";
        echo "Page : ".$page."<br>";
        if(mysqli_connect_errno())
        {
            echo "***Error num. ".mysqli_connect_errno()."***<br>";
            echo mysqli_connect_error()."<br>";
        }
        
        echo
$message."<br>//////////////////////////////////////////////////////////
///////<br><br>";
    }
    
    function toString()
    {
        
    }
}
?>