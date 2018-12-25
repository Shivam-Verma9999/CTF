<?php

function create_connection( string $database ) {
   $conn = mysqli_connect( "localhost", "shivam", "Spiderman80", $database);
   if($conn->connect_errno)
   {
      echo "Failed to create connection";
      return;
   }
   return($conn);
}
?>