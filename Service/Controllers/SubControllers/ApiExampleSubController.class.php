<?php


/**
 * 
 */
class ApiExampleSubController extends SubController {
    
    /**
     * 
     */
    public function __construct($fun){
        parent::__construct();
        $this->router($fun);
        
        
    }

    protected function router($fun){
        switch ($fun) {
            default:
                # code...
                break;
        }
        $this->response(200,[ "ok" => false,"response" => "Function dont exist"]);
    }

}

?>