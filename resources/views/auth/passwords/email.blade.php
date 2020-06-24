@extends('layouts.app')

@section('content')

  <div class="lemon-form-container">
    <div class="lemon-form">

      <span class="lemon-form-title">
        @if(session('organization')) {{session('organization')->name}}<br/> @endif
        Reset Password
      </span>

      @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
      @endif

      <form method="POST" action="{{ route('password.email') }}">
        @csrf

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

        <div class="row">
          <div class="col col-12">
            <button type="submit" class="btn btn-block btn-outline-info">Send Password Reset Link</button>
          </div>
        </div>

      </form>

    </div>
  </div>

@endsection
