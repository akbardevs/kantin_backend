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
        // try {
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
            $data_param = [

                    "external_id"=>"ovo-testing ".mt_rand(100000, 999999),
                    "amount"=>$request['total'],
                    "phone"=>$request['nohp'],
                    "ewallet_type"=>"OVO"
                  
            ];
            $httpClient = new \GuzzleHttp\Client();
            $response = null;
            try {
                $request =
                    $httpClient->request('POST', 'https://api.xendit.co/ewallets', [
                        'headers' => [
                            'Accept'     => 'application/json',
                            'Authorization'     => 'Basic eG5kX2RldmVsb3BtZW50X3FFWE92ajlwM0QwcTVkU2VTZkpSZDAzMU9ZZENyc0ZBZmVtb09aa2J5UWNSZjQwQVRzeEhrT29CR2hpNng6',
                        ],
                        'form_params' => $data_param
                    ]);
        
                $response = json_decode($request->getBody()->getContents());
                

            } catch (Exception $e) {
                Log::alert($e);

            }
            // $client = new \GuzzleHttp\Client();
            // $res = $client->get('https://pntol.securitychatboot.xyz/etoll/newPayment2.php?metode=' . $request['metode'] . '&nohp=' . $request['nohp'] . '&total=' . $request['total']);
            // Log::info($res->getBody());
        // } catch (Exception $e) {
        //     Log::info('errpr');
        //     Log::info($e);
        // }

        return $request;
    }
    
    public function testingcallback(Request $request)
    {
        Log::info($request);
        

        return $request;
    }
}
