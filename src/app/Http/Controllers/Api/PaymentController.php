<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Payment::with(['customer', 'paymentMethod'])->get()
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,completed,failed,refunded',
            'description' => 'required|string',
            'payment_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $payment = Payment::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $payment->load(['customer', 'paymentMethod'])
        ], 201);
    }

    public function show($id)
    {
        $payment = Payment::with(['customer', 'paymentMethod'])->find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $payment
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'customer_id' => 'sometimes|required|exists:customers,id',
            'payment_method_id' => 'sometimes|required|exists:payment_methods,id',
            'amount' => 'sometimes|required|numeric|min:0',
            'status' => 'sometimes|required|in:pending,completed,failed,refunded',
            'description' => 'sometimes|required|string',
            'payment_date' => 'sometimes|required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $payment->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $payment->fresh()->load(['customer', 'paymentMethod'])
        ], 200);
    }

    public function destroy($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        $payment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Payment deleted successfully'
        ], 200);
    }
}
