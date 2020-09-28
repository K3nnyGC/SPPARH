<?php


/**
 * 
 */
class ApiInstituteSubController extends SubController {
    
    /**
     * 
     */
    public function __construct($fun){
        parent::__construct();
        $this->routerDirect($this->getId());
        $this->router($this->getId());
            
    }

    protected function router($id){
        switch (Service::getAction()) {
            case 'POST':
                $this->create();
                break;
            case 'PUT':
                $this->update($id);
                break;
            case 'GET':
                $this->show($id);
                break;
            case 'DELETE':
                $this->delete($id);
                break;
            case 'OPTIONS':
                $this->response(200,[ "ok" => true]);
                break;
            default:
                # code...
                break;
        }
        $this->response(200,[ "ok" => false,"response" => "Function dont exist"]);
    }

    protected function routerDirect($fun){
        switch ($fun) {
            case 'pendings':
                $this->getPendings();
                break;
            case 'approve':
                $this->approve();
                break;
            default:
                break;
        }
    }

    //Classic CRUD - Custom
    public function create(){
        $this->response(400,[ "ok" => false,"message" => "Use User Service"]);
    }

    public function update($id){
        $this->response(400,[ "ok" => false,"message" => "Use User Service"]);
    }

    public function show($id){
        $id_num = intval($id) + 0;
        $im = new InstituteManager();
        $um = new UserManager();
        if($id_num > 0) {
            $users = $um->resultArray($um->exec("
                SELECT * FROM {$um->table} a, {$im->table} b
                WHERE a.id_user = b.id_user AND a.id_user = {$id_num}
            "));
            if(count($users) == 0){
                $this->response(404,[ "ok" => false, "message" => "Institute No found"]);
            } else {
                $user = $this->toAnonimusClass($users[0],['name','lastname','dni','phone','email','id_user','edited_date','created_date','RUC','legal_name','comercial_name','address','status'],['name','lastname','dni','phone','email','id_user','edited_date','created_date','RUC','legal_name','comercial_name','address','status']);
                $this->response(200,[ "ok" => true,"institute" => $user,"message" => "Institute found"]);
            }
        } else {
            $users = $um->resultArray($um->exec("
                SELECT * FROM {$um->table} a, {$im->table} b
                WHERE a.id_user = b.id_user
            "));
            $n_users = [];
            foreach ($users as $key => $value) {
                $user = $this->toAnonimusClass($value,['name','lastname','dni','phone','email','id_user','edited_date','created_date','RUC','legal_name','comercial_name','address','status'],['name','lastname','dni','phone','email','id_user','edited_date','created_date','RUC','legal_name','comercial_name','address','status']);
                $n_users[] = $user;
            }
            $this->response(200,[ "ok" => true,"institutes" => $n_users,"message" => "Institutes found"]);
        }
        
    }

    public function delete($id){
        $id_num = intval($id) + 0;
        $um = new UserManager();
        $user = $um->findById($id_num);
        if (!$user) {
            $this->response(404,[ "ok" => false,"message"=>"User do not Exist"]);
        }
        
        $this->response(401,[ "ok" => false,"message" => "User Can not be deleted"]);
    }

    //Especial Functions
    public function getPendings(){
        $this->setMethod("GET",true);
        $im = new InstituteManager();
        $um = new UserManager();
        $users = $um->resultArray($um->exec("
            SELECT * FROM {$um->table} a, {$im->table} b
            WHERE a.id_user = b.id_user AND b.status = 0
        "));
        $n_users = [];
        foreach ($users as $key => $value) {
            $user = $this->toAnonimusClass($value,['name','lastname','dni','phone','email','id_user','edited_date','created_date','RUC','legal_name','comercial_name','address','status'],['name','lastname','dni','phone','email','id_user','edited_date','created_date','RUC','legal_name','comercial_name','address','status']);
            $n_users[] = $user;
        }
        $this->response(200,[ "ok" => true,"institutes" => $n_users,"message" => "Institutes pendings found"]);
    }

    public function approve(){
        $this->setMethod("PUT",true);
        $id = intval($this->getId(1)) + 0;
        $um = new UserManager();
        $user = $um->findById($id);
        if($user){
            $im = new InstituteManager();
            $institute = $im->findById($id);
            if ($institute) {
                $result = $um->execSP('institute_edit_sp',[$user->id_user,$user->name, $user->lastname, $user->dni,$user->phone,$user->email,$user->password,$institute->RUC,$institute->legal_name,$institute->comercial_name,$institute->address,1]);
                $this->searchError($result);
                if($result){
                    $this->response(200,[ "ok" => true, "message" => "User Updated", "mailStatus" => ApiSignSubController::createKeys($institute->id_user)]);
                } else {
                    $this->response(400,[ "ok" => false,"error" => Service::getErrorMsg()]);
                }
            }
        } else {
            $this->response(404,[ "ok" => false,"message" => "User Do not Exist"]);
        }
    }

    //Private Tools
    private function searchError($response){
        if($response === false){
            $this->response(500,[ "ok" => false,"message" => "Internal server error"/*,"error" => Service::getErrorMsg()*/]);
        }
        if(count($response) == 1 && isset($response[0]->Code)){
            if($response[0]->Code == 1062){
                $this->response(400,["ok" => false, "message" => "Missing or Wrong Parameter", "parameter" => $response[0]->Message]);
            } else {
                $this->response(400,["ok" => false, "message" => "Error", "parameter" => $response[0]->Message]);
            }
            
        }
    }

    private function getId($plus=0){
        $values = Service::parseUri();
        if (isset($values[DEEP_ROOT+1+1+$plus])) {
            return $values[DEEP_ROOT+1+1+$plus];
        } else {
            return "";
        }
    }


}

?>