<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersImpuesto extends CI_Controller {

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
        $this->load->model('impuesto/ModelsImpuesto');
        $this->load->model('busquedas_ajax/ModelsBusqueda');
    }

    function index() {
        $data['listar'] = $this->ModelsImpuesto->obtenerImpuestos();
        $this->load->view('impuesto/lista', $data);
    }

    function registrar() {
        $data['detalles_lista'] = $this->ModelsBusqueda->count_all_table('impuesto');
        $this->load->view('impuesto/registrar', $data);
    }

    public function guardar() {

        $data = array(
            'codigo' => $this->input->post('codigo'),
            'nombre' => $this->input->post('nombre'),
            'valor' => $this->input->post('valor'),
            'fecha_create' => date('Y-m-d H:i:s'),
            'fecha_update' => date('Y-m-d H:i:s'),
            'user_create_id' => $this->input->post('user_create_id'),
        );

        $result = $this->ModelsImpuesto->insertarImpuesto($data);

        if ($result) {

            // modulo de auditoria
            $param = array(
                'tabla' => 'impuesto',
                'codigo' => $this->input->post('valor'),
                'accion' => 'Nuevo Impuesto',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            // fin modulo auditoria
            redirect('impuesto/ControllersImpuesto');
        }
    }

    function editar() {
        $data['id'] = $this->uri->segment(4);
        $data['editar'] = $this->ModelsImpuesto->obtenerImpuesto($data['id']);
        $this->load->view('impuesto/editar', $data);
    }

    function eliminar($id) {
        $result = $this->ModelsImpuesto->eliminarImpuesto($id);
        // modulo de auditoria
            $param = array(
                'tabla' => 'impuesto',
                'codigo' => $id,
                'accion' => 'Eliminar Impuesto',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            // fin modulo auditoria
        if ($result) {

            
            redirect('impuesto/ControllersImpuesto');
        }
    }

    function actualizar() {
        $result = $this->ModelsImpuesto->actualizarImpuesto($this->input->post());

        if ($result) {

            // modulo de auditoria
            $param = array(
                'tabla' => 'impuesto',
                'codigo' => $this->input->post('codigo'),
                'accion' => 'Editar Impuesto',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            // fin modulo auditoria
            redirect('impuesto/ControllersImpuesto');
        }
    }

}
