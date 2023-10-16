<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class ExportsController extends Controller
{
    public function export() 
    {
        $user=User::all();
        // return Excel::download(new UsersExport($user), 'users.xlsx');
            return Excel::download(new UsersExport,'users.xlsx');
        
    }
}
