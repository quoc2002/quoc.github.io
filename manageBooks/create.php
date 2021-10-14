<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
<?php
// Include config file
require_once "../config.php";
// sach_id;  ma_sach; tensach; tacgia; namxb; trangthai; macn
//Define variables and initialize with empty values
$ma_sach = $tensach = $tacgia = $namxb = $trangthai = $macn = "";
$ma_sach_err = $tensach_err = $tacgia_err = $namxb_err = $trangthai_err = $macn_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"]== "POST"){
    //Validate Book ID
    if(empty(trim($_POST["ma_sach"]))){
        $ma_sach_err = "Please enter Book ID.";
    }elseif (!preg_match('/^[0-9]+$/', trim($_POST["ma_sach"]))){
        $ma_sach_err = " Book ID just contains exactly 5 numbers.";
    }elseif(strlen(trim($_POST["ma_sach"])) != 5){
        $ma_sach_err = " Reader ID just contains exactly 5 numbers.";
    }
    else{
        $sql = "select sach_id from sach where ma_sach=?";

        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("s",$param_ma_sach);

            $param_ma_sach = trim($_POST["ma_sach"]);

            if($stmt->execute()){
                $stmt->store_result();

                if($stmt->num_rows ==1){
                    $ma_sach_err = "This ID is already taken.";
                }else{
                    $ma_sach = trim($_POST["ma_sach"]);
                }
            }else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }

    // Validate book name
    $input_tensach = trim($_POST["tensach"]);
    if(empty($input_tensach)){
        $tensach_err = "Please enter Book name.";

    }else{
        $tensach = $input_tensach;
    }

    // Validate author name
    $input_tacgia = trim($_POST["tacgia"]);
    if(empty($input_tacgia)){
        $tacgia_err = "Please enter Author name.";

    }else{
        $tacgia = $input_tacgia;
    }
    // Validate year
    $input_namxb = trim($_POST["namxb"]);
    if(empty($input_namxb)){
        $namxb_err = "Please enter the published time.";
    }elseif (!preg_match('/^[0-9]+$/', trim($_POST["namxb"]))){
        $namxb_err = " Please enter an integer type.";
    }
    elseif($input_namxb < 0 || $input_namxb > 2021){
        $namxb_err="Published time is invalid.";
    }else{
        $namxb = $input_namxb;
    }

    // Validate book status
    $input_trangthai = trim($_POST["trangthai"]);
    if(empty($input_trangthai)){
        $trangthai_err = "Please enter Book status.";
    } else{
        $trangthai = $input_trangthai;
    }

    //Validate Major ID
    $input_macn = trim($_POST["macn"]);
    if(empty($input_macn)){
        $macn_err = "Please enter Major ID.";

    }else{
        $macn = $input_macn;
    }


    //Check input errors before inserting in database
    if(empty($tensach_err) && empty($ma_sach_err) && empty($tacgia_err) && empty($namxb_err) && empty($trangthai_err) && empty($macn_err) ){
        //Prepare an insert statement
        $sql = "insert into sach (ma_sach, tensach, tacgia, namxb, trangthai, macn) values (?,?,?,?,?,?)";

        if($stmt = $mysqli->prepare($sql)){
            //Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssss",$param_ma_sach, $param_tensach, $param_tacgia, $param_namxb, $param_trangthai, $param_macn);

            //Set parameters
            $param_ma_sach = $ma_sach;
            $param_tensach = $tensach;
            $param_tacgia = $tacgia;
            $param_namxb = $namxb;
            $param_trangthai = $trangthai;
            $param_macn = $macn;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                //Records created successfully. Redirect to landing page
                header("location: dashboard.php");
                exit();
            }else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        // Close statement
        $stmt->close();
    }
    //Close connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                <h2 class="mt-5">Create Record</h2>
                <p> Please fill this form and submit to add employee record to the database.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    <div class="form-group">
                        <label>Book ID</label>
                        <input type="text" name="ma_sach" class="form-control
                                <?php echo (!empty($ma_sach_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $ma_sach; ?>">
                        <span class="invalid-feedback"><?php echo $ma_sach_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Major ID</label>
                        <input type="text" name="macn" class="form-control
                                <?php echo (!empty($macn_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $macn; ?>">
                        <span class="invalid-feedback"><?php echo $macn_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Book Name</label>
                        <input type="text" name="tensach" class="form-control
                                <?php echo (!empty($tensach_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $tensach; ?>">
                        <span class="invalid-feedback"><?php echo $tensach_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Author</label>
                        <input type="text" name="tacgia" class="form-control
                                <?php echo (!empty($tacgia_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $tacgia; ?>">
                        <span class="invalid-feedback"><?php echo $tacgia_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Publish year</label>
                        <input type="text" name="namxb" class="form-control
                             <?php echo(!empty($namxb_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $namxb;  ?>">
                        <span class="invalid-feedback"><?php echo $namxb_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <input type="text" name="trangthai" class="form-control
                                <?php echo (!empty($trangthai_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $trangthai; ?>">
                        <span class="invalid-feedback"><?php echo $trangthai_err; ?></span>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="dashboard.php" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
