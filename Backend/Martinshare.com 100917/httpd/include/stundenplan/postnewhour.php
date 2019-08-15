<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';

if(Input::exists()) {
    
                 
        $user = new User();
        $userklasse = $user->data()->username;

        $neuetttabelle = DB::getInstance()->query('CREATE TABLE IF NOT EXISTS `TT'.$userklasse.'` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `wochentag` int(11) NOT NULL,
          `stunde` int(11) NOT NULL,
          `fach` text NOT NULL,
          `beginn` time NOT NULL,
          `ende` time NOT NULL,
          `aenderungsdatum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;');
                
    
        $validate = new Validate();
        $validation = $validate-> check($_POST, array( 
            'stunde' => array(
                'required' => true
            ),
            'fach' => array(
                'required' => true,
                'max'      => 10
            ),
            'beginn' => array(
                'required' => true
            ),
            'ende' => array(
                'required' => true
            ),
            'wochentag' => array(
                'required' => true
            )
            )); 
            
        $user = new User();
        $userklasse = $user->data()->username;
        
        if($validation->passed()) {
            echo Input::get('wochentag');
                $insert = DB::getInstance()->insert('`TT'.$userklasse.'`', array(
                        'wochentag'         => Input::get('wochentag'),
                        'stunde'            => Input::get('stunde'),
                        'fach'              => Input::get('fach'),
                        'beginn'             => Input::get('beginn'),
                        'ende'              => Input::get('ende')
                        ));  
                /*if($insert) {
                    Session::flash('eintragerfolgreich', 'Dein Eintrag wurde gespeichert!');
                    
                } else {
                    Session::flash('erroreintrag', 'Fehler! bitte kontaktiere den Support');
                }*/
        } else {
            foreach($validation->errors() as $error) {
                Session::flash('errorneuestunde', $error.'<br>');
            }
        }
}