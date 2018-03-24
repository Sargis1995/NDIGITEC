<?php
header('Content-Type: application/json');//set header for json output

//require once files
require_once 'config.php';
require_once 'model.php';
require_once 'controller.php';

//init controller
$products_arr = new Controller();



//check if method get and return data if method not get return error
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	http_response_code(200);
    echo json_encode(
		$products_arr->array_reconstruct(
				(isset($_REQUEST['mqty']) ? (int)$_REQUEST['mqty'] : 1),
				(isset($_REQUEST['sort']) ? (int)$_REQUEST['sort'] : 1)
			)
	);
} else {
	http_response_code(412);
    echo json_encode(array("error" => "method must be get"));
}
