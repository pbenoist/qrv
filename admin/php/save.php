<?php
include('../../config/define.php');

ini_set('display_errors', 'on');


//	C:\wamp\bin\mysql\mysql5.5.20\bin\mysqldump --host=localhost --user=root --password= qrvet --tables em_animal > qrvet.sql


echo ("Votre base est en cours de sauvegarde.......");

echo ('<br>');
echo ('mysqldump --host='.$SRVNAME.' --user='.$USER.' --password='.$PASSWORD.' '.$DBNAME.' --tables em_animal > '.$DBNAME. '.sql');
echo ('<br>');

system('mysqldump --host='. $SRVNAME. ' --user=' .$USER .' --password='. $PASSWORD. ' ' .$DBNAME. ' --tables em_animal > '. $DBNAME. '.sql');

echo ('Vous pouvez récupérer la base par FTP');
echo ('<br>');
?>


