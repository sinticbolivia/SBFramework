<?php
?>
	<footer id="footer">
		<p id="copyright">
			<?php if( defined('FOOTER_TEXT') ): ?>
				<?php if( defined('FOOTER_LINK') && strstr(FOOTER_LINK, 'http') ): ?>
				<a href="<?php print FOOTER_LINK; ?>" target="_blank">
				<?php endif; ?>
				<?php print str_replace('[date_year]', date('Y'), FOOTER_TEXT); ?>
				<?php if( defined('FOOTER_LINK') && strstr(FOOTER_LINK, 'http') ): ?></a><?php endif; ?>
			<?php else: ?>
				<?php if( defined('FOOTER_LINK') && strstr(FOOTER_LINK, 'http') ): ?>
				<a href="<?php print FOOTER_LINK; ?>" target="_blank"><?php print FOOTER_LINK; ?></a>
				<?php endif; ?>
			<?php endif; ?>
		</p>
	</footer><!-- end id="footer" -->
</div><!-- end id="container" -->
<?php lt_footer(); ?>
</body>
</html>