@extends('admin.layout.master')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Product Category List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Product Category List</li>
                        </ol>
                    </div>
                    @if (session('message'))
                        <div class="col-sm-12 alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h3 class="card-title">
                                            <form method="get">
                                                <input type="text" value="{{ $keyword }}" placeholder="Search..."
                                                    name="keyword">
                                                <select name="sortBy">
                                                    <option value="">---Please Select---</option>
                                                    <option {{ $sortBy === 'latest' ? 'selected' : '' }} value="latest">
                                                        Latest</option>
                                                    <option {{ $sortBy === 'oldest' ? 'selected' : '' }} value="oldest">
                                                        Oldest</option>
                                                </select>
                                                <select name="status">
                                                    <option value="">---Please Select---</option>
                                                    <option value="1">Show</option>
                                                    <option value="0">Hide</option>
                                                </select>
                                                <button class="btn btn-primary" type="submit">Search</button>
                                            </form>
                                        </h3>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <a class="btn btn-primary" href="{{ route('admin.product_category.add') }}">Add</a>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($productCategories as $productCategory)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $productCategory->name }}</td>
                                                <td>
                                                    <div
                                                        class="{{ $productCategory->status ? 'btn btn-success' : 'btn btn-danger' }}">
                                                        {{ $productCategory->status ? 'Show' : 'Hide' }}</div>
                                                </td>
                                                <td>{{ $productCategory->created_at }}</td>
                                                <td>
                                                    <a href="{{ route('admin.product_category.detail', ['product_category' => $productCategory->id]) }}"
                                                        class="btn btn-primary">Detail</a>
                                                    <a onclick="return confirm('Are you sure ?')" class="btn btn-danger"
                                                        href="{{ route('admin.product_category.destroy', ['product_category' => $productCategory->id]) }}">Delete</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">No data</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix">
                                {{ $productCategories->links() }}
                                {{-- <ul class="pagination pagination-sm m-0 float-right">
                                    <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                                    @for ($i = 1; $i <= $totalPages; $i++)
                                        <li class="page-item {{ $i == $currentPage ? 'active' : '' }}"><a class="page-link"
                                                href="?page={{ $i }}">{{ $i }}</a>
                                        </li>
                                    @endfor
                                    <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                                </ul> --}}
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
