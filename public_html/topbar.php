<?php include ('./db_connect.php'); ?>
<style>
  .logo {
    margin: auto;
    font-size: 20px;
    background: black;
    padding: 7px 11px;
    border-radius: 50% 50%;
    color: #000000b3;
  }
  .badge-txt {
    color: white;
    background-color: red;
    border-radius: 50%;
    padding: 5px;
    width: 20px;
    height: 20px;
    display: flex;
    position: absolute;
    top: -8px;
    right: 85px;
    justify-content: center;
    align-items: center;
    text-align: center;
  }
</style>

<nav class="navbar navbar-light fixed-top bg-primary" style="padding:0;min-height: 3.5rem">
  <div class="container-fluid mt-2 mb-2">
    <div class="col-lg-12">
      <div class="col-md-1 float-left" style="display: flex;">

      </div>
      <div class="col-md-4 float-left text-white">
        <large><b>
            <?php echo isset($_SESSION['system']['name']) ? $_SESSION['system']['name'] : '' ?>
          </b></large>
      </div>
      <div class="float-right d-flex align-items-center">
      <!-- Notification Icon -->
      <div class="mr-4">
        <a href="index.php?page=inquire" class="text-white">
          <i class="fa fa-bell"></i>
          <span class="badge-txt"><?php echo $conn->query("SELECT * FROM inquire")->num_rows ?></span>
        </a>
      </div>

  <!-- Account Settings Dropdown (Login Name) -->
  <div class="dropdown">
    <a href="#" class="text-white dropdown-toggle" id="account_settings" data-toggle="dropdown"
      aria-haspopup="true" aria-expanded="false">
      <?php echo $_SESSION['login_name']; ?>
    </a>
    <div class="dropdown-menu" aria-labelledby="account_settings" style="left: -2.5em;">
      <a class="dropdown-item" href="javascript:void(0)" id="manage_my_account"><i class="fa fa-cog"></i> Manage Account</a>
      <a class="dropdown-item" href="ajax.php?action=logout"><i class="fa fa-power-off"></i> Logout</a>
    </div>
  </div>
</div>

    </div>

</nav>

<script>
  $('#manage_my_account').click(function () {
    uni_modal("Manage Account", "manage_user.php?id=<?php echo $_SESSION['login_id'] ?>&mtype=own")
  })
</script>