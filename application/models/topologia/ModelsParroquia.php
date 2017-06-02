<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsParroquia extends CI_Model
{

    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }

    public function obtenerParroquias()
    {
		$query = $this->db->order_by("parroquia", "asc");
        $query = $this->db->get('parroquias');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

    // Metodo publico, forma de insertar los datos
    public function insertar($datos)
    {

        $result = $this->db->insert("parroquias", $datos);
        return $result;
    }

    public function obtenerParroquia($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('parroquias');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

    public function actualizarParroquia($datos)
    {

        $result = $this->db->where('id', $datos['id']);
        $result = $this->db->update('parroquias', $datos);
        return $result;
    }

    public function eliminarParroquia($id)
    {
        $result = $this->db->delete('parroquias', array('cod_parroquia' => $id));
        return $result;
    }

}
