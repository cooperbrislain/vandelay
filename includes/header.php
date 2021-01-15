<header>
    <div id="art"></div>
    <h1>Vandelay</h1>
    <h3>Importer/Exporter</h3>
    <nav>
        <? foreach ($pages as $page) { ?>
            <a href="?page=<?=$page?>"><?=ucfirst($page)?></a>
        <? } ?>
    </nav>
</header>