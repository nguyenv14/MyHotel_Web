<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
   
    protected $dates = [
       'deleted_at' ,
    ]; 
    public $timestamps = false;
    protected $fillable = [
       'start_day','end_day', 'orderer_id', 'payment_id', 'order_status', 'order_code','coupon_name_code' ,'coupon_sale_price' /* Trường Trong Bảng */
    ];
    protected $primaryKey = 'order_id'; /* Khóa Chính */
    protected $table = 'tbl_order'; /* Tên Bảng */
    public function payment()
    {
        return $this->belongsTo('App\Models\Payment', 'payment_id');
    }
    public function orderer()
    {
        return $this->belongsTo('App\Models\Orderer', 'orderer_id');
    }
    public function orderdetails()
    {
        return $this->belongsTo('App\Models\OrderDetails', 'order_code' ,'order_code');
    }
}
