@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit User</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('users.update', $user->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name"
                                        value="{{ $user->name }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email"
                                        value="{{ $user->email }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="mobile" class="col-md-4 col-form-label text-md-right">Mobile</label>
                                <div class="col-md-6">
                                    <input id="mobile" type="text" class="form-control" name="mobile"
                                        value="{{ $user->mobile }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="isactive" class="col-md-4 col-form-label text-md-right">Active</label>
                                <div class="col-md-6">
                                    <select name="isactive" id="isactive" class="form-control">
                                        <option value="1" {{ $user->isactive ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ !$user->isactive ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Update User
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
