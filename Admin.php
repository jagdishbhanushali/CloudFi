<?php
if(!isset($_SESSION))
{
session_start();
}
include 'connect.php';
if($_SESSION['Login']==true){
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CloudFi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Charisma, a fully featured, responsive, HTML5, Bootstrap admin template.">

    <!-- The styles -->
    <link id="bs-css" href="css/bootstrap-cerulean.min.css" rel="stylesheet">

    <link href="css/charisma-app.css" rel="stylesheet">
    <link href='bower_components/fullcalendar/dist/fullcalendar.css' rel='stylesheet'>
    <link href='bower_components/fullcalendar/dist/fullcalendar.print.css' rel='stylesheet' media='print'>
    <link href='bower_components/chosen/chosen.min.css' rel='stylesheet'>
    <link href='bower_components/colorbox/example3/colorbox.css' rel='stylesheet'>
    <link href='bower_components/responsive-tables/responsive-tables.css' rel='stylesheet'>
    <link href='bower_components/bootstrap-tour/build/css/bootstrap-tour.min.css' rel='stylesheet'>
    <link href='css/jquery.noty.css' rel='stylesheet'>
    <link href='css/noty_theme_default.css' rel='stylesheet'>
    <link href='css/elfinder.min.css' rel='stylesheet'>
    <link href='css/elfinder.theme.css' rel='stylesheet'>
    <link href='css/jquery.iphone.toggle.css' rel='stylesheet'>
    <link href='css/uploadify.css' rel='stylesheet'>
    <link href='css/animate.min.css' rel='stylesheet'>

    <!-- jQuery -->
    <script src="bower_components/jquery/jquery.min.js"></script>

    <!-- The fav icon -->
    <link rel="shortcut icon" href="img/favicon.ico">

</head>

<body>
    <!-- topbar starts -->
    <div class="navbar navbar-default" role="navigation">

        <div class="navbar-inner">
            <button type="button" class="navbar-toggle pull-left animated flip">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php"> 
                <img alt="Charisma Logo" src="img/logo20.png" class="hidden-xs"/>
                <span>CloudFi</span>
            </a>

            <!-- user dropdown starts -->
            <div class="btn-group pull-right">
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="glyphicon glyphicon-user"></i><span class="hidden-sm hidden-xs"> admin</span>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#">Profile</a></li>
                    <li class="divider"></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
            <!-- user dropdown ends -->
        </div>
    </div>
    <!-- topbar ends -->
<div class="ch-container">
    <div class="row">
        <?php
            if($_SESSION['message']!=""){
                echo "<div class='alert alert-info'>
                <button type='button' class='close' data-dismiss='alert'>×</button>
                ".$_SESSION['message']."
                </div>";    
                $_SESSION['message']="";
            }
            
        ?>
        <!-- left menu starts -->
        <div class="col-sm-2 col-lg-2">
            <div class="sidebar-nav">
                <div class="nav-canvas">
                    <div class="nav-sm nav nav-stacked">

                    </div>
                    <ul class="nav nav-pills nav-stacked main-menu">
                        <li class="nav-header">Main</li>
                        <li>
                            <a class="ajax-link" href="Admin.php"><i class="glyphicon glyphicon-home"></i><span> Dashboard</span></a>
                        </li>
                        <li>
                            <a class="ajax-link" href="AdminClusters.php"><i class="glyphicon glyphicon-eye-open"></i><span> Cluster</span></a>
                        </li>
                        <!-- <li><a class="ajax-link" href="form.html"><i
                                    class="glyphicon glyphicon-edit"></i><span> Forms</span></a></li>
                        <li><a class="ajax-link" href="table.html"><i
                                    class="glyphicon glyphicon-align-justify"></i><span> Tables</span></a></li>
                        <li><a href="login.html"><i class="glyphicon glyphicon-lock"></i><span> Login Page</span></a>
                        </li> -->
                    </ul>
                </div>
            </div>
        </div>
        <!--/span-->
        <!-- left menu ends -->

        <noscript>
            <div class="alert alert-block col-md-12">
                <h4 class="alert-heading">Warning!</h4>

                <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a>
                    enabled to use this site.</p>
            </div>
        </noscript>

        <div id="content" class="col-lg-10 col-sm-10">
            <!-- content starts -->
            <div>
    <ul class="breadcrumb">
        <li>
            <a href="#">Home</a>
        </li>
        <li>
            <a href="#">Dashboard</a>
        </li>
    </ul>
</div>


<div class="row">
    <h2>Your Files</h2>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <!-- <input data-no-uniform="true" type="file" name="file_upload" id="file_upload"> -->
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" name="submit">
    </form>
    <div class="container">
      
      <table class="table table-striped">
        <thead>
          <tr>
            <th>File Name</th>
            <th>Size</th>
            <th>Uploaded On</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php
            $sql="select *  from tblfile";
            $result=$db->query($sql);
            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>".$row['FileName']."</td>
                            <td>".$row['FileSize']."</td>
                            <td>".$row['createdDate']."</td>
                            <td class='center'>
                                <a class='btn btn-info' href='DownloadFile.php?fid=".$row['FileId']."&cb=admin'>
                                    <i class='glyphicon glyphicon-download icon-white'></i>
                                    Download
                                </a>
                                <a class='btn btn-danger' href='DeleteFile.php?fid=".$row['FileId']."&cb=admin'>
                                    <i class='glyphicon glyphicon-trash icon-white'></i>
                                    Delete
                                </a>
                            </td>
                        </tr>";
                }
            } else {
                echo "No File Uploaded";
            } 
        ?>
        </tbody>
      </table>
    </div>
    
    <!--/span-->


</div>

 <!--/row-->
    <!-- content ends -->
    </div><!--/#content.col-md-0-->
</div><!--/fluid-row-->

    <hr>


    <footer class="row">
    </footer>

</div><!--/.fluid-container-->



<!-- external javascript -->

<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- library for cookie management -->
<script src="js/jquery.cookie.js"></script>
<!-- calender plugin -->
<script src='bower_components/moment/min/moment.min.js'></script>
<script src='bower_components/fullcalendar/dist/fullcalendar.min.js'></script>
<!-- data table plugin -->
<script src='js/jquery.dataTables.min.js'></script>

<!-- select or dropdown enhancer -->
<script src="bower_components/chosen/chosen.jquery.min.js"></script>
<!-- plugin for gallery image view -->
<script src="bower_components/colorbox/jquery.colorbox-min.js"></script>
<!-- notification plugin -->
<script src="js/jquery.noty.js"></script>
<!-- library for making tables responsive -->
<script src="bower_components/responsive-tables/responsive-tables.js"></script>
<!-- tour plugin -->
<script src="bower_components/bootstrap-tour/build/js/bootstrap-tour.min.js"></script>
<!-- star rating plugin -->
<script src="js/jquery.raty.min.js"></script>
<!-- for iOS style toggle switch -->
<script src="js/jquery.iphone.toggle.js"></script>
<!-- autogrowing textarea plugin -->
<script src="js/jquery.autogrow-textarea.js"></script>
<!-- multiple file upload plugin -->
<script src="js/jquery.uploadify-3.1.min.js"></script>
<!-- history.js for cross-browser state change on ajax -->
<script src="js/jquery.history.js"></script>
<!-- application script for Charisma demo -->
<script src="js/charisma.js"></script>


</body>
</html>
<?php
}else{
    header("Location:login.php");
}

?>