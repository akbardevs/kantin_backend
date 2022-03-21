<?php

namespace App\Http\Controllers;

use App\Models\Kasirs;
use App\Models\Products;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    //
    public function index($id)
    {
        return Products::where('id_kasir', $id)->get();
    }

    public function image($filename)
    {
        if (File::exists(storage_path('app/public/' . $filename))) {
            $path = storage_path('app/public/' . $filename);
            $file = File::get($path);
            $type = File::mimeType($path);

            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);

            // return $response;
            return $response;
        }
    }

    public function pay(Request $request)
    {
        Log::info($request);
        try {
            Transaction::create([
                'list_produk' => $request['list_produk'],
                'nama' => $request['nama'],
                'id_kasir' => $request['id_kasir'],
                'nohp' => $request['nohp'],
                'metode' => $request['metode'],
                'total' => $request['total'],
            ]);
            $kantin = Kasirs::find($request['id_kasir']);
            $kantin['saldo'] = $kantin['saldo'] + $request['total'];
            $kantin->save();
            $client = new \GuzzleHttp\Client();
            $res = $client->get('https://pntol.securitychatboot.xyz/etoll/newPayment2.php?metode=' . $request['metode'] . '&nohp=' . $request['nohp'] . '&total=' . $request['total']);
            Log::info($res->getBody());
        } catch (Exception $e) {
            Log::info('errpr');
            Log::info($e);
        }

        return $request;
    }
    
    public function testingcallback(Request $request)
    {
        Log::info($request);
        

        return $request;
    }
}
