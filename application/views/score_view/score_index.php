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
                <div id="insert-detail"> 
                    <div id="file-detail"></div>
                    <div id="fail-detail"></div>
                    <div id="pass-detail"></div>
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
                    <form id="upload-score-form" enctype="multipart/form-data">
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

        $("#upload-score-form").submit(function() {
            $("#rs").html(`กำลังโหลด ... <div class="spinner-border text-success" role="status">
                    <span class="sr-only">Loading...</span>
                </div>`);

            var formData = new FormData(this);

            $.ajax({
                url: "<?= site_url('score/ajax_upload_score') ?>",
                data: formData,
                type: "POST",
                processData: false,  // tell jQuery not to process the data
                contentType: false,  // tell jQuery not to set contentType
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    $("#rs").html('');
                    $("#import-score-Modal").modal('toggle');

                    if (data.file.status) {
                        $("#file-detail").attr('class', 'alert alert-success');
                        $("#file-detail").html(data.file.text);
                    } else {
                        $("#file-detail").attr('class', 'alert alert-danger');
                        $("#file-detail").html(data.file.text);
                    }

                    if ( (typeof data.pass) !== 'undefined' ) {
                        console.log('pass');
                        var participants = '<table class="table">';

                        data.pass.forEach(element => {
                            participants += `<tr>`
                                    +`<td>${element.BIOG_IDP} </td>`
                                    +`<td>${element.BIOG_NAME}</td>`
                                    +`<td>${element.BIOG_UNITNAME}</td>`
                                    +`<td>${element.SCORE} คะแนน</td>`
                                    +`<td>รอบที่ : ${element.ROUND}</td>`
                                    +`<td>${element.TEXT}</td>`;
                                    +`</tr>`;
                        });

                        participants += '</table>';

                        $("#pass-detail").attr('class', 'table-responsive alert alert-success');
                        $("#pass-detail").html(participants);
                    } 
                    
                    if ( (typeof data.fail) !== 'undefined' ) {
                        console.log('fail');
                        var participants = '<table class="table">';

                        data.fail.forEach(element => {
                            participants += `<tr>`
                                    +`<td>${element.BIOG_IDP} </td>`
                                    +`<td>${element.BIOG_NAME}</td>`
                                    +`<td>${element.BIOG_UNITNAME}</td>`
                                    +`<td>รอบที่ : ${element.ROUND}</td>`
                                    +`<td>${element.TEXT}</td>`;
                                    +`</tr>`;
                        });

                        participants += '</table>';

                        $("#fail-detail").attr('class', 'table-responsive alert alert-light');
                        $("#fail-detail").html(participants);
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