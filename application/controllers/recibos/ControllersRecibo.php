<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersRecibo extends CI_Controller
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
        $this->load->model('recibos/ModelsRecibo');
        $this->load->model('empleados/ModelsEmpleados');
        $this->load->model('conceptos/ModelsConceptos');
        $this->load->model('cuentas/ModelsCuentas');
        $this->load->model('bancos/ModelsBancos');
        $this->load->model('movimientos/ModelsMovimientos');
        $this->load->model('busquedas_ajax/ModelsBusqueda');
        
    }

    function index()
    {
		$data['cuentas'] = $this->ModelsCuentas->obtenerCuentas();
		$data['bancos'] = $this->ModelsBancos->obtenerBancos();
        $data['listar'] = $this->ModelsRecibo->obtenerRecibos();
        $this->load->view('recibos/lista.php', $data);
    }
    
    function recibo()
    {

        $data['listar'] = $this->ModelsRecibo->obtenerEmpleados();
        $data['hora']= time();
        $data['ultimo_id'] = $this->ModelsBusqueda->count_all_table('recibos');
        $data['cod_recibo'] = '';
        if ($data['ultimo_id'] > 0){
			$data['cod_recibo'] = $this->ModelsRecibo->obtenerRecibo($data['ultimo_id']);
		}
        $this->load->view('recibos/recibo', $data);
    }

    public function guardar()
    {	
		$ultimo_id = $this->ModelsBusqueda->count_all_table('recibos');
		// Preparamos los datos generales del recibo
		$data_recibo = array(
			'id' => $ultimo_id + 1,
			'codrecibo' => trim($this->input->post('codrecibo')),
			'codempleado' => $this->input->post('codempleado'),
			'empleado' => $this->input->post('empleado'),
			'salario' => $this->input->post('salario'),
			'monto_salario' => $this->input->post('monto_salario'),
			'abonos' => $this->input->post('abonos'),
			'descuento' => $this->input->post('descuento'),
			'ret_faov' => $this->input->post('ret_faov'),
			'ret_sso' => $this->input->post('ret_sso'),
			'faov_patrono' => $this->input->post('faov_patrono'),
			'sso_patrono' => $this->input->post('sso_patrono'),
			'condicion_pago' => $this->input->post('condicion_pago'),
			'subtotal' => $this->input->post('subtotal'),
			'totalrecibo' => $this->input->post('totalrecibo'),
			'observaciones' => $this->input->post('observaciones'),
			'estado' => 1,
			'fecha_emision' => date("Y-m-d"),
			'hora_emision' => date("h:i:s a"),
			//~ 'num_cheque' => $this->input->post('num_cheque'),
			//~ 'num_recibo' => $this->input->post('num_recibo'),
			//~ 'num_transf' => $this->input->post('num_transf'),
			//~ 'num_deposito' => $this->input->post('num_deposito'),
			'num_control' => (string)rand(),
			//~ 'fecha_vencimiento' => $this->input->post('fecha_vencimiento'),
        );
        
		// Guardamos los datos generales de la recibo
		$result = $this->ModelsRecibo->insertar($data_recibo);
		
		// Guardado en el módulo de auditoría
		if ($result) {

            $param = array(
                'tabla' => 'recibos',
                'codigo' => trim($this->input->post('codrecibo')),
                'accion' => 'Nueva recibo',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            
            // Guardamos los abonos/descuentos asociados al recibo
			$data_recibo_ps = $this->input->post('data');
			
			foreach ($data_recibo_ps as $campos){
				//~ print_r($campos);
				//Construcción del correlativo para el nuevo registro
				$ultimo_id = $this->ModelsBusqueda->count_all_table('recibos_ps');
				//~ echo $ultimo_id;
				$correlativo_ps = str_pad($ultimo_id+1, 8, "0", STR_PAD_LEFT);  //Rellenamos con ceros a la izquierda
				//~ echo $correlativo_ps;
				$tipo = "";
				if($campos['tipo'] == 'Abono'){
					$tipo = 1;
				}else{
					$tipo = 2;
				}
				// Registro del nuevo producto
				$data_f_ps = array(
					'id' => $ultimo_id+1,
					'codrecibops' => $correlativo_ps,
					'codrecibo' => $campos['cod_recibo'],
					'tipo' => $tipo,
					'abono_descuento' => $campos['concepto'],
					'monto' => $campos['monto'],
					'cantidad' => $campos['cantidad'],
					'importe' => $campos['importe'],
				);
				
				// Guardamos los datos de los productos del recibo
				$result = $this->ModelsRecibo->insertar_ps($data_f_ps);
			}
        }

    }
    

    function editar()
    {
        $data['codrecibo'] = $this->uri->segment(4);
        $data['editar'] = $this->ModelsBusqueda->obtenerRegistro('recibos', 'codrecibo', $data['codrecibo']);  // Datos de la recibo
        $data['listar'] = $this->ModelsRecibo->obtenerEmpleados();  // Lista de clientes
        $data['hora']= time();  //Hora actual
        $data['listar_ad'] = $this->ModelsRecibo->obtenerAbonosDescuentos($data['codrecibo']);  // Lista de abonos/descuentos
        
        $this->load->view('recibos/editar', $data);
    }


    function actualizar()
    
    {
        // Preparamos los datos generales de la recibo
		
		$regs_eliminar = $this->input->post('codigos_des');  // Abonos/Descuentos a desvincular del recibo
		
		$data_recibo = array(
			'codrecibo' => trim($this->input->post('codrecibo')),
			'codempleado' => $this->input->post('codempleado'),
			'empleado' => $this->input->post('empleado'),
			'salario' => $this->input->post('salario'),
			'monto_salario' => $this->input->post('monto_salario'),
			'abonos' => $this->input->post('abonos'),
			'descuento' => $this->input->post('descuento'),
			'ret_faov' => $this->input->post('ret_faov'),
			'ret_sso' => $this->input->post('ret_sso'),
			'faov_patrono' => $this->input->post('faov_patrono'),
			'sso_patrono' => $this->input->post('sso_patrono'),
			'condicion_pago' => $this->input->post('condicion_pago'),
			'subtotal' => $this->input->post('subtotal'),
			'totalrecibo' => $this->input->post('totalrecibo'),
			'observaciones' => $this->input->post('observaciones'),
			//~ 'estado' => 1,
			//~ 'fecha_emision' => date("Y-m-d"),
			//~ 'hora_emision' => date("h:i:s a"),
			//~ 'num_cheque' => $this->input->post('num_cheque'),
			//~ 'num_recibo' => $this->input->post('num_recibo'),
			//~ 'num_transf' => $this->input->post('num_transf'),
			//~ 'num_deposito' => $this->input->post('num_deposito'),
			//~ 'num_control' => (string)rand(),
        );
        
		// Actualizamos los datos generales de la recibo
		$result = $this->ModelsRecibo->actualizarRecibo($data_recibo);
		
		// Guardado en el módulo de auditoría
		if ($result) {

            $param = array(
                'tabla' => 'recibos',
                'codigo' => trim($this->input->post('codrecibo')),
                'accion' => 'Editar recibo',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            
            // Guardamos los abonos/descuentos asociados al recibo
            $data_recibo_ps = $this->input->post('data');
			
			// Verificamos si hay productos para asociar (registrar en recibos_ps)
			foreach ($data_recibo_ps as $campos){
				
				// Primero validamos si el producto ya tiene código, si es así entonces es que ya está asociado
				if ($campos['cod_ad_ps'] == ""){
					echo "abono/descuento no existente";
					//Construcción del correlativo para el nuevo registro
					$ultimo_id = $this->ModelsBusqueda->count_all_table('recibos_ps');
					//~ echo $ultimo_id;
					$correlativo_ps = str_pad($ultimo_id+1, 8, "0", STR_PAD_LEFT);  // Rellenamos con ceros a la izquierda hasta completar 8 dígitos
					//~ echo $correlativo_ps;
					$tipo = "";
					if($campos['tipo'] == 'Abono'){
						$tipo = 1;
					}else{
						$tipo = 2;
					}
					// Registro del nuevo producto
					$data_f_ps = array(
						'id' => $ultimo_id+1,
						'codrecibops' => $correlativo_ps,
						'codrecibo' => $campos['cod_recibo'],
						'tipo' => $tipo,
						'abono_descuento' => $campos['concepto'],
						'monto' => $campos['monto'],
						'cantidad' => $campos['cantidad'],
						'importe' => $campos['importe'],
					);
					
					// Guardamos los datos de los nuevos productos de la recibo
					$result = $this->ModelsRecibo->insertar_ps($data_f_ps);
					
				}else{
					echo "producto existente";
				}
			}
			
			// Verificamos si hay productos para eliminar
			if($regs_eliminar != ''){
				$regs_eliminar = explode(",",$regs_eliminar);
				
				// Desvinculamos (eliminamos de la tabla recibos_ps)
				foreach ($regs_eliminar as $reg){
					//~ echo "Código: ".$reg;
					
					// Eliminamos la asociación de la tabla recibos_ps
					$result = $this->ModelsRecibo->eliminarAbonoDescuento($reg);
				}
			}
        }
        
    }
    
    // Método para anular o activar un recibo
    function anular($cod)
    {
		$accion = $this->input->post('accion');
		$estado = 1;
		
		if ($accion == 'anular'){
			$estado = 3;
		}
		
		// Armamos la data a actualizar
        $data_recibo = array(
			'codrecibo' => $cod,
			'estado' => $estado,
			'motivo_anulacion' => $this->input->post('motivo'),
        );
        
		// Actualizamos la recibo con los datos armados
		$result = $this->ModelsRecibo->actualizarRecibo($data_recibo);
		
		// Guardado en el módulo de auditoría
		if ($result) {

            $param = array(
                'tabla' => 'recibos',
                'codigo' => $cod,
                'accion' => 'Anular recibo',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            //~ redirect('presupuesto/ControllersPresupuesto');
        }
		
    }
    
    
    // Método para verificar o activar un recibo
    function verificar($cod)
    {
		$accion = $this->input->post('accion');
		$estado = 1;
		$cuenta = $this->input->post('cuenta');
		$monto_recibo = $this->input->post('monto_r');
		$firma_rrhh = $this->input->post('firma_rrhh');
		
		if ($accion == 'verificar'){
			$estado = 2;
		}
		// Obtenemos los datos del usuario logueado
		$datos_usuario = $this->ModelsBusqueda->obtenerRegistro('usuarios', 'id', $this->session->userdata['logged_in']['id']);
		$usuario_emite = "".$datos_usuario->first_name ." ". $datos_usuario->last_name ." (".$datos_usuario->username .")";
		
		// Armamos la data a actualizar
        $data_recibo = array(
			'codrecibo' => $cod,
			'estado' => $estado,
			'firma_rrhh' => $usuario_emite,
			'fecha_entrega' => date("d-m-Y"),
			'hora_entrega' => date("h:i:s a"),
			'num_cheque' => $this->input->post('num_cheque'),
			'num_recibo' => $this->input->post('num_recibo'),
			'num_transf' => $this->input->post('num_transf'),
			'num_deposito' => $this->input->post('num_deposito'),
        );
        
		// Actualizamos el recibo con los datos armados
		$result = $this->ModelsRecibo->actualizarRecibo($data_recibo);
		
		// Guardado en el módulo de auditoría, cuentas y movimientos
		if ($result) {
			// Guardado en el módulo de auditoría
            $param = array(
                'tabla' => 'recibos',
                'codigo' => $cod,
                'accion' => 'verificar recibo',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            
            // Guardado en el módulo de cuentas
            // Primero buscamos los datos del recibo para extraer los aportes patronales
            $busqueda_recibo = $this->ModelsBusqueda->obtenerRegistro('recibos','codrecibo',$cod);
            // Luego buscamos los datos de la cuenta con el código
            $busqueda_cuenta = $this->ModelsBusqueda->obtenerRegistro('cuentas','codigo',$cuenta);
            // Armamos la data para actualizar la cuenta
            $data_cuenta = array(
                'id' => $busqueda_cuenta->id,
                'monto_total' => (float)$busqueda_cuenta->monto_total-(float)$monto_recibo,
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
				'monto' => (float)$monto_recibo,
				'tipo' => '2',
				'concepto' => "Nueva deducción por recibo $cod",
				'fecha' => date('Y-m-d'),
				'hora' => date("h:i:s a"),
				'user_create' => $this->session->userdata['logged_in']['id'],
            );
            // Guardamos los datos del movimiento
			$this->ModelsMovimientos->insertar($data_movimiento);
			
        }
		
    }
    
    // Método para consultar los ajustes aplicados a una recibo o más de un empleado
    function consultar_salario($empleado) {
		// Primero consultamos los datos del empleado
		$emp = $this->ModelsBusqueda->obtenerRegistro('empleados', 'codigo', $empleado);
		
		// Ahora consultamos el salario base que le corresponde y lo multiplicamos por la escala salarial que le corresponde
        $result = $this->ModelsBusqueda->obtenerRegistro('conceptos', 'codigo', $emp->salario);
        
        //~ echo json_encode($result);
        $total_salario = $result->monto*$emp->escala;
        echo $result->concepto.";".$result->monto.";".$emp->escala.";".$total_salario;
    }
    
    // Método para consultar los ajustes aplicados a una recibo o más de un empleado
    function saldo_cuenta($cuenta) {
		// Primero consultamos los datos del empleado
		$busqueda_cuenta = $this->ModelsBusqueda->obtenerRegistro('cuentas','codigo',$cuenta);
		
        echo $busqueda_cuenta->monto_total;
    }
    
    // Método para generar las recibos en pdf
    function pdf_recibo($cod)
    {
        $data['recibo'] = $this->ModelsRecibo->obtenerReciboCod($cod);  // Datos generales del recibo
        if ($data['recibo']->codempleado != 'PUNTOVENTA'){
			$data['empleado'] = $this->ModelsRecibo->obtenerEmpleadoCod($data['recibo']->codempleado);  // Datos del empleado
		}else{
			$data['empleado'] = "PUNTOVENTA";  // Datos del empleado
		}
        $data['abonos_descuentos'] = $this->ModelsRecibo->obtenerAbonosDescuentos($data['recibo']->codrecibo);  // Productos asociados a la recibo
        //~ foreach ($data['productos_servicios'] as $campos){
			//~ print_r($campos);
		//~ }
        
        $this->load->view('recibos/pdf/reporte_recibo', $data);
    }
}
