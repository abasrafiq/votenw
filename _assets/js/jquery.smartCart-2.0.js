/*
 * SmartCart 2.0 plugin
 * jQuery Shopping Cart Plugin
 * by Dipu 
 * 
 * http://www.techlaboratory.net 
 * http://tech-laboratory.blogspot.com
 */
 
(function($){
  $.fn.smartCart = function(options) {
    var options = $.extend({}, $.fn.smartCart.defaults, options);
                
    return this.each(function() {
      var obj = $(this);
      // retrive all products
      var products = $("input[type=hidden]",obj);
      var resultName = options.resultName;
      var cartItemCount = 0;
      var cartProductCount = 0; // count of unique products added
      var subTotal = 0; 
      var toolMaxImageHeight = 200;
                
      // Attribute Settings
      // You can assign the same you have given on the hidden elements
      var attrProductId = "pid";  // Product Id attribute
      var attrProductName = "pname"; // Product Name attribute   
      var attrProductPrice = "pprice"; // Product Price attribute  
      var attrProductImage = "pimage"; // Product Image attribute
      var attrProductImageBig = "pimageBig"; // Product Image attribute
      var attrCategoryName = "pcategory";
                
      // Labels & Messages              
      var labelCartMenuName = 'Warenkorb (_COUNT_)';  // _COUNT_ will be replaced with cart count
      var labelCartMenuNameTooltip = "Warenkorb | Anzahl Produkte: _PRODUCTCOUNT_ ";
      var labelProductMenuName = 'Produkte';
      var labelSearchButton = "Suchen";
      var labelSearchText = "Suche";
      var labelCategoryText = "Category";
      var labelClearButton = "Zurücksetzen";
      var labelAddToCartButton = "Hinzufügen"; 
      var labelQuantityText = "Anzahl";
      var labelProducts = 'Produkt';
      var labelPrice = 'Price';
      var labelSubtotal = 'Subtotal';
      var labelTotal = 'Total';
      var labelRemove = 'Entfernen';
      var labelCheckout = 'Bestellung abschicken';
                
      var messageConfirmRemove = '"_PRODUCTNAME_" aus dem Warenkorb entfernen?'; //  _PRODUCTNAME_ will be replaced with actula product name
      var messageCartEmpty = "Warenkorb ist leer";
      var messageProductEmpty = "Keine Produkte gefunden";
      var messageProductAddError = "Produkt kann nicht hinzugefügt werden";
      var messageItemAdded = 'Produkt zum Warenkorb hinzugefügt';
      var messageItemRemoved = 'Produkt aus dem Warenkorb entfernt';
      var messageQuantityUpdated = 'Anzahl aktualisiert';
      var messageQuantityErrorAdd = 'Ungültige Anzahl, Produkt kann nicht hinzugefügt werden';
      var messageQuantityErrorUpdate = 'Ungültige Anzahl, Anzahl kann nicht aktualisiert werden';
                
      //Create Main Menu
      cartMenu = labelCartMenuName.replace('_COUNT_','0'); // display default
      var btShowCart = $('<a>'+cartMenu+'</a>').attr("href","#scart");
      var btShowProductList = $('<a>'+labelProductMenuName+'</a>').attr("href","#sproducts");
      var msgBox2 = $('<div></div>').addClass("scMessageBar2").hide();
                
      var elmProductMenu = $("<li></li>").append(btShowProductList);
      var elmCartMenu = $("<li></li>").append(btShowCart);
      var elmMsgBox = $("<li></li>").append(msgBox2);
      var elmMenuBar = $('<ul></ul>').addClass("scMenuBar").append(elmProductMenu).append(elmCartMenu).append(elmMsgBox);
      obj.prepend(elmMenuBar);
                
      // Create Search Elements
      
      var elmPLSearchPanel = $('<div></div>').addClass("scSearchPanel");
      if(options.enableSearch){
        var btSearch = $('<a>'+labelSearchButton+'</a>').attr("href","#").addClass("scSearch").attr("title","Search Product");                
        $(btSearch).click(function() {
          var searcStr = $(txtSearch).val();                      
          populateProducts(searcStr);
          
          return false;
        }); 
        var btClear = $('<a>'+labelClearButton+'</a>').attr("href","#").addClass("scSearch").attr("title","Clear Search");
        $(btClear).click(function() {
          $(txtSearch).val('');                      
          populateProducts('');
          return false;
        });
        var txtSearch = $('<input type="text" />').attr("value","").addClass("scTxtSearch")
        $(txtSearch).keyup(function() {
          var searcStr = $(this).val();                      
          populateProducts(searcStr);
        });  
        var lblSearch = $('<label>'+labelSearchText+':</label>').addClass("scLabelSearch");                                                 
        elmPLSearchPanel.append(lblSearch).append(txtSearch).append(btSearch).append(btClear);                
      }
      // Create Category filter
      if(options.enableCategoryFilter){
        var lblCategory = $('<label>'+labelCategoryText+':</label>').addClass("scLabelCategory");
        var elmCategory = $("<select></select>").addClass("scSelCategory");                
        elmPLSearchPanel.append(lblCategory).append(elmCategory);                
        fillCategory();
      }
      
      
      
      

      // Create Product List                
      var elmPLContainer = $('<div></div>').addClass("scTabs").hide();
      elmPLContainer.prepend(elmPLSearchPanel);
                
      var elmPLProducts = $('<table id="tblProducts"></table>').addClass("scProductList");
                
      elmPLContainer.append(elmPLProducts);
                
                
      // Create Cart
      var elmCartContainer = $('<div></div>').addClass("scTabs").hide();
      var elmCartHeader = $('<div></div>').addClass("scCartHeader");
      var elmCartHeaderTitle1 = $('<label>'+labelProducts+'</label>').addClass("scCartTitle scCartTitle1");
      var elmCartHeaderTitle2 = $('<label>'+labelQuantityText+'</label>').addClass("scCartTitle scCartTitle2");
      elmCartHeader.append(elmCartHeaderTitle1).append(elmCartHeaderTitle2);
                
      var elmCartList = $('<div></div>').addClass("scCartList");
      elmCartContainer.append(elmCartHeader).append(elmCartList);
                
      obj.append(elmPLContainer).append(elmCartContainer);
                
                
      // Create Bottom bar
      
      var elmBottomBar = $('<div></div>').addClass("scBottomBar");
      //var elmBottomSubtotalText = $('<label>'+labelTotal+':</label>').addClass("scLabelSubtotalText");
      //var elmBottomSubtotalValue = $('<label>'+getMoneyFormatted(subTotal)+'</label>').addClass("scLabelSubtotalValue");
      var btCheckout = $('<a>'+labelCheckout+'</a>').attr("href","#").addClass("scCheckoutButton");               
      $(btCheckout).click(function() {
        if($.isFunction(options.onCheckout)) {
          // calling onCheckout event;
          options.onCheckout.call(this,elmProductSelected);
        }else{
          $(this).parents("form").submit();                   
        }
        return false;
      });
      //elmBottomBar.append(btCheckout).append(elmBottomSubtotalValue).append(elmBottomSubtotalText);
      elmBottomBar.append(btCheckout);

      var formHtml = '<div class="checkoutFormAdditionalInput">'
          + '<label>Jobnummer* </label><input type="text" name="jobnummer" value="123.44.212-0"  /></div>'
          + '<div><label>Jobdatum</label><input type="text" name="jobdatum" id="jobdatum" value="12.08.2012" class="datepicker" /></div>'
          + '(*) = Pflichtfeld'
        + '</div>'
      ;
      elmBottomBar.append(formHtml);

      obj.append(elmBottomBar);
                
      // Create Tooltip
      var tooltip = $('<div></div>').addClass('tooltip').hide();
      obj.append(tooltip);
      obj.bind("mousemove",function(){
        tooltip.hide();                    
        return true;
      });                
                
      // Create SelectList                                
      var elmProductSelected = $('select[name="'+resultName+'"]',obj);
      if(elmProductSelected.length <= 0){
        elmProductSelected = $("<select></select>").attr("name",resultName).attr("multiple","multiple").hide();
        refreshCartValues();
      }else{ 
        elmProductSelected.attr("multiple","multiple").hide();
        populateCart(); // pre-populate cart if there are selected items  
      }                 
      obj.append(elmProductSelected);
                
      // prepare the product list
      populateProducts();
                
      if(options.selected == '1'){
        showCart();
      }else{
        showProductList();
      }	       

      $(btShowProductList).bind("click", function(e){
        showProductList();
        return false;
      }); 
      $(btShowCart).bind("click", function(e){
        showCart();
        return false;
      });

      function showCart(){  
        $(btShowProductList).removeClass("sel");
        $(btShowCart).addClass("sel");
        $(elmPLContainer).hide();
        $(elmCartContainer).show();
      }
      function showProductList(){ 
        $(btShowProductList).addClass("sel");
        $(btShowCart).removeClass("sel");  
        $(elmCartContainer).hide();
        $(elmPLContainer).show();
      }
                
      function addToCart(i,qty){
        var addProduct = products.eq(i);
        
        
        //alert(addProduct.attr("plist"));

        if(addProduct.length > 0){
          if($.isFunction(options.onAdd)) {
            // calling onAdd event; expecting a return value
            // will start add if returned true and cancel add if returned false
            if(!options.onAdd.call(this,$(addProduct),qty)){
              return false;
            }
          }
          var pId = $(addProduct).attr(attrProductId);
          var pName = $(addProduct).attr(attrProductName);
          var pPrice = $(addProduct).attr(attrProductPrice);
          var prdLagerort = $(addProduct).attr("Lagerort");
          

          // Check wheater the item is already added
          var productItem = elmProductSelected.children("option[rel=" + i + "]");
          if(productItem.length > 0){
            // Item already added, update the quantity and total
            var curPValue =  productItem.attr("value");
            var valueArray = curPValue.split('|');
            var prdId = valueArray[0];
            var prdQty = valueArray[1];
            var prdLagerort = valueArray[2];
            prdQty = (prdQty-0) +  (qty-0);
            var newPValue =  prdId + '|' + prdQty + '|' + prdLagerort;
            productItem.attr("value",newPValue).attr('selected', true);    
            var prdTotal = getMoneyFormatted(pPrice * prdQty);
            // Now go for updating the design
            var lalQuantity =  $('#lblQuantity'+i).val(prdQty);
            var lblTotal =  $('#lblTotal'+i).html(prdTotal);
            // show product quantity updated message
            showHighlightMessage(messageQuantityUpdated);                                                      
          }else{
            // This is a new item so create the list
            var prodStr = pId + '|' + qty + '|' + prdLagerort;
            productItem = $('<option></option>').attr("rel",i).attr("value",prodStr).attr('selected', true).html(pName);
            elmProductSelected.append(productItem);
            addCartItemDisplay(addProduct,qty);
            // show product added message
            showHighlightMessage(messageItemAdded);                            
          }
          // refresh the cart
          refreshCartValues();
          // calling onAdded event; not expecting a return value
          if($.isFunction(options.onAdded)) {
            options.onAdded.call(this,$(addProduct),qty);
          }
        }else{
          showHighlightMessage(messageProductAddError);
        }
      }
                
      function addCartItemDisplay(objProd,Quantity){
        var pId = $(objProd).attr(attrProductId);
        var pIndex = products.index(objProd);
        var pName = $(objProd).attr(attrProductName);
        var pPrice = $(objProd).attr(attrProductPrice);
        var prodImgSrc = $(objProd).attr(attrProductImage);
        var pTotal = (pPrice - 0) * (Quantity - 0);
        pTotal = getMoneyFormatted(pTotal);
        // Now Go for creating the design stuff
                    
        $('.scMessageBar',elmCartList).remove();
                    
        var elmCPTitle1 = $('<div></div>').addClass("scCartItemTitle scCartItemTitle1");                            
        if(prodImgSrc && options.enableImage && prodImgSrc.length>0){
          var prodImg = $("<img></img>").attr("src",prodImgSrc).addClass("scProductImageSmall");
          if(prodImg && options.enableImageTooltip){
            prodImg.bind("mouseenter mousemove",function(){
              showTooltip($(this));                    
              return false;
            });
            prodImg.bind("mouseleave",function (){
              tooltip.hide();
              return true;
            });
          }
          elmCPTitle1.append(prodImg);
        }
        var elmCP = $('<div></div>').attr("id","divCartItem"+pIndex).addClass("scCartItem");
  
        var pTitle =  pName;
        var phtml = formatTemplate(options.cartItemTemplate, $(objProd));
        var elmCPContent = $('<div></div>').html(phtml).attr("title",pTitle);                        
        elmCPTitle1.append(elmCPContent);   
        
        var inputQty = $('<input type="text" value="'+Quantity+'" />').attr("id","lblQuantity"+pIndex).attr("rel",pIndex).addClass("scTxtQuantity2").addClass("quantityFor_" + replacedPid(pId));                    
        $(inputQty).bind("change", function(e){
          var newQty = $(this).val();
          var prodIdx = $(this).attr("rel");
          newQty = newQty - 0;
          if(validateNumber(newQty)){
            updateCartQuantity(prodIdx,newQty);
          }else{
            var productItem = elmProductSelected.children("option[rel=" + prodIdx + "]");
            var pValue = $(productItem).attr("value");
            var valueArray = pValue.split('|'); 
            var pQty = valueArray[1];
            $(this).val(pQty);                                                 
            showHighlightMessage(messageQuantityErrorUpdate);
          }
          return true;
        });
                    
        var elmCPTitle3 = $('<div></div>').append(inputQty).addClass("scCartItemTitle scCartItemTitle3");

        var btRemove = $('<a>'+labelRemove+'</a>').attr("rel",pIndex).attr("href","#").addClass("scRemove").attr("title","Remove from Cart");
        $(btRemove).bind("click", function(e){
          var idx = $(this).attr("rel");
          removeFromCart(idx);
          return false;
        });
        var elmCPTitle5 = $('<div></div>').addClass("scCartItemTitle scCartItemTitle5");
        elmCPTitle5.append(btRemove);
                    
        elmCPTitle1.append(elmCPContent);
        elmCP.append(elmCPTitle1).append(elmCPTitle3).append(elmCPTitle5);
        elmCartList.append(elmCP);
      }
                
      function removeFromCart(idx){
        var pObj = products.eq(idx);
        var pName = $(pObj).attr(attrProductName)
        var removeMsg = messageConfirmRemove.replace('_PRODUCTNAME_',pName); // display default
        if(confirm(removeMsg)){
          if($.isFunction(options.onRemove)) {
            // calling onRemove event; expecting a return value
            // will start remove if returned true and cancel remove if returned false
            if(!options.onRemove.call(this,$(pObj))){
              return false;
            }
          }
          var productItem = elmProductSelected.children("option[rel=" + idx + "]");
          var pValue = $(productItem).attr("value");
          var valueArray = pValue.split('|');
          var pQty = valueArray[1];
          productItem.remove();
          $("#divCartItem"+idx,elmCartList).slideUp("slow",function(){
            $(this).remove();
            showHighlightMessage(messageItemRemoved);
            //Refresh the cart
            refreshCartValues();
          });
          if($.isFunction(options.onRemoved)) {
            // calling onRemoved event; not expecting a return value
            options.onRemoved.call(this,$(pObj));
          }
        }
      }
                
      function updateCartQuantity(idx,qty){
        var pObj = products.eq(idx);
        var productItem = elmProductSelected.children("option[rel=" + idx + "]");
        var pPrice = $(pObj).attr(attrProductPrice);
        var pValue = $(productItem).attr("value");
        var valueArray = pValue.split('|');
        var prdId = valueArray[0];
        var curQty = valueArray[1];                    
        if($.isFunction(options.onUpdate)) {
          // calling onUpdate event; expecting a return value
          // will start Update if returned true and cancel Update if returned false
          if(!options.onUpdate.call(this,$(pObj),qty)){
            $('#lblQuantity'+idx).val(curQty);
            return false;
          }
        }


        var newPValue =  prdId + '|' + qty;
        $(productItem).attr("value",newPValue).attr('selected', true);    
        var prdTotal = getMoneyFormatted(pPrice * qty);
        // Now go for updating the design
        var lblTotal =  $('#lblTotal'+idx).html(prdTotal); 
        showHighlightMessage(messageQuantityUpdated);
        //Refresh the cart
        refreshCartValues();
        if($.isFunction(options.onUpdated)){
          // calling onUpdated event; not expecting a return value
          options.onUpdated.call(this,$(pObj),qty);
        }                    
      }
                
      function refreshCartValues(){
        var sTotal = 0;
        var cProductCount = 0;
        var cItemCount = 0;
        elmProductSelected.children("option").each(function(n) {
          var pIdx = $(this).attr("rel"); 
          var pObj = products.eq(pIdx);                     
          var pValue = $(this).attr("value");
          var valueArray = pValue.split('|');
          var prdId = valueArray[0];
          var pQty = valueArray[1];
          var pPrice =  $(pObj).attr(attrProductPrice);
          sTotal = sTotal + ((pPrice - 0) * (pQty - 0));
          cProductCount++;
          cItemCount = cItemCount + (pQty-0);
        });
        subTotal = sTotal;
        cartProductCount = cProductCount;
        cartItemCount = cItemCount;
        //elmBottomSubtotalValue.html(getMoneyFormatted(subTotal));
        cartMenu = labelCartMenuName.replace('_COUNT_',cartProductCount);  
        cartMenuTooltip = labelCartMenuNameTooltip.replace('_PRODUCTCOUNT_',cartProductCount).replace('_ITEMCOUNT_',cartItemCount);
        btShowCart.html(cartMenu).attr("title",cartMenuTooltip);
                    
        if(cProductCount <= 0){
          showMessage(messageCartEmpty,elmCartList);
        }else{
          $('.scMessageBar',elmCartList).remove();
        }
      }
                
      function populateCart(){
        elmProductSelected.children("option").each(function(n) {
          var curPValue =  $(this).attr("value");
          var valueArray = curPValue.split('|');
          var prdId = valueArray[0];
          var prdQty = valueArray[1];
          if(!prdQty){
            prdQty = 1; // if product quantity is not present default to 1
          }
          var objProd = jQuery.grep(products, function(n, i){
            return ($(n).attr(attrProductId) == prdId);
          });                        
          var prodIndex = products.index(objProd[0]);
          var prodName = $(objProd[0]).attr(attrProductName);
          $(this).attr('selected', true);
          $(this).attr('rel', prodIndex);
          $(this).html(prodName);
          cartItemCount++; 
          addCartItemDisplay(objProd[0],prdQty);                         
        });
        // Reresh the cart
        refreshCartValues();
      }
                
      function fillCategory(){
        var catCount = 0;
        var catItem = $('<option></option>').attr("value",'').attr('selected', true).html('All');
        elmCategory.prepend(catItem);                   
        $(products).each(function(i,n){
          var pCategory = $(this).attr(attrCategoryName);
          if(pCategory && pCategory.length>0){
            var objProd = jQuery.grep(elmCategory.children('option'), function(n, i){
              return ($(n).val() == pCategory);
            });
            if(objProd.length<=0){
              catCount++;
              var catItem = $('<option></option>').attr("value",pCategory).html(pCategory);
              elmCategory.append(catItem);
            }                        
          }
                            
        });
        if(catCount>0){
          $(elmCategory).bind("change", function(e){
            $(txtSearch).val('');
            populateProducts();
            return true;
          });                      
        }else{
          elmCategory.hide();
          lblCategory.hide();
        }
      }
                
                
      function populateProducts(searchString){
        var isSearch = false;
        var productCount = 0;
        //var selectedCategory = $(elmCategory).children(":selected").val();
        // validate and prepare search string
        if(searchString){
          searchString = trim(searchString);
          if(searchString.length>0){
            isSearch = true;
            searchString = searchString.toLowerCase();
          }                      
        }
        // Clear the current items on product list
        elmPLProducts.html('');
                
        //<thead> bauen
        var elmHead = $("<thead></thead>");
        var elmTr = $("<tr></tr>");
        var elmTh1 = $("<th id='head1'>ID</th>");
        var elmTh2 = $("<th id='head2'>Bild</th>");
        var elmTh3 = $("<th id='head3'>Produktdetails</th>");
        var elmTh4 = $("<th id='head4'></th>");
        
        elmTr.append(elmTh1);
        elmTr.append(elmTh2);
        elmTr.append(elmTh3);
        elmTr.append(elmTh4);
        elmHead.append(elmTr);
        elmPLProducts.append(elmHead);
        //
        //
        // Lets go for dispalying the products
        $(products).each(function(i,n){
          var product = $(this);
          var productName = $(this).attr(attrProductName);
          var pId = $(this).attr(attrProductId);
          var productCategory = $(this).attr(attrCategoryName);
          var isValid = true;
          var isCategoryValid = true;
          if(isSearch){
            if(productName.toLowerCase().indexOf(searchString) == -1 && (pId.toLowerCase()).indexOf(searchString) == -1) {
              isValid = false;
            }else{
              isValid = true;
            }
          }

          //Prüfen ob das Produkt im Attribut plist Einträge hat, wenn jaist es ein "Werkzeugkoffer"
          //und besitzt "Unterprodukte", die automatisch in den Warenkorb gelegt werden
          var prodHasPlist = false;
          var prodPlist = "";
          if($(this).attr("plist") != ""){
            prodHasPlist = true;
            prodPlist = getProductPlist(product);
          }
          // Category filter
          /*
          if(selectedCategory && selectedCategory.length>0){
            selectedCategory = selectedCategory.toLowerCase();
            if(productCategory.toLowerCase().indexOf(selectedCategory) == -1) {
              isCategoryValid = false;
            }else{
              isCategoryValid = true;
            }
          }
          */

          //if(isValid && isCategoryValid) {
          if(isValid) {
            productCount++; 
            var productPrice = $(this).attr(attrProductPrice); 
            var prodImgSrc = $(this).attr(attrProductImage);
            var prodImgSrcBig = $(this).attr(attrProductImageBig) || "#";
                 
            //Product ID
            var elmProdDiv1 = $('<td></td>').addClass("scPDiv1"); // for product name, desc & price
            elmProdDiv1.html(pId);
            
            //Image
            var elmProdDiv2 = $('<td></td>').addClass("scPDiv2"); //product image
            if(prodImgSrc && options.enableImage && prodImgSrc.length>0){
              
              var prodImgLink = $("<a></a>").attr("href", prodImgSrcBig);
              if(prodImgSrcBig != "#"){
                prodImgLink.attr("rel", "shadowbox");                      
              }else{
                prodImgLink.on("click", function(e){
                  e.preventDefault();
                });
              }

              var prodImg = $("<img></img>").attr("src",prodImgSrc).addClass("scProductImage").attr("rel", prodImgSrcBig);                      
              if(prodImg && options.enableImageTooltip){
                prodImg.bind("mouseenter mousemove",function(){
                  showTooltip($(this));                    
                  return false;
                });
                prodImg.bind("mouseleave",function (){
                  tooltip.hide();
                  return true;
                });
              }
              prodImgLink.append(prodImg);
              elmProdDiv2.append(prodImgLink);
            }
            
            //Details
            var elmProdDiv3 = $('<td></td>').addClass("scPDiv3"); // for product name, desc & price
            var productHtml = formatTemplate(options.productItemTemplate, $(this));
            elmProdDiv3.html(productHtml);                      
            
            if(prodHasPlist){
              var plistContainer = $("<div class='plistContainer'><strong>Fügt automatisch folgende Produkte hinzu:</strong><br /></div>");
              
              $(prodPlist).each(function(i,n){
                //log(this);
                plistContainer.append(this + " - " + getProductInputByPid(this.toString()).attr("pname") + "<br />");
              });
        

              elmProdDiv3.append(plistContainer);
            }

            //Add to cart
            var elmProdDiv4 = $('<td></td>').addClass("scPDiv4"); // for button & qty    
            var btAddToCart = $('<a>'+labelAddToCartButton+'</a>').attr("href","#").attr("rel",i).attr("title",labelAddToCartButton).addClass("scAddToCart");
            $(btAddToCart).bind("click", function(e){
              var idx = $(this).attr("rel");
              var qty = $(this).siblings("input").val();

              if(validateNumber(qty)){
                addToCart(idx,qty);
                addPlistProductsToCart(product);
              }else{
                $(this).siblings("input").val(1);                                                 
                showHighlightMessage(messageQuantityErrorAdd);
              }
              return false;
            });
            var inputQty = $('<input type="text" value="1" />').addClass("scTxtQuantity").addClass("quantityFor_" + replacedPid(pId));  
            var labelQty = $('<label>'+labelQuantityText+':</label>').addClass("scLabelQuantity");                    
            elmProdDiv4.append(labelQty).append(inputQty).append(btAddToCart);                  
    
            var elmProds = $('<tr></tr>').addClass("scProducts");
            if(prodHasPlist){
              elmProds.addClass("hasPlist");
            }
    
            elmProds.append(elmProdDiv1).append(elmProdDiv2).append(elmProdDiv3).append(elmProdDiv4);
            elmPLProducts.append(elmProds);
          }                                                        
        });
                   
        if(productCount <= 0){
          showMessage(messageProductEmpty,elmPLProducts);
        }
      }
            

      function getProductInputByPid(pid){
        var theInput = ($('input[pid="' + pid + '"]'));
        //log(theInput);
        return theInput;
      }

      /**
      /* Beim Hinzufügen eines Produktes wird sein Attribut plist ausgelesen
      /* in plist stehen alle "Subprodukte", welche automatisch mit zum Warenkorb
      /* hinzugefügt werden.
      /* Beispiel: plist="06.001.A001,06.001.A004,06.001.A022"
      /* @param product Das Html <hidden> Element des Produkts
      */
      function addPlistProductsToCart(product){

        prodPlist = getProductPlist(product);
        
        $(prodPlist).each(function(i,n){
          //log(this);

          //Index im DOM für dieses pList <hidden> Element finden,
          //Da das Hinzufügen in den Warenkob über den Index erfolgt
          listItem = $('input[pid="' + this + '"]');
          index = $('input').index(listItem) - 1;

          addToCart(index, 1); //index, quantity.

        });

      }

      function getProductPlist(product){
        var prodPlist = product.attr("plist");
        log(prodPlist);
        prodPlist = prodPlist.split(",");

        return prodPlist;
      }

      // Display message
      function showMessage(msg, elm){
        var elmMessage = $('<div></div>').addClass("scMessageBar").hide();
        elmMessage.html(msg);                  
        if(elm){
          elm.append(elmMessage);
          elmMessage.show();
        }
      }
                
      function showHighlightMessage(msg){
        msgBox2.html(msg);
        msgBox2.fadeIn("fast", function() {
          setTimeout(function() {
            msgBox2.fadeOut("fast");
          }, 2000); 
        }); 
      }

      // Show Image tooltip
      function showTooltip(img) {
        var height = img.height();
        var width = img.height();
        var imgOffsetTop = img.offset().top;
        jQuery.log(img.offset());                
        jQuery.log(img.position());
        jQuery.log("--------------");
        tooltip.html('');
        var tImage = $("<img></img>").attr('src',$(img).attr('src')); 
        tImage.height(toolMaxImageHeight);
        tooltip.append(tImage);
        var top = imgOffsetTop - height ;
        var left = width + 10;
        tooltip.css({
          'top':top, 
          'left':left
        });	
        tooltip.show("fast");                                              
      }
                
      function validateNumber(num){
        var ret = false;
        if(num){
          num = num - 0;
          if(num && num > 0){
            ret = true;
          }
        }
        return ret;
      }
                
      // Get the money formatted for display
      function getMoneyFormatted(val){
        return val.toFixed(2);
      }
      // Trims the blankspace
      function trim(s){
        var l=0;
        var r=s.length -1;
        while(l < s.length && s[l] == ' ')
        {
          l++;
        }
        while(r > l && s[r] == ' ')
        {
          r-=1;
        }
        return s.substring(l, r+1);
      }
      // format the product template
      function formatTemplate(str, objItem){
        resStr =str.split("<%=");
        var finalStr = '';
        for(i=0;i<resStr.length;i++){
          var tmpStr = resStr[i];
          valRef = tmpStr.substring(0, tmpStr.indexOf("%>")); 
          if(valRef!='' || valRef!=null){
            var valRep = objItem.attr(valRef); //n[valRef]; 
            if(valRep == null || valRep == 'undefined'){
              valRep = '';
            }
            tmpStr = tmpStr.replace(valRef+'%>',valRep);
            finalStr += tmpStr;
          }else{
            finalStr += tmpStr;
          }
        }
        return finalStr;
      }

    });  
  };  
 
  // Default options
  $.fn.smartCart.defaults = {
    selected: 0,  // 0 = produts list, 1 = cart   
    resultName: 'products_selected[]', 
    enableImage: true,
    enableImageTooltip: true,
    enableSearch: true,
    enableCategoryFilter: true,
    productItemTemplate:'<strong><%=pname%></strong><br />Category: <%=pcategory%><br /><small><%=pdesc%></small><br /><strong>Price: <%=pprice%></strong>',
    cartItemTemplate:'<strong><%=pname%></strong>',
    // Events
    onAdd: null,      // function(pObj,quantity){ return true; }
    onAdded: null,    // function(pObj,quantity){ }
    onRemove: null,   // function(pObj){return true;}
    onRemoved: null,  // function(pObj){ } 
    onUpdate: null,   // function(pObj,quantity){ return true; }
    onUpdated: null,  // function(pObj,quantity){ } 
    onCheckout: null  // function(Obj){ } 
  };
    
  jQuery.log = function(message) {
    if(window.console) {
      console.debug(message);
    }
  };
    
})(jQuery);
