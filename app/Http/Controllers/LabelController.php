<?php

namespace App\Http\Controllers;

use App\Http\Requests\LabelRequest;
use App\Models\Label;
use Symfony\Component\HttpFoundation\Response;

class LabelController extends Controller
{

    public function index(){
        return auth()->user()->labels;
    }

    public function store(LabelRequest $request)
    {
        return auth()->user()->labels()->create($request->validated());
    }

    public function destroy(Label $label)
    {
        $statusCode = Response::HTTP_OK;
        if (intval(auth()->user()->id) === intval($label->user_id) && $label->delete())
            $statusCode = Response::HTTP_NO_CONTENT;
        return response([], $statusCode);
    }

    public function update(Label $label, LabelRequest $request)
    {
        if (intval(auth()->user()->id) === intval($label->user_id))
            return $label->update($request->validated());
        return response([]);
    }

}
