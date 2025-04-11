<?php
namespace App\Libraries;

use CodeIgniter\HTTP\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class API_PembayaranDenda
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
        $paket_kerjasama = session()->get('paket_kerjasama_id');

        if(!$role) {
            return $this->response->setContentType('application/json') 
                ->setStatusCode(401)
                ->setJSON(['error' => 'Access denied', 'data' => []]);
        }

        try {
            $table->insert($data);
            
            $db = db_connect();

            $query = $db->table('persetujuanfinal_denda')
            ->where('persetujuanfinal_denda_paket_kerjasama_id',  $paket_kerjasama) 
            ->select('SUM(persetujuanfinal_denda_nominal_denda) as total_sum')
            ->get();

            $result = $query->getRow();

            // Mengambil nilai SUM dari hasil query
            $totalSum = $result->total_sum;
    
            // Query untuk melakukan edit atau update data di tabel tujuan
            $db->table('paket_kerjasama')
               ->set('paket_kerjasama_total_denda', $totalSum)
               ->where('paket_kerjasama_id',  $paket_kerjasama) // Misalnya data dengan id = 1 yang akan diedit
               ->update();

        // Dump hasil query menggunakan var_dump()
            // var_dump($resultArray);
    
            // dd($resultArray);

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
        $paket_kerjasama = session()->get('paket_kerjasama_id');

        if(!$role) {
            return $this->response->setContentType('application/json') 
                ->setStatusCode(401)
                ->setJSON(['error' => 'Access denied', 'data' => []]);
        }

        try {
            $table->where($filter)->set($data)->update();

            $db = db_connect();

            $query = $db->table('persetujuanfinal_denda')
            ->where('persetujuanfinal_denda_paket_kerjasama_id',  $paket_kerjasama) 
            ->select('SUM(persetujuanfinal_denda_nominal_denda) as total_sum')
            ->get();

            $result = $query->getRow();

            // Mengambil nilai SUM dari hasil query
            $totalSum = $result->total_sum;
    
            // Query untuk melakukan edit atau update data di tabel tujuan
            $db->table('paket_kerjasama')
               ->set('paket_kerjasama_total_denda', $totalSum)
               ->where('paket_kerjasama_id',  $paket_kerjasama) // Misalnya data dengan id = 1 yang akan diedit
               ->update();

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
        $paket_kerjasama = session()->get('paket_kerjasama_id');

        if(!$role) {
            return $this->response->setContentType('application/json') 
                ->setStatusCode(401)
                ->setJSON(['error' => 'Access denied', 'data' => []]);
        }

        try {
            $table->where($filter)->delete();

            $db = db_connect();

            $query = $db->table('persetujuanfinal_denda')
            ->where('persetujuanfinal_denda_paket_kerjasama_id',$paket_kerjasama) 
            ->select('SUM(persetujuanfinal_denda_nominal_denda) as total_sum')
            ->get();

            $result = $query->getRow();

            // Mengambil nilai SUM dari hasil query
            $totalSum = $result->total_sum;
    
            // Query untuk melakukan edit atau update data di tabel tujuan
            $db->table('paket_kerjasama')
               ->set('paket_kerjasama_total_denda', $totalSum)
               ->where('paket_kerjasama_id',  $paket_kerjasama) // Misalnya data dengan id = 1 yang akan diedit
               ->update();

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