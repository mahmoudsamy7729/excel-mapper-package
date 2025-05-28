  <h1 class="text-2xl font-semibold text-center mb-4">Upload Your File</h1>
      <form wire:submit.prevent="uploadFile" enctype="multipart/form-data" >        
        <div class="flex items-center justify-center w-full">
          <label for="dropzone-file" 
                 class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                </svg>
              <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
              <p class="text-xs text-gray-500">Upload your Excel file</p>
            </div>
            <input id="dropzone-file" type="file" 
            wire:model="file" 
                     name="file"
            accept=".xlsx,.xls,.csv" required class="hidden" 
                   @change="fileName = $event.target.files[0]?.name ?? ''" />
          </label>
        </div>

        <!-- Display selected file name -->
        <template x-if="fileName">
          <p class="mt-2 text-gray-700 text-center font-medium" x-text="fileName"></p>
        </template>

        <input type="submit" class="w-full mt-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600" value="Upload File">
      </form>