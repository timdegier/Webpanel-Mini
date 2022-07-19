<?php

$websites = new Websites;
$server = new ServerInfo;
$websites->delete();
$websites->reloadApache();

require 'views/layout/admin/header.php';

?>

<div class="panel-title">
  <h2>Home</h2>
</div>

<div class="px-3 pt-3 pb-1">
  <a href="<?= PANEL_URL ?>/admin/add-website" class="button">Add website</a>

  <form method="post" class="d-inline-block">
    <input type="hidden" name="id" value="<?= $value['id'] ?>">
    <button type="submit" name="reloadApache" class="website-right-item button">
      <i class="fas fa-sync-alt"></i> Reload apache
    </button>
  </form>
</div>

<div class="p-3">

  <?php if (isset($msg)): ?>
    <div class="alert alert-light">
      <?= $msg ?>
    </div>
  <?php endif; ?>

  <div class="row">
    <div class="col-lg-9">
      <ul class="list-group list-group-flush border">
        <?php $i = 0; ?>
        <li class="list-group-item px-0 custom-list-item header">
          <div class="row px-4 py-3">
            <div class="col-6">
              <b>Website</b>
            </div>

            <div class="col-6">
              <b>Status</b>
            </div>
          </div>
        </li>
        <?php foreach($websites->getAll() as $key => $value): ?>
          <li class="list-group-item px-0 custom-list-item">
            <a class="btn d-block text-left" data-toggle="collapse" href="#website<?= $value['id'] ?>" role="button" aria-expanded="true" aria-controls="website<?= $value['id'] ?>">
              <span class="mr-3"></span>
              <div class="row">
                <div class="col-md-6">
                   <?= $value['name'] ?>
                </div>

                <div class="col-md-6">
                  <?= $value['status'] ?>

                  <?php if ($value['status'] == 'Online'): ?>
                    <div class="active-ball"></div>
                  <?php endif; ?>

                  <?php if ($value['status'] == 'Offline'): ?>
                    <div class="not-active-ball"></div>
                  <?php endif; ?>
                </div>
              </div>

            </a>
            <div class="collapse <?php if($i == 0){ echo 'show'; } ?>" id="website<?= $value['id'] ?>">
              <div class="border-top p-3">
                <div class="row">
                  <div class="col-12 line-height">
                    <img src="<?= $value['screenshot'] ?>" width="400" class="d-inline-block my-auto border align-top">

                    <div class="website-right">
                      <div class="row">
                        <div class="col-md-4">
                          <h5>Website</h5>
                          <a class="website-right-item" href="<?= $value['url'] ?>">
                            <i class="fas fa-database"></i> PHPMyAdmin
                          </a>
                          <a class="website-right-item" href="<?= $value['url'] ?>">
                            <i class="fas fa-arrow-right"></i> Go to website
                          </a>
                        </div>

                        <div class="col-md-4">
                          <h5>Actions</h5>

                          <div class="website-right-item">
                            <form method="post" action="add-screenshot">
                              <input type="hidden" name="id" value="<?= $value['id'] ?>">
                              <button type="submit" name="edit" class="website-right-item button-none">
                                <i class="fas fa-camera"></i> Add screenshot
                              </button>
                            </form>

                            <form method="post" action="edit-website">
                              <input type="hidden" name="id" value="<?= $value['id'] ?>">
                              <button type="submit" name="edit" class="website-right-item button-none">
                                <i class="fas fa-cog"></i> Edit website
                              </button>
                            </form>

                            <form method="post">
                              <input type="hidden" name="id" value="<?= $value['id'] ?>">
                              <input type="hidden" name="name" value="<?= $value['name'] ?>">
                              <button type="submit" name="delete" class="website-right-item text-danger button-none">
                                <i class="fas fa-times"></i> Delete website
                              </button>
                            </form>
                          </div>
                        </div>

                        <div class="col-md-4">
                          <h5>Info</h5>
                          <div class="website-right-item">
                            <b>Site size:</b> <?php echo formatBytes(folderSize($value['directory'])); ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </li>

          <?php $i++; ?>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="col-lg-3 mt-3 mt-lg-0 pl-lg-0">
      <div class="border mb-3">
        <div class="info-title">
          User info
        </div>

        <div class="p-3 border-bottom">
          <b>Username</b><br>
          <?= $_SESSION['username'] ?>
        </div>

        <div class="p-3">
          <b>Type</b><br>
          <?php if($_SESSION['type'] == 0) echo 'Admin' ?>
        </div>
      </div>

      <div class="border">
        <div class="info-title">
          Server info
        </div>

        <div class="p-3 border-bottom">
          <b>Server name</b><br>
          <?= $server->getServername(); ?>
        </div>
        <div class="p-3 border-bottom">
          <b>Server address</b><br>
          <?= $server->getServeraddress(); ?>
        </div>
        <div class="p-3">
          <b>Free storage</b><br>
          <?= $server->getFreeStorage() ?> / <?= $server->getTotalStorage() ?>

          <div class="progress mt-2">
            <div class="progress-bar" role="progressbar" style="width: <?= round( $server->getStoragePercentage() ) ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<?php require 'views/layout/admin/footer.php'; ?>
