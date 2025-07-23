@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            Report for {{ $farmer->name }}
        </div>
        <div class="card-body">
            {{-- Display specific report details --}}
            <p>Reports will be displayed here.</p>
        </div>
    </div>
@endsection