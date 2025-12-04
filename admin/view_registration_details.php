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
                                            <th scope="col">First Name</th>
                                            <th scope="col">Last Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Zipcode</th>
                                            <th scope="col">Gender</th>
                                            <th scope="col">Birthday</th>
                                            <th scope="col">Income</th>
                                            <th scope="col">Job Industry</th>
                                            <th scope="col">Job Decision</th>
                                            <th scope="col">Job Title</th>
                                            <th scope="col">Delete</th>
                                            <!-- <th scope="col">Update</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
											include('config.php');
											$sql="select * from signup";
											$rs=mysqli_query($db,$sql);
											while($rw=mysqli_fetch_row($rs))
											{
                                                $ans=json_decode($rw[4],true);


											?>
                                        <tr>
                                            <td><?php echo $rw[0];?></td>
                                            <td><?php echo $rw[1];?></td>
                                            <td><?php echo $rw[2];?></td>
                                            <td><?php echo $rw[3];?></td>
                                            <td><?php echo $rw[4];?></td>
                                            <td><?php echo $rw[5];?></td>
                                            <td><?php echo $rw[6];?></td>
                                            <td><?php echo $rw[7];?></td>
                                            <td><?php echo $rw[8];?></td>
                                            <td><?php echo $rw[9];?></td>
                                            <td><?php echo $rw[10];?></td>

                                            <td><a href='sign_delete.php?id=<?php echo $rw[0]; ?>'>Delete</a></td>

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
