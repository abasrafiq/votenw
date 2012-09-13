function appInit(){

  initUploads();

}

function initUploads(){

  $("#tblUploads select").each(function(){
    $(this).on("change", function(){
      var uploadID = $(this).attr("rel");
      updateUploadEntry("t", $(this).val(), uploadID);
    });
  });

  $("#tblUploads td.price input").each(function(){
    $(this).on("change", function(){
      var uploadID = $(this).attr("rel");
      updateUploadEntry("p", $(this).val(), uploadID);
    });
  });

}


function updateUploadEntry(field, value, uploadID){
  $.ajax({
    type: "POST",
    url: baseUrl + "upload/update",
    dataType: 'json',
    data: "field=" + field + "&value=" + value + "&uploadID=" + uploadID
  }).done(function( data ) {
      //self.cartLoading.hide();
      userMessage(data);
  });
}

//Steht in data.error true, wird eine Fehlermeldung ausgegeben
function isAjaxResponseError(data){
  var self = this;

  if(data.error){
    //self.cartLoading.hide();
    return true;
  }
  return false;
}



function userMessage (data){

  if(data.message === "" || data.message === undefined){
    return;
  }
  
  var messageContainer = $("#MessagesContainer");
  //messageContainer.removeClass().addClass(data.messageCssClass);
  //messageContainer.find("div").stop().remove();
  //var messageEl = messageContainer.prepend("<div></div>").hide().html(data.message);
  //messageEl.fadeIn("fast").delay(2000).fadeOut(5000);
  
  notyType = data.messageCssClass;
  if(notyType === "" || notyType === undefined){
    notyType = "success";
  }
  
  //alert(data.message);
  
  noty({
    text: data.message,
    dismissQueue: true,
    timeout: 1500,
    type: notyType

  });
  
}