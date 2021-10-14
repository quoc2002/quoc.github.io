<?php
session_start();
if(!isset($_SESSION["loggedin"]) ||  $_SESSION["loggedin"] !== true){
    header("location:login.php");
    exit;
}
?>
<?php
// Include config file
require_once "../config.php";

//Define variables and initialize with empty values
$mabd = $tenbd = $diachi = "";
$mabd_err = $tenbd_err = $diachi_err = "";

// Processing form data when form is submitted
if(isset($_POST["bd_id"]) && !empty($_POST["bd_id"])){
    // Get hidden input value
    $bd_id = $_POST["bd_id"];

    //Validate ID
    $input_mabd = trim($_POST["mabd"]);
    if(empty($input_mabd)){
        $mabd_err = "Please enter Reader ID.";
    }elseif (!filter_var($input_mabd, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[0-9\s]+$/")))){
        $mabd_err = " Reader ID just contains exactly 5 numbers.";
    }elseif(strlen(trim($_POST["mabd"])) != 5){
        $mabd_err = " Reader ID just contains exactly 5 numbers.";
    }
    else{
        $mabd = $input_mabd;
    }

    //Validate name
    $input_tenbd = trim($_POST["tenbd"]);
    if(empty($input_tenbd)){
        $tenbd_err = "Please enter a name.";
    }elseif(!filter_var($input_tenbd, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $tenbd_err = "Please enter a valid name.";
    }else{
        $tenbd = $input_tenbd;
    }
    //Validate address
    $input_diachi = trim($_POST["diachi"]);
    if(empty($input_diachi)){
        $diachi_err = "Please enter an address.";
    }else{
        $diachi = $input_diachi;
    }



    //Check input errors before inserting in database
    if(empty($mabd_err) && empty($tenbd_err) && empty($diachi_err)){
        //Prepare an update statement
        $sql = "update bandoc set mabd=? , tenbd=?, diachi=? where bd_id=?";

        if($stmt = $mysqli->prepare($sql)){
            //Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssi",$param_mabd, $param_tenbd, $param_diachi, $param_bd_id);

            //Set parameters
            $param_mabd = $mabd;
            $param_tenbd = $tenbd;
            $param_diachi = $diachi;
            $param_bd_id = $bd_id;

            //Attempt to execute the prepared statement
            if($stmt->execute()){
                //Records updated successfully. Redirect to landing page
                header("location: dashboard.php");
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
    if(isset($_GET["bd_id"]) && !empty(trim($_GET["bd_id"]))){
        //Get URL parameter
        $bd_id = trim($_GET["bd_id"]);

        //Prepare a select statement
        $sql = "SELECT * from bandoc where bd_id=?";
        if($stmt = $mysqli->prepare($sql)){
            //Bind variables to the prepared statement as parameters
            $stmt->bind_param("i",$param_bd_id);

            //Set parameters
            $param_bd_id = $bd_id;

            //Attempt to execute the prepare statement
            if($stmt->execute()){
                $result = $stmt->get_result();
                if($result->num_rows == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = $result->fetch_array(MYSQLI_ASSOC);

                    //Retrieve individual field value
                    $mabd = $row["mabd"];
                    $tenbd = $row["tenbd"];
                    $diachi = $row["diachi"];

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
                <p>Please edit the input values and submit to update the reader record.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                        <label>Reader ID</label>
                        <input type="text" name="mabd" class="form-control
                                 <?php echo (!empty($mabd_err)) ?'is-invalid' : ''; ?>" value="<?php echo $mabd; ?>">
                        <span class="invalid-feedback"><?php echo $mabd_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="diachi" class="form-control
                          <?php echo (!empty($diachi_err)) ?'is-invalid' : ''; ?>"> <?php echo $diachi; ?></textarea>
                        <span class="invalid-feedback"><?php echo $diachi_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Reader Name</label>
                        <input type="text" name="tenbd" class="form-control
                              <?php echo (!empty($tenbd_err)) ?'is-invalid' : ''; ?>" value="<?php echo $tenbd; ?>">
                        <span class="invalid-feedback"><?php echo $tenbd_err; ?></span>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $bd_id; ?>" />
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="dashboard.php" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
