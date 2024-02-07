<?php

namespace Tests\Feature;

use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class TransactionTest extends TestCase
{

    /***
    public function testHasItemInTransactions(): void
    {
        // navigate to the transaction endpoint
        $response = $this->get('/api/transactions');

        $transactions = new Transaction(
            [
                'transaction_id' => "a238e7e4-c52e-49e9-8b2f-9959aa71f8a8",
                'account_id' => "256ee5de-c132-487a-8288-d8b6c20ac934",
                'amount' => 7,
                "created_at" => "2024-02-06 03:40:15"
            ],
            [
                'transaction_id' => "32858f63-c6b6-451f-b93a-74e14e6ba326",
                'account_id' => "e49f2354-9229-4a65-816f-7ace2356f4ec",
                'amount' => 4,
                "created_at" => "2024-02-06 03:40:15"
            ],
            [
                'transaction_id' => "9d21ff1d-3f4c-4901-b175-9ff03ddd8c18",
                'account_id' => "e49f2354-9229-4a65-816f-7ace2356f4ec",
                'amount' => -4,
                "created_at" => "2024-02-06 03:40:15"
            ],
        );
        
        // $transactions = Transaction::all();

        $this->assertTrue($transactions->has([
            'transaction_id' => "32858f63-c6b6-451f-b93a-74e14e6ba326",
            'account_id' => "e49f2354-9229-4a65-816f-7ace2356f4ec",
            'amount' => 4,
            "created_at" => "2024-02-06 03:40:15"
        ]));

        $this->assertFalse($transactions->has([
            'transaction_id' => "32858f63-c6b6-451f-b93a-xxxxxxxx",
            'account_id' => "e49f2354-9229-4a65-816f-xxxxxx",
            'amount' => 4,
            "created_at" => "2024-02-06 03:xx:xx"
        ]));



        $response->assertStatus(200);
    }
    **/

    public function testGetTransactions(){
        // url endpoint should be /api/transactions
        $response = $this->get('/api/transactions');

        // should return 200 code
        $response->assertStatus(200);
    }

    public function testCreateTransaction()
    {
        // Data for the new transaction
        $uuid = Str::uuid();
        $requestData = [
            'account_id' => $uuid,
            'amount' => 10,
        ];

        // Send a POST request to create a new transaction
        $response = $this->json(
            'POST', '/api/transactions',
            $requestData,
            ['Content-Type' => 'application/json']
        );

        // Assert that the Content-Type header is present
        $response->assertHeader('Content-Type', 'application/json', 'ERROR: Asserting that the Content-Type header is present');

        // Assert that the request has account_id of type uuid
        $this->assertDatabaseHas('transactions', ['account_id' => $requestData['account_id']]);
        
        // Assert that the request has amount
        $this->assertDatabaseHas('transactions', ['amount' => $requestData['amount']]);


        // Assert that the transaction was saved in the database
        $this->assertDatabaseHas('transactions', [
            'account_id' => $uuid,
            'amount' => 10,
        ]);

        // Assert that the account balance was updated
        $this->assertDatabaseHas('accounts', [
            'account_id' => $uuid,
            'balance' => 10,
        ]);

        // Assert that specific details in the response JSON
        $response->assertJson([
            'account_id' => $uuid,
            'amount' => 10,
        ]);

        // Assert that the request was successful (status 201)
        $response->assertStatus(201);

    }
}
