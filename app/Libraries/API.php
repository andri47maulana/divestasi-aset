<?php
namespace App\Libraries;

use CodeIgniter\HTTP\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class API
{
    use ResponseTrait;

    protected $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function fetchData($table, $select, $filter) {
        $role = session()->get('role_id');

        if(!$role) {
            return $this->response->setContentType('application/json') 
                ->setStatusCode(401)
                ->setJSON(['error' => 'Access denied', 'data' => []]);
        }

        try {
            $builder = $table->select($select);
                
            if($filter) {
                $builder->where($filter);
            }
            $data = $builder->get()->getResult();

            return $this->response->setContentType('application/json') 
                ->setStatusCode(200)
                ->setJSON(['data' => $data]);
        } catch (Exception $e) {
            return $this->response->setContentType('application/json') 
                ->setStatusCode(500)
                ->setJSON(['error' => $e->getMessage(), 'data' => []]);
        }
    }

    public function postData($table, $data) {
        $role = session()->get('role_id');

        if(!$role) {
            return $this->response->setContentType('application/json') 
                ->setStatusCode(401)
                ->setJSON(['error' => 'Access denied', 'data' => []]);
        }

        try {
             $builder =$table->insert($data);

            return $this->response->setContentType('application/json') 
                ->setStatusCode(200)
                ->setJSON(['message' => `Berhasil menambah data`]);
        } catch (Exception $e) {
            return $this->response->setContentType('application/json') 
                ->setStatusCode(500)
                ->setJSON(['error' => $e->getMessage(), 'data' => []]);
        }
    }

    public function patchData($table, $filter, $data) {
        $role = session()->get('role_id');

        if(!$role) {
            return $this->response->setContentType('application/json') 
                ->setStatusCode(401)
                ->setJSON(['error' => 'Access denied', 'data' => []]);
        }

        try {
            $table->where($filter)->set($data)->update();

            return $this->response->setContentType('application/json') 
                ->setStatusCode(200)
                ->setJSON(['message' => `Berhasil mengupdate data`]);
        } catch (Exception $e) {
            return $this->response->setContentType('application/json') 
                ->setStatusCode(500)
                ->setJSON(['error' => $e->getMessage(), 'data' => []]);
        }
    }

    public function deleteData($table, $filter) {
        $role = session()->get('role_id');

        if(!$role) {
            return $this->response->setContentType('application/json') 
                ->setStatusCode(401)
                ->setJSON(['error' => 'Access denied', 'data' => []]);
        }

        try {
            $table->where($filter)->delete();

            return $this->response->setContentType('application/json') 
                ->setStatusCode(200)
                ->setJSON(['message' => `Berhasil menghapus data`]);
        } catch (Exception $e) {
            return $this->response->setContentType('application/json') 
                ->setStatusCode(500)
                ->setJSON(['error' => $e->getMessage(), 'data' => []]);
        }
    }
}
?>