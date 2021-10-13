<?php

namespace App\Api\Controllers;

use Dingo\Api\Exception\ValidationHttpException;
use Dingo\Api\Http\Request;
use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    use Helpers, ValidatesRequests;

    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);
        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors());
        }
    }
}
