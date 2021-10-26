<!doctype html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
        $(document).ready(function{
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
                    <h2 class="pull-left">FPT-APTECH LIBRARY</h2>
                </div>
                <div class="mt-5 mb-3 clearfix">
                    <div class="pull-left">
                        <form action="" method="get" >
                            <input type="text" style="height: 38px" class="pull-left" name="key" placeholder="Search..." value="
                           <?php if(isset($_GET["key"])) {echo $_GET["key"];}?>">

                            <input type="submit" value="search" class="btn btn-success pull-center">
                            <a href="dashboard.php" class="btn btn-success" >Display All</a>

                        </form>
                    </div>
                    <a href="create.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i>Add New Book</a>
                </div>

                <?php
                //include confif file
                require_once "config.php";

                //attempt select quey execution
                if(isset($_GET["key"]) && !empty($_GET["key"])){


                    $keyword=$_GET["key"];

                    $sql="select * from library where name ='%$keyword%' or title like '%$keyword%' or authorid like '%$keyword%'";
                }else {
                    $sql = "select * from library";
                }


                if ($result = $mysqli->query($sql)) {
                    if ($result->num_rows > 0) {
                        echo '<table class="table table-bordered table-striped">';
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>BookID</th>";
                        echo "<th>Name</th>";
                        echo "<th>AuthorID</th>";
                        echo "<th>Title</th>";

                        echo "<th>Pub Year</th>";
                        echo "<th>Availbale</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = $result->fetch_array()) {
                            echo "<tr>";
                            echo "<td>" . $row['bookid'] . "</td>";
                            echo "<td>" . $row['name']  . "</td>";
                            echo "<td>" . $row['authorid'] . "</td>";
                            echo "<td>" . $row['title'] . "</td>";

                            echo "<td>" . $row['pub_year']  . "</td>";
                            echo "<td>" . $row['availbale'] . "</td>";
                            echo "<td>";
                            echo '<a href="read.php?id=' . $row['bookid'] . '" class="mr-3"
                                                 title="View Recound" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                            echo '<a href="update.php?id=' . $row['bookid'] . '" class="mr-3"
                                                 title="Update Recound" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                            echo '<a href="delete.php?id=' . $row['bookid'] . '"
                                                 title="Delete Recound" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                        //free réult set
                        $result->free();

                    } else {
                        echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                    }
                } else {
                    echo "Oops! Something went wrong.Please try again later.";
                }

                $mysqli->close();


                //Close connection


                ?>

            </div>
        </div>

    </div>

</div>
</body>
</html>