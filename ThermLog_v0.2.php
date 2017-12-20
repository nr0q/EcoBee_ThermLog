<?php
    //
    // ThermLog
    // By Matthew A. Chambers
    // Copyright 2017 Matthew A. Chambers
    // License under GNU Public License V3.0 https://www.gnu.org/licenses/gpl-3.0.txt
    //
    // This program takes data from the EcoBee 3 smart thermostat saved in CSV format
    // and generates an information webpage displaying data in human readable form
    //
    // Version ThermLog.php v0.2
    //
    //
    
    $PAGE_TITLE="EcoBee ThermLog v0.2 by M. Chambers";
    $THERMOSTAT_ID="318372681494";
    $DATA_DIR="ecobee_data";
    $SelectedDate=null;

?>
<!DOCTYPE html>
<html lang="EN">
<head>
<title><?php echo $PAGE_TITLE; ?></title>
</head>
<body class="mainPage">

<?php
    
    $KoolControlsFolder = "../../KoolPHPSuite/KoolControls";//Relative path to "KoolPHPSuite/KoolControls" folder

    require $KoolControlsFolder."/KoolCalendar/koolcalendar.php";
    require $KoolControlsFolder."/KoolAjax/koolajax.php";
    $koolajax->scriptFolder = $KoolControlsFolder."/KoolAjax";
    
    $cal = new KoolCalendar("cal"); //Create calendar object
    $cal->id="viewCal";
    $cal->scriptFolder = $KoolControlsFolder."/KoolCalendar";//Set scriptFolder
    $cal->styleFolder="default";

    // Set Date Range
    $cal->RangeMinDate=mktime(0,0,0,8,4,2017);
    
    //Enabled client mode
    $cal->ClientMode = true;
    
    //Enable Ajax
    $cal->AjaxEnabled = true;
    $cal->AjaxLoadingImage = $KoolControlsFolder."/KoolAjax/loading/2.gif";

    //Init calendar before render
    $cal->Init();
    
    ?>

<form id="form1" method="post">
<?php
    echo "<div style='padding-top:10px;padding-bottom:10px;width:650px";
    if ($style_select=='black'){echo "background:#333'";}
    echo $cal->Render();
?>

<div style="padding-top:5px;">
<input type="submit" value="Submit" />
</div>
<div style="padding-top:5px;">
<?php
    if($cal->SelectedDates!=null)
    {
        echo "<b>Selected Date:</b>";
        echo " [".date("Y-m-d",$cal->SelectedDates[0])."] ";
        $selectedDate=date("Y-m-d",$cal->SelectedDates[0]);
    }
    ?>
</div>
</div>
</form>
<?php

    $row = 1;
    if ($selectedDate==null)
    {
        echo "Choose date above.";
    }
    else
    {
    if (($handle = fopen("$DATA_DIR/report-$THERMOSTAT_ID-$selectedDate.csv", "r")) !== FALSE)
    {
        echo "\n\n<table border=1>\n";
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
        {
            $num = count($data);
            if ($row > 5)
            {
                echo "<tr>\n";
                for ($c=0; $c < $num; $c++)
                {
                    if ($c !== 15)
                    {
                        if ($row !== 6)
                        {
                            if ($data[$c] == "heatOff")
                            {
                                echo "<td>Heat Off</td>\n";
                            }
                            elseif ($data[$c] == "heatStage1On")
                            {
                                echo "<td>Heat On</td>\n";
                            }
                            elseif ($data[$c] == "coolOff")
                            {
                                echo "<td>Cool Off</td>\n";
                            }
                            elseif ($data[$c] == "coolStage1On")
                            {
                                echo "<td>Cool On</td>\n";
                            }
                            elseif ($data[$c] == "heat")
                            {
                                echo "<td>Heat</td>\n";
                            }
                            elseif ($data[$c] == "cool")
                            {
                                echo "<td>Cool</td>\n";
                            }
                            elseif ($data[$c] == "auto")
                            {
                                echo "<td>Auto</td>\n";
                            }
                            elseif ($data[$c] == "hold")
                            {
                                echo "<td>Hold</td>\n";
                            }
                            elseif ($c == 18)
                            {
                                if ($data[$c] == "0")
                                {
                                    echo "<td>No</td>\n";
                                }
                                elseif ($data[$c] == "1")
                                {
                                    echo "<td>Yes</td>\n";
                                }
                                else
                                {
                                    echo "<td>" . $data[$c] . "</td>\n";
                                }
                            }
                            elseif ($c == 20)
                            {
                                if ($data[$c] == "0")
                                {
                                    echo "<td>No</td>\n";
                                }
                                elseif ($data[$c] == "1")
                                {
                                    echo "<td>Yes</td>\n";
                                }
                                else
                                {
                                    echo "<td>" . $data[$c] . "</td>\n";
                                }
                            }
                            elseif ($c > 20)
                            {
                                //SKIP
                            }
                            else
                            {
                                echo "<td>" . $data[$c] . "</td>\n";
                            }
                        }
                        elseif ($row == 6)
                        {
                            if ($data[$c] == "Cool Stage 1 (sec)")
                            {
                                echo "<th>Cool (sec)</th>\n";
                            }
                            elseif ($data[$c] == "Heat Stage 1 (sec)")
                            {
                                echo "<th>Heat (sec)</th>\n";
                            }
                            elseif ($data[$c] == "Bathroom2")
                            {
                                echo "<th>Bathroom Motion</th>\n";
                            }
                            elseif ($data[$c] == "Thermostat Temperature (F)")
                            {
                                echo "<th>Living Room Temperature (F)</th>\n";
                            }
                            elseif ($data[$c] == "Thermostat Humidity (%RH)")
                            {
                                echo "<th>Living Room Humidity (%RH)</th>\n";
                            }
                            elseif ($data[$c] == "Thermostat Motion")
                            {
                                echo "<th>Living Room Motion</th>\n";
                            }
                            else
                            {
                                echo "<th>" . $data[$c] . "</th>\n";
                            }
                        }
                    }
                }
                echo "</tr>\n";
            }
            $row++;
        }
        fclose($handle);
        echo "</table>\n";
    }
    else
    {
        echo "Error: Data not available";
    }
    }
?>
</body>
</html>
