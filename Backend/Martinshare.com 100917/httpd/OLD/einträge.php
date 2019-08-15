<?php
$pageTitle = 'Einträge';
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
include Config::get('includes/header');
?>

<script src="swiper/idangerous.swiper.js"></script>
<script src="swiper/idangerous.swiper.scrollbar.js"></script>
  
<link rel="stylesheet" href="swiper/idangerous.swiper.css">

<script type="text/javascript">
window.onload = function() {

    var pages = new Swiper('.swiper-pages',{
    pagination: '.pagination',
    grabCursor: true,
    paginationClickable: true,
    speed:200, 
    eventTarget: 'container',
    freeMode: false,
    })
    
        $('.scroll-container').each(function(){
            $(this).swiper({
            
    eventTarget: 'page-inner',
                mode:'vertical',
                scrollContainer: true,
                mousewheelControl: true,
                freeMode: false,
                
            })
        })
    
        $('.arrow-left').on('click', function(e){
        e.preventDefault()
        pages.swipePrev()
        })
        $('.arrow-right').on('click', function(e){
        e.preventDefault()
        pages.swipeNext()
        })
    
}

</script>

</head>
<?php
echo '<body class="bodyein" id="Eintraege">';
include Config::get('includes/navbar');
?>

<div class="swiper-pages">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <div class="swiper-container scroll-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="page-inner">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-12 text-center">
                                        <h1>Hausaufgaben</h1>
                                            <?php
                                            $_typ = 'h';
                                            include 'include/tablesoop.php';
                                            ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="swiper-slide">
            <div class="swiper-container scroll-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="page-inner">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-12 text-center">
                                        <h1>Arbeitstermine</h1>
                                            <?php
                                            $_typ = 'a';
                                            include 'include/tablesoop.php';
                                            ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="swiper-slide">
            <div class="swiper-container scroll-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="page-inner">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-12 text-center">
                                        <h1>Sonstiges</h1>
                                            <?php
                                            $_typ = 's';
                                            include 'include/tablesoop.php';
                                            ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<a class="arrow-left" href="#"></a>
<a class="arrow-right" href="#"></a>
<div class="pagination"></div>
    <?php include Config::get('includes/footer'); ?>
</body>
</html>