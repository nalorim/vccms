<div class="bg-slate-900 py-2">
    @if (Route::has('login'))
        <nav class="max-w-screen-xl mx-auto flex items-center justify-between">
            
            @auth
                <a
                    href="{{ url('/') }}"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Dashboard
                </a>
                <div class="flex items-center gap-4">
                    <div>Hello, {{ auth()->user()->name }}</div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn border text-sm">
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            @else
            
                <a
                    href="{{ url('/') }}"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Dashboard
                </a>
                <div class="flex items-center justify-end">
                    

                    <a
                        href="{{ route('login') }}"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                    >
                        Log in
                    </a>
    
                    @if (Route::has('register'))
                        <a
                            href="{{ route('register') }}"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            Register
                        </a>
                    @endif
                </div>
            @endauth
        </nav>
    @endif
</div>