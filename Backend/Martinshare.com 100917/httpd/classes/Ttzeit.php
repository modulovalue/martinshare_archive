<?php
class Ttzeit {
    
    public static function gettimestamp($beginn, $ende) {
        
        iF((strlen($beginn) == 5) && (strlen($ende) == 5)) {
            
            $beginn .= ':00';
            $ende .= ':00';
            $zeitarray = explode(':', $beginn);
            $timestamp = mktime($zeitarray[0],$zeitarray[1],$zeitarray[2],date('m'),date('d'),date('Y'));
            return $timestamp ;
        } else iF((strlen($beginn) == 8) && (strlen($ende) == 8)) {
            
            $zeitarray = explode(':', $beginn);
            
            $timestamp = mktime($zeitarray[0],$zeitarray[1],$zeitarray[2],date('m'),date('d'),date('Y'));
            return $timestamp;
            
        } else {
            
            return false;
        }
        
    }
    
     public static function gettimeforsql($zeit) {
        
        iF( strlen($zeit) == 5 ) {
            
            $zeit .= ':00';
            $ende .= ':00';
        
            return $zeit;
            
        } else iF(strlen($zeit) == 8 ) {
            
            return $zeit;
            
        } else {
            
            return false;
        }
        
    }
    
}