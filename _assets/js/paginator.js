/*
 * Jquery 'Paginator' plug-in
 * Author: Brandon Lee Jennings
 * www.DemiGodDesign.com
 *
 *
 * Paginator instantly paginates a table client-side with minimal effort.
 * Simply call paginator on your table of choice, and it's paginated!
 *
 * I, Brandon Lee Jennings, do hereby grant full permission to use this software free of charge.
 * However, please do keep my signature to give me credit when using.
 *
 * NOTE:
 * 
 * - To use this you must also include JqueryUI w/the the accompanied styles.
 * - I guess it goes without saying that you need to include Jquery as well.
 * - This only works with id's, so don't try to call a table by class name
 *
 * Example:
 * Say you got a table with an id of "paginateThis", you want 30 rows per page,
 * you'd like a nice medium fadeIn/Out effect:
 *
 *
 * $("#paginateThis").paginate(30, 300);
 *
 * That's it!
 * 
 */


(function( $ ){

  $.fn.paginate = function(numberofrowstodisplayperpage, animationspeed) {

    var tableid = this.attr("id");
    new TablePaginator(tableid, numberofrowstodisplayperpage, animationspeed)

  };
})( jQuery );



//Here's the initial JS Object I wrote for a project. Bascially, this plugin is
//an object of this instanitiated in a jquery call.
function TablePaginator(tableid, numberofrowstodisplayperpage, animationspeed)
{


    this.pagenum = 1;
    this.sizeoftable = $("#"+ tableid + " tr").length;
    this.count = 1;
    var self = this;



    /*Displays a range of rows, called via a pagenum onclick event*/
    this.resetRows = function(start, end)
    { 
       //Were reseting the pagenum navaigator here to indicate which page
       $("#"+ tableid + " tr, #"+tableid+"pagenumholder").not("tr:first").hide();
       this.displayRows(start, end);
    }

    /*Loops through an entire page of rows, displaying them with a delay for the effect*/
    this.displayRows = function(start, end)
    {
      if(start == end || start == this.sizeoftable)
          {
              start = end;
              $("#"+tableid+"pagenumholder").show();
          }



      if(start <= end)
        {
            $("#" + tableid + start).fadeIn(animationspeed);
            start++;
            self.displayRows(start,end);
        }
    }


    this.createPagesandPageholder = function()
    {
        var beginning;
        var ending;
        var current;


        if(this.sizeoftable <= numberofrowstodisplayperpage)
        {
            beginning = 1;
            end = this.sizeoftable;
        }

        else
        {

        $("#"+tableid).after('<div style="margin: 3px; padding: 3px; display: inline-block;" id="'+tableid+'pagenumholder" class="ui-widget-header ui-corner-all"><button id="'+tableid+'beginning"><<</button><button id="'+tableid+'rewind"><</button><input style="width:35px; display: inline-block; margin:3px;" id="'+tableid+'currentPage" value="1"/><div style="display: inline-block; margin: 3px; width: inherit;" id="'+tableid+'numOfPage"></div><button id="'+tableid+'forward">></button><button id="'+tableid+'end">>></button></div>');
        var numberofpages = Math.ceil(this.sizeoftable/numberofrowstodisplayperpage);

        $("#"+tableid+"numOfPage").text(numberofpages + " Gesamt ");
        $("#"+tableid+"pagenumholder").hide();

        $( "#"+tableid+"beginning" ).button({
			text: false,
			icons: {
				primary: "ui-icon-seek-start"
			}
		}).click(function(){

                    $("#"+tableid+"currentPage").val(1);
                    current = 1;
                    ending = numberofrowstodisplayperpage * current;
                    beginning = (ending - numberofrowstodisplayperpage) + 1;
                    self.resetRows(beginning, ending);

                });

		$( "#"+tableid+"rewind" ).button({
			text: false,
			icons: {
				primary: "ui-icon-seek-prev"
			}
		}).click(function(){

                   current = $("#"+tableid+"currentPage").val();
                   if(current != 1 && current >= 1 && current <= numberofpages)
                   {
                    current--;
                    $("#"+tableid+"currentPage").val(current);
                    ending = numberofrowstodisplayperpage * current;
                    beginning = (ending - numberofrowstodisplayperpage) + 1;
                    self.resetRows(beginning, ending);
                   }

                });


                $( "#"+tableid+"forward" ).button({
			text: false,
			icons: {
				primary: "ui-icon-seek-next"
			}
		}).click(function(){

                   current = $("#"+tableid+"currentPage").val();
                   if(current != numberofpages && current >= 1 && current <= numberofpages)
                   {
				
                    current++;
                    $("#"+tableid+"currentPage").val(current);
                    ending = numberofrowstodisplayperpage * current;
                    beginning = (ending - numberofrowstodisplayperpage) + 1;
                    self.resetRows(beginning, ending);
                   }


                });



		$( "#"+tableid+"end" ).button({
			text: false,
			icons: {
				primary: "ui-icon-seek-end"
			}
		}).click(function(){

                    $("#"+tableid+"currentPage").val(numberofpages);
                    current = numberofpages;
                    ending = numberofrowstodisplayperpage * current;
                    beginning = (ending - numberofrowstodisplayperpage) + 1;
                    self.resetRows(beginning, ending);

                });

                $("#"+tableid+"currentPage").change(function(){
                   current = $("#"+tableid+"currentPage").val();
                   if(current >= 1 && current <= numberofpages)
                       {
                            ending = numberofrowstodisplayperpage * current;
                            beginning = (ending - numberofrowstodisplayperpage) + 1;
                            self.resetRows(beginning, ending);
                       }
                })




        }
    }

    /*Constructor*/
    this.construct = function()
    {
        var counter = 0;
        $("#"+ tableid + " tr").not("tr:first").hide();
        $("#" + tableid + " tr").each(function(){$(this).attr("id", tableid+counter); counter++; });
        this.createPagesandPageholder();
        this.displayRows(1, numberofrowstodisplayperpage);

    }

    /*I wish Javascript had more straightforward constructors.*/
    this.construct();


}