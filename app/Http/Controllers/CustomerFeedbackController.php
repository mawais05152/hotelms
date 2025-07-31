<?php

namespace App\Http\Controllers;

use App\Models\CustomerFeedback;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerFeedbackController extends Controller
{
    public function index() {
        $feedbacks = CustomerFeedback::with('order')->latest()->get();
        return view('customer_feedback.index', compact('feedbacks'));
    }

    public function create() {
        $orders = Order::all();
        return view('customer_feedback.create', compact('orders'));
    }

    public function store(Request $request) {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'feedback_text' => 'nullable|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $feedback = new CustomerFeedback();
        // dd($feedback);
        $feedback->order_id = $request->order_id;
        $feedback->feedback_text = $request->feedback_text;
        $feedback->rating = $request->rating;
        $feedback->save();

        return redirect()->route('customer-feedback.index')->with('success', 'Feedback added successfully.');
    }

    public function edit($id) {
        $feedback = CustomerFeedback::findOrFail($id);
        $orders = Order::all();
        return view('customer_feedback.edit', compact('feedback', 'orders'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'feedback_text' => 'nullable|string',
            'rating' => 'required|in:Good,Bad,Neutral',
        ]);

        $feedback = CustomerFeedback::findOrFail($id);
        $feedback->order_id = $request->order_id;
        $feedback->feedback_text = $request->feedback_text;
        $feedback->rating = $request->rating;
        $feedback->save();

        return redirect()->route('customer-feedback.index')->with('success', 'Feedback updated successfully.');
    }

    public function destroy($id) {
        $feedback = CustomerFeedback::findOrFail($id);
        $feedback->delete();

        return redirect()->route('customer-feedback.index')->with('success', 'Feedback deleted successfully.');
    }
}
