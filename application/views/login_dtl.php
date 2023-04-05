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
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Login time</th>
                    <th scope="col">Logout time</th>
                    <th scope="col">System</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data AS $key => $row){ ?>
                <tr>
                    <td><?=$row['id']?></td>
                    <td><?=$row['name']?></td>
                    <td><?=$row['logintime']?></td>
                    <td><?=$row['logouttime']?></td>
                    <td><?=$row['system']; ?></td>
                </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</body>

</html>