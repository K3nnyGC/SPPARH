<?php

/**
 * 
 */
class MailController extends Controller {
    
    /**
     * 
     */
    public function __construct(){
        parent::__construct();
        $this->ini();
    }

    public function ini(){
    }

    static public function send($mail,$fileName){
        $to=$mail;
        $asunto = "SPPARH: New Private Key";
        $modo = 0;
        $adjunto = $fileName;
        $archivo_name = "private.key";

        $boundary= md5(time());
        $htmlalt_boundary= $boundary. "_htmlalt";
        $subject=$asunto;

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"". $boundary. "\"\r\n";
        $headers .= "From: ". "SPPARH". "<no-reply@". DOMINIO . EXTENSION . ">\r\n";

        $cuerpo="--". $boundary. "\r\n";
        $cuerpo .= "Content-Type: multipart/alternative; boundary=\"". $htmlalt_boundary. "\"\r\n\r\n";
        $cuerpo .= "--". $htmlalt_boundary. "\r\n";

        $cuerpo .= "Content-Type: text/html; charset=iso-8859-1\r\n";
        $cuerpo .= "Content-Transfer-Encoding: 8bits\r\n\r\n";

        $cuerpo .= "Se ha enviado un correo a peticion suya.";
        $cuerpo .= "\r\n\r\n";
        $cuerpo .= "--". $htmlalt_boundary. "--\r\n\r\n";

        if( strcmp($adjunto, "0") && strcmp($adjunto, "vacio")  ){
            set_time_limit(600);
            $archivo= $adjunto;
        
            $buf_type = "application/octet-stream; name=\"" . $archivo_name . "\"";
 
            //$fp= fopen( $archivo, "r" ); //abrimos archivo
            //$buf= fread( $fp, filesize($archivo) ); //leemos archivo completamente
            //fclose($fp); //cerramos apuntador;

            $buf = file_get_contents($archivo);
            $buf = chunk_split(base64_encode($buf));
 
            $cuerpo .= "--". $boundary. "\r\n";
            $cuerpo .= "Content-Type: ". $buf_type. "\r\n"; //  directo de datos
            $cuerpo .= "Content-Transfer-Encoding: base64\r\n";
            $cuerpo .= "Content-Disposition: attachment; filename=\"". $archivo_name. "\"\r\n\r\n";
            //$cuerpo .= base64_encode($buf). "\r\n\r\n";
            $cuerpo .= $buf . "\r\n\r\n";
        }
        $cuerpo .= "--". $boundary. "--\r\n\r\n"; 
        $mail = @mail($to, $subject, $cuerpo, $headers);

        if ($mail) {
            return true;
        } else {
            return false;
        }
    }
}

