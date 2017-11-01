<?php
    
    $log_file = 'download.log';
    $fh = fopen($log_file, 'a') or die('error opening log file');
    fwrite($fh, time() . PHP_EOL);
    $log_array = file('download.log');    

    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=LuminetWorld.apk");
    readfile('LuminetWorld.apk');
    
    

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
    </head>
    <body>
        
    </body>
</html>
