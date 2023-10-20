<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {
  
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
	public function show($id = 0)
	{
        if(!empty($id)){
            $query = $this->db->get_where("products", ['id' => $id])->row_array();
        }else{
            $query = $this->db->get("products")->result();
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
        $this->db->insert('products',$data);
        $res = $this->db->get_where('products',['id' => $this->db->insert_id()],1)->result();
        return $res; 
    } 
     
    /**
     * UPDATE | PUT method.
     *
     * @return Response
    */
    public function update($data, $id)
    {
        $data = $this->db->update('products', $data, array('id'=>$id));
		return $this->db->affected_rows();
    }
     
    /**
     * DELETE method.
     *
     * @return Response
    */
    public function delete($id)
    {
        $this->db->delete('products', array('id'=>$id));
        return $this->db->affected_rows();
    }

    public function rules(){
        return [
            [
              "field" => "name",
              "label" => "Product Name",
              "rules" => "required"
            ],
            [
              "field" => "category_id",
              "label" => "Product Category",
              "rules" => "required|integer"
            ],
            [
              "field" => "price",
              "label" => "Product Price",
              "rules" => "required|numeric|less_than_equal_to[99999999.99]"
            ],
            [
              "field" => "quantity",
              "label" => "Product Quantity",
              "rules" => "integer"
            ],
        ];
    }
}
