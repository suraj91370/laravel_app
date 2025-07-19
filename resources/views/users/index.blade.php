    @extends('layouts.app')

    @section('content')
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Users Management</div>

                        <div class="card-body">
                            <table class="table table-bordered" id="users-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Role</th>
                                        <th>Active</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="mt-4">
            <h4>Debug Information:</h4>
            <p>Database values for isactive:</p>
            <ul>
                @foreach (App\Models\User::all() as $user)
                    <li>User {{ $user->id }}: {{ $user->isactive }} ({{ $user->isactive ? 'Active' : 'Inactive' }})</li>
                @endforeach
            </ul>
        </div> --}}
    @endsection


    @push('scripts')
        <script>
            $(function() {
                $('#users-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('users.data') }}",
                    pageLength: 5,
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'mobile',
                            name: 'mobile'
                        },
                        {
                            data: 'role.name',
                            name: 'role.name',
                            render: function(data) {
                                return data ? data : 'N/A';
                            }
                        },
                        {
                            data: 'isactive',
                            name: 'isactive',
                            // Remove any existing render function here
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    // Add this to debug each row
                    createdRow: function(row, data, dataIndex) {
                        console.log(`User ID: ${data.id}, isactive: ${data.isactive}`);
                        $(row).find('td:eq(5)').attr('data-debug', data.isactive);
                    }
                });
            });
        </script>
    @endpush

    @push('scripts')
        <script>
            $(document).on('click', '.delete-user', function() {
                const userId = $(this).data('id');

                if (!confirm('Are you sure you want to delete this user?')) {
                    return;
                }

                const url = "{{ route('users.destroy', ':id') }}".replace(':id', userId);
                const token = '{{ csrf_token() }}';

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: token
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#users-table').DataTable().ajax.reload(null, false);
                            alert(response.success);
                        } else if (response.error) {
                            alert(response.error);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while deleting the user.';

                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }

                        alert(errorMessage);
                    }
                });
            });
        </script>
    @endpush
{{-- 
    @push('scripts')
        <div class="mt-4 debug-card">
            <div class="card">
                <div class="card-header">Delete Debug Information</div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Current User ID:</strong> {{ auth()->id() }}
                    </div>
                    <div class="mb-3">
                        <strong>CSRF Token:</strong> {{ csrf_token() }}
                    </div>
                    <div>
                        <strong>Delete Route:</strong> {{ route('users.destroy', 0) }}
                    </div>
                </div>
            </div>
        </div>

        <style>
            .debug-card {
                font-size: 14px;
            }

            .debug-card .card-header {
                font-weight: bold;
                background-color: #f8f9fa;
            }
        </style>
    @endpush --}}
