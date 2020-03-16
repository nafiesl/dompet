@extends('layouts.app')

@section('title', __('auth.reset_password'))

@section('content')
    <div class="w-3/6 my-16 mx-auto">
        <h1 class="text-3xl text-center mb-8">{{ __('auth.reset_password') }}</h1>
        
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
          </div>
        @endif

        <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="POST" action="{{ route('password.email') }}">
            {{ csrf_field() }}

            <div class="mb-6">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">{{ __('auth.email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700
                        {{ $errors->has('email') ? 'border-red-500' : '' }}
                        leading-tight focus:outline-none focus:shadow-outline">
                @if ($errors->has('email'))
                    <p class="text-red-500 text-xs italic">
                        {{ $errors->first('email') }}
                    </p>
                @endif
            </div>

            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                {{ __('auth.send_reset_password_link') }}
            </button>
        </form>
    </div>
@endsection
