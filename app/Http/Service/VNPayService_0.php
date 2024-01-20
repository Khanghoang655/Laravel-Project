<?php

namespace App\Http\Service;

use App\Events\OrderEvent;
use App\Events\PaymentSuccessful;
use App\Models\FootballMatch;
use App\Models\Order;
use App\Models\Seat_rows;
use Illuminate\Http\Request;

class VNPayService
{
    public function callBackVNPay(Request $request, $id)
    {
        $_GET['vnp_Amount'];
        $orderId = (int) $request->vnp_TxnRef - 2000;
        $order = Order::find($orderId);
        $seatsArray = Seat_rows::where('match_id', $id)->get()->toArray();

        foreach ($seatsArray as &$value) {
            if ($order && $value && $value['seat_name']) {
                $orderNames = explode(', ', $order->name);
                $seatNameObject = json_decode($value['seat_name']);

                $availableSeats = $seatNameObject->available;
                $unavailableSeats = $seatNameObject->unavailable;

                $matchingSeats = array_intersect($orderNames, $availableSeats);

                if (!empty($matchingSeats)) {
                    $availableSeats = array_diff($availableSeats, $matchingSeats);
                    $unavailableSeats = array_merge($unavailableSeats, $matchingSeats);

                    $seatNameObject->available = array_values($availableSeats);
                    $seatNameObject->unavailable = array_values($unavailableSeats);

                    $value['seat_name'] = json_encode($seatNameObject);

                    Seat_rows::where('id', $value['id'])->update(['seat_name' => $value['seat_name']]);
                }
            }
        }
        $orderPaymentMethod = $order->order_payment_methods[0];
        $responseCode = $request->vnp_ResponseCode;
        if ($responseCode === '00') {
            event(new OrderEvent($order));
            $order->status = 'success';
            $order->save();

            $orderPaymentMethod->status = 'success';
            $orderPaymentMethod->save();
            return redirect()->route('home')->with('msg', 'đặt vé thành công');
        } else {
            $order->status = 'cancel';
            $order->save();
            $orderPaymentMethod->status = 'cancel';
            $orderPaymentMethod->save();

            return redirect()->route('home')->with('msg', 'Đặt vé thất bại');
        }
    }

    public function getVNPayUrl(Order $order, string $type, $id): string
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $orderId = $order->id + 2000;
        $vnp_TxnRef = $orderId; //Mã giao dịch thanh toán tham chiếu của merchant
        $vnp_Amount = $order->total * 23500 * 100; // Số tiền thanh toán
        $vnp_Locale = 'vn'; //Ngôn ngữ chuyển hướng thanh toán
        $vnp_BankCode = $type; //Mã phương thức thanh toán
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR']; //IP Khách hàng thanh toán
        $startTime = date("YmdHis");
        $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => config('myconfig.vnpay.tmn_code'),
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => "Thanh toan GD:" . $vnp_TxnRef,
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => route('call.back.vnpay', ['id' => $id]),
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $expire,
        );
        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = config('myconfig.vnpay.url') . "?" . $query;
        $vnpSecureHash = hash_hmac('sha512', $hashdata, config('myconfig.vnpay.hash_secret'));
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

        return $vnp_Url;
    }
}
