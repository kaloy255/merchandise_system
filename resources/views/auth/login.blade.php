@extends('layouts.app')
@section('title', 'Login | G CLOTHING')
@section('content')
<div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">Sign in to your account</h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Or
            <a href="/register" class="font-medium text-indigo-600 hover:text-indigo-500">create a new account</a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white px-6 py-8 shadow sm:rounded-lg sm:px-10">
            <form class="space-y-6" method="POST" action="/login">
                @csrf
                
                <div>
                    <x-form-label for="email">Email address</x-form-label>
                    <div class="mt-2">
                        <x-form-input name="email" id="email" :value="old('email')" placeholder="you@example.com" />
                        <x-form-error name='email'/>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <x-form-label for="password">Password</x-form-label>
                        <div class="text-sm">
                            <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Forgot your password?</a>
                        </div>
                    </div>
                    <div class="mt-2">
                        <x-form-input name="password" id="password" type="password" placeholder="••••••••" />
                        <x-form-error name='password'/>
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900">Remember me</label>
                </div>

                <div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors">
                        Sign in
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="bg-white px-2 text-gray-500">Or continue with</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <a href="{{ route('auth.redirection', 'google') }}" class="flex w-full items-center justify-center gap-3 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0">
                        <i class="fab fa-google text-red-500"></i>
                        Google
                    </a>

                    <a href="{{  route('auth.redirection', 'facebook') }}" class="flex w-full items-center justify-center gap-3 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0">
                        <i class="fab fa-facebook text-blue-600"></i>
                        Facebook
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection