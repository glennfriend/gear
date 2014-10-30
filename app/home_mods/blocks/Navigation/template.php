<?php
    $homeUri = url('');
?>
<div class="navbar navbar-inverse navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="javascript:;">Gear</a>
        </div>
        <div class="navbar-collapse collapse">

            <ul class="nav navbar-nav">
                <!--
                    <li class="active">Home</li>
                -->
                <li><a href="<?php echo $homeUri; ?>">Home</a></li>
            </ul>

            <ul class="nav navbar-nav pull-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hello<b class="caret"></b></a>
                    <ul class="dropdown-menu"><li class="dropdown-header">User</li>
                        <li><a href="javascript:;">User Name</a></li>
                        <li class="dropdown-header">Status</li>
                        <li><a href="javascript:;">Logout</a></li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>
</div>
