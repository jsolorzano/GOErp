<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersTipoCliente extends CI_Controller {

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
        $this->load->model('auditoria/ModelsAuditoria');
        $this->load->model('tipo_cliente/ModelsTipoCliente');
        $this->load->model('busquedas_ajax/ModelsBusqueda');  // Librería de consultas extra
    }

    function index() {
        $data['listar'] = $this->ModelsTipoCliente->obtenerTiposClientes();
        $this->load->view('tipo_cliente/lista', $data);
    }

    function registrar() {
        $data['ultimo_id'] = $this->ModelsBusqueda->count_all_table('tipo_cliente');
        $this->load->view('tipo_cliente/registrar', $data);
    }

    public function guardar() {

        $result = $this->ModelsTipoCliente->insertarTipoCliente($this->input->post());

        if ($result) {

            $param = array(
                'tabla' => 'tipo_cliente',
                'codigo' => $this->input->post('cod_tipo'),
                'accion' => 'Nuevo Tipo de cliente',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('tipo_cliente/ControllersTipoCliente');
        }
    }

    function editar() {
        $data['id'] = $this->uri->segment(4);
        $data['editar'] = $this->ModelsTipoCliente->obtenerTipoCliente($data['id']);
        $this->load->view('tipo_cliente/editar', $data);
    }

    function eliminar($id) {
        $result = $this->ModelsTipoCliente->eliminarTipoCliente($id);
        $param = array(
                'tabla' => 'tipo_cliente',
                'codigo' => $id,
                'accion' => 'Eliminar Tipo de cliente',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
        if ($result) {

            
            redirect('tipo_cliente/ControllersTipoCliente');
        }
    }

    function actualizar() {
        $result = $this->ModelsTipoCliente->actualizarTipoCliente($this->input->post());

        if ($result) {

            $param = array(
                'tabla' => 'tipo_cliente',
                'codigo' => $this->input->post('cod_tipo'),
                'accion' => 'Editar Tipo de cliente',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('tipo_cliente/ControllersTipoCliente');
        }
    }

    function consultar() {
        $result = $this->ModelsBusqueda->existe_registro('tipo_cliente', 'tipo_cliente', $this->input->post('tipo_cliente'));
    }

}
