<?php

namespace App\src\Contoller\Common;

use App\Controller\Controller;
use App\src\Model\User\User;

class UpdateContoller extends Controller
{
    public function index()
    {
        $returnData = $this->getData();
        $userId = $this->getId();

        $user = new User(['id' => $userId]);

        $user->edit($returnData);

        $result = $user->save();

        $userData = $user->get();

        if ($result) {
            echo json_encode([
                'success' => true,
                "result" => [
                    "id" => $user->id(),
                    'full_name' => $userData['full_name'],
                    'role' => $userData['role'],
                    'efficiency' => $userData['efficiency']
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
        $json = file_get_contents("php://input");
        $data = json_decode($json);

        $returnData = [];

        if(isset($data->full_name))
            $returnData['full_name'] = $data->full_name;

        if(isset($data->role))
            $returnData['role'] = $data->role;

        if(isset($data->efficiency))
            $returnData['efficiency'] = $data->efficiency;


        return $returnData;

    }
}