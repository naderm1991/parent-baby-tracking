<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BabyResource;
use App\Models\Baby;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BabyController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $babies = Baby::all();
        return $this->sendResponse(BabyResource::collection($babies), 'Products retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'parent_id'=>'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $baby = Baby::create($input);
        $parents[] = $input['parent_id'];
        $baby->users()->attach($parents);

        return $this->sendResponse(new BabyResource($baby), 'Product created successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, Baby $baby): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $baby->name = $input['name'];
        $baby->save();

        return $this->sendResponse(new BabyResource($baby), 'Product updated successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $baby = Baby::find($id);

        if (is_null($baby)) {
            return $this->sendError([], ['error'=>'Baby not found.']);
        }

        return $this->sendResponse(new BabyResource($baby), 'Product retrieved successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(Request $request,Baby $baby)
    {
        $input = $request->all();
        $parents_ids = [];
        $validator = Validator::make($input, [
            'parent_id' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $babies = Baby::find($baby->id);
        foreach ($babies->users as $user) {
            $parents_ids[] =$user->id;
        }

        if (in_array($input['parent_id'], $parents_ids)) {
            $baby->delete();
            return $this->sendResponse([], 'Product deleted successfully.');
        }
        return $this->sendResponse([], 'Wrong parent ID');

    }
}
