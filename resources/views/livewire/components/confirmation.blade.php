         <!-- Mapping Preview -->
            @if($mapping && count($mapping) > 0)
            @if($dbValidation == false)
            <div class="text-sm text-yellow-600 mt-2">
                ⚠️ No validation rules defined. All data will be imported as-is.
            </div>
            @endif
            @if ($mapping && count($mapping) > 0)
                <div class="text-sm text-green-600 mt-2">
                    ✅ Mapping is valid.
                </div>
            @endif
            <form wire:submit.prevent="submitMapping" class="mt-4">
                <div class="mt-6 p-4 border rounded bg-gray-50">
                    <h3 class="text-lg font-semibold mb-2">Mapping Preview</h3>
                    <table class="w-full table-auto border-collapse border border-gray-300">
                        <thead>
                            <tr>
                                <th class="border border-gray-300 px-2 py-1 text-left">Excel Column</th>
                                <th class="border border-gray-300 px-2 py-1 text-left">Database Column</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mapping as $excelCol => $dbCol)
                                <tr>
                                    <td class="border border-gray-300 px-2 py-1">{{ $excelCol }}</td>
                                    <td class="border border-gray-300 px-2 py-1">{{ $dbCol }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <input type="submit" class="w-full mt-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600" value="Submit Mapping">
            </form>
            @endif