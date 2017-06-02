<?php
 
###################
#
# Script de Respaldo de Base de Datos
# 26 de Diciembre de 2013
# Ing. Joel Corona
# sisneting.com.mx
#
###################
############## Fecha y carpeta de salida
$fecha_hoy = date("Ymd-His");
$bak_dir = "/home/sitio/backups";
#$md = "mkdir $bak_dir"; $a = system($md);
#$ch = "chmod 777 $bak_dir"; $a = system($ch);
 
############## Base de datos y tablas
$db_user = "db_user";
$db_pass = "db_pass";
$db_name = "db_name";
$db_host = "localhost";
$conexion = mysql_connect($db_host,$db_user,$db_pass);
 
############## Archivos de Salida
$salida_db_sql = $bak_dir.'/'.$db_name.'-'.$fecha_hoy.'.sql'; // Datos
$salida_db_tar = $bak_dir.'/'.$db_name.'-'.$fecha_hoy.'.tar.gz'; //Datos
 
$salida_db_sqlE = $bak_dir.'/'.$db_name.'E-'.$fecha_hoy.'.sql'; //Estructura
$salida_db_tarE = $bak_dir.'/'.$db_name.'E-'.$fecha_hoy.'.tar.gz'; //Estructura
 
############## Mensaje para enviar correo de notificación
$mensaje=date("Y-m-d")." Se hizo el respaldo de las bases de datos: ".$db_name;
$mensaje .= "<br>Archivos:";
$mensaje .= "<br>".$bak_dir.'/'.$db_name.'-'.$fecha_hoy.'.sql';
$mensaje .= "<br>".$bak_dir.'/'.$db_name.'-'.$fecha_hoy.'.tar.gz';
 
$mensaje .= "<br>".$bak_dir.'/'.$db_name.'E-'.$fecha_hoy.'.sql';
$mensaje .= "<br>".$bak_dir.'/'.$db_name.'E-'.$fecha_hoy.'.tar.gz';
 
# Dumps
########## Salida1
$dump = "mysqldump --result-file=$salida_db_sql --default-character-set=utf8 --no-create-info --add-locks=FALSE --disable-keys=FALSE --extended-insert --user=$db_user --password=$db_pass $db_name";
$a = system($dump);
$comprime = "tar -czf $salida_db_tar $salida_db_sql";
$a = system($comprime);
$borra1 = "rm $salida_db_sql";
$a = system($borra1);
 
//Estructura
$dump = "mysqldump --result-file=$salida_db_sqlE --default-character-set=utf8 --add-locks=FALSE --disable-keys=FALSE --no-data --user=$db_user --password=$db_pass $db_name";
$a = system($dump);
$comprime = "tar -czf $salida_db_tarE $salida_db_sqlE";
$a = system($comprime);
$borra1 = "rm $salida_db_sqlE";
$a = system($borra1);
#######################
 
echo "Backup OK";
 
######## Enviando el correo de notificación
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
mail($destinatario="backups-sitio@host.com", $subject="Backup SITIO ".date("Y-m-d"), $mensaje,$headers);
 
######################################################
## Para "Inyectar" el Respaldo a la Base de datos
######################################################
## $inyecta = "mysql --user=$db_user --password=$db_pass --database=$db_name < /home/sitio/backups/db-XXXXXXX.sql ";
## $a = system($inyecta);
######################################################
 
?>
