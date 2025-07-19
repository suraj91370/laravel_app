@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="mb-4">
                            <h4>Welcome, {{ auth()->user()->name }}!</h4>
                            <p>Your role: <strong>{{ auth()->user()->role->name }}</strong></p>
                        </div>

                        @if (auth()->user()->isAdmin() || auth()->user()->isManager())
                            <div class="mt-4">
                                <a href="{{ route('users.index') }}" class="btn btn-primary">
                                    Manage Users
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
