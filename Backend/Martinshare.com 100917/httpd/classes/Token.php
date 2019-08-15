<?php
class Token {
    public static function generate() {
        return Session::put(Config::get('session/token_name'), md5(uniqid()));
    }
    
    public static function generatekey() {
        return substr ( sha1(openssl_random_pseudo_bytes(16)) ,0 ,20);
    }
    
    
    
    public static function check($token) {
        $tokenName = Config::get('session/token_name');
        
        if(Session::exists($tokenName) && $token === Session::get($tokenName)) {
            Session::delete($tokenName);
            return true;
        }
        
        return false;
    }
}