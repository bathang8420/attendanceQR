<?php

//index.php

include('header.php');

?>
      <main id=""  class="">
        <div class="py-2 text-center">
          <a class="btn btn-primary" href="scanqr.php" role="button">Scan QR</a>
        </div>

        <div class="container py-1 px-1 vh-100">
          <div class="col-lg-12 px-0">
          <div class="card">
            <div class="card-body">
              <div id="calendar" class="vh-100"></div>
            </div>
          </div>
          </div>
        </div>
      </main>  		
</body>
</html>

<style>
    @media screen and (max-width:767px) { .fc-toolbar.fc-header-toolbar {font-size: 60%}}
</style>

<div class="modal" id="viewModal">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Schedule Details</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="class_details">

      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-primary btn-sm">QR Code</button> -->
        <!-- <input type="submit" name="button_scan-qr" id="button_scan-qr" class="btn btn-success btn-sm" value="Scan QR" /> -->
        <a class="btn btn-primary" href="attendance.php" role="button">Attendance history</a>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<div class="modal" id="QRModal">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Scan QR Code For Attendance</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="qr_details">
        <div class="col-md-12 pt-3"  id ="qr_holder" align="center">
            
          <div id="qr-reader" style=""></div>
            
          <div id="qr-reader-results"></div>

          <input type="text" id="qr_id" value="">
        </div>
        

      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-primary btn-sm">QR Code</button>

        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button> -->
      </div>

    </div>
  </div>
</div>




<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="../js/html5-qrcode-scanner.js"></script>
<script src="../js/html5-qrcode.js"></script>
<script src="../js/sweetalert.min.js"></script>
<script src="../js/md5.min.js"></script>


<script>

// $(document).ready(function(){
// });
var idClass = '';
var dateClass = '';

document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  var calendar; 
  $.ajax({
      url:"schedule_action.php",
      type:"POST",
      data:{action:'fetch'},
      success:function(resp) {
        if(resp){
		 			resp = JSON.parse(resp)
		 					var evt = [] ;
               console.log(resp.length);
		 			if(resp.length > 0){
		 					Object.keys(resp).map(k=>{
		 						var obj = {};
		 							obj['title']=resp[k].course
		 							obj['data_id']=resp[k].class_id
		 							obj['data_location']=resp[k].location
		 							obj['description']=resp[k].description
		 							
		 							obj['daysOfWeek']=resp[k].dow
		 							obj['startRecur']=resp[k].date_from
		 							obj['endRecur']=resp[k].date_to
									obj['startTime']=resp[k].time_from
		 							obj['endTime']=resp[k].time_to
		 							
		 							
		 							evt.push(obj)
		 					})
							 console.log(evt)

		 		}
       
        // console.log(resp.length);
        calendar = new FullCalendar.Calendar(calendarEl, {
          headerToolbar: {
            left: 'prev,today,next',
            center: 'title',
            right: 'timeGridWeek,timeGridDay,listMonth'
          },
          initialView: 'timeGridWeek',
          
          initialDate: '<?php echo date('Y-m-d') ?>',
          weekNumbers: false,
          navLinks: true,
          editable: false,
          selectable: true,
          nowIndicator: true,
          dayMaxEvents: true, 
          events: evt,

        //   eventClick: function(info) {
        //     var eventDate = moment(info.event.start).format("YYYY-MM-DD");
        //     // console.log(eventDate);
        // },
           
          eventClick: function(e,el) {
            var data =  e.event.extendedProps;
            idClass = data.data_id;
            
            var eventDate = moment(e.event.start).format("DD/MM/YYYY");
            dateClass = eventDate;
            // console.log(eventDate);
            // uni_modal('View Schedule Details','view_schedule.php?id='+data.data_id,'mid-large');
            $.ajax({
              url:"schedule_action.php",
              method:"POST",
              data:{action:'single_fetch', class_id:data.data_id, eventDate:eventDate},
              success:function(data)
              {
                $('#viewModal').modal('show');
                $('#class_details').html(data);
              }
            });


          }
        });
      }
      },
      
      complete:function(){
		 		calendar.render();
      }
  });

});

  $(document).on('click', '#button_scan-qr', function(){
    // console.log(idClass,dateClass);
    // qrcode = md5(idClass+""+dateClass);
    // console.log(qrcode);

    // $('#viewModal').modal('hide');
    // $('#QRModal').modal('show');

    // var resultContainer = document.getElementById('qr-reader-results');
    // var lastResult, countResults = 0;

    // function onScanSuccess(qrCodeMessage) {
    //   if (qrCodeMessage !== $('#qr_id').val()) {
    //       ++countResults;
    //       lastResult = qrCodeMessage;
    //       $('#qr_id').val(qrCodeMessage)
    //       _qsave()
                
    //   }
    // }

    // var html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", { fps: 10, qrbox: 250 });
    // html5QrcodeScanner.render(onScanSuccess);
  });

  // function _qsave(){
	// 	var qr_id = $('#qr_id').val()
	// 	$.ajax({
  //     url:'schedule_action.php',
  //     method:'POST',
  //     data:{action:'qr_scan', qr_id:qr_id, class_id:idClass, class_date:dateClass},
  //     success:function(data) {
  //       if(data.success) {

  //         swal({
  //           title: 'Success',
  //           text: 'Successfully Registered',
  //           icon: 'success',
  //           timer: 2000,
  //           buttons: false,
  //         })

  //       }
  //       if(data.error) {
  //         if(data.error1 != '') {
	// 					swal({
  //           title: 'QRCode is not Valid',
  //           text: data.error1,
  //           icon: 'error',
  //           timer: 200,
  //           buttons: false,
  //           })
	// 				}
  //         else if(data.error2 != '') {
	// 					swal({
  //           title: data.error2,
  //           text: resp.name+' is already recorded',
  //           icon: 'error',
  //           timer: 200,
  //           buttons: false,
  //           })
	// 				}
  //         else {
	// 					alert_toast('An Error Occured.');
	// 				}

  //       }

  //     }
  //   })
	// }


</script>