<?php
require_once 'dbconn.php';

$dbc = new Dbconn();//create an instance of the db

//for search
if(isset($_POST['sv'])){
    $value = filter_input(INPUT_POST, 'sv');
    
    $visitors_list = $dbc->visitorSearch($value);
    
    $sn = 1;
}

//others (default, sort order, show per page)
else{
    //get page number
    $page = isset($_POST['vListPage']) ? filter_input(INPUT_POST, 'vListPage', FILTER_VALIDATE_INT) : 1;

    //set info to query the db with
    //determine the start and the limit (limit should be 10 by default)
    //to get the start, subtract one from $page and multiply the result by $limit to determine where to start from
    $limit = isset($_POST['l']) ? filter_input(INPUT_POST, 'l', FILTER_VALIDATE_INT) : 10;// 
    $start = $page ? (($page - 1) * $limit) : 0;//defaults to 0

    //set SN
    $sn = $page ? ($start + 1) : 1;//defaults to 1

    //set sort order
    $order_by = isset($_POST['vsb']) ? filter_input(INPUT_POST, 'vsb') : 'id';//vsb = "visitor sort by"
    $order_format = isset($_POST['vsbo']) ? filter_input(INPUT_POST, 'vsbo') : 'DESC';//vsbo = "visitor sort by order"


    $visitors_list = $dbc->getAllVisitors($order_by, $order_format, $start, $limit);//get all visitors
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>DigiPass</title>
        
        <!-- Favicon -->
        <link rel="shortcut icon" href="public/img/favicon.ico">
        <!-- favicon ends --->
		
		<?php if($_SERVER['HTTP_HOST'] == "localhost" || (stristr($_SERVER['HTTP_HOST'], "192.168.") !== FALSE)|| (stristr($_SERVER['HTTP_HOST'], "127.0.0.") !== FALSE)): ?>
        <link rel="stylesheet" href="public/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="public/bootstrap/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="public/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="public/font-awesome/css/font-awesome-animation.min.css">

        <script src="public/js/jquery.min.js"></script>
        <script src="public/bootstrap/js/bootstrap.min.js"></script>

        <?php else: ?>
		
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome-animation/0.0.8/font-awesome-animation.min.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <?php endif; ?>
		
		<!--Custom styles -->
		<link href="public/css/main.css" rel="stylesheet">
        
		<!--Custom js -->
		<script src="public/js/webcam.min.js"></script><!-- Loaded locally (default error removed)-->
        <script src="public/js/main.js"></script>
		
		<!-- Google font-->
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,700|Cabin|PT+Sans' rel='stylesheet' type='text/css'>
    </head>
    
    
    <body class="bg-hash">
        <div class="container-fluid hidden-print">
            <div class="row" style="margin-top:10px">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="panel panel-hash">
                        <div class="panel-heading">
                            <i class="fa fa-users"></i> Visitors
                        </div>
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="text-center" id='vPassFMsg'></div>
                            </div>
                            <br><br>
                            <!--- LEFT COL--->
                            <div class="col-md-6">
                                <div class="form-group-sm">
                                    <form id="vPassForm">
                                        <label for="vName">Name</label>
                                        <input type="text" class="form-control" id="vName" placeholder="Name">
                                        <br>
                                        <label for="vEmail">Email</label>
                                        <input type="email" class="form-control" id="vEmail" placeholder="Email">
                                        <br>
                                        <label for="vPhone">Phone</label>
                                        <input type="tel" class="form-control" id="vPhone" placeholder="Phone Number">
                                        <br>
                                        <label for="vFrom">From</label>
                                        <input type="text" class="form-control" id="vFrom" placeholder="From">
                                        <br>
                                        <label for="vToSee">Whom to See</label>
                                        <input type="text" class="form-control" id="vToSee" placeholder="Whom to See">
                                    </form>
                                </div>
                                <br>
                                <div class="text-center">
                                    <button class="btn btn-success btn-sm" id='genPass'>Generate Pass</button>
                                    <button class="btn btn-danger btn-sm" id='cancelGenPass'>Cancel</button>
                                </div>
                            </div>
                            <!--- END OF LEFT COL--->
                            
                            
                            
                            <!--- RIGHT COL--->
                            <div class="col-md-6">
                                <div class="panel panel-hash">
                                    <div class="panel-heading">
                                        <i class="fa fa-image"></i> Visitor's Image
                                        <span class="pull-right">
                                            <button class="btn btn-success btn-xs enablewebcam" id="toggleWebCam">Enable Webcam</button>
                                        </span>
                                    </div>
                                    <div class="panel-body">
                                        <div id="visitorImage"></div>
                                    </div>
                                </div>
                                <br>
                                <div class="panel panel-hash">
                                    <div class="panel-heading"><i class="fa fa-sign-out"></i> Visitor Check-out</div>
                                    <div class="panel-body">
                                        <div class="form-group-sm">
                                            <input type="text" class="form-control" id="vVCheckOutId" placeholder="Pass ID">
                                            <br>
                                            <button class="btn btn-danger btn-sm" id="vCheckOut">Check out</button>
                                            <div id='vVCheckOutFMsg'></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--- END OF RIGHT COL--->
                        </div>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            
            
            
            
            <br>
            <input type="hidden" id="vListCurPage" value="1">
            <div class="row lil-top-space">
                <div class="col-sm-1"></div>
                <div class="col-sm-3 form-group-sm">
                    <label class="" for="vShow">Show Per Page</label>
                    <select class="form-control" id="vShow">
                        <option value="">--Show per page--</option>
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div class="col-sm-4 form-group-sm">
                    <label class="" for="vSort">Sort By</label>
                    <select class="form-control" id="vSort">
                        <option value="">--Sort By--</option>
                        <option value="name-ASC">Name (a-z)</option>
                        <option value="phone-ASC">phone (ascending)</option>
                        <option value="id-ASC">Time In (oldest first)</option>
                        <option value="where_from-ASC">From (a-z)</option>
                        <option value="to_see-ASC">To See (a-z)</option>
                        <option></option>
                        <option value="name-DESC">Name (z-a)</option>
                        <option value="phone-DESC">phone (descending)</option>
                        <option value="id-DESC" selected>Time In (latest first)</option>
                        <option value="where_from-DESC">From (z-a)</option>
                        <option value="to_see-DESC">To See (z-a)</option>
                    </select>                        
                </div>
                <div class="col-sm-3 form-group-sm">
                    <label class="" for="vSearch">Search</label>
                    <input type="search" class="form-control" id="vSearch" placeholder="Search">
                </div>
                <div class="col-sm-1"></div>
            </div>
            
            <div class="row lil-top-space">
                <div class="col-md-12 text-center">
                    <button class="btn btn-default btn-xs pgBtnPrev"><i class="fa fa-arrow-circle-left"></i> Previous</button>
                    <button class="btn btn-default btn-xs pgBtnNxt">Next <i class="fa fa-arrow-circle-right"></i></button>
                </div>
            </div>
            
            <div class="row lil-top-space">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="panel panel-hash">
                        <div class="panel-heading">List Of Visitors</div>
                        <div class="panel-body">
                            <div class="table-responsive" id="vTableDiv">
                                <table class="table table-striped table-bordered" id="vListTable">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Name</th>
                                            <th>Pass ID</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>From</th>
                                            <th>To See</th>
                                            <th>Time In</th>
                                            <th>Time Out</th>
                                            <th>Status</th>
                                            <th>View Pass</th>
                                        </tr>
                                    </thead>

                                    <tbody id="vVPassTBody">
                                        <?php if($visitors_list): ?>
                                        <?php foreach($visitors_list as $get): ?>
                                        <tr id="lr-<?=$get['id']?>">
                                            <td class="vVPassSn"><?=$sn."."?></td>
                                            <td class="vVPassName"><?=$get['name']?></td>
                                            <td class="vVPassId"><?=$get['id']?></td>
                                            <td class="vVPassEmail"><?=$get['email']?></td>
                                            <td class="vVPassPhone"><?=$get['phone']?></td>
                                            <td class="vVPassFrom"><?=$get['where_from']?></td>
                                            <td class="vVPassToSee"><?=$get['to_see']?></td>
                                            <td class="vVPassCheckIn"><?=date('jS M, Y h:i:sa', strtotime($get['check_in_time']))?></td>
                                            <td class="vVPassCheckOut">
                                                <?=$get['check_out_time'] != "0000-00-00 00:00:00" ? date('jS M, Y h:i:sa', strtotime($get['check_out_time'])) : "---"?>
                                            </td>
                                            <td class="vVPassStatus"><?=$get['status'] ? "OUT" : "IN"?></td>
                                            <td>
                                                <button class="btn btn-primary btn-xs viewVPassClk">View Pass</button>
                                                <input type="hidden" class="vVPassImg" value="<?=$get['img_url']?>">
                                            </td>
                                        </tr>
                                        <?php $sn++; ?>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button class="btn btn-default btn-xs pgBtnPrev"><i class="fa fa-arrow-circle-left"></i> Previous</button>
                                    <button class="btn btn-default btn-xs pgBtnNxt">Next <i class="fa fa-arrow-circle-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>
        
        
        
        <!--- PASS MODAL---->
        <div class="modal fade" role='dialog' id='visitorPassModal' data-dropback='static'>
            <div class="modal-dialog modal-sm">
                <div class="modal-content" style='font-size:10px'>
                    <div class="modal-header">
                        <button class="close hidden-print" data-dismiss='modal'>&times;</button>
                        <center><img src='public/img/logo.png' class="img-responsive modal-logo"></center>
                    </div>
                    <div class="modal-body">
                        <center><img src='' id='vPassImg' class="img-responsive modal-img"></center>
                        <br>
                        <div class="table-responsive">
                            <table class="table table-striped modal-table">
                                <tr>
                                    <th>Pass ID</th>
                                    <td id='vPassId'></td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td id='vPassName'></td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td id='vPassPhone'></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td id='vPassEmail'></td>
                                </tr>
                                <tr>
                                    <th>From</th>
                                    <td id='vPassFrom'></td>
                                </tr>
                                <tr>
                                    <th>Whom to see</th>
                                    <td id="vPassSee"></td>
                                </tr>
                                <tr>
                                    <th>Time In</th>
                                    <td id="vPassTimeIn"></td>
                                </tr>
								<!--
                                <tr>
                                    <th>Time Out</th>
                                    <td id="vPassTimeOut">---</td>
                                </tr>-->
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer hidden-print">
                        <button class="btn btn-info btn-sm" id='vPassPrint'><i class="fa fa-print"></i> Print</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END OF PASS MODAL--->
    </body>
</html>