<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPaymentMethod;
use App\Http\Service\VNPayService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Redirect;

class CartController extends Controller
{
    protected $vnpaySerivce;

    public function __construct(VNPayService $vnpaySerivce)
    {
        $this->vnpaySerivce = $vnpaySerivce;
    }

    public function payBooking(Request $request, $id)
    {
        // Validation rules
        $rules = [
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'address' => 'required|string',
        ];
        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // Begin a database transaction
        DB::beginTransaction();
        try {
            // Decode the seats JSON
            $seatsArray = json_decode($request->seats, true);

            // Get the number of elements in the seats array
            $numberOfElements = count($seatsArray);

            // Extract seatNums from the seats array
            $seatNums = array_column($seatsArray, 'seatNum');
            $uniqueFirstCharacters = array_unique(array_map(function($seatNum) {
                return substr($seatNum, 0, 1); // Extract the first character
            }, $seatNums));
            $uniqueFirstCharacters = implode(',', $uniqueFirstCharacters);
            // Create a comma-separated string from seatNums
            $name = implode(', ', $seatNums);
            // Create an order record
            $order = Order::create([
                'name' => $request->name,
                'seat_name' => $name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'user_id' => Auth::user()->id,
                'total' => $request->totalPrice,
                'payment_method' => $request->payment_method
            ]);
            // Create an order item record
            $orderItem = OrderItem::create([
                'name' => $uniqueFirstCharacters,
                'seat_name' => $name,
                'qty' => $numberOfElements,
                'price' => $request->totalPrice,
                'order_id' => $order->id,
                'match_id' => $id,
            ]);

            // Create an order payment method record
            $orderPaymentMethod = OrderPaymentMethod::create([
                'total' => $request->totalPrice,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'order_id' => $order->id,
            ]);

            // Update the user's phone number
            $user = User::find(Auth::id());
            $user->phone = $request->phone;
            $user->save();
            $paymentMethod = $request->payment_method;

            if ($paymentMethod === 'VNBANK' || $paymentMethod === 'INTCARD') {
                $vnp_Url = $this->vnpaySerivce->getVNPayUrl($order, $paymentMethod, $id);
               

                // Commit the database transaction
                DB::commit();
                // Redirect to the VNPay URL
                return redirect()->to($vnp_Url);
            } else {
                DB::rollBack();
                return redirect()->route('home')->with('error', 'Unsupported payment method.');
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return redirect()->route('home')->with('error', 'An error occurred while processing your request.');
        }
    }
}
