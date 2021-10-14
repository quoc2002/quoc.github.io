<?php
session_start();
if(!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !==true) {
    header("location: login.php");
    exit();
}
?>
<?php
//include config file
require_once "../config.php";
// macn : specializedCode tencn: specializedName
$macn = $tencn = "";
$macn_err = $tencn_err = "";
//processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // validate specialized code
    if(empty(trim($_POST["macn"]))){
        $macn_err="Please enter a specialized code.";
    }elseif (strlen(trim($_POST["macn"])) !== 5){
        $macn_err="Please enter a valid specialized code.";
    }else{
        $macn = trim($_POST["macn"]);
    }

    //validate specializedName
    if(empty(trim($_POST["tencn"]))) {
        $tencn_err = "Please enter a specialized name.";
    }else {
        $tencn =  trim($_POST["tencn"]);
    }


    //check input errors before inserting in database
    if(empty($macn_err) && empty($tencn_err)){
        //prepare an insert statement
        $sql="INSERT INTO chuyennganh (macn,tencn) VALUES (?,?)";
        global $mysqli;
        if($stmt=$mysqli->prepare($sql)){
            //bind variables to the prepared statement as parameters
            $stmt->bind_param("ss",$param_macn,$param_tencn);
            //set parameters
            $param_macn = $macn;
            $param_tencn = $tencn;

            //attempt to execute the prepared statement
            if($stmt->execute()){
                //records created successfully.rect to landing page
                header("location: specialized.php");
                exit();
            }else{
                echo "Oops! Something went wrong.Please try again later.";
            }
        }$stmt->close();
    }
    global $mysqli;
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
                <h2 class="mt-5">Create Specialized</h2>
                <p>Please fill this form and submit to add specialized record to the database.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label for="#">Specialized code</label>
                        <input type="text" name="macn" class="form-control
                            <?php echo (!empty($macn_err)) ? 'is-invalid' : '';?>"
                               value="<?php echo $macn;?>">
                        <span class="invalid-feedback"><?php echo $macn_err;?></span>
                    </div>
                    <div class="form-group">
                        <label for="#">Specialized name</label>
                        <textarea name="tencn" class="form-control
                              <?php echo (!empty($tencn_err)) ? 'is-invalid' : '';?>"
                                  value="<?php echo $tencn;?>"></textarea>
                        <span class="invalid-feedback"><?php echo $tencn_err;?></span>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="specialized.php" class="btn btn-secondary m1-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>

</div>
</body>
</html>
