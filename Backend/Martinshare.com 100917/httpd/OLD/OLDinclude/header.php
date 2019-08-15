<!DOCTYPE html>
<?php 
if($pageTitle == 'Einträge'){
    echo '<html class="htmlein" lang="de">';
} else {
    echo '<html lang="de">';
}
?>
<head>
<link rel="shortcut icon" href="favicons/favicon.ico">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale = 1, maximum-scale = 1.4, user-scalable = yes">
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <title><?php echo $pageTitle?></title>
    
    <script src="//code.jquery.com/jquery-1.11.1.min.js" ></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">

    
    <style>


    iframe { 
        -webkit-overflow-scrolling: touch;
        overflow: auto;
  	    overflow-y: scroll;
        height: 500px;
    }
    
    
    section.module.parallax {
    height: auto;
    background-position: 50% 50%;
    background-repeat: no-repeat;
    background-attachment: fixed;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
        
    }
    
    section.module.parallax h3 {
    padding-top: 7px;
    padding-bottom: 7px;
    }
    
    img {
        max-width:100%;
        height: auto;
    }

    @media \0screen{
        img {
            width: auto;
        }
    }
    
    .spinner {
    }
    
    html {
	position: relative;
    overflow-y: scroll;
    min-width:100%;
    min-height:100%;
    width: 100%;
    }
    
    #firstPage {

    }
    
    .col-lg-12  {
    background: none repeat scroll 0% 0% rgba(0, 0, 0, 0.45);
    padding-bottom: 30px;
    padding-top: 10px;
  /*-webkit-box-shadow: 0 0 10px -2px #000;
    box-shadow: 0px 0px 10px -2px #000; */
    margin-top: 10px;
    -moz-border-radius: 6px;
    border-radius: 6px;
        
    }
    
    .blackwindow  {
    background: none repeat scroll 0% 0% rgba(0, 0, 0, 0.45);
    padding-bottom: 30px;
    padding-top: 10px;
  /*-webkit-box-shadow: 0 0 10px -2px #000;
    box-shadow: 0px 0px 10px -2px #000; */
    margin-top: 10px;
    -moz-border-radius: 6px;
    border-radius: 6px;
        
    }

	body {
	
	    padding-top: 50px;
	    padding-bottom: 30px;
	    background: #505050;
	    background: url('images/bg/1.jpg') repeat scroll ;
        background-position: center ;
        background-size: cover;
	    
	}
	
	
	button {
	    background: transparent;
	    border: 0px;
	    color:#FFFFFF;
	    float: left;
	    text-align: left;
	    padding: 0px 10px 0px 0px;
        word-wrap: break-word;
	}
	
	.fachbtn {
	    position: relative;
	    width: 100%;
	}
	
	button:hover {
	    background:transparent;
	    border:0px;
	    color:#61A0FF;
	    /* old color #FFD700 */
	    text-decoration: underline;
	}
	.container {
	    width: 800px;
	    max-width: 97%;
	}
    .inverse-dropdown{
      background-color: #222;
      border-color: #080808;
      color: #FFFFFF;
    }
    .navbar-inverse {
      background-color: rgba(34, 34, 34, 0.9);
    }
    
    #footer1 {
      background-color: rgba(34, 34, 34, 0.9);
    }
    
    #footer1 .p {
      color: #fff;
    }
    
    #Vertretungsplan #footer1{
      background-color: rgba(34, 34, 34, 0.49);
      opacity: 0.49;
      filter: alpha(opacity=49); /* For IE8 and earlier */
    }

	p{color:#FFFFFF}
	p.lead{color:#FFFFFF}
	h1, h2, h3, h4{color:#FFFFFF}
	tr{color:#FFFFFF}

    /*android footer button*/
    .btn-custom { 
    background-color: hsl(0, 0%, 17%) !important;
    background-repeat: repeat-x; 
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#4f4f4f", endColorstr="#2b2b2b"); 
    background-image: -khtml-gradient(linear, left top, left bottom, from(#4f4f4f), to(#2b2b2b)); 
    background-image: -moz-linear-gradient(top, #4f4f4f, #2b2b2b); 
    background-image: -ms-linear-gradient(top, #4f4f4f, #2b2b2b); 
    background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #4f4f4f), color-stop(100%, #2b2b2b)); 
    background-image: -webkit-linear-gradient(top, #4f4f4f, #2b2b2b); 
    background-image: -o-linear-gradient(top, #4f4f4f, #2b2b2b); 
    background-image: linear-gradient(#4f4f4f, #2b2b2b); 
    border-color: #2b2b2b #2b2b2b hsl(0, 0%, 13.5%); 
    color: #fff !important; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.23); 
    -webkit-font-smoothing: antialiased; 
    }
	a:link, a:visited {
	color:#61A0FF;
	    
	}
	
	a:hover, a:active {
	
	color: #FFF;
    -webkit-transition: color 200ms ease-in-out;
       -moz-transition: color 200ms ease-in-out;
        -ms-transition: color 200ms ease-in-out;
         -o-transition: color 200ms ease-in-out;
            transition: color 200ms ease-in-out;
	}
	

	.dropdown-menu > li > a {color: #888888;}
    .badge-morgen {background-color: #FF8800;}
    .badge-uebermorgen {background-color: #CCCC00;}
        
	.dropdown-menu > li > a:hover {
	background-color: #FFF;
    -webkit-transition: background-color 250ms ease-in-out;
       -moz-transition: background-color 250ms ease-in-out;
        -ms-transition: background-color 250ms ease-in-out;
         -o-transition: background-color 250ms ease-in-out;
            transition: background-color 250ms ease-in-out;
	}
	

    
	#footer1  {
        position:fixed;
        bottom:0px;
        height:20px;
        width:100%;
        /*background: #171717;
        background-color: rgba(34, 34, 34);*/
        text-align: center;
        
	}
	#androidfooter {
        position:fixed;
        bottom:0px;
        height:40px;
        width:100%;
        background-color:#171717;
        background: #171717; /* for non-css3 browsers */

        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#282828', endColorstr='#171717'); /* for IE */
        background: -webkit-gradient(linear, top, bottom, from(#282828), to(#171717)); /* for webkit browsers */
        background: -moz-linear-gradient(top,  #282828,  #171717); /* for firefox 3.6+ */
        background-image: linear-gradient(to bottom , #282828, #171717);
        text-align: center;
	}

    .autocomplete-suggestions { border: 1px solid #999; background: #FFF; cursor: default; overflow: auto; -webkit-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); -moz-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); }
    .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
    .autocomplete-no-suggestion { padding: 2px 5px;}
    .autocomplete-selected { background: #F0F0F0; }
    .autocomplete-suggestions strong { font-weight: bold; color: #000; }
    .autocomplete-group { padding: 2px 5px; }
    .autocomplete-group strong { font-weight: bold; font-size: 16px; color: #000; display: block; border-bottom: 1px solid #000; }

    #Arbeitstermine .Arbeitstermine,
    #Hausaufgaben .Hausaufgaben,
    #Martinshare  .Martinshare,
    #Stundenplan .Stundenplan,
    #Stundenplanupload .Stundenplanupload,
    #Vertretungsplan .Vertretungsplan,
    #Upload .Upload,
    #Sonstiges .Sonstiges,
    #Einreichen .Einreichen,
    #Eintraege .Eintraege,
    #Archiv .Archiv,
    #Einstellungen .Einstellungen,
    #Profil .Profil { 
        color: #FFF;
  
    }
    
    @media (min-width: 768px) {
        #Arbeitstermine .Eintraegedrop,
        #Hausaufgaben .Eintraegedrop,
        #Stundenplan .Stundenplan,
        #Stundenplanupload .Stundenplanupload,
        #Vertretungsplan .Vertretungsplan,
        #Upload .Upload,
        #Sonstiges .Eintraegedrop,
        #Einreichen .Einreichen,
        #Eintraege .Eintraegedrop,
        #Archiv .Eintraegedrop,
        #Einstellungen .Einstellungen,
        #Profil .Profil { 
        
          
            
            /* background: -moz-linear-gradient(top, rgba(0,0,0,0) 0%, rgba(217,161,0,0) 85%, rgba(255,189,0,0.35) 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(0,0,0,0)), color-stop(85%,rgba(217,161,0,0)), color-stop(100%,rgba(255,189,0,0.35)));
            background: -webkit-linear-gradient(top, rgba(0,0,0,0) 0%,rgba(217,161,0,0) 85%,rgba(255,189,0,0.35) 100%);
            background: -o-linear-gradient(top, rgba(0,0,0,0) 0%,rgba(217,161,0,0) 85%,rgba(255,189,0,0.35) 100%);
            background: -ms-linear-gradient(top, rgba(0,0,0,0) 0%,rgba(217,161,0,0) 85%,rgba(255,189,0,0.35) 100%);
            background: linear-gradient(to bottom, rgba(0,0,0,0) 0%,rgba(217,161,0,0) 85%,rgba(255,189,0,0.35) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00000000', endColorstr='#59ffbd00',GradientType=0 ); */
    }


        #Arbeitstermine .Eintraegedrop::after,
        #Hausaufgaben .Eintraegedrop::after,
        #Stundenplan .Stundenplan::after,
        #Stundenplanupload .Stundenplanupload::after,
        #Vertretungsplan .Vertretungsplan::after,
        #Upload .Upload::after,
        #Sonstiges .Eintraegedrop::after,
        #Einreichen .Einreichen::after,
        #Eintraege .Eintraegedrop::after,
        #Archiv .Eintraegedrop::after,
        #Einstellungen .Einstellungen::after,
        #Profil .Profil::after { 
                        
            position: absolute;
        	top: 100%;
        	left: 0;
        	width: 100%;
        	height: 1px;
        	background: #3366cc;
        	content: '';
        	opacity: 0;
        	-webkit-transition: height 0.3s, opacity 0.3s, -webkit-transform 0.3s;
        	-moz-transition: height 0.3s, opacity 0.3s, -moz-transform 0.3s;
        	transition: height 0.3s, opacity 0.3s, transform 0.3s;
        		height: 3px;
        	opacity: 1;
        	-webkit-transform: translateY(0px);
        	-moz-transform: translateY(0px);
        	transform: translateY(0px);
        }
        
        .Stundenplan::after ,
        .Stundenplanupload::after ,
        .Vertretungsplan::after ,
        .Upload::after ,
        .Eintraegedrop::after,
        .Einreichen::after ,
        .Einstellungen::after ,
        .Profil::after {
        
        	position: absolute;
        	top: 100%;
        	left: 0;
        	width: 100%;
        	height: 1px;
        	background: #fff;
        	content: '';
        	opacity: 0;
        	-webkit-transition: height 0.3s, opacity 0.3s, -webkit-transform 0.3s;
        	-moz-transition: height 0.3s, opacity 0.3s, -moz-transform 0.3s;
        	transition: height 0.3s, opacity 0.3s, transform 0.3s;
        	-webkit-transform: translateY(-10px);
        	-moz-transform: translateY(1-0px);
        	transform: translateY(-10px);
        }
                  .Stundenplan:hover::after ,
              .Vertretungsplan:hover::after ,
                       .Upload:hover::after ,
                   .Einreichen:hover::after ,
                .Einstellungen:hover::after ,
                       .Profil:hover::after ,
                .Eintraegedrop:hover::after ,
                  .Stundenplan:focus::after ,
                  .Stundenplanupload:focus::after ,
                       .Upload:focus::after ,
                   .Einreichen:focus::after ,
                .Einstellungen:focus::after ,
                       .Profil:focus::after ,
                .Eintraegedrop:focus::after {
                
                	height: 3px;
                	opacity: 1;
                	-webkit-transform: translateY(0px);
                	-moz-transform: translateY(0px);
                	transform: translateY(0px);
                }
    }


    .navbar-brand {
    margin-left: 0px !important ;
    
    }


    .data-table {
    width: 100%;
    max-width: 800px;
    font-size: 14px;
    table-layout: fixed;
    word-wrap: break-word;
    margin: 0 auto;
    }
    
    td.fach {
        padding: 0px 2px 0px 0px;
        word-wrap: break-word;
    }
    
    td.beschreibung {
        padding: 0px 2px 0px 3px;
        word-wrap: break-word;
    }
    
    td.datum {
        padding: 0px 0px 0px 3px;
        word-wrap: break-word;
    }
    
    th.beschreibung{
        text-align: center;
    }
    
    th.datum {
        text-align: right;
    }
    
    .data-table tr {
        border-bottom: 2px solid #989898;
    }

    tr td:last-child {
    width:1%;
    white-space:nowrap;
    }
    
    body {
    overflow: hidden;
    width: 100%;
    }
    
    .bodyein, .htmlein {
	position: relative;
	height: 100%;
    }
    
    .swiper-pages {
    min-height: 100%
    }
    .swiper-wrapper {
    height: 100%;
    width: 100%;
    }
    .scroll-container {
    height: 100%;
    width: 100%;
    }
    .scroll-container .swiper-slide {
    width: 100%;
    }
    
    .arrow-left {
    background: url(images/arrows.png) no-repeat left top;
    position: absolute;
    left: 10px;
    top: 50%;
    margin-top: -15px;
    width: 17px;
    height: 30px;
    z-index:3;
    }
    
    .arrow-right {
    background: url(images/arrows.png) no-repeat left bottom;
    position: absolute;
    right: 10px;
    top: 50%;
    margin-top: -15px;
    width: 17px;
    height: 30px;
    z-index:3;
    }
    
    .pagination {
    position: relative;
    left: 0;
    text-align: center;
    bottom:50px;
    width: 100%;
    }
    .swiper-pagination-switch {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 10px;
    background: #999;
    box-shadow: 0px 1px 2px #555 inset;
    margin: 0 3px;
    cursor: pointer;
    }
    .swiper-active-switch {
    background: #fff;
    }
    
    .zoom {
		display:inline-block;
		position: relative;
	}
	
	/* magnifying glass icon */
	.zoom:after {
		content:'';
		display:block; 
		width:33px; 
		height:33px; 
		position:absolute; 
		top:0;
		right:0;
		background:url(include/zoom-master/icon.png);
	}

	.zoom img {
	display: block;
	}

	.zoom img::selection { 
	background-color: transparent; 
	}
    
    .martinchatcon {
    position:relative;
    width:300px;
    /* margin-left:-150px;
    left:50%;*/
    position: fixed;
    bottom: auto;
    right: 2px;
    height:auto;
    bottom:40px;
    z-index: 98;
    }

    .martinchat {
    display: inline-block;
    vertical-align: bottom;
    position: relative;
    height:220px;
    width: 300px;
    background: #cfcfcf;
    border-radius: 0px 0px 0px 0px;
    z-index:99;
    overflow:auto;
    text-align: center;
    }
    
    .martinchatclosed {
    outline: 0;
    display: inline-block;
    position: relative;
    vertical-align: top;
    text-align:center;
    width: 300px;
    height:30px;
    color:#000000;
    font-size: 19px;
    background: #353535;
     
    border-radius: 10px 10px 0px 0px;
    z-index:100;
     filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#282828', endColorstr='#171717'); /* for IE */
        background: -webkit-gradient(linear, top, bottom, from(#353535), to(#202020)); /* for webkit browsers */
        background: -moz-linear-gradient(top,  #353535,  #202020); /* for firefox 3.6+ */
        background-image: linear-gradient(to bottom , #353535, #202020);
    }
    
    .martinchatclosed:hover {
     filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#282828', endColorstr='#171717'); /* for IE */
        background: -webkit-gradient(linear, top, bottom, from(#202020), to(#353535)); /* for webkit browsers */
        background: -moz-linear-gradient(top,  #202020,  #353535); /* for firefox 3.6+ */
        background-image: linear-gradient(to bottom , #202020, #353535);
    }

    .martinchatclosed > a {
    margin: 0px 5px;
    height: 30px;
    line-height: 30px;
    text-decoration: none;
    color: #aaa;
    outline: 0;
    font-size: 14px;
    }
    
    .martinchatclosed > a:hover, .martinchatclosed > a:hover, .martinchatclosed > a:active{
    color: rgba(255, 255, 255, 1);
    }
    .martinchat .messageleft {
        background: #FFFFFF;
        border-radius: 0px 5px 8px 0px;
        margin: 10px 30px 5px 2px;
        padding: 2px 2px 0px 4px;
        text-align: left;
        word-wrap: break-word;
        max-width: 280px;
    }
    .martinchat .messageright {
        background: #FFFFFF;
        border-radius: 5px 0px 0px 8px;
        margin: 10px 2px 5px 30px;
        padding: 4px 4px 0px 2px;
        text-align: left;
        word-wrap: break-word;
        max-width: 280px;

    }
     .martinchat .nachricht1 {
        color: #000000;
        font-size: 90%;
        margin: 0px 0px 0px 0px;
        padding: 0px 0px 0px 0px;
    }
     .martinchat .notchat {
        color: #777777;
        font-size: 75%;
        word-wrap: break-word;
    }
    .martinchat .name {
        color: #f26533;
        font-size: 70%;
    }
    .martinchat .datum {
        color: #ABABAB;
        font-size: 58%;
        margin: 10px 2px 5px 2px;
        padding: 4px 4px 0px 0px;
        position:relative;
        right: 2px;
    }
    .martinchat .wichtig {
        background: #FFDBDB;
    }
    .martinchat .nichtsowichtig {
        background: #FFFF9C;
    }
    .martinchat .systemnachricht {
        background: #FFFFFF;
        border-radius: 5px 5px 5px 5px;
        margin: 10px 15px 10px 15px;
        padding: 4px 4px 4px 4px;
        text-align: center;
        word-wrap: break-word;
        max-width: 270px;
    }
    
    /* Stundenplan */
    .stundenplantablediv {
    position: relative;
    margin: 0 auto;
    width: 800px;
	max-width: 97%;
	background: #FFFFFF;
    }
    
    .stundenplantablediv td, .stundenplanuntertitle, font[color="#000000"] {
    text-align: center;
    border-width: 2px;
    color: #000000;
    }
    
    .contentcells{
    width: auto;  
    min-width: 10%;
    font-size: 12pt;
    }
    
    .stundenummerzeitoutertd, .stundenummerzeitoutertd td {
    width: 5%;
    font-size: 8pt;
    height: 20px;
    }
    
    .tagecell {
        width: auto;
        color: #FFFFFF;
        font-size: 8pt;
    }
    
    .stundenplantablediv .raum {
        font-size: 8pt;
    }
    
    .mvtimetable {
        table-layout: fixed;
        position: relative;
        width: auto;
        max-width: 800px;
        margin: 0 auto;
        background: #666;
        font-size: 9pt;
    }
    
    .mvtimetable th,.mvtimetable td {
    width: 1%;
    }
    
    .mvttheader {
    text-align: center;
    background: #444;
    }
    
    .mvttdayhours {
         background: #555;
         font-size: 11pt;
    }
    
    .mvttlessoncell {
    margin: 0;
    padding: 0;
    }
    
    .mvtimetablecontainer {
    
    }
    
    .mvttimg {
        position: relative;
        margin: 0 auto;
        width: 40px; 
        height: 40px;
    }
    
    .mvttimgmini {
        display: inline-block;
        bottom:0px;
        bottom: 0px;
        width: 20px; 
        height: 20px;
    }
   
    .mvttday<?php switch(date('N')) {
        case 1:
        echo '1';
            break;
        case 2:
        echo '2';
            break;
        case 3:
        echo '3';
            break;
        case 4:
        echo '4';
            break;
        case 5:
        echo '5';
            break;
    } ?>
     {
        background: #405485;
    }
    
    .mvttlessoncell {
        word-wrap: break-word;
    }
    
    .logoms {
    
    
    }
    
 
    </style>
    
   
	
    <script src="js/bootstrap.min.js" ></script>
	<script src='include/zoom-master/jquery.zoom.js'></script>
	<script type="text/javascript">
    
		function toggle(control){
	    var elem = document.getElementById(control);
    	    if(elem.style.display == "none"){
    	        elem.style.display = "block";
    	    }else{
    	        elem.style.display = "none";
    	    }
		}
	</script>
	<script>
		$(document).ready(function(){
			$('#ex1').zoom();
			$('#ex2').zoom({ on:'grab' });
			$('#ex3').zoom({ on:'click' });			 
			$('#ex4').zoom({ on:'toggle' });
		});
	</script>
	