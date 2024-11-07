<?php

namespace App\src\Contoller\Common;

use App\Controller\Controller;
use App\src\Model\User\User;

class DeleteContoller extends Controller
{
    public function index()
    {
        $userId = $this->getId();

        $user = new User(['id' => $userId]);
        $userData = $user->get();

        $stmt = $this->database->delete('users', ['id' => $userId]);

        if ($stmt) {
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
                    "error" => "Error deleting user"
                ]
            ]);
        }
    }
}