$(document).on("click", ".livesearch p", function(){
  $(this).parents(".containerRow").find('input[type="text"]').val($(this).text());
  $(this).parent(".livesearch").empty();
  var customerId = $(this).attr('id');
  $.ajax({
      url: "http://localhost:8081/api/v1/animals/customerid/"+customerId,
      type : "GET",
      crossDomain: true,
      success: function(result){
        $('#pets').empty();
        for (var i = 0; i < result.length; i++) {
            var pet = result[i];
            $('#pets').append(new Option(pet.name));
        }


        //$("#livesearch").html(result).show();
      }
    })
});

function showResult(str) {
  if (str.length==0) {
    $(".livesearch").empty();
    $('#pets').empty();
    return;
  }

  $.ajax({
    url: "http://localhost:8081/api/v1/customers/name/"+str,
    type : "GET",
    crossDomain: true,
    success: function(result){
      $(".livesearch").empty();
      for (var i = 0; i < result.length; i++) {
        $(".livesearch").append("<p id="+result[i].id+">"+result[i].firstName +" "+ result[i].lastName +"</a>");
      }
    }
  })
}

function firstDateCheck() {
  var userDate = $("#checkInDate").get(0).value;
  var todaysDate = new Date();
  $('#room').empty();
  if(new Date(userDate).setHours(0,0,0,0) < todaysDate.setHours(0,0,0,0)){
    alert("Check In Date cannot be in the past.")
    $("#checkInDate").val("");
    return false;
  }else{
    return true;
  }
}

function updateType(startDate, endDate){
  $.ajax({
    url: "http://localhost:8081/api/v1/rooms/available/"+startDate+"/"+endDate,
    type : "GET",
    crossDomain: true,
  }).done(function(data) {
  var counts = [0, 0, 0, 0];
  $('#room').empty();
  for (var i = 0; i < data.length; i++) {
    var counter = data[i];
    $('#room').append(new Option(counter.name +" ("+counter.size+")", counter.name));
    switch(counter.size) {
      case "Small":
      counts[0]+=1;
      break;
      case "Normal":
      counts[1]+=1;
      break;
      case "Large":
      counts[2]+=1;
      break;
      case "Family":
      counts[3]+=1;
      break;
    }
  }
  if(data.length==0){
  //create red box and offer to look at calender for dates
    $(".row#available").show();
    $(".availabilityContainer").attr("id","availableFalse");
  }else{
    $(".row#available").show();
    $(".availabilityContainer").attr("id","availableTrue");
  //create green box and inform room sizes
  }
});
}

function secondDateCheck() {
  var userDate = $("#checkOutDate").get(0).value;
  var firstDate = $("#checkInDate").get(0).value;
  $('#room').empty();
  if(new Date(userDate).setHours(0,0,0,0) < new Date(firstDate).setHours(0,0,0,0)){
    alert("Check Out Date cannot be before check in date.")
    $("#checkOutDate").val("");
    return false;
  }else{
    updateType(firstDate, userDate);
    return true;
  }
}
