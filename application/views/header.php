<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <?php if(empty($this->session->userdata('user'))){?>
            <li
                class="nav-item <?php if((!(isset($_SERVER['REDIRECT_QUERY_STRING']))) || $_SERVER['REDIRECT_QUERY_STRING']=="/login"){echo "active";} ?>">
                <a class="nav-link" href="<?=base_url('/login')?>">Login</a>
            </li>

            <li
                class="nav-item <?php if(isset($_SERVER['REDIRECT_QUERY_STRING'])){ if($_SERVER['REDIRECT_QUERY_STRING']=="/register"){echo "active";}} ?>">
                <a class="nav-link" href="<?=base_url('/register')?>">Register</a>
            </li>
            <?php } else { ?>
            <li class="nav-item ">
                <a class="nav-link <?php if(isset($_SERVER['REDIRECT_QUERY_STRING'])){ if($_SERVER['REDIRECT_QUERY_STRING']=="/admin"){echo "active";}} ?>"
                    href="<?=base_url('/admin')?>">Home</a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="<?=base_url('/admin/logout')?>">Logout</a>
            </li>
            <?php } ?>
        </ul>
        <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
        </form>
    </div>
</nav>