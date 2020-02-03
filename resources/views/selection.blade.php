@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Select School</div>

                    <div class="panel-body">
                        @foreach($tenants as $tenant)
                            <p>
                                <a class="btn btn-link" href="{{ url('login/tenant/'.$tenant['id']) }}">{{$tenant['name']}}</a>
                            </p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection