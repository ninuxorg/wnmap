<? require ("config.php"); ?>

<p>Are you <strong>sure</strong> you want to delete this node?</p>

<p><a href="DeleteNodeYes.php?hash=<?=$_GET["hash"]?>">Yes, delete it.</a></p>

<p><a href="<?=MAP_URL?>">Nope, I changed my mind.</a></p>
