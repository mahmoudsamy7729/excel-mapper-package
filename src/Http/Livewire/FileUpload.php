<?php

namespace Sam\ExcelMapper\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
/**
 * FileUpload Livewire Component
 * This component handles the file upload, mapping of Excel columns to database columns,
 * and importing data into the selected database table.
 */
class FileUpload extends Component
{
    use WithFileUploads;

    public $step = 1;            // track current step: 1=upload, 2=mapping , 3=import
    public $file;                // uploaded file 
    public $headers = [];        // excel column headers
    public $tables = [];         // db tables list
    public $selectedTable;       // user selected table
    public $tableColumns = [];   // columns of the selected table
    public $mapping = [];        // mapping excel col => db col


    /**
     * Database validation toggle and rules.
     * This allows you to enable or disable database validation and define rules for it.
     */
    public $dbValidation = false; // Toggle for DB validation
    protected $dbRules = [
        // Optional: Define your database validation rules here
        // you can use Config::get('importer.rules.' . $this->selectedTable, []); to dynamically load rules
        // Define your database validation rules here
        // Example: 'email' => 'required|email|unique:users,email',
        // This will be used to validate the data before inserting into the database
        // You can dynamically set this based on the selected table and its columns
        // 'name' => 'required|string|max:255',
        // 'age' => 'nullable|integer|min:0',
        // Add more rules as needed
    ];   // Optional: Define like ['email' => 'required|email'] you can

    
    /**
     * Upload the file and read the headers.
     * This method is called when the user uploads a file.
     */
    public function uploadFile()
    {

        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);
        $headings = (new HeadingRowImport)->toArray($this->file->getRealPath()); // Read the first row of the uploaded file to get headings
        $this->headers = $headings[0][0] ?? []; // Get the first row of headings from the uploaded file
        if (empty($this->headers)) {
            session()->flash('error', 'The uploaded file does not contain valid headers.');
            return;
        }
        // Load tables from the database to show in step 2
        $this->tables = $this->getDatabaseTables();
        $this->step = 2; // go to mapping step
        session()->flash('success', 'The uploaded file has been successfully processed.');

    }

    /**
     * Move to the next step in the import process.
     * This method is called when the user clicks "Next" after uploading the file.
     */
    public function nextStep()
    {
        $this->step++;
    }

    /**
     * Move to the previous step in the import process.
     * This method is called when the user clicks "Back" during mapping.
     */
    public function resetStep()
    {
        $this->step = 1; // reset to upload step
        $this->file = null; // clear file
        $this->headers = []; // clear headers
        $this->tables = []; // clear tables
        $this->selectedTable = null; // clear selected table
        $this->tableColumns = []; // clear table columns
        $this->mapping = []; // clear mapping
    }

    /**
     * Update the selected table and fetch its columns.
     * This method is called when the user selects a table from the dropdown.
     * It also resets the mapping and loads validation rules for the selected table.
     * it also retrive the rules from config file for the selected table.
     */
    public function updatedSelectedTable($value)
    {
        $this->tableColumns = $this->getTableColumns($value);
        // Reset mapping when table changes
        $this->mapping = [];
        $this->dbRules = config("excel-import.rules.{$this->selectedTable}", []); // Load validation rules for the selected table from config
        session()->flash('success', 'Table columns retrieved successfully.'); // Flash success message

    }

    /**
     * Update the mapping for a specific Excel column.
     * This method is called when the user changes the mapping for an Excel column.
     */
    public function updatedMapping($value, $key)
    {
        // If mapping is empty, remove the key from array
        if (empty($value)) {
            unset($this->mapping[$key]); // Remove the mapping entry if no value is provided
        }
    }


    /**
     * Get the list of database tables.
     * This method retrieves all tables from the database.
     * Adjust the query if using a different database system. (i use mysql)
     */
    protected function getDatabaseTables()
    {
        // Works for MySQL — adjust if using another DB
        return collect(DB::select('SHOW TABLES'))->map(function ($table) {
            // The object key is like "Tables_in_database"
            return array_values((array) $table)[0];
        })->toArray(); // Get all tables from the database
    }


    /**
     * Get the columns of the selected table.
     * This method retrieves the column names of the specified table.
     * Adjust the query if using a different database system. (i use mysql)
     */
    protected function getTableColumns($table)
    {
        return DB::getSchemaBuilder()->getColumnListing($table); // Get columns of the selected table
    }


    /**
     * Submit the mapping and import data into the selected table.
     * This method is called when the user clicks "Import" after mapping.
     */
    public function submitMapping()
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv', // Ensure file is still valid
            'selectedTable' => 'required|string',  // Ensure a table is selected
            'mapping' => 'required|array',  // Ensure mapping is provided
        ]);
        $data = Excel::toArray([], $this->file->getRealPath())[0];   // Read the file into an array
        if (empty($data)) {
            session()->flash('error', 'The uploaded file is empty or invalid.'); 
            return;
        }
        // Remove heading row if present
        $headings = $data[0]; // First row = headings
        $rows = array_slice($data, 1); // The rest = data rows
        // Normalize headings to match the mapping keys
        $normalizedHeadings = array_map(function ($h) {
            return strtolower(str_replace([' ', '-'], '_', trim($h)));
        }, $headings);
        $insertData = []; // Prepare data for insertion
        $invalidRows = []; // Collect invalid rows for validation errors
        foreach ($rows as $index => $row) {
            $assocRow = array_combine($normalizedHeadings, $row);  // Combine headings with row data
            $entry = [];  // Prepare entry for database insertion
            // Map Excel columns to database columns
            foreach ($this->mapping as $excelColumn => $dbColumn) {
                // Normalize the Excel column name too
                $normalizedExcelColumn = strtolower(str_replace([' ', '-'], '_', trim($excelColumn)));
                $entry[$dbColumn] = $assocRow[$normalizedExcelColumn] ?? null; // Use null if not found
            }
            if (!empty($this->dbRules)) {
            
                $validator = Validator::make($entry, $this->dbRules); // Validate the entry against the defined rules

                if ($validator->fails()) { // If validation fails, store the errors
                    // Store the row number and errors
                    // Note: $index starts from 0, so we add 2 to match the Excel row number (1-based index)
                    $invalidRows[] = [
                        'row' => $index + 2,
                        'errors' => $validator->errors()->all(),
                    ];
                    continue; // Skip this entry if validation fails
                }
            }
            $insertData[] = $entry;
        }
        // $invalidRows collect all invalid rows that failed the validation
        DB::table($this->selectedTable)->insert($insertData); // Insert valid data into the selected table
        session()->flash('success', 'Data imported successfully!');// Flash success message
        $this->resetStep(); // Reset the component state after import
    }


    /**
     * Render the Livewire component view.
     * This method returns the view for the file upload component.
     */
    public function render()
    {
        return view('excel-mapper::livewire.file-upload');
    }
}
