 <a href="<?php the_permalink()?>">
                    <div class="project proy<?php echo $project_index?>" id="proy">
                      <div class="pastilla-project">
                        <h4><?php the_title()?></h4>
                        <p><?php echo get_field('subtitulo')?></p>
                      </div>
                      <img class="home-project" src="<?php echo moka_get_image_listado(get_the_ID())?>" alt="">
                    </div>
                  </a>