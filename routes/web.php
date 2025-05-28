<?php

use Illuminate\Support\Facades\Route;
use Sam\ExcelMapper\Http\Controllers\FileUploadController;


Route::get('/excel-mapper/file-upload', function () {
    return view('excel-mapper::index');
})->name('excel-mapper.input');

