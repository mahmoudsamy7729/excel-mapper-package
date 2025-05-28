<div>
 <div class="min-h-screen flex items-center justify-center p-4 bg-gray-100">
    <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-md" x-data="{ fileName: '' }">

        @include('excel-mapper::livewire.components.alert-success')
        @include('excel-mapper::livewire.components.alert-error')
        
    @if($step === 1)
            @include('excel-mapper::livewire.components.upload-file')
    @endif

    @if($step === 2)
            @include('excel-mapper::livewire.components.table-mapping')
    @endif

    @if ($step === 3)
            @include('excel-mapper::livewire.components.confirmation')
    @endif

    @include('excel-mapper::livewire.components.buttons')
</div>
</div>

</div>
