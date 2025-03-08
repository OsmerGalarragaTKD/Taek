@extends('layouts.app')

@section('title', 'System Logs')

@section('content_header')
    <h1>System Logs</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All System Logs</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="logs-table" class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Action</th>
                            <th>Model</th>
                            <th>Model ID</th>
                            <th>User</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->id }}</td>
                                <td>
                                    @if($log->action == 'create')
                                        <span class="badge badge-success">{{ ucfirst($log->action) }}</span>
                                    @elseif($log->action == 'update')
                                        <span class="badge badge-info">{{ ucfirst($log->action) }}</span>
                                    @elseif($log->action == 'delete')
                                        <span class="badge badge-danger">{{ ucfirst($log->action) }}</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($log->action) }}</span>
                                    @endif
                                </td>
                                <td>{{ $log->model }}</td>
                                <td>{{ $log->model_id }}</td>
                                <td>{{ $log->user->name ?? 'Unknown User' }}</td>
                                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    <a href="{{ route('logs.show', $log->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No logs found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // You can add DataTables initialization here if needed
            $('#logs-table').DataTable();
        });
    </script>
@stop

