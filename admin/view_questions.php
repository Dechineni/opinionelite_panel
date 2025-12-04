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
                            <li><a href="#">Details</a></li>
                            <li class="active">Basic table</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="animated fadeIn">
                <div class="row">
                    


                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">Question Answers Details</strong>
                            </div>
                            <div class="card-body">
                               <table id="categoryTable" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Id</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Question Name</th>
                                            <th scope="col">Answer</th>
                                            <th scope="col">Precode</th>
                                            <th scope="col">Delete</th>
                                            <!-- <th scope="col">Update</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
											include('config.php');
											$sql="select * from question_answers";
											$rs=mysqli_query($db,$sql);
											while($rw=mysqli_fetch_row($rs))
											{
                                                $ans=json_decode($rw[4],true);
                                                
                                                
											?>
                                        <tr>
                                            <td><?php echo $rw[0];?></td>
                                            <td><?php echo $rw[2];?></td>
                                            <td><?php echo $rw[3];?></td>
                                            <td>
                                                <ol>
                                            <?php
                                                if (is_array($ans)) {
                                                foreach($ans as $a)
                                                {
                                                    ?>
                                                    
                                                    <li><?php echo ($a['answer']);?></li>
                                                    
                                                <?php
                                                }
                                            }
                                            ?>
                                            </ol>
                                            </td>
                                            <td>
                                            <?php
                                                if (is_array($ans)) {
                                                foreach($ans as $a)
                                                {
                                                    ?>
                                                    
                                                    <li><?php echo ($a['precode']);?></li>
                                                    
                                                <?php
                                                }
                                            }
                                            ?>
                                            </td>
                                            <td><a href='question_answer_delete.php?id=<?php echo $rw[0]; ?>'>Delete</a></td>
											
                                        </tr>
                                        <?php
											}?>
                                    </tbody>
                                </table>
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