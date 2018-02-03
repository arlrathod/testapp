<?php 


$db_host = "localhost"; //can be "localhost" for local development
$db_username = "root";
$db_password = "";
$db_name = "mychat";
$conn = new mysqli($db_host,$db_username,$db_password,$db_name) or die(mysqli_error());

$method = $_SERVER['REQUEST_METHOD'];

// Process only when method is POST
if($method == 'POST'){
	$requestBody = file_get_contents('php://input');
	$json = json_decode($requestBody);

	$text = $json->result->parameters->text;


	$qry = "SELECT * FROM tblmessage WHERE msg = $text";

    $result = mysqli_query($conn, $qry);

    $rowname = mysqli_fetch_array($result);

    $mid = $rowname["id"];

    $qry1 = "SELECT * FROM tblreply WHERE msgid = $mid order by RAND() LIMIT 1";

    $result1 = mysqli_query($conn, $qry1);

    $rowname1 = mysqli_fetch_array($result1);

/*
	switch ($text) {
		case 'hi':
			$speech = "Hello, how can i help you";
			break;

		case 'help':
		case 'i need help':
		case 'need information':
			$speech = "which coching help you need";
			break;

		case 'anything':
			$speech = "Yes, you can type anything here.";
			break;
		
		default:
			$speech = "Sorry, I didnt get that. Please ask me something else.";
			break;
	}
	*/
	$speech = $rowname1["msg"];

	$response = new \stdClass();
	$response->speech = $speech;
	$response->displayText = $speech;
	$response->source = "webhook";
	echo json_encode($response);
}
else
{
	echo "Method not allowed";
}

?>
