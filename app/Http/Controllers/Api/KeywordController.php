<?php

namespace App\Http\Controllers\Api;

use App\Key;
use App\Keyword;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\KeywordRequest;

class KeywordController extends Controller
{
    public function index()
    {
        abort(403);
    }

    public function store(KeywordRequest $request, Key $key)
    {
        $keyword = $key->keywords()->create(request()->toArray());

        return response()->json([
            'message' => 'Successfuly created key keyword.',
            'keyword' => $keyword
        ]);
    }

    public function update(KeywordRequest $request, Key $key, Keyword $keyword)
    {
        $keyword->fill(request()->toArray());

        $keyword->save();

        return response()->json([
            'message' => 'Successfully updated key keyword.',
            'keyword' => $keyword
        ]);
    }

    public function destroy(Key $key, Keyword $keyword)
    {
        $keyword->delete();

        return [
            'message' => 'Successfully deleted key keyword.'
        ];
    }
}
