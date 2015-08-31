
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>click demo</title>

  <script src="javascript/jquery.js"></script>
</head>
<body>
 <script>
  $(document).ready(function(){
	$('.submitbutton').click(function() {
    alert("welcome");
	});
});
</script>
<form name="addToCartForm">
  <input type="hidden" class="inputid" value="1"/>
  <input type="submit" class="submitbutton" value="Add to cart"/>
</form>


</body>
</html>