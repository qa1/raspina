<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
  <html lang="<?= Yii::$app->language ?>">
  <head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?= Html::csrfMetaTags() ?>
    <title>رسپینا - <?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script type="text/javascript">
      var controller_name = '<?= Yii::$app->controller->id ?>';
      var action_name = '<?= Yii::$app->controller->action->id ?>';
    </script>
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="skin-blue sidebar-mini">
  <?php $this->beginBody() ?>
    <div class="wrapper">

      <header class="main-header">

        <!-- Logo -->
        <a href="<?= Url::base() ?>" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini">RS</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg">رَسپینا</span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <a href="<?= Url::base() . '/site/logout'; ?>"><span class="fa fa-sign-out signout" title="Exit"></span></a>

        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <?= Html::a('<div class="title-mini-create fa fa-plus"></div><div class="title-lg-create">'.Yii::t('app','Create Post').'</div>', ['post/create'], ['class' => 'btn btn-success post-create-nav logo']) ?>
        <section class="sidebar">
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li><a target="_blank" href="<?= Yii::$app->setting->getValue('url'); ?>"><i class="fa fa-desktop"></i> <span><?= Yii::t('app','View Site') ?></span></a></li>
            <li id="m-post" class="h-menu">
              <a href="#">
                <i class="fa fa-file-text-o"></i>  <span><?= Yii::t('app','Posts') ?></span> <i class="fa fa-angle-left"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?= Url::base() . '/post/create'; ?>"><i class="fa fa-circle-o"></i><?= Yii::t('app','Create Post') ?></a></li>
                <li><a href="<?= Url::base() . '/post'; ?>"><i class="fa fa-circle-o"></i><?= Yii::t('app','Posts') ?></a></li>
                <li><a href="<?= Url::base() . '/post?PostSearch%5Bstatus%5D=0'; ?>"><i class="fa fa-circle-o"></i><?= Yii::t('app','Drafts') ?></a></li>
              </ul>
            </li>

            <li class="h-menu" id="m-category"><a href="<?= Url::base() . '/category'; ?>"><i class="fa fa-book"></i> <span><?= Yii::t('app','Categories') ?></span></a></li>

            <li class="h-menu" id="m-comment">
              <a href="<?= Url::base() . '/comment'; ?>">
                <?php
                $model = new \backend\models\Comment;
                $countNotAccepted = $model->getCountNotAccepted();
                if($countNotAccepted):
                ?>
                <small class="label pull-left bg-red pull-left-margin">
                  <?= $countNotAccepted ?>
                </small>
                <?php endif; ?>
                <i class="fa fa-comments-o"></i> <span><?= Yii::t('app','Comments') ?></span>
              </a>
            </li>

            <li class="h-menu" id="m-contact">
              <a href="<?= Url::base() . '/contact'; ?>">
                <?php
                $model = new \backend\models\Contact;
                $CountNotVisited = $model->getCountNotVisited();
                if($CountNotVisited):
                ?>
                <small class="label pull-left bg-red pull-left-margin">
                  <?= $CountNotVisited ?>
                </small>
                  <?php endif; ?>
                <i class="fa fa-comment-o"></i> <span><?= Yii::t('app','Contacts') ?></span>
              </a>
            </li>

            <li class="treeview h-menu" id="m-newsletter">
              <a href="#">
                <?php
                $model = new \backend\models\Newsletter;
                $countMails = $model->getCountMails();
                if($countMails):
                ?>
                <span class="label label-primary pull-left pull-left-margin">
                  <?= $countMails; ?>
                </span>
                <?php endif; ?>
                <i class="fa fa-envelope-o"></i>  <span><?= Yii::t('app','Newsletters') ?></span> </i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?= Url::base() . '/newsletter/create'; ?>"><i class="fa fa-circle-o"></i><?= Yii::t('app','Send Newsletter') ?></a></li>
                <li><a href="<?= Url::base() . '/newsletter'; ?>"><i class="fa fa-circle-o"></i><?= Yii::t('app','Mails') ?></a></li>
              </ul>
            </li>

            <li class="h-menu" id="m-link">
              <a href="<?= Url::base() . '/link'; ?>">
                <i class="fa fa-link"></i> <span><?= Yii::t('app','Links') ?></span>
              </a>
            </li>

            <li class="treeview h-menu" id="m-file">
              <a href="#">
                <i class="fa fa-upload"></i>  <span><?= Yii::t('app','Files Manager') ?></span> </i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?= Url::base() . '/file/upload'; ?>"><i class="fa fa-circle-o"></i><?= Yii::t('app','Upload File') ?></a></li>
                <li><a href="<?= Url::base() . '/file/index'; ?>"><i class="fa fa-circle-o"></i><?= Yii::t('app','Files') ?></a></li>
              </ul>
            </li>

            <li id="m-about" class="h-menu">
              <a href="<?= Url::base() . '/about'; ?>">
                <i class="fa fa-info-circle"></i> <span><?= Yii::t('app','About') ?></span>
              </a>
            </li>
            <li id="m-site" class="h-menu">
              <a href="<?= Url::base() . '/site/changepassword'; ?>">
                <i class="fa fa-pencil-square-o"></i> <span><?= Yii::t('app','Change Password') ?></span>
              </a>
            </li>
            <li id="m-setting" class="h-menu">
              <a href="<?= Url::base() . '/setting/update'; ?>">
                <i class="fa fa-cog"></i> <span><?= Yii::t('app','Settings') ?></span>
              </a>
            </li>

          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
          <?= Breadcrumbs::widget([
              'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
          ]) ?>
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><?= $this->title ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-12">
                      <?= Alert::widget() ?>
                      <?= $content ?>
                    </div>
                  </div><!-- /.row -->
                </div><!-- ./box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <footer class="main-footer" style="text-align: center">
        <a href="http://www.developit.ir" target="_blank">رَسپینا، نسخه 1.0.0</a>
      </footer>

      <!-- Control Sidebar -->
      <!-- /.control-sidebar -->
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>

    </div><!-- ./wrapper -->
  <?php $this->endBody() ?>
  </body>
</html>
<?php $this->endPage() ?>