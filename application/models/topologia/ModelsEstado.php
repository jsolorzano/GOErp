<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsEstado extends CI_Model
{

    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }

    public function obtenerEstados()
    {
		$query = $this->db->order_by("estado", "asc");
        $query = $this->db->get('estados');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

    // Metodo publico, forma de insertar los datos
    public function insertar($datos)
    {

        $result = $this->db->insert("estados", $datos);
        return $result;
    }

    public function obtenerEstado($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('estados');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

    public function actualizarEstado($datos)
    {

        $result = $this->db->where('id', $datos['id']);
        $result = $this->db->update('estados', $datos);
        return $result;
    }

    public function eliminarEstado($id)
    {
        $result = $this->db->where('estado_id =', $id);
        $result = $this->db->get('municipios');

        #echo print_r($result);
        #return true;

        if ($result->num_rows() > 0) {
            #echo "CORRECTO";
            echo 'existe';
        } else {
            #$result = $this->db->delete('estados', array('id' => $id));
            $result = $this->db->where('cod_estado', $id);
            $result = $this->db->delete('estados');
            return $result;
            #return $result;
        }
    }

}
