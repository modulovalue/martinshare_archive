<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
require_once $_SERVER['DOCUMENT_ROOT']. '/include/header.php';

?>
<div class="stundenplantablediv">
</div>
	
<script>
window.onload = function() {

    $( ".stundenplantablediv" ).load( "images/stundenpläneraw/c00121.htm", function() {
        var meta = $(this).find("meta");
        $(meta).remove();
        
        var title = $(this).find( "title" );
        $(title).remove();
        
        var link = $(this).find( "link" );
        $(link).remove();
        
        var style = $(this).find( "style" );
        $(style).remove();
        
        var font = $(this).find( "font" )[0];
        $(font).next().remove();
        $(font).next().remove();
        $(font).next().remove();
        $(font).remove();
        
        $(this).find( "font" ).last().addClass("stundenplanuntertitle");
        var brwhat = $(this).find( "br" )[0];
        $(brwhat).remove();
        var item1 = $(this).find( "table" )[ 0 ];
        $(item1).addClass("stundenplantabelle");
        var nexttable = $(item1).next();
        nexttable.remove();
        nexttable = $(item1).next();
        nexttable.remove();
        var tablecount = 0;
        var tdcount = 0;
        
        
        var tbodytr = $( item1 ).find( "tbody" )[0];
        
        var firstchild = $(tbodytr).children(":first");
        
        firstchild.addClass("tage");
        
      
        
        var cellcounter = 0;
        $(firstchild).children().each(function() {
            $(this).addClass("tagecell");
            switch(cellcounter) {
                case 0:
                    $(this).addClass("tageheader0");
                    break;
                case 1:
                    $(this).addClass("tageheader1");
                    break;
                case 2:
                    $(this).addClass("tageheader2");
                    break;
                case 3:
                    $(this).addClass("tageheader3");
                    break;
                case 4:
                    $(this).addClass("tageheader4");
                    break;
                case 5:
                    $(this).addClass("tageheader5");
                    break;
            }
            cellcounter++;
            
           
        });
        
        var stundencounter = 0;
         var STUNDEZAHLCTR = 0;
        $(tbodytr).children().siblings().each(function() {
       
          if(tablecount%2 == 0) {
            $(this).addClass("even");
         
            
          } else {
            $(this).addClass("odd");
            
            var zeittbody = $(this).children(":first").find("tbody");
            $(zeittbody).addClass("zeitentbody");
            
            var zeittbodytr = $(zeittbody).children();
            var zeitenctr = 0;
            var tagcounter = 0;
            STUNDEZAHLCTR++;
                $(zeittbodytr).each(function() { 
                    $(this).addClass("zeittbodytr");
                    var zeitenctrinner = 0;
                    
                        if(zeitenctr == 0) {
                            var stdntdctr = 0;
                                $(this).children().each(function() {
                                
                                    $(this).addClass("stdntd");
                                    if(stdntdctr == 0) {
                                        $(this).addClass("stundenummer"+STUNDEZAHLCTR);
                                        $(this).addClass("stundenummer");
                                    } else if (stdntdctr == 1) {
                                        $(this).addClass("stundenummer"+STUNDEZAHLCTR+"beginn");
                                        $(this).addClass("stundenummerbeginn");
                                    }
                                    
                                    stdntdctr++;
                                });
                        }
                        if(zeitenctr == 1) {
                            var stdntdctr = 0;
                                $(this).children().each(function() {
                                    $(this).addClass("stdntd");
                                    if(stdntdctr == 0) {
                                        $(this).addClass("stundenummerhyphen"+STUNDEZAHLCTR);
                                        $(this).addClass("stundenummerhyphen");
                                    }
                                    
                                    stdntdctr++;
                                });
                        }
                        if(zeitenctr == 2) {
                            var stdntdctr = 0;
                                $(this).children().each(function() {
                                    $(this).addClass("stdntd");
                                    if(stdntdctr == 0) {
                                        $(this).addClass("stundenummerende"+STUNDEZAHLCTR);
                                        $(this).addClass("stundenummerende");
                                    }
                                    
                                    stdntdctr++;
                                });
                        }
                        
                        zeitenctr++;
                });
            
            var tdzahl = 0;
            var polystunde = false;
            var polygesetzt = false;
            
            $(this).children().siblings().each(function() {
            
                if(tdzahl == 0) {
                $(this).addClass("stundenummerzeitoutertd"); 
                }
                if(tdzahl == 1) {
                }
                if(tdzahl >= 2) {
                  
                    $(this).addClass("contentcells"); 
                    if($(this).attr('colspan') == 12 && polystunde) {
                        polygesetzt = false;
                        polystunde = false;
                    }
                    if($(this).attr('colspan') == 6) {
                        polystunde = true;
                    }
                    
                    $(this).children().each(function() {
                    
                        $(this).children().each(function() {
                        
                            $(this).addClass("tbodybeforecellcontent");
                            var token = 0;
                            $(this).children().each(function() {
                                $(this).addClass("tbodybeforecellcontent");
                            
                                if(token == 0) {
                                
                                    if(polystunde && !polygesetzt){
                                        
                                        polygesetzt = true;
                                        tagcounter++;
                                    }
                                    if(!polystunde) {
                                        tagcounter++;
                                    }
                                    $(this).addClass("fach");
                                    $(this).addClass("fach"+STUNDEZAHLCTR);
                                    $(this).addClass("fachday"+tagcounter);
                                    
                                        
                                } else if(token == 1) {
                                    $(this).remove();
                                    
                                } else if(token == 2) {
                                    if($(this).text().trim().length == 4) {
                                        $(this).addClass("raum");
                                    } else {
                                        $(this).remove();
                                    }
                                    
                                } else if(token == 3) {
                                    if($(this).text().trim().length == 4) {
                                        $(this).addClass("raum");
                                    } else {
                                        $(this).remove();
                                    }
                                    
                                } else if(token == 4) {
                                    if($(this).text().trim().length == 4) {
                                        $(this).addClass("raum");
                                    } else {
                                        $(this).remove();
                                    }
                                    
                                }
                                else if(token >= 5) {
                                    $(this).remove();
                                }
                                
                                token++;
                            });
                        });
                    
                    });
                  } 
                  
                  tdzahl++;
            });
          }
          tablecount++;
        });
    
        $(".stundenplantablediv").find("font").contents().unwrap();
        
        //var stundenplan = new Array();
        // var stundenplan = []
       /* 
        var tage = [];
        var tag = new Array();
        var j = 1;
        for(i = 1; i < 6; i++) {
        
                    $('.fachday'+i+'.fach'+j).each(function() {
                    tag.push();
                    j++;
                    console.log( $(this).text());
                    });  
                
                    tage[i] = tag[j];
              
        console.log(tage);
        }
        */
        
        //console.log(stundennummern);
    });
    
    
}

</script>