@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

    {{-- PAGE HEADER --}}
    <div class="page-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold mb-1">Dashboard</h3>
            <h6 class="op-7 mb-0">Superdigitech Admin Portal</h6>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ url('/dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>

    {{-- STAT CARDS --}}
    <div class="row mb-4">
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3">
                            <div class="numbers">
                                <p class="card-category">Total Users</p>
                                <h4 class="card-title">1,294</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3">
                            <div class="numbers">
                                <p class="card-category">Posts Today</p>
                                <h4 class="card-title">32</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-warning bubble-shadow-small">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3">
                            <div class="numbers">
                                <p class="card-category">Monthly Posts</p>
                                <h4 class="card-title">412</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-danger bubble-shadow-small">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3">
                            <div class="numbers">
                                <p class="card-category">Yearly Posts</p>
                                <h4 class="card-title">4,981</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CHART + ACTIVITY --}}
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card card-round shadow-sm">
                <div class="card-header">
                    <div class="card-title">Post Analytics</div>
                    <p class="card-category">Posts growth overview</p>
                </div>
                <div class="card-body">
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-chart-area fa-2x mb-2"></i>
                        <p class="mb-0">Chart will be displayed here</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-round shadow-sm">
                <div class="card-header">
                    <div class="card-title">Quick Info</div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between mb-2">
                            <span>Active Categories</span>
                            <span class="fw-bold">18</span>
                        </li>
                        <li class="d-flex justify-content-between mb-2">
                            <span>Draft Articles</span>
                            <span class="fw-bold">7</span>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span>Admins</span>
                            <span class="fw-bold">3</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- RECENT POSTS TABLE --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card card-round shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title">Recent Articles</div>
                        <p class="card-category">Latest content activity</p>
                    </div>
                    <a href="{{ url('/articles') }}" class="btn btn-sm btn-primary">
                        View All
                    </a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Welcome to Superdigitech</td>
                                    <td>General</td>
                                    <td><span class="badge bg-success">Published</span></td>
                                    <td>2026-01-10</td>
                                </tr>
                                <tr>
                                    <td>Admin Portal Update</td>
                                    <td>System</td>
                                    <td><span class="badge bg-warning">Draft</span></td>
                                    <td>2026-01-08</td>
                                </tr>
                                <tr>
                                    <td>Security Improvements</td>
                                    <td>Technology</td>
                                    <td><span class="badge bg-success">Published</span></td>
                                    <td>2026-01-05</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
