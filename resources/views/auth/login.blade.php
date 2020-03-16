@extends('layouts.app')

@section('title', __('auth.login'))

@section('content')
    <div class="w-3/6 my-16 mx-auto">
        <h1 class="text-3xl text-center mb-8">{{ __('auth.login') }}</h1>
        <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}

            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">{{ __('auth.email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
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
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">{{ __('auth.password') }}</label>
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
                <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} class="w-5 h-5 form-checkbox text-green-500"> {{ __('auth.remember_me') }}
                </label>
            </div>

            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                {{ __('auth.login') }}
            </button>

            <a class="underline text-blue-600" href="{{ route('password.request') }}">
                {{ __('auth.forgot_password') }}
            </a>
        </form>
    </div>
@endsection
