<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Paginated, filterable transaction list for the authenticated user.
     *
     * GET /api/v1/transactions
     * Query params: type (income|expense), category_id, search, per_page
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'type'        => ['nullable', 'in:income,expense'],
            'category_id' => ['nullable', 'integer'],
            'search'      => ['nullable', 'string', 'max:100'],
            'per_page'    => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $query = $request->user()->transactions()->with('category:id,name,color')->latest('transaction_date');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', (int) $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . strip_tags($request->search) . '%');
        }

        $paginated = $query->paginate($request->integer('per_page', 20));

        $transactions = collect($paginated->items())->map(fn ($tx) => [
            'id'               => $tx->id,
            'title'            => $tx->title,
            'amount'           => (float) $tx->amount,
            'type'             => $tx->type,
            'description'      => $tx->description,
            'transaction_date' => $tx->transaction_date->toDateString(),
            'source_type'      => $tx->source_type,
            'category'         => [
                'id'    => $tx->category?->id,
                'name'  => $tx->category?->name,
                'color' => $tx->category?->color,
            ],
        ]);

        return response()->json([
            'status' => 'success',
            'data'   => [
                'transactions' => $transactions,
                'pagination'   => [
                    'current_page' => $paginated->currentPage(),
                    'last_page'    => $paginated->lastPage(),
                    'per_page'     => $paginated->perPage(),
                    'total'        => $paginated->total(),
                ],
            ],
        ], 200);
    }

    /**
     * Create a new transaction with idempotency-key protection.
     *
     * POST /api/v1/transactions
     * Header: X-Idempotency-Key: <uuid>  (strongly recommended from mobile)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'amount'           => ['required', 'numeric', 'min:0.01', 'max:999999999'],
            'type'             => ['required', 'in:income,expense'],
            'category_id'      => ['nullable', 'integer', 'exists:categories,id'],
            'description'      => ['nullable', 'string', 'max:1000'],
            'transaction_date' => ['required', 'date', 'before_or_equal:today'],
        ]);

        // Idempotency: if the mobile client retries the same request (network
        // hiccup, double-tap), return the cached result without creating a duplicate.
        $idempotencyKey = $request->header('X-Idempotency-Key');

        if ($idempotencyKey) {
            $cacheKey = 'idempotency:' . $request->user()->id . ':' . $idempotencyKey;
            $cached   = Cache::get($cacheKey);

            if ($cached) {
                return response()->json($cached, 200)
                    ->header('X-Idempotency-Replayed', 'true');
            }
        }

        $transaction = DB::transaction(function () use ($request, $validated) {
            return $request->user()->transactions()->create($validated);
        });

        $transaction->load('category:id,name,color');

        $payload = [
            'status'  => 'success',
            'message' => 'Transaction created.',
            'data'    => [
                'id'               => $transaction->id,
                'title'            => $transaction->title,
                'amount'           => (float) $transaction->amount,
                'type'             => $transaction->type,
                'description'      => $transaction->description,
                'transaction_date' => $transaction->transaction_date->toDateString(),
                'source_type'      => $transaction->source_type,
                'category'         => [
                    'id'    => $transaction->category?->id,
                    'name'  => $transaction->category?->name,
                    'color' => $transaction->category?->color,
                ],
            ],
        ];

        if ($idempotencyKey) {
            Cache::put($cacheKey, $payload, now()->addHours(24));
        }

        return response()->json($payload, 201);
    }
}
