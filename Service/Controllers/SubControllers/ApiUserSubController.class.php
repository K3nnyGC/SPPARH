<?php


/**
 * 
 */
class ApiUserSubController extends SubController {
    
    /**
     * 
     */
    public function __construct($fun){
        parent::__construct();
        $this->routerDirect($fun);
        $this->router($fun);
            
    }

    protected function router($fun){
        switch (Service::getAction()) {
            case 'POST':
                $this->create();
                break;
            case 'PUT':
                $this->update($fun);
                break;
            case 'GET':
                $this->show($fun);
                break;
            case 'DELETE':
                $this->delete($fun);
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
            case 'login':
                $this->login();
                break;
            case 'institutes':
                new ApiInstituteSubController($fun);
                break;
            default:
                break;
        }
    }

    //Classic CRUD - Custom
    public function create(){
        //$this->setMethod("POST",true);
        //var_dump($_POST);
        $pUser = $this->validatePostParameters(['name','lastname','dni','phone','email','password','type']);

        $um = new UserManager();
        if ($pUser['type'] == 1) {
            $pInstitute = $this->validatePostParameters(['RUC','legal_name','comercial_name','address']);
            $result = $um->execSP('institute_create_sp',[$pUser['name'], $pUser['lastname'], $pUser['dni'],$pUser['phone'],$pUser['email'],md5($pUser['password']),$pInstitute['RUC'],$pInstitute['legal_name'],$pInstitute['comercial_name'],$pInstitute['address']]);
        } else {
            $pStudent = $this->validatePostParameters(['birthdate']);
            $result = $um->execSP('student_create_sp',[$pUser['name'], $pUser['lastname'], $pUser['dni'],$pUser['phone'],$pUser['email'],md5($pUser['password']),$pStudent['birthdate']]);
        }
        $this->searchError($result);


        if($result){
            $user = $this->toAnonimusClass($result[0],['name','lastname','dni','phone','email','id_user','edited_date','created_date'],['name','lastname','dni','phone','email','id_user','edited_date','created_date']);
            $this->response(201,[ "ok" => true,"user" => $user, "message" => "User Created", "tkn" => Auth::generateToken($user)]);
        } else {
            $this->response(400,[ "ok" => false,"message" => Service::getErrorMsg()]);
        }
    }

    public function update($id){
        $id = intval($id) + 0;
        $pUser = $this->validatePostParameters(['name','lastname','dni','phone','email','type']);
        $um = new UserManager();
        $user = $um->findById($id);
        if($user){
           $password = isset($pUser['password']) ? md5($pUser['password']) : $user->password;   
        } else {
            $this->response(404,[ "ok" => false,"message" => "User Do not Exist"]);
        }

        if ($pUser['type'] == 1) {
            $pInstitute = $this->validatePostParameters(['RUC','legal_name','comercial_name','address','status']);
            $result = $um->execSP('institute_edit_sp',[$id,$pUser['name'], $pUser['lastname'], $pUser['dni'],$pUser['phone'],$pUser['email'],$password,$pInstitute['RUC'],$pInstitute['legal_name'],$pInstitute['comercial_name'],$pInstitute['address'],$pInstitute['status']]);
        } else {
            $pStudent = $this->validatePostParameters(['birthdate']);
            $result = $um->execSP('student_edit_sp',[$id,$pUser['name'], $pUser['lastname'], $pUser['dni'],$pUser['phone'],$pUser['email'],$password,$pStudent['birthdate']]);
        }
        $this->searchError($result);

        if($result){
            $user = $this->toAnonimusClass($result[0],['name','lastname','dni','phone','email','id_user','edited_date','created_date'],['name','lastname','dni','phone','email','id_user','edited_date','created_date']);
            $this->response(200,[ "ok" => true,"user" => $user, "message" => "User Updated", "tkn" => Auth::generateToken($user)]);
        } else {
            $this->response(400,[ "ok" => false,"error" => Service::getErrorMsg()]);
        }
    }

    public function show($id){
        $id_num = intval($id) + 0;
        $um = new UserManager();
        if($id_num > 0) {
            $users = $um->showAttr(["*"],$um->id_criteria,"id_user = {$id_num}");
            if(count($users) == 0){
                $this->response(404,[ "ok" => false, "message" => "User No found"]);
            } else {
                $user = $this->toAnonimusClass($users[0],['name','lastname','dni','phone','email','id_user','edited_date','created_date'],['name','lastname','dni','phone','email','id_user','edited_date','created_date']);
                $this->response(200,[ "ok" => true,"user" => $user,"message" => "User found"]);
            }
        } else {
            $users = $um->show();
            $n_users = [];
            foreach ($users as $key => $value) {
                $user = $this->toAnonimusClass($value,['name','lastname','dni','phone','email','id_user','edited_date','created_date'],['name','lastname','dni','phone','email','id_user','edited_date','created_date']);
                $n_users[] = $user;
            }
            $this->response(200,[ "ok" => true,"users" => $n_users,"message" => "Users found"]);
        }
        
    }

    public function delete($id){
        $id_num = intval($id) + 0;
        $um = new UserManager();
        $user = $um->findById($id_num);
        if (!$user) {
            $this->response(404,[ "ok" => false,"message"=>"User do not Exist"]);
        }
        
        $this->response(400,[ "ok" => false,"message" => "User Can not be deleted"]);
    }

    //Especial Functions
    public function login(){
        $this->setMethod("POST",true);
        $pUser = $this->validatePostParameters(['email','password']);

        $um = new UserManager();
        $email = md5($pUser['email']);
        $password = md5($pUser['password']);

        $result = $um->showAttr(["*"],$um->id_criteria,"md5(email) = '{$email}'");
        if(count($result) == 1){
            $user = $result[0];
            if($user->password == $password){
                $user = $this->toAnonimusClass($user,['name','lastname','dni','phone','email','id_user','edited_date','created_date','id_role'],['name','lastname','dni','phone','email','id_user','edited_date','created_date','id_role']);
                $this->response(200,[ "ok" => true,"user" => $user, "message" => "Authorizated", "tkn" => Auth::generateToken($user)]);
            } else {
                $this->response(400,[ "ok" => false, "message" => "Wrong Password"]);
            }
        } else {
            $this->response(404,[ "ok" => false, "message" => "User dont Exist"]);
        }
    }

    //Private Tools
    private function searchError($response){
        if($response === false){
            $this->response(500,[ "ok" => false,"message" => "Internal server error"]);
        }
        if(count($response) == 1 && isset($response[0]->Code)){
            if($response[0]->Code == 1062){
                $this->response(400,["ok" => false, "message" => "Missing or Wrong Parameter", "parameter" => $response[0]->Message]);
            } else {
                $this->response(400,["ok" => false, "message" => "Error", "parameter" => $response[0]->Message]);
            }
            
        }
    }


}

?>