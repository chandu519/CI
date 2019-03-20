<?php
$hostname = 'localhost';
$database = 'vnr';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}


function getDayWisePricing($date){
	global $conn;
	try{
		if($date != ''){
			$stmt = $conn->prepare("SELECT * FROM tbl_pricing WHERE date=:date"); 
			$stmt->bindParam("date", $date,PDO::PARAM_STR) ;
			$stmt->execute();
			$count=$stmt->rowCount();
			$data=$stmt->fetchAll(PDO::FETCH_ASSOC);
			if($count){
				return $data;				
			}else{
				return false;
			}
		}				 
	}
	catch(PDOException $e) {
		return false;
		//echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function getUsersList(){
	global $conn;
	try{
			$stmt = $conn->prepare("SELECT * FROM tbl_users WHERE status=1"); 
			$stmt->execute();
			$count=$stmt->rowCount();
			$data=$stmt->fetchAll(PDO::FETCH_ASSOC);
			if($count){
				return $data;				
			}else{
				return false;
			}
						 
	}
	catch(PDOException $e) {
		//return false;
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function getProductsByCategory(){
	global $conn;
	try{
			$stmt = $conn->prepare("SELECT * FROM tbl_products WHERE status=1  ORDER BY category ASC"); 
			$stmt->execute();
			$count=$stmt->rowCount();
			$data=$stmt->fetchAll(PDO::FETCH_ASSOC);
			if($count){
				return $data;				
			}else{
				return false;
			}
						 
	}
	catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}	
}



function addinvoices($data){
	$invoicedate = $data['invoicedate'];
	$user_info = isset($data['user_info'])?$data['user_info']:'';
	$exch_prod_type = isset($data['exch_prod_type'])?$data['exch_prod_type']:'';
    $exch_weight = isset($data['exch_weight'])?$data['exch_weight']:'';
    $exch_percentage = isset($data['exch_percentage'])?$data['exch_percentage']:'';
    $overall_price = isset($data['overall_price'])?$data['overall_price']:'';
    $discount_price = isset($data['discount_price'])?$data['discount_price']:'';
    $total_after_discount = isset($data['total_after_discount'])?$data['total_after_discount']:'';
    $paid_amount = isset($data['paid_amount'])?$data['paid_amount']:'';
    $due_amount = isset($data['due_amount'])?$data['due_amount']:'';
    $exc_price = isset($data['exch_price'])?$data['exch_price']:'';


    $weight = isset($data['weight'])?$data['weight']:'';
    $prod_type = isset($data['prod_type'])?$data['prod_type']:'';
    $qty = isset($data['qty'])?$data['qty']:'';
    $stoneWeight = isset($data['stoneWeight'])?$data['stoneWeight']:'';
    $mkgCharges = isset($data['mkgCharges'])?$data['mkgCharges']:'';
    $prodTotal = isset($data['prodTotal'])?$data['prodTotal']:'';

    //echo count($data['weight']);exit;

	try{
		global $conn;
		$stmt = $conn->prepare('INSERT INTO tbl_invoces SET user_info=:user_info, discount=:discount_price, exc_cat_type=:exch_prod_type, exc_gross_weight=:exch_weight, exc_price=:exc_price, paid_amount=:paid_amount, due_amount=:due_amount, date=:invoicedate, total_amount=:total_after_discount, dt_created=current_date(),status=1');

/*echo "INSERT INTO tbl_invoces SET user_info=$user_info, discount=$discount_price, exc_cat_type=$exch_prod_type, exc_gross_weight=$exch_weight, exc_price=$exc_price, paid_amount=$paid_amount, due_amount=$due_amount, date=$invoicedate, total_amount=$total_after_discount, date_created=current_date(),status=1";*/
		$stmt->bindParam(':user_info', $user_info); 
		$stmt->bindParam(':discount_price', $discount_price); 
		$stmt->bindParam(':exch_prod_type', $exch_prod_type); 
		$stmt->bindParam(':exch_weight', $exch_weight); 
		$stmt->bindParam(':exc_price', $exc_price); 
		$stmt->bindParam(':paid_amount', $paid_amount); 
		$stmt->bindParam(':due_amount', $due_amount);
		$stmt->bindParam(':total_after_discount', $total_after_discount);
		$stmt->bindParam(':invoicedate', $invoicedate);
		$stmt->execute();-
	  	$invoice_id = $conn->lastInsertId();



	  	$sql = 'INSERT INTO tbl_invoice_info (invoice_id, product_id, gross, qty, making_charges, stone_weight, price_of_product) VALUES ';
		$insertQuery = array();
		$insertData = array();
		for($i=0;$i<count($data['weight']);$i++) {
		    $insertQuery[] = '(?,?,?,?,?,?,?)';
		    $insertData[] = $invoice_id;
		    $insertData[] = $data['prod_type'][$i];
		    $insertData[] = $data['weight'][$i];
		    $insertData[] = $data['qty'][$i];
		    $insertData[] = $data['mkgCharges'][$i];
		    $insertData[] = $data['stoneWeight'][$i];
		    $insertData[] = $data['prodTotal'][$i];		    
		}

		if (!empty($insertQuery)) {
		    $sql .= implode(', ', $insertQuery);
		    $stmt = $conn->prepare($sql);
		    $stmt->execute($insertData);
		}

		return true;
  	}catch(PDOException $e){  		
  		echo '{"error":{"text":'. $e->getMessage() .'}}';
  		return false;
  	}
}
