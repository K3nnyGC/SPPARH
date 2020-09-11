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


    public function create(){
        //$this->setMethod("POST",true);
        //var_dump($_POST);
        $params = $this->validateFormParameters(['id_institute','name','notes','emited_date']);
        $file = $this->createFile();
        $document = new Document();
        $document->id_institute = $params['id_institute'];
        $document->id_student = isset($params['id_student']) ? intval($params['id_student']) : null;
        $document->status = 0;
        $document->name = $params['name'];
        $document->notes = $params['notes'];
        $document->route_file = $file['rute'];
        $document->emited_date = $params['emited_date'];
        $document->id_document_before = isset($params['id_ref']) ? intval($params['id_ref']) : null;
        $dm = new DocumentManager();
        if($dm->create($document)){
            $document = $dm->findLast();
            $obj = $this->toAnonimusClass($document,['id_document','name','status'],['id','name','status']);
            $this->response(201,[ "ok" => true,"document" => $obj]);
        } else {
            $this->response(400,[ "ok" => false,"error" => Service::getErrorMsg()]);
        }
    }

    public function update($id){
        $params = $this->validatePostParameters(['id_institute','name','notes','emited_date','status']);
        $id_num = intval($id) + 0;
        $dm = new DocumentManager();
        $document = $dm->findById($id_num);
        if (!$document) {
            $this->response(404,[ "ok" => false]);
        }
        $document->id_institute = $params['id_institute'];
        $document->id_student = isset($params['id_student']) ? $params['id_student'] : $document->id_student;
        $document->status = $params['status'];
        $document->name = $params['name'];
        $document->notes = $params['notes'];
        $document->emited_date = $params['emited_date'];
        $document->edited_date = date(DateTime::ISO8601);
        
        if($dm->update($document)){
            $obj = $this->toAnonimusClass($document,['id_document','name','status'],['id','name','status']);
            $this->response(200,[ "ok" => true,"document" => $obj]);
        } else {
            $this->response(400,[ "ok" => false,"error" => Service::getErrorMsg()]);
        }
    }

    public function show($id){
        $id_num = intval($id) + 0;
        $dm = new DocumentManager();
        if($id_num > 0) {
            $documents = $dm->showAttr(["*"],$dm->id_criteria,"id_document = {$id_num} AND status < 2");
            if(count($documents) == 0){
                $this->response(404,[ "ok" => false]);
            } else {
                $this->response(200,[ "ok" => true,"document" => $documents[0]]);
            }
        } else {
            $documents = $dm->showAttr(["*"],$dm->id_criteria,"status < 2");
            $this->response(200,[ "ok" => true,"documents" => $documents]);
        }
        
    }

    public function delete($id){
        $id_num = intval($id) + 0;
        $dm = new DocumentManager();
        $document = $dm->findById($id_num);
        if (!$document) {
            $this->response(404,[ "ok" => false]);
        }
        if ($document->status == 3) {
            $this->response(404,[ "ok" => false]);
        }
        $document->status = 3;
        
        if($dm->update($document)){
            $this->response(200,[ "ok" => true]);
        } else {
            $this->response(400,[ "ok" => false,"error" => Service::getErrorMsg()]);
        }
    }


    private function createFile(){
        if (isset($_FILES["file"])){
            $file = $_FILES["file"];
            $nombre = $file["name"];

            $parts = explode(" ", $nombre);
            $nombre = implode("_", $parts);

            $tipo = $file["type"];
            $ruta_provisional = $file["tmp_name"];
            $size = $file["size"];

            $carpeta = FILE_BASE . "Assets/files/documents/";

            $hoy = getdate();
            $fecha = "" . $hoy['year'] . "-" . $hoy['mon'] . "-" . $hoy['mday'];
            $hora = "".$hoy['hours'] . ":" . $hoy['minutes'] . ":" . $hoy['seconds'];
            $src = $carpeta.md5($nombre.$fecha.$hora).$nombre.".tmp";
            move_uploaded_file($ruta_provisional, $src);
            return ["name"=>$nombre, "rute"=>$src];
        } else {
            $this->response(400,[ "ok" => false,"error" => "Missing document"]);
            return ["name"=>"", "rute"=>""];
        }
    }

}

?>