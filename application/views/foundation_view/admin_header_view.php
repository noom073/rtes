<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="shortcut icon" href="<?= base_url('assets/images/RTES1.png') ?>" type="image/x-icon">
        <title><?= $title ?></title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark my-bg-blue">
            <div class="m-3">
                <a class="navbar-brand" href="#">
                    <img src="<?= base_url('assets/images/RTES1.png') ?>" width="75" alt="RSES">
                </a>            
            </div>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li id="admin-index" class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/index') ?>">ห้องสอบ</a>
                    </li>
                    <li id="admin-manage-round" class="nav-item">
                        <a class="nav-link" href="<?= site_url('manage_round/index') ?>">วัน-เวลา การทดสอบ</a>
                    </li> 
                    <li id="admin-score" class="nav-item">
                        <a class="nav-link" href="<?= site_url('score/index') ?>">อัพโหลดคะแนนผู้สอบ</a>
                    </li> 
                    <li id="admin-search" class="nav-item">
                        <a class="nav-link" href="<?= site_url('search/index') ?>">ค้นหาคะแนน</a>
                    </li> 
                    <li id="admin-view-log" class="nav-item">
                        <a class="nav-link" href="<?= site_url('view_log/index') ?>">Log</a>
                    </li>                  
                </ul>

                <div>
                    <button id="log-out" class="btn btn-primary my-2 my-sm-0" type="button">ออกจากระบบ</button>
                    <div id="logout-txt"></div>
                </div>
            </div>
        </nav>
        