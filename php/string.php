<?php	
   function generate_password()
    {
     $password = array();
     for ($i=1; $i<=5; $i++) { $password[] = chr(rand(65, 90)); } 
     for ($i=1; $i<=3; $i++) { $password[] = chr(rand(97, 122)); } 
     for ($i=1; $i<=5; $i++) { $password[] = chr(rand(48, 57)); }

     shuffle($password);

     return implode("", $password);
    }
?>