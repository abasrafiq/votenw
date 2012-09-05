
function Cart(){};
Cart.prototype = {
  cart: $("#cart"),
  cartContainer: $("#cartContainer"),
  cartContainerInner: $("#cartContainer .inner"),
  cartContent: $("#cartContent"),
  selProducts: $("#selProducts"),
  cartLoading: $("#cartLoading"),
  cartOrderForm: $("#cartOrderForm"),
  btnCartOrderSubmit: $("#btnCartOrderSubmit"),
  tblCartProducts: $("#tblCartProducts"),
  cartStartpointX: 10,
  cartEndpointX: 310,


  init: function(){
    var self =  this;
    
    //Warenkorbeintrag hinzufügen
    $(".btnAddToCart").on("click", function(e){
      e.preventDefault();

      var theInput = $(this).parent().find("input"); //quantity Input
      var quantity = theInput.val();
      var pid = theInput.attr("data-pid");

      //self.cartContainerInner.show();
      self.addProduct(pid, quantity);
      
    });


    //Warenkorbeintrag löschen
    $(".btnDeleteFromCart").live("click", function(e){
      e.preventDefault();

      var theInput = $(this).parent().parent().find(".prodQuantity"); //quantity Input
      var pid = theInput.attr("data-pid");

      self.removeProduct(pid);
      
    });

    //in place editing für freie Artikel
    $('#tblFreieArtikel .editable').live('click', function(){
      $(this).editable(function(value, settings){ 
        return(value);
      },{
        submit  : 'OK',
        event: 'dblclick',
        placeholder: ''
      });
    });
 


    //Subprodukt hinzufügen/ausschließen
    $(".plistInclude").live("click", function(e){
      self.excludePlistProduct($(this).attr("data-plist-pid"), $(this).attr("data-pid"), $(this).attr("checked"));
    });
    

    //Warenkorb bei Verlassen des Anzahl Feldes aktualisieren
    $("#cart input.prodQuantity").live("focusout", function(e){
      e.preventDefault();

      var theInput = $(this).parent().find("input"); //quantity Input
      var pid = theInput.attr("data-pid");
      var quantity = theInput.val();

      self.updateProduct(pid, quantity);
      
    });


    //Warenkorb anzeigenb/ausblenden
    $("#aControlCart").on("click", function(e){
      self.controlCart();
      e.preventDefault();
    });


    //Lieferschein Vorschau
    $("#aPreviewDeliveryBill").on("click", function(e){
      e.preventDefault();
      var url = $(this).attr("href");

      var formData = self.getOrderFormAsJSON();

      $.ajax({
        type: "POST",
        url: url,
        data: formData
      }).done(function( data ) {
        if(!self.isAjaxResponseError(data)){
          //console.log(data);
          Shadowbox.open({
              content:    data,
              player:     "html",
              width: 800,
              height: 800
          });
        }
      }).error(function(e){
        //console.log(e);
      });

    });


    /* Order Form */
    $("#btnCartOrderSubmit").on("click", function(e){
      e.preventDefault();
      self.checkout();
    });

    //Beim Start Warenkorb aktualisieren und einblenden
    self.updateCart();

  },

  /**
  /* Cart aufgrund Cookie ein/ausblendn, Text und Pfeile anzeigen
  */
  controlCart: function(){

    var self =  this;

    if(self.cartContainerInner.is(":visible")){
      
      $("body").css({"overflow-y" : "scroll"});

      self.cartContainerInner.hide();
      $("#aControlCart").css({"background" : "#ce235b url(" + baseUrl + "_assets/images/cart_hidden.gif) left top no-repeat"})
      $("#aControlCart").html("Warenkorb einblenden");
      $.cookie('cart_hidden', "false");
    }else{
      
      var topOffset = 77;

      $("body").css({"overflow-y" : "hidden"});

      //if( (self.cartContainerInner.height() + topOffset) > $(window).height() ){
        //self.cartContainerInner.height(600).css({"overflow-y" : "scroll"}).show();
      //}else{
        self.cartContainerInner.css({"height" : $(window).height() - topOffset, "overflow-y" : "scroll"}).show();
      //}

      $("#aControlCart").css({"background" : "#ce235b url(" + baseUrl + "_assets/images/cart_visible.gif) left top no-repeat"})
      $("#aControlCart").html("Warenkorb ausblenden");
      $.cookie('cart_hidden', "true");

    }
  },

  /**
  /* Minusbestellung prüfen. Minusbestellung wenn eingegebene Anzahl > Lagerbestand
  */
  checkMinusBestellung: function(){

    $("#tblCartProducts .prodQuantity").each(function(i,n){
      var quantity = $(this).val();
      var pid = $(this).attr("data-pid");
      var $productHiddenInput = $("#hiddenProduct_" + pid);
      var bestand = $productHiddenInput.attr("bestand");
      
      if(quantity > bestand){
        if(!$(this).parent().find(".minusbestand").length){
          $minusbestandObj = $("<div></div>").attr("class", "minusbestand").text("Achtung Minusbestand! Bestellung ist trotzdem möglich.");
          $(this).parent().append($minusbestandObj);
        }
      }else{
        $(this).parent().find(".minusbestand").remove();
      }
    });

  },

  /**
  /* Hinzufügen eines Produkts zum Warenkorb
  */
  addProduct: function(pid, quantity){
    var self =  this;

    self.cartLoading.show();

    $.ajax({
      type: "POST",
      url: baseUrl + "cart/add",
      dataType: 'json',
      data: { pid: pid, quantity: quantity }
    }).done(function( data ) {
      if(!self.isAjaxResponseError(data)){
        self.updateCart();
        self.userMessage(data);
        $.cookie('cart_hidden', "false");
      }
    });
  },


  /**
  /* Aktualisieren eines Produkts zum Warenkorb
  */
  updateProduct: function(pid, quantity){
    var self =  this;

    self.cartLoading.show();

    $.ajax({
      type: "POST",
      url: baseUrl + "cart/update",
      dataType: 'json',
      data: { pid: pid, quantity: quantity }
    }).done(function( data ) {
      if(!self.isAjaxResponseError(data)){
        self.updateCart();
        self.unlockCheckout();
        self.userMessage(data);
      }else{
        self.lockCheckout();
      }
    });
  },


  /**
  /* Löschen eines Produkts aus dem Warenkorb
  */
  removeProduct: function(pid){
    var self =  this;

    self.cartLoading.show();

    $.ajax({
      type: "POST",
      url: baseUrl + "cart/remove",
      dataType: 'json',
      data: { pid: pid}
    }).done(function( data ) {
      if(!self.isAjaxResponseError(data)){
        self.updateCart();
        self.userMessage(data);
      }
    });
  },

  updateCart: function(){
    var self = this;

    self.cartLoading.show();

    $.ajax({
      type: "POST",
      url: baseUrl + "cart/showCart"
    }).done(function( data ) {
      if(!self.isAjaxResponseError(data)){
        self.unlockCheckout();
        self.cartContent.html(data);
        self.checkMinusBestellung();
        Shadowbox.init({ skipSetup: true }); Shadowbox.setup(); 
      }else{
        self.lockCheckout();
      }
      self.cartLoading.hide();
    });
  },

  lockCheckout: function(){
    var self =  this;

    self.btnCartOrderSubmit.hide();
  },

  unlockCheckout: function(){
    var self =  this;

    self.btnCartOrderSubmit.show();
  },


  /**
  /* Warenkorb bestellen
  */
  checkout: function(){
    var self =  this;
    
    var formData = self.getOrderFormAsJSON();

    self.cartLoading.show();

    $.ajax({
      type: "POST",
      url: baseUrl + "cart/sendCheckout",
      dataType: 'json',
      data: formData
    }).done(function( data ) {
      if(!self.isAjaxResponseError(data)){
        self.updateCart();
        self.userMessage(data);
        window.location.href = baseUrl + "cart/thankyou";
      }
    });
  },

  /*
  /* "JSONifiziert" alle Inputs aus dem Order Form für Checkout und Preview
  */
  getOrderFormAsJSON: function(){
    var self =  this;
    var formData = $("#frmCartOrder").serializeFormJSON(); //json object
    formData.freieProdukte = self.getFreieArtikelJson().products;
    return formData;
  },


  /*
  /* Freie Artikel json bauen
  /* @return json object "products"
  */
  getFreieArtikelJson: function(){
    var jsonText = '{"products":['; //json sctring aufbauen
    var arProductTexts = []; //um die verschiedenen Produkte dann per "," joinen zu können
    $("tr.optionalProducts").each(function(){
      productText = "{";
      productText += '"menge":"' + $(this).find(".menge").text() + '"';
      productText += ",";

      productText += '"bezeichnung":"' + $(this).find(".bezeichnung").text() + '"';
      productText += ",";

      productText += '"herkunft":"' + $(this).find(".herkunft").text() + '"';
      productText += "}";

      arProductTexts.push(productText);
    });

    jsonText += arProductTexts.join(",");
    jsonText += "]}";

    return $.parseJSON(jsonText);
  },

  /**
  /* Beim Klick auf Checkbox in Cart das Subprodukt eines Produkts entfernen/hinzufügen
  */
  excludePlistProduct: function(plistPid, pid, include){
    var self =  this;
    var exclude = include !== "checked";
    self.cartLoading.show();

    $.ajax({
      type: "POST",
      url: baseUrl + "cart/excludePlistProduct",
      dataType: 'json',
      data: "plistPid=" + plistPid + "&pid=" + pid + "&exclude=" + exclude
    }).done(function( data ) {
      if(!self.isAjaxResponseError(data)){
        self.updateCart();
        self.userMessage(data);
      }
    });
  
  },



  


  //Steht in data.error true, wird eine Fehlermeldung ausgegeben
  isAjaxResponseError: function(data){
    var self = this;

    if(data.error){
      self.userMessage(data);
      self.cartLoading.hide();
      return true;
    }
    return false;
  },

  // Kurze Flash-Message an den Benutzer
  userMessage: function(data){
    var messageContainer = $("#cartMessagesContainer");
    //messageContainer.removeClass().addClass(data.messageCssClass);
    //messageContainer.find("div").stop().remove();
    //var messageEl = messageContainer.prepend("<div></div>").hide().html(data.message);
    //messageEl.fadeIn("fast").delay(2000).fadeOut(5000);
    
    notyType = data.messageCssClass;
    if(notyType == ""){
      notyType = "success";
    }
    
    noty({
      text: data.message,
      dismissQueue: true,
      timeout: 1500,
      type: notyType

    });
  }


  

};