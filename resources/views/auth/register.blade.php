@extends('layouts.app')

@section('content')

  <div class="lemon-form-container">
    <div class="lemon-form">

      <span class="lemon-form-title">
        @if(session('organization')) {{session('organization')->name}}<br/> @endif
        Register
      </span>

      <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="row mb-2">
          <div class="col col-4 my-auto text-secondary font-weight-bold text-right">Name</div>
          <div class="col col-8">
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
            @error('name')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="row mb-2">
          <div class="col col-4 my-auto text-secondary font-weight-bold text-right">E-Mail Address</div>
          <div class="col col-8">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
        </div>

        <div class="row mb-2">
          <div class="col col-4 my-auto text-secondary font-weight-bold text-right">Password</div>
          <div class="col col-8">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
        </div>

        <div class="row mb-2">
          <div class="col col-4 my-auto text-secondary font-weight-bold text-right">Confirm Password</div>
          <div class="col col-8">
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
          </div>
        </div>

        <div class="row">
          <div class="col col-12">
            <button type="submit" class="btn btn-block btn-outline-primary">Register</button>
          </div>
        </div>

      </form>

    </div>
  </div>
  
@endsection
