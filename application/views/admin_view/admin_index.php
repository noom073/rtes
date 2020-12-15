<div class="container-fluid h-full">  
    <div class="main container-fluid">
        <div class="">
            <div class="bg-white m-2 p-2">
                <div class="h2">รายการห้องสอบ</div>
                <div class="">
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#create-roomModal">+ เพิ่มห้องสอบ</button>
                </div>
            </div>

            <div class="m-2 p-2">
                <div class="table-responsive">
                    <table id="ecl-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">ลำดับ</th>
                                <th class="text-center">ชื่อห้องสอบ</th>
                                <th class="text-center">สถานที่</th>
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

        <!-- Create room Modal -->
        <div class="modal fade" id="create-roomModal" tabindex="-1" role="dialog" aria-labelledby="create-roomModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="create-roomModalLabel">สร้างห้องสอบ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="create-room-form">
                            <div class="form-group">
                                <label>ชื่อห้องสอบ</label>
                                <input type="text" class="form-control" name="room_name" maxlength="255">
                            </div>
                            <div class="form-group">
                                <label>สถานที่</label>
                                <input type="text" class="form-control" name="address" maxlength="255">
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
        <!-- End create room Modal -->

        <!-- Edit room Modal -->
        <div class="modal fade" id="edit-roomModal" tabindex="-1" role="dialog" aria-labelledby="edit-roomModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit-roomModalLabel">แก้ไขห้องสอบ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="edit-room-form">
                        <div class="form-group">
                            <label>ชื่อห้องสอบ</label>
                            <input type="text" class="form-control" name="edit_room_name" maxlength="255">
                        </div>
                        <div class="form-group">
                            <label>สถานที่</label>
                            <input type="text" class="form-control" name="edit_address" maxlength="255">
                        </div>
                        <input type="hidden" name="edit_enc_id" value="">
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                        <button type="button" id="del-room" class="btn btn-danger">- ลบห้องสอบ</button>
                    </form>
                    <div id="rs-edit-room"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>
        <!-- End Edit room Modal -->

    </div>
</div>

<link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/my-css.css') ?>">
<script src="<?= base_url('assets/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/bootstrap/js/bootstrap.js') ?>"></script>
<script src="<?= base_url('assets/popper/popper.min.js') ?>"></script>

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
        $("#admin-index.nav-item").addClass('active');

        function generate_datatable() {
            $("#ecl-table").DataTable({
                destroy: true,
                ajax: {
                    url: "<?= site_url('admin/ajax_list_rooms') ?>",
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
                    { data: 'address' },
                    { data: 'time_create', 
                        className: "text-center"
                    },
                    { data: 'time_update', 
                        className: "text-center" 
                    },
                    { data: null,
                        className: "text-center",
                        render: function (data, type, row, meta) {
                            var button          = `<button class="btn btn-sm btn-primary edit-room">แก้ไข</button>`;
                            var inputRowID      = `<input type="hidden" class="id" value="${row.enc_id}" >`;
                            var inputRoomName   = `<input type="hidden" class="room_name" value="${row.room_name}" >`;
                            var inputAddress    = `<input type="hidden" class="address" value="${row.address}" >`;
                            return `${button} ${inputRowID} ${inputRoomName} ${inputAddress}`;
                        }
                    }
                ]
            });
        }

        generate_datatable();

        $("#create-room-form").submit(function() {
            var formData = $(this).serialize();
            console.log(formData);
            $.ajax({
                url: "<?= site_url('admin/ajax_create_room') ?>",
                data: formData,
                type: "POST",
                dataType: "json",
                success: function(data) {
                    console.log(data);
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

        $(document).on("click", ".edit-room", function() {
            var id          = $(this).siblings(".id").val();
            var room_name   = $(this).siblings(".room_name").val();
            var address     = $(this).siblings(".address").val();

            $("form#edit-room-form").find("input[name='edit_room_name']").val(room_name);
            $("form#edit-room-form").find("input[name='edit_address']").val(address);
            $("form#edit-room-form").find("input[name='edit_enc_id']").val(id);

            $("#rs-edit-room").html('');
            $("#rs-edit-room").attr('class', '');

            $("#edit-roomModal").modal();
        });

        $("#edit-room-form").submit(function() {
            
            var formData = $(this).serialize();
            $.ajax({
                url: "<?= site_url('admin/ajax_update_room') ?>",
                data: formData,
                type: "POST",
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    if (data.status) {
                        $("#rs-edit-room").html('');
                        $("#rs-edit-room").attr('class', 'alert alert-success');
                        $("#rs-edit-room").html(data.text);

                        generate_datatable();
                    } else {
                        $("#rs-edit-room").html('');
                        $("#rs-edit-room").attr('class', 'alert alert-danger');
                        $("#rs-edit-room").html(`!Error ${data.text}`);
                    }
                },
                error: function(jhx, status, error) {
                    console.log(`${jhx}, ${status}, ${error}`);
                }
            });

            return false;
        });

        $("#del-room").click(function() {
            var message = "ยืนยันการลบห้องสอบ ?";

            if ( confirm(message) ) {
                var id = $("input[name='edit_enc_id']").val();

                $.ajax({
                    url: "<?= site_url('admin/ajax_delete_room') ?>",
                    data: {enc_id: id},
                    type: "POST",
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        if (data.status) {
                            $("#rs-edit-room").html('');
                            $("#rs-edit-room").attr('class', 'alert alert-success');
                            $("#rs-edit-room").html(data.text);

                            generate_datatable();
                        } else {
                            $("#rs-edit-room").html('');
                            $("#rs-edit-room").attr('class', 'alert alert-danger');
                            $("#rs-edit-room").html(`!Error ${data.text}`);
                        }
                    },
                    error: function(jhx, status, error) {
                        console.log(`${jhx}, ${status}, ${error}`);
                    }
                });
            } 

            return false;
        });
    });
</script>