<?php
set_include_path($_SERVER['DOCUMENT_ROOT'].'/util');
include_once("auth.php");
?>
<div class="content-wrapper">
  <div class="page-header">
    <h3 class="page-title"> Form elements </h3>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Forms</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit data</li>
      </ol>
    </nav>
  </div>
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Change Email or Password</h4>
          <form class="forms-sample" method="POST" action="api/setting_edit.php">
            <div class="form-group">
              <label for="username">Username</label>
              <input type="text" class="form-control" id="username" name="username" placeholder="Name" value="<?= $auth->getUsername() ?>" disabled require>
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?= $auth->getEmail() ?>" require>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Password" require>
            </div>
            <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
