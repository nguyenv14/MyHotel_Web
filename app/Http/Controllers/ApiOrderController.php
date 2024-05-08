<?php
namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Customers;
use App\Models\Evaluate;
use App\Models\GalleryHotel;
use App\Models\GalleryRoom;
use App\Models\Hotel;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Orderer;
use App\Models\Payment;
use App\Models\Room;
use App\Models\ServiceCharge;
use App\Models\TypeRoom;
use Google\Service\HangoutsChat\Resource\Rooms;
use Illuminate\Http\Request;

class ApiOrderController extends Controller{
    public function getOrderListByCustomerId(Request $request){
        $orderer = Orderer::where('customer_id', $request->customer_id)->get('orderer_id')->toArray();
        $list_id_orderer = array();
        foreach ($orderer as $key => $v_orderer) {
            $list_id_orderer[$key] = $v_orderer['orderer_id'];
        }
        if($request->order_status == 0){
            $orders = Order::wherein('orderer_id', $list_id_orderer)->where("order_status", 0)->orderby('order_id', 'DESC')->get();
        }else if($request->order_status == 1){
            $orders = Order::wherein('orderer_id', $list_id_orderer)->whereIn("order_status", [1, 2])->orderby('order_id', 'DESC')->get();
        }else{
            $orders = Order::wherein('orderer_id', $list_id_orderer)->whereIn("order_status", [-1, -2])->orderby('order_id', 'DESC')->get();
        }
        if($orders->count() > 0){
            $data = $this->convertOrderToJson($orders);
            return response()->json([
                'status_code' => 200,
                'message' => 'Thành công!',
                'data' => $data,
            ]);
        }else{
            return response()->json([
                'status_code' => 404,
                'message' => 'Không truy xuất được dữ liệu',
                'data' => null,
            ]) ;
        }
    }

    public function cancelOrderByCustomer(Request $request){
        $order = Order::where("order_id", $request->order_id)->first();
        
        $order->order_status = -2;
        $order->save();
        
        $orderer = Orderer::where('customer_id', $request->customer_id)->get('orderer_id')->toArray();
        $list_id_orderer = array();
        foreach ($orderer as $key => $v_orderer) {
            $list_id_orderer[$key] = $v_orderer['orderer_id'];
        }
        $orders = Order::wherein('orderer_id', $list_id_orderer)->where("order_status", 0)->orderby('order_id', 'DESC')->get();
        if($orders->count() > 0){
            $data = $this->convertOrderToJson($orders);
            return response()->json([
                'status_code' => 200,
                'message' => 'Thành công!',
                'data' => $data,
            ]);
        }else{
            return response()->json([
                'status_code' => 404,
                'message' => 'Không truy xuất được dữ liệu',
                'data' => null,
            ]) ;
        }
    }

    public function evaluateCustomer(Request $request){
        $customer = Customers::where("customer_id", $request->customer_id)->first();
        
        $evaluate = new Evaluate();
        $evaluate->customer_id = $customer->customer_id;
        $evaluate->customer_name = $customer->customer_name;
        $evaluate->hotel_id = $request->hotel_id;
        $evaluate->room_id = $request->room_id;
        $evaluate->type_room_id = $request->type_room_id;
        $evaluate->evaluate_title = $request->evaluate_content;
        $evaluate->evaluate_content = $request->evaluate_content;
        $evaluate->evaluate_loaction_point = $request->evaluate_loaction_point;
        $evaluate->evaluate_service_point = $request->evaluate_service_point;
        $evaluate->evaluate_price_point = $request->evaluate_price_point;
        $evaluate->evaluate_sanitary_point = $request->evaluate_sanitary_point;
        $evaluate->evaluate_convenient_point = $request->evaluate_convenient_point;
        $evaluate->save();

        $order = Order::where("order_id", $request->order_id)->first();
        $order->order_status = 2;
        $order->save();

        $orderer = Orderer::where('customer_id', $request->customer_id)->get('orderer_id')->toArray();
        $list_id_orderer = array();
        foreach ($orderer as $key => $v_orderer) {
            $list_id_orderer[$key] = $v_orderer['orderer_id'];
        }
        $orders = Order::wherein('orderer_id', $list_id_orderer)->whereIn("order_status", [1, 2])->orderby('order_id', 'DESC')->get();
        if($orders->count() > 0){
            $data = $this->convertOrderToJson($orders);
            return response()->json([
                'status_code' => 200,
                'message' => 'Thành công!',
                'data' => $data,
            ]);
        }else{
            return response()->json([
                'status_code' => 404,
                'message' => 'Không truy xuất được dữ liệu',
                'data' => null,
            ]) ;
        }
    }

    public  function convertOrderToJson($result){
        foreach($result as $rs){
            $data_order = null;
            $orderer = Orderer::where("orderer_id", $rs->orderer_id)->first();
            $payment = Payment::where("payment_id", $rs->payment_id)->first();
            $order_detail = OrderDetails::where("order_code", $rs->order_code)->first();
            $hotel = Hotel::where("hotel_id", $order_detail->hotel_id)->first();
            $room = Room::where("room_id", $order_detail->room_id)->first();
            $type_room = TypeRoom::where("type_room_id", $order_detail->type_room_id)->first(); 
            $gallery_room = GalleryRoom::where("room_id", $order_detail->room_id)->first();
            $data_order = array(
                "order_details_id" => $order_detail->order_details_id,
                "order_code" => $order_detail->order_code,
                "hotel_id" => $order_detail->hotel_id,
                "hotel_name" => $order_detail->hotel_name,
                "hotel" => $hotel,
                "room_id" => $order_detail->room_id,
                "room_name" => $order_detail->room_name,
                "room" => $room,
                "type_room_id" => $order_detail->type_room_id,
                "roomType" => $type_room,
                "price_room" => $order_detail->price_room,
                "hotel_fee" => $order_detail->hotel_fee,
                "room_image" => $gallery_room->gallery_room_image,
                "created_at" => $order_detail->created_at,
            );
            $data[] = array(
                "orderId" => $rs->order_id,
                "startDay" => $rs->start_day,
                "endDay" => $rs->end_day,
                "ordererId" => $rs->orderer_id,
                "paymentId" => $rs->payment_id,
                "payment" => $payment,
                "orderer" => $orderer,
                "orderDetail" => $data_order,
                "orderStatus" => $rs->order_status,
                "orderCode" => $rs->order_code,
                "couponNameCode" => $rs->coupon_name_code,
                "couponSalePrice" => $rs->coupon_sale_price,
                "createdAt" => $rs->created_at,
            );
        }
        return $data;
    }

    

    public function convertDataToJson($result){
        foreach($result as $dt){
            $evaluates = Evaluate::where("hotel_id", $dt->hotel_id)->get();
            $service = ServiceCharge::where("hotel_id", $dt->hotel_id)->get();
            $rooms = Room::where('hotel_id', $dt->hotel_id)->get();
            $room_data = [];
            $gallery_hotel = GalleryHotel::where("hotel_id", $dt->hotel_id)->get();
            foreach($rooms as $room){
                $roomTypes = TypeRoom::where("room_id", $room->room_id)->get();
                $gallery_room = GalleryRoom::where("room_id", $room->room_id)->get();
                $room_data[] = array(
                    "room_id" => $room->room_id,
                    "hotel_id" => $room->hotel_id,
                    "room_name" => $room->room_name,
                    "gallery_room" => $gallery_room,
                    "roomTypes" => $roomTypes,
                    "room_amount_of_people" => $room->room_amount_of_people,
                    "room_acreage" => $room->room_acreage,
                    "room_view" => $room->room_view,
                    "room_status" => $room->room_status,
                    "created_at" => $room->created_at,
                    "updated_at" => $room->updated_at,
                    "deleted_at" => $room->deleted_at,
                );
            }

            $data[] = array(
                "hotel_id" => $dt->hotel_id,
                "hotel_name" => $dt->hotel_name,
                "hotel_rank" => $dt->hotel_rank,
                "hotel_type" => $dt->hotel_type,
                "brand_id" => $dt->brand_id,
                "evaluates" => $evaluates,
                "service_change" => $service,
                "brand" => $dt->brand,
                "rooms" => $room_data,
                "area" => $dt->area,
                "gallery_hotel" => $gallery_hotel,
                "hotel_placedetails" => $dt->hotel_placedetails,
                "hotel_linkplace" => $dt->hotel_linkplace,
                "hotel_jfameplace" => $dt->hotel_jfameplace,
                "hotel_image" => $dt->hotel_image,
                "hotel_desc" => $dt->hotel_desc,
                "hotel_tag_keyword" => $dt->hotel_tag_keyword,
                "hotel_view" => $dt->hotel_view,
                "hotel_status" => $dt->hotel_status,
                "created_at" => $dt->created_at,
                "updated_at" => $dt->updated_at,
            );

        }
        return $data;
    }
}



