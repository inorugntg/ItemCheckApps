@extends('template.main')
@section('title', 'Apar')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">@yield('title')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">@yield('title')</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="text-right">
                                <a href="/apar/create" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add
                                    Apar</a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-striped table-bordered table-hover text-center" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Kode</th>
                                        <th>Lokasi</th>
                                        <th>Supplier</th>
                                        <th>Media</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($apar as $data)
                                    <tr>
                                        <td>{{ $data->nama }}</td>
                                        <td>{{ $data->kode }}</td>
                                        <td>{{ $data->lokasi }}</td>
                                        <td>{{ $data->supplier }}</td>
                                        <td>
                                            @if (strpos($data->media, '.pdf') !== false)
                                            <a href="{{ asset('storage/media/' . $data->media) }}" target="_blank">{{ $data->media }}</a>
                                            @else
                                            <img src="{{ asset('storage/media/'. $data->media) }}" alt="{{ $data->media }}" style="max-width: 100px;">
                                            @endif
                                        </td>
                                        <td>{{ $data->status }}</td>
                                        <td>
                                            <a href="{{ route('apar.edit', $data->id) }}" class="btn btn-primary">Edit</a>
                                            <form action="{{ route('apar.destroy', $data->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
    </div>
</div>

@endsection