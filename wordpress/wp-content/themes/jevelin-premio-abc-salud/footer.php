<?php
/**
 * Footer
 */
?>

	<?php if (jevelin_post_option(get_the_ID(), 'page_layout') != 'full') : ?>
		</div>
	<?php endif; ?>
	</div>

		<footer class="sh-footer">
			<?php if (jevelin_footer_enabled() == 'on') : ?>

				<div class="sh-footer-widgets">
					<div class="container">
						<div class="sh-footer-columns">
							<?php
                                /* Show theme footer widgets */
                                if (is_active_sidebar('footer-widgets')) :
                                    dynamic_sidebar('footer-widgets');
                                elseif (is_active_sidebar('footer_widgets')) :
                                    dynamic_sidebar('footer_widgets');
                                endif;
                            ?>
						</div>
					</div>
				</div>

			<?php endif; ?>
			<?php
                if (jevelin_copyrights_enabled() == 'on') :
                    /* Inlcude theme copyrights bar */
                    get_template_part('inc/templates/copyrights');
                endif;
            ?>
		</footer>
	</div>


	<?php if (jevelin_post_option(get_the_ID(), 'back_to_top') != 'none') :

        /* Inlcude back to top button HTML */
        get_template_part('inc/templates/back_to_top');

    endif; ?>
</div>

<?php wp_footer(); ?>

	<script>
		// const x = document.referrer;
		// x.toUpperCase();
		// console.log('REFERER',x);
		// let n = x.includes('candidaturas');
		// if(n){
		// 	document.querySelector('.juradosback').style.display = "block";
		// 	document.querySelector('.btnhomejurado').style.display = "none";
		// 	document.querySelector('.header-logo a').removeAttribute("href"); 
		// }else{
		// 	document.querySelector('.juradosback').style.display = "none";
		// }


		const setLocalStorage = function (key, value, ttl){
			const now = new Date();
			const item = {
				value: value,
				expiry: now.getTime() + ttl,
			}
			localStorage.setItem(key, JSON.stringify(item))
		}

		const getWithExpiry = function(key) {
			const itemStr = localStorage.getItem(key);
			// if the item doesn't exist, return null
			if (!itemStr) {
				return null
			}
			const item = JSON.parse(itemStr)
			const now = new Date()
			// compare the expiry time of the item with the current time
			if (now.getTime() > item.expiry) {
				// If the item is expired, delete the item from storage
				// and return null
				localStorage.removeItem(key)
				return null
			}
			return item.value
		}


		const currentUrl = window.location.href;
		let validarJurado = currentUrl.includes('candidaturas');

		if(validarJurado){
			setLocalStorage('jurado', 'true', 86400000);
		}
		const ifJurado = getWithExpiry("jurado");

		if(ifJurado){
			console.log('...');
			//document.querySelector('.juradosback').style.display = "block";
			document.querySelector('.header-logo a').removeAttribute("href"); 
		}else{
			console.log('......');
			const elementos = document.querySelectorAll('.juradosback');
			elementos.forEach( function(elemento) {
				elemento.style.display = "none"; 
			});
		}

		
	</script>
</body>
</html>
