<?php

  
  

    try{

         $pdo = new PDO("sqlite:C:/xampp/htdocs/Quest_App_PHP/functions/data.db");

         $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);



    } catch(\Throwable $th) {
       // echo "Hata" , $th;
    }




?>