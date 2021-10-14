<?php
session_start();
if(!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !==true) {
    header("location:login.php");
    exit();
}
?>
<?php
//include config file
require_once "../config.php";

$mabd = $tenbd =  $diachi = "";
$mabd_err = $tenbd_err = $diachi_err = "";

//processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate mabd
    if(empty(trim($_POST["mabd"]))){
        $mabd_err = "Please enter a Reader code.";
    }elseif(strlen(trim($_POST["mabd"])) !== 5){
        $mabd_err = "Please enter a valid Reader code.";
    }else{
        $mabd = trim($_POST["mabd"]);
    }

    //validate tenbd
    $input_tenbd=trim($_POST["tenbd"]);
    if(empty($input_tenbd)) {
        $name_err = "Please enter a Reader name.";
    }else{
        $name=$input_tenbd;
    }

    //validate address
    if(empty(trim($_POST["diachi"]))){
        $address_err="Please enter an address.";
    }else{
        $address=trim($_POST["diachi"]);
    }

    //check input errors before inserting in database
    if(empty($mabd_err) && empty($tenbd_err) && empty($diachi_err)){
        //prepared an insert statement
        $sql = "INSERT INTO bandoc(mabd,tenbd,diachi) values (?,?,?)";

        if($stmt =$mysqli->prepare($sql)){
            //bind variables to the prepared statement as parameter
            $stmt->bind_param("sss",$param_mabd,$param_tenbd,$param_diachi);

            //set parameters
            $param_mabd = $mabd;
            $param_tenbd = $tenbd;
            $param_diachi = $diachi;

            if($stmt->execute()){
                header("location: dashboard.php");
                exit();
            }else{
                echo "Oops! Something went wrong.Please try again later.";
            }
        }
        //close statement
        $stmt->close();
    }
    //close connection

    $mysqli->close();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
            <div class="col-ml-12">
                <h2 class="mt-5">Create Record</h2>
                <p>Please fill this form and submit to add employee record to the database.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label for="#">Reader Code</label>
                        <input type="text" name="mabd" class="form-control
                            <?php echo (!empty($mabd_err)) ? 'is-invalid' : '';?>" value="<?php echo $mabd;?>">
                        <span class="invalid-feedback"><?php echo $mabd_err;?></span>
                    </div>
                    <div class="form-group">
                        <label for="#">Reader Name</label>
                        <textarea name="tenbd" class="form-control
                              <?php echo (!empty($tenbd_err)) ? 'is-invalid' : '';?>" value="<?php echo $tenbd;?>"></textarea>
                        <span class="invalid-feedback"><?php echo $tenbd_err;?></span>
                    </div>
                    <div class="form-group">
                        <label for="#">Address</label>
                        <input type="text" name="diachi" class="form-control
                            <?php echo (!empty($diachi_err)) ? 'is-invalid' : '';?>" value="<?php echo $diachi;?>">
                        <span class="invalid-feedback"><?php echo $diachi_err;?></span>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="dashboard.php" class="btn btn-secondary m1-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>

</div>
</body>
</html>
