<?php
/*
Template Name: Blog
*/
?>
<?php get_header()?>
<script src="<?php echo get_stylesheet_directory_uri()?>/js/blog_pagination.js"></script>

<section class="blog blog-template">
  <div class="father-contenedor">
    <div class="blog2">
      <h1>
        <img src="<?php echo get_stylesheet_directory_uri()?>/img/blog.svg" class="blogblog" alt="moka">
      </h1>
    </div>

    <div class="row" id="contenedor-grip-blog">
      <div class="col-md-6" id="columm-A">
		
      </div>
      <div class="col-md-6" id="columm-B">
       
      </div>
    </div>



    <div class="boton-blog" id="loadmore" style="display:none;">
      <div class="btn btn-primary btn-lg"  id="btn-blog">CARGAR MÁS<span class="flecha"></span></div>
    </div>



  </div> 
</section>

<script id="blogitem" type="x-tmpl-mustache">
    {{#.}}
        <article class="grip">
          <figure>
            <img src="{{image}}" alt="{{post_title}}">
          </figure>
          <h3>
            <a href="{{permalink}}" class="enlaces-blog">{{post_title}}</a>
          </h3>
          <span class="fecha">
            <time>
               {{date}}
          </time>
          </span>
          <p>
             {{post_excerpt}}
          </p>
          <div class="line"></div>
        </article>
    {{/.}}
</script>


<?php get_footer()?>