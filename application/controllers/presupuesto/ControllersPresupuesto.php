<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersPresupuesto extends CI_Controller
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
        $this->load->model('presupuesto/ModelsPresupuesto');
        $this->load->model('impuesto/ModelsImpuesto');
        $this->load->model('topologia/ModelsEstado');
        $this->load->model('topologia/ModelsMunicipio');
        $this->load->model('topologia/ModelsParroquia');
        $this->load->model('busquedas_ajax/ModelsBusqueda');
        
    }

    function index()
    {

        $data['listar'] = $this->ModelsPresupuesto->obtenerPresupuestos();
        //~ $data['list_iva'] = $this->ModelsImpuesto->obtenerImpuestos();
        //~ $data['ultimo_id'] = $this->ModelsBusqueda->count_all_table('presupuesto');
        //~ $data['cod_presupuesto'] = '';
        //~ if ($data['ultimo_id'] > 0){
			//~ $data['cod_presupuesto'] = $this->ModelsPresupuesto->obtenerPresupuesto($data['ultimo_id']);
		//~ }
        $this->load->view('presupuesto/lista.php', $data);
    }
    function presupuesto()
    {

        $data['listar'] = $this->ModelsPresupuesto->obtenerClientes();
        $data['list_iva'] = $this->ModelsImpuesto->obtenerImpuestos();
        $data['hora']= time();
        $data['ultimo_id'] = $this->ModelsBusqueda->count_all_table('presupuesto');
        $data['cod_presupuesto'] = '';
        if ($data['ultimo_id'] > 0){
			$data['cod_presupuesto'] = $this->ModelsPresupuesto->obtenerPresupuesto($data['ultimo_id']);
		}
        $this->load->view('presupuesto/presupuesto', $data);
    }

    public function guardar()
    {	
		// Preparamos los datos generales del presupuesto
		$descuento = 0;
		if ($this->input->post('descuento') != ''){
			$descuento = $this->input->post('descuento');
		}
		$data_presupuesto = array(
                'codpresupuesto' => trim($this->input->post('codpresupuesto')),
                //~ 'pre_cod_presupuesto' => trim($this->input->post('pre_cod_presupuesto')),
                'codcliente' => $this->input->post('codcliente'),
                'cliente' => $this->input->post('cliente'),
                'base_imponible' => $this->input->post('base_imponible'),
                'monto_exento' => $this->input->post('monto_exento'),
                'monto_desc' => $this->input->post('monto_desc'),
                'monto_iva' => $this->input->post('monto_iva'),
                'iva' => $this->input->post('iva'),
                'descuento' => $descuento,
                //~ 'condicion_pago' => $this->input->post('condicion_pago'),
                'subtotal' => $this->input->post('subtotal'),
                'totalpresupuesto' => $this->input->post('totalpresupuesto'),
                'observaciones' => $this->input->post('observaciones'),
                'estado' => 1,
                'fecha_emision' => $this->input->post('fecha_emision'),
                'hora_emision' => date("h:i:s a"),
                //~ 'fecha_vencimiento' => $this->input->post('fecha_vencimiento'),
        );
        
		// Guardamos los datos generales de la presupuesto
		$result = $this->ModelsPresupuesto->insertar($data_presupuesto);
		
		// Guardado en el módulo de auditoría
		if ($result) {

            $param = array(
                'tabla' => 'presupuesto',
                'codigo' => trim($this->input->post('codpresupuesto')),
                'accion' => 'Nuevo Presupuesto',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            //~ redirect('presupuesto/ControllersPresupuesto');
        }
        
        $data_presupuesto_ps = $this->input->post('data');
        
        //~ print_r($data_presupuesto_ps);
        
        foreach ($data_presupuesto_ps as $campos){
            //~ print_r($campos);
            //Construcción del correlativo para el nuevo registro
            $ultimo_id = $this->ModelsBusqueda->count_all_table('presupuesto_ps');
            //~ echo $ultimo_id;
            $correlativo_ps = str_pad($ultimo_id+1, 8, "0", STR_PAD_LEFT);  //Rellenamos con ceros a la izquierda
            //~ echo $correlativo_ps;
            
            // Registro del nuevo producto
            $data_p_ps = array(
				'codpresupuestops' => $correlativo_ps,
				'codpresupuesto' => $campos['cod_presupuesto'],
				'tipo' => 1,
				'cod_producto_servicio' => $campos['id'],
				'producto_servicio' => $campos['id_servicio'],
				'precio' => $campos['precio'],
				'monto_iva' => $campos['monto_iva'],
				'cantidad' => $campos['cantidad'],
				'importe' => $campos['importe'],
            );
            
			// Guardamos los datos de los productos del presupuesto
            $result = $this->ModelsPresupuesto->insertar_ps($data_p_ps);
        }
		//~ echo json_encode($data_presupuesto_ps);
		//~ 
		
		redirect('presupuesto/ControllersPresupuesto');

    }

    function editar()
    {
        $data['codpresupuesto'] = $this->uri->segment(4);
        $data['editar'] = $this->ModelsBusqueda->obtenerRegistro('presupuesto', 'codpresupuesto', $data['codpresupuesto']);  // Datos del presupuesto
        $data['listar'] = $this->ModelsPresupuesto->obtenerClientes();  // Lista de clientes
        $data['list_iva'] = $this->ModelsImpuesto->obtenerImpuestos();  // Lista de tipos de IVA
        $data['hora']= time();  //Hora actual
        $data['listar_ps'] = $this->ModelsPresupuesto->obtenerProdustosServicios($data['codpresupuesto']);  // Lista de productos
        
        $this->load->view('presupuesto/editar', $data);
    }


    function actualizar()
    
    {
        // Preparamos los datos generales de la presupuesto
		$descuento = 0;
		if ($this->input->post('descuento') != ''){
			$descuento = $this->input->post('descuento');
		}
		
		$regs_eliminar = $this->input->post('codigos_des');  // Productos a desvincular del presupuesto
		
		$data_presupuesto = array(
                'codpresupuesto' => trim($this->input->post('codpresupuesto')),
                //~ 'pre_cod_presupuesto' => trim($this->input->post('pre_cod_presupuesto')),
                'codcliente' => $this->input->post('codcliente'),
                'cliente' => $this->input->post('cliente'),
                'base_imponible' => $this->input->post('base_imponible'),
                'monto_exento' => $this->input->post('monto_exento'),
                'monto_desc' => $this->input->post('monto_desc'),
                'monto_iva' => $this->input->post('monto_iva'),
                'iva' => $this->input->post('iva'),
                'descuento' => $descuento,
                //~ 'condicion_pago' => $this->input->post('condicion_pago'),
                'subtotal' => $this->input->post('subtotal'),
                'totalpresupuesto' => $this->input->post('totalpresupuesto'),
                'observaciones' => $this->input->post('observaciones'),
                'estado' => 1,
                'fecha_emision' => $this->input->post('fecha_emision'),
                //~ 'hora_emision' => date("h:i:s a"),
                //~ 'fecha_vencimiento' => $this->input->post('fecha_vencimiento'),
        );
        
		// Actualizamos los datos generales de la presupuesto
		$result = $this->ModelsPresupuesto->actualizarPresupuesto($data_presupuesto);
		
		// Guardado en el módulo de auditoría
		if ($result) {

            $param = array(
                'tabla' => 'presupuesto',
                'codigo' => trim($this->input->post('codpresupuesto')),
                'accion' => 'Editar Presupuesto',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            //~ redirect('presupuesto/ControllersPresupuesto');
        }
        
        $data_presupuesto_ps = $this->input->post('data');
        
        //~ print_r($data_presupuesto_ps);
        
        // Verificamos si hay productos para asociar (registrar en presupuesto_ps)
        foreach ($data_presupuesto_ps as $campos){
			
			// Primero validamos si el producto ya tiene código, si es así entonces es que ya está asociado
			if ($campos['cod_f_ps'] == ""){
				echo "producto no existente";
				//Construcción del correlativo para el nuevo registro
				$ultimo_id = $this->ModelsBusqueda->count_all_table('presupuesto_ps');
				//~ echo $ultimo_id;
				$correlativo_ps = str_pad($ultimo_id+1, 8, "0", STR_PAD_LEFT);  // Rellenamos con ceros a la izquierda hasta completar 8 dígitos
				echo $correlativo_ps;
				
				// Registro del nuevo producto
				$data_p_ps = array(
					'codpresupuestops' => $correlativo_ps,
					'codpresupuesto' => $campos['cod_presupuesto'],
					'tipo' => 1,
					'cod_producto_servicio' => $campos['cod_ps'],
					'producto_servicio' => $campos['nom_ps'],
					'precio' => $campos['precio'],
					'monto_iva' => $campos['monto_iva'],
					'cantidad' => $campos['cantidad'],
					'importe' => $campos['importe'],
				);
				
				// Guardamos los datos de los nuevos productos del presupuesto
				$result = $this->ModelsPresupuesto->insertar_ps($data_p_ps);
			}else{
				echo "producto existente";
			}
        }
        
        // Verificamos si hay productos para eliminar
        if($regs_eliminar != ''){
			$regs_eliminar = explode(",",$regs_eliminar);
			
			// Desvinculamos (eliminamos de la tabla presupuesto_ps)
			foreach ($regs_eliminar as $reg){
				//~ echo "Código: ".$reg;
				
				// Eliminamos la asociación de la tabla presupuesto_ps
				$result = $this->ModelsPresupuesto->eliminarProductoServicio($reg);
			}
		}
        
    }
    
    function tiempo_emision($codigo)
    {
		//~ echo $codigo;
		$cod_presupuesto  = $codigo;
        $data_presupuesto = $this->ModelsBusqueda->obtenerRegistro('presupuesto', 'codpresupuesto', $cod_presupuesto);  // Datos del presupuesto
        $fecha            = $data_presupuesto->fecha_emision;
        //~ echo $fecha;
        $fecha            = explode('-', $fecha);
        $fecha_actual     = date('Y-m-d');
        $fecha_actual     = explode('-', $fecha_actual);
        // Variable de fecha capturada
        $ano              = $fecha[2];
        $mes              = $fecha[1];
        $dia              = $fecha[0];
        // Variable de fecha actual
        $ano_actual       = $fecha_actual[0];
        $mes_actual       = $fecha_actual[1];
        $dia_actual       = $fecha_actual[2];
        // Diferencia de fechas
        $dia_diferencia   = $dia_actual - $dia;
        $mes_diferencia   = $mes_actual - $mes;
        $ano_diferencia   = $ano_actual - $ano;

        # se suma dia_diferencia los dias que tiene el mes acterior de la fecha actual
        if ($dia_diferencia < 0) {
            $mes_diferencia = $mes_diferencia - 1;
            if ($mes_actual) {
                if ($mes_actual == 1 or $mes_actual == 3 or $mes_actual == 5 or $mes_actual == 7 or $mes_actual == 8 or $mes_actual == 10 or $mes_actual == 12) {
                    $dias_mes_anterior = 31;
                } else if ($mes_actual == 2) { # calculo si un año es bisiesto
                    if (((($ano_actual % 100) != 0) and ( ($ano_actual % 4) == 0)) or ( ($ano_actual % 400) == 0)) {
                        echo 'El año es Bisiesto';
                        $dias_mes_anterior = 29;
                    } else {
                        echo 'El año no es Bisiesto';
                        $dias_mes_anterior = 28;
                    }
                } else if ($mes_actual == 4 or $mes_actual == 6 or $mes_actual == 9 or $mes_actual == 11) {
                    $dias_mes_anterior = 30;
                }
                $dia_diferencia = $dia_diferencia + $dias_mes_anterior;
            }
        }if ($mes_diferencia < 0) {
            $ano_diferencia = $ano_diferencia - 1;
            $mes_diferencia = $mes_diferencia + 12;
            # Se valida si cumple un año se muestre año si es mayor de un año se muestre años
        }if ($ano_diferencia < 2) {
            //~ $ano_diferencia = $ano_diferencia . " Año";
            $ano_diferencia = $ano_diferencia;
        } else if ($ano_diferencia > 1) {
            //~ $ano_diferencia = $ano_diferencia . " Años";
            $ano_diferencia = $ano_diferencia;
        }if ($mes_diferencia < 2) {
            //~ $mes_diferencia = $mes_diferencia . " Mes";
            $mes_diferencia = $mes_diferencia;
        } else if ($mes_diferencia > 1) {
            //~ $mes_diferencia = $mes_diferencia . " Meses";
            $mes_diferencia = $mes_diferencia;
        }if ($dia_diferencia < 2) {
            //~ $dia_diferencia = $dia_diferencia . " Dia";
            $dia_diferencia = $dia_diferencia;
        } else if ($dia_diferencia > 1) {
            //~ $dia_diferencia = $dia_diferencia . " Dias";
            $dia_diferencia = $dia_diferencia;
        }
        
        // Actualizamos el estado si es necesario (ya ya ha vencido)
        if($ano_diferencia > 0 || $mes_diferencia > 0 || $dia_diferencia >5){
			//~ echo "Vencido";
			$data_presupuesto = array(
                'codpresupuesto' => $codigo,
                'estado' => 2,
                'fecha_emision' => $this->input->post('fecha_emision'),
                //~ 'hora_emision' => date("h:i:s a"),
                //~ 'fecha_vencimiento' => $this->input->post('fecha_vencimiento'),
			);
			
			// Ejecutamos la actualización
			$result = $this->ModelsPresupuesto->actualizarPresupuesto($data_presupuesto);
		
		}
        
        $time_emision = str_replace("-", "", $ano_diferencia) . "," . $mes_diferencia . "," . $dia_diferencia;
        //echo "TIEMPO DE SERVICIO: ".$time_service;
        //~ echo json_encode($time_emision);
        echo $time_emision;
    }
    
    
    function pdf_presupuesto($cod)
    {
        $data['presupuesto'] = $this->ModelsPresupuesto->obtenerPresupuestoCod($cod);  // Datos generales del presupuesto
        $data['cliente'] = $this->ModelsPresupuesto->obtenerClienteCod($data['presupuesto']->codcliente);  // Datos del cliente
        $data['impuesto'] = $this->ModelsBusqueda->obtenerRegistro('impuesto', 'id', $data['presupuesto']->iva);  // Datos del cliente
        //~ print_r($data['presupuesto']);
        //~ echo $data['presupuesto']->codpresupuesto;
        $data['productos_servicios'] = $this->ModelsPresupuesto->obtenerProdustosServicios($data['presupuesto']->codpresupuesto);  // Productos asociados al presupuesto
        //~ foreach ($data['productos_servicios'] as $campos){
			//~ print_r($campos);
		//~ }
        
        $this->load->view('presupuesto/pdf/reporte_presupuesto', $data);
    }
}
