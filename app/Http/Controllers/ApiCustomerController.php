<?php
namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Customers;
use Illuminate\Http\Request;

class ApiCustomerController extends Controller{
    public function logIn(Request $request){
        $result = Customers::where('customer_password', md5($request->customer_password))->Where('customer_email', $request->customer_email)->first();
        if($result){
            // $data[] = array(
            //     "customer_id" => $result->customer_id,
            //     "customer_name" => $result->customer_name,
            //     "customer_email" => $result->customer_email,
            //     "customer_password" => $result->customer_password,2
            // );
            return response()->json([
                'status_code' => 200,
                'message' => 'Đăng nhập thành công!',
                'data' => $result,
            ]) ;
        }else{
            return response()->json([
                'status_code' => 404,
                'message' => 'Sai email đăng nhập hoặc mật khẩu!',
                'data' => null,
            ]) ;
        }
    }
}

