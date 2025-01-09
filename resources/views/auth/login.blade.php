@extends('layouts.main')

@section('content')

<div class="w-full mt-[15%] flex items-center justify-center">
        
    <form action="{{ route('login') }}" method="POST" class="border border-gray-500 rounded-lg p-4 w-[350px] flex flex-col gap-4" autocomplete="off">
        
        <div>Login</div>
        
        @csrf
        {{-- Username --}}
        <div>
            <label for="username" class="label">Username</label>
            <input type="text" name="name" value="{{ old('name') }}" 
            class="form-input rounded w-full bg-transparent" placeholder="Email / Username">
            @error('name')
                <span class="text-xs text-yellow-500">{{ $message }}</span>
            @enderror
        </div>

        {{-- Password  --}}
        <div x-data="{ show: false }" class="relative">
            <label for="password" class="label">Password</label>
            <input :type="show ? 'text' : 'password'" name="password" 
            class="form-input rounded w-full bg-transparent" placeholder="*********">
            <span @click="show=!show"
            :class="show ? 'i-eye-off' : 'i-eye'" 
            class="absolute right-2 rounded"></span>
            
            @error('password')
                <span class="text-xs text-yellow-500">{{ $message }}</span>
            @enderror
        </div>

        {{-- Remember Me Checkbox --}}
        <div class="text-white text-[16px]">
            <input type="checkbox" name="remember" checked>
            Remember Me
        </div>

        {{-- Submit Button --}}
        <div>
            <button type="submit" class="w-full text-[18px] text-center bg-blue-500 
            text-white py-2 rounded-md hover:shadow-lg transition-all">Login</button>
        </div>

    </form>
        

</div>

@endsection