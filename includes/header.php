<?php
require_once("data.php");
require_once("opts.php");
?>
<header>
    <div id="art"></div>
    <h1>Vandelay</h1>
    <h3>Importer/Exporter</h3>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand"></a>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <? foreach ($pages as $page) { ?>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="?page=<?=$page?>"><?=ucfirst($page)?></a>
                    </li>
                <? } ?>
            </ul>

        </div>
    </nav>
</header>