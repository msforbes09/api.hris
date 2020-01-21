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
        return Key::with('keywords')->get();
    }

    public function show(Key $key, Keyword $keyword)
    {
        abort(404);
    }

    public function store(KeywordRequest $request)
    {
        $key = Key::where('id', request('key'))->first();

        $keyword = $key->keywords()->create($request->only('value'));

        return response()->json([
            'message' => 'Successfuly created key keyword.',
            'keyword' => $keyword
        ]);
    }

    public function update(KeywordRequest $request, Keyword $keyword)
    {
        $keyword->update($request->only('value'));

        return response()->json([
            'message' => 'Successfully updated key keyword.',
            'keyword' => $keyword
        ]);
    }

    public function destroy(Keyword $keyword)
    {
        $keyword->delete();

        return [
            'message' => 'Successfully deleted key keyword.'
        ];
    }
}
