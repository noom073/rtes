<div class="container-fluid h-full">  
    <div class="main container-fluid">
        <div class="">
            <div class="bg-white m-2 p-2">
                <div class="h2">วัน-เวลา การทดสอบ</div>
                <div class="">
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#create-roundModal">+ เพิ่มวัน-เวลา การทดสอบ</button>
                </div>
            </div>

            <div class="m-2 p-2">
                <div class="table-responsive">
                    <table id="ecl-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">ลำดับ</th>
                                <th class="text-center">ชื่อห้องสอบ</th>
                                <th class="text-center">รอบที่</th>
                                <th class="text-center">วัน เวลาทดสอบ</th>
                                <th class="text-center">จำนวนที่นั่ง</th>
                                <th class="text-center">สถานะ</th>
                                <th class="text-center">วันที่สร้าง</th>
                                <th class="text-center">วันที่แก้ไข</th>
                                <th class="text-center">แก้ไข</th>
                            </tr>
                        </thead>                        
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="all-modal">

        <!-- Create round Modal -->
        <div class="modal fade" id="create-roundModal" tabindex="-1" role="dialog" aria-labelledby="create-roundModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="create-roundModalLabel">สร้างวัน-เวลา การทดสอบ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="create-round-form">
                            <div class="form-group">
                                <label>รอบที่</label>
                                <small class="text-danger">**ตัวอย่าง: 2562/1</small>
                                <input type="text" class="form-control col-md-4" name="round" required>
                                <div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>วันที่</label>
                                <?php 
                                    $year = date('Y')+543 ;
                                    $defaultDate = date('d') ."-". date('m') ."-". $year ;
                                ?>
                                <input type="text" class="form-control date bg-white col-md-4" name="date" maxlength="255" value="<?= $defaultDate ?>" readonly required>
                            </div>
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label>ชั่วโมง</label>
                                        <input type="number" class="form-control" name="hour" min="7" max="18" placeholder="7 - 18 นาฬิกา" value="7" required>
                                    </div>
                                    
                                    <div class="form-group col-md-2">
                                        <label>นาที</label>
                                        <input type="number" class="form-control" name="minute" min="0" max="59" placeholder="0 - 59 นาที" value="0" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>สถานที่</label> 
                                <span id="select-room-loading">loading ...</span>                               
                                <select id="list-select-room" class="form-control" name="room" required></select>
                            </div>
                            <div class="form-group">
                                <label>จำนวนที่นั่งสูงสุด</label>
                                <input type="number" class="form-control col-md-4" name="amount_seat" min="1" value="1" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                        <div id="rs"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End create round Modal -->

        <!-- Edit round Modal -->
        <div class="modal fade" id="edit-round-Modal" tabindex="-1" role="dialog" aria-labelledby="edit-roundModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="edit-roundModalLabel">แก้ไข วัน-เวลา การทดสอบ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form id="edit-round-form">
                            
                            <div class="form-group">
                                <label>วันที่</label>                                
                                <input type="text" class="form-control date bg-white col-md-4" name="date" maxlength="255" value="" readonly required>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label>ชั่วโมง</label>
                                    <input type="number" class="form-control" name="hour" min="7" max="18" value="7" required>
                                </div>
                                
                                <div class="form-group col-md-2">
                                    <label>นาที</label>
                                    <input type="number" class="form-control" name="minute" min="0" max="59" value="0" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>สถานที่</label>                            
                                <select id="list-select-edit" class="form-control" name="room" required></select>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>

                        <div id="rs-edit-round"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Edit round Modal -->

    </div>
</div>

<link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/my-css.css') ?>">
<script src="<?= base_url('assets/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/bootstrap/js/bootstrap.js') ?>"></script>
<script src="<?= base_url('assets/popper/popper.min.js') ?>"></script>

<script src="<?= base_url('assets/my-js/function.js') ?>"></script>

<!-- dataTable -->
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/dataTable/datatables.min.css') ?>"/> 
<script type="text/javascript" src="<?= base_url('assets/dataTable/datatables.min.js') ?>"></script>
<!-- End dataTable -->

<!-- datepicker -->
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/datepicker/css/datepicker.css') ?>"/> 
<script type="text/javascript" src="<?= base_url('assets/datepicker/js/date-func.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/datepicker/js/datepicker.js') ?>"></script>
<!-- End datepicker -->

<script>
    $(document).ready(function() {

        $(".nav-item").removeClass('active');
        $("#admin-manage-round.nav-item").addClass('active');

        function generate_datatable() {
            $("#ecl-table").DataTable({
                destroy: true,
                ajax: {
                    url: "<?= site_url('manage_round/ajax_list_rounds') ?>",
                    dataSrc: ""
                },
                columns: [
                    { data: null,
                        className: "text-center",
                        render: function(data, type, row, meta) {
                            return meta.row+1;
                        }
                    },
                    { data: 'room_name' },
                    { data: 'round',
                        className: "text-center"                    
                    },
                    { data: null,
                        className: "text-center",
                        render: function (data, type, row, meta) {
                            var year    = parseInt( row.date_test.substring(0,4) ) +543;
                            var month   = toThaiDate(row.date_test.substring(5,7));
                            var date    = `${row.date_test.substring(8)} ${month} ${year}`;
                            var time    = `${row.time_test}:00`;
                            return `${date} ${time} น.`;
                        }                    
                    },
                    { data: 'total',
                        className: "text-center"
                    },
                    { data: 'active',
                        className: "text-center",
                        render: function(data, type, row, meta) {
                            var status = (data == 'y') ? `<span class="text-success">เปิดลงทะเบียน</span>` : `<span class="text-danger">เปิดลงทะเบียน</span>`;
                            return status;
                        }
                    },
                    { data: 'time_create',
                        className: "text-center" 
                    },
                    { data: 'time_update',
                        className: "text-center" 
                    },
                    { data: null,
                        className: "text-center",
                        render: function (data, type, row, meta) {
                            if (row.person > 0) {
                                button = `<button class="btn btn-sm btn-light"> มีผู้ลงทะเบียนแล้ว </button>`;

                            } else {
                                var year = parseInt( row.date_test.substring(0,4) ) +543;
                                var date = `${row.date_test.substring(8)}-${row.date_test.substring(5,7)}-${year}`;
                                var hour = `${row.time_test.substring(0,2)}`;
                                var minute = `${row.time_test.substring(3)}`;
                                var room_id = `${row.room_id}`;

                                var button = `<button class="btn btn-sm btn-primary edit-round"`+
                                    `data-room='${row.room_name}'`+
                                    `data-room_id='${row.room_id}'`+
                                    `data-date='${date}'`+
                                    `data-hour='${hour}'`+
                                    `data-minute='${minute}'`+
                                    `>แก้ไข</button>`;                                   
                            }

                            var detail = `<a class="btn btn-sm btn-primary m-1" href="<?= site_url('manage_round/round_detail/')?>${row.enc_id}"> รายละเอียด </a>`;

                            return `${button} ${detail}`;
                        }
                    }
                ]
            });
        }

        generate_datatable();

        $("#create-round-form").submit(function() { // submit to create round
            $("#rs").html(`กำลังโหลดข้อมูล... <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>`);
            var formData = $(this).serialize();
            // console.log(formData);
            $.ajax({
                url: "<?= site_url('manage_round/ajax_create_round') ?>",
                data: formData,
                type: "POST",
                dataType: "json",
                success: function(data) {
                    // console.log(data);
                    if (data.status) {
                        $("#rs").html('');
                        $("#rs").attr('class', 'alert alert-success');
                        $("#rs").html(data.text);

                        generate_datatable();
                    } else {
                        $("#rs").html('');
                        $("#rs").attr('class', 'alert alert-danger');
                        $("#rs").html(`!Error ${data.text}`);
                    }
                },
                error: function(jhx, status, error) {
                    console.log(`${jhx}, ${status}, ${error}`);
                }
            });

            return false;
        });

        $(document).on("click", ".edit-round", function() { // insert data in modal when click edit round 
            var room_name   = $(this).attr("data-room");
            var date        = $(this).attr("data-date");
            var hour        = $(this).attr("data-hour");
            var minute      = $(this).attr("data-minute");
            var rooms_id    = $(this).attr("data-room_id");

            $("form#edit-round-form").find("input[name='room']").val(room_name);
            $("form#edit-round-form").find("input[name='date']").val(date);
            $("form#edit-round-form").find("input[name='hour']").val(hour);
            $("form#edit-round-form").find("input[name='minute']").val(minute);

            $("#list-select-edit > option").each(function() {
                var name = $(this).text();

                if (name === room_name) {
                    $(this).attr({"selected": true});
                } else {
                    $(this).attr({"selected": false});
                }
            });
            
            // console.log(room_name);

            $("#rs-edit-room").html('');
            $("#rs-edit-room").attr('class', '');

            $("#edit-round-Modal").modal();
        });

        $(function() { // list room in modal create round
            $.ajax({
                url: "<?= site_url('manage_round/ajax_list_room') ?>",
                dataType: "json",
                success: function(data) {
                    var option = '';
                    data.forEach(element => {
                        option += `<option value="${element.enc_id}">${element.room_name}</option>`
                    });
                    $("#select-room-loading").html('');
                    $("#list-select-room").html(option);
                    $("#list-select-edit").html(option);
                },
                error: function(jhx, status, error) {
                    console.log(`${jhx}, ${status}, ${error}`);
                }
            });
        });


    });
</script>