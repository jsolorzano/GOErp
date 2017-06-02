<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersTransferencias extends CI_Controller
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
        $this->load->model('cuentas/ModelsCuentas');
        $this->load->model('bancos/ModelsBancos');
        $this->load->model('usuarios/Usuarios_model');
        $this->load->model('transferencias/ModelsTransferencias');
        $this->load->model('movimientos/ModelsMovimientos');
        $this->load->model('busquedas_ajax/ModelsBusqueda');
        
    }

    function index()
    {
        $data['list_bancos'] = $this->ModelsBancos->obtenerBancos();  // Bancos en general 
        $data['list_cuentas'] = $this->ModelsCuentas->obtenerCuentas();
        $data['list_usuarios'] = $this->Usuarios_model->obtenerUsuarios();
        $data['listar'] = $this->ModelsTransferencias->obtenerTransferencias();
        $this->load->view('transferencias/lista.php', $data);
    }
    
    function registrar() {
        $data['ultimo_id'] = $this->ModelsBusqueda->count_all_table('transferencias');
        //~ $data['list_bancos'] = $this->ModelsCuentas->obtenerBancosActivos();  // Sólo bancos activos
        $data['bancos'] = $this->ModelsBancos->obtenerBancos();
        $data['cuentas'] = $this->ModelsCuentas->obtenerCuentas();
        $data['cuentas_activas'] = $this->ModelsCuentas->obtenerCuentasActivasFil2();
        $this->load->view('transferencias/registrar', $data);
    }
    
    public function guardar() {
		
		// Preparamos el siguiente id para evitar errores luegos de cargar manuales
		$ultimo_id = $this->ModelsBusqueda->count_all_table('transferencias');
		
		// Armamos la data a registrar
        $data_transferencia = array(
			'id' => $ultimo_id+1,
			'codigo' => $this->input->post('codigo'),
			'origen' => $this->input->post('origen'),
			'destino' => $this->input->post('destino'),
			'monto' => $this->input->post('monto'),
			'num_referencia' => '',
			'estatus' => $this->input->post('estatus'),
			'fecha' => date('Y-m-d'),
			'hora' => date("h:i:s a"),
			'user_create' => $this->session->userdata['logged_in']['id'],
		);

        $result = $this->ModelsTransferencias->insertar($data_transferencia);

        if ($result) {

            $param = array(
                'tabla' => 'transferencias',
                'codigo' => $this->input->post('codigo'),
                'accion' => 'Nueva transferencia',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('transferencias/ControllersTransferencias');
        }
    }
    
    function editar() {
        $data['id'] = $this->uri->segment(4);
        $data['editar'] = $this->ModelsTransferencias->obtenerTransferencia($data['id']);
        $data['bancos'] = $this->ModelsBancos->obtenerBancos();
        $data['cuentas'] = $this->ModelsCuentas->obtenerCuentas();
        $data['cuentas_activas'] = $this->ModelsCuentas->obtenerCuentasActivasFil2();
        $this->load->view('transferencias/editar', $data);
    }
    
    function actualizar() {
		
		// Armamos la data a actualizar
        $data_transferencia = array(
			'id' => $this->input->post('id'),
			'codigo' => $this->input->post('codigo'),
			'origen' => $this->input->post('origen'),
			'destino' => $this->input->post('destino'),
			'monto' => $this->input->post('monto'),
        );
        
        $result = $this->ModelsTransferencias->actualizar($data_transferencia);

        if ($result) {

            $param = array(
                'tabla' => 'transferencias',
                'codigo' => $this->input->post('codigo'),
                'accion' => 'Editar transferencia',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('transferencias/ControllersTransferencias');
        }
    }
    
    // Método para consultar los datos de una cuenta por su código
    function consultar() {
        $result = $this->ModelsBusqueda->obtenerRegistro('cuentas', 'codigo', $this->input->post('origen'));
        echo $result->monto_total;
    }
    
    // Método para anular o activar una transferencia
    function anular($cod)
    {
		$accion = $this->input->post('accion');
		$estatus = 1;
		
		if ($accion == 'anular'){
			$estatus = 3;
		}
		
		// Armamos la data a actualizar
        $data_transferencia = array(
			'codigo' => $cod,
			'estatus' => $estatus,
			'motivo_anulacion' => $this->input->post('motivo'),
        );
        
		// Actualizamos la transferencia con los datos armados
		$result = $this->ModelsTransferencias->actualizar($data_transferencia);
		
		// Guardado en el módulo de auditoría
		if ($result) {
            $param = array(
                'tabla' => 'facturas',
                'codigo' => $cod,
                'accion' => 'Anular transferencia',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
        }
		
    }
    
    // Método para verificar o activar una transferencia
    function verificar($cod)
    {
		//~ echo "Código: ".$cod;
		//~ echo "Acción: ".$this->input->post('accion');
		//~ echo "condicion pago: ". $this->input->post('condicion');
		
		$accion = $this->input->post('accion');
		$estatus = 1;
		$origen = $this->input->post('origen');
		$destino = $this->input->post('destino');
		$monto = $this->input->post('monto');
		
		if ($accion == 'verificar'){
			$estatus = 2;
		}
		
		// Armamos la data a actualizar
        $data_transferencia = array(
			'codigo' => $cod,
			'estatus' => $estatus,
			'num_referencia' => $this->input->post('num_referencia'),
        );
        
		// Actualizamos la factura con los datos armados
		$result = $this->ModelsTransferencias->actualizar($data_transferencia);
		
		// Guardado en el módulo de auditoría y cuentas
		if ($result) {
			// Guardado en el módulo de auditoría
            $param = array(
                'tabla' => 'transferencias',
                'codigo' => $cod,
                'accion' => 'verificar Tranferencia',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            
            // Guardado en el módulo de cuentas
            // Primero buscamos los datos de las cuentas con su código
            $busqueda_cuenta_origen = $this->ModelsBusqueda->obtenerRegistro('cuentas','codigo',$origen);
            $busqueda_cuenta_destino = $this->ModelsBusqueda->obtenerRegistro('cuentas','codigo',$destino);
            // Armamos la data para actualizar las cuentas
            $data_cuenta_origen = array(
                'id' => $busqueda_cuenta_origen->id,
                'monto_total' => (float)$busqueda_cuenta_origen->monto_total-(float)$monto,
            );
            $data_cuenta_destino = array(
                'id' => $busqueda_cuenta_destino->id,
                'monto_total' => (float)$busqueda_cuenta_destino->monto_total+(float)$monto,
            );
            // Actualizamos el nuevo monto total a las cuentas
            $update_cuenta_origen = $this->ModelsCuentas->actualizarCuenta($data_cuenta_origen);
            $update_cuenta_destino = $this->ModelsCuentas->actualizarCuenta($data_cuenta_destino);
            
            // Guardado en el módulo de movimientos
            // Armamos la data de los dos movimientos a crear, uno de deducción y otro de ingreso
            $movimiento1 = array(
				'banco' => $busqueda_cuenta_origen->cod_banco,
				'cuenta' => $origen,
				'tipo' => '2',
				'concepto' => "Nueva deducción por transferencia $cod",
			);
			$movimiento2 = array(
				'banco' => $busqueda_cuenta_destino->cod_banco,
				'cuenta' => $destino,
				'tipo' => '1',
				'concepto' => "Nuevo ingreso por transferencia $cod",
			);
			$data_movimientos = array('mov1'=>$movimiento1, 'mov2'=>$movimiento2);
			
			// Ejecutamos el registro de los movimientos
			foreach($data_movimientos as $movimiento){
				// Construimos el correlativo para el nuevo movimiento
				$ultimo_id = $this->ModelsBusqueda->count_all_table('movimientos');
				//~ echo $ultimo_id;
				$correlativo_m = str_pad($ultimo_id+1, 8, "0", STR_PAD_LEFT);  // Rellenamos con ceros a la izquierda hasta completar 8 dígitos
				//~ echo $correlativo_m;
				// Armamos la data para registrar el nuevo movimiento
				$data_movimiento = array(
					'id' => $ultimo_id+1,
					'codigo' => $correlativo_m,
					'banco' => $movimiento['banco'],
					'cuenta' => $movimiento['cuenta'],
					'monto' => (float)$monto,
					'tipo' => $movimiento['tipo'],
					'concepto' => $movimiento['concepto'],
					'fecha' => date('Y-m-d'),
					'hora' => date("h:i:s a"),
					'user_create' => $this->session->userdata['logged_in']['id'],
				);
				// Guardamos los datos del movimiento
				$this->ModelsMovimientos->insertar($data_movimiento);
			}
        }
		
    }
}
