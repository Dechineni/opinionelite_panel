<?php
include('config.php');
$id=$_GET['id'];
$sql="DELETE FROM `question_answers` WHERE id=$id";
if(mysqli_query($db,$sql))
{
    echo "<script>
    alert('Question/Answers Deleted Successfully');
    window.location.href='view_questions.php';
    </script>";
}
else
{
    echo mysqli_query($db);
}
?>