<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="#">BRANDY</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item"><a class="nav-link" href="dashboard.php"><?php echo lang("admin"); ?></a></li>
        <li class="nav-item"><a class="nav-link" href="members.php"><?php echo lang("members"); ?></a></li>
        <li class="nav-item"><a class="nav-link" href="categories.php"><?php echo lang("categories"); ?></a></li>
        <li class="nav-item"><a class="nav-link" href="items.php"><?php echo lang("items"); ?></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><?php echo lang("stat"); ?></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><?php echo lang("logs"); ?></a></li>
      </ul>
      <ul class="float-right navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Dropdown
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="members.php?do=edit&id=<?php echo $_SESSION['id'] ?>">Edit profile</a>
            <a class="dropdown-item" href="#">Settings</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="logout.php">Logout</a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>