<?php


/**
 * 
 */
class ApiDocumentSubController extends SubController {
    
    /**
     * 
     */
    public function __construct($fun){
        parent::__construct();
        $this->router($fun);
        
        
    }

    protected function router($fun){
        switch ($fun) {
            case 'create':
                $this->response(200,[ "ok" => true,"response" => "TODO: create function"]);
                break;
            case 'edit':
                $this->response(200,[ "ok" => true,"response" => "TODO: edit function"]);
                break;
            case 'delete':
                $this->response(200,[ "ok" => true,"response" => "TODO: delete function"]);
                break;
            default:
                # code...
                break;
        }
        $this->response(200,[ "ok" => false,"response" => "Function dont exist"]);
    }

}

?>