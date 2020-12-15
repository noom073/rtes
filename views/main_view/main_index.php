<div class="container-fluid h-full">  
    <div class="main container">
        <div class="my-bg-image">
            <div class="my-3">
                <div class="h3 text-center">ระบบลงทะเบียน การทดสอบวัดระดับภาษาอังกฤษ ของ บก.ทท.</div> 
                <div class="h4 text-center">รอบที่ <?= $round->round ?></div> 

                <div class="accordion" id="accordionExample">
                    <div class="card">
                        <div class="card-header my-bg-blue" id="register">
                            <h2 class="mb-0">
                                <button class="btn btn-link text-white" type="button" data-toggle="collapse" data-target="#register-contain" aria-expanded="true" aria-controls="register-contain">
                                    <div class="h4">ลงทะเบียนเข้ารับการทดสอบ</div> 
                                </button>
                            </h2>
                        </div>

                        <div id="register-contain" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                            <div class="card-body">
                                <form id="register-form">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label>หมายเลขบัตรประจำตัวประชาชน</label>
                                            <input type="text" class="form-control" name="idp" maxlength="13">
                                        </div> 
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-2">
                                            <label>รอบที่</label>
                                            <select class="form-control" name="round">
                                                <option value="<?= $round->round  ?>"><?= $round->round  ?></option>
                                            </select>
                                        </div> 
                                    </div>                          
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form> 

                                <div id="rs-register-form" class="my-2"></div>                               
                                <div id="rs-register-detail" class="my-2"></div>                               
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header my-bg-blue" id="check-registered">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed text-white" type="button" data-toggle="collapse" data-target="#check-registered-contain" aria-expanded="false" aria-controls="check-registered-contain">                                
                                <div class="h4">ตรวจสอบผลการลงทะเบียน5</div> 
                                </button>
                            </h2>
                        </div>

                        <div id="check-registered-contain" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                            <div class="card-body">
                                <form id="check-registered-form">                                                    
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label>หมายเลขบัตรประจำตัวประชาชน</label>
                                            <input type="text" class="form-control" name="idp" maxlength="13">
                                        </div> 
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-2">
                                            <label>รอบที่</label>
                                            <select class="form-control" name="round">
                                                <option value="<?= $round->round  ?>"><?= $round->round  ?></option>
                                            </select>
                                        </div> 
                                    </div>                                  
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>                                 

                                <div id="check-registered-detail" class="my-2"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header my-bg-blue" id="check-score">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed text-white" type="button" data-toggle="collapse" data-target="#check-score-contain" aria-expanded="false" aria-controls="check-score-contain">                                
                                <div class="h4">ผลการทดสอบ</div> 
                                </button>
                            </h2>
                        </div>

                        <div id="check-score-contain" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                            <div class="card-body">
                                <form id="check-score-form">
                                    <div class="form-group">
                                        <label>หมายเลขบัตรประจำตัวประชาชน</label>
                                        <input type="text" class="form-control col-md-4" name="idp" maxlength="13">
                                    </div>                                    
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form> 

                                <div id="check-score-detail" class="my-2"></div>

                                <div class="my-2 table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ชื่อ นามสกุล</th>
                                                <th>หน่วย</th>
                                                <th>เวลาที่สอบ</th>
                                                <th>คะแนน</th>
                                            </tr>
                                        </thead>
                                        <tbody id="score-table"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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

<script>
    $(document).ready(function() {

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
                        console.log(555);
                        $("#rs-register-detail").attr('class', 'my-2');
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

        function set_detail_registered(data) {
            if (data.status == 'registered') {
                var y = parseInt(data.registered.date_test.substring(0,4)) + 543;
                var m = toThaiDate(data.registered.date_test.substring(5,7));
                var d = data.registered.date_test.substring(8);
                var date = `${d} ${m} ${y} ${data.registered.time_test}`;
    
                var html = `<div>`+
                            `<label>ยศ ชื่อ สกุล: </label><span> ${data.registered.name}</span>`+
                        `</div>`+
                        `<div>`+
                            ` <label>สังกัด: </label><span> ${data.registered.unit_name}</span>`+
                        `</div>`+
                        `<div>`+
                            `<label>วันเดือนปี เข้ารับการทดสอบ: </label><span class="text-danger"> ${date} น.</span>`+
                        `</div>`+
                        `<div>`+
                            `<label>ลำดับที่นั่ง: </label><span> ${data.registered.seat_number}</span>`+
                        `</div>`+
                        `<div>`+
                            `<label>สถานที่: </label><span class="text-danger"> ${data.registered.room_name}</span>`+
                        `</div>`+
                        `<div>`+
                            `<button class="btn btn-danger clear-registered" data-rid="${data.registered.row_id}">ยกเลิกการลงทะเบียน</button>`+
                        `</div>`;  

            } else if (data.status == 'not-register'){
                if (data.room.length > 0) {
                    var option = '';
                    data.room.forEach(element => {
                        var y = parseInt(element.date_test.substring(0,4)) + 543;
                        var m = toThaiDate(element.date_test.substring(5,7));
                        var d = element.date_test.substring(8);
                        var date = `${d} ${m} ${y} ${element.time_test} น.`;
                        option += `<option value="${element.row_id}:${element.round}">วันที่ ${date} ${element.room_name}</option>`;
                    });

                    var html =`<form id="registering-form">`+
                            `<div class="form-group">`+
                                `<label>ยศ ชื่อ สกุล:</label>`+
                                `<input type="text" class="form-control col-md-4 bg-white" name="name" value="${data.member.name}" readonly>`+
                                `<input type="hidden" name="idp" value="${data.member.idp}">`+
                            `</div>`+  
                            `<div class="form-group">`+
                                `<label>สังกัด:</label>`+
                                `<input type="text" class="form-control col-md-4 bg-white" name="unit_name" value="${data.member.unitname}" readonly>`+
                                `<input type="hidden" name="unit_code" value="${data.member.unit}">`+
                            `</div>`+
                            `<div class="form-row">`+
                                `<div class="form-group col-md-4">`+
                                    `<label>หมายเลขโทรศัพท์:</label>`+
                                    `<input type="text" class="form-control" name="tel_number" value="">`+                            
                                `</div>`+ 
                                `<div class="form-group col-md-8">`+
                                    `<label>E-mail:</label>`+
                                    `<input type="text" class="form-control bg-white" name="email" value="${data.member.email}">`+
                                `</div>`+ 
                            `</div>`+
                            `<div class="form-group">`+
                                `<label>วันเดือนปี เข้ารับการทดสอบ:</label>`+
                                `<span class="text-danger"> แสดงเฉพาะรอบที่ยังไม่เต็ม</span>`+
                                `<select class="form-control col-md-6" name="round">${option}</select>`+
                            `</div>`+
                            `<button type="submit" class="btn btn-primary">บันทึกการลงทะเบียน</button>`+
                        `</form>`; 
                    
                } else {
                    var html = `<div class="alert alert-warning">จำนวนที่นั่งเต็มทุกห้อง | ยังไม่เปิดลงทะเบียน<div>`;
                }               
            }
            return html;
        }

        $(document).on("submit", "#registering-form", function() {
            
			var message = 'ยืนยันการลงทะเบียน';
			if( confirm(message) ) {
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
                
			} else {
				return false;
			}

        });

        $("#check-registered-form").submit(function() {
            var formData = $(this).serialize();
            $.ajax({
                url: "<?= site_url('main/ajax_check_member_register') ?>",
                data: formData,
                type: "POST",
                dataType: "json",
                success: function(data) {
                    if (data.status == 'registered') {
                        $("#check-registered-detail").html('');
                        $("#check-registered-detail").attr('class', 'my-2 alert alert-info');
                        $("#check-registered-detail").html(`${data.text}`);  
                        
                        var html = set_detail_registered(data);
                        console.log(data);
                        html += `<a href="<?= base_url('assets/PDF_generate/') ?>${data.registered.idp}.pdf" download="number">Download file ลงทะเบียน</a>`;

                        $("#check-registered-detail").html(html);
                    } else {
                        console.log('not-found');
                        $("#check-registered-detail").html('');
                        $("#check-registered-detail").attr('class', 'my-2 alert alert-danger');
                        $("#check-registered-detail").html(`! ${data.text}`);
                    }
                },
                error: function(jhx, status, error) {
                    console.log(`${jhx}, ${status}, ${error}`);
                }
            });

            return false;
        });

        $("#check-score-form").submit(function() {
            var formData = $(this).serialize();

            $("#check-score-detail").html(`กำลังโหลด ... <div class="spinner-border text-success" role="status">
                    <span class="sr-only">Loading...</span>
                </div>`);
           
            $.ajax({
                url: "<?= site_url('main/ajax_check_score') ?>",
                data: formData,
                type: "POST",
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    if (data.status == true) {
                        $("#check-score-detail").html('');
                        $("#check-score-detail").attr('class', 'my-2 alert alert-info');
                        $("#check-score-detail").html(`${data.text}`); 
                        $("#score-table").html('');

                        var tr = '';
                        data.score.forEach(function(element) {
                            var year    = parseInt( element.date_test.substring(0,4) ) +543;
                            var month   = toThaiDate(element.date_test.substring(5,7));
                            var date    = `${element.date_test.substring(8)} ${month} ${year}`;

                            tr +=`<tr>`+
                                `<td>${element.name}</td>`+
                                `<td>${element.unit_name}</td>`+
                                `<td>${date} ${element.time_test}</td>`+
                                `<td>${element.score_test}</td>`+
                                `</tr>`;
                                $("#score-table").html(tr);
                        });
                        
                    } else {
                        $("#score-table").html('');
                        $("#check-score-detail").html('');
                        $("#check-score-detail").attr('class', 'my-2 alert alert-danger');
                        $("#check-score-detail").html(`! ${data.text}`);
                    }
                },
                error: function(jhx, status, error) {
                    console.log(`${jhx}, ${status}, ${error}`);
                }
            });

            return false;
        }); 

        // $("#clear-registered").click(function() {
        $(document).on("click", ".clear-registered",function() {
            var rid     = $(this).attr("data-rid");
            var message = "ยืนยันการยกเลิก การลงทะเบียน";
            var this_button = $(this);

            if ( confirm(message) ) {
                $.ajax({
                    url: "<?= site_url('main/ajax_cancel_registered') ?>",
                    data: {row: rid},
                    type: "POST",
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        if (data.status == true) {

                            this_button.parent().parent().attr('class', 'my-2 alert alert-success');
                            this_button.parent().parent().html(`${data.text}`);

                            $("#rs-register-form").html('');
                            $("#rs-register-form").attr('class', '');
                            
                        } else {
                            $("#rs-register-detail").html(''); 

                            $("#rs-register-form").html('');
                            $("#rs-register-form").attr('class', 'my-2 alert alert-danger');
                            $("#rs-register-form").html(`! ${data.text}`);

                            $("#check-registered-detail").html('');
                            $("#check-registered-detail").attr('class', 'my-2 alert alert-success');
                            $("#check-registered-detail").html(`${data.text}`);
                        }
                    },
                    error: function(jhx, status, error) {
                        console.log(`${jhx}, ${status}, ${error}`);
                    }
                });
                
                return;

            } else {
                return false;
            }
        });

    });
</script>