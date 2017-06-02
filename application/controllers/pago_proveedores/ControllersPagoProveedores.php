<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersPagoProveedores extends CI_Controller
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
        $this->load->model('pago_proveedores/ModelsPagoProveedores');
        $this->load->model('cuentas/ModelsCuentas');
        $this->load->model('bancos/ModelsBancos');
        $this->load->model('movimientos/ModelsMovimientos');
        $this->load->model('busquedas_ajax/ModelsBusqueda');
        
    }

    function index()
    {
		$data['cuentas'] = $this->ModelsCuentas->obtenerCuentas();
		$data['bancos'] = $this->ModelsBancos->obtenerBancos();
        $data['listar'] = $this->ModelsPagoProveedores->obtenerPagos();
        $this->load->view('pago_proveedores/lista.php', $data);
    }
    
    
    function pago()
    {
        $data['listar'] = $this->ModelsPagoProveedores->obtenerProveedores();
        $data['hora']= time();
        $data['cuentas'] = $this->ModelsCuentas->obtenerCuentasActivas();
		$data['bancos'] = $this->ModelsBancos->obtenerBancos();
        $data['ultimo_id'] = $this->ModelsBusqueda->count_all_table('pago_proveedores');
        $data['cod_pago'] = '';
        if ($data['ultimo_id'] > 0){
			$data['cod_pago'] = $this->ModelsPagoProveedores->obtenerPago($data['ultimo_id']);
		}
        $this->load->view('pago_proveedores/pago', $data);
    }

    public function guardar()
    {	
		$ultimo_id = $this->ModelsBusqueda->count_all_table('pago_proveedores');
		// Preparamos los datos generales del pago
		$data_pago = array(
			'id' => $ultimo_id + 1,
			'codpago' => trim($this->input->post('codpago')),
			'codproveedor' => $this->input->post('codproveedor'),
			'proveedor' => $this->input->post('proveedor'),
			'monto' => $this->input->post('monto'),
			'cuenta_origen' => $this->input->post('cuenta_origen'),
			'num_factura' => $this->input->post('num_pago'),
			'condicion_pago' => $this->input->post('condicion_pago'),
			'observaciones' => $this->input->post('observaciones'),
			'estado' => 1,
			'fecha_pago' => date("Y-m-d"),
			'hora_pago' => date("h:i:s a"),
			//~ 'num_control' => (string)rand(),
        );
        
		// Guardamos los datos generales del pago
		$result = $this->ModelsPagoProveedores->insertar($data_pago);
		
		// Guardado en el módulo de auditoría
		if ($result) {

            $param = array(
                'tabla' => 'pago_proveedores',
                'codigo' => trim($this->input->post('codpago')),
                'accion' => 'Nuevo Pago',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
        }

    }
    

    function editar()
    {
        $data['codpago'] = $this->uri->segment(4);
        $data['editar'] = $this->ModelsBusqueda->obtenerRegistro('pago_proveedores', 'codpago', $data['codpago']);  // Datos de la pago
        $data['listar'] = $this->ModelsPagoProveedores->obtenerProveedores();
        $data['hora']= time();  //Hora actual
        $data['cuentas'] = $this->ModelsCuentas->obtenerCuentas();
		$data['bancos'] = $this->ModelsBancos->obtenerBancos();
        
        $this->load->view('pago_proveedores/editar', $data);
    }


    function actualizar()
    
    {
        // Preparamos los datos generales del pago
		$regs_eliminar = $this->input->post('codigos_des');  // Productos a desvincular de la pago
		
		$data_pago = array(
			'codpago' => trim($this->input->post('codpago')),
			'codproveedor' => $this->input->post('codproveedor'),
			'proveedor' => $this->input->post('proveedor'),
			'monto' => $this->input->post('monto'),
			'cuenta_origen' => $this->input->post('cuenta_origen'),
			'num_factura' => $this->input->post('num_factura'),
			'condicion_pago' => $this->input->post('condicion_pago'),
			'observaciones' => $this->input->post('observaciones'),
        );
        
		// Actualizamos los datos generales de la pago
		$result = $this->ModelsPagoProveedores->actualizarPago($data_pago);
		
		// Guardado en el módulo de auditoría
		if ($result) {

            $param = array(
                'tabla' => 'pago_proveedores',
                'codigo' => trim($this->input->post('codpago')),
                'accion' => 'Editar pago',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
        }
        
    }
    
    // Método para anular o activar un pago
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
        $data_pago = array(
			'codpago' => $cod,
			'estado' => $estado,
			'motivo_anulacion' => $this->input->post('motivo'),
        );
        
		// Actualizamos el pago con los datos armados
		$result = $this->ModelsPagoProveedores->actualizarPago($data_pago);
		
		// Guardado en el módulo de auditoría
		if ($result) {

            $param = array(
                'tabla' => 'pago_proveedores',
                'codigo' => $cod,
                'accion' => 'Anular pago',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
        }
		
    }    
    
    // Método para verificar o activar una pago
    function verificar($cod)
    {
		//~ echo "Código: ".$cod;
		//~ echo "Acción: ".$this->input->post('accion');
		//~ echo "condicion pago: ". $this->input->post('condicion');
		
		$accion = $this->input->post('accion');
		$estado = 1;
		$cuenta = $this->input->post('cuenta');
		$monto_pago = $this->input->post('monto_p');
		
		if ($accion == 'verificar'){
			$estado = 2;
		}
		
		// Armamos la data a actualizar
        $data_pago = array(
			'codpago' => $cod,
			'estado' => $estado,
			'num_cheque' => $this->input->post('num_cheque'),
			'num_recibo' => $this->input->post('num_recibo'),
			'num_transf' => $this->input->post('num_transf'),
			'num_deposito' => $this->input->post('num_deposito'),
        );
        
		// Actualizamos el pago con los datos armados
		$result = $this->ModelsPagoProveedores->actualizarPago($data_pago);
		
		// Guardado en el módulo de auditoría y cuentas
		if ($result) {
			// Guardado en el módulo de auditoría
            $param = array(
                'tabla' => 'pago_proveedores',
                'codigo' => $cod,
                'accion' => 'verificar pago',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            
            // Guardado en el módulo de cuentas
            // Primero buscamos los datos de la cuenta con el código
            $busqueda_cuenta = $this->ModelsBusqueda->obtenerRegistro('cuentas','codigo',$cuenta);
            // Armamos la data para actualizar la cuenta
            $data_cuenta = array(
                'id' => $busqueda_cuenta->id,
                'monto_total' => (float)$busqueda_cuenta->monto_total-(float)$monto_pago,
            );
            // Actualizamos el nuevo monto total a la cuenta
            $update_cuenta = $this->ModelsCuentas->actualizarCuenta($data_cuenta);
            
            // Guardado en el módulo de movimientos
            // Primero construimos el correlativo para el nuevo movimiento
            $ultimo_id = $this->ModelsBusqueda->count_all_table('movimientos');
			//~ echo $ultimo_id;
			$correlativo_m = str_pad($ultimo_id+1, 8, "0", STR_PAD_LEFT);  // Rellenamos con ceros a la izquierda hasta completar 8 dígitos
			//~ echo $correlativo_m;
			// Armamos la data para registrar el movimiento
            $data_movimiento = array(
				'id' => $ultimo_id+1,
				'codigo' => $correlativo_m,
				'banco' => $busqueda_cuenta->cod_banco,
				'cuenta' => $cuenta,
				'monto' => (float)$monto_pago,
				'tipo' => '2',
				'concepto' => "Nueva deducción por pago $cod",
				'fecha' => date('Y-m-d'),
				'hora' => date("h:i:s a"),
				'user_create' => $this->session->userdata['logged_in']['id'],
            );
            // Guardamos los datos del movimiento
			$this->ModelsMovimientos->insertar($data_movimiento);
			
        }
		
    }
    
    // Método para generar las pagos en pdf
    function pdf_pago($cod)
    {
        $data['pago'] = $this->ModelsPagoProveedores->obtenerpagoCod($cod);  // Datos generales de la pago
        if ($data['pago']->codproveedor != 'PUNTOVENTA'){
			$data['proveedor'] = $this->ModelsPagoProveedores->obtenerProveedorCod($data['pago']->codproveedor);  // Datos del proveedor
		}else{
			$data['proveedor'] = "PUNTOVENTA";  // Datos del proveedor
		}
        
        $this->load->view('pago_proveedores/pdf/reporte_pago', $data);
    }
}
