$(function(){

  initUploads();
  Shadowbox.init();
  
});

function initUploads(){

  //beim Verlassen des Feldes Update in der Datenbank
  $("#tblUploads select").each(function(){
    $(this).on("change", function(){
      var uploadID = $(this).attr("rel");
      updateUploadEntry("t", $(this).val(), uploadID);
    });
  });

  //beim Verlassen des Feldes Update in der Datenbank
  $("#tblUploads td.price input").each(function(){
    $(this).on("change", function(){
      var uploadID = $(this).attr("rel");
      updateUploadEntry("p", $(this).val(), uploadID);
    });
  });

  //Icon normal/delete bei hover austauschen
  $("#tblUploads td.uploadIcon").hover(
    function () {
      $(this).toggleClass("hover");
    }
  );

  //Klick auf img löscht den Eintrag
  $("#tblUploads td.uploadIcon img").each(function(){
    
    $(this).on("click", function(){
      if(confirm("Möchten Sie diesen Upload wirklich löschen")){
        var uploadID = $(this).attr("rel");
        deleteUploadEntry(uploadID);
      }
    });

  });

}

function deleteUploadEntry(uploadID){

  //TODO: confirm

  $.ajax({
    type: "POST",
    url: baseUrl + "upload/delete",
    dataType: 'json',
    data: "uploadID=" + uploadID
  }).done(function( data ) {

      if(data.error === false){
        userMessage(data);
        $("#uploadRow_" + uploadID).fadeOut();
      }else{
        alert("Beim Löschen ist ein Fehler aufgetreten.");
      }

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
    theme: 'df',
    text: data.message,
    dismissQueue: true,
    timeout: 1500,
    type: notyType

  });
  
}