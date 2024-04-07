@extends('template.main')
@section('title', 'Add Apar')
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
            <li class="breadcrumb-item"><a href="/apar">Apar</a></li>
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
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <div class="text-right">
                <a href="/apar" class="btn btn-warning btn-sm"><i class="fa-solid fa-arrow-rotate-left"></i>
                  Back
                </a>
              </div>
            </div>
            <form class="needs-validation" novalidate action="/apar" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="nama">Name</label>
                      <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" id="nama" placeholder="Name Barang" value="{{old('nama')}}" required>
                      @error('nama')
                      <span class="invalid-feedback text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="kode">Code</label>
                      <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" id="kode" placeholder="Code" value="{{old('kode')}}" required>
                      @error('kode')
                      <span class="invalid-feedback text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="lokasi">Location</label>
                      <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror" id="lokasi" placeholder="Location" value="{{old('lokasi')}}" required>
                      @error('lokasi')
                      <span class="invalid-feedback text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="supplier">Supplier</label>
                      <input type="text" name="supplier" class="form-control @error('supplier') is-invalid @enderror" id="supplier" placeholder="Supplier" value="{{old('supplier')}}" required>
                      @error('supplier')
                      <span class="invalid-feedback text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="media">Media</label>
                      <input type="file" name="media" class="form-control @error('media') is-invalid @enderror" id="media" required>
                      @error('media')
                      <span class="invalid-feedback text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="status">Status</label>
                      <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                        <option value="">Select Status</option>
                        <option value="good">Good</option>
                        <option value="no">No</option>
                      </select>
                      @error('status')
                      <span class="invalid-feedback text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="user_id">Owner</label>
                      <select name="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                        <option value="">Select Owner</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                      </select>
                      @error('user_id')
                      <span class="invalid-feedback text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer text-right">
                <button class="btn btn-dark mr-1" type="reset"><i class="fa-solid fa-arrows-rotate"></i>
                  Reset</button>
                <button class="btn btn-success" type="submit"><i class="fa-solid fa-floppy-disk"></i>
                  Save</button>
              </div>
            </form>
          </div>
        </div>
        <!-- /.content -->
      </div>
    </div>
  </div>
</div>

@endsection
