<?php
script('mailtest', 'script');
style('mailtest', 'style');
?>

<div id="app">
	<div id="app-navigation">
		<?php print_unescaped($this->inc('navigation/index')); ?>
		<?php print_unescaped($this->inc('settings/index')); ?>
	</div>

	<div id="app-content">
		<div id="app-content-wrapper">
			<iframe src="<?php p(\OC::$server->getURLGenerator()->linkToRouteAbsolute('mailtest.page.mail')); ?>"></iframe>
		</div>
	</div>
</div>

