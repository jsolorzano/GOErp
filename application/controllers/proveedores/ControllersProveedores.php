<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersProveedores extends CI_Controller {

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
        $this->load->model('proveedores/ModelsProveedores');
        $this->load->model('tipo_proveedor/ModelsTipoProveedor');
        $this->load->model('topologia/ModelsEstado');
        $this->load->model('topologia/ModelsMunicipio');
        $this->load->model('topologia/ModelsParroquia');
        $this->load->model('busquedas_ajax/ModelsBusqueda');
    }

    function index() {
        $data['listar'] = $this->ModelsProveedores->obtenerProveedores();
        $this->load->view('proveedores/lista', $data);
    }

    function registrar() {
        $data['detalles_lista'] = $this->ModelsBusqueda->count_all_table('proveedor');
        $data['list_tipo'] = $this->ModelsTipoProveedor->obtenerTiposProveedores();
        $data['list_estado'] = $this->ModelsEstado->obtenerEstados();
        $this->load->view('proveedores/registrar', $data);
    }

    public function guardar() {
		$ultimo_id = $this->ModelsBusqueda->count_all_table('proveedor');

        $estatus = $this->input->post('estatus');
        if ((string)$estatus == 'on') {
            $is_active = 1;
        } else {
            $is_active = 0;
        }
        
        //~ $rif = $this->input->post('rif');
        //~ if ((string)$rif == 'on') {
            //~ $rif = 1;
        //~ } else {
            //~ $rif = 0;
        //~ }
        //~ $acta_constitutiva = $this->input->post('acta_constitutiva');
        //~ if ((string)$acta_constitutiva == 'on') {
            //~ $acta = 1;
        //~ } else {
            //~ $acta = 0;
        //~ }
        //~ $cedula_represen = $this->input->post('cedula_represen');
        //~ if ((string)$cedula_represen == 'on') {
            //~ $representante = 1;
        //~ } else {
            //~ $representante = 0;
        //~ }
        //~ $autorizacion_represen = $this->input->post('autorizacion_represen');
        //~ if ((string)$autorizacion_represen == 'on') {
            //~ $autorizacion = 1;
        //~ } else {
            //~ $autorizacion = 0;
        //~ }
        //~ $solvencia_laboral = $this->input->post('solvencia_laboral');
        //~ if ((string)$solvencia_laboral == 'on') {
            //~ $solvencia = 1;
        //~ } else {
            //~ $solvencia = 0;
        //~ }
        //~ $snc = $this->input->post('snc');
        //~ if ((string)$snc == 'on') {
            //~ $snc = 1;
        //~ } else {
            //~ $snc = 0;
        //~ }
        //~ $rcn = $this->input->post('rcn');
        //~ if ((string)$rcn == 'on') {
            //~ $rcn = 1;
        //~ } else {
            //~ $rcn = 0;
        //~ }
        //~ $solvencia_ince = $this->input->post('solvencia_ince');
        //~ if ((string)$solvencia_ince == 'on') {
            //~ $solvencia_ince = 1;
        //~ } else {
            //~ $solvencia_ince = 0;
        //~ }
        //~ $solvencia_sso = $this->input->post('solvencia_sso');
        //~ if ((string)$solvencia_sso == 'on') {
            //~ $solvencia_sso = 1;
        //~ } else {
            //~ $solvencia_sso = 0;
        //~ }

        $data = array(
			'id' => $ultimo_id + 1,
            'codigo' => $this->input->post('codigo'),
            'tipo_proveedor' => $this->input->post('t_proveedor'),  // Tipo de proveedor
            'cirif' => $this->input->post('cirif'),
            'tipoproveedor' => $this->input->post('tipoproveedor'),  // Tipo de identificación
            'nombre' => $this->input->post('nombre'),
            'estado' => $this->input->post('estado'),
            'municipio' => $this->input->post('municipio'),
            'parroquia' => $this->input->post('parroquia'),
            'direccion' => $this->input->post('direccion'),
            'tlf' => $this->input->post('tlf'),
            'tlf_movil' => $this->input->post('tlf_movil'),
            'fax' => $this->input->post('fax'),
            'email' => $this->input->post('email'),
            'fechacreacion' => date('Y-m-d H:i:s'),
            'estatus' => $is_active,
            'rif' => $rif,
            'venc_cirif' => $this->input->post('venc_cirif'),
            'acta_constitutiva' => $acta,
            'cedula_represen' => $representante,
            'autorizacion_represen' => $autorizacion,
            'solvencia_laboral' => $solvencia,
            'venc_solvencia_laboral' => $this->input->post('venc_solvencia_laboral'),
            'snc' => $snc,
            'rcn' => $rcn,
            'venc_rcn' => $this->input->post('venc_rcn'),
            'solvencia_ince' => $solvencia_ince,
            'venc_solvencia_ince' => $this->input->post('venc_solvencia_ince'),
            'solvencia_sso' => $solvencia_sso,
            'venc_solvencia_sso' => $this->input->post('venc_solvencia_sso'),
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
//      $result = $this->ModelsProveedores->insertarContacto($datos);
        $result = $this->ModelsProveedores->insertar($data, $datos);


        if ($result) {
            $param = array(
                'tabla' => 'proveedor',
                'codigo' => $this->input->post('codigo'),
                'accion' => 'Nuevo Proveedor',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            //~ redirect('proveedores/ControllersProveedores');
        }
    }

    function editar() {
        $data['id'] = $this->uri->segment(4);
        $data['editar'] = $this->ModelsProveedores->obtenerProveedor($data['id']);
        $data['list_tipo'] = $this->ModelsTipoProveedor->obtenerTiposProveedores();
        $data['list_estado'] = $this->ModelsEstado->obtenerEstados();
        $data['list_municipio'] = $this->ModelsMunicipio->obtenerMunicipios();
        $data['list_parroquia'] = $this->ModelsParroquia->obtenerParroquias();

        $this->load->view('proveedores/editar', $data);
    }

    function eliminar($id) {
        $result = $this->ModelsProveedores->eliminarProveedor($id);
        $param = array(
                'tabla' => 'proveedor',
                'codigo' => $id,
                'accion' => 'Eliminar Proveedor',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
        if ($result) {
            
            //~ redirect('proveedores/ControllersProveedores');
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
        
        //~ $rif = $this->input->post('rif');
        //~ if ((string)$rif == 'on') {
            //~ $rif = 1;
        //~ } else {
            //~ $rif = 0;
        //~ }
        //~ $acta_constitutiva = $this->input->post('acta_constitutiva');
        //~ if ((string)$acta_constitutiva == 'on') {
            //~ $acta = 1;
        //~ } else {
            //~ $acta = 0;
        //~ }
        //~ $cedula_represen = $this->input->post('cedula_represen');
        //~ if ((string)$cedula_represen == 'on') {
            //~ $representante = 1;
        //~ } else {
            //~ $representante = 0;
        //~ }
        //~ $autorizacion_represen = $this->input->post('autorizacion_represen');
        //~ if ((string)$autorizacion_represen == 'on') {
            //~ $autorizacion = 1;
        //~ } else {
            //~ $autorizacion = 0;
        //~ }
        //~ $solvencia_laboral = $this->input->post('solvencia_laboral');
        //~ if ((string)$solvencia_laboral == 'on') {
            //~ $solvencia = 1;
        //~ } else {
            //~ $solvencia = 0;
        //~ }
        //~ $snc = $this->input->post('snc');
        //~ if ((string)$snc == 'on') {
            //~ $snc = 1;
        //~ } else {
            //~ $snc = 0;
        //~ }
        //~ $rcn = $this->input->post('rcn');
        //~ if ((string)$rcn == 'on') {
            //~ $rcn = 1;
        //~ } else {
            //~ $rcn = 0;
        //~ }
        //~ $solvencia_ince = $this->input->post('solvencia_ince');
        //~ if ((string)$solvencia_ince == 'on') {
            //~ $solvencia_ince = 1;
        //~ } else {
            //~ $solvencia_ince = 0;
        //~ }
        //~ $solvencia_sso = $this->input->post('solvencia_sso');
        //~ if ((string)$solvencia_sso == 'on') {
            //~ $solvencia_sso = 1;
        //~ } else {
            //~ $solvencia_sso = 0;
        //~ }

        $data = array(
            'id' => $this->input->post('id'),
            'codigo' => $this->input->post('codigo'),
            'tipo_proveedor' => $this->input->post('t_proveedor'),  // Tipo de proveedor
            'cirif' => $this->input->post('cirif'),
            'tipoproveedor' => $this->input->post('tipoproveedor'),  // Tipo de identificación
            'nombre' => $this->input->post('nombre'),
            'estado' => $this->input->post('estado'),
            'municipio' => $this->input->post('municipio'),
            'parroquia' => $this->input->post('parroquia'),
            'direccion' => $this->input->post('direccion'),
            'tlf' => $this->input->post('tlf'),
            'tlf_movil' => $this->input->post('tlf_movil'),
            'fax' => $this->input->post('fax'),
            'email' => $this->input->post('email'),
            'fechacreacion' => date('Y-m-d H:i:s'),
            'estatus' => $is_active,
            'rif' => $rif,
            'venc_cirif' => $this->input->post('venc_cirif'),
            'acta_constitutiva' => $acta,
            'cedula_represen' => $representante,
            'autorizacion_represen' => $autorizacion,
            'solvencia_laboral' => $solvencia,
            'venc_solvencia_laboral' => $this->input->post('venc_solvencia_laboral'),
            'snc' => $snc,
            'rcn' => $rcn,
            'venc_rcn' => $this->input->post('venc_rcn'),
            'solvencia_ince' => $solvencia_ince,
            'venc_solvencia_ince' => $this->input->post('venc_solvencia_ince'),
            'solvencia_sso' => $solvencia_sso,
            'venc_solvencia_sso' => $this->input->post('venc_solvencia_sso'),
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
        //~ $result = $this->ModelsProveedores->actualizarContacto($datos);
        $result = $this->ModelsProveedores->actualizarProveedor($data);

        if ($result) {
            $param = array(
                'tabla' => 'proveedor',
                'codigo' => $this->input->post('codigo'),
                'accion' => 'Editar Proveedor',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            //~ redirect('proveedores/ControllersProveedores');
        }
    }

}
