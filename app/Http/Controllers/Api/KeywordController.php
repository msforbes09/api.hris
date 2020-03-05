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
        $this->authorize('allows', [$this->module, 'view']);

        return Key::with(['keywords' => function($query) {
            return $query->orderBy('value', 'asc');
        }])->orderBy('name', 'asc')->get();
    }

    public function show(Key $key, Keyword $keyword)
    {
        abort(404);
    }

    public function store(KeywordRequest $request)
    {
        $this->authorize('allows', [$this->module, 'create']);

        $key = Key::where('id', request('key'))->first();

        $keyword = $key->keywords()->create($request->only('value'));

        return response()->json([
            'message' => 'Successfuly created keyword.',
            'keyword' => $keyword
        ]);
    }

    public function update(KeywordRequest $request, Keyword $keyword)
    {
        $this->authorize('allows', [$this->module, 'update']);

        $keyword->update($request->only('value'));

        return response()->json([
            'message' => 'Successfully updated keyword.',
            'keyword' => $keyword
        ]);
    }

    public function destroy(Keyword $keyword)
    {
        $this->authorize('allows', [$this->module, 'delete']);

        $keyword->delete();

        return [
            'message' => 'Successfully deleted keyword.'
        ];
    }
}
