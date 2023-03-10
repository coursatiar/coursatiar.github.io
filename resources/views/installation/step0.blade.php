@extends('layouts.blank')
@section('content')
    <div class="container">
        <div class="row pt-5">
            <div class="col-md-12">
                @if(session()->has('error'))
                    <div class="alert alert-danger" role="alert">
                        {{session('error')}}
                    </div>
                @endif
                <div class="mar-ver pad-btm text-center">
                    <h1 class="h3">6POS Software Installation</h1>
                    <p>Provide information which is required.</p>
                </div>
                <ol class="list-group">
                    <li class="list-group-item text-semibold"><i class="fa fa-check"></i> Database Name</li>
                    <li class="list-group-item text-semibold"><i class="fa fa-check"></i> Database Username</li>
                    <li class="list-group-item text-semibold"><i class="fa fa-check"></i> Database Password</li>
                    <li class="list-group-item text-semibold"><i class="fa fa-check"></i> Database Hostname</li>
                </ol>
                <p class="pt-5 p-one-s1">
                    We will check permission to write several files,proceed..
                </p>
                <br>
                <div class="text-center">
                    <a href="{{ route('step1',['token'=>bcrypt('step_1')]) }}" class="btn btn-info text-light">
                        Ready ? Then start <i class="fa fa-forward"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
