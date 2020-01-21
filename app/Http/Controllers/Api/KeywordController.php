<?php

namespace App\Http\Controllers\Api;

use App\Key;
use App\Module;
use App\Keyword;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\KeywordRequest;

class KeywordController extends Controller
{
    protected $module;

    public function __construct()
    {
        $this->module = Module::where('code', 'keyword')->first();
    }

    public function index()
    {
        abort(403);
    }

    public function show(Key $key, Keyword $keyword)
    {
        $this->authorize('show', $this->module);

        return $keyword;
    }

    public function store(KeywordRequest $request, Key $key)
    {
        $this->authorize('create', $this->module);

        $keyword = $key->keywords()->create(request()->toArray());

        return response()->json([
            'message' => 'Successfuly created key keyword.',
            'keyword' => $keyword
        ]);
    }

    public function update(KeywordRequest $request, Key $key, Keyword $keyword)
    {
        $this->authorize('update', $this->module);

        $keyword->fill(request()->toArray());

        $keyword->save();

        return response()->json([
            'message' => 'Successfully updated key keyword.',
            'keyword' => $keyword
        ]);
    }

    public function destroy(Key $key, Keyword $keyword)
    {
        $this->authorize('destroy', $this->module);

        $keyword->delete();

        return [
            'message' => 'Successfully deleted key keyword.'
        ];
    }
}
