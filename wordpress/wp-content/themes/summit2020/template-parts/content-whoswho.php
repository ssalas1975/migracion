    
    <!-- START: content-->
    <main class="content">
        <!-- START: section whos-who -->
        <section class="section section-whos-who">
            <div class="section__container">
            <div class="section__claim">
                <span><?php the_title() ?></span>
            </div>
            <div class="shape shape-1 rotate-180"></div>
            <div class="shape shape-2"></div>
            <article class="txt">
               <?php echo $post->post_content; ?>
            </article>
            </div>
        </section>
        <!-- END: section section-whos-who -->

        <nav class="section section-nav">
            <div class="section__container">
            <a href="#" class="btn btn-red">Regístrate</a>
            </div>
        </nav>
    </main>
    <!-- END: content-->