<?php require_once '../include/core.inc.php'; ?>
<?php $markasactive = 1; ?>
<?php 

$user = new User(); 
if($user->hasPermission("admin")) { 
?>

<!DOCTYPE html>
<html lang="de">
<head>
    
    <?php include'include/headinclude.php'; ?>
    
    <meta charset="utf-8">    
    <meta name="viewport" id="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=10.0, minimum-scale=0.5, user-scalable=yes" />
    <title>Martinshare - Admin Übersicht</title>

    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="css/local.css" />

    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>


    <link href="include/toggle/toggle.css" rel="stylesheet">
    <script src="include/toggle/toggle.js"></script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    
    <script src="../../js/xdate.js"></script>
    <script src="../../js/cookie.js"></script>
    <script src="include/theme1.css"></script>
    
    <?php
    
    function getButton($stringval, $dataval, $class) {
        echo '
        <button data-month="'.$dataval.'" type="button" class="btn btn-sm btn-default '.$class.'" aria-label="Left Align">'.$stringval.'</button>
        ';
    }
    
    function echoBtnGroup($id) {
        echo"<div class='btn-group'>";
        echo'
                '.getButton("96w",96,$id).'
                '.getButton("48w",48,$id).'
                '.getButton("24w",24,$id).'
                '.getButton("12w",12,$id).'
                '.getButton("4w",4,$id).'
                '.getButton("1w",1,$id).'
        ';
        echo"</div>";
    }
    
    ?>
    
    <script >
        
        
        var eintraegenutzermonthbtnval = 96;
        var eintraegeperdaymonthbtnval = 96;
        var mobilesessionmonthbtnval   = 96;
        var homepagesessionmonthbtnval = 96;
        
    $(function () {
        
        
        function loadcookies() {
            if (!Cookies.get("eintraegenutzermonthbtnval")) {
              Cookies.set("eintraegenutzermonthbtnval", eintraegenutzermonthbtnval, { expires: 365 }); 
            } else {
              eintraegenutzermonthbtnval = Cookies.get("eintraegenutzermonthbtnval");
            }
            
            if (!Cookies.get('eintraegeperdaymonthbtnval')) {
              Cookies.set("eintraegeperdaymonthbtnval", eintraegeperdaymonthbtnval, { expires: 365 }); 
            } else {
              eintraegeperdaymonthbtnval = Cookies.get("eintraegeperdaymonthbtnval");
            }
            
            if (!Cookies.get('mobilesessionmonthbtnval')) {
              Cookies.set("mobilesessionmonthbtnval", mobilesessionmonthbtnval, { expires: 365 }); 
            } else {
              mobilesessionmonthbtnval = Cookies.get("mobilesessionmonthbtnval");
            }
            
            if (!Cookies.get('homepagesessionmonthbtnval')) {
              Cookies.set("homepagesessionmonthbtnval", homepagesessionmonthbtnval, { expires: 365 }); 
            } else {
              homepagesessionmonthbtnval = Cookies.get("homepagesessionmonthbtnval");
            }
            
            $("#eintraegenutzermonthbtn").find("[data-month='" + eintraegenutzermonthbtnval + "']").click();
            $("#eintraegeperdaymonthbtn").find("[data-month='" + eintraegeperdaymonthbtnval + "']").click();
            $("#mobilesessionmonthbtn").find("[data-month='"   + mobilesessionmonthbtnval + "']").click();
            $("#homepagesessionmonthbtn").find("[data-month='" + homepagesessionmonthbtnval + "']").click();
        }
        
        function setBtnClick() {
            $('.eintraegenutzermonthbtn').button().click(function() { 
                Cookies.set("eintraegenutzermonthbtnval", $(this).data("month"), { expires: 365 }); 
                loadcookies();
                countperuser();
            });
            $('.eintraegeperdaymonthbtn').button().click(function() { 
                Cookies.set("eintraegeperdaymonthbtnval", $(this).data("month"), { expires: 365 }); 
                loadcookies();
                reloadEPW()
            });
            $('.mobilesessionmonthbtn').button().click(function() { 
                Cookies.set("mobilesessionmonthbtnval", $(this).data("month"), { expires: 365 }); 
                loadcookies();
                reloadBMS();
            });
            $('.homepagesessionmonthbtn').button().click(function() { 
                Cookies.set("homepagesessionmonthbtnval", $(this).data("month"), { expires: 365 }); 
                loadcookies();
                reloadHomepageStat();
            });
        }
        
        
        setBtnClick();
        loadcookies();
        
        
        
        $('#toggle-four').bootstrapToggle();
        $('#toggle-four').prop('checked', false).change();
        countperuser();
        
        $('#toggle-one').bootstrapToggle();
        $('#toggle-one').prop('checked', false).change();
        reloadEPW();
        
        $('#toggle-two').bootstrapToggle();
        $('#toggle-two').prop('checked', false).change();
        reloadBMS();
        
        $('#toggle-three').bootstrapToggle();
        $('#toggle-three').prop('checked', false).change();
        reloadHomepageStat();
        
        
        $('#toggle-four').change(function() {
            countperuser()
        })
        
        $('#toggle-one').change(function() {
            reloadEPW();
        })
        
        $('#toggle-two').change(function() {
            reloadBMS();
        })
        
        $('#toggle-three').change(function() {
            reloadHomepageStat();
        })
        
        function countperuser() {
            
            var usertype = "";
            if($('#toggle-four').prop('checked')) {
                usertype = "manager";
            } else {
                usertype = "user";
            }
            
            $.post("https://www.martinshare.com/api/adminapi.php/countperuser/", {monthrange: eintraegenutzermonthbtnval, usertype: usertype}, function(json) {
                var options = {   
                    chart: {
                        type: 'column',
                        renderTo: 'statuser1'
                    },
                    title: {
                        text: 'Einträge'
                    },
                    subtitle: {
                        text: 'pro User'
                    },
                    xAxis: {
                        categories: [],
                        crosshair: true
                    },
                    yAxis: {
                        title: {
                            text: 'Einträge'
                        }
                    },
                    tooltip: {
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        }
                    },
                    legend: {
                        enabled: false
                    },
                    series: [{}]
                }
    
                var categories = [];
                var points     = [];
                
                $.each(json, function(i, el) {
    				categories.push(el.name);
    				points.push(el.data);
                });
                
                options.xAxis.categories = categories;
                options.series =  [{
                        type: 'column',
                        name: 'Einträge',
                        data: points
                    }];
                var chart = new Highcharts.Chart(options);
    
            }, "json");
        }
       
        
        function reloadEPW() {
        
            $.post("https://www.martinshare.com/api/adminapi.php/countperdate/", {monthrange: eintraegeperdaymonthbtnval}, function(json) {
               
                var options = {
                        chart: {
                            zoomType: 'x',
                            renderTo: 'statuser2',
                            type: 'area'
                        },
                        title: {
                            text: 'Einträge'
                        },
                        subtitle: {
                            //text: document.ontouchstart === undefined ?  'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
                            text: "pro Tag"
                        },
                        xAxis: {
                            type: 'datetime',
                            labels: {
                                formatter: function() {
                                    return new XDate(this.value.toString()).toString('dd.MM.yy');
                                },
                                enabled: true
                            },
                            style: {
                                fontSize: '5px'
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Einträge'
                            }
                        },
                        legend: {
                            enabled: false
                        },
                        tooltip: {
                            formatter: function() {
                                var date = new XDate(this.x); 
                                return  date.toString('dd, MMMM yyyy') + '<br>' + 'Einträge: ' + this.y;
                            }
                        },
                        plotOptions: {
                            area: {
                                fillColor: {
                                    linearGradient: {
                                        x1: 0,
                                        y1: 0,
                                        x2: 0,
                                        y2: 1
                                    },
                                    stops: [
                                        [0, Highcharts.getOptions().colors[0]],
                                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                                    ]
                                },
                                marker: {
                                    radius: 2
                                },
                                lineWidth: 1,
                                states: {
                                    hover: {
                                        lineWidth: 1
                                    }
                                },
                                threshold: null
                            }
                        }
                }
                
                var categories         = [];
                var points             = [];
    
                $.each(json, function(i, el) {
    				categories.push(el.erstelldatum);
    				if($('#toggle-one').prop('checked')) {
        				if (points.length > 1) {
        				    points.push(parseInt(el.count) + points[points.length-1]);
        				} else {
        				    points.push(parseInt(el.count) );
        				}
    				} else {
    				    points.push(parseInt(el.count));
    				}
                });
                
                options.xAxis.categories = categories;
                options.series = [{
                        name: 'Einträge',
                        data: points
                    }];
                var chart = new Highcharts.Chart(options);
                
            }, "json");
        }
            
        function reloadBMS() {
        
            $.post("https://www.martinshare.com/api/adminapi.php/mobilelogin/", {monthrange: mobilesessionmonthbtnval}, function(json) {
               
                var options = {
                        chart: {
                            zoomType: 'x',
                            renderTo: 'statuser3',
                            type: 'area'
                        },
                        title: {
                            text: 'Mobile Sessions'
                        },
                        subtitle: {
                            //text: document.ontouchstart === undefined ?  'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
                            text: ""
                        },
                        xAxis: {
                            type: 'datetime',
                            labels: {
                                formatter: function() {
                                    return new XDate(this.value.toString()).toString('dd.MM.yy');
                                },
                                step: 6,
                                enabled: true
                            },
                            style: {
                                fontSize: '5px'
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Sessions',
                                min: 0
                            }
                        },
                        legend: {
                            enabled: false
                        },
                        tooltip: {
                            formatter: function() {
                                var date = new XDate(this.x); 
                                return  date.toString('dd, MMMM yyyy') + '<br>' + 'Sessions: ' + this.y;
                            }
                        },
                        plotOptions: {
                            area: {
                                fillColor: {
                                    linearGradient: {
                                        x1: 0,
                                        y1: 0,
                                        x2: 0,
                                        y2: 1
                                    },
                                    stops: [
                                        [0, Highcharts.getOptions().colors[0]],
                                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                                    ]
                                },
                                marker: {
                                    radius: 2
                                },
                                lineWidth: 1,
                                states: {
                                    hover: {
                                        lineWidth: 1
                                    }
                                },
                                threshold: null
                            }
                        }
                }
                
                var categories         = [];
                var points             = [];
    
    
                $.each(json, function(i, el) {
    				categories.push(el.erstelldatum);
    				if($('#toggle-two').prop('checked')) {
        				if (points.length > 1) {
        				    points.push(parseInt(el.count) + points[points.length-1]);
        				} else {
        				    points.push(parseInt(el.count) );
        				}
    				} else {
    				    points.push(parseInt(el.count));
    				}
                });
                
                
                options.xAxis.categories = categories;
                options.series = [{
                        name: 'Sessions',
                        data: points
                    }];
                var chart = new Highcharts.Chart(options);
    
                
            }, "json");
        }
            
        function reloadHomepageStat() {
        
            $.post("https://www.martinshare.com/api/adminapi.php/homepagelogin/", {monthrange: homepagesessionmonthbtnval}, function(json) {
               
                var options = {
                        chart: {
                            zoomType: 'x',
                            renderTo: 'statuser4',
                            type: 'area'
                        },
                        title: {
                            text: 'Homepage Sessions'
                        },
                        subtitle: {
                            //text: document.ontouchstart === undefined ?  'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
                            text: ""
                        },
                        xAxis: {
                            type: 'datetime',
                            labels: {
                                formatter: function() {
                                    return new XDate(this.value.toString()).toString('dd.MM.yy');
                                },
                                step: 6,
                                enabled: true
                            },
                            style: {
                                fontSize: '5px'
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Sessions',
                                min: 0
                            }
                        },
                        legend: {
                            enabled: false
                        },
                        tooltip: {
                            formatter: function() {
                                var date = new XDate(this.x); 
                                return  date.toString('dd, MMMM yyyy') + '<br>' + 'Sessions: ' + this.y;
                            }
                        },
                        plotOptions: {
                            area: {
                                fillColor: {
                                    linearGradient: {
                                        x1: 0,
                                        y1: 0,
                                        x2: 0,
                                        y2: 1
                                    },
                                    stops: [
                                        [0, Highcharts.getOptions().colors[0]],
                                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                                    ]
                                },
                                marker: {
                                    radius: 2
                                },
                                lineWidth: 1,
                                states: {
                                    hover: {
                                        lineWidth: 1
                                    }
                                },
                                threshold: null
                            }
                        },
            
                        series: [{ }]
                }
                
                var categories         = [];
                var points             = [];
    
                
                $.each(json, function(i, el) {
    				categories.push(el.erstelldatum);
    				if($('#toggle-three').prop('checked')) {
        				if (points.length > 1) {
        				    points.push(parseInt(el.count) + points[points.length-1]);
        				} else {
        				    points.push(parseInt(el.count) );
        				}
    				} else {
    				    points.push(parseInt(el.count));
    				}
                });
                
                
                options.xAxis.categories = categories;
                options.series = [{
                        name: 'Sessions',
                        data: points
                    }];
                var chart = new Highcharts.Chart(options);
    
                
            }, "json");
        }
            
    });
            
    </script>

    
</head>
<body>
    <div id="wrapper">
        
        
        <!-- Navigation -->
        <?php include'include/nav.php'; ?>
        
        
        <div id="page-wrapper">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <h1>Dashboard <small>Statistics and more</small></h1>
                    <div class="alert alert-dismissable alert-warning">
                        <button data-dismiss="alert" class="close" type="button">&times;</button>
                        Welcome to the admin dashboard! Feel free to review all pages and modify the layout to your needs. 
                        <br />
                        This theme uses the <a href="https://www.shieldui.com">ShieldUI</a> JavaScript library for the 
                        additional data visualization and presentation functionality illustrated here.
                    </div>
                </div>
            </div> -->
            <div class="row">
                
                
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Einträge pro Nutzer
                            <?php echoBtnGroup("eintraegenutzermonthbtn");?>
                            
                            <input id="toggle-four" data-toggle="toggle" data-on="Schulen" data-off="Nutzer" data-onstyle="success" type="checkbox">
                            </h3>
                        </div>
                        <div style="padding: 1px;" class="panel-body">
                           
                            <div id="statuser1" style="min-width: 200px; height: 300px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i><span style="margin-right: 10px"> Einträge pro Tag 
                                <?php echoBtnGroup("eintraegeperdaymonthbtn");?>
                            </span>          
                            <input id="toggle-one" checked data-toggle="toggle" data-on="Cumulative" data-off="Pro Woche" data-onstyle="success" type="checkbox">
                            </h3>
                        </div>
                        <div style="padding: 1px;" class="panel-body">
                           
                            <div id="statuser2" style="min-width: 200px; height: 300px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
                
                  
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i><span style="margin-right: 10px"> Bestehende Mobile Sessions
                                <?php echoBtnGroup("mobilesessionmonthbtn");?>
                                </span>
                            <input id="toggle-two" checked data-toggle="toggle" data-on="Cumulative" data-off="Pro Tag" data-onstyle="success" type="checkbox"></h3>
                        </div>
                        <div style="padding: 1px;" class="panel-body">
                            <div id="statuser3" style="min-width: 200px; height: 300px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>

                
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> <span style="margin-right: 10px"> Bestehende Homepage Sessions 
                                <?php echoBtnGroup("homepagesessionmonthbtn");?>
                                </span></span>
                            <input id="toggle-three" checked data-toggle="toggle" data-on="Cumulative" data-off="Pro Tag" data-onstyle="success" type="checkbox"></h3>
                        </div>
                        <div style="padding: 1px;" class="panel-body">
                           
                            <div id="statuser4" style="min-width: 200px; height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>

                
           <!-- <div class="col-md-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-rss"></i> Feed</h3>
                        </div>
                        <div class="panel-body feed">
                            <section class="feed-item">
                                <div class="icon pull-left">
                                    <i class="fa fa-comment"></i>
                                </div>
                                <div class="feed-item-body">
                                    <div class="text">
                                        <a href="#">John Doe</a> commented on <a href="#">What Makes Good Code Good</a>.
                                    </div>
                                    <div class="time pull-left">
                                        3 h
                                    </div>
                                </div>
                            </section>
                            <section class="feed-item">
                                <div class="icon pull-left">
                                    <i class="fa fa-check"></i>
                                </div>
                                <div class="feed-item-body">
                                    <div class="text">
                                        <a href="#">Merge request #42</a> has been approved by <a href="#">Jessica Lori</a>.
                                    </div>
                                    <div class="time pull-left">
                                        10 h
                                    </div>
                                </div>
                            </section>
                            <section class="feed-item">
                                <div class="icon pull-left">
                                    <i class="fa fa-plus-square-o"></i>
                                </div>
                                <div class="feed-item-body">
                                    <div class="text">
                                        New user <a href="#">Greg Wilson</a> registered.
                                    </div>
                                    <div class="time pull-left">
                                        Today
                                    </div>
                                </div>
                            </section>
                            <section class="feed-item">
                                <div class="icon pull-left">
                                    <i class="fa fa-bolt"></i>
                                </div>
                                <div class="feed-item-body">
                                    <div class="text">
                                        Server fail level raises above normal. <a href="#">See logs</a> for details.
                                    </div>
                                    <div class="time pull-left">
                                        Yesterday
                                    </div>
                                </div>
                            </section>
                            <section class="feed-item">
                                <div class="icon pull-left">
                                    <i class="fa fa-archive"></i>
                                </div>
                                <div class="feed-item-body">
                                    <div class="text">
                                        <a href="#">Database usage report</a> is ready.
                                    </div>
                                    <div class="time pull-left">
                                        Yesterday
                                    </div>
                                </div>
                            </section>
                            <section class="feed-item">
                                <div class="icon pull-left">
                                    <i class="fa fa-shopping-cart"></i>
                                </div>
                                <div class="feed-item-body">
                                    <div class="text">
                                        <a href="#">Order #233985</a> needs additional processing.
                                    </div>
                                    <div class="time pull-left">
                                        Wednesday
                                    </div>
                                </div>
                            </section>
                            <section class="feed-item">
                                <div class="icon pull-left">
                                    <i class="fa fa-arrow-down"></i>
                                </div>
                                <div class="feed-item-body">
                                    <div class="text">
                                        <a href="#">Load more...</a>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
                -->
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Traffic Sources One month tracking </h3>
                        </div>
                        <div class="panel-body">
                            <div id="shieldui-grid1"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Logins per week</h3>
                        </div>
                        <div class="panel-body">
                            <div id="shieldui-chart2"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-magnet"></i> Server Overview</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="server-stats">
                                <li>
                                    <div class="key pull-right">CPU</div>
                                    <div class="stat">
                                        <div class="info">60% / 37°C / 3.3 Ghz</div>
                                        <div class="progress progress-small">
                                            <div style="width: 70%;" class="progress-bar progress-bar-danger"></div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="key pull-right">Mem</div>
                                    <div class="stat">
                                        <div class="info">29% / 4GB (16 GB)</div>
                                        <div class="progress progress-small">
                                            <div style="width: 29%;" class="progress-bar"></div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="key pull-right">LAN</div>
                                    <div class="stat">
                                        <div class="info">6 Mb/s <i class="fa fa-caret-down"></i>&nbsp; 3 Mb/s <i class="fa fa-caret-up"></i></div>
                                        <div class="progress progress-small">
                                            <div style="width: 48%;" class="progress-bar progress-bar-inverse"></div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="col-lg-4">
                    <header>
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a data-toggle="tab" href="#stats">Users</a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#report">Favorites</a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#dropdown1">Commenters</a>
                            </li>
                        </ul>
                    </header>
                    <div class="body tab-content">
                        <div class="tab-pane clearfix active" id="stats">
                            <h5 class="tab-header"><i class="fa fa-calendar-o fa-2x"></i> Last logged-in users</h5>
                            <ul class="news-list">
                                <li>
                                    <i class="fa fa-user fa-4x pull-left"></i>
                                    <div class="news-item-info">
                                        <div class="name"><a href="#">Ivan Gorge</a></div>
                                        <div class="position">Software Engineer</div>
                                        <div class="time">Last logged-in: Mar 12, 11:11</div>
                                    </div>
                                </li>
                                <li>
                                    <i class="fa fa-user fa-4x pull-left"></i>
                                    <div class="news-item-info">
                                        <div class="name"><a href="#">Roman Novak</a></div>
                                        <div class="position">Product Designer</div>
                                        <div class="time">Last logged-in: Mar 12, 19:02</div>
                                    </div>
                                </li>
                                <li>
                                    <i class="fa fa-user fa-4x pull-left"></i>
                                    <div class="news-item-info">
                                        <div class="name"><a href="#">Teras Uotul</a></div>
                                        <div class="position">Chief Officer</div>
                                        <div class="time">Last logged-in: Jun 16, 2:34</div>
                                    </div>
                                </li>
                                <li>
                                    <i class="fa fa-user fa-4x pull-left"></i>
                                    <div class="news-item-info">
                                        <div class="name"><a href="#">Deral Ferad</a></div>
                                        <div class="position">Financial Assistant</div>
                                        <div class="time">Last logged-in: Jun 18, 4:20</div>
                                    </div>
                                </li>
                                <li>
                                    <i class="fa fa-user fa-4x pull-left"></i>
                                    <div class="news-item-info">
                                        <div class="name"><a href="#">Konrad Polerd</a></div>
                                        <div class="position">Sales Manager</div>
                                        <div class="time">Last logged-in: Jun 18, 5:13</div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-pane" id="report">
                            <h5 class="tab-header"><i class="fa fa-star fa-2x"></i> Popular contacts</h5>
                            <ul class="news-list news-list-no-hover">
                                <li>
                                    <i class="fa fa-user fa-4x pull-left"></i>
                                    <div class="news-item-info">
                                        <div class="name"><a href="#">Pol Johnsson</a></div>
                                        <div class="options">
                                            <button class="btn btn-xs btn-success">
                                                <i class="fa fa-phone"></i>
                                                Call
                                            </button>
                                            <button class="btn btn-xs btn-warning">
                                                <i class="fa fa-envelope-o"></i>
                                                Message
                                            </button>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <i class="fa fa-user fa-4x pull-left"></i>
                                    <div class="news-item-info">
                                        <div class="name"><a href="#">Terry Garel</a></div>
                                        <div class="options">
                                            <button class="btn btn-xs btn-success">
                                                <i class="fa fa-phone"></i>
                                                Call
                                            </button>
                                            <button class="btn btn-xs btn-warning">
                                                <i class="fa fa-envelope-o"></i>
                                                Message
                                            </button>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <i class="fa fa-user fa-4x pull-left"></i>
                                    <div class="news-item-info">
                                        <div class="name"><a href="#">Eruos Forkal</a></div>
                                        <div class="options">
                                            <button class="btn btn-xs btn-success">
                                                <i class="fa fa-phone"></i>
                                                Call
                                            </button>
                                            <button class="btn btn-xs btn-warning">
                                                <i class="fa fa-envelope-o"></i>
                                                Message
                                            </button>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <i class="fa fa-user fa-4x pull-left"></i>
                                    <div class="news-item-info">
                                        <div class="name"><a href="#">Remus Reier</a></div>
                                        <div class="options">
                                            <button class="btn btn-xs btn-success">
                                                <i class="fa fa-phone"></i>
                                                Call
                                            </button>
                                            <button class="btn btn-xs btn-warning">
                                                <i class="fa fa-envelope-o"></i>
                                                Message
                                            </button>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <i class="fa fa-user fa-4x pull-left"></i>
                                    <div class="news-item-info">
                                        <div class="name"><a href="#">Lover Lund</a></div>
                                        <div class="options">
                                            <button class="btn btn-xs btn-success">
                                                <i class="fa fa-phone"></i>
                                                Call
                                            </button>
                                            <button class="btn btn-xs btn-warning">
                                                <i class="fa fa-envelope-o"></i>
                                                Message
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-pane" id="dropdown1">
                            <h5 class="tab-header"><i class="fa fa-comments fa-2x"></i> Top Commenters</h5>
                            <ul class="news-list">
                                <li>
                                    <i class="fa fa-user fa-4x pull-left"></i>
                                    <div class="news-item-info">
                                        <div class="name"><a href="#">Edin Garey</a></div>
                                        <div class="comment">
                                            Nemo enim ipsam voluptatem quia voluptas sit aspernatur 
                                            aut odit aut fugit,sed quia
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <i class="fa fa-user fa-4x pull-left"></i>
                                    <div class="news-item-info">
                                        <div class="name"><a href="#">Firel Lund</a></div>
                                        <div class="comment">
                                            Duis aute irure dolor in reprehenderit in voluptate velit
                                             esse cillum dolore eu fugiat.
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <i class="fa fa-user fa-4x pull-left"></i>
                                    <div class="news-item-info">
                                        <div class="name"><a href="#">Jessica Desingter</a></div>
                                        <div class="comment">
                                            Excepteur sint occaecat cupidatat non proident, sunt in
                                             culpa qui officia deserunt.
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <i class="fa fa-user fa-4x pull-left"></i>
                                    <div class="news-item-info">
                                        <div class="name"><a href="#">Novel Forel</a></div>
                                        <div class="comment">
                                            Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque.
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <i class="fa fa-user fa-4x pull-left"></i>
                                    <div class="news-item-info">
                                        <div class="name"><a href="#">Wedol Reier</a></div>
                                        <div class="comment">
                                            Laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis
                                            et quasi.
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /#wrapper -->

    <script type="text/javascript">
       
    </script>
    
    
</body>
</html>
<?php } else {
    Redirect::to("index.php");
} ?>


