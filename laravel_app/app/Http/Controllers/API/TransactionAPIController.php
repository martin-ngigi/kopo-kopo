<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionAPIController extends Controller
{
    //
  /**
     * @OA\Get(
     *     path="/api/v1/transactions",
     *     summary="Get example data",
     *     @OA\Response(response="200", description="Successful operation")
     * )
     */
    public function getExampleData() {
        // Your code here
        // return response()->json(['message' => 'Hello World!']);
        return "Hello World!";
    }
}
