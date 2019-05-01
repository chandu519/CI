<?php include_once "includes/config.php"; 
	include_once "includes/loginAuth.php"; 
	include_once "includes/model.php";
	//$_SESSION['stat'] = "fdsafdsaf";  
	if(!empty($_POST)){
		$result = addinvoices($_POST);
		echo $result."";
		if($result == true){
			$_SESSION['stat'] = 'Invoice Submitted Successfully.';
			header('Location:addbills.php');
			exit;
		}
		if($result == false){
			$_SESSION['stat'] = 'Error, Unable to submit Invoice.';
			header('Location:addbills.php');
			exit;
		}
	}
	$date = (@$_REQUEST['date'] != '') ? @$_REQUEST['date'] : date('Y-m-d');
	$priceData = getDayWisePricing($date);
	//print_r($priceData);

	$userdata = getUsersList();
	$proditems = getProductsByCategory();
	// echo "<pre>";print_r($proditems);
	// exit;

	$prodItemData = json_encode($proditems);

?>

<!doctype html>
<html>
	<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script> 
<style>
    .prodheadrow {
      width: 100%;
      padding: 10px 0;
      border: 1px solid #ccc;
      background: #777;
      color: #fff;
      font-size: 16px;
    }

    .prodheadrow .column {
      border-right: 1px solid #aaa;
    }

    .prodheadrow .column:last-child {
      border: none;
    }

    .prodrow {
      width: 100%;
      padding: 10px 0;
      border: 1px solid #ccc;
    }

    .prodrow:nth-child(2n+1) {
      background: #f5f5f5;
    }

    .disabled {
      background: #f0f0f0;
    }

    .otherinfo {
      padding: 20px 0px;
      border: 1px solid #ddd;
      width: 100%;
      margin-left: -15px;
      background: #f9f9f9;
    }

    .otherinfo>.row {
      margin: 10px 0;
    }

    .noPricing{
    	width: 100%;
    	float: left;
    	text-align: center;
    	padding: 20px 15px;
    	line-height: 32px;
    	font-size: 16px;
    	color: #ff0000;
    }
    .noPricing a{
    	cursor: pointer;
    	color: #fff;
    	text-decoration: none;
    }
    /* .addBtn,.removeBtn{ font-size: 18px; } */
  </style>
		<style type="text/css">
			.address{
				float: left;
			    width: 100%;			    
			    border: 1px solid #ddd;
			    border-radius: 3px;
			    padding: 8px 10px;
			    font-size: 16px;
			    color: #333;
			    margin-bottom: 15px;
			}
			.addBtn{ 
				padding: 3px 5px; 
				background:green; 
				color: #fff; 
				border:0px; 
				height: 20px; 
				line-height: 15px; 
				cursor: pointer;
				display: block;
				margin-bottom: 2px;  
			}
			.bigBtn{
				padding: 5px 8px;
				height: 30px; 
				line-height: 22px;
				margin-top:5px; 
			}
			.removeBtn{
				padding: 3px 7px; 
				background:#ff0000; 
				color: #fff; 
				border:0px; 
				height: 20px; 
				line-height: 15px; 
				cursor: pointer;
				display: block;
			}
		</style>
		<!-- META/CSS/JS DATA -->
		<?php include "includes/ui_meta_tags.php"; ?>                
	</head>
	<body>
		<?php include_once "includes/ui_header.php"; ?>
	<?php include_once "includes/ui_editor1.php"; ?>
    <div class="wrapper content_box">
			<div class="wrapper_inner">
				<div class="wrapper">										
					<?php include_once "includes/admin_menu.php"; ?>    					
					<div class="content_block">	
						<div class="wrapper title">
                        <h1>Invoice</h1>                       
                    </div>

						<?php if(isSet($_SESSION['stat'])){ ?>
						<div class="success_msg"><?php echo $_SESSION['stat']; ?> <span class="msgC">x</span></div>
						<?php unset($_SESSION['stat']);
						 } ?>

						<div class="wrapper table form_upload">								
							 <form method="post" name="prodForm">
      <!-- Declare two inputs for today price -->
      <input type="hidden" name="gold_price" id="gold_price" value="<?php echo $priceData[0]['gold']; ?>" />
      <input type="hidden" name="silver_price" id="silver_price" value="<?php echo $priceData[0]['silver']; ?>" />
      <!-- End of price decalration -->
      <div class="userinfo">
        <!-- <div class="row prodheadrow">
          <div class="col-sm-2 column">User Information</div>
        </div> -->
        <div class="row prodrow">
          <div class="col-sm-12 text-center">
          		<div class="col-sm-3">

          		</div>
          		<div class="col-sm-3">
          			    <input type="text" name="invoicedate" class="form-control" id="datepicker" placeholder="Select Date" value="<?php echo $date; ?>" onChange="gotoPage(this.value)" autocomplete="off">		                
          		</div>
          		<div class="col-sm-3">
	          		<select name="user_info" class="form-control">
		              <option value="">Select User</option>
		              <?php foreach ($userdata as $k => $usrvalue){?>
		              <option value="<?php echo $usrvalue['user_id'];?>"  ><?php echo $usrvalue['name']; ?> - <?php echo $usrvalue['phone'];?></option>
		          	  <?php } ?>	              
		            </select>
		        </div>
          </div>
          
          <?php if(!empty($priceData) && $priceData != false){ ?>
          <div class="col-sm-12 text-center" style="background:#999; color: #fff; padding: 5px; font-size: 18px; margin: 10px 0 "> 
          		<div class="col-sm-6 text-right">
          			Gold : <mark><?php echo $priceData[0]['gold'] != '' ? $priceData[0]['gold'] : 0; ?></mark>
          		</div>
          		<div class="col-sm-6 text-left">
          			Silver : <mark><?php echo $priceData[0]['silver'] != '' ? $priceData[0]['silver'] : 0; ?></mark>
          		</div>
          </div>
      <?php } ?>

        </div>
      </div>

      
      <?php if(!empty($priceData) && $priceData != false){ ?>
      <div class="prodlist">

        <div class="row prodheadrow">
          <div class="col-sm-2 column">Product Name</div>
          <div class="col-sm-2 column">Gross/Weight</div>
          <div class="col-sm-1 column">Quantity</div>
          <div class="col-sm-2 column">Stone Weight </div>
          <div class="col-sm-2 column">Making Charges</div>
          <div class="col-sm-2 column">Product Total Price</div>
        </div>

        <div class="row prodrow">
          <div class="col-sm-2">
            <select name="prod_type[]" class="form-control">
              <option value="">Select Product</option>
              <?php foreach ($proditems as $key => $Itemvalue) {?>             	
              
                <option value="<?php echo $Itemvalue['inc_id'];?>"><?php echo $Itemvalue['category'];?> - <?php echo $Itemvalue['prod_name'];?></option>
            <?php } ?>
                
            </select>
            <input type="hidden" name="totalgross[]" value="0">
            <!-- <input type="hidden" name="category[]" value=""> -->
          </div>

          <div class="col-sm-2">
            <input type="text" name="weight[]" value="" class="form-control disabled" placeholder="Gross/Weight" readonly>
          </div>
          <div class="col-sm-1">
            <input type="number" min="0" name="qty[]" value="" class="form-control disabled" readonly placeholder="Qty">
          </div>
          <div class="col-sm-2">
            <input type="text" name="stoneWeight[]" value="" class="form-control disabled" readonly placeholder="Stone Weight">
          </div>

          <div class="col-sm-2">
            <input type="text" name="mkgCharges[]" value="" class="form-control disabled" readonly placeholder="Making Charges">
          </div>

          <div class="col-sm-2">
            <input type="text" name="prodTotal[]" value="0.00" readonly class="form-control disabled" placeholder="Total Price">
          </div>
          <div class="col-sm-1">
            <button type="button" class="btn btn-primary addBtn bigBtn">+</button>
            <!-- <button type="button" class="btn btn-primary removeBtn">-</button> -->
          </div>
        </div>
      </div>


      <div class="userinfo">
        <div class="row prodheadrow">
          <div class="col-sm-12 column">Old/Exchange Product Information</div>
        </div>
        <div class="row prodrow">
          <div class="col-sm-3">
            <select name="exch_prod_type" class="form-control">
              <option value="">Select Product Type</option>
              <option value="Gold">Gold</option>
              <option value="Silver">Silver</option>
            </select>
          </div>

          <div class="col-sm-3">
            <input type="text" name="exch_weight" value="" class="form-control disabled" readonly placeholder="Gross/Weight">
          </div>

          <div class="col-sm-3">
             <input type="text" name="exch_percentage" value="" class="form-control disabled" readonly placeholder="Percentage" >
          </div>

          <div class="col-sm-3">
            <input type="text" name="exch_price" value="" class="form-control" placeholder="Price" readonly>
          </div>


        </div>
      </div>



      <div class="otherinfo">

        <div class="row">
          <div class="col-sm-5"></div>
          <div class="col-sm-2">
            <input type="text" name="overall_price" id="overall_price" value="" class="form-control" placeholder="Total Amount" readonly>
            <span style="position: absolute; top:-5px; right:-5px;font-size: 28px;">-</span>
          </div>
          <div class="col-sm-2">
            <input type="text" name="discount_price" id="discount_price" value="" class="form-control" placeholder="Discount">
            <span style="position: absolute; top:-2px; right:-5px;font-size: 28px;">=</span>
          </div>
          <div class="col-sm-3">
            <input type="text" name="total_after_discount" id="total_after_discount" value="" class="form-control" placeholder="Total Price after Discount"
              readonly>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-9"></div>
          <div class="col-sm-3">
            <input type="text" name="paid_amount" id="paid_amount" value="" class="form-control" placeholder="Paid Amount">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-9"></div>
          <div class="col-sm-3">
            <input type="text" name="due_amount" id="due_amount" value="" class="form-control" placeholder="Due Amount" readonly>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-12">
            <div class="pull-right">
              <!-- <button type="button" class="btn btn-default" id="print">Print</button> -->
              <button type="submit" class="btn btn-primary" id="save">Save</button>
              <button type="clear" class="btn btn-danger">Clear</button>
            </div>
          </div>
        </div>
      </div>


  <?php }else{ ?>
  	<div class="noPricing">
  		<p>You haven't added any pricing on selected date. <br> 
  			Please add pricing for selected date here <a href="addpricing.php" class="btn btn-primary">Add Pricing</a> </p>
  	</div>
  <?php } ?>

    </form>
						</div>    
						
				
				</div>
			</div>
    </div>		
    

						<?php include_once "includes/ui_footer.php"; ?>  

	</body>
<!-- 
<select name="prod_name[]" ><optgroup label="Gold"><option value="1">100gr</option><option value="2">200gr</option><option value="3">300gr</option></optgroup><optgroup label="Silver"><option value="4">100gr</option><option value="5">200gr</option><option value="6">300gr</option></optgroup></select> -->
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  
	<script type="text/javascript">

	function gotoPage(date){
		window.location = "addbills.php?date="+date;
	}	

    $("#print").click(function () {      
      window.print();      
    })

    $(".msgC").click(function () {      
      $(".success_msg").hide();      
    })

	

	const prodItemData = <?php echo $prodItemData; ?>;
	function getProdItemGross(value){
		for (var i = 0 ; i < prodItemData.length; i++) {
    		if(prodItemData[i].inc_id == value){
    			var returnObj = {'gross':prodItemData[i].gross, 'category':prodItemData[i].category};
    			return returnObj;
    		}
    	}
    	return false;	
	} 


    $(document).ready(function () {
	
		
		

		
  		    	


    		
	    $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	    $(document).on('click', '.addBtn', function (e) {
        	$(".prodlist").append('<div class="row prodrow"><div class="col-sm-2"><select name="prod_type[]" class="form-control"><option value="">Select Product</option><?php foreach ($proditems as $key => $Itemvalue) {?><option value="<?php echo $Itemvalue['inc_id'];?>"><?php echo $Itemvalue['category'];?> - <?php echo $Itemvalue['prod_name'];?></option><?php } ?></select><input type="hidden" name="totalgross[]" value="0"></div><div class="col-sm-2"><input type="text" name="weight[]" class="form-control" value="" placeholder="Gross/Weight" readonly ></div><div class="col-sm-1"><input type="text" class="form-control" name="qty[]" value="" readonly placeholder="Qty" ></div><div class="col-sm-2"><input type="text" class="form-control" name="stoneWeight[]" value="" placeholder="Stone Weight" readonly ></div><div class="col-sm-2"><input type="text" name="mkgCharges[]" value="" class="form-control disabled" readonly placeholder="Making Charges"></div><div class="col-sm-2"><input type="text" class="form-control disabled" name="prodTotal[]" value="0.00" readonly placeholder="Total Price" ></div><div class="col-sm-1"><button type="button" class="btn btn-primary addBtn">+</button><button type="button" class="btn btn-danger removeBtn">-</button></div></div></div>');
      });

      $(document).on('click', '.removeBtn', function (e) {
        $(this).parent().parent().remove();
      });

      $("#save").click(function () {
        //event.preventDefault();
        var lngtxt = $('input[name="weight[]"]').length;
        var error = [];
        if (lngtxt == 0) {
          error['err'] =  "Error with document";
        } else {

          var prodInput = document.getElementsByName('prod_type[]');
          var weightInput = document.getElementsByName('weight[]');
          var qtyInput = document.getElementsByName('qty[]');
          var mkgInput = document.getElementsByName('mkgCharges[]');
          var stnInput = document.getElementsByName('stoneWeight[]');
          var prodTotalInput = document.getElementsByName('prodTotal[]');
          


          for (let i = 0; i < prodInput.length; i++) {
            //console.log(eduInput[i].value);                        
            if (prodInput[i].value === null || prodInput[i].value === undefined || prodInput[i].value === '') {
              error['err'] = "Please select product" + i;
            }
            if (weightInput[i].value === null || weightInput[i].value === undefined || weightInput[i].value === '') {
              error['err'] = "Please Enter Weight" + i;
            }
            if (qtyInput[i].value === null || qtyInput[i].value === undefined || qtyInput[i].value === '') {
              error['err'] = "Please Enter Quantity" + i;
            }
            if (prodTotalInput[i].value === null || prodTotalInput[i].value === undefined || prodTotalInput[i].value === '') {
              error['err'] = "Total should be greater than 0" + i;
            }

            if (stnInput[i].value >= weightInput[i].value && weightInput[i].value > 0) {
              error['err'] = "Stone Weight should be less than Product weight ";
              alert("Stone Weight should be less than Product weight 2");
              return false;
            }

          }

          $("#prodForm").submit();
        }


        if (Object.keys(error).length > 0) {
          alert("Enter all fields");
          return false;
        }else{
        	$("#prodForm").submit();
        }
      });

    })



    $(document).on('change', 'select[name="exch_prod_type"]', function (e) {
      var thisdiv = $(this).parent().parent()[0];
      if ($(this).val() == '') {
        $(thisdiv).find('input[name!="exch_price"]').addClass('disabled');
        $(thisdiv).find('input[name!="exch_price"]').prop('readonly', true);
      } else {
        $(thisdiv).find('input[name!="exch_price"]').removeClass('disabled');
        $(thisdiv).find('input[name!="exch_price"]').removeAttr('readonly');
      }
    });


    $(document).on('change', 'select[name="prod_type[]"]', function (e) {
      var thisdiv = $(this).parent().parent()[0];
      if ($(this).val() == '') {
        $(thisdiv).find('input[name!="prodTotal[]"]').addClass('disabled');
        $(thisdiv).find('input[name!="prodTotal[]"]').prop('readonly', true);
      } else {
        $(thisdiv).find('input[name!="prodTotal[]"]').removeClass('disabled');
        $(thisdiv).find('input[name!="prodTotal[]"]').removeAttr('readonly');
      }

   //    var data = {};
	  // data.prod_type =  $(thisdiv).find('select[name="prod_type"]').val();
	  // data.weight =  $(thisdiv).find('input[name="weight"]').val();
	  // data.qty =  $(thisdiv).find('input[name="qty"]').val();
	  // console.log($(thisdiv).find('select[name="prod_type"]').val(),"data");
   //    checkProductAvailability(data);

      calculatePrice();
      // $('input[name!="prodTotal[]"]').addClass('disabled');
      // $('input[name!="prodTotal[]"]').prop('readonly', true);
    });


    function calculatePrice() {

      
      //get Today Total Price values for Gold and silver
      var gold_price = $("#gold_price").val();
      var silver_price = $("#silver_price").val();

      var prodInput = document.getElementsByName('prod_type[]');
      var overallPrice = 0;

      var selectedProd = [];
      //Loop the elements
      for (let i = 0; i < prodInput.length; i++) {

      	if(!selectedProd.includes(prodInput[i].value)){
      		selectedProd.push(prodInput[i].value);
      	}else{
      		prodInput[i].value = '';
      		alert("You are not allowed to select product for multiple times again.");
      		return false;
      	}

        let weightInput = document.getElementsByName('weight[]')[i].value;
        let qtyInput = document.getElementsByName('qty[]')[i].value;
        let mkgInput = document.getElementsByName('mkgCharges[]')[i].value;
        let stnInput = document.getElementsByName('stoneWeight[]')[i].value;
        let prodTotalInput = document.getElementsByName('prodTotal[]')[i].value;
        let prodTotalGross = document.getElementsByName('totalgross[]')[i].value;
        //let prodCategory = document.getElementsByName('category[]')[i].value;

        stnInput = (stnInput == '') ? 0 : makeToFixed(stnInput);
        qtyInput = (qtyInput == '') ? 0 : parseInt(qtyInput);
        mkgInput = (mkgInput == '') ? 0 : parseInt(mkgInput);
        weightInput = (weightInput == '') ? 0 : makeToFixed(weightInput);

        /* Find selected product is under gold/silver
        As of now gold is in between 1-3 and silver is between 4-6 */
        

        //Check StoneWeight is lesser than  Actual Weight
        if (qtyInput == '' && qtyInput == 0) {
          //alert("Quantity should not be empty");
          return false;
        } else if (weightInput == '' && weightInput == 0) {
          //alert("Quantity should not be empty");
          return false;
        } else if (stnInput >= weightInput && weightInput > 0) {
          alert("Stone Weight should be less than Product weight");
          return false;
        } else {
          
          /* Check product Availability
          	 If Prod is not available show an alert with --
          	 -- available product quantity along with weight 
          */
          var resObj = getProdItemGross(prodInput[i].value);
          var prodItemGross = resObj.gross;

          let selProdPriceVal = (resObj.category == 'gold') ? gold_price : silver_price;

          if(prodItemGross != false){
          	   //console.log(prodItemGross - (weightInput*qtyInput));
          	   var availProdWeight = prodItemGross - (weightInput*qtyInput);
	          if(availProdWeight < 0){
	          	alert("Selected Product / Product Quantity is not available \n Selected available weight for this product is "+prodItemGross + ". You have selected "+(weightInput*qtyInput)+". Please select in range.");
	          	return false;
	          }	
	           document.getElementsByName('totalgross[]')[i].value=availProdWeight;
	         // console.log(prodTotalGross);
          }else{
          		alert("Selected Product is not available");
	          	return false;
          }
           
          //console.log(actualAvilProdWeight,availProdWeight,"actualAvilProdWeight");

          //find row total
          let totalPrice = (selProdPriceVal * ((weightInput * qtyInput) - stnInput)) + mkgInput;
          totalPrice = parseFloat(totalPrice);
          //console.log(totalPrice,typeof totalPrice,"overallPrice");
          document.getElementsByName('prodTotal[]')[i].value = totalPrice;
          overallPrice += totalPrice;
        }
      }



      var prod_type = $('select[name="exch_prod_type"]').val();
      if(prod_type){
      	var exchageTotal = (calculateExchagePrice() == false) ? 0 : calculateExchagePrice();
      	overallPrice = overallPrice - exchageTotal;
      }      	      	
      $("#overall_price").val(overallPrice);      
      
      calculateTransaction();
    }

    $("#discount_price, #paid_amount").keyup(function () {
      calculateTransaction();
    });

    $(document).on('keyup', 'input[name="weight[]"], input[name="qty[]"], input[name="stoneWeight[]"], input[name="mkgCharges[]"]', function (e) {
      calculatePrice();
    });

    $(document).on('keyup', 'input[name="exch_weight"], input[name="exch_percentage"]', function (e) {
      calculatePrice();
    });

    function makeToFixed(val){
      //return val.toFixed(3);
      var x = parseFloat(val);
      x = x.toFixed(3);
      console.log(x);
      return x;
    }
   	function calculateExchagePrice(){
   		var prod_type = $('select[name="exch_prod_type"]').val();
   		var exch_weight = $('input[name="exch_weight"]').val();
   		var exch_percentage = $('input[name="exch_percentage"]').val();
   		var exch_price = $('input[name="exch_price"]').val();

   		var exch_gold_price = $("#gold_price").val() == '' ? 0 : parseInt($("#gold_price").val());
   		var exch_silver_price = $("#silver_price").val() == '' ? 0 : parseInt($("#silver_price").val());

   		exch_weight = (exch_weight == '') ? 0 : parseInt(exch_weight);
   		exch_percentage = (exch_percentage == '') ? 0 : parseInt(exch_percentage);
   		exch_price = (exch_price == '') ? 0 : parseInt(exch_price);

		var selCatPrice = (prod_type == 'Gold') ? exch_gold_price : exch_silver_price;

		var tot = (selCatPrice*exch_weight)*(exch_percentage/100);
		$('input[name="exch_price"]').val(parseInt(tot));
		return tot;
   	} 

    function calculateTransaction() {

      var overallPrice = $("#overall_price").val();
      var discountPrice = $("#discount_price").val();
      var priceAfterDiscount = $("#total_after_discount").val();
      var paidAmount = $("#paid_amount").val();
      var dueAmount = $("#due_amount").val();

      overallPrice = overallPrice == '' ? 0 : parseInt(overallPrice);
      discountPrice = discountPrice == '' ? 0 : parseInt(discountPrice);
      priceAfterDiscount = priceAfterDiscount == '' ? 0 : parseInt(priceAfterDiscount);
      paidAmount = paidAmount == '' ? 0 : parseInt(paidAmount);
      dueAmount = dueAmount == '' ? 0 : parseInt(dueAmount);

      if (paidAmount > priceAfterDiscount && priceAfterDiscount > 0) {
        alert("Paid Amount Shoud be less than or equal to Total Amount");
        return false;
      } else {
        if (overallPrice > 0) {
          $("#total_after_discount").val(overallPrice - discountPrice);
          $("#due_amount").val((overallPrice - discountPrice) - paidAmount);
        } else {
          $("#discount_price, #paid_amount, #total_after_discount, #due_amount ").val(0);
        }
      }



    }


  </script>


	
</html>
