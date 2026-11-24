 <article class="grip">
          <figure>
            <img src="<?php echo moka_get_image_listado(get_the_ID())?>" alt="<?php the_title()?>">
          </figure>
          <h3>
            <a href="<?php the_permalink()?>" class="enlaces-blog"><?php the_title()?></a>
          </h3>
          <span class="fecha">
            <time>
            <?php the_date()?> 
          </time>
          </span>
          <p>
            <?php echo moka_limit_text(get_the_excerpt(),24)?>
          </p>
          <div class="line"></div>
        </article>