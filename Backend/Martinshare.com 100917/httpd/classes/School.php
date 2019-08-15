<?php 
class School {
    private $_db,
            $_data;
    
    public function __construct($schoolid){
        $this->_db = DB::getInstance();

        $this->find($schoolid);
        
    }
    
    public function find($schoolid) {
        if($schoolid) {
            $data = $this->_db->get('schulen', array('id', '=', $schoolid));
            if($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }
 
 
    public function exists() {
        return (!empty($this->_data)) ? true : false;
    }
    
    
    public function data() {
        return $this->_data;
    }

}