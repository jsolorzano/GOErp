<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsMunicipio extends CI_Model
{

    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }

    public function obtenerMunicipios()
    {
		$query = $this->db->order_by("municipio", "asc");
        $query = $this->db->get('municipios');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

    // Metodo publico, forma de insertar los datos
    public function insertar($datos)
    {

        $result = $this->db->insert("municipios", $datos);
        return $result;
    }

    public function obtenerMunicipio($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('municipios');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

    public function actualizarMunicipio($datos)
    {

        $result = $this->db->where('id', $datos['id']);
        $result = $this->db->update('municipios', $datos);
        return $result;
    }

    public function eliminarMunicipio($id)
    {
        $result = $this->db->where('municipio =', $id);
        $result = $this->db->get('parroquias');
        
        if ($result->num_rows() > 0) {
            echo 'existe';
        } else {
            $result = $this->db->delete('municipios', array('cod_municipio' => $id));
            return $result;
        }
    }

}
