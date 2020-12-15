
        <div class="text-center my-bg-blue py-2">
            <img src="<?= base_url('assets/images/RTES1.png') ?>" width="100" alt="RSES">
        </div>
    </body>      

    <script>
        $(document).ready(function() {

            $("#log-out").click(function() {
                console.log(555);

                $.ajax({
                    url: '<?= site_url('login/ajax_get_logout') ?>',
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);                        
                        if (data.status) {
                            window.location.replace("<?= site_url('main/index') ?>");
                        } else {
                            $("#logout-txt").attr('class', '');
                            $("#logout-txt").attr('class', 'text-white');
                            $("#logout-txt").html(`${data.text}`);
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