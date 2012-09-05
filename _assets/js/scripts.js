function appInit(){
  
  var theCart = new Cart();
  theCart.init();

  initSearch();

  initDatepicker();
  Shadowbox.init();


}


function initSearch(){
  $('.searchInput').on("keyup", throttle(function(){
      searchTable($($(this).attr("rel")), $(this).val());
  }, 250));

  $('.searchReset').on("click", function(){
      var searchInput = $(this).parent().find(".searchInput");
      var $searchTable = $(searchInput.attr("rel"));
      searchInput.val("");
      searchTable($searchTable, "");
  });

  
}

function initDatepicker(){
  $( ".datepicker" ).datepicker({
    dateFormat: "dd.mm.yy",
    firstDay: 1,
    showButtonPanel: true,
    closeText: 'Kalender schließen',
    currentText: 'Heute',
    dayNames: ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
    dayNamesMin: ['SO', 'MO', 'DI', 'MI', 'DO', 'FR', 'SA'],
    monthNames: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober',  'November', 'Dezember'],
    showAnim: 'blind'
  });
}