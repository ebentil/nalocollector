<?php
session_start();
include_once 'core/connection.php';

$paymentClient = $_POST['paymentClient'];
$paymentClientN = $_POST['paymentClientN'];
$paymentType = $_POST['paymentType'];
$paymentAmount = $_POST['paymentAmount'];
$receivedBy = $_SESSION['agent'];

$result_msisdn = mysqli_query($con, "select mobile from clients where clientid='$paymentClient' LIMIT 1;");
$fetchMsisdn = mysqli_fetch_assoc($result_msisdn);
$clientMobile = $fetchMsisdn['mobile'];

$clientPart = $paymentClientN[0] . $paymentClientN[1];
$prefix = 'NAZL' . $clientPart;
$transactionID = uniqid($prefix);

$msg = "Dear $paymentClientN, you have just made a $paymentType payment of $paymentAmount for Zoomlion service. Your transaction ID is $transactionID";
$message = urlencode($msg);

$result_verify = mysqli_query($con, "select * from clients where clientid='$paymentClient'");
if (mysqli_num_rows($result_verify) > 0) {
	$result_payment = mysqli_query($con, "insert into payments (client,clientname,type,amount,receivedby)values('$paymentClient','$paymentClientN','$paymentType','$paymentAmount','$receivedBy');");
	if ($result_payment) {
		$filename = "http://216.224.161.207/api/bulksms/?username=zoomlionmobile&password=zoomie&type=0&dlr=1&destination=$clientMobile&source=ZOOMLION&message=$message";
		file_get_contents($filename);
		echo "Operation Successful";
	} else {
		echo "Operation Unsucessful" . mysqli_error($con);
	}
} else {
	echo "Invalid ClientID";
}
?>