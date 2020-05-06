@extends('layouts.app')

@section('content')
<div class="flex flex-col break-words bg-white border border-2 rounded shadow-md w-1/2 mx-auto">

    <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
        Select School
    </div>

    <div class="w-full p-6">
        <p class="text-gray-700">
            <form class="form-horizontal " method="POST" action="{{ route('tenant.select') }}">
                @csrf
                @foreach($tenants as $tenant)
                    <p>
                        <button type="submit" name="tenant" value="{{$tenant['uuid']}}">
                            {{$tenant['name']}}
                        </button>
                    </p>
                @endforeach
            </form>
        </p>
    </div>
</div>
@endsection