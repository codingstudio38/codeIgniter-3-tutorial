<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My User Table</title>
    <?php include('css_js.php'); ?>
</head>

<body>
    <?php include('header.php'); ?>
    <div class="container">
        <?php if(!empty($this->session->flashdata('success'))){?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Status</strong> <?=$this->session->flashdata('success');?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php } ?>
        <?php if(!empty($this->session->flashdata('failed'))){?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Status</strong> <?=$this->session->flashdata('failed');?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php } ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col"> </th>
                    <th scope="col"> </th>
                    <th scope="col"> </th>
                    <th colspan="2">
                        <form action="<?= base_url('/admin/excelimport')?>" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="file" name="excelfile"
                                        accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                        class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" name="ImportExcel" class="btn btn-primary btn-sm">Excel Import
                                    </button>
                                </div>
                            </div>
                        </form>
                    </th>
                    <th><a href="<?=base_url('/admin/excelexport')?>" target="_blank" class="btn btn-info btn-sm">Export
                            Excel</i></a>
                        <a href="<?=base_url('/admin/pdfexport')?>" target="_blank" class="btn btn-info btn-sm">Export
                            PDF</a>
                    </th>
                </tr>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Picture</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users AS $key => $row){ ?>
                <tr>
                    <td><?=$row['id']?></td>
                    <td><?=$row['name']?></td>
                    <td><?=$row['email']?></td>
                    <td><?=$row['phone']?></td>
                    <td><img style=" width:70px;" src="<?=base_url('/uploads')."/".$row['picture']?>"
                            title="<?= $row['name']; ?>" alt="<?= $row['name']; ?>"></td>
                    <td scope="col">
                        <a href="<?=base_url('/admin')."/".$row['id']?>/edit" class="btn btn-info btn-sm"><i
                                class="fa fa-pencil" aria-hidden="true"></i></a>
                        <a href="<?=base_url('/admin')."/".$row['id']?>/delete" class="btn btn-danger btn-sm"><i
                                class="fa fa-trash" aria-hidden="true"></i></a>
                    </td>
                </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</body>

</html>