<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
$l = setlocale(LC_TIME, 'deu', 'de_DE.UTF-8'); 
$zeit = strtotime(Input::get('zeit'));
if(Input::get('monat')) {
    $monat = Input::get('monat');
} else {
    $monat = 0;
}

$kal_datum = strtotime($monat.' month');
$kal_tage_gesamt = date("t", $kal_datum);
$kal_start_timestamp = mktime(0,0,0,date("n",$kal_datum),1,date("Y",$kal_datum));
$kal_start_tag = date("N", $kal_start_timestamp);
$kal_ende_tag = date("N", mktime(0,0,0,date("n",$kal_datum),$kal_tage_gesamt,date("Y",$kal_datum)));

?>
    <div class="mvcheader">
        <span class="backmonth">
            <button style="color: black; text-decoration: none; padding:0px;"><</button>
        </span>
    <span class="mvcmonth" data-month="<?php echo strftime("%b.%y", $kal_datum); ?>">
    <?php echo strftime("%B %Y", $kal_datum); ?></span>
        <span class="forwardmonth">
           <button style="color: black; text-decoration: none;padding:0px;">></button>
        </span>
    </div>
    <div class="mvcweekdays">
        <span class="mvcweekday">
        Mo
        </span>
        <span class="mvcweekday">
        Di
        </span>
        <span class="mvcweekday">
        Mi
        </span>
        <span class="mvcweekday">
        Do
        </span>
        <span class="mvcweekday">
        Fr
        </span>
        <span class="mvcweekday">
        Sa
        </span>
        <span class="mvcweekday">
        So
        </span>
    </div>
<?php
$user = new User();
$userklasse= $user->data()->username;

for($i = 1; $i <= $kal_tage_gesamt+($kal_start_tag-1)+(7-$kal_ende_tag); $i++)
{
$kal_anzeige_akt_tag = $i - $kal_start_tag;
$kal_anzeige_heute_timestamp = strtotime($kal_anzeige_akt_tag." day", $kal_start_timestamp);
$kal_anzeige_heute_tag = date("j", $kal_anzeige_heute_timestamp);

$datum = date('Y-m-d',$kal_anzeige_heute_timestamp);
$response =  $datum;
                $notiz = '<div class="mvcnotizcon">';
                $newdb = DB::getInstance()->query('SELECT * FROM `'.$userklasse.'` WHERE datum  = "'.$datum.'" ');
                foreach($newdb->results() as $new) {
                    
                    switch($new->typ) {
                        case 'h':
                            if (strpos($notiz,'H') === false) {
                               $notiz .= '<span class="mvcnotiztypkleinh">H </span>';
                            }
                            break;
                        case 'a':
                            if (strpos($notiz,'A') === false) {
                               $notiz .= '<span class="mvcnotiztypkleina">A </span>';
                            }
                            break;
                        case 's':
                            if (strpos($notiz,'S') === false) {
                               $notiz .= '<span class="mvcnotiztypkleins">S </span>';
                            }
                            break;
                        
                    }
                    
                }

    $notiz .= '</div>';

    if(date("N",$kal_anzeige_heute_timestamp) == 1) {
        echo " <span >";
    }
    if(date("dmY", strtotime('today')) == date("dmY", $kal_anzeige_heute_timestamp)) {
        echo " <span class=\" mvcday mvctoday  kal_aktueller_tag\" data-timestamp=\"$response\">",$kal_anzeige_heute_tag,"$notiz</span>";
    }
    else if($kal_anzeige_akt_tag >= 0 AND $kal_anzeige_akt_tag < $kal_tage_gesamt) {
        echo " <span class=\" mvcday  kal_standard_tag\" data-timestamp=\"$response\">",$kal_anzeige_heute_tag,"$notiz</span>";
    } else {
        echo " <span class=\" mvcday mvcdaybefore kal_vormonat_tag\" data-timestamp=\"$response\">",$kal_anzeige_heute_tag,"$notiz</span>";
    }
    
    if(date("N",$kal_anzeige_heute_timestamp) == 7) {
        echo " </span>";
    }
        
}
?>