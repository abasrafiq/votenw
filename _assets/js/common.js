/* Detect and Fix IE6 png */
/* Dazu $.ifixpng('_assets/images/pixel.gif'); um den Bildpfad für transp. Pixel anzupassen */
function isIe6(){
    return ($.browser.msie && $.browser.version=="6.0");
}
function fixIe6Png(){
    if(isIe6()){
        $('.pngfix').ifixpng();
    }
}

window.log = function(){
    log.history = log.history || [];
    log.history.push(arguments);
    if(this.console){
        console.log( Array.prototype.slice.call(arguments) );
    }
};

function str_replace(search, replace, subject) {
  return subject.split(search).join(replace);
}

/**
/* Eine Funktion nur nach X-Sekunden des letzten ausführens ausführen. Gut z.B. für keyUp Events bei Sucheingabe
*/
function throttle(f, delay){
    var timer = null;
    return function(){
        var context = this, args = arguments;
        clearTimeout(timer);
        timer = window.setTimeout(function(){
            f.apply(context, args);
        },
        delay || 500);
    };
}


/**
/* Alle <tr> einer Tabelle ausblenden, die <inputVal> in keiner <td> enthalten
*/
function searchTable($table, inputVal)
{
    $table.find('tr').each(function(index, row)
    {
        var allCells = $(row).find('td');
        if(allCells.length > 0)
        {
            var found = false;
            allCells.each(function(index, td)
            {
                var regExp = new RegExp(inputVal, 'i');
                if(regExp.test($(td).text()))
                {
                    found = true;
                    return false;
                }
            });
            if(found == true)$(row).show();else $(row).hide();
        }
    });
}