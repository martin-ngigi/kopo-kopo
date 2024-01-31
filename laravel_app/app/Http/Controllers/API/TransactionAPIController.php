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
public function getTransactions() {

    $transactions = [
        ['id' => 1, 'amount' => 100.00, 'description' => 'Transaction 1'],
        ['id' => 2, 'amount' => 150.50, 'description' => 'Transaction 2'],
    ];

    return response()->json($transactions);
}
}
