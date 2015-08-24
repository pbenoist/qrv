<?php
include('connect.php');

ini_set('display_errors', 'on');

function makeFileFromquery ($DB, $sql, $filename)
{    
    $file = fopen($filename, 'w');

    $res = $DB->query($sql);
    $cc  = $res->columnCount();

    $lig = 0;
    $fields = array();
    while($row = $res->fetch(PDO::FETCH_ASSOC))
    {
        var_dump($row);
        if ($lig == 0)
        {
            // pour chaque colonne
            $fields = array_keys($row);
            var_dump($fields);
            
            $var = '';
            $i = 0;
            while ($i < $cc)
            {
                $var = $var . $fields[$i];
                $var = $var . ';';
                $i++;
            }
            $var = $var . "\n";
            fwrite($file, $var);
        }

        $var = '';
        $i = 0;
        while ($i < $cc)
        {
            $var = $var . $row[$fields[$i]];
            $var = $var . ';';
            $i++;
        }
        $var = $var . "\n";
        fwrite($file, $var);
        $lig++;
    }

    fclose($file);

}

$sql = "select nuserie, codepin from em_animal ";
makeFileFromquery($DB, $sql,     './temp/qrv.txt');


?>

