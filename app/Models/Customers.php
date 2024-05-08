<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customers extends Model
{
   use SoftDeletes;

   public $timestamps = false;

   protected $dates = [
      'deleted_at' ,
   ]; 

   protected $fillable = [
    'customer_name' ,  'customer_phone' , 'customer_email' ,'customer_password' , 'customer_status' , 'customer_ip' ,   'customer_located' ,   'customer_device' ,  /* Trường Trong Bảng */
   ]; 
   protected $primaryKey =  'customer_id'; /* Khóa Chính */
   protected $table =   'tbl_customers'; /* Tên Bảng */

   public function social(){
      return $this->belongsTo('App\Models\Social', 'customer_id' , 'user');
  }
  public function socialTrashed(){
   return $this->belongsTo('App\Models\Social', 'customer_id' , 'user')->onlyTrashed();
  }

}
