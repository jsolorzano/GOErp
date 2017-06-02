<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersClientes extends CI_Controller {

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
        $this->load->model('clientes/ModelsClientes');
        $this->load->model('topologia/ModelsEstado');
        $this->load->model('topologia/ModelsMunicipio');
        $this->load->model('topologia/ModelsParroquia');
        $this->load->model('busquedas_ajax/ModelsBusqueda');
        $this->load->model('tipo_cliente/ModelsTipoCliente');
    }

    function index() {
        $data['listar'] = $this->ModelsClientes->obtenerClientes();
        $this->load->view('clientes/lista', $data);
    }

    function registrar() {
        $data['detalles_lista'] = $this->ModelsBusqueda->count_all_table('cliente');
        $data['list_estado'] = $this->ModelsEstado->obtenerEstados();
        $data['list_tipos_clientes'] = $this->ModelsTipoCliente->obtenerTiposClientes();
        $this->load->view('clientes/registrar', $data);
    }

    public function guardar() {
		$ultimo_id = $this->ModelsBusqueda->count_all_table('cliente');
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
            'tipocliente' => $this->input->post('tipocliente'),
            'tipo_cliente' => $this->input->post('tipo_cliente'),
            'nombre' => $this->input->post('nombre'),
            'estado' => $this->input->post('estado'),
            'municipio' => $this->input->post('municipio'),
            'parroquia' => $this->input->post('parroquia'),
            'direccion' => $this->input->post('direccion'),
            'tlf' => $this->input->post('tlf'),
            'tlf_movil' => $this->input->post('tlf_movil'),
            'fax' => $this->input->post('tlf'),
            'email' => $this->input->post('email'),
            'fechacreacion' => date('Y-m-d H:i:s'),
            'estatus' => $is_active,
//                'puntuacion' => $this->input->post('puntuacion'),
        );

        //~ $datos = array(
            //~ 'idcontacto' => $this->input->post('codigo'),
            //~ 'nacionalidad' => $this->input->post('nacionalidad'),
            //~ 'cedula' => $this->input->post('cedula'),
            //~ 'nombres' => $this->input->post('nombres'),
            //~ 'apellidos' => $this->input->post('apellidos'),
            //~ 'telefono' => $this->input->post('telefono'),
            //~ 'correo' => $this->input->post('correo'),
            //~ 'fechacreacion' => date('Y-m-d H:i:s'),
        //~ );
        $datos = "";
        $result = $this->ModelsClientes->insertar($data, $datos);

        if ($result) {

            $param = array(
                'tabla' => 'cliente',
                'codigo' => $this->input->post('codigo'),
                'accion' => 'Nuevo Cliente',
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
        $data['editar'] = $this->ModelsClientes->obtenerCliente($data['id']);
        $data['list_estado'] = $this->ModelsEstado->obtenerEstados();
        $data['list_municipio'] = $this->ModelsMunicipio->obtenerMunicipios();
        $data['list_parroquia'] = $this->ModelsParroquia->obtenerParroquias();
        $data['list_tipos_clientes'] = $this->ModelsTipoCliente->obtenerTiposClientes();

        $this->load->view('clientes/editar', $data);
    }

    function eliminar($id) {
        $result = $this->ModelsClientes->eliminarCliente($id);
        $param = array(
			'tabla' => 'cliente',
			'codigo' => $id,
			'accion' => 'Eliminar Cliente',
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
            'tipocliente' => $this->input->post('tipocliente'),
            'tipo_cliente' => $this->input->post('tipo_cliente'),
            'nombre' => $this->input->post('nombre'),
            'estado' => $this->input->post('estado'),
            'municipio' => $this->input->post('municipio'),
            'parroquia' => $this->input->post('parroquia'),
            'direccion' => $this->input->post('direccion'),
            'tlf' => $this->input->post('tlf'),
            'tlf_movil' => $this->input->post('tlf_movil'),
            'fax' => $this->input->post('tlf'),
            'email' => $this->input->post('email'),
            //~ 'fechacreacion' => date('Y-m-d H:i:s'),
            'estatus' => $is_active,
//                'puntuacion' => $this->input->post('puntuacion'),
        );
        //~ $datos = array(
            //~ 'idcontacto' => $codigo,
            //~ 'nacionalidad' => $this->input->post('nacionalidad'),
            //~ 'cedula' => $this->input->post('cedula'),
            //~ 'nombres' => $this->input->post('nombres'),
            //~ 'apellidos' => $this->input->post('apellidos'),
            //~ 'telefono' => $this->input->post('telefono'),
            //~ 'correo' => $this->input->post('correo'),
            //~ 'fechacreacion' => date('Y-m-d H:i:s'),
        //~ );
        //~ $result = $this->ModelsClientes->actualizarContacto($datos);
        $result = $this->ModelsClientes->actualizarCliente($data);


        if ($result) {
            $param = array(
                'tabla' => 'cliente',
                'codigo' => $codigo,
                'accion' => 'Editar Cliente',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            //~ redirect('clientes/ControllersClientes');
        }
    }

}
