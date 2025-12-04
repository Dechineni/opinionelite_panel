<?php include('header.php'); ?>
<div id="right-panel" class="right-panel">

    <!-- Header-->
    <header id="header" class="header">
        <div class="header-menu">
            <div class="col-sm-7">
                <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
            </div>
        </div>
    </header>

    <div class="content mt-3">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Question Form</strong>
                        </div>
                        <div class="card-body">
                            <div id="pay-invoice">
                                <div class="card-body">
                                    <div class="card-title">
                                        <h3 class="text-center">Add Question Form</h3>
                                    </div>
                                    <hr>
                                    <form action="store_question.php" method="post">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label mb-1">Question Id</label>
                                                    <input name="q_id" type="text" class="form-control" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label mb-1">Question Name</label>
                                                    <input name="q_name" type="text" class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group has-success">
                                                    <label class="control-label mb-1">Question</label>
                                                    <input name="question" type="text" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group has-success">
                                                    <label class="control-label mb-1">Profile Type</label>
                                                    <input name="profile" type="text" class="form-control" required>
                                                </div>
                                            </div>

                                          <div class="col-md-12">
    <label class="control-label mb-1">Answers with Precode</label>
    <div id="answer-wrapper">
        <div class="input-group mb-2">
            <input type="text" name="answer[]" class="form-control" placeholder="Enter answer">
            <input type="text" name="precode[]" class="form-control" placeholder="Enter precode">
            <div class="input-group-append">
                <button type="button" class="btn btn-success add-answer">+</button>
            </div>
        </div>
    </div>
</div>

                                        </div>

                                        <div class="mt-3">
                                            <button type="submit" class="btn btn-lg btn-info btn-block" name="btn">
                                                Submit
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> <!-- .card -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery and Script to handle dynamic answers -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $('#answer-wrapper').on('click', '.add-answer', function(){
        let html = `
        <div class="input-group mb-2">
            <input type="text" name="answer[]" class="form-control" placeholder="Enter answer">
            <input type="text" name="precode[]" class="form-control" placeholder="Enter precode">
            <div class="input-group-append">
                <button type="button" class="btn btn-danger remove-answer">−</button>
            </div>
        </div>`;
        $('#answer-wrapper').append(html);
    });

    $('#answer-wrapper').on('click', '.remove-answer', function(){
        $(this).closest('.input-group').remove();
    });
});


</script>

<?php include('footer.php'); ?>
