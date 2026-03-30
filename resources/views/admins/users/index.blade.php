@extends('admins.master')

@section('content')
    <div class="container my-5">
        <style>
            .users-table-wrap {
                border: 1px solid #d9dee3;
                border-radius: 6px;
                overflow: hidden;
                background: #fff;
            }

            .users-table th,
            .users-table td {
                vertical-align: middle;
            }

            .users-table thead th {
                background: #f8fafc;
                font-weight: 700;
            }

            .users-table thead th:last-child,
            .users-table tbody td:last-child {
                width: 140px;
                text-align: right;
                padding-right: 14px;
            }

            .action-box {
                width: 32px;
                height: 32px;
                padding: 0;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 6px;
                line-height: 1;
                border: 0;
                color: #ffffff;
                cursor: pointer;
                transition: transform 0.15s ease, filter 0.15s ease;
            }

            .action-box-edit {
                background: #68bb93;
            }

            .action-box-edit:hover {
                background: #5bac85;
                transform: translateY(-1px);
            }

            .action-box-delete {
                background: #ef9aa7;
            }

            .action-box-delete:hover {
                background: #e38997;
                transform: translateY(-1px);
            }

            .action-box svg {
                width: 14px;
                height: 14px;
            }

            .actions-wrap {
                display: inline-flex;
                gap: 8px;
                justify-content: flex-end;
                align-items: center;
            }

            @media (max-width: 767.98px) {
                .users-header {
                    text-align: center;
                    justify-content: center !important;
                }

                .users-header .btn {
                    width: 100%;
                }

                .users-table th:last-child,
                .users-table td:last-child {
                    width: auto;
                    padding-right: 8px;
                }
            }
        </style>

        <div class="users-header d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <h4 class="fw-bold mb-0">Users</h4>
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                <a href="{{ route('admin.users.create') }}" class="btn btn-success">Create New</a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Back</a>
            </div>
        </div>

        <div class="users-table-wrap shadow-sm">
            <div class="table-responsive">
                <table class="table table-bordered users-table mb-0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ optional($user->created_at)->format('Y-m-d H:i') }}</td>
                                <td class="text-end">
                                    <div class="actions-wrap">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn action-box action-box-edit" title="Edit">
                                            <svg viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                                                <path d="M12.146.854a.5.5 0 0 1 .708 0l2.292 2.292a.5.5 0 0 1 0 .708l-9.5 9.5L3 14l.646-2.646zM11.207 3 13 4.793 14.293 3.5 12.5 1.707zM12.293 5.5 10.5 3.707 4 10.207V12h1.793z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline js-confirm-delete" data-confirm-message="Are you sure to delete this user?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn action-box action-box-delete" title="Delete">
                                                <svg viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0A.5.5 0 0 1 8.5 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1 0-2H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1M4 4v9a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4z" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
@endsection
