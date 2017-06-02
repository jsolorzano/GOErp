<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersGanancia extends CI_Controller {

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
        $this->load->model('ganancia/ModelsGanancia');
        $this->load->model('auditoria/ModelsAuditoria');
        $this->load->model('busquedas_ajax/ModelsBusqueda');
    }

    function index() {
        $data['listar'] = $this->ModelsGanancia->obtenerGanancias();
        $this->load->view('ganancia/lista', $data);
    }

    function registrar() {
        $data['detalles_lista'] = $this->ModelsBusqueda->count_all_table('ganancia');
        $this->load->view('ganancia/registrar', $data);
    }

    public function guardar() {


        $data = array(
            'codigo' => $this->input->post('codigo'),
            'valor' => $this->input->post('valor'),
            'fecha_create' => date('Y-m-d H:i:s'),
            'fecha_update' => date('Y-m-d H:i:s'),
            'user_create_id' => $this->input->post('user_create_id'),
        );
        $result = $this->ModelsGanancia->insertarGanancia($data);


        if ($result) {

            $param = array(
                'tabla' => 'ganancia',
                'codigo' => $this->input->post('codigo'),
                'accion' => 'Nuevo Porcentaje de Ganancia',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('ganancia/ControllersGanancia');
        }
    }

    function editar() {
        $data['id'] = $this->uri->segment(4);
        $data['editar'] = $this->ModelsGanancia->obtenerGanancia($data['id']);
        $this->load->view('ganancia/editar', $data);
    }

    function eliminar($id) {
        $result = $this->ModelsGanancia->eliminarGanancia($id);
        $param = array(
                'tabla' => 'producto',
                'codigo' => $id,
                'accion' => 'Eliminar Porcentaje de Ganancia',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
        if ($result) {

            
            redirect('ganancia/ControllersGanancia');
        }
    }

    function actualizar() {
        $result = $this->ModelsGanancia->actualizarGanancia($this->input->post());

        if ($result) {

            $param = array(
                'tabla' => 'producto',
                'codigo' => $this->input->post('codigo'),
                'accion' => 'Editar Porcentaje de Ganancia',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('ganancia/ControllersGanancia');
        }
    }

}
