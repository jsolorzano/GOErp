<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsClientes extends CI_Model
{

    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }

    public function obtenerClientes()
    {
		// NUEVA FORMA
		$query = $this->db->select('c.id, c.codigo, t.tipo_cliente, c.cirif, c.nombre, c.direccion, c.tlf, c.estatus, c.tipocliente');
		$query = $this->db->from('cliente c');
		$query = $this->db->join('tipo_cliente t', 't.cod_tipo = c.tipo_cliente');
		$query = $this->db->order_by("id", "desc");
		$query = $this->db->get();
		
        //~ $query = $this->db->get('cliente'); FORMA ANTERIOR                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

    // Metodo publico, forma de insertar los datos
    public function insertar($data,$datos)
    {
        $result = $this->db->where('cirif =', $data['cirif']);
        $result = $this->db->get('cliente');

        if ($result->num_rows() > 0) {
            #echo "CORRECTO";
            echo '1';
        } else {
            //~ $result = $this->db->insert("contacto", $datos);
            $result = $this->db->insert("cliente", $data);
            return $result;
        }
        
    }
    
    public function obtenerCliente($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('cliente');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    public function actualizarCliente($datos)
    {
        $result = $this->db->where('id', $datos['id']);
        $result = $this->db->update('cliente', $datos);
        return $result;
    }
    
    //~ public function actualizarContacto($datos)
    //~ {
//~ 
        //~ $result = $this->db->where('idcontacto', $datos['idcontacto']);
        //~ $result = $this->db->update('contacto', $datos);
        //~ return $result;
    //~ }

    public function eliminarCliente($id)
    {
        // Primero buscamos si existe un cliente asociado a la factura que queremos eliminar
		$result = $this->db->where('codcliente =', $id);
        $result = $this->db->get('facturas');
        
        if ($result->num_rows() > 0) {
            echo 'existe';
        } else {
			// Procedemos a borrar el cliente si no está asociado a una factura
            $result = $this->db->delete('cliente', array('codigo'=>$id));
            //~ $result = $this->db->delete('contacto', array('idcontacto'=>$id));
            return $result;
        }
        
    }
    


}
