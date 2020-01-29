<?php
include("environment.php");

extract($_REQUEST);

if (!isset($submit)) {

?>

<html>
  <head>
       <link rel="stylesheet" type="text/css" href="styles.css?<?php echo rand(); ?>" />
       <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
       <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
       <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
       <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </head>

  <body class="rules">
    <h3>Enter your rules:</h3>
    <form action="rules.php" method="post" style="margin-top: 20px; margin-left: 100px; ">

        <textarea name="rule" style="width: 50%; height:200px"/></textarea>
        <br />
        <button class ='button' type="submit" name="submit">Save</button>
    </form>
  </body>
</html>

<?php

}
else
{
  $rule = $conn->real_escape_string($rule);

  $sql = "INSERT INTO rules (rule) VALUES ('$rule')";

 if ($conn->query($sql) === TRUE)
 {
   header("Location: index.php");
 }
 else
 {
   echo "Error";
 }
}
?>
