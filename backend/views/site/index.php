<?php
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
?>

<div class="site-index">

    <div class="jumbotron">
        <h1>The website pages</h1>
    </div>
    <div class="body-content">
        <div class="row">
            <div class="col-lg-4">
                <h2>User</h2>
                <p><a class="btn btn-default" href="/admin/user">User &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Workshops</h2>
                <p><a class="btn btn-default" href="/admin/workshops">Workshops &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Append users to a workshop</h2>
                <p><a class="btn btn-default" href="/admin/user-workshops/create">Append users to a workshop &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
