<div class="container-fluid h-full">  
    <div class="main container-fluid">
        <div class="">
            <div class="bg-white m-2 p-2">
                <div class="h2">รายการ Log</div>                
            </div>

            <div class="m-2 p-2">
                <div class="table-responsive">
                    <table id="ecl-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">ลำดับ</th>
                                <th >ชื่อไฟล์</th>
                                <th >วันที่อัพเดต</th>
                                <th class="text-center">รายละเอียด</th>
                            </tr>
                        </thead>                        
                    </table>
                </div>
            </div>
        </div>
    </div> 

    <div id="all-modal">
        <!-- View round detail Modal -->
        <div class="modal fade" id="detail-log-Modal" tabindex="-1" role="dialog" aria-labelledby="detail-log-ModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detail-log-ModalLabel">รายละเอียด Log</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div id="log-text"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End View round detail Modal -->
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
<style>

</style>

<script>
    $(document).ready(function() {

        $(".nav-item").removeClass('active');
        $("#admin-view-log.nav-item").addClass('active');

        function generate_datatable() {
            $("#ecl-table").DataTable({
                destroy: true,
                order: [[ 2, "desc" ]],
                ajax: {
                    url: "<?= site_url('view_log/ajax_list_log') ?>",
                    dataSrc: ""
                },
                columns: [
                    { data: null,
                        className: "text-center",
                        render: function(data, type, row, meta) {
                            return meta.row+1;
                        }
                    },
                    { data: 'filename' },
                    { data: 'info',
                        render: function(data, type, row, meta) {
                            var date        = new Date(data.date*1000);
                            var Year        = date.getFullYear();
                            var Month       = date.getMonth()+1;
                            var Month       = (Month < 10) ? `0${Month}` : Month;
                            var Day         = (date.getDate() < 10) ? `0`+date.getDate(): date.getDate();
                            var Hour        = (date.getHours() < 10) ? `0`+date.getHours(): date.getHours();
                            var Minute      = (date.getMinutes() < 10) ? `0`+date.getMinutes(): date.getMinutes();
                            var Second      = (date.getSeconds() < 10) ? `0`+date.getSeconds(): date.getSeconds();
                            var fullDate    = `Date: ${Year}-${Month}-${Day} ${Hour}:${Minute}:${Second}`;
                            var Size        = `Size: ${data.size} B`;
                                                        
                            return `${fullDate} <br> ${Size}`;
                        }
                    },
                    { data: null,
                        className: "text-center",
                        render: function(data, type, row, meta) {
                            var button = `<button class="btn btn-sm btn-primary see-log" data-file="${data.filename}">รายละเอียด</button>`;
                            return button;
                        }
                    }
                ]
            });
        }

        generate_datatable();
       
        $(document).on("click", ".see-log", function() { // insert data in modal when click edit round 
            var filename    = $(this).attr("data-file");
            console.log(filename);

            $.ajax({
                url: "<?= site_url('view_log/ajax_get_log_detail') ?>",
                data: {file: filename},
                type: "POST",
                dataType: "json",
                success: function(data) {  
                    console.log(data);
                    $("#log-text").html('');

                    var html    = '';    
                    var num     = 1;          
                    data.forEach(element => {   
                        if (element.text) {
                            html += `<div class="detail-log p-3 mb-2">${num}. ${element.text}</div>`;                            
                            num++;
                        }                     
                    });

                    $("#log-text").html(html);
                    $(".detail-log:odd").addClass("border-top border-bottom border-secondary");
                },
                error: function(jhx, status, error) {
                    console.log(`${jhx}, ${status}, ${error}`);
                }
            });
            
            $("#detail-log-Modal").modal();
        });

    });
</script>