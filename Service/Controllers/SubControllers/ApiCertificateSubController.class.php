<?php


/**
 * 

Servicio de firmado de documentos

 */
class ApiCertificateSubController extends SubController {
    
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
            case 'create':
                $this->signDocument();
                break;
            case 'update':
                $this->signDocumentPending();
                break;
            default:
                break;
        }
    }

    //Classic CRUD - Custom
    public function create(){
        $this->response(400,[ "ok" => false,"message" => "Forbidden Method"]);
    }

    public function update($id){
        $this->response(400,[ "ok" => false,"message" => "Forbidden Method"]);
    }

    public function show($id){
        $this->response(401,[ "ok" => false,"message" => "Forbidden Method"]);
    }

    public function delete($id){
        $this->response(400,[ "ok" => false,"message" => "Forbidden Method"]);
    }

    //Especial Functions
    public function signDocument(){
        //Definimos metodo de request
        $this->setMethod("POST",true);
        //verificamos parametros por formdata
        $params = $this->validateFormParameters(['id_institute','name','notes','emited_date']);
        //guardamos el archivo documento
        $file = ApiDocumentSubController::createDocumentFromFile();
        //Creamos el objeto documento
        $document = new Document();
        $document->id_institute = intval($params['id_institute']);
        $document->id_student = isset($params['id_student']) ? intval($params['id_student']) : null;
        $document->status = 1;
        $document->name = $params['name'];
        $document->notes = $params['notes'];
        $document->route_file = $file['rute'];
        $document->emited_date = $params['emited_date'];
        $document->id_document_before = isset($params['id_ref']) ? intval($params['id_ref']) : null;
        //verificamos si se mandó la firma y si existe
        $sign = ApiSignSubController::verifySign($document->id_institute);
        //creamos el documento en DB
        $dm = new DocumentManager();
        if($dm->create($document)){
            $document = $dm->findLast();
            //firmamos el documentos
            $pathDocSigned = self::getSignFile($sign->route_key_public,$document->route_file,$document->id_institute);
            //Creamos el certificado
            $certi = new Certificate();
            $certi->sign_id_sign = $sign->id_sign;
            $certi->id_document = $document->id_document;
            $certi->route_signed_file = $pathDocSigned;
            //creamos el certificado en DB
            $cm = new CertificateManager();
            if($cm->create($certi)){
                $this->response(201,[ "ok" => true, "message" => "Document Signed"]);
            } else {
                $this->response(500,[ "ok" => true, "message" => Service::getErrorMsg()]);
            }
        } else {
            $this->response(400,[ "ok" => false,"error" => Service::getErrorMsg()]);
        }
    
    }


    public function signDocumentPending(){
        //Definimos metodo de request
        $this->setMethod("POST",true);
        //verificamos parametros por formdata
        $params = $this->validateFormParameters(['id_document']);

        //buscamos el objeto documento
        $id_document = intval($params['id_document']);
        $dm = new DocumentManager();
        $document = $dm->findById($id_document);

        if($document){
            //verificamos si se mandó la firma y si existe
            $sign = ApiSignSubController::verifySign($document->id_institute);
            //firmamos el documentos
            $pathDocSigned = self::getSignFile($sign->route_key_public,$document->route_file,$document->id_institute);
            //Creamos el certificado
            $certi = new Certificate();
            $certi->sign_id_sign = $sign->id_sign;
            $certi->id_document = $document->id_document;
            $certi->route_signed_file = $pathDocSigned;
            //creamos el certificado en DB
            $cm = new CertificateManager();
            if($cm->create($certi)){
                $document->status = 1;
                if($dm->update($document)){
                    $this->response(201,[ "ok" => true, "message" => "Document Signed"]);
                }
                $this->response(500,[ "ok" => true, "message" => Service::getErrorMsg()]);
            } else {
                $this->response(500,[ "ok" => true, "message" => Service::getErrorMsg()]);
            }

        } else {
            $this->response(400,[ "ok" => false,"message" => "Institute do not exist"]);
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

    static private function getSignFile($pathPubK,$pathDoc,$id_user){
        $pathDocSigned = "";
        $id = uniqid();
        $pathDocSigned = FILE_BASE . 'Assets/files/signedFiles/' . md5($id_user.SECRETE) . '__'. $id . '__' . 'sign.data';

        $data = base64_encode(file_get_contents($pathDoc));
        $dataGZ = gzcompress($data);

        // Obtener la llave pública
        $publicKey = openssl_pkey_get_public(file_get_contents($pathPubK));
        $a_key = openssl_pkey_get_details($publicKey);
        // Encriptar los datos en porciones pequeñas, combinarlos y enviarlo
        $chunkSize = ceil($a_key['bits'] / 8) - 11;
        $output = '';
        while ($dataGZ){
            $chunk = substr($dataGZ, 0, $chunkSize);
            $dataGZ = substr($dataGZ, $chunkSize);
            $encrypted = '';
            if (!openssl_public_encrypt($chunk, $encrypted, $publicKey)){
                return false;
            }
            $output .= $encrypted;
        }
        openssl_free_key($publicKey);

        // Datos encrip
        file_put_contents($pathDocSigned, $output);

        return $pathDocSigned;
    }



}

?>