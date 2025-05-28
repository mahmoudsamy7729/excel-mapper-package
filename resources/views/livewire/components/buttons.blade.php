@if ($step > 1)
        <button 
            wire:click="resetStep"  x-on:click="fileName = ''"
            class="w-full mt-4 py-2 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600"
            >
                Reset
        </button>
@endif
@if($step === 2 && $selectedTable)
    <button 
        wire:click="nextStep" 
        class="w-full mt-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600">
                Next
    </button>
@endif
