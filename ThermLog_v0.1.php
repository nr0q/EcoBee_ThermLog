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
    // Version ThermLog.php v0.1
    //
    //
    
    $PAGE_TITLE="EcoBee ThermLog by M. Chambers";
    $THERMOSTAT_ID="318372681494";
    $DATA_DIR="ecobee_data";
?>
<!DOCTYPE html>
<html lang="EN">
<head>
<title><?php echo $PAGE_TITLE; ?></title>
</head>
<body class="mainPage">
<table border=1>
<?php
    $row = 1;
    if (($handle = fopen("$DATA_DIR/report-$THERMOSTAT_ID-2017-12-18.csv", "r")) !== FALSE)
    {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
        {
            $num = count($data);
            if ($row > 5)
            {
                echo "<tr>";
                for ($c=0; $c < $num; $c++)
                {
                    if ($row == 5)
                    {
                        echo "<th>" . $data[$c] . "</th>\n";
                    }
                    else
                    {
                        echo "<td>" . $data[$c] . "</td>\n";
                    }
                }
                echo "</tr>\n";
            }
            $row++;
        }
        fclose($handle);
    }
?>
</body>
</html>
