<!DOCTYPE html>

<?php
    header("Refresh: 5");

    $bbdd = mysqli_connect('localhost', 'root', '', 'alarmsystem') or
        die("Error de conexión".mysqli_error($bbdd));

    mysqli_set_charset($bbdd, "utf8");

    //read the value on the DDBB
    $query = "SELECT state FROM stateAlarm";
    $result = mysqli_query($bbdd, $query);
    $row = mysqli_fetch_array($result, MYSQLI_NUM);

    $row[0] = intval($row[0]);
    
    $command = "python state.py";
    $output = shell_exec($command);
    //echo "Estado alarma: ";
    //echo $output;

    $output[0] = intval($output[0]);

    $command = "python stated.py";
    $det = shell_exec($command);
    $det = intval($det);

    if($output[0] != $row[0])
    {
	//The state of the alarm was changed by the RFID
	
	//Change the state of the alarm for the real one
	$query = "UPDATE stateAlarm SET state=$output[0] WHERE 1";
        mysqli_query($bbdd, $query) or die("Error de conexión".mysqli_error($bbdd));
        
	//Write the change on the register
	$date = getdate();
        
        $day = $date[mday];
        $month = $date[mon];
        $year = $date[year];
        $hour = $date[hours];
        $minutes = $date[minutes];
        $seconds = $date[seconds];

	$query = "SELECT detections FROM detable";
    	$result2 = mysqli_query($bbdd, $query);
    	$row2 = mysqli_fetch_array($result2, MYSQLI_NUM);
	$row2[0] = intval($row2[0]);
        
        $query = "INSERT INTO registry (state, method, detections, day, month, year, hour, minutes, seconds) VALUES ($output[0], 'RFID', $row2[0], $day, $month, $year, $hour, $minutes, $seconds)";
        mysqli_query($bbdd, $query) or die("Error de conexión".mysqli_error($bbdd));
         
    }

?>

<html>
    <head>
        <title>Web App IoT</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />   
        <meta charset="UTF-8">                               <!--Meta points UTF-8 codification-->
        <meta name="description" content="This is a final project of the degree of Engineering of Telecommunications">                 <!--Description of the app-->
        <meta name="keywords" content="Technology">          <!--Keywords-->
        <meta name="viewport" content="width=device-width, initial-scale=0.6" /> <!--Makes the app mobile friendly-->

        <link rel="stylesheet" href="css/style.css" type="text/css" charset="utf-8" />
    </head>
    <body>
        
        <div id="title" style="text-align: center; font-size: 40px;"><h1>Web App IoT</h1></div>
        
        <div id="state">
            <h3>State of the alarm: </h3>
        
            <?php

                if($output[0] == '0')
                {
                    //Red Button
                    echo "<div style='width: 100px; background-color: red; margin-left: 50px ;margin-bottom: 25px; text-align: center'>DISABLED</div>";
                    $button = "Turn On";
                }
                else if($output[0] == '1')
                {
                    //Green Button
                    echo "<div style='width: 100px; background-color: green; margin-left: 50px ;margin-bottom: 25px; text-align: center'>ENABLED</div>";
                    $button = "Turn Off";
                }
            ?>

            <!-- Enable/Disable button -->        
            <form action="alarmmanagement.php" method="get">
                <input hidden="hidden" name="state" value="<?=$button?>">
                <input hidden="hidden" name="detections" value="<?=$det?>">
                <input type="submit" id="Boton" value="<?=$button?>"/>                                         
            </form>
        
        </div>
        
        <div id="detections">
            <h3 style="float: right;">Number of detections: <div id="number"><?php
					echo $det;
					$query = "UPDATE detable SET detections=$det WHERE 1";
					mysqli_query($bbdd, $query) or die("Error de conexión".mysqli_error($bbdd));
                                        ?></div>
            </h3>
        </div>

        <br><br><br><br><br><br><br><br><br><br>

        <div id="registro" style="overflow-x:auto; padding-left: 10px; padding-right: 10px;">
            <table>
                <caption style="background-color: #00394d; color: white;">Latest movements</caption>
                <tr><th>State</th><th>Method</th><th>Detections</th><th>Day</th><th>Month</th><th>Year</th><th>Hour</th><th>Minutes</th><th>Seconds</th></tr>

                <?php 
                    //Shows the 10 latest updates of the state of the alarm
                    $query = "SELECT * FROM registry ORDER BY year DESC, month DESC, day DESC, hour DESC, minutes DESC, seconds DESC";
                    $result = mysqli_query($bbdd, $query);

                    $i = 0;
                    while(++$i < 10 && $row = mysqli_fetch_array($result, MYSQLI_NUM))
                    {      
                        print "<tr><td>{$row[0]}</td><td>{$row[1]}</td><td>{$row[2]}</td><td>{$row[3]}</td><td>{$row[4]}</td><td>{$row[5]}</td><td>{$row[6]}</td><td>{$row[7]}</td><td>{$row[8]}</td></tr>";
                    }  
                ?>
            </table>
        
        
            <form action="alarmmanagement.php" method="get">
                <input hidden="hidden" name="clear" value="1">
                <input type="submit" id="Boton" value="Delete All"/>                                         
            </form>
            
        </div>
    </body>
</html>
