@extends('layouts.app')

@section('title', __('auth.reset_password'))

@section('content')
    <div class="w-3/6 my-16 mx-auto">
        <h1 class="text-3xl text-center mb-8">{{ __('auth.reset_password') }}</h1>

        <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="POST" action="{{ route('password.request') }}">
            {{ csrf_field() }}

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">{{ __('auth.email') }}</label>
                <input id="email" type="email" name="email" value="{{ $email }}" required autofocus
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700
                        {{ $errors->has('email') ? 'border-red-500' : '' }}
                        leading-tight focus:outline-none focus:shadow-outline">
                @if ($errors->has('email'))
                    <p class="text-red-500 text-xs italic">
                        {{ $errors->first('email') }}
                    </p>
                @endif
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">{{ __('auth.new_password') }}</label>
                <input id="password" type="password" name="password" value="{{ old('password') }}" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 
                        {{ $errors->has('password') ? 'border-red-500' : '' }}
                        leading-tight focus:outline-none focus:shadow-outline">
                @if ($errors->has('password'))
                    <p class="text-red-500 text-xs italic">
                        {{ $errors->first('password') }}
                    </p>
                @endif
            </div>

            <div class="mb-6">
                <label for="password-confirm" class="block text-gray-700 text-sm font-bold mb-2">{{ __('auth.new_password_confirmation') }}</label>
                <input id="password-confirm" type="password" name="password_confirmation" required 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 
                        leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                {{ __('auth.reset_password') }}
            </button>
        </form>
    </div>
@endsection
