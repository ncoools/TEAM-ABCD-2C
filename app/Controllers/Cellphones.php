<?php

namespace App\Controllers;

use App\Models\CellphonesModel;
use CodeIgniter\Controller;
use App\Models\LogModel;

class Cellphones extends Controller
{
    public function index(){
        $model = new CellphonesModel();
        $data['cellphones'] = $model->findAll();
        return view('cellphones/index', $data);
    }

    public function save(){
        $name = $this->request->getPost('name');
        $brand = $this->request->getPost('brand');

        $animalModel = new \App\Models\CellphonesModel();
        $logModel = new LogModel();

        $data = [
            'name'       => $name,
            'brand'      => $brand,
            'updated_at' => date('Y-m-d H:i:s'),
            'deleted_at' => date('Y-m-d H:i:s')
        ];

        if ($animalModel->insert($data)) {
            $logModel->addLog('New User has been added: ' . $name, 'ADD');
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save user']);
        }
    }

    public function update(){
        $model = new CellphonesModel();
        $logModel = new LogModel();
        $userId = $this->request->getPost('id');
        $name = $this->request->getPost('name');
        $brand = $this->request->getPost('brand');

        $userData = [
            'name'       => $name,
            'brand'      => $brand,
            'updated_at' => date('Y-m-d H:i:s'),
            'deleted_at' => date('Y-m-d H:i:s')
        ];

        if (!empty($password)) {
            $userData['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $updated = $model->update($userId, $userData);

        if ($updated) {
            $logModel->addLog('New User has been apdated: ' . $name, 'UPDATED');
            return $this->response->setJSON([
                'success' => true,
                'message' => 'User updated successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating user.'
            ]);
        }
    }

    public function edit($id){
        $model = new CellphonesModel();
    $user = $model->find($id); // Fetch user by ID

    if ($user) {
        return $this->response->setJSON(['data' => $user]); // Return user data as JSON
    } else {
        return $this->response->setStatusCode(404)->setJSON(['error' => 'User not found']);
    }
}

public function delete($id){
    $model = new CellphonesModel();
    $logModel = new LogModel();
    $user = $model->find($id);
    if (!$user) {
        return $this->response->setJSON(['success' => false, 'message' => 'User not found.']);
    }

    $deleted = $model->delete($id);

    if ($deleted) {
        $logModel->addLog('Delete user', 'DELETED');
        return $this->response->setJSON(['success' => true, 'message' => 'User deleted successfully.']);
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete user.']);
    }
}

public function fetchRecords()
{
    $request = service('request');
    $model = new \App\Models\CellphonesModel();

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