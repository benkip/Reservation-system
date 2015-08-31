<div id="header" class="header">
<div class="top">
<h3 class="logo"><img src="images/main-logo.png"></h3>
<div class="search">
<label><a href="<?php echo 'updateuser.php?id='.$_SESSION['user_id']; ?>">Welcome &nbsp;<?php echo $_SESSION['user_name']; ?></a></label>
<label><a href="logout.php">Log out</a></label>
</div>
</div>
<div class="dashboard">
<div class="menu">
<?php
        $full_name = $_SERVER['PHP_SELF'];
        $name_array = explode('/',$full_name);
        $count = count($name_array);
        $page_name = $name_array[$count-1];
?>
<ul><li class="<?php if($page_name=='home.php') echo 'active';?>"><a href="home.php">DashBoard</a></li>
<li class="<?php if($page_name=='room.php') echo 'active';?>"><a href="room.php">Rooms</a></li>
<li class="<?php if($page_name=='roomprices.php') echo 'active';?>"><a href="roomprices.php">Rates</a></li>
<li class="<?php if($page_name=='reservation.php') echo 'active';?>"><a href="reservation.php">Reservations</a></li>
<li class="<?php if($page_name=='account-accomm.php') echo 'active';?>"><a href="account-accomm.php">Accounts</a></li>
<li class="<?php if($page_name=='bill.php') echo 'active';?>"><a href="bill.php">Orders</a></li>
<?php if($_SESSION['user_name']==='admin') { ?><li class="<?php if($page_name=='users.php') echo 'active';?>"><a href="users.php">Users</a></li> <?php } ?>
</ul>
