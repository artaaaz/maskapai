<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoApiController extends Controller
{
    /**
     * GET /api/promos - List all promos
     */
    public function index()
    {
        $promos = Promo::orderBy('created_at', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $promos
        ]);
    }

    /**
     * GET /api/promos/active - List active promos only
     */
    public function active()
    {
        $promos = Promo::where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->where(function ($q) {
                $q->where('usage_limit', 0)
                  ->orWhere('used_count', '<', 'usage_limit');
            })
            ->orderBy('valid_until')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $promos
        ]);
    }

    /**
     * POST /api/promos/validate - Validate a promo code
     */
    public function validate(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $code = strtoupper($request->code);
        $totalAmount = (float) $request->total_amount;

        $promo = Promo::where('code', $code)->first();

        if (!$promo) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo tidak ditemukan.',
            ], 404);
        }

        // Check if promo is active
        if (!$promo->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo tidak aktif.',
            ], 400);
        }

        // Check date validity
        if ($promo->valid_from > now()) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo belum berlaku.',
            ], 400);
        }

        if ($promo->valid_until < now()) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo sudah kadaluarsa.',
            ], 400);
        }

        // Check quota
        if ($promo->usage_limit > 0 && $promo->used_count >= $promo->usage_limit) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota promo sudah habis.',
            ], 400);
        }

        // Check minimum transaction
        if ($totalAmount < $promo->min_transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum transaksi Rp' . number_format($promo->min_transaction, 0, ',', '.') . ' belum terpenuhi.',
                'min_transaction' => (float) $promo->min_transaction,
            ], 400);
        }

        // Calculate discount
        $discountAmount = $promo->discount_type === 'percentage'
            ? ($totalAmount * $promo->discount_value / 100)
            : (float) $promo->discount_value;

        // Apply max discount cap
        if ($promo->max_discount > 0 && $discountAmount > $promo->max_discount) {
            $discountAmount = (float) $promo->max_discount;
        }

        $finalPrice = max(0, $totalAmount - $discountAmount);

        return response()->json([
            'success' => true,
            'message' => 'Kode promo berhasil diterapkan!',
            'data' => [
                'promo' => $promo,
                'discount_amount' => round($discountAmount, 2),
                'final_price' => round($finalPrice, 2),
                'total_amount' => $totalAmount,
            ]
        ]);
    }
}