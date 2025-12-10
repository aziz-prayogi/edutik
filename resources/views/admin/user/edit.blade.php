@extends('templates.app')

@section('content')
    <div class="w-75 d-block mx-auto my-5 p-4">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Home</a>
        </li>
        <li class="breadcrumb-item">
          <a href="#">Library</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
          <a href="#">Data</a>
        </li>
      </ol>
    </nav>
  </div>
</nav>
            <div class="card p-4 my-4">
        <h5 class="text-center my-3"> Edit Data User</h5>
        <form action="{{ route('admin.users.update', $user['id']) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nama" class="form-label">Nama User</label>
                <input type="text" value="{{ $user['name']}}" class="form-control @error('name') is-invalid
                @enderror" id="nama" name="name">
                @error('name')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password :</label>
                <input type="password" class="form-control @error('password') is-invalid
                @enderror" id="password" name="password">
                @error('password')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary"> Update data</button>
        </form>

    </div>
    </div>
@endsection
