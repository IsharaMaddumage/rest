<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Employee;
use JWTAuth;

class EmployeeController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {   
        try{
            $employees = Employee::get(['first_name', 'last_name', 'phone'])->toArray();
            return response()->json([
                'success' => true,
                'message' => 'found employees',
                'data' => $employees,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            $errorArr = array('msg' => "Faild to retrive users", 'error' => $e->getMessage());
            \Log::error($errorArr);
        }
    }

    public function show($id)
    {   
        try{
            $employee = Employee::find($id);
        
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, employee with id ' . $id . ' cannot be found'
                ], 400);
            }
        
            return response()->json([
                'success' => true,
                'message' => 'found employees',
                'data' => $employee,
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            $errorArr = array('msg' => "Faild to retrive user", 'error' => $e->getMessage());
            \Log::error($errorArr);
        }
    }

    public function store(Request $request)
    {
        try{
            $this->validate($request, [
                'first_name' => 'required',
                'last_name' => 'required',
                'phone' => 'required'
            ]);
        
            $employee = new Employee();
            $employee->first_name = $request->first_name;
            $employee->last_name = $request->last_name;
            $employee->phone = $request->phone;
            $employee->email = $request->email;
            $employee->location = $request->location;
        
            if ($employee->save())
                return response()->json([
                    'success' => true,
                    'message' => 'successfully saved',
                    'data' => $employee
                ]);
            else
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, employee could not be added'
                ], 500);
        } catch (\Exception $e) {
            $errorArr = array('msg' => "Faild to store user", 'error' => $e->getMessage());
            \Log::error($errorArr);
        }
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
    
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, employee with id ' . $id . ' cannot be found'
            ], 400);
        }
    
        $updated = $employee->fill($request->all())
            ->save();
    
        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'successfully updated',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, employee could not be updated'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);
    
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, employee with id ' . $id . ' cannot be found'
            ], 400);
        }
    
        if ($employee->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'successfully deleted'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'employee could not be deleted'
            ], 500);
        }
    }
}
