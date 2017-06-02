<?php
echo "</br></br>SCRIPT GENERACION DE CLAVE ACCESO SHA256</br>";
echo "<br>";
echo 'pbkdf2_sha256$12000$'.hash( "sha256", 'VALOR CLAVE');
?>
