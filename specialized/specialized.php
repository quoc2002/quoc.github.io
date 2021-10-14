<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("login.php");
    exit;
}
?>
    <!DOCTYPE html>
    <html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
        table tr td:last-child{
            width: 120px;
        }
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="mt-5 mb-3 clearfix">
                    <h2 class="pull-left">Quản lý chuyên ngành</h2>
                    <a href="create.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Thêm chuyên ngành mới</a>
                </div>
                <?php
                //Include config file
                require_once "../config.php";

                //Attempt select query execution
                $sql = "SELECT * FROM chuyennganh";
                global $mysqli;
                if($result = $mysqli->query($sql)){
                    if($result->num_rows > 0){
                        echo '<table class="table table-bordered table-striped">';
                        echo "<thead>";
                        echo "<tr>";
                        echo"<th>Mã chuyên ngành</th>";
                        echo"<th>Tên chuyên ngành</th>";
                        echo "<th>Hoạt động</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = $result->fetch_array()){
                            echo "<tr>";
                            echo "<td>" .$row['macn'] ."</td>";
                            echo "<td>" .$row['tencn'] ."</td>";
                            echo "<td>";
                            echo '<a href="read.php?id=' .$row['macn'].'" class="mr-3" title="View Record" 
                                            data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                            echo '<a href="update.php?id=' .$row['macn'].'" class="mr-3" title="Update Record"
                                            data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                            echo '<a href="delete.php?id=' .$row['macn'].'" class="mr-3" title="Delete Record"
                                            data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                        // free result set
                        $result->free();
                    }else{
                        echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                    }
                }else{
                    echo "Oops! Something went wrong. Please try again later.";
                }

                //close connection
                $mysqli->close();
                ?>
            </div>
        </div>
    </div>
    <p>
        <a href="../welcome.php" class="btn btn-danger ml-3">Return Welcome Page</a>
    </p>
</div>
</body>
    </html>
