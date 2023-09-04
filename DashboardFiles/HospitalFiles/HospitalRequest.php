<?php
$userId = 1;

require_once '../classes/hospitalrequestclass.php';

use classes\hospitalrequestclass;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title> Hospital Request</title>


        <link rel="stylesheet" href="../../CSS/HospitalHSRequest.css">
        <style>
            .card4{
                display: flex;
                flex-direction: column;
                border-radius: 30px;
                border: 1px solid rgba(255, 255, 255, .25);
                background-color: white !important;
                box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
                height: 400px;
                padding: 20px;
                margin: 60px auto;
                margin-left: 250px;
                width :1250px;
                margin-left: 160px !important;
            }
        </style>
    </head>
    <body>

        <!-- nav bar start -->
        <div class="sticky-top bg-white shadownav" style="height: 50px;">
            <div class="row m-0 d-flex">
                <div class="col-8">
                </div>
                <div class="col-4">
                    <div class="row align-items-center">
                        <div class="col-2 mb-2">
                        </div>
                        <div class="col-2 mb-2">
                        </div>
                        <div class="col-2 mb-2">
                        </div>
                        <div class="col-6 mt-2 	d-none d-xl-block">
                            <b>Jaffna Blood Bank</b>
                            <p style="font-size: 10px;">Blood Bank</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- nav bar end -->

        <!-- Hospital Request start -->

        <div class="container">
            <?php
            if (isset($_GET['addreq']) && $_GET['addreq'] === 'Success') {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Indicates a successful or positive action.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
            } else {
                // Handle other cases here or leave it empty.
            }
            ?>
            <div class="mt-5 m-4 mb-2" style="color:gray;"> <h5>Create Request</h5></div>

            <div class="row bg-white m-3 pt-0  align-items-center justify-content-center rounded-5" style="height: 600px;">

                <div class="col-lg-6">
                    <div class="form-container">


                        <form action="../services/addrequest.php" method="POST">
                            <h2 class="container-title">Create Request</h2>
                            <label for="bloodGroup">Blood Group:</label>
                            <select class="form-control form-control-lg" name="bloodGroup" required>
                                <option selected>Select your Blood Group</option>
                                <option value="A+">A+</option>
                                <option value="A-"> A-</option>
                                <option value="B+"> B+</option>
                                <option value="B-"> B-</option>
                                <option value="O+"> O+</option>
                                <option value="O-"> O-</option>
                                <option value="AB+"> AB+</option>
                                <option value="AB-"> AB-</option>

                            </select>

                            <label for="bloodQuantity">Blood Quantity (ml):</label>
                            <input type="number"  name="bloodQuantity" required>
                            <label for="status">Status:</label>
                            <select class="form-control form-control-lg" name="requestStatus" required>
                                <option value="Normal">Normal</option>
                                <option value="Emergency">Emergency</option>
                                <option value="Urgent">Urgent</option>
                                <option value="Completed">Completed</option>
                            </select>
                            <br>

                            <br>
                            <input type="hidden" name="userID" value="<?php echo $userId; ?>" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" required>
                            <div class="text-end">
                                <button type="submit" class="btn btn-danger" >Send Request</button>
                                <button type="button" class="btn btn-danger">Cancel Request</button>
                            </div>
                        </form>

                    </div>
                </div>

                <div class="col-6 align-items-center justify-content-center">
                    <img class="d-none d-xl-block" src="../Images/hospitalreq.png"/>
                </div>
            </div>

            <div class="mt-5 m-4 mb-2" style="color:gray;"> <h5>Request</h5></div>
            <div class="row bg-white m-3 pt-0 align-items-center p-3 justify-content-start rounded-3 d-flex" >
                <?php
                $requestArray = hospitalrequestclass::getAllRequest();

                foreach ($requestArray as $datAarray) {
                    ?>
                    <div class="col">

                        <div class="bg-white p-3  m-3" style="width: 270px; height: 170px; box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px; background: <?php echo hospitalrequestclass::getHospitalStatusGradient($datAarray["requestStatus"]); ?>;">
                            <a href="../Dashboards/HospitalDashboard.php?page=hospitalreqview"style="text-decoration: none;">
                                <div class="row">
                                    <div class="col">
                                        <p class="m-b-0 text-white"  style="margin-top: 5px"><strong><?php echo $datAarray["hospitalRequestID"]; ?></strong><span class="f-right" style="margin-left:140px;font-weight: bold"><?php echo $datAarray["bloodGroup"]; ?></span></p>
                                    </div>
                                </div>
                                <div class="row align-items-center justify-content-center text-white">
                                    <div class="col">
                                        <?php echo $datAarray["bloodQuantity"]; ?>

                                    </div>  
                                    <div class="col">
                                        <img class="w-50" src="../Images/icons8-blood-100.png"/>
                                    </div>
                                </div>                        

                                <div class="row">
                                    <div class="col">
                                        <p class="m-b-0 text-white " ><?php echo $datAarray["createdDate"]; ?><span class="f-right" style="margin-left:30px;font-weight: bold"><?php echo $datAarray["requestStatus"]; ?></span></p> 
                                    </div>       
                                </div>
                            </a>   
                        </div>

                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
// put your code here
        ?>
    </body>
</html>
