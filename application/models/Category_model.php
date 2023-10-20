<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {
  
    /**
     * CONSTRUCTOR | LOAD DB
    */
    public function __construct() {
       parent::__construct();
       $this->load->database();
    }

    /**
     * SHOW | GET method.
     *
     * @return Response
    */
	public function show(?string $id = null)
	{
        if(!empty($id)){
            $query = $this->db->get_where("categories", ['id' => $id])->row_array()??[];
        }else{
            $query = $this->db->get("categories")->result();
        }
        return $query;
	}
      
    /**
     * INSERT | POST method.
     *
     * @return Response
    */
    public function insert($data)
    {
        $this->db->insert('categories',$data);
        return $this->db->insert_id(); 
    } 
     
    /**
     * UPDATE | PUT method.
     *
     * @return Response
    */
    public function update($data, $id)
    {
        $data = $this->db->update('categories', $data, array('id'=>$id));
        //echo $this->db->last_query();
		return $this->db->affected_rows();
    }
     
    /**
     * DELETE method.
     *
     * @return Response
    */
    public function delete ($id)  {
        $this->db->delete('categories', array('id'=>$id));
        return $this->db->affected_rows();
    }
}
