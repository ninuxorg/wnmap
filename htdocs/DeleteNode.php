<?php require ("config.php"); ?>

<p><?php echo ARE_YOU_SURE;?></p>

<p><a href="DeleteNodeYes.php?hash=<?php echo $_GET["hash"];?>"><?php echo YES_DELETE;?></a></p>

<p><a href="<?php echo MAP_URL;?>"><?php echo DONT_DELETE;?></a></p>
