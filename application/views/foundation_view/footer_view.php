                
        <div class="text-center">
            <div class="text-center">สถาบันภาษากองทัพไทย</div>
            <div class="text-center">RTARF Language Institute</div>
            <div class="text-center">โทร. 0 2241 2716</div>            
            <a id="log-in" href="<?= site_url('admin/index') ?>" data-toggle="modal" data-target="#log-inModal">Administrator</a>            
        </div>       

        <!-- Modal -->
        <div class="modal fade" id="log-inModal" tabindex="-1" role="dialog" aria-labelledby="log-inModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <img src="<?= base_url('assets/images/RTES1.png') ?>" width="75" alt="RSES">                        
                    <span class="mx-2">ผู้ดูแลระบบ การทดสอบวัดระดับภาษาอังกฤษ ของ บก.ทท.</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="logIn-form">
                        <div class="form-group">
                            <label >Username</label>
                            <input type="text" name="user" class="form-control">
                        </div>
                        <div class="form-group">
                            <label >Password</label>
                            <input type="password" name="password" class="form-control" >
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>

                <div id="rs"></div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>
        
        <div class="text-center py-2">
            <img src="<?= base_url('assets/images/RTES1.png') ?>" width="100" alt="RSES">
        </div>
    </body>

    <!-- <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/my-css.css') ?>">
    <script src="<?= base_url('assets/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.js') ?>"></script>
    <script src="<?= base_url('assets/popper/popper.min.js') ?>"></script>   -->

    <script>
        $(document).ready(function() {

            $("#logIn-form").submit(function() {
                console.log(555);
                var formData = $(this).serialize();

                $.ajax({
                    url: '<?= site_url('login/ajax_login_proc') ?>',
                    data: formData,
                    type: 'POST',
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);                        
                        if (data.status) {
                            $("#rs").attr('class', '');
                            $("#rs").attr('class', 'alert alert-success');
                            $("#rs").html(`${data.text}`); 

                            setInterval(() => {
                                window.location.replace("<?= site_url('admin/index') ?>");
                            }, 700);

                        } else {
                            $("#rs").attr('class', '');
                            $("#rs").attr('class', 'alert alert-warning');
                            $("#rs").html(`${data.text}`);
                        }
                    },
                    error: function (jhx, status, error) {
                        console.log(`${jhx} ${status} ${error}`);
                    } 
                });

                return false;
            });
        });
    </script>
</html>