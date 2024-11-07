<?php

namespace App\src\Contoller\Common;

use App\Controller\Controller;
use App\src\Model\User\User;

class GetController extends Controller
{
    public function index()
    {
        $params = $this->getParams();

        $users = $this->database->select("users", $params);

        if (empty($users)) {
            echo json_encode([
                'success' => false,
                "result" => [
                    "error" => "No users found"
                ]
            ]);

            return;
        }

        if ($users) {
            echo json_encode([
                'success' => true,
                "result" => [
                    "users" => $users
                ]
            ]);
        }
        else{
            echo json_encode([
                'success' => false,
                "result" => [
                    "error" => "Error"
                ]
            ]);
        }
    }

    public function user(){
        $userId = $this->getId();

        $user = new User(['id'=>$userId]);

        if (!$user) {
            echo json_encode([
                'success' => false,
                "result" => [
                    "error" => "No users found"
                ]
            ]);

            return;
        }

        echo json_encode([
            'success' => true,
            "result" => [
                "users" => $user->get()
            ]
        ]);

    }

    private function getParams(){
        $params = [];

        if ($this->request->input('role')){
            $params['role'] = $this->request->input('role');
        }
        if ($this->request->input('full_name')){
            $params['full_name'] = $this->request->input('full_name');
        }
        if ($this->request->input('efficiency')){
            $params['efficiency'] = $this->request->input('efficiency');
        }

        return $params;
    }
}