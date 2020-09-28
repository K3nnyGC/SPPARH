<?php


/**
 * 

 Servicio de creación de firmas a instituciones
 Muestra instituciones con falta de firma
 */
class ApiSignSubController extends SubController {
    
    /**
     * 
     */
    public function __construct($fun){
        parent::__construct();
        $this->routerDirect($fun);
        $this->router($fun);
            
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
            default:
                break;
        }
    }

    //Classic CRUD - Custom
    public function create(){
        $pUser = $this->validatePostParameters(['id_user']);
        $id_user = intval($pUser['id_user'])+0;
        $um = new UserManager();
        $user = $um->findById($id_user);
        if($user){
            $info = self::generate($user->id_user);
            $sign = new Sign();
            $sm = new SignManager();
            $sign->id_user = $user->id_user;
            $sign->route_key_public = $info['public'];
            $sign->route_key_private = $info['private'];
            $sign->due_date = $info['due'];
            if($sm->create($sign)){
                MailController::send($user->email,$sign->route_key_private);
                $this->response(201,[ "ok" => true,"response" => "New Sign Created!"]);
            } else {
                $this->response(500,[ "ok" => false,"response" => "Internal server Error"]);
            }
        } else {
            $this->response(404,[ "ok" => false,"response" => "User dont exist"]);
        }
        


    }

    public function update($id){
        $this->response(400,[ "ok" => false,"message" => "Sign Cant Update"]);
    }

    public function show($id){
        $this->response(401,[ "ok" => false,"message" => "Forbidden Action"]);
    }

    public function delete($id){
        $this->response(400,[ "ok" => false,"message" => "Sign Cant de deleted"]);
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

    static private function generate($id_user){
        $gen_date = date(DateTime::ISO8601);
        $mod_date = strtotime($gen_date."+ 90 days");
        $due_date = date(DateTime::ISO8601,$mod_date);
        $id = uniqid();
        $pathPublic = FILE_BASE . 'Assets/files/keys/' . md5($id_user.SECRETE) . '__'. $id . '__' . 'public.key';
        $pathPrivate = FILE_BASE . 'Assets/files/keys/' . md5($id_user.SECRETE) . '__'. $id . '__' . 'private.key';
        $pathSign = FILE_BASE . 'Assets/files/keys/' . md5($id_user.SECRETE) . '__'. $id . '__' . 'sign.pem';
        $privateKey = openssl_pkey_new(array(
                "digest_alg" => "sha512",
                'private_key_bits' => 2048,      // Tamaño de la llave
                'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ));
        //Fecha
        
        if($privateKey == false){
        #if(true){
            #$test = "==HolaOPENGL()";
            #file_put_contents($pathPublic, $test);
            #file_put_contents($pathPrivate, $test);
            $this->response(500,[ "ok" => false,"message" => "Invalid cnf"]);
        } else{
            // Guardar la llave privada en el archivo private.key.
            openssl_pkey_export_to_file($privateKey, $pathPrivate);
            // Generar la llave pública para la llave privada
            $a_key = openssl_pkey_get_details($privateKey);

            // Guardar la llave pública en un archivo public.key.
            file_put_contents($pathPublic, $a_key['key']);
            // Libera la llave privada
            openssl_free_key($privateKey);
        }

        return [
            'public' => $pathPublic,
            'private' => $pathPrivate,
            'sign' => $pathSign,
            'due' => $due_date
        ];
    }

    static public function createKeys($id_user){
        $id_user = intval($id_user)+0;
        $um = new UserManager();
        $user = $um->findById($id_user);
        if($user){
            $info = self::generate($user->id_user);
            $sign = new Sign();
            $sm = new SignManager();
            $sign->id_user = $user->id_user;
            $sign->route_key_public = $info['public'];
            $sign->route_key_private = $info['private'];
            $sign->due_date = $info['due'];
            if($sm->create($sign)){
                MailController::send($user->email,$sign->route_key_public);
                return true;
            }
        }
        return false;
    }

    //verifica si la llave es válida
    static public function verifySign($id_user){
        if (isset($_FILES["sign"])){
            $file = $_FILES["sign"];
            $id = uniqid();
            $ruta_provisional = $file["tmp_name"];
            $carpeta = FILE_BASE . "Assets/files/temporal/" . $id . ".tmp";
            move_uploaded_file($ruta_provisional, $carpeta);

            //Verificamos a la insitucion
            $sm = new SignManager();
            $result = $sm->showAttr(['*'],$sm->id_criteria,"id_user = {$id_user}");
            if(count($result)>0){
                $aliensign = base64_encode(file_get_contents($carpeta));
                unlink($carpeta);
                $ok = false;
                foreach ($result as $key => $value) {
                    $localsign = base64_encode(file_get_contents($value->route_key_public));
                    if($aliensign == $localsign){
                        $ok = true;
                        return $value;
                    }
                }
            }

            self::responseST(400,[ "ok" => false,"message" => "Missing or Wrong Parameter","parameter"=>"Invalid sign"]);
        } else {
            self::responseST(400,[ "ok" => false,"message" => "Missing or Wrong Parameter","parameter"=>"Invalid sign"]);
        }
    }


}

?>