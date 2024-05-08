<?php
namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Customers;
use App\Models\Evaluate;
use App\Models\GalleryHotel;
use App\Models\GalleryRoom;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\ServiceCharge;
use App\Models\TypeRoom;
use Google\Service\HangoutsChat\Resource\Rooms;
use Illuminate\Http\Request;

class ApiHotelController extends Controller{
    public function getHotelList(Request $request){
        $result = Hotel::where("hotel_type", $request->hotel_type)->get();
        if($result){
            $data = $this->convertDataToJson($result);
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

    public function getHotelById(Request $request){
        $result = Hotel::where("hotel_id", $request->hotel_id)->get();
        if(count($result) > 0){
            $data = $this->convertDataToJson($result);
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

    public function getHotelListByArea(Request $request){
        $result = Hotel::where("area_id", $request->area_id)->get();
        if($result){
            // dd($result);
            $data = $this->convertDataToJson($result);
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



