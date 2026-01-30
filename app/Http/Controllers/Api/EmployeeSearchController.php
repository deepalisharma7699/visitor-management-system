<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $employees = Employee::where('is_active', true)
            ->where('name', 'LIKE', "%{$query}%")
            ->select('id', 'name', 'department', 'designation')
            ->orderBy('name')
            ->limit(10)
            ->get();
        
        return response()->json($employees);
    }
}
