/**
  /* Beim Hinzufügen eine Produkts zum Warenkorb
  */
  function addProduct(productInput, quantity){
    checkMinusBestellung(productInput, quantity);
    return true;
  }

  /**
  /* Beim Hinzufügen eine Produkts zum Warenkorb
  
  addProduct: function(pid, quantity){
    //checkMinusBestellung(productInput, quantity);
    var productItem = this.selProducts.children("option[rel=" + pid + "]");
    if(productItem.length > 0){
      // Item already added, update the quantity and total
      var curPValue =  productItem.attr("value");
      var valueArray = curPValue.split('|');
      var prdId = valueArray[0];
      var prdQty = valueArray[1];
      prdQty = (prdQty-0) +  (quantity-0);
      var newPValue =  prdId + '|' + prdQty ;
      productItem.attr("value",newPValue).attr('selected', true);    
      // Now go for updating the design
      //var lalQuantity =  $('#lblQuantity'+i).val(prdQty);
      //var lblTotal =  $('#lblTotal'+i).html(prdTotal);
      // show product quantity updated message
      //showHighlightMessage(messageQuantityUpdated);                                                      
    }else{
      // This is a new item so create the list
      var prodStr = pid + '|' + quantity;
      productItem = $('<option></option>').attr("rel",pid).attr("value",prodStr).attr('selected', true).html(pid);
      this.selProducts.append(productItem);
      // show product added message
      //howHighlightMessage(messageItemAdded);                            
    }

    return true;
  }
  */

  
  /**
  /* Beim Aktualisieren eines Produkts im Warenkorb
  */
  function updateProduct(productInput, quantity){
    checkMinusBestellung(productInput, quantity)
    return true;
  }

  /**
  /* Beim Löschen eines Produkts im Warenkorb
  */
  function removeProduct(productInput, quantity){
    checkMinusBestellung(productInput, quantity)
    return true;
  }

  /**
  /* Minusbestellung prüfen. Minusbestellung wenn eingegebene Anzahl > Lagerbestand
  */
  function checkMinusBestellung(productInput, quantity){
    $productHiddenInput = $(productInput[0].outerHTML);
    $productQuantityInput = $(".quantityFor_" + replacedPid($productHiddenInput.attr("pid")));

    bestand = ($productHiddenInput.attr("bestand"));
    if(quantity > bestand){
      alert("Vorsicht, Sie führen eine Minusbestellung aus!");
      $productQuantityInput.parent().addClass("minusbestand");
    }else{
      $productQuantityInput.parent().removeClass("minusbestand");
    }
  }


  /**
   * @param object products Html <select> Element mit ausgewählten Produkten

   */
  function checkout(selProducts){
    
    /* Beispiel selProducts:
      <select name=​"products_selected[]​" style=​"display:​ none;​ " multiple=​"multiple">​
      <option rel=​"0" value=​"100|1" selected=​"selected">​Apple MacBook Pro MA464LL/A 15.4" Notebook PC​</option>​
      <option rel=​"1" value=​"101|2" selected=​"selected">​Sony VAIO 11.1" Notebook PC​</option>​
      </select>​
     */
    
      //TODO: Wenn keine Produkte ausgewählt sind, Abbruch
     //console.log(selProducts);

    var valObj = validateCheckout();

    if(valObj.error){
      alert(valObj.message);
    }else{
      postData = {
        username: "geibi",
        products: [],
        stock_key: $("#stock_key").val()
      }
      
      selProducts.find("option").each(function(){

        var values = $(this).val().split("|");
        log(values);
        var pid = values[0];
        var quantity = values[1];
        var lagerort = values[2];

        postData.products.push({pid: pid, quantity: quantity, lagerort: lagerort});
      });
      
      $.ajax({
          type: "POST",
          url: baseUrl + "/home/sendCheckout",
          data: postData,
          dataType: "json",
          beforeSend: function(x) {
            if (x && x.overrideMimeType) {
              x.overrideMimeType("application/j-son;charset=UTF-8");
            }
          },
          success: function(data){
            if(data.error){
              alert("Fehler bei der Übertragung: " + data.message);
            }else{
              alert(data.message);
            }
              //alert(data);
          }
      });
    }
    
    //console.log(postData);
    
  }


  function validateCheckout(){
    var retObj = {
      error : false,
      message : "Es ist ein Fehler aufgetreten!"
    };

    if($("#jobnummer").val() == ""){
      retObj.error = true;
      retObj.message = "Fehler: Bitte geben Sie eine Jobnummer ein."
    }

    if($("#jobdatum").val() == ""){
      retObj.error = true;
      retObj.message = "Fehler: Bitte geben Sie ein Jobdatum ein."
    }

    return retObj;
  }


  /**
  /* Ersetzt die pId damit sie für CSS Klassennamen verwendet kann
  /* In der XML hat die Artikelnummer z.B. Punkte, diese werden entfernt
  */
  function replacedPid(pId){
    return str_replace(".", "", pId)
  }