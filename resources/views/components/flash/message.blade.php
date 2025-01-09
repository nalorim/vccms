<div>
        @if (session()->has('message'))
            <div class="bg-green-500 p-4 rounded-lg mb-2 flex items-center justify-between">
                {{ session('message') }} 
                <div>
                    <a href="{{ route('items.show', $item['id'] ) }}" class="bg-green-700 p-2 rounded ml-8 text-xs">See Update</a>
                    <button wire:click="clearMessage()" class="btn border py-1 text-xs">x</button>
                </div>
            </div>
        @endif
    
        @if (session()->has('fail'))
            <div class="bg-yellow-600 p-4 rounded-lg mb-2 flex items-center justify-between">
                <span>
                    {{ session('fail') }} 
                </span>
                <button wire:click="clear()" class="btn border px-3">x</button>
            </div>
        @endif
    
        @if ($errors->any())
            <div class="bg-red-500 p-4 text-sm rounded-lg flex items-center justify-between">
                <div class="w-[90%] max-h-[60px] overflow-scroll">
                    @foreach ($errors->all() as $error)
                    <div>{{$error}}</div>
                    @endforeach
                </div>
                <div>
                    <button wire:click="clearValidate()" class="btn px-3 border bg-slate-700">x</button>
                </div>
            </div> 
        @endif
</div>