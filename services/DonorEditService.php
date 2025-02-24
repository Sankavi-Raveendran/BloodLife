<?php
namespace classes;

require_once '../classes/Donor.php';
 require_once  '../classes/district.php';
 require_once '../classes/Validation.php';

use classes\district;
use classes\Donor;
use classes\Validation;

$status = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    session_start();

    if (isset($_POST["VerificationCode"], $_SESSION["VerificationCode"], $_SESSION["donor"], $_SESSION["email"], $_SESSION['timestamp'])) {
        //check status for adding donor

        $verifyOtp = (int) $_POST["VerificationCode"] === (int) $_SESSION["VerificationCode"];
        $time = time() - $_SESSION['timestamp'] > 60000000;

        if ($verifyOtp) {
            
            $status = $_SESSION["donor"]->AddDonor($_SESSION["email"]) ? 1 : 2;
            session_destroy();
            
        } else {
            unset($_SESSION["VerificationCode"]);
            unset($_SESSION['timestamp']);
            $status = !$verifyOtp ? 3 : (!$time ? 4 : 5);

        }
    } else if (isset($_POST["name"], $_POST["bloodGroup"], $_POST["dob"],
                    $_POST["nic"], $_POST["contactNumber"], $_FILES["medicalReport"],
                    $_POST["district"], $_POST["division"], $_POST["token"],
                    $_POST["email"],$_POST["donorId"])) {

        //empty value check
        if (!empty($_POST["name"]) && ( $_POST["bloodGroup"]) && ($_POST["dob"]) &&
                ($_POST["nic"]) && ($_POST["contactNumber"]) && ($_FILES["medicalReport"]) &&
                ($_POST["district"]) && ($_POST["division"]) && ($_POST["token"]) && ($_POST["email"])&& ($_POST["donorId"])) {

            // sanitizing the inputs
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $bloodGroup = filter_var($_POST['bloodGroup'], FILTER_SANITIZE_STRING);
            $dob = filter_var($_POST['dob'], FILTER_SANITIZE_STRING);
            $contactNumber = filter_var($_POST['contactNumber'], FILTER_SANITIZE_STRING);
            $nic = filter_var($_POST['nic'], FILTER_SANITIZE_STRING);
            $medicalReport = file_get_contents($_FILES['medicalReport']['tmp_name']);
            $token = filter_var($_POST['token'], FILTER_SANITIZE_STRING);
            $UserName = filter_var($_POST['UserName'], FILTER_SANITIZE_STRING);
            $district = filter_var($_POST['district'], FILTER_SANITIZE_STRING);
            $division = filter_var($_POST['division'], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $donorId = filter_var($_POST['donorId'], FILTER_SANITIZE_NUMBER_INT);
            $districtId = district::getDistrictIDDD($district, $division);

            //create user object with token
            $user = new User(null, null, null, null, null, $token, null, null, null, null);

            //validations
            $nicExist = Validation::validateAlreadyExist("nic", $nic, "donor");
            $emailExist = Validation::validateAlreadyExist("email", $email, "user");
     
            $validateEmail = Validation::validateGmail($email);
            $validatePhoneNumber = Validation::validateContactNumber($contactNumber);
            $validateNic = Validation::validateSriLankanNIC($nic);
            $validateDob = Validation::validateDOB($dob);
            $validateBloodGroup = Validation::validateBloodGroup($bloodGroup);
            $validateToken = $user->validateToken();
            $bloodBankId = $user->getBloodBankId();

            //token and bloodbank id checking
            if ($validateToken && $bloodBankId != null) {


                //email,phonenumber,nic,dob,bloodgroup valitaion check
                if ($validateEmail && $validatePhoneNumber && $validateNic && $validateDob && $validateBloodGroup) {

                    //nic,email,username exist check in db
                    if (!$nicExist && !$emailExist) {

                        //create donor object
                        $donor = new Donor(null, $name, $bloodGroup, $dob, $contactNumber, $nic, null, null, null, null, $medicalReport, null, $bloodBankId, $districtId);

                        $_SESSION["VerificationCode"] = Validation::generateOTP();
                        $_SESSION["donor"] = $donor;
                        $_SESSION["email"] = $email;
                        $_SESSION['timestamp'] = time();
                        //$status = $user->SendMail($_SESSION["VerificationCode"], $email, $name) ? header("Location: newEmptyPHPWebPage.php") : 7;

                    } else {
                        //check status for exist values
                        $status = $nicExist ? 8 : ($emailExist ? 9 : ($userNameExist ? 10 : 11));
                    }
                } else {
                    //check status for valitations
                    $status = !$validateEmail ? 12 : (!$validatePhoneNumber ? 13 : (!$validateNic ? 14 : (!$validateDob ? 15 : (!$validateBloodGroup ? 16 : 17))));
                }
            } else {
                //status for not valid token
                $status = 18;
            }
        } else {
            //status for empty value
            $status = 19;
        }
    } else {
        //status for isset value
        $status = 20;
    }
} else {

    echo"Invalid request method";
}

echo $status;
