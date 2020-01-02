<?php
  include("environment.php");

  $conn = create_connection();
  $round = $conn->real_escape_string($round);

  if (isset($_POST["import"])) {

      $conn = create_connection();

      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      $fileName = $_FILES["file"]["tmp_name"];

      if ($_FILES["file"]["size"] > 0) {

          $file = fopen($fileName, "r");

          while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
              $sqlInsert = "INSERT INTO players (first_name, last_name, email)
                     VALUES ('" . $column[0] . "','" . $column[1] . "','" . $column[2] . "')";
              $result = mysqli_query($conn, $sqlInsert);
          }

          header("Location: index.php?round=$round");
      }
  }
  else
  {
?>

<html>
  <form class="form-horizontal" action="" method="post" name="uploadCSV"
      enctype="multipart/form-data">
      <div class="input-row">
          <label class="col-md-4 control-label">Select a CSV File</label> <input
              type="file" name="file" id="file" accept=".csv">
          <button type="submit" id="submit" name="import" class="btn-submit">Import File</button>
          <br />

      </div>
      <div id="labelError"></div>
  </form>
</html>

<script type="text/javascript">
	$(document).ready(
	function() {
		$("#frmCSVImport").on(
		"submit",
		function() {

			$("#response").attr("class", "");
			$("#response").html("");
			var fileType = ".csv";
			var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+("
					+ fileType + ")$");
			if (!regex.test($("#file").val().toLowerCase())) {
				$("#response").addClass("error");
				$("#response").addClass("display-block");
				$("#response").html(
						"Invalid File. Upload : <b>" + fileType
								+ "</b> Files.");
				return false;
			}
			return true;
		});
	});
</script>

<?php
  }
?>
