<?php

function icsToArray($paramUrl) {
    $icsFile = file_get_contents($paramUrl);

    $icsData = explode("BEGIN:", $icsFile);

    foreach($icsData as $key => $value) {
        $icsDatesMeta[$key] = explode("\n", $value);
    }

    foreach($icsDatesMeta as $key => $value) {
        foreach($value as $subKey => $subValue) {
            if ($subValue != "") {
                if ($key != 0 && $subKey == 0) {
                    $icsDates[$key]["BEGIN"] = $subValue;
                } else {
                    $subValueArr = explode(":", $subValue, 2);
                    $icsDates[$key][$subValueArr[0]] = $subValueArr[1];
                }
            }
        }
    }

    return $icsDates;
}

echo"<pre>";
print_r(icsToArray("http://www.schulferien.org/iCal/Feiertage/icals/Feiertage_Baden_Wuerttemberg_2015.ics"));

echo"</pre>";
?>