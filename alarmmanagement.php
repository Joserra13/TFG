<?php
    //Here, I am going to enable/disable the alarm

    $state = filter_input(INPUT_GET, 'state', FILTER_SANITIZE_STRING);
    $clear = filter_input(INPUT_GET, 'clear', FILTER_SANITIZE_STRING);
    $det = filter_input(INPUT_GET, 'detections', FILTER_SANITIZE_STRING);
       
    $bbdd = mysqli_connect('localhost', 'admin', 'admin', 'alarmsystem') or 
        die("Error de conexión".mysqli_error($bbdd));

    mysqli_set_charset($bbdd, "utf8");

    //Update the registry
    $date = getdate();
        
    $day = $date[mday];
    $month = $date[mon];
    $year = $date[year];
    $hour = $date[hours];
    $minutes = $date[minutes];
    $seconds = $date[seconds];
    
    if($state == "Turn On")
    {
    	$command = "python encender.py";
   	$output = shell_exec($command);
   	//echo "Estado alarma: ";
   	//echo $output;
        
        //Enable the alarm
        $query = "UPDATE stateAlarm SET state=1 WHERE 1";
        mysqli_query($bbdd, $query) or die("Error de conexión".mysqli_error($bbdd));
        
 
        $query = "INSERT INTO registry (state, method, detections, day, month, year, hour, minutes, seconds) VALUES (1, 'App', 0, $day, $month, $year, $hour, $minutes, $seconds)";
        mysqli_query($bbdd, $query) or die("Error de conexión".mysqli_error($bbdd));
                            
        mysqli_close($bbdd);
        
        header('location: index.php');
    }
    else if($state == "Turn Off")
    {
        $command = "python apagar.py";
   	$output = shell_exec($command);
   	//echo "Estado alarma: ";
   	//echo $output;
        
        //Disable the alarm
        $query = "UPDATE stateAlarm SET state=0 WHERE 1";                 
        mysqli_query($bbdd, $query) or die("Error de conexión".mysqli_error($bbdd));
        

        $query = "INSERT INTO registry (state, method, detections, day, month, year, hour, minutes, seconds) VALUES (0, 'App', $det, $day, $month, $year, $hour, $minutes, $seconds)";
        mysqli_query($bbdd, $query) or die("Error de conexión".mysqli_error($bbdd));
                            
        mysqli_close($bbdd);
        
        header('location: index.php');
    }
    
    if($clear == '1')
    {
        //Delete the register
        $query = "DELETE FROM registry WHERE 1";
        mysqli_query($bbdd, $query) or die("Error de conexión".mysqli_error($bbdd));
        
        mysqli_close($bbdd);
        
        header('location: index.php');
    }
?>
