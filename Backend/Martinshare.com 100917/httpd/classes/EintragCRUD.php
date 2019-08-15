<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

class EintragCRUD {
    public $username,
            $userid,
            $user,
            $dbinstance;
    
    public function __construct() {
        $this->dbinstance = DB::getInstance();
        $this->user = new User();
        $this->username = $this->user->data()->username;
        $this->userid = $this->user->data()->id;
    }
    
    private function escape($string) {
        return htmlentities($string, ENT_QUOTES, 'UTF-8');
    }
    
    public function getHausaufgaben($order, $datum = null) {
        return self::getEintrag('h', $order, $datum = null);
    }
    
    public function getArbeiten($order, $datum = null) {
        return self::getEintrag('a', $order, $datum = null);
    }
    
    public function getSonstiges($order, $datum = null) {
        return self::getEintrag('s', $order, $datum = null );
    }
    
    public function getAlleEintraege($order, $datum = null) {

        if(!$datum) {
            
            $anfrage = $this->dbinstance->query('SELECT id, typ, name, beschreibung, datum, erstelldatum, deleted FROM `eintraege` WHERE `userid` = '.$this->user->data()->id.' ORDER BY datum '.$order.' ');
            return $anfrage->results();
            
        } else if($datum) {
            
            $anfrage = $this->dbinstance->query('SELECT id, typ, name, beschreibung, datum, erstelldatum, deleted FROM `eintraege` WHERE `userid` = '.$this->user->data()->id.' AND datum  = "'.$datum.'"  ORDER BY datum '.$order.' ');
            return $anfrage->results();
         
        }
    }
    
    
    
    //returns results 
    public function getEintrag($typ, $order, $datum = null) {
        return self::getEintragParent($typ, $order, $datum)->results();
    }
    
    //returns query
    public function getEintragQuery($typ, $order, $datum = null) {
        return self::getEintragParent($typ, $order, $datum);
    }
    
    
    private function getEintragParent($typ, $order, $datum = null) {
        if(!$datum) {
            $anfrage = $this->dbinstance->query('SELECT id, typ, name, beschreibung, datum, erstelldatum, deleted FROM `eintraege` WHERE `userid` = '.$this->user->data()->id.' AND typ="'.$typ.'" ORDER BY datum '.$order.' ');
            return $anfrage;
            
        } else if($datum) {
            $anfrage = $this->dbinstance->query('SELECT id, typ, name, beschreibung, datum, erstelldatum, deleted FROM `eintraege` WHERE `userid` = '.$this->user->data()->id.' AND datum  = "'.$datum.'" and typ="'.$typ.'"  ORDER BY id '.$order.' ');
            return $anfrage;
        }
    }
    
    public function newEintrag($typ, $fach, $beschreibung, $datum) {
        
        $insert = $this->dbinstance->insert('`eintraege`', array(
                        'userid'        => $this->user->data()->id,
                        'typ'           => escape($typ),
                        'name'          => escape($fach),
                        'beschreibung'  => escape($beschreibung),
                        'datum'         => escape($datum),
                        'deleted'       => 0
                        ));  
        return $insert;
        
    }
    
    public function updateEintrag($id, $fach, $beschreibung, $datum) {

        $update = $this->dbinstance->updatewhereuserid('`eintraege`', $id, $this->user->data()->id, array(
                        'name'          => escape($fach),
                        'beschreibung'  => escape($beschreibung),
                        'datum'         => escape($datum)
                        )); 
                        
     
        return $update;
    }
    
    public function deleteEintrag($id, $userid) {
        
        if(isDeletable($id, $userid)) {
            
            $update = DB::getInstance()->updatewhereuserid('`eintraege`', $id, $userid, array(
                            'deleted'          => 1
                            )); 
            if(!$update) {
                $app->response()->status(400);
            } else {
                //Push::pushtoandroid("Fach: ".$fach ,$beschreibung, $summary." fällig bis: ", $datum, $id, $key, $user->data()->id, $typ );
                //Push::pushtoios($user->data()->username, $artdespushs.$fach, $datum, $beschreibung);
            }
            
        } else {
            return false;
        }
    }
    
    public function getLastChanged() {
            $anfrage = $this->dbinstance->query('SELECT max(erstelldatum) FROM `eintraege` WHERE `userid` = '.$this->user->data()->id.' ');
            $wut = $anfrage->first();
            return $wut;

    }
    
    function isDeletable($id, $userid) {
        
        $query = DB::getInstance()->query('SELECT deleted FROM `eintraege` WHERE `id` = "'.$id.'" AND `userid` = "'.$userid.'"  ');
        
        if($query->count() < 1) {
            return false;
        } else {
            return $query->first()->deleted == 0 ? true : false;
        }
    }
}
