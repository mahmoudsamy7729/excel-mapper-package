        <!-- Select Table -->
        
        <div class="max-w-sm mx-auto">
            <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select a Table</label>
            <select wire:model.live="selectedTable" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option selected>Choose a table</option>
                @foreach($tables as $table)
                    <option value="{{ $table }}">{{ $table }}</option>
                @endforeach
            </select>
        </div>

        <!-- Column Mapping -->
        @if($selectedTable)
                <div class="mt-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Map Excel Columns to Database Columns</h3>
            <div class="grid grid-cols-1 gap-4">
                @foreach($headers as $excelCol)
                    <div class="grid grid-cols-2 gap-4 items-center">
                        <div class="text-sm text-gray-700 font-medium">
                            {{ $excelCol }}
                        </div>
                        <select wire:model.live="mapping.{{ $excelCol }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option selected value="">Choose a table</option>
                            @foreach($tableColumns as $dbCol)
                                <option value="{{ $dbCol }}">{{ $dbCol }}</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
            </div>
            
            </div>

            
        @endif