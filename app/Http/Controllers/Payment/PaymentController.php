<?php

namespace App\Http\Controllers\Payment;

use App\Models;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\Midtrans\CreateSnapTokenService; // => letakkan pada bagian atas class

class PaymentController extends Controller
{
    
    public function show()
    {
        $subs = Models\Subs::first();
        //$snapToken = $subs->payment->snap_token;
        //if (empty($snapToken)) {
            // Jika snap token masih NULL, buat token snap dan simpan ke database
        $uuid = 'INV/'.date('Ymd').
        $data = [
            'order_id' => $uuid,
            'gross_amount' => $subs->harga - (($subs->diskon/100)*$subs->harga),
            'id' => $subs->id_packet,
            'price' => $subs->harga,
            'quantity' => 1,
            'name' => $subs->packet->nama,
            'first_name' => $subs->user->nama,
            'email' => $subs->user->email,
        ];

        $midtrans = new CreateSnapTokenService($data);
        $snapToken = $midtrans->getSnapToken();
        
        $payment = Models\Payment::create([
            'id_subs' => $subs->id,
            'stat_pembayaran' => 0,
            'snap_token' => $snapToken,
            'tgl_pembayaran' => date('Y/m/d'),
            'uuid' => $uuid
        ]);

        //}

        return view('payment', compact('payment', 'snapToken'));
    }
}
