<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;

class UserController extends BaseApiController
{
    public function profile()
    {
        return $this->execute(function () {

            return $this->successResponse(
                'Profile fetched',
                $this->authenticatedUser()
                    ->publicData()
            );
        });
    }
}
