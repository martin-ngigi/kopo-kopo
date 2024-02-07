<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="KOPOKOPO Transactions",
 *      description="Kopokopo Transactions Management",
 *      @OA\Contact(
 *          email="martinwainaina001@gmail.com",
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Demo API Server"
 * )
 *
 * @OA\Tag(
 *     name="Projects",
 *     description="API Endpoints of Projects"
 * )
 * @OA\Schema(
 *      schema="TransactionRequest",
 *      title="Transaction Request",
 *      description="Request payload for creating a transaction",
 *      @OA\Property(property="account_id", type="string", format="uuid", description="Account ID (UUID)"),
 *      @OA\Property(property="amount", type="integer", description="Transaction Amount")
 * )
 * @OA\Schema(
 *      schema="Transaction",
 *      title="Transaction",
 *      description="Transaction details",
 *      @OA\Property(property="transaction_id", type="string", format="uuid", description="Transaction ID (UUID)"),
 *      @OA\Property(property="account_id", type="string", format="uuid", description="Account ID (UUID)"),
 *      @OA\Property(property="amount", type="integer", description="Transaction Amount"),
 *      @OA\Property(property="created_at", type="string", format="date-time", description="Transaction creation timestamp")
 * )
 * @OA\Schema(
 *      schema="ArrayOfTransactions",
 *      title="Array of Transactions",
 *      description="An array of transaction details",
 *      @OA\Property(property="transaction_id", type="string", format="uuid", description="Transaction ID (UUID)"),
 *      @OA\Property(property="account_id", type="string", format="uuid", description="Account ID (UUID)"),
 *      @OA\Property(property="amount", type="integer", description="Transaction Amount"),
 *      @OA\Property(property="created_at", type="string", format="date-time", description="Transaction creation timestamp")
 * )
 * @OA\Schema(
 *      schema="Account",
 *      title="Account",
 *      description="Account details",
 *      @OA\Property(property="account_id", type="string", format="uuid", description="Account ID (UUID)"),
 *      @OA\Property(property="balance", type="integer", description="Account Balance")
 * )
  * @OA\Schema(
 *     schema="TransactionRequestWithPositiveAmount",
 *     required={"account_id", "amount"},
 *     @OA\Property(property="account_id", type="string", format="uuid", example="0afd02d3-6c59-46e7-b7bc-893c5e0b7ac2"),
 *     @OA\Property(property="amount", type="number", example=7),
 * )
 *  * @OA\Schema(
 *     schema="TransactionRequestWithNegativeAmount",
 *     required={"account_id", "amount"},
 *     @OA\Property(property="account_id", type="string", format="uuid", example="0afd02d3-6c59-46e7-b7bc-893c5e0b7ac2"),
 *     @OA\Property(property="amount", type="number", example=-7),
 * )
 */

 
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
