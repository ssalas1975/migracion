

<footer> 

	<?php $name=$post->post_name; 
	if($name!='aviso-legal'&&$name!='politica-de-privacidad'&&$name!='politica-de-cookies'&&$name!='registrate'&&$name!='register'): ?>
		<section class="registro">
			<?php dynamic_sidebar('Link Footer'); ?>
		</section>
	<?php endif; ?>

	<section class="cont-footer">
		<?php if($name!='aviso-legal'&&$name!='politica-de-privacidad'&&$name!='politica-de-cookies'): ?> ?>
			<div class="animacion col">
				<span class="c3"></span>
				<span class="c3"></span>
				<span class="c3"></span>
			</div>
		<?php endif; ?>
		<span class="container">
			<img class="logo" src="<?php echo get_template_directory_uri();?>/images/logo_footer.png"> 

			<article class="center col">
				<a href="http://www.tallerdeeditores.com/">
					<img src="<?php echo get_template_directory_uri();?>/images/footer_logo2.png">
				</a>
				<a href="https://www.vocento.com/">
					<img src="<?php echo get_template_directory_uri();?>/images/footer_logo1.png">
				</a>  
				<p><?php _e( '© WOMEN NOW SUMMIT.<br> Todos los derechos reservados', 'won2019' ); ?></p>
			</article>
			
			<span class="legales-rrss vcenter">
				<nav class="legales">
					<?php wp_nav_menu(array( 'theme_location' => 'menu-legales','container' => 'ul')); ?>
				</nav>
				<nav class="rrss">
			        <a href="https://www.instagram.com/womennowsp/?hl=es"><i class="icon-inst"></i></a>
			        <a href="https://twitter.com/WomenNOWSP?lang=es"><i class="icon-twt"></i></a>
			        <a href="https://www.linkedin.com/company/womennow-summit/"><i class="icon-link"></i></a>
			    </nav>
			</span>
			
		</span>
	</section>
		

</footer>

<?php wp_footer(); ?>

</body>
</html>