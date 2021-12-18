<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\Base64Image;
use App\Models\UserActivationStatus;
use App\Services\Users\CreateUserService;
use App\Services\Users\ValidateSignUpUserService;
use App\Services\Users\ValidateUpdateUserService;
use App\Services\Exceptions\InvalidParameterException;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
  //
  public function showIndex()
  {
    return view('welcome');
  }
  public function update(Request $request)
  {
    try {
      $upload = new Base64Image;
      $validateSelfSignUpSvc = new ValidateUpdateUserService($request->all());
      $validParams = $validateSelfSignUpSvc->execute();
      if ($validParams) {
        $user = User::where('id', $request['user']['id'])->update([
          'name' => $request['user']['name'],
          'image' => $request['user']['image'],
          'email' => $request['user']['email'],
          'phone' => $request['user']['phone'],
          'village_id' => $request['user']['village_id'],
          'jkel' => $request['user']['jkel']
        ]);
        if ($request['image'] && $request['image'] != null) {
          $upload->save($request['image'], storage_path('app/public/'), $request->user['image']);
        }
        if ($user) {
          // $request->session()->put("user", $user);
          return redirect()->intended("/profil/" . $request['user']['id'] . "/limit")->with("message", ["from" => "update", "data" => $user]);
        }
      }
      return redirect()->back()->with("error", ["from" => "update", "data" => $user, 'ok' => 'ok']);
      // return redirect()->back()->with("message", ["from" => "update", "data" => $user]);
    } catch (InvalidParameterException $e) {
      return back()->with("error", [
        "from" => "update",
        "data" => $e->getErrors()
      ]);
    } catch (\Exception $e) {
      $errorMsg = $e->getMessage();

      return back()->with("error", [
        "from" => "update",
        "data" => $errorMsg
      ]);
    }
  }
  public function listStorage()
  {
    $files = Storage::disk('local')->allFiles('storage');
    return $files;
  }

  public function getLog()
  {
    if (file_exists(storage_path('logs/laravel.log'))) {
      $path = storage_path('logs/laravel.log');
      $downloadFileName = env('APP_ENV') . '.laravel.log';

      return response()->download($path, $downloadFileName);
    }
  }

  public function testDB()
  {
    return User::all();
  }

  public function clearLog()
  {
    exec('rm -f ' . storage_path('logs/*.log'));

    exec('rm -f ' . base_path('*.log'));

    return 'Logs have been cleared!';
  }
}
