@extends('admin.admin_layout')
@section('admin_content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-crosshairs-gps"></i>
            </span> Quản Lý Khách Sạn
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <i class="mdi mdi-timetable"></i>
                    <span><?php
                    $today = date('d/m/Y');
                    echo $today;
                    ?></span>
                </li>
            </ul>
        </nav>
    </div>
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 style="margin-top: -15px" class="card-title">Thêm Khách Sạn</h4>
                <form id="form-hotel" action="{{ 'save-hotel' }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="">Tên Khách Sạn</label>
                        <input id="name_hotel" type="text" name="hotel_name" class="form-control" id=""
                            placeholder="Tên Khách Sạn" value="{{ old('name_hotel') }}">
                        <span class="text-danger form-message"></span>
                    </div>
                    <div class="form-group">
                        <label for="">Hạng Khách Sạn</label>
                        <select class="form-control m-bot15" name="hotel_rank">
                            <option value="1">1 Sao</option>
                            <option value="2">2 Sao</option>
                            <option value="3">3 Sao</option>
                            <option value="4">4 Sao</option>
                            <option value="5">5 Sao</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Loại Khách Sạn</label>
                        <select class="form-control m-bot15" name="hotel_type">
                            <option value="1">Khách Sạn </option>
                            <option value="2">Khách Sạn Căn Hộ</option>
                            <option value="3">Khu Nghỉ Dưỡng</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Thương Hiệu Khách Sạn</label>
                        <select class="form-control m-bot15" name="brand_id">
                            @foreach ($brands as $key => $brand)
                                <option value="{{ $brand->brand_id }}">{{ $brand->brand_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="">Khu Vực Khách Sạn</label>
                        <select class="form-control m-bot15" name="area_id">
                            @foreach ($areas as $key => $area)
                                <option value="{{ $area->area_id }}">{{ $area->area_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Vị Trí Chi Tiết</label>
                        <input id="hotel_placedetails" type="text" name="hotel_placedetails" class="form-control"
                            placeholder="Vị Trí Chi Tiết" value="{{ old('hotel_placedetails') }}">
                        <span class="text-danger form-message"></span>
                    </div>

                    <div class="form-group">
                        <label for="">Link Vị Trí Google Map</label>
                        <input id="hotel_linkplace" type="text" name="hotel_linkplace" class="form-control" 
                            placeholder="Link Vị Trí Google Map" value="{{ old('hotel_linkplace') }}">
                        <span class="text-danger form-message"></span>
                    </div>

                    <div class="form-group">
                        <label for="">Jframe Vị Trí Google Map</label>
                        <input id="hotel_jfameplace" type="text" name="hotel_jfameplace" class="form-control"
                            placeholder="Jframe Vị Trí Google Map" value="{{ old('hotel_jfameplace') }}">
                        <span class="text-danger form-message"></span>
                    </div>

                    <div class="form-group">
                        <label for="hotel_image" class="form-label">Tải Ảnh Lên</label>
                        <input id="img_hotel" class="form-control" type="file" id="hotel_image" type="file"
                            name="hotel_image">
                        <span class="text-danger form-message"></span>
                    </div>

                    <div class="form-group">
                        <label for="exampleTextarea1">Mô Tả Khách Sạn</label>
                        <textarea id="desc_hotel" rows="8" class="form-control" name="hotel_desc"></textarea>
                        <span class="text-danger form-message"></span>
                    </div>

                    <div class="form-group">
                        <label for="exampleTextarea1">Từ Khóa - Tag Khách Sạn (SEO)</label>
                        <textarea id="tag_keyword_hotel" rows="8" class="form-control" name="hotel_tag_keyword"></textarea>
                        <span class="text-danger form-message"></span>
                    </div>

                    <div class="form-group">
                        <label for="">Hiễn thị</label>
                        <select class="form-control" name="hotel_status">
                            <option value="1">Hiện</option>
                            <option value="0">Ẩn</option>
                        </select>
                    </div>
                 
                    <button type="submit" class="btn btn-gradient-primary me-2 form-hotel-submit">Xác Nhận</button>
                </form>
            </div>
        </div>
    </div>
    {{-- <script>
        ClassicEditor
            .create(document.querySelector('#hotel_desc'))
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });
    </script> --}}
    <script>
        Validator({
            form: '#form-hotel',
            errorSelector: '.form-message',
            rules: [
                Validator.isRequired('#name_hotel', 'Vui lòng nhập tên khách sạn'),
                Validator.isRequired('#img_hotel', 'Vui lòng tải lên ảnh khách sạn'),
                Validator.isRequired('#desc_hotel', 'Vui lòng nhập mô tả khách sạn'),
                Validator.isRequired('#tag_keyword_hotel', 'Vui lòng nhập mô tả khách sạn'),
                Validator.isRequired('#hotel_placedetails', 'Vui lòng nhập vào đây'),
                Validator.isRequired('#hotel_linkplace', 'Vui lòng nhập vào đây'),
                Validator.isRequired('#hotel_jfameplace', 'Vui lòng nhập vào đây'),
            ]
        });

        $('.form-hotel-submit').click(function() {
            if ($('#name_hotel').val() == '' || $('#img_hotel').val() == ''  || $('#desc_hotel').val() == ''  || $('#tag_keyword_hotel').val() == '' || $('.form-message').text() != '') {
                    $("#form-hotel").submit(function(e) {
                        e.preventDefault();
                    });
            }
        })
    </script>
@endsection
