<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Edit</title>
    <?php include('css_js.php'); ?>
    <style>
    .invalid {
        color: red;
        font-size: small;
        margin-bottom: 0px;
    }
    </style>
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




        <form class="mt-5 col-md-7" style="margin:auto;"
            action="<?= base_url('/admin')?>/<?=$users['data']->id?>/updateuser" method="post"
            enctype="multipart/form-data">
            <div class="d-flex flex-row align-items-center mb-1">

                <div class="form-outline flex-fill mb-0">
                    <label class="form-label" for="name">Your Name</label>
                    <input type="text" id="name" name="name" class="form-control"
                        value="<?= $users['data']->name; ?>" />
                    <?= form_error('name')?>
                </div>
            </div>

            <div class="d-flex flex-row align-items-center mb-1">

                <div class="form-outline flex-fill mb-0">
                    <label class="form-label" for="email">Your Email</label>
                    <input type="text" id="email" name="email" class="form-control"
                        value="<?= $users['data']->email; ?>" />
                    <?= form_error('email')?>
                </div>
            </div>

            <div class="d-flex flex-row align-items-center mb-1">

                <div class="form-outline flex-fill mb-0">
                    <label class="form-label" for="phone">Your Phone</label>
                    <input type="number" id="phone" name="phone" class="form-control"
                        value="<?= $users['data']->phone; ?>" />
                    <?= form_error('phone')?>
                </div>
            </div>

            <div class="d-flex flex-row align-items-center mb-1">

                <div class="form-outline flex-fill mb-0">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control"
                        value="<?= set_value('password'); ?>" />
                    <?= form_error('password')?>
                </div>
            </div>

            <div class="d-flex flex-row align-items-center mb-1">

                <div class="form-outline flex-fill mb-0">
                    <label class="form-label" for="picture">Your Profile Picture</label>
                    <input type="file" id="picture" name="picture" accept="image/*" class="form-control" />
                    <?= form_error('picture')?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>







    </div>
</body>

</html>