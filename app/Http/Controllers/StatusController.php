<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class StatusController extends Controller
{
    public function index()
    {
        $data = [];

        $file = file(base_path() . '/tests/Support/status-1-entry');

        foreach ($file as $line) {
            $exploded = explode(': ', $line);

            if ($exploded[0] == 'Package') {
                $data[] = trim($exploded[1]);
            }
        }
        return response()->json([$data]);
    }

    public function show($package = '')
    {
        return response()->json(['Could not find requested package'], 400);
    }
}
