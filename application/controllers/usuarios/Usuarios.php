<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller {

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
        $this->load->model('usuarios/Usuarios_model');
        $this->load->model('auditoria/ModelsAuditoria');
    }

    function index() {
        $data['listar'] = $this->Usuarios_model->obtenerUsuarios();
        $this->load->view('usuarios/lista', $data);
    }

// Show registration page
    function registrar() {
        $this->load->view('usuarios/registrar');
    }

    public function guardar() {
        $data = array(
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'password' => 'pbkdf2_sha256$12000$' . hash("sha256", $this->input->post('password')),
            'cedula' => $this->input->post('cedula'),
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'tipo_usuario' => $this->input->post('tipo_usuario'),
            'cargo' => $this->input->post('cargo'),
            'telefono' => $this->input->post('telefono'),
            'estatus' => $this->input->post('estatus'),
            'change_id' => 0,
            'fecha_create' => date('Y-m-d H:i:s'),
            'fecha_update' => date('Y-m-d H:i:s'),
            'user_create_id' => $this->input->post('user_create_id'),
        );
        $result = $this->Usuarios_model->insertar($data);

        if ($result) {

            $param = array(
                'tabla' => 'usuarios',
                'codigo' => $this->input->post('cedula'),
                'accion' => 'Nuevo Usuario',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            //~ redirect('usuarios/Usuarios');
        }
    }

    function editar() {

        $data['id'] = $this->uri->segment(4);

        $data['password'] = sha1(('password'));
        $data['editar'] = $this->Usuarios_model->obtenerUsuario($data['id']);


        $this->load->view('usuarios/editar', $data);
    }

    function eliminar() {
        $id = $this->uri->segment(4);
        $this->Usuarios_model->eliminarUsuario($id);
        $param = array(
            'tabla' => 'usuarios',
            'codigo' => $id,
            'accion' => 'Eliminar Usuario',
            'fecha' => date('Y-m-d'),
            'hora' => date("h:i:s a"),
            'usuario' => $this->session->userdata['logged_in']['id'],
        );
        $this->ModelsAuditoria->add($param);
        redirect('usuarios/Usuarios/');
    }
    
    
    // Método para anular o activar un usuario
    function activar_desactivar($id)
    {
		//~ echo "Id: ".$id;
		
		$accion = $this->input->post('accion');
		$estatus = true;
		
		if ($accion == 'desactivar'){
			$estatus = false;
		}
		
		// Armamos la data a actualizar
        $data_usuario = array(
			'id' => $id,
			'estatus' => $estatus,
			'fecha_update' => date('Y-m-d'),
        );
        
		// Actualizamos el usuario con los datos armados
		$result = $this->Usuarios_model->actualizarUsuario($id, $data_usuario);
		
		// Guardado en el módulo de auditoría
		//~ if ($result) {
//~ 
            //~ $param = array(
                //~ 'tabla' => 'facturas',
                //~ 'codigo' => $cod,
                //~ 'accion' => 'Anular Factura',
                //~ 'fecha' => date('Y-m-d'),
                //~ 'hora' => date("h:i:s a"),
                //~ 'usuario' => $this->session->userdata['logged_in']['id'],
            //~ );
            //~ $this->ModelsAuditoria->add($param);
        //~ }
        
    }
    

    function actualizar() {
        $data = array(
            'id' => $this->input->post('id'),
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'password' => 'pbkdf2_sha256$12000$' . hash("sha256", $this->input->post('password')),
            'cedula' => $this->input->post('cedula'),
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'tipo_usuario' => $this->input->post('tipo_usuario'),
            'cargo' => $this->input->post('cargo'),
            'telefono' => $this->input->post('telefono'),
            'estatus' => $this->input->post('estatus'),
            'change_id' => 1,
            'fecha_update' => date('Y-m-d H:i:s'),
        );

        $result = $this->Usuarios_model->actualizarUsuario($data['id'], $data);

        if ($result) {
            $param = array(
                'tabla' => 'usuarios',
                'codigo' => $this->input->post('id'),
                'accion' => 'Editar Usuario',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('usuarios/usuarios');
        }
    }

}
