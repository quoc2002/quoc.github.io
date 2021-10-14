<?php
session_start();
if(!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !==true){
    header("location:login.php");
    exit();
}
?>
<?php
require_once "../config.php";

$macn=$tencn="";
$macn_err=$tencn_err="";

if(isset($_POST["macn"]) && !empty($_POST["macn"])){

    //validate
    if(empty(trim($_POST["macn"]))){
        $name_err="Please enter a specialized code.";
    }elseif (strlen($_POST["macn"]) !== 5){
        $name_err="Please enter a specialized code.";
    }else{
        $name=trim($_POST["macn"]);
    }

    if(empty(trim($_POST["tencn"]))){
        $tencn_err="please enter an specialized name.";
    }else{
        $address=trim($_POST["tencn"]);
    }

    if(empty($macn_err) && empty($tencn_err)){
        $sql="update chuyennganh set tencn=? where macn=?";


        if($stmt=$mysqli->prepare($sql)){
            $stmt->bind_param("ss",$param_tencn,$param_macn);
            $param_macn = $macn;
            $param_tencn = $tencn;

            if($stmt->execute()){
                header("location:specialized.php");
                exit();
            }else{
                echo "Oops! Something went wrong.Please try again later.";
            }
        }
        $stmt->close();

    }

    $mysqli->close();
}else{
    //check existence of id parameter before processing further
    if(isset($_GET["macn"]) && !empty(trim($_GET["macn"]))){
        $sql="select * from chuyennganh where macn=?";
        global $mysqli;
        if($stmt=$mysqli->prepare($sql)){
            $stmt->bind_param("i",$param_macn);
            $param_macn = $macn;
            if($stmt->execute()){
                $result=$stmt->get_result();
                if($result->num_rows==1){
                    /* fetch result row as an assoclative array.Since the result set contains only are row,
                    we dont't need to use while loop
                    */
                    $row=$result->fetch_array(MYSQLI_ASSOC);
                    //retrieve individual field value
                    $macn=$row["macn"];
                    $tencn=$row["tencn"];
                }
            }else{
                echo "Oops! Something went wrong.Please try again later.";
            }
        }
        $stmt->close();
        $mysqli->close();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
                <h2 class="mt-5">Update Specialized</h2>
                <p>Please edit the input values and submit to update the specialized.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER["REQUEST_URI"]));?>" method="post">
                    <div class="form-group">
                        <label for="#">Specialized Code</label>
                        <input type="text" name="macn" class="form-control
                            <?php echo (!empty($macn_err)) ? 'is-invalid' : '';?>" value="<?php echo $macn;?>">
                        <span class="invalid-feedback"><?php echo $macn_err;?></span>
                    </div>

                    <div class="form-group">
                        <label for="#">Specialized Name</label>
                        <textarea name="tencn" class="form-control
                              <?php echo (!empty($tencn_err)) ? 'is-invalid' : '';?>" value="<?php echo $tencn;?>"></textarea>
                        <span class="invalid-feedback"><?php echo $tencn_err;?></span>
                    </div>
                    <!--<input type="hidden" name="macn" value="<?php echo $macn?>"/>-->
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="specialized.php" class="btn btn-secondary m1-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>