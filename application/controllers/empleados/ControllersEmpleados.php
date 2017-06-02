<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersEmpleados extends CI_Controller {

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
        $this->load->model('empleados/ModelsEmpleados');
        $this->load->model('cargos/ModelsCargos');
        $this->load->model('conceptos/ModelsConceptos');
        $this->load->model('topologia/ModelsEstado');
        $this->load->model('topologia/ModelsMunicipio');
        $this->load->model('topologia/ModelsParroquia');
        $this->load->model('busquedas_ajax/ModelsBusqueda');
        $this->load->model('tipo_cliente/ModelsTipoCliente');
    }

    function index() {
		$data['cargos'] = $this->ModelsCargos->obtenerCargos();
		$data['salarios'] = $this->ModelsConceptos->obtenerConceptos();
        $data['listar'] = $this->ModelsEmpleados->obtenerEmpleados();
        $this->load->view('empleados/lista', $data);
    }

    function registrar() {
        $data['ultimo_id'] = $this->ModelsBusqueda->count_all_table('empleados');
        $data['list_estado'] = $this->ModelsEstado->obtenerEstados();
        $data['cargos'] = $this->ModelsCargos->obtenerCargosActivos();
        $data['salarios'] = $this->ModelsConceptos->obtenerConceptosActivos();
        $this->load->view('empleados/registrar', $data);
    }

    public function guardar() {
		$ultimo_id = $this->ModelsBusqueda->count_all_table('empleados');
        $estatus = $this->input->post('estatus');
        if ((string)$estatus == 'on') {
            $is_active = 1;
        } else {
            $is_active = 0;
        }
        
        $data = array(
			'id' => $ultimo_id + 1,
            'codigo' => $this->input->post('codigo'),
            'cirif' => $this->input->post('cirif'),
            'tipodoc' => $this->input->post('tipodoc'),
            'cargo' => $this->input->post('cargo'),
            'nombre' => $this->input->post('nombre'),
            'estado' => $this->input->post('estado'),
            'municipio' => $this->input->post('municipio'),
            'parroquia' => $this->input->post('parroquia'),
            'direccion' => $this->input->post('direccion'),
            'tlf' => $this->input->post('tlf'),
            'fax' => $this->input->post('fax'),
            'email' => $this->input->post('email'),
            'fechacreacion' => date('Y-m-d H:i:s'),
            'estatus' => $is_active,
            'salario' => $this->input->post('salario'),
            'escala' => $this->input->post('escala'),
            'monto' => $this->input->post('monto'),
        );

        $datos = "";
        $result = $this->ModelsEmpleados->insertar($data, $datos);

        if ($result) {

            $param = array(
                'tabla' => 'empleados',
                'codigo' => $this->input->post('codigo'),
                'accion' => 'Nuevo Emĺeado',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            //~ redirect('clientes/ControllersClientes');
        }
    }

    function editar() {
        $data['id'] = $this->uri->segment(4);
        $data['editar'] = $this->ModelsEmpleados->obtenerEmpleado($data['id']);
        $data['list_estado'] = $this->ModelsEstado->obtenerEstados();
        $data['list_municipio'] = $this->ModelsMunicipio->obtenerMunicipios();
        $data['list_parroquia'] = $this->ModelsParroquia->obtenerParroquias();
        $data['cargos'] = $this->ModelsCargos->obtenerCargos();
        $data['salarios'] = $this->ModelsConceptos->obtenerConceptosActivos();

        $this->load->view('empleados/editar', $data);
    }

    function eliminar($id) {
        $result = $this->ModelsEmpleados->eliminarEmpleado($id);
        $param = array(
			'tabla' => 'empleados',
			'codigo' => $id,
			'accion' => 'Eliminar Empleado',
			'fecha' => date('Y-m-d'),
			'hora' => date("h:i:s a"),
			'usuario' => $this->session->userdata['logged_in']['id'],
		);
		$this->ModelsAuditoria->add($param);
        if ($result) {            
            //~ redirect('clientes/ControllersClientes');
        }
    }

    function actualizar() {
        $estatus = $this->input->post('estatus');
        $codigo = $this->input->post('codigo');
        if ((string)$estatus == 'on') {
            $is_active = 1;
        } else {
            $is_active = 0;
        }

        $data = array(
			'id' => $this->input->post('id'),
            'codigo' => $codigo,
            'cirif' => $this->input->post('cirif'),
            'tipodoc' => $this->input->post('tipodoc'),
            'cargo' => $this->input->post('cargo'),
            'nombre' => $this->input->post('nombre'),
            'estado' => $this->input->post('estado'),
            'municipio' => $this->input->post('municipio'),
            'parroquia' => $this->input->post('parroquia'),
            'direccion' => $this->input->post('direccion'),
            'tlf' => $this->input->post('tlf'),
            'fax' => $this->input->post('fax'),
            'email' => $this->input->post('email'),
            //~ 'fechacreacion' => date('Y-m-d H:i:s'),
            'estatus' => $is_active,
            'salario' => $this->input->post('salario'),
            'escala' => $this->input->post('escala'),
            'monto' => $this->input->post('monto'),
        );
        
        $result = $this->ModelsEmpleados->actualizarEmpleado($data);

        if ($result) {
            $param = array(
                'tabla' => 'empleados',
                'codigo' => $codigo,
                'accion' => 'Editar Empleado',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            //~ redirect('clientes/ControllersClientes');
        }
    }
    
    // Método para consultar los datos del salario por su código
    function consultar() {
        $result = $this->ModelsBusqueda->obtenerRegistro('conceptos', 'codigo', $this->input->post('salario'));
        echo $result->monto;
    }

}
