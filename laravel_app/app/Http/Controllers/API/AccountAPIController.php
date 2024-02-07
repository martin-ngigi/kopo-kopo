<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Account;
// use App\Traits\IdGenerator;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class AccountAPIController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/ping",
     *     tags={"Ping"},
     *     summary="Ping connection test",
     *     @OA\Response(response="200", description="Healthcheck to make sure the server is up")
     * )
     */
    public function pingIndex(Request $request){
        try{
            return response()->json([
                "message" => "Pong. The server is up",
                "status" => 200,
            ], 200);
        }
        catch(Exception $e){
            return response()->json([
                "message" => "Couldn't create account",
                'error' => $e->getMessage(),
                "status" => 500,
            ], 500);
        }// 405 status code means "Method Not Allowed"
        // }
    }
    /**
     * @OA\Post(
     *     path="/api/accounts",
     *     summary="create account",
     *     tags={"Accounts"},
     *     @OA\Response(
     *         response="200",
     *         description="Create new account",
     *         @OA\JsonContent(
     *             type="application/json",
     *             example = {
     *                 "account_id": "0afd02d3-6c59-46e7-b7bc-893c5e0b7ac2",
     *                 "amount": 7
     *              }
     *          )
     *      )
     * )
     */
    public function createAccount(Request $request){
        try{
            // Check Content-Type header 
            if (!$request->header('Content-Type') == 'application/json') {
                return response()->json(['error' => 'Unsupported Media Type. Use Content-Type: application/json'], 415);
            }

            // Check Content-Type header 
            if (!$request->header('Content-Type') == 'application/json') {
                return response()->json(['error' => 'Unsupported Media Type. Use Content-Type: application/json'], 415);
            }
            
            // $account_id =IdGenerator::generateID();
            $account_id = Str::uuid();

            // $account = Account::create([
            //     'account_id' => $account_id,
            //     'balance' => 0
            // ]);

            $account = new Account();
            $account->account_id = $account_id;
            $account->balance = 0;
            $account->save();

            return response()->json($account, 201);
        }
        catch(Exception $e){
            return response()->json([
                "message" => "Couldn't create account",
                'error' => $e->getMessage(),
                "status" => 500,
            ], 500);

        }
    }

        /**
     * @OA\Get(
     *     path="/api/accounts",
     *     summary="Get accounts data",
     *     tags={"Accounts"},
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="account_id", type="string", example="0afd02d3-6c59-46e7-b7bc-893c5e0b7ac2"),
     *                 @OA\Property(property="balance", type="integer", example=7),
     *             ),
     *             example={
     *                 {
     *                     "account_id": "0afd02d3-6c59-46e7-b7bc-893c5e0b7ac2",
     *                     "balance": 7,
     *                 },
     *                 {
     *                     "account_id": "5ae0ef78-e902-4c40-9f53-8cf910587312",
     *                     "balance": -4,
     *                 }
     *             }
     *         )
     *     )
     * )
     */
    public function getAccounts(){
        try{
            // $accounts = Account::all();
            $accounts = Account::get(['account_id', 'balance']);

            return response()->json($accounts);
        }
        catch (Exception $e) {
            return response()->json([
                "message" => "Couldn't get accounts",
                'error' => $e->getMessage(),
                "status" => 500,
            ], 500);
        }

    }

    /**
 * @OA\Get(
 *     path="/api/accounts/{account_id}",
 *     summary="Get a transaction by account_id",
 *     tags={"Accounts"},
 *     @OA\Parameter(
 *         name="account_id",
 *         in="path",
 *         required=true,
 *         description="ID of the account",
 *         @OA\Schema(type="string", format="uuid", example="0afd02d3-6c59-46e7-b7bc-893c5e0b7ac2"),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Account_id retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Account_id retrieved successfully"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="account_id", type="string", format="uuid", example="0afd02d3-6c59-46e7-b7bc-893c5e0b7ac2"),
 *                 @OA\Property(property="balance", type="number", example=17),
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Account_id not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="Account not found"),
 *         ),
 *     ),
 * )
 *
 * @param string $account_id
 * @return JsonResponse
 */
public function getAccountByAccountId($account_id) {
    // all transactions
    try{

        $account = Account::get(['account_id', 'balance'])
                                    ->where('account_id', $account_id)->first();

        // account not found, return error message
        if(!$account){
            return response()->json([
                "error" => "Account not found"
            ], 404);
        }

        return response()->json($account, 200);
        
    }
    catch(Exception $e){

        return response()->json([
            "message" => "Couldn't get account",
            'error' => $e->getMessage(),
            "status" => 500,
        ]);
    }
}
}
