<div class="container-fluid h-full">  
    <div class="main container-fluid">
        <div class="">
            <div class="bg-white m-2 p-2">
                <div class="h2">รายการห้องสอบ</div>
                <div class="">
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#import-score-Modal">+ อัพโหลดคะแนน</button>
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
</div>

<div class="all-modal">

    <!-- Import score Modal -->
    <div class="modal fade" id="import-score-Modal" tabindex="-1" role="dialog" aria-labelledby="import-score-ModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="import-score-ModalLabel">อัพโหลดคะแนน</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="create-room-form">
                        <div class="form-group">
                            <label>เลือกไฟล์</label>
                            <input type="file" class="form-control" name="file_upload">
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
    <!-- End Import score Modal -->

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

<script>
    $(document).ready(function() {

        $(".nav-item").removeClass('active');
        $("#admin-score.nav-item").addClass('active');

        $("#register-form").submit(function() {
            var formData = $(this).serialize();

            $.ajax({
                url: "<?= site_url('main/ajax_check_member_register') ?>",
                data: formData,
                type: "POST",
                dataType: "json",
                success: function(data) {
                    if (data.status == 'not-register') {
                        $("#rs-register-form").html('');
                        $("#rs-register-form").attr('class', 'my-2 alert alert-success');
                        $("#rs-register-form").html(data.text);

                        var html = set_detail_registered(data);

                        $("#rs-register-detail").html(html);

                    } else if (data.status == 'registered') {
                        $("#rs-register-form").html('');
                        $("#rs-register-form").attr('class', 'my-2 alert alert-info');
                        $("#rs-register-form").html(`${data.text}`);  
                        
                        var html = set_detail_registered(data);

                        $("#rs-register-detail").html(html);
                    } else {
                        console.log('not-found');
                        $("#rs-register-form").html('');
                        $("#rs-register-form").attr('class', 'my-2 alert alert-danger');
                        $("#rs-register-form").html(`! ${data.text}`);
                        $("#rs-register-detail").html('');
                    }
                },
                error: function(jhx, status, error) {
                    console.log(`${jhx}, ${status}, ${error}`);
                }
            });

            return false;
        });

        $(document).on("submit", "#registering-form", function() {
            var formData = $(this).serialize();
            $("#rs-register-form").html(`กำลังโหลด ... <div class="spinner-border text-success" role="status">
                <span class="sr-only">Loading...</span>
            </div>`);

            $.ajax({
                url: "<?= site_url('main/ajax_register_member') ?>",
                data: formData,
                type: "POST",
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    if (data.status) {
                        $("#rs-register-form").html('');
                        $("#rs-register-form").attr('class', 'my-2 alert alert-success');
                        $("#rs-register-form").html(`${data.text}`);
                        $("#rs-register-detail").html('');

                    } else {
                        console.log('not-found');
                        $("#rs-register-form").html('');
                        $("#rs-register-form").attr('class', 'my-2 alert alert-danger');
                        $("#rs-register-form").html(`! ${data.text}`);
                        $("#rs-register-detail").html('');
                    }
                },
                error: function(jhx, status, error) {
                    console.log(`${jhx}, ${status}, ${error}`);
                }
            });

            return false;
        });

    });
</script>