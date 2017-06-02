<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersRespaldos extends CI_Controller {

    public function __construct() {
        @parent::__construct();

// Load form helper library
        $this->load->helper('form');

        $this->load->helper(array('url'));

        $this->load->view('base');

// Load form validation library
        $this->load->library('form_validation');

// Load session library
        $this->load->library('session');

// Load database
        $this->load->model('Login_database');
        $this->load->model('respaldos/ModelsRespaldos');
        $this->load->model('usuarios/Usuarios_model');
        $this->load->model('auditoria/ModelsAuditoria');
        $this->load->model('busquedas_ajax/ModelsBusqueda');
    }

    function index() {
        $data['usuarios'] = $this->Usuarios_model->obtenerUsuarios();
        $data['listar'] = $this->ModelsRespaldos->obtenerRespaldos();
        $this->load->view('respaldos/lista', $data);
    }
    
    
    // Método para anular o activar un usuario
    function respaldar()
    {
		
		// Genaramos el respaldo usando la función system de php
		// Fecha y carpeta de salida
		$fecha_hoy = date("Ymd-His");
		// Datos de conexión
		$db_user = "root";  // Nombre de usuario de mysql
		$db_pass = "";  // Contraseña de usuario de mysql
		$db_name = "GOErp";  // Nombre de la base de datos
		
		// Armamos la ruta de guardado del respaldo
		$ruta = getcwd();  // Obtiene el directorio actual en donde se esta trabajando
		//~ $nombre_archivo1 = $db_name.'-'.$fecha_hoy.'.sql';  
		//~ $ruta1 = $ruta."/respaldos/".$nombre_archivo1;
		//~ $nombre_archivo2 = $db_name.'E-'.$fecha_hoy.'.sql';
		//~ $ruta2 = $ruta."/respaldos/".$nombre_archivo2;
		$nombre_archivo3 = $db_name.'C-'.$fecha_hoy.'.sql';
		$ruta3 = $ruta."/".$nombre_archivo3;
		// Ejecución de los comandos de respaldo
		//~ $dump = "mysqldump --result-file=$ruta1 --default-character-set=utf8 --no-create-info --add-locks=FALSE --disable-keys=FALSE --extended-insert --user=$db_user --password=$db_pass $db_name";
		//~ $a = system($dump); // Backup con sólo data
		//~ $dump2 = "mysqldump --result-file=$ruta2 --default-character-set=utf8 --add-locks=FALSE --disable-keys=FALSE --no-data --user=$db_user --password=$db_pass $db_name";
		//~ $b = system($dump2); // Backup con sólo la estructura
		$dump3 = "C:/xampp/mysql/bin/mysqldump --result-file=$ruta3 --default-character-set=utf8 --add-locks=FALSE --disable-keys=FALSE --user=$db_user --password=$db_pass $db_name";
		$c = system($dump3); // Backup con data y estructura (completo)
		
		// Creamos el archivo .zip para almacenar el respaldo
		$zip = new ZipArchive;
		$zip->open($db_name.'C-'.$fecha_hoy.".zip",ZipArchive::CREATE);
		// Añadimos el archivo respaldado al archivo .zip
		$zip->addFile($nombre_archivo3);
		$zip->close();
		
		// Eliminamos el archivo .sql puesto que ya no es necesario
		unlink($nombre_archivo3);
		
		// Copiamos el archvio .zip a la carpeta de respaldos y luego borramos el archivo original
		copy($db_name.'C-'.$fecha_hoy.".zip", $ruta."/respaldos/".$db_name.'C-'.$fecha_hoy.".zip");
		unlink($db_name.'C-'.$fecha_hoy.".zip");
		
		// Construcción del correlativo para el nuevo registro
		$ultimo_id = $this->ModelsBusqueda->count_all_table('respaldos');
		$correlativo_res = str_pad($ultimo_id+1, 8, "0", STR_PAD_LEFT);  //Rellenamos con ceros a la izquierda
		
		// Obtenemos los datos del usuario
		$usuario = $this->Usuarios_model->obtenerUsuario($this->session->userdata['logged_in']['id']);
		
		// Armamos la data a registrar
		$data_respaldo = array(
			'id' => $ultimo_id + 1,
            'codigo' => $correlativo_res,
            'usuario' => $usuario[0]->username,
            'cedula' => $usuario[0]->cedula,
            'nombre' => $usuario[0]->first_name." ".$usuario[0]->last_name,
            'tipo_usuario' => $usuario[0]->tipo_usuario,
            'archivo' => $db_name.'C-'.$fecha_hoy.".zip",
            'fecha' => date('Y-m-d'),
            'hora' => date('H:i:s'),
//          'puntuacion' => $this->input->post('puntuacion'),
        );
        
		// Registramos los datos del usuario y la hora y fecha del respaldo
		$result = $this->ModelsRespaldos->insertar($data_respaldo);
		
		//~ // Guardado en el módulo de auditoría
		if ($result) {

            $param = array(
                'tabla' => 'respaldos',
                'codigo' => $correlativo_res,
                'accion' => 'Nuevo Respaldo',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
        }
        
        echo $db_name.'C-'.$fecha_hoy.".zip";
        
    }

}
