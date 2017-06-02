<?php

class ModelsBusqueda extends CI_Model
{
    //put your code here}
    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }
    
    // Método publico para traer las lineas estrategicas segun la asociacion con el plan de la nacion
    public function search($campo, $table, $id)
    {
        $result = $this->db->where($campo . '=', $id);
        $result = $this->db->get($table);
        return $result->result();
    }
    
    // Método publico para traer el objetivo nacional segun la asociacion con el plan de la patria y el objetivo Historico
    public function search_multiple_two($campoA, $campoB, $table, $idA,$idB)
    {
        $result = $this->db->where($campoA . '=', $idA);
        $result = $this->db->where($campoB . '=', $idB);
        $result = $this->db->get($table);
        return $result->result();
    }
    
    
    // Método publico para traer el objetivo nacional segun la asociacion con el plan de la patria y el objetivo Historico
    public function search_multiple_three($campoA, $campoB, $campoC, $table, $idA,$idB,$idC)
    {
        $result = $this->db->where($campoA . '=', $idA, $campoB . '=', $idB, $campoC . '=', $idC);
        $result = $this->db->get($table);
        return $result->result();
    }
    
    // Método público para consultar el id del último registro de la tabla especificada
    public function count_all_table($table)
    {
        $query = $this->db->limit(1)->order_by("id", "desc")->get($table);
        foreach ($query->result() as $row)
        {
                return $row->id;
        }
       
        
    }
    
    // Método público para consultar si ya existe un determinado registro en una tabla específica a través de su nombre
    public function existe_registro($table, $camp_nom, $nom)  // Argumentos: tabla, campo, valor
    {
        $result = $this->db->where(''.$camp_nom.' =', $nom);
        $result = $this->db->get($table);
        
        if ($result->num_rows() > 0) {
            echo 'existe';
        } else {
			echo 'no existe';
        }
        
    }
    
    // Método público para consultar un registro específico de una tabla y un campo dado
    public function obtenerRegistro($table, $campo, $valor)
    {
        $result = $this->db->where($campo . '=', $valor);
        $result = $this->db->get($table);
        return $result->row();
    }
    
}
