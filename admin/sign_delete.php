<?php
include('config.php');
$id=$_GET['id'];
$sql="DELETE FROM `signup` WHERE id=$id";
if(mysqli_query($db,$sql))
{
    echo "<script>
    alert('Sign Detail Deleted Successfully');
    window.location.href='view_registration_details.php';
    </script>";
}
else
{
    echo mysqli_query($db);
}
?>
