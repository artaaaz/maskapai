<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoValidationController extends Controller
{
    public function validate(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $promo = Promo::where('code', strtoupper($request->code))->first();

        if (!$promo) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo tidak ditemukan.',
            ]);
        }

        if (!$promo->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo sudah tidak berlaku atau kuota habis.',
            ]);
        }

        if ($request->total_amount < $promo->min_purchase) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal pembelian Rp' . number_format($promo->min_purchase, 0, ',', '.') . ' untuk menggunakan promo ini.',
            ]);
        }

        $discount = $promo->calculateDiscount($request->total_amount);

        return response()->json([
            'success' => true,
            'data' => [
                'promo_id' => $promo->id,
                'promo_code' => $promo->code,
                'discount_amount' => $discount,
                'discount_type' => $promo->discount_type,
                'discount_value' => $promo->discount_value,
            ],
            'message' => 'Promo berhasil diterapkan! Diskon Rp' . number_format($discount, 0, ',', '.'),
        ]);
    }
}