<?php

namespace App\Http\Controllers\API;

use App\Classes\Validation;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends BaseController
{
    public function addParent(Request $request): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input['password'] = "";
        $user = User::create($input);
        $success['token'] =  $user->createToken('ParentBabyTracker')->plainTextToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        if(User::checkAvailability($request->parent_id, $request->name)){
            Auth::loginUsingId($request->parent_id);
            $user = Auth::user();
            $success['token'] =  $user->createToken('ParentBabyTracker')->plainTextToken;
            $success['name'] =  $user->name;
            $success['partner_id'] =  $user->partner_id;

            return $this->sendResponse($success, 'User login successfully.');
        }
        else{
            return $this->sendError('Not found.', ['error'=>'Not found']);
        }
    }

    public function inviteParent(Request $request): JsonResponse
    {
        $input = $request->all();
        $validator = (new Validation())->validate($request);
        if($validator->fails()){
            return $this->sendError('Validation Error.', (array)$validator->errors());
        }

        $user = User::find($input['parent_id']);

        // validate the added partner
        if (! $user->partner_id){
            $user->partner_id = $input['partner_id'];
            $user->save();
        }else{
            return $this->sendResponse([], 'Already added before.');
        }

        return $this->sendResponse([], 'User register successfully.');
    }

    public function showThePartner(Request $request): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'partner_id' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user = User::find($input['partner_id']);

        return $this->sendResponse(new UserResource($user), 'Product retrieved successfully.');
    }
}
