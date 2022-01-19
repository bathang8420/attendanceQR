<?php

//index.php

include('header.php');

?>

<main id=""  class="">
  <div class="container pt-4 pb-4">
    <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
        <div id="calendar"></div>
      </div>
    </div>
    </div>
  </div>
</main>  		

</body>
</html>

<style>

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
        <input type="submit" name="button_gen-qr" id="button_gen-qr" class="btn btn-success btn-sm" value="QR Code" />


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
        <h4 class="modal-title">QR Code</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="qr_details">
        <!-- <div class="qr-code">This is QRCode</div> -->
        

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
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
          },
          
          eventDidMount: function(info) {
            $(info.el).tooltip({ 
              title: info.event.extendedProps.description,
              placement: "top",
              trigger: "hover",
              container: "body"
            });
          },
          
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
            // checkButton();
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


function checkButton() {
    var thisDay = moment().format("DD/MM/YYYY");
    if (thisDay !== dateClass) 
    {
        $("#button_gen-qr").prop("disabled", true);
    }

}

$(document).on('click', '#button_gen-qr', function(){
    console.log(idClass,dateClass);
    $.ajax({
      url:"schedule_action.php",
      method:"POST",
      data:{action:'qr_fetch', class_id:idClass, class_date:dateClass},
      success:function(data)
      {
        $('#viewModal').modal('hide');
  	    $('#QRModal').modal('show');
        $('#qr_details').html(data);
        var fiveMinutes = 60 * 1,
        display = document.querySelector('#time');
        startTimer(fiveMinutes, display);
        setTimeout(function() {
          $('#viewModal').modal('show');
  	      $('#QRModal').modal('hide');
        },1000*fiveMinutes)
      }
    });
  	// teacher_id = $(this).attr('id');
  });


  function startTimer(duration, display) {
    var timer = duration, minutes, seconds;
    setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds;

        if (--timer < 0) {
            timer = duration;
        }
    }, 1000);
}


</script>