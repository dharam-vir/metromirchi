<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function show(Request $request){
        $lead = Lead::with('listing')->findOrFail(auth()->user()->id);
        return response()->json([
          'message' => 'Total Leads',
          'data' => $lead
        ]);
    }

    public function anyData(Request $request){
        return response()->json([
            'message' => 'Total Leads'           
          ]);
    }

    
    public function updateFollowup(Request $request){
        return response()->json([
            'message' => 'Total Leads'           
          ]);
    }

    
    public function updateAssign(Request $request){
        return response()->json([
            'message' => 'Total Leads'           
          ]);
    }
    
    public function updateStatus(Request $request){
        return response()->json([
            'message' => 'Total Leads'           
          ]);
    }
}
