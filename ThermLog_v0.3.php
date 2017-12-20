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
    // Version ThermLog.php v0.3
    //
    //
    
    $PAGE_TITLE="EcoBee ThermLog v0.3 by M. Chambers";
    $THERMOSTAT_ID="318372681494";
    $DATA_DIR="ecobee_data";
    $SelectedDate=null;

?>
<!DOCTYPE html>
<html lang="EN">
<head>
<title><?php echo $PAGE_TITLE; ?></title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
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
    //if ($style_select=='black'){echo "background:#333'";}
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
        $SelectedDate=date("Y-m-d",$cal->SelectedDates[0]);
    }
    ?>
</div>
</div>
</form>
<?php

    $DayTime = array();
    $CoolSet = array();
    $HeatSet = array();
    $CurTemp = array();
    $CurHumd = array();
    $OutTemp = array();
    $WindSpd = array();
    $LvRmTemp = array();
    $BathTemp = array();
    
    $row = 1;
    if ($SelectedDate==null)
    {
        echo "Choose date above.";
    }
    else
    {
    if (($handle = fopen("$DATA_DIR/report-$THERMOSTAT_ID-$SelectedDate.csv", "r")) !== FALSE)
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
                            if (substr($data[1],3,2) == "00")
                            {
                                if ($c == 1)
                                {
                                    $DayTime[] = $data[$c];
                                }
                                elseif ($c == 6)
                                {
                                    $CoolSet[] = $data[$c];
                                }
                                elseif ($c == 7)
                                {
                                    $HeatSet[] = $data[$c];
                                }
                                elseif ($c == 8)
                                {
                                    $CurTemp[] = $data[$c];
                                }
                                elseif ($c == 9)
                                {
                                    $CurHumd[] = $data[$c];
                                }
                                elseif ($c == 10)
                                {
                                    $OutTemp[] = $data[$c];
                                }
                                elseif ($c == 11)
                                {
                                    $WindSpd[] = $data[$c];
                                }
                                elseif ($c == 16)
                                {
                                    $LvRmTemp[] = $data[$c];
                                }
                                elseif ($c == 19)
                                {
                                    $BathTemp[] = $data[$c];
                                }
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
        
        require $KoolControlsFolder."/KoolChart/koolchart.php";
        
        $chart = new KoolChart("chart");
        $chart->scriptFolder=$KoolControlsFolder."/KoolChart";
        $chart->Width = 1000;
        $chart->Height = 640;
        
        $chart->Title->Text = "HVAC Performance";
        $chart->PlotArea->XAxis->Title = "Time";
        $chart->PlotArea->XAxis->LabelsAppearance->RotationAngle = 45;
        $chart->PlotArea->XAxis->Set($DayTime);
        $chart->PlotArea->YAxis->Title = "Temp";
        $chart->PlotArea->YAxis->MaxValue = 100;
        $chart->PlotArea->YAxis->MinValue = 0;
        $chart->PlotArea->YAxis->MajorStep = 25;
        $chart->PlotArea->YAxis->MinorStep = 5;
        $chart->PlotArea->YAxis->LabelsAppearance->DataFormatString = "{0}";
        
        $series = new LineSeries();
        $series->Name = "Cool Set Temp";
        $series->Appearance->BackgroundColor="#006699";
        $series->TooltipsAppearance->DataFormatString = "{0}&deg;F";
        $series->LabelsAppearance->DataFormatString = "{0}&deg;F";
        $series->LabelsAppearance->Position = "Above";
        $series->LabelsAppearance->Visible = FALSE;
        $series->MarkersAppearance->MarkersType = "Circle";
        $series->ArrayData($CoolSet);
        $chart->PlotArea->AddSeries($series);
        
        $series = new LineSeries();
        $series->Name = "Heat Set Temp";
        $series->Appearance->BackgroundColor="#990000";
        $series->TooltipsAppearance->DataFormatString = "{0}&deg;F";
        $series->LabelsAppearance->DataFormatString = "{0}&deg;F";
        $series->LabelsAppearance->Position = "Above";
        $series->LabelsAppearance->Visible = FALSE;
        $series->ArrayData($HeatSet);
        $chart->PlotArea->AddSeries($series);
        
        $series = new LineSeries();
        $series->Name = "Current Temp";
        $series->Appearance->BackgroundColor="#999999";
        $series->TooltipsAppearance->DataFormatString = "{0}&deg;F";
        $series->LabelsAppearance->DataFormatString = "{0}&deg;F";
        $series->LabelsAppearance->Position = "Above";
        $series->LabelsAppearance->Visible = FALSE;
        $series->ArrayData($CurTemp);
        $chart->PlotArea->AddSeries($series);
        
        $series = new LineSeries();
        $series->Name = "Outdoor Temp";
        $series->Appearance->BackgroundColor="#009900";
        $series->TooltipsAppearance->DataFormatString = "{0}&deg;F";
        $series->LabelsAppearance->DataFormatString = "{0}&deg;F";
        $series->LabelsAppearance->Position = "Above";
        $series->LabelsAppearance->Visible = FALSE;
        $series->ArrayData($OutTemp);
        $chart->PlotArea->AddSeries($series);
        
        $series = new LineSeries();
        $series->Name = "Living Room";
        $series->Appearance->BackgroundColor="#444444";
        $series->TooltipsAppearance->DataFormatString = "{0}&deg;F";
        $series->LabelsAppearance->DataFormatString = "{0}&deg;F";
        $series->LabelsAppearance->Position = "Above";
        $series->LabelsAppearance->Visible = FALSE;
        $series->ArrayData($LvRmTemp);
        $chart->PlotArea->AddSeries($series);
        
        $series = new LineSeries();
        $series->Name = "Bath Room";
        $series->Appearance->BackgroundColor="#444444";
        $series->TooltipsAppearance->DataFormatString = "{0}&deg;F";
        $series->LabelsAppearance->DataFormatString = "{0}&deg;F";
        $series->LabelsAppearance->Position = "Above";
        $series->LabelsAppearance->Visible = FALSE;
        $series->ArrayData($BathTemp);
        $chart->PlotArea->AddSeries($series);
        
        ?>

<form id="form2" method="post">
<?php echo $chart->Render();?>
</form>
<?php
    }
    
    else
    {
        echo "Error: Data not available";
    }
    }
    /*
    echo count($CoolSet);
    for ($i=0; $i < count($CoolSet); $i++)
    {
        echo $CoolSet[$i];
    }
    */
?>
</body>
</html>
