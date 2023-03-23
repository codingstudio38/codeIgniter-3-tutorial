<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <title>CodeIgniter 3 || Register Page</title>
    <?php include('css_js.php'); ?>
    <style>
        .invalid{
            color: red;
            font-size: small;
            margin-bottom: 0px;
        }
    </style>
</head>
 
<body> 
    <?php include('header.php'); ?>
    <section class="vh-100" style="background-color: #eee;">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-12 col-xl-11">
                    <div class="card text-black" style="border-radius: 25px;">
                        <div class="card-body p-md-5">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                                    <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign up</p>
                                
<form class="mx-1 mx-md-4" action="<?= base_url('/saveuser')?>" method="post" enctype="multipart/form-data">
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
<div class="d-flex flex-row align-items-center mb-1">
   
    <div class="form-outline flex-fill mb-0">
        <label class="form-label" for="name">Your Name</label>
        <input type="text" id="name" name="name" class="form-control" value="<?= set_value('name'); ?>"/>
        <?= form_error('name')?> 
    </div>
</div>

<div class="d-flex flex-row align-items-center mb-1">
  
    <div class="form-outline flex-fill mb-0">
    <label class="form-label" for="email">Your Email</label>
        <input type="text" id="email" name="email" class="form-control" value="<?= set_value('email'); ?>"/>
        <?= form_error('email')?>
    </div>
</div>

<div class="d-flex flex-row align-items-center mb-1">
  
    <div class="form-outline flex-fill mb-0">
    <label class="form-label" for="phone">Your Phone</label>
        <input type="number" id="phone" name="phone" class="form-control" value="<?= set_value('phone'); ?>"/>
        <?= form_error('phone')?>
    </div>
</div>

<div class="d-flex flex-row align-items-center mb-1">
   
    <div class="form-outline flex-fill mb-0">
    <label class="form-label" for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" value="<?= set_value('password'); ?>"/>
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



<div class="d-flex justify-content-center mx-4 mb-1 mb-lg-4">
    <button type="submit" class="btn btn-primary">Sing Up</button>
</div>

</form>

                                </div>
                                <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">

                                    <img src="./img/draw1.webp" class="img-fluid" alt="Sample image">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>