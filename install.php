<!doctype html>
<html lang="fa-IR">
<head>
    <meta charset="utf-8">
    <title>Raspina - Install</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=0.9, user-scalable=no" />
    <link rel="stylesheet" href="backend/web/css/bootstrap.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        body {font-family: Tahoma; direction: rtl; font-size: 12px;}
        .input-box {}
        .input-box input{width: 100%; padding: 10px; border: 1px solid #cccccc; font-family: Tahoma; border-radius: 2px; margin-bottom: 20px}
        .input-box div{ margin-bottom: 6px;}
        .btn-primary {font-size: 12px;}
        .error {background: #d9534f; color: #ffffff; padding: 15px; margin-bottom: 25px; border-radius: 4px;}
        .success {background: #00a65a; color: #ffffff; padding: 15px; margin-bottom: 25px; border-radius: 4px;}
        .error a{color: #ffffff; font-weight: bold}
        .success a{color: #ffffff; font-weight: bold}
    </style>
</head>
<body>
<div class="container">
    <form action="" method="post">
    <div class="row col-lg-12">
        <br><br>
        <div class="col-lg-12">

        <?php
        if(isset($_POST['data']) && !empty($_POST['data']))
        {
            $data = $_POST['data'];
            foreach ($data as $k => $v)
            {
				$v = trim($v); // 
                if(empty($v))
                {
                    echo '<div class="error">تمام فیلد ها اجباری هستند لطفا آنها را با دقت پر کنید. <a href="install.php">صفحه نصب رسپینا</a></div>';
                    return false;
                }

                $data[$k] = trim($v);
            }

            $len = mb_strlen($data['url']);
            $url = $data['url'];
            if($url[$len-1] != '/')
            {
                $data['url'] .= '/';
            }

            // db config
            try
            {
                $db = new PDO("{$data['dbms']}:host={$data['host']};dbname={$data['db_name']}",$data['db_username'],$data['db_password']);
            }
            catch (Exception $e)
            {
                $message = $e->getMessage();
                echo '<div class="error">به دلیل خطای زیر امکان اتصال به پایگاه داده وجود ندارد. <a href="install.php">صفحه نصب رسپینا</a></div>';
                echo '<div class="error" dir="ltr">' . $message . '</div>';
                return false;
            }

            $db_config = '<?php' . "\n";
            $db_config .= "define('DBMS','{$data['dbms']}');\n";
            $db_config .= "define('DB_HOST','{$data['host']}');\n";
            $db_config .= "define('DB_NAME','{$data['db_name']}');\n";
            $db_config .= "define('DB_USER_NAME','{$data['db_username']}');\n";
            $db_config .= "define('DB_PASSWORD','{$data['db_password']}');\n";
            try
            {
                file_put_contents('common/config/db_config.php',$db_config);
            }
            catch (Exception $e)
            {
                $message = $e->getMessage();
                echo '<div class="error">به دلیل خطای زیر امکان ایجاد فایل db_config.php در مسیر common/config/  . <a href="install.php">صفحه نصب رسپینا</a>وجود ندارد</div>';
                echo '<div class="error" dir="ltr">' . $message . '</div>';
                return false;
            }

            // run migrate
            $migrate = exec('php yii migrate --interactive=0 --migrationPath=@console/migrations');
            if($migrate != 'Migrated up successfully.')
            {
                echo '<div class="error">به دلیل خطای زیر جداول دیتابیس ایجاد نشده اند. <a href="install.php">صفحه نصب رسپینا</a></div>';
                echo '<div class="error" dir="ltr">' . $migrate . '</div>';
                return false;
            }
            // add user


            defined('YII_DEBUG') or define('YII_DEBUG', false);
            defined('YII_ENV') or define('YII_ENV', 'prod');

            require('vendor/autoload.php');
            require('vendor/yiisoft/yii2/Yii.php');
            require('common/config/bootstrap.php');
            require('frontend/config/bootstrap.php');

            $config = yii\helpers\ArrayHelper::merge(
                require('common/config/main.php'),
                require('common/config/main-local.php'),
                require('frontend/config/main.php'),
                require('frontend/config/main-local.php')
            );

            $application = new yii\web\Application($config);

            $model = new \frontend\models\SignupForm();
            $signupData = [
                'username' => $data['username'],
                'password' => $data['password'],
                'email' => $data['email']
            ];

            if((trim($data['url'])) == 'http://www.')
            {
                echo '<div class="error">آدرس وبسایت وارد شده صحیح نیست</div>';
                return false;
            }

            if(mb_strlen(trim($data['username'])) < 4)
            {
                echo '<div class="error">نام کاربری حداقل باید 5 کاراکتر باشد</div>';
                return false;
            }

            if(mb_strlen(trim($data['password'])) < 5)
            {
                echo '<div class="error">کلمه عبور حداقل باید 6 کاراکتر باشد</div>';
                return false;
            }

            if(!preg_match("/^[A-Za-z0-9\\.|-|_]*[@]{1}[A-Za-z0-9\\.|-|_]*[.]{1}[a-z]{2,5}$/",($data['email'])))
            {
                echo '<div class="error">ایمیل وارد شده معتبر نیست</div>';
                return false;
            }
            $user = $model->signup($data['username'],$data['password'],$data['email']);
            if(empty($user->username))
            {
                echo '<div class="error">متاسفانه مشکلی در هنگام ایجاد حساب کاربری به وجود آمد، تمام محتوای دیتابیس را پاک کنید و از نو عملیات نصب را انجام دهید</div>';
                return false;
            }

            $link = new \backend\models\Link();
            $link->title = 'انجمن ایران پی اچ پی';
            $link->url = 'http://www.forum.iranphp.org/index.php';
            $link->save();

            $link2 = new \backend\models\Link();
            $link2->title = 'احسان رضایی - توسعه دهنده وب';
            $link2->url = 'http://www.developit.ir';
            $link2->save();

            $setting = new \backend\models\Setting();
            $setting->url = $data['url'];
            $setting->title = $data['title'];
            $setting->template = 'default';
            $setting->page_size = 20;
            $setting->date_format = 'HH:mm - yyyy/MM/dd';
            $setting->sult = substr(md5(time()),0,10);
            if(!$setting->save())
            {
                echo '<div class="error">متاسفانه مشکلی در هنگام ایجاد تنظیمات به وجود آمد، تمام محتوای دیتابیس را پاک کنید و از نو عملیات نصب را انجام دهید</div>';
                return false;
            }

            echo '<div class="success">عملیات نصب با موفقیت انجام شد. جهت حفظ امنیت سایت هر چه سریعتر فایل install.php را از هاست خود حذف کنید. <a href="'.$data['url'].'">مشاهده وبسایت</a></div>';
            return false;
        }

        ?>
        </div>
        <div class="col-lg-12" style="margin: 0px auto">
            <div class="panel panel-default">
                <div class="panel-heading">بررسی نیازمندی ها</div>
                <div class="panel-body">
                    قبل از نصب لطفا
<a href="requirements.php" target="_blank">نیازمندی های نرم افزار</a>
                     را مشاهده و بررسی کنید.
                </div>
            </div>
        </div>
        <div style="clear:both;"></div>
        <br>
        <div class="col-lg-12" style="margin: 0px auto">
            <div class="panel panel-default">
                <div class="panel-heading">تنظیمات دیتابیس</div>
                <div class="panel-body">

                    <div class="input-box">
                        <div>سیستم مدیریت پایگاه داده</div>
                        <input type="text" name="data[dbms]" value="mysql" dir="ltr">
                    </div>

                    <div class="input-box">
                        <div>هاست</div>
                        <input type="text" name="data[host]" value="localhost" dir="ltr">
                    </div>

                    <div class="input-box">
                        <div>نام دیتابیس</div>
                        <input type="text" name="data[db_name]" value="" dir="ltr">
                    </div>

                    <div class="input-box">
                        <div>نام کاربری دیتابیس</div>
                        <input type="text" name="data[db_username]" value="" dir="ltr">
                    </div>

                    <div class="input-box">
                        <div>کلمه عبور دیتابیس</div>
                        <input type="password" name="data[db_password]" value="" dir="ltr">
                    </div>

                </div>
            </div>
        </div>
        <div style="clear:both;"></div>
        <br>
        <div class="col-lg-12" style="margin: 0px auto">
            <div class="panel panel-default">
                <div class="panel-heading">تنظیمات وبسایت</div>
                <div class="panel-body">

                    <div class="input-box">
                        <div>آدرس وبسایت <span>به عنوان مثال: www.site.ir</span></div>
                        <input type="text" name="data[url]" value="http://www." dir="ltr">
                    </div>

                    <div class="input-box">
                        <div>عنوان وبسایت</div>
                        <input type="text" name="data[title]" value="">
                    </div>

                    <div class="input-box">
                        <div>نام کاربری(جهت لاگین در پنل مدیریت، حداقل 5 کاراکتر)</div>
                        <input type="text" name="data[username]" value="" dir="ltr">
                    </div>

                    <div class="input-box">
                        <div>آدرس ایمیل(<span>جهت بازیابی کلمه عبور آدرس ایمیل حقیقی خود را وارد کنید</span>)</div>
                        <input type="text" name="data[email]" value="" dir="ltr">

                    </div>

                    <div class="input-box">
                        <div>کلمه عبور (<span>حداقل 6 کاراکتر، از کلمه های عبور ساده و قابل حدس استفاده نکنید</span>)</div>
                        <input type="password" name="data[password]" value="" dir="ltr">
                    </div>

                </div>
            </div>
        </div>
        <div style="clear:both;"></div>
        <br>
        <div class="col-lg-12" style="margin: 0px auto">
            <div class="panel panel-default">
                <div class="panel-heading">نصب رَسپینا</div>
                <div class="panel-body">
                <div style="text-align: center">ممکن است عملیات نصب کمی طول بکشد، لطفا شکیبا باشید</div><br>
                <div style="text-align: center"><button class="btn btn-primary">نصب</button></div>

                </div>
            </div>
        </div>
        <div style="clear:both;"></div>
        <br>

        <br><br>
    </div>
    </form>
</div>


</body>
</html>
