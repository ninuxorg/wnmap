<? require ("config.php"); ?>

<p><?=ARE_YOU_SURE?></p>

<p><a href="DeleteNodeYes.php?hash=<?=$_GET["hash"]?>"><?=YES_DELETE?></a></p>

<p><a href="<?=MAP_URL?>"><?=DONT_DELETE?></a></p>
