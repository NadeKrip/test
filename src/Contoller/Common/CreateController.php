<?php

namespace App\src\Contoller\Common;

use App\Controller\Controller;
use App\src\Model\User\User;

class CreateController extends Controller
{
    public function index()
    {
        $data = $this->getData();

        $user = new User();
        $user->edit($data);
        $result = $user->save();

        if ($result) {
            echo json_encode([
                'success' => true,
                "result" => [
                    "id" => $user->id()
                ]
            ]);
        }
        else{
            echo json_encode([
                'success' => false,
                "result" => [
                    "error" => $user->error()
                ]
            ]);
        }
    }

    private function getData()
    {
        if (empty($_POST)){
            $json = file_get_contents("php://input");
            $data = json_decode($json);

            return[
                'full_name' => $data->full_name,
                'role' => $data->role,
                'efficiency' => $data->efficiency
            ];
        }

        return [
            'full_name' => $this->request->input('full_name'),
            'role' => $this->request->input('role'),
            'efficiency' => $this->request->input('efficiency')
        ];
    }
}