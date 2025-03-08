@extends('layouts.app')

@section('title', 'Log Details')

@section('content_header')
    <h1>Log Details</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Log #{{ $log->id }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('logs.index') }}" class="btn btn-sm btn-default">
                            <i class="fas fa-arrow-left"></i> Back to Logs
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Action</span>
                                    <span class="info-box-number">
                                        @if($log->action == 'create')
                                            <span class="badge badge-success">{{ ucfirst($log->action) }}</span>
                                        @elseif($log->action == 'update')
                                            <span class="badge badge-info">{{ ucfirst($log->action) }}</span>
                                        @elseif($log->action == 'delete')
                                            <span class="badge badge-danger">{{ ucfirst($log->action) }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($log->action) }}</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning">
                                    <i class="fas fa-calendar"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Date & Time</span>
                                    <span class="info-box-number">{{ $log->created_at->format('Y-m-d H:i:s') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Model Information</h3>
                                </div>
                                <div class="card-body">
                                    <dl class="row">
                                        <dt class="col-sm-4">Model Type</dt>
                                        <dd class="col-sm-8">{{ $log->model }}</dd>
                                        
                                        <dt class="col-sm-4">Model ID</dt>
                                        <dd class="col-sm-8">{{ $log->model_id }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-outline card-success">
                                <div class="card-header">
                                    <h3 class="card-title">User Information</h3>
                                </div>
                                <div class="card-body">
                                    <dl class="row">
                                        <dt class="col-sm-4">User ID</dt>
                                        <dd class="col-sm-8">{{ $log->user_id }}</dd>
                                        
                                        <dt class="col-sm-4">User Name</dt>
                                        <dd class="col-sm-8">{{ $log->user->name ?? 'Unknown User' }}</dd>
                                        
                                        <dt class="col-sm-4">User Email</dt>
                                        <dd class="col-sm-8">{{ $log->user->email ?? 'N/A' }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($log->details)
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Details</h3>
                                </div>
                                <div class="card-body">
                                    <pre class="bg-light p-3 rounded">{{ $log->details }}</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
@stop

