<div class="container-fluid h-full">  
    <div class="main container-fluid">
        <div class="">
            <div class="bg-white m-2 p-2">
                <div class="h2">รายการห้องสอบ</div>
                <div class="">
                    <a class="btn btn-sm btn-primary" href="<?= site_url('score/index') ?>">อัพโหลดคะแนน</a>
                </div>
            </div>

            <div class="m-2 p-2">
                <form id="search-score-form">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label>เลขประชาชน</label>
                            <input type="text" name="idp" class="form-control" >
                        </div>
                        <div class="form-group col-md-3">
                            <label>หน่วย</label>
                            <select name="unit" class="form-control">
                                <?php foreach ($unitname as $r) { ?>
                                    <option value="<?= $r['unitname'] ?>"><?= $r['unitname'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>วัน เดือน ปี ที่ทดสอบ</label>
                            <input type="text" name="date" class="form-control date" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label>เวลาที่ทดสอบ</label>
                            <select name="time"  class="form-control">
                                <option value="">ไม่ระบุ</option>
                                <?php foreach ($time_test as $r) { ?>
                                    <option value="<?= $r['time'] ?>"><?= $r['time'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-primary" type="submit">Submit</button>
                        <div id="rs"></div>
                    </div>
                </form>

                <div class="table-responsive my-2">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ชื่อ นามสกุล</th>
                                <th>หน่วย</th>
                                <th>เวลาที่สอบ</th>
                                <th>คะแนน</th>
                            </tr>
                        </thead>
                        <tbody id="body-score-table"></tbody>
                    </table>
                </div>
            </div>
        </div>
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
        $("#admin-search.nav-item").addClass('active');

        $("#search-score-form").submit(function() {
            $("#rs").html(`กำลังโหลด ... <div class="spinner-border text-success" role="status">
                    <span class="sr-only">Loading...</span>
                </div>`);

            var formData = new FormData(this);
            $.ajax({
                url: "<?= site_url('search/ajax_search_score') ?>",
                data: formData,
                type: "POST",
                processData: false,  // tell jQuery not to process the data
                contentType: false,  // tell jQuery not to set contentType
                dataType: "json",
                success: function(data) {
                    
                    var row = '';
                    data.forEach(function(element) {
                        console.log(element.date_test.substring());
                        var year    = +element.date_test.substring(0,4)+543
                        var month   = toThaiDate(element.date_test.substring(5,7))
                        var day     = element.date_test.substring(8)
                        row +=`<tr><td>${element.name}</td>
                            <td>${element.unit_name}</td>
                            <td>${day} ${month} ${year} ${element.time_test}</td>
                            <td>${element.score_test}</td></tr>`;
                    });
                    $("#rs").html('');
                    $("#body-score-table").html(row);
                },
                error: function(jhx, status, error) {
                    console.log(`${jhx}, ${status}, ${error}`);
                }
            });

            return false;
        });

    });
</script>