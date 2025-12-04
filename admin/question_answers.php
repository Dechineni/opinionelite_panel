<?php 
include_once 'config.php'; 
require_once 'vendor/autoload.php'; 
use PhpOffice\PhpSpreadsheet\Reader\Xlsx; 

if (isset($_POST['btn'])) { 
    $allowedMimes = [
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-excel'
    ]; 

    if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $allowedMimes)) { 
        if (is_uploaded_file($_FILES['file']['tmp_name'])) { 
            $reader = new Xlsx(); 
            $spreadsheet = $reader->load($_FILES['file']['tmp_name']); 
            $sheetData = $spreadsheet->getActiveSheet()->toArray(); 
            unset($sheetData[0]); // skip header row

            $grouped = [];
            $last_qid = $last_name = $last_question = '';

            foreach ($sheetData as $row) {
                // Columns: QID, Name, Question, Answer, Precode
                $qid = trim($row[0]);
                $name = trim($row[1]);
                $question = trim($row[2]);
                $answer = trim($row[3]);
                $precode = trim($row[4]);
                $profile = trim($row[5]);

                if (!empty($qid)) $last_qid = $qid;
                if (!empty($name)) $last_name = $name;
                if (!empty($question)) $last_question = $question;
                if (!empty($profile)) $profile = $profile;

                if (!isset($grouped[$last_qid])) {
                    $grouped[$last_qid] = [
                        'question_id' => $last_qid,
                        'name' => $last_name,
                        'question' => $last_question,
                        'answers' => [],
                        'profile'=>$profile
                    ];
                }

                // Add even if answer is empty (optional – remove condition to always include)
                if (!empty($answer)) {
                    $grouped[$last_qid]['answers'][] = [
                        'answer' => $answer,
                        'precode' => $precode
                    ];
                }
            }

            // Insert into database
            $inserted = 0;
            foreach ($grouped as $q) {
                $questionId = mysqli_real_escape_string($db, $q['question_id']);
                $name = mysqli_real_escape_string($db, $q['name']);
                $question = mysqli_real_escape_string($db, $q['question']);
                $profile = mysqli_real_escape_string($db, $q['profile']);
                $answersJson = mysqli_real_escape_string($db, json_encode($q['answers'], JSON_UNESCAPED_UNICODE));

                $sql = "INSERT INTO question_answers (question_id, name, question, answers,profile) 
                        VALUES ('$questionId', '$name', '$question', '$answersJson','$profile')";

                if (!$db->query($sql)) {
                    echo "<p style='color:red;'>MySQL Error for QID $questionId: " . $db->error . "</p>";
                } else {
                    $inserted++;
                    echo "<p style='color:green;'>Inserted QID: $questionId</p>";
                }
            }

            echo "<p style='color:blue;'>✅ Total Questions Inserted: $inserted</p>";

            // Optional: Uncomment to debug full grouped data
            // echo "<pre>"; print_r($grouped); echo "</pre>";

        } else {
            echo "<p style='color:red;'>❌ Failed to upload the file.</p>";
        }
    } else {
        echo "<p style='color:red;'>❌ Invalid file type. Please upload a valid Excel file.</p>";
    }
}
?>








<?php
include('header.php');
?>

    <!-- Right Panel -->

    <div id="right-panel" class="right-panel">

        <!-- Header-->
        <header id="header" class="header">

            <div class="header-menu">

                <div class="col-sm-7">
                    <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
                    

                </div>
            </div>

        </header><!-- /header -->
        <!-- Header-->

        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Dashboard</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="#">Dashboard</a></li>
                            <li><a href="#">Forms</a></li>
                            <li class="active">Basic</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="animated fadeIn">


                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">Question/Answers Form</strong>
                            </div>
                            <div class="card-body">
                                <!-- Credit Card -->
                                <div id="pay-invoice">
                                    <div class="card-body">
                                        <div class="card-title">
                                            <h3 class="text-center">Question Answer Form</h3>
                                        </div>
                                        <hr>
                                        <form  method="post" enctype="multipart/form-data" id="importFrm">
                                            <div class="form-group">
                                                <label for="cc-payment" class="control-label mb-1">Upload File</label>
                                                <input id="cc-pament" name="file" type="file" class="form-control" required>

                                            </div>
                                                
                                                <div>
                                                    <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block" name="btn">
                                                        Submit                                                  </button>
                                                </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div> <!-- .card -->

                    </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- .animated -->
                                    </div><!-- .content -->
                                </div><!-- /#right-panel -->
                                <!-- Right Panel -->


                          <?php
                          include('footer.php');
                          ?>
