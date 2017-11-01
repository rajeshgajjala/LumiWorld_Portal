<?php
    $filename = basename($_SERVER['PHP_SELF']);
?>
<div class="left-margin clearfix">
	<nav class="left-menu">
		<ul>
			<li id="menu-item-search">
				<a href="search.php">
                    <?php if($filename == 'search.php') {?>
                    <img src="img/08_search button_red.png" alt="search" />
                    <?php } else{ ?>
                    <img src="img/04_search button_black.png" alt="search" />
                    <?php }?>
                </a>
				<ul id="search-menu">
					<li value="0" style="font-weight: 700;">Individuals</li>
					<li value="1">Groups</li>
				</ul>
			</li>
			<li id="menu-item-reach">
				<a href="reach.php">
                    <?php if($filename == 'reach.php') {?>
                    <img src="img/09_reach button_red.png" alt="reach" />
                    <?php } else{ ?>
                    <img src="img/05_reach button_black.png" alt="reach" />
                    <?php }?>
                </a>
			</li>
			<li id="menu-item-engage">
                <?php if($filename == 'engage.php') {?>
				<img src="img/10_engage button_red.png" alt="engage" />
                <?php } else{ ?>
                <img src="img/06A_engage button_greyed out.png" alt="engage" />
                <?php }?>
			</li>
			<li id="menu-item-analyse">
                <a href="analyse.php">
                    <?php if($filename == 'analyse.php') {?>
				    <img src="img/11_analyse button_red.png" alt="analyse" />
                    <?php } else{ ?>
                    <img src="img/07_analyse button_black.png" alt="analyse" />
                    <?php }?>
                </a>
			</li>
		</ul>
	</nav>
</div>
