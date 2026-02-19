<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\FoodsModel;
use CodeIgniter\Controller;
use App\Models\LogModel;

class Foods extends Controller
{
    public function index(){
        $model = new FoodsModel();
        $data['foods'] = $model->findAll();
        return view('foods/index', $data);
    }

    public function save(){
        $name = $this->request->getPost('name');
        $price = $this->request->getPost('price');
        

        $userModel = new \App\Models\FoodsModel();
        $logModel = new LogModel();

        $data = [
            'name'       => $name,
            'price'       => $price,
            
        ];

        if ($userModel->insert($data)) {
            $logModel->addLog('New Foods has been added: ' . $name, 'ADD');
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save Profiling']);
        }
    }

    public function update(){
        $model = new FoodsModel();
        $logModel = new LogModel();
        $userId = $this->request->getPost('id');
        $name = $this->request->getPost('name');
        $price = $this->request->getPost('price');
        

        $userData = [
            'name'       => $name,
            'price'       => $price,
            
        ];

        $updated = $model->update($userId, $userData);

        if ($updated) {
            $logModel->addLog('New Foods has been apdated: ' . $name, 'UPDATED');
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Foods updated successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating Foods.'
            ]);
        }
    }

    public function edit($id){
        $model = new FoodsModel();
    $user = $model->find($id); // Fetch user by ID

    if ($user) {
        return $this->response->setJSON(['data' => $user]); // Return user data as JSON
    } else {
        return $this->response->setStatusCode(404)->setJSON(['error' => 'User not found']);
    }
}

public function delete($id){
    $model = new FoodsModel();
    $logModel = new LogModel();
    $user = $model->find($id);
    if (!$user) {
        return $this->response->setJSON(['success' => false, 'message' => 'Food not found.']);
    }

    $deleted = $model->delete($id);

    if ($deleted) {
        $logModel->addLog('Delete Food', 'DELETED');
        return $this->response->setJSON(['success' => true, 'message' => 'Food deleted successfully.']);
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete Food.']);
    }
}

public function fetchRecords()
{
    $request = service('request');
    $model = new \App\Models\FoodsModel();

    $start = $request->getPost('start') ?? 0;
    $length = $request->getPost('length') ?? 10;
    $searchValue = $request->getPost('search')['value'] ?? '';

    $totalRecords = $model->countAll();
    $result = $model->getRecords($start, $length, $searchValue);

    $data = [];
    $counter = $start + 1;
    foreach ($result['data'] as $row) {
        $row['row_number'] = $counter++;
        $data[] = $row;
    }

    return $this->response->setJSON([
        'draw' => intval($request->getPost('draw')),
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $result['filtered'],
        'data' => $data,
    ]);
}

}