<?php


require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Categories extends REST_Controller
{

    /**
     * CONSTRUCTOR | LOAD MODEL
     *
     * @return Response
     */

    private $decodedToken;
    public function __construct()
    {
        
        parent::__construct();
        $this->load->library('Authorization_Token');
        $this->load->library("form_validation");
        $this->load->model('Category_model');

        $headers = $this->input->request_headers();
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken();
            if ($decodedToken['status']) {
                // ------- Main Logic part -------
                $this->decodedToken = $decodedToken;
                // ------------- End -------------
            } else {
                $this->response($decodedToken);
            }
        } else {
            echo "ci_kontol";
            $this->response(['Authentication failed'], REST_Controller::HTTP_OK);
        }
    }

    /**
     * SHOW | GET method.
     *
     * @return Response
     */
    public function index_get(?string $id = null )
    {
        $categories = $this->Category_model->show($id);
        $this->response([
            "message" => "Succes Get Categories",
            "data" =>  $categories,
        ], REST_Controller::HTTP_OK);
    }

    /**
     * INSERT | POST method.
     *
     * @return Response
     */
    public function index_post()
    {
        $this->form_validation->set_rules('name', 'Category Name', 'trim|required|min_length[2]|is_unique[categories.name]', array('is_unique' => 'This Category Name already used. Please choose another one.'));
        if( $this->form_validation->run() == FALSE ){
            $this->response(array('errors'=> validation_errors()),REST_Controller::HTTP_UNPROCESSABLE_ENTITY);
        }else{
            $res =$this->Category_model->insert([
                'name'=> $this->input->post('name'),
            ]);
            if($res){
                $record = $this->db->get_where('categories', ['id' => $res])->result()[0];
                $this->response( [
                    "message" => "success create category",
                    "data"=> $record
                ] , REST_Controller::HTTP_CREATED);
            }else{
                $this->response( [
                    "message" => "failed create category",
                ] , 500);
            }
           
        }
    }

    /**
     * UPDATE | PUT method.
     *
     * @return Response
     */
    public function index_put($id)
    {

        $this->form_validation->reset_validation();
        $category = $this->Category_model->show($id);
        if(!$category){
            return $this->response([
                "message" => "The category with the id $id doesn't exist"
            ], REST_Controller::HTTP_NOT_FOUND);
        }
        $this->form_validation->set_data($this->put());
        $this->form_validation->set_rules('name', 'name', 'required', array('is_unique' => 'This Category Name already used. Please choose another one.'));
        if( $this->form_validation->run() == FALSE ){
            $this->response(array('errors'=> validation_errors()),REST_Controller::HTTP_UNPROCESSABLE_ENTITY);
        }else{
            if($this->Category_model->update([
                'name'=> $this->put('name')
            ], $id)){
                $this->response([
                    'message'=> 'Berhasil Update Category',
                ], REST_Controller::HTTP_OK);
            }else{
                var_dump($this->db->error());
                $this->response([
                    'message'=> 'Gagal Update Category',
                ], 500);
            }

        }
    }

    /**
     * DELETE method.
     *
     * @return Response
     */
    public function index_delete($id)
    {
        if($this->Category_model->delete($id)){
            return $this->response([
                "message" => "Berhasil menghapus Category"
            ],200);
        }

        return $this->response([
            "message" => "Gagal menghapus Category"
        ],500);
    }
}
