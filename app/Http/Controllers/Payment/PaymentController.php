<?php

namespace App\Http\Controllers\Payment;

use App\Models;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use General;
use App\Services\Midtrans; // => letakkan pada bagian atas class

class PaymentController extends Controller
{
    
    public function show(Request $request)
    {
        $subs = Models\Subs::first();
        //$snapToken = $subs->payment->snap_token;
        //if (empty($snapToken)) {
            // Jika snap token masih NULL, buat token snap dan simpan ke database
        $uuid = 'INV/'.date('Ymd').'/'.$this->numberToRomanRepresentation(date('m')).'/'.$this->numberToRomanRepresentation(date('d')).'/'.$request->token;

        $params = array(
            'enable_payments' => ["credit_card", "cimb_clicks",
            "bca_klikbca", "bca_klikpay", "bri_epay", "echannel", "permata_va",
            "bca_va", "bni_va", "bri_va", "other_va", "gopay", "indomaret",
            "danamon_online", "akulaku", "shopeepay"],
            'transaction_details' => array(
                'order_id' => $uuid,
                'gross_amount' => $subs->harga - (($subs->diskon/100)*$subs->harga),
                'id' => $subs->id_packet,
                'price' => $subs->harga,
                'quantity' => 1,
                'name' => $subs->packet->nama,
                'first_name' => $subs->user->nama,
                'email' => $subs->user->email,
            )
        );
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $midtrans = \Midtrans\Snap::createTransaction($params);
        

        //}

        return $midtrans;
    }

    private function numberToRomanRepresentation($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
}
