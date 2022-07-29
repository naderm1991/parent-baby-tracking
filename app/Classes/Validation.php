<?php

namespace App\Classes;

use Illuminate\Support\Facades\Validator;

class Validation
{
    public function validate($request): \Illuminate\Contracts\Validation\Validator
    {
        $input = $request->all();

        return Validator::make($request->all(), [
            'parent_id'=>'required',
            'partner_id'=>'required'
        ]);
    }
}
