@if (session()->has('error'))
            <div
            x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 3000)" 
            x-show="show" 
            class="bg-red-100 text-red-800 p-4 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif