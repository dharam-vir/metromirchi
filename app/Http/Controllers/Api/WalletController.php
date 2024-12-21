<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function addMoney(Request $request){

        

        // Ensure the user is authenticated
        if (!auth()->check()) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        // Get the authenticated user's ID
        $userId = auth()->user()->id;        
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string',
        ]);

        // Find the wallet for the user
        $wallet = Wallet::where('user_id', $userId)->first();
        
    
        // If wallet does not exist, create a new one
        if (!$wallet) {
            $wallet = Wallet::firstOrCreate([
                'user_id' => $userId,
                'balance' => 0.00,
            ]);
        }
        
        $wallet->addMoney($request->amount);

         // Record the transaction
         Transaction::create([
            'wallet_id' => $wallet->id,
            'amount' => $request->amount,
            'type' => 'deposit',
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Money added successfully!',
            'new_balance' => $wallet->balance,
        ]);
    }

    public function spendMoney(Request $request)
    {
        // Validate the request
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string',
        ]);

        // Get the authenticated user's ID
        $userId = auth()->user()->id;

        // Find the wallet for the authenticated user
        $wallet = Wallet::where('user_id', $userId)->first();

        // Check if the wallet exists
        if (!$wallet) {
            return response()->json(['message' => 'Wallet not found.'], 404);
        }

        // Try to spend money from the wallet
        $spendSuccessful = $wallet->spendMoney($request->amount);

        // If the spend was successful, record the transaction
        if ($spendSuccessful) {
            Transaction::create([
                'wallet_id' => $wallet->id,
                'amount' => $request->amount,
                'type' => 'withdrawal',  // Transaction type is 'withdrawal'
                'description' => $request->description ?? 'Spent money from wallet',
            ]);

            return response()->json([
                'message' => 'Money spent successfully!',
                'new_balance' => $wallet->balance,
            ]);
        }

        // If insufficient balance, return an error message
        return response()->json(['message' => 'Insufficient balance.'], 400);
    }


    public function spendForLeads(Request $request)
    {
        // Validate the input
        $request->validate([
            // 'num_leads' => 'required|integer|min:1', 
            'cat_id' => 'required|integer|min:1',
        ]);

        // Get the authenticated user's ID
        $userId = auth()->user()->id;

        // Find the wallet associated with the authenticated user
        $wallet = Wallet::where('user_id', $userId)->first();

        // Check if the wallet exists
        if (!$wallet) {
            return response()->json(['message' => 'Wallet not found.'], 404);
        }
        
         // Fetch the price for a lead from the prices table
         $leadSubCategory = SubCategory::select(['id','name','price'])->where('id', $request->cat_id)->first();  // Assuming 'lead' is the item name for leads        
        // Check if the subcategory exists
        if (!$leadSubCategory) {
            return response()->json(['message' => 'Lead price not found.'], 404);
        }
           // Check if the user has any services under this subcategory
         $service = Service::where('sub_cat_id', $leadSubCategory->id)->where('user_id', $userId)->first();         
        // Calculate the total amount to spend
        if(!$service){
            $amountToSpend = $leadPrice->price; 
        }else {
            return response()->json(['message' => 'Lead price not found.'], 404);
        } 
        // Check if the wallet has sufficient balance
        if ($wallet->balance < $amountToSpend) {
            return response()->json(['message' => 'Insufficient balance.'], 400);
        }

        // Deduct the amount from the wallet
        $wallet->balance -= $amountToSpend;
        $wallet->save();

        // Log the transaction for the spending
        Transaction::create([
            'wallet_id' => $wallet->id,
            'amount' => $amountToSpend,
            'type' => 'withdrawal',  // Type is 'withdrawal' for spending
            'description' => "Spent for {$leadSubCategory->name} leads",
        ]);

        // Return success response with updated balance
        return response()->json([
            'message' => 'Money spent successfully!',
            'new_balance' => $wallet->balance,
        ]);
    }


    
}
