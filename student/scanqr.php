<?php

//index.php

include('header.php');

?>

<style>
    @media screen and (max-width:767px) { .fc-toolbar.fc-header-toolbar {font-size: 60%}}
    #qr-reader {
      width:calc(60%);
    }
    @media (max-width: 720px) {
      #qr-reader {
      width:calc(100%);
      }
      #qr-reader__scan_region video {
          object-fit: cover !important; 
      }
    }
</style>

<div class="col-md-12 pt-3"  id ="qr_holder" align="center">
	<div class="w-100 d-flex justify-content-end">
		<a class="btn btn-primary btn-rounded" id="startLive" href="index.php">Back</a>
	</div>
		
	<div id="qr-reader" style=""></div>
		
	<div id="qr-reader-results"></div>
</div>
<input type="hidden" id="qr_id" value="">

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="../js/html5-qrcode-scanner.js"></script>
<script src="../js/html5-qrcode.js"></script>
<script src="../js/sweetalert.min.js"></script>
<!-- <script src="../js/md5.min.js"></script> -->

<script>
    function docReady(fn) {
        // see if DOM is already available
        if (document.readyState === "complete"
            || document.readyState === "interactive") {
            // call on next available tick
            setTimeout(fn, 1);
        } else {
            document.addEventListener("DOMContentLoaded", fn);
        }
    }

    docReady(function () {
        var resultContainer = document.getElementById('qr-reader-results');
        var lastResult, countResults = 0;
        function onScanSuccess(qrCodeMessage) {
            if (qrCodeMessage !== $('#qr_id').val()) {
                ++countResults;
                lastResult = qrCodeMessage;
				$('#qr_id').val(qrCodeMessage)
                // console.log($('#qr_id').val())
                var qr_id = $('#qr_id').val()

                var today = new Date();
                var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
                var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                var scan_time = date+' '+time;
                // var scan_time = Math.round(Date.now() / 1000);
                
                console.log(qr_id);
                $.ajax({
                    url:'schedule_action.php',
                    method:'POST',
                    data:{action:'qr_scan', qr_id:qr_id, scan_time:scan_time},
                    dataType:"json",
                    // error:err=>{
                    //     console.log(err)
                    //     alert_toast('An Error Occured.');
                    //     end_loader()
                    // },      
                    success:function(data) {
                        if(data.success) {
                            swal({
                                title: 'Success',
                                text: 'Successfully Registered',
                                icon: 'success',
                                timer: 3000,
                                buttons: false,
                            })
                        }
                        if(data.error) {
                            swal({
                                title: data.status,
                                text: data.status,
                                icon: 'error',
                                timer: 3000,
                                buttons: false,
                            })
                            

                        }
                        setTimeout(function(){
                            $('#qr_id').val('')
                            // end_loader()
                        },3000)
                    }
                })
                html5QrcodeScanner.clear();
                
                setTimeout(function(){
                    location.href = 'index.php';
                },3000)
            }
        }

      
		var html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    });

   $(document).ready(function(){
	   console.log($(window).height() - $('.top-head').height())
	   $('.main-container').height($(window).height() - $('.top-head').height())
   })
</script>