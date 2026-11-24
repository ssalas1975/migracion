<footer class="piedepagina py-1 lineasup" role="contenido">

  <div class="container row" id="footer-margin">
    <div class="col-sm-12 up-factor-moka"><a href="#">SUBIR</a></div>


    <div class="col-6 menu-piedepagina">
      <img class="logo" src="<?php echo get_stylesheet_directory_uri()?>/img/moka-footer.svg" alt="hamburguesa">
      <ul class="redes-sociales">
       <li><a href="https://www.linkedin.com/company/25065146/" target="blank"><i id="footer-icon" class="fa fa-linkedin" aria-hidden="true"></i></a></li>
       <li><a href="https://twitter.com/FactorMoka" target="blank"><i id="footer-icon" class="fa fa-twitter" aria-hidden="true"></i></a></li>
       <li><a href="https://www.facebook.com/AgenciaFactorMoka/" target="blank"><i id="footer-icon" class="fa fa-facebook" aria-hidden="true"></i></a></li>
     </ul>
     <a href="mailto:info@factormoka.es">info@factormoka.es</a>

     <div class="vocento15">
      <img src="<?php echo get_stylesheet_directory_uri()?>/img/vocento15.svg" alt="">
    </div>

  </div>

  <div class="col-6" id="list-footer">
  <?php $items=wp_get_nav_menu_items('principal'); ?>
    <ul>
     <?php foreach($items as $item){?>
     <li><a href="<?php echo $item->url?>"><?php echo $item->title?></a></li>
	<?php }?>
	 <li><a target="blank" href="http://www.vocento.com/politica-cookies">Política de cookies</a></li>
	  <li><a target="blank" href="https://www.factormoka.es/political-policy.html">Política de privacidad</a></li>
    <li><a target="blank" href="https://www.factormoka.es/legal-warning.html">Aviso legal</a></li>
   </ul>
 </div>
  <div class="col-sm-12 p-hola orange muestra"><p style="height: 50px"></p></div>
  <div class="col-sm-6 factor-moka"><p>2017 © Factor Moka S.L. Todos los derechos reservados. </p></div>
  <div class="col-sm-6 desktop-factor-moka"><a href="#">SUBIR<img class="arrow" src="<?php echo get_stylesheet_directory_uri()?>/img/arrow-top.svg" alt=""></a></div>
    </div>
</footer>
<?php wp_footer()?>
<script>
	Voonto.ready.then(function(app) {
		app.when('consent:advertising').then(function(result) {
			window.googletag.pubads().setRequestNonPersonalizedAds(result ? 0 : 1);
		});
		app.on('consent:updated:advertising', function(event, result) {
			window.googletag.pubads().setRequestNonPersonalizedAds(result ? 0 : 1);
		});
	});
</script>

</body>
</html>
