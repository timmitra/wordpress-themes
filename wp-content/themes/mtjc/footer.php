<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
	</div><!-- #main .wrapper -->
	<footer id="colophon" role="contentinfo">
		<div class="site-info">
		The More Than Just Code podcast is published by <a href="http://www.it-guy.com">iT Guy Technologies</a> - &copy; <?php echo date('Y'); ?>.<br />
		The opinions expressed are those of the participants. Sorry, we are not looking for content contributors to the blog.<br />
		<em>Hey, our show is sometimes sponsored and we use affiliate links in order to support our podcast, so please consider clicking our links if any of the books interest you.</em>
			<a rel="me" href="https://universeodon.com/@mtjc">MTJC on Mastodon</a>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->


<?php wp_footer(); ?>
<script type="text/javascript" src="//s3.amazonaws.com/downloads.mailchimp.com/js/signup-forms/popup/embed.js" data-dojo-config="usePlainJson: true, isDebug: false"></script><script type="text/javascript">require(["mojo/signup-forms/Loader"], function(L) { L.start({"baseUrl":"mc.us9.list-manage.com","uuid":"5a8aead023dbf959b6899c83a","lid":"269594c0f5"}) })</script>
</body>
</html>