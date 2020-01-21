@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center" style="height: 100vh">
        <iframe src="{{ route('telescope') }}" frameborder="0" style="overflow:hidden;height:100%;width:100%" height="100%" width="100%"></iframe>
    </div>
</div>
@endsection
