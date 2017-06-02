<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersAjustes extends CI_Controller
{

    public function __construct()
    {

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
        $this->load->model('ajustes/ModelsAjustes');
        $this->load->model('factura/ModelsFacturar');
        $this->load->model('productos/ModelsProductos');
        $this->load->model('impuesto/ModelsImpuesto');
        $this->load->model('topologia/ModelsEstado');
        $this->load->model('topologia/ModelsMunicipio');
        $this->load->model('topologia/ModelsParroquia');
        $this->load->model('busquedas_ajax/ModelsBusqueda');
        
    }

    function index()
    {

        $data['listar'] = $this->ModelsAjustes->obtenerAjustes();
        //~ $data['list_iva'] = $this->ModelsImpuesto->obtenerImpuestos();
        //~ $data['ultimo_id'] = $this->ModelsBusqueda->count_all_table('facturas');
        //~ $data['cod_factura'] = '';
        //~ if ($data['ultimo_id'] > 0){
			//~ $data['cod_factura'] = $this->ModelsAjustes->obtenerFactura($data['ultimo_id']);
		//~ }
        $this->load->view('ajustes/lista.php', $data);
    }
    
    function ajuste()
    {

        $data['listar'] = $this->ModelsAjustes->obtenerFacturas();  // Lista de facturas
        $data['list_iva'] = $this->ModelsImpuesto->obtenerImpuestos();  // Lista de impuestos
        $data['hora']= time();
        $data['ultimo_id'] = $this->ModelsBusqueda->count_all_table('ajustes');
        $data['cod_ajuste'] = '';
        if ($data['ultimo_id'] > 0){
			$data['cod_ajuste'] = $this->ModelsAjustes->obtenerAjuste($data['ultimo_id']);
		}
        $this->load->view('ajustes/ajuste.php', $data);
    }

    public function guardar()
    {	
		$ultimo_id = $this->ModelsBusqueda->count_all_table('ajustes');
		$exento = $this->input->post('monto_exento');
		//~ echo "Monto exento: ".$exento;
		if($exento == ''){
			$exento = 0;
		}
		$data_consumo = array(
			'id' => $ultimo_id + 1,
			'codajuste' => trim($this->input->post('codajuste')),
			'codfactura' => trim($this->input->post('codfactura')),
			//~ 'pre_cod_factura' => trim($this->input->post('pre_cod_factura')),
			'rifcliente' => $this->input->post('rifcliente'),
			'cliente' => $this->input->post('cliente'),
			'base_imponible' => $this->input->post('base_imponible'),
			'monto_exento' => $exento,
			'monto_iva' => $this->input->post('monto_iva'),
			'iva' => $this->input->post('iva'),
			'tipo_ajuste' => $this->input->post('tipo_ajuste'),
			'subtotal' => $this->input->post('subtotal'),
			'totalajuste' => $this->input->post('totalajuste'),
			'concepto' => $this->input->post('concepto'),
			'estado' => 1,
			'fecha_ajuste' => date("Y-m-d"),
			'hora_ajuste' => date("h:i:s a"),
			'user_create' => $this->session->userdata['logged_in']['id'],
			//~ 'fecha_vencimiento' => $this->input->post('fecha_vencimiento'),
        );
        
		// Guardamos los datos generales del ajuste
		$result = $this->ModelsAjustes->insertar($data_consumo);
		
		// Guardado en el módulo de auditoría
		if ($result) {

            $param = array(
                'tabla' => 'ajustes',
                'codigo' => trim($this->input->post('codajuste')),
                'accion' => 'Nuevo Ajuste',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            //~ redirect('presupuesto/ControllersPresupuesto');
        }        

    }
    

    function editar()
    {
        $data['codajuste'] = $this->uri->segment(4);
        $data['editar'] = $this->ModelsBusqueda->obtenerRegistro('ajustes', 'codajuste', $data['codajuste']);  // Datos de la factura
        $data['listar'] = $this->ModelsAjustes->obtenerFacturas();  // Lista de facturas
        $data['list_iva'] = $this->ModelsImpuesto->obtenerImpuestos();  // Lista de tipos de IVA
        $data['hora']= time();  //Hora actual
        
        $this->load->view('ajustes/editar', $data);
    }


    function actualizar()
    
    {
		$exento = $this->input->post('monto_exento');
		if($exento == ''){
			$exento = 0;
		}
		$data_ajuste = array(
			'codajuste' => trim($this->input->post('codajuste')),
			'codfactura' => trim($this->input->post('codfactura')),
			//~ 'pre_cod_factura' => trim($this->input->post('pre_cod_factura')),
			'rifcliente' => $this->input->post('rifcliente'),
			'cliente' => $this->input->post('cliente'),
			'base_imponible' => $this->input->post('base_imponible'),
			'monto_exento' => $exento,
			'monto_iva' => $this->input->post('monto_iva'),
			'iva' => $this->input->post('iva'),
			'tipo_ajuste' => $this->input->post('tipo_ajuste'),
			'subtotal' => $this->input->post('subtotal'),
			'totalajuste' => $this->input->post('totalajuste'),
			'concepto' => $this->input->post('concepto'),
			'estado' => 1,
			'fecha_ajuste' => $this->input->post('fecha_ajuste'),
			'hora_ajuste' => date("h:i:s a"),
			//~ 'fecha_vencimiento' => $this->input->post('fecha_vencimiento'),
        );
        
		// Actualizamos los datos generales de la factura
		$result = $this->ModelsAjustes->actualizarAjuste($data_ajuste);
		
		// Guardado en el módulo de auditoría
		if ($result) {

            $param = array(
                'tabla' => 'ajuste',
                'codigo' => trim($this->input->post('codajuste')),
                'accion' => 'Editar Ajuste',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            //~ redirect('presupuesto/ControllersPresupuesto');
        }
        
    }
    
    // Método para anular o activar una factura
    function anular($cod)
    {
		//~ echo "Código: ".$cod;
		//~ echo "Acción: ".$this->input->post('accion');
		//~ echo "Motivo: ". $this->input->post('motivo');
		
		$accion = $this->input->post('accion');
		$estado = 1;
		
		if ($accion == 'anular'){
			$estado = 3;
		}
		
		// Armamos la data a actualizar
        $data_ajuste = array(
			'codajuste' => $cod,
			'estado' => $estado,
			'motivo_anulacion' => $this->input->post('motivo'),
        );
        
		// Actualizamos el ajuste con los datos armados
		$result = $this->ModelsAjustes->actualizarAjuste($data_ajuste);
		
		// Guardado en el módulo de auditoría
		if ($result) {

            $param = array(
                'tabla' => 'ajuste',
                'codigo' => $cod,
                'accion' => 'Anular Ajuste',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            //~ redirect('ajuste/Controllersajuste');
        }
		
    }
    
    
    // Método para pagar o activar una factura
    function aplicar($cod)
    {
		//~ echo "Código: ".$cod;
		//~ echo "Acción: ".$this->input->post('accion');
		
		$accion = $this->input->post('accion');
		$estado = 1;
		
		if ($accion == 'aplicar'){
			$estado = 2;
		}
		
		// Armamos la data a actualizar
        $data_ajuste = array(
			'codajuste' => $cod,
			'estado' => $estado,
        );
        
		// Actualizamos el ajuste con los datos armados
		$result = $this->ModelsAjustes->actualizarAjuste($data_ajuste);
		
		// Guardado en el módulo de auditoría
		if ($result) {

            $param = array(
                'tabla' => 'ajuste',
                'codigo' => $cod,
                'accion' => 'Ejecutar Ajuste',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            //~ redirect('presupuesto/ControllersPresupuesto');
        }
		
    }
    
    //Método para la generación del reporte del ajuste
    function pdf_ajuste($cod)
    {
        $data['ajuste'] = $this->ModelsAjustes->obtenerAjusteCod($cod);  // Datos generales del ajuste
        $data['factura'] = $this->ModelsFacturar->obtenerFacturaCod($data['ajuste']->codfactura);  // Datos de la factura
        $data['cliente'] = $this->ModelsAjustes->obtenerClienteCod($data['factura']->codcliente);  // Datos del cliente
        
        $this->load->view('ajustes/pdf/reporte_ajuste', $data);
    }
    
}
