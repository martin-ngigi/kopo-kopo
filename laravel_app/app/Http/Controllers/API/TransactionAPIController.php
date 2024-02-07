<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;
// use App\Traits\IdGenerator;
use Illuminate\Support\Str;
use Exception;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class TransactionAPIController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/transactions",
     *     summary="create transactions data",
     *     tags={"Transactions"},
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="transaction_id", type="string", example="4bcc3959-6fe1-406e-9f04-cad2637b47d5"),
     *                 @OA\Property(property="account_id", type="string", example="0afd02d3-6c59-46e7-b7bc-893c5e0b7ac2"),
     *                 @OA\Property(property="amount", type="integer", example=7),
     *                 @OA\Property(property="created_at", type="string", example="2021-05-12T18:29:40.206924+00:00")
     *             ),
     *             example={
     *                 {
     *                     "transaction_id": "4bcc3959-6fe1-406e-9f04-cad2637b47d5",
     *                     "account_id": "0afd02d3-6c59-46e7-b7bc-893c5e0b7ac2",
     *                     "amount": 7,
     *                     "created_at": "2021-05-12T18:29:40.206924+00:00"
     *                 },
     *                 {
     *                     "transaction_id": "050a75f6-8df1-4ad1-8f5b-54e821e98581",
     *                     "account_id": "5ae0ef78-e902-4c40-9f53-8cf910587312",
     *                     "amount": -4,
     *                     "created_at": "2021-05-18T21:33:47.203136+00:00"
     *                 }
     *             }
     *         )
     *     )
     * )
     */
    public function getTransactions(Request $request) {
        // all transactions
        try{
            $transactions = Transaction::get(['transaction_id','account_id', 'amount', 'created_at']);
            return response()->json(
                $transactions, 200
            );
        }
        catch(Exception $e){
            return response()->json([
                "message" => "Couldn't get transactions",
                'error' => $e->getMessage(),
                "status" => 500,
            ]);
        }
    }

/**
 * @OA\Post(
 *     path="/api/transactions",
 *     summary="Create a new transaction",
 *     tags={"Transactions"},
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             type="object",
 *             anyOf={
 *                 @OA\Schema(ref="#/components/schemas/TransactionRequestWithPositiveAmount"),
 *                 @OA\Schema(ref="#/components/schemas/TransactionRequestWithNegativeAmount")
 *             }
 *         ),
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Transaction created successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Transaction created successfully"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="transaction_id", type="string", format="uuid", example="generated-uuid"),
 *                 @OA\Property(property="account_id", type="string", format="uuid", example="0afd02d3-6c59-46e7-b7bc-893c5e0b7ac2"),
 *                 @OA\Property(property="amount", type="number", example=7),
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="Mandatory body parameters missing or have incorrect type."),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=405,
 *         description="Method not allowed. This status code should be returned when using the wrong HTTP method.",
 *     ),
 *     @OA\Response(
 *         response=415,
 *         description="Invalid content",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="Specified content type not allowed."),
 *         ),
 *     ),
 * )
 *
 * @param Request $request
 * @return JsonResponse
 */
public function createTransaction(Request $request)
{
    try{

        // Check if the Content-Type header is present
        if (!$request->hasHeader('Content-Type')) {
            return response()->json(['error' => 'Content-Type header is missing. Use Content-Type: application/json'], 415);
        }

        // Check Content-Type header is 'application/json'
        if ($request->header('Content-Type') != 'application/json') {
            return response()->json(['error' => 'Unsupported Media Type. Use Content-Type: application/json'], 415);
        }

        // Validat the request
       $validate = Validator::make( $request->all(),[
        'account_id' =>'required|uuid',
        'amount' =>'required|integer',
       ]);

       // incase the provided data is not valid, raise validation error
       if($validate->fails()){
        return response()->json([
            "message" => "Validation error",
            'error' => $validate->errors(),
            "status" => 400,
        ], 400);
       }

        $account_id = $request->account_id;
        $amount = $request->amount;
        // $transaction_id =IdGenerator::generateID();
        $transaction_id =Str::uuid();


        $account = Account::where('account_id', $account_id)->first();

        //No account found, create the account
        if(!$account) {
            // return response()->json([
            //     "message" => "Account not found",
            //     "status" => 404,
            // ], 404);

            $account = Account::create([
                'account_id' => $account_id,
                'balance' => 0,
            ]);
        }

        /// create a new Transaction
        $transaction = new Transaction();
        $transaction->transaction_id = $transaction_id;
        $transaction->account_id = $account_id;
        $transaction->amount = $amount;
        $transaction->created_at = Date::now();
        $isCreated = $transaction->save();

        // if transaction was not successful created, return with error message
        if(!$isCreated) {
            return response()->json([
                "message" => "Transaction not created",
                "status" => 400,
            ], 400);
        } 

        /// Top up user account balance
        $account->update([
            'balance' => $account->balance + $amount,
        ]);

        if($isCreated) {
            return response()->json($transaction, 201);
        }
    }
    catch(Exception $e){
        return response()->json([
            "message" => "Couldn't create transaction",
            'error' => $e->getMessage(),
            "status" => 500,
        ], 500);
    }
    // catch (MethodNotAllowedHttpException $e) {
    //     // Handle the incorrect request method
    //     return response()->json(['error' => 'Method not allowed'], 405);
    // }
}

/**
 * @OA\Get(
 *     path="/api/transactions/{transaction_id}",
 *     summary="Get a transaction by transaction_id",
 *     tags={"Transactions"},
 *     @OA\Parameter(
 *         name="transaction_id",
 *         in="path",
 *         required=true,
 *         description="ID of the transaction",
 *         @OA\Schema(type="string", format="uuid", example="0afd02d3-6c59-46e7-b7bc-893c5e0b7ac2"),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Transaction retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Transaction retrieved successfully"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="transaction_id", type="string", format="uuid", example="generated-uuid"),
 *                 @OA\Property(property="account_id", type="string", format="uuid", example="0afd02d3-6c59-46e7-b7bc-893c5e0b7ac2"),
 *                 @OA\Property(property="amount", type="number", example=7),
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Transaction not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="Transaction not found"),
 *         ),
 *     ),
 * )
 *
 * @param string $transaction_id
 * @return JsonResponse
 */
    public function getTransactionByTransactionId($transaction_id){
        // all transactions
        try{

            $transaction = Transaction::get(['transaction_id','account_id', 'amount', 'created_at'])
                                        ->where('transaction_id', $transaction_id)->first();

            // Transaction not found, return error message
            if(!$transaction){
                return response()->json([
                    "error" => "Transaction not found"
                ], 404);
            }

            return response()->json($transaction, 200);
            
        }
        catch(Exception $e){
            return response()->json([
                "message" => "Couldn't get transaction",
                'error' => $e->getMessage(),
                "status" => 500,
            ]);
        }
    }




}
