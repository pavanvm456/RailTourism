<!DOCTYPE html>
<html style="height:100%">
<head>
	<title>RailWays</title>
		<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
  
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<script type="text/javascript">
	function bookconfirm()
	{
		var r = confirm("are sure you wanto book this  seat?");
		if(r == true){
			return true;
		}
		else
			return false;
	}



</script>


</head>
<body>

<nav class="navbar navbar-default " role="navigation">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle = "collapse" data-target="#nvbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span> 
      </button>
      <a class="navbar-brand" href="../rail/">RAILWAY</a>
    </div>
    <div class="collapse navbar-collapse" id="nvbar">
      <ul class="nav navbar-nav">
        <li><a href="../rail/">Home</a></li>
        <li><a href="#">News</a></li>
        <li><a href="#">Contact</a></li>
        <li><a href="#">About Us</a></li>
      </ul>
        <ul class="nav navbar-nav navbar-right" style="margin-right:2px;">
        <li><a href="user/"><span class="glyphicon glyphicon-arrow-left"></span> Back</a></li>
        <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
       
      </ul>   
    </div>
  </div>
</nav>




<?php

require 'phpfiles/classes.php';
session_start();
if(empty($_SESSION['userId'])){
	$message = " please login first and then try !";
	echo "<script type='text/javascript'>  var r =confirm('$message');
			if (r == true){  
				window.location='login.php';
				}else
		window.location='index.php';
	 </script>";
	
}
else{

$userId = $_SESSION['userId'];
/*
if(!empty($_GET['userid'])) && !empty($_GET['amt'])
{	
	if(!isset($_COOKIE["t_no"]))
	{
		echo $_COOKIE['t_no'];
	}

	
	$book = new book();
		$bid = $book->insertTkt($userId,$t_no,$t_name,$t_class,$t_from,$t_to,$t_jdate);
		$book->insertPdetail($_POST['p_name'],$_POST['p_age'],$_POST['p_idno'],$_POST['p_idcard'],$_POST['p_gender']);
		$book->insertFare($tFare ,$catCharge,$servCharge,$totalFare);
		if($bid != 0){
			header("location:printTicket.php?bookid=".$bid);
		}
	
}

*/




if(!empty($_POST['t_class'] ) && !empty($_POST['t_name']) && !empty($_POST['t_arrtime'])  && !empty($_POST['t_from']) && !empty($_POST['t_to']) && !empty($_POST['t_deptime']) && !empty($_POST['t_jdate']) && !empty($_POST['t_no']))
{

$t_no = $_POST['t_no'];
$t_jdate = $_POST['t_jdate'];
$t_name = $_POST['t_name'];
$t_class = $_POST['t_class'];
$t_from = $_POST['t_from'];
$t_to = $_POST['t_to'];
$t_deptime = $_POST['t_deptime'];
$t_arrtime = $_POST['t_arrtime'];
			
		$conn = new connect();
		$flag = 0;
		$check = 'select * from book where t_class="'.$t_class.'" and t_no = "'.$t_no.'" and t_jdate="'.$t_jdate.'"';
		
		if($result = $conn->exeQuery($check)){

		while ($row = $result->fetch_assoc()) {
			++$flag;
		}	
	}
	if($flag>= 5)
	{
		session_start();
		$_SESSION['noseat'] = 'there is no seat for your Query';
		header("location:user/");
	}
	else{
	if(!empty($_POST['p_name']) &&  !empty($_POST['p_age']) &&  !empty($_POST['p_idcard']) &&  !empty($_POST['p_idno']) &&  !empty($_POST['p_gender']))
	{		
			$tFare = 200;
			$catCharge = 100;
			$servCharge = 50;
			$totalFare = $tFare+$catCharge+$servCharge;

			
		$book = new book();
		$bid = $book->insertTkt($userId,$t_no,$t_name,$t_class,$t_from,$t_to,$t_jdate);
		$book->insertPdetail($_POST['p_name'],$_POST['p_age'],$_POST['p_idno'],$_POST['p_idcard'],$_POST['p_gender']);
		$book->insertFare($tFare ,$catCharge,$servCharge,$totalFare);
		echo $bid;		

		if($bid != 0){
			$cn = new connect();
    		$que1 = 'select * from users where userId="'.$userId. '"';
    		$res = $cn->exeQuery($que1);
    		$mob = $res->fetch_assoc();

$post_data = array(
    // 'From' doesn't matter; For transactional, this will be replaced with your SenderId;
    // For promotional, this will be ignored by the SMS gateway
    'From'   => 'phpRailways',
    //'To'    => array('7405200603','8511357339','9712197486'),

        'To'    => array($mob['mobile']),
    'Body'  => 'phpRailways- train no/name:'.$t_no.'/'.$t_name.' journey date:'.$t_jdate.' passenger name:'.$_POST["p_name"].
    ' totalFare:Rs.'.$totalFare. ' happy journey'
);
 
$exotel_sid = "himanshu1"; // Your Exotel SID - Get it from here: http://my.exotel.in/Exotel/settings/site#api-settings
$exotel_token = "299e54e4a70adfb7d95577cab13eb0cc5d9ce09f"; // Your exotel token - Get it from here: http://my.exotel.in/Exotel/settings/site#api-settings
 
$url = "https://".$exotel_sid.":".$exotel_token."@twilix.exotel.in/v1/Accounts/".$exotel_sid."/Sms/send";
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FAILONERROR, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
 
$http_result = curl_exec($ch);
$error = curl_error($ch);
$http_code = curl_getinfo($ch ,CURLINFO_HTTP_CODE);
 
curl_close($ch);
 
//print "Response = ".print_r($http_result);

			header("location:printTicket.php?bookid=".$bid);
		}
		//echo "your tickt is book happy journey";
			

		$conn = new connect();
		$flag = 0;	
		$check = 'select * from book where t_class="'.$t_class.'" and t_no = "'.$t_no.'" and t_jdate="'.$t_jdate.'"';
		$result = $conn->exeQuery($check);

		while ($row = $result->fetch_assoc()) {
			++$flag;
		}	

	


	}




//echo $_POST['t_class'];
//echo "<br>";
//echo $_POST['t_name'];
echo "<a href='javascript:history.go(-1)'>GO BACK</a>";
?>
<div class="container">
	
	<div class="row">
	<h3>  There is only <?php  echo 5-$flag ; ?> seats available ! </h3>

	<!--	<div class="row"><h2>Journey Details</h2></div>  <!-- 1st row for heading -->
	<!--
		<div class="row">              
			<div class="col-lg-6 col-md-6 col-sm-6">
				<p><h4>train name:</h4><?php echo $t_name; ?></p>
				<p><h4>class : </h4><?php echo $t_class; ?></p>
				<p><h4>fare :</h4>200RS<br></p>
			</div>

			<div class="col-lg-6 col-md-6 col-sm-6">
			<p><h4>from:<?php echo $t_from; ?></h4></p>
			<p><h4>to:</h4><?php echo $t_to; ?></p>
			<p><h4>src dep time:</h4><?php  echo  $t_deptime; ?></p>
			<p><h4>des arr time:</h4><?php echo $t_arrtime; ?></p>
			</div>	
		</div>    
			
		  <!--   2nd row over  -->
		<!--
	

	-->
	<div class="col-lg-5 col-md-5 ">
	<table class='table table-responsive'>
		<tr>
			<th>train name</th>
			<td><?php echo $t_name; ?></td>
		</tr>
		<tr>
			<th>class</th>
			<td><?php echo $t_class; ?></td>
		</tr>
		<tr>
			<th>fare</th>
			<td>200 RS.</td>
		</tr>
	</table>
	</div>

	<div class="col-lg-5 col-md-5 ">
	<table class='table table-responsive'>
		<tr>
			<th>from:</th>
			<td><?php echo $t_from; ?></td>
		</tr>
		<tr>
			<th>to:</th>
			<td><?php echo $t_to; ?></td>
		</tr>
		<tr>
			<th>src dep time</th>
			<td><?php  echo $t_deptime; ?></td>
		</tr>
		<tr>
			<th>des arr time</th>
			<td><?php   echo $t_arrtime; ?></td>
		</tr>
	</table>
	</div>
</div>  <!-- top row over -->
	<hr>

<?php
if($flag>= 5)
	{
		//session_start();
		echo '<h2>there is no seat for you ! you can\'book anymore! </h2>';
		//header("location:tbwsta.php");
	}

else{
?>

<div class="row">
	<form name="detail" onsubmit="return bookconfirm()" action="#" method="post">
		
            <input type ='hidden' name='t_no' value='<?php echo $t_no; ?>' required >
            <input type ='hidden' name='t_name' value='<?php echo $t_name; ?>' required>
            <input type ='hidden' name='t_from' value='<?php echo $t_from; ?>' required>
            <input type ='hidden' name='t_deptime' value='<?php echo $t_deptime; ?>' required>
            <input type ='hidden' name='t_to' value='<?php echo $t_to; ?>' required>
            <input type ='hidden' name='t_arrtime' value='<?php echo $t_arrtime; ?>' required >
            <input type ='hidden' name='t_class' value='<?php echo $t_class; ?>' required >
            <input type ='hidden' name='t_jdate' value='<?php echo $t_jdate; ?>' required >


		<label>name:</label><input  class="form-control" type="text" name="p_name" required>
		<label>age:</label><input  class="form-control" type="text" name="p_age" required>
		<label>gender:</label><input  class="form-control" type="text" name="p_gender" required>
		<label>Idcard:</label><input  class="form-control" type="text" name="p_idcard" required>
		<label>Idcard number:</label><input  class="form-control" type="text" name="p_idno" required>

		<button type="submit" class="btn btn-primary">submit</button>

	</form>
</div>

		
</div>

<?php
}
} //else for flag count

}   // 1st else
else
{
?>
<h2>there is no data on page go back choose another.</h2>
<a href="javascript:history.go(-1)">GO BACK</a>
<?php
}


} // 1st else


?>


<!--	<script type="text/javascript" scr="../js/bootstrap.min.js"></script>  -->
	<script src="js/jquery-3.1.0.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 


</body>
</html>