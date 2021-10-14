<?php
session_start();
if(!isset($_SESSION["loggedin"]) ||  $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
<?php
// Include config file
require_once "../config.php";

//Define variables and initialize with empty values
$tensach = $tacgia = $namxb = $trangthai =  "";
$tensach_err = $tacgia_err = $namxb_err = $trangthai_err =  "";

// Processing form data when form is submitted
if(isset($_POST["sach_id"]) && !empty($_POST["sach_id"])){
    // Get hidden input value
    $sach_id = $_POST["sach_id"];

    //Validate name
    $input_tensach = trim($_POST["tensach"]);
    if(empty($input_tensach)){
        $tensach_err = "Please enter a book name.";
    }elseif(!filter_var($input_tensach, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\s]+$/")))){
        $tensach_err = "Please enter a valid name.";
    }else{
        $tensach = $input_tensach;
    }
    //Validate author
    $input_tacgia = trim($_POST["tacgia"]);
    if(empty($input_tacgia)){
        $tacgia_err = "Please enter an author name.";
    }else{
        $tacgia = $input_tacgia;
    }

    //Validate year
    $input_namxb = trim($_POST["namxb"]);
    if(empty($input_namxb)){
        $namxb_err = "Please enter the publish year.";
    }elseif(!ctype_digit($input_namxb)){
        $namxb_err = "Please enter a positive integer value.";
    }else{
        $namxb = $input_namxb;
    }

    // Validate book status
    $input_trangthai = trim($_POST["trangthai"]);
    if(empty($input_trangthai)){
        $trangthai_err = "Please enter Book status.";
    }elseif($input_trangthai !="Not Published" || $input_trangthai != "Published"){
        $trangthai_err = "Publish status is invalid.";
    }
    else{
        $trangthai = $input_trangthai;
    }

    //Check input errors before inserting in database
    if(empty($tensach_err) && empty($tacgia_err) && empty($namxb_err) && empty($trangthai_err)){
        //Prepare an update statement
        $sql = "update sach set tensach=? , tacgia=?, namxb=? , trangthai=? where sach_id=?";

        if($stmt = $mysqli->prepare($sql)){
            //Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssi",$param_tensach, $param_tacgia, $param_namxb, $param_trangthai, $param_id);

            //Set parameters
            $param_tensach = $tensach;
            $param_tacgia = $tacgia;
            $param_namxb = $namxb;
            $param_trangthai = $trangthai;
            $param_id = $sach_id;

            //Attempt to execute the prepared statement
            if($stmt->execute()){
                //Records updated successfully. Redirect to landing page
                header("location: dashboard_book.php");
                exit();
            }else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        //Close statement
        $stmt->close();
    }
    //Close connection
    $mysqli->close();
}else{
    //Check existence of id parameter before processing further
    if(isset($_GET["sach_id"]) && !empty(trim($_GET["sach_id"]))){
        //Get URL parameter
        $sach_id = trim($_GET["sach_id"]);

        //Prepare a select statement
        $sql = "SELECT * from sach where sach_id=?";
        if($stmt = $mysqli->prepare($sql)){
            //Bind variables to the prepared statement as parameters
            $stmt->bind_param("i",$param_id);

            //Set parameters
            $param_id = $sach_id;

            //Attempt to execute the prepare statement
            if($stmt->execute()){
                $result = $stmt->get_result();
                if($result->num_rows == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = $result->fetch_array(MYSQLI_ASSOC);

                    //Retrieve individual field value
                    $tensach = $row["tensach"];
                    $tacgia = $row["tacgia"];
                    $namxb = $row["namxb"];
                    $trangthai = $row["trangthai"];
                }else{
                    //URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
            }else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        //Close statement
        $stmt->close();

        //Close connection
        $mysqli->close();
    }else{
        //URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-5">Update Record</h2>
                <p>Please edit the input values and submit to update the book record.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                        <label>Book name</label>
                        <input type="text" name="tensach" class="form-control
                                 <?php echo (!empty($tensach_err)) ?'is-invalid' : ''; ?>" value="<?php echo $tensach; ?>">
                        <span class="invalid-feedback"><?php echo $tensach_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Author</label>
                        <input type="text" name="tacgia" class="form-control
                                 <?php echo (!empty($tacgia_err)) ?'is-invalid' : ''; ?>" value="<?php echo $tacgia; ?>">
                        <span class="invalid-feedback"><?php echo $tacgia_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Publish year</label>
                        <input type="text" name="namxb" class="form-control
                              <?php echo (!empty($namxb_err)) ?'is-invalid' : ''; ?>" value="<?php echo $namxb; ?>">
                        <span class="invalid-feedback"><?php echo $namxb_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Book status</label>
                        <input type="text" name="trangthai" class="form-control
                                 <?php echo (!empty($trangthai_err)) ?'is-invalid' : ''; ?>" value="<?php echo $trangthai; ?>">
                        <span class="invalid-feedback"><?php echo $trangthai_err; ?></span>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $sach_id; ?>" />
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="dashboard.php" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>