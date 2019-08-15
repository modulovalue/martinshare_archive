    <?php
    setlocale(LC_TIME, "de_DE");
    $kal_datum = time();
    $kal_tage_gesamt = date("t", $kal_datum);
    $kal_start_timestamp = mktime(0,0,0,date("n",$kal_datum),1,date("Y",$kal_datum));
    $kal_start_tag = date("N", $kal_start_timestamp);
    $kal_ende_tag = date("N", mktime(0,0,0,date("n",$kal_datum),$kal_tage_gesamt,date("Y",$kal_datum)));
    ?>
    <table class="kalender" style="text-align: center; ">
    <caption><?php echo utf8_decode(strftime("%B %Y", $kal_datum)); ?></caption>
    <thead>
    <tr>
    <th>Mo</th>
    <th>Di</th>
    <th>Mi</th>
    <th>Do</th>
    <th>Fr</th>
    <th>Sa</th>
    <th>So</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for($i = 1; $i <= $kal_tage_gesamt+($kal_start_tag-1)+(7-$kal_ende_tag); $i++)
    {
    $kal_anzeige_akt_tag = $i - $kal_start_tag;
    $kal_anzeige_heute_timestamp = strtotime($kal_anzeige_akt_tag." day", $kal_start_timestamp);
    $kal_anzeige_heute_tag = date("j", $kal_anzeige_heute_timestamp);
    if(date("N",$kal_anzeige_heute_timestamp) == 1)
    echo " <tr>\n";
    if(date("dmY", $kal_datum) == date("dmY", $kal_anzeige_heute_timestamp))
    echo " <td class=\"kal_aktueller_tag\">",$kal_anzeige_heute_tag,"</td>\n";
    elseif($kal_anzeige_akt_tag >= 0 AND $kal_anzeige_akt_tag < $kal_tage_gesamt)
    echo " <td class=\"kal_standard_tag\">",$kal_anzeige_heute_tag,"</td>\n";
    else
    echo " <td class=\"kal_vormonat_tag\">",$kal_anzeige_heute_tag,"</td>\n";
    if(date("N",$kal_anzeige_heute_timestamp) == 7)
    echo " </tr>\n";
    }
    ?>
    </tbody>
    </table>