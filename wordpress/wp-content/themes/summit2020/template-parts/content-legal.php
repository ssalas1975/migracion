    
    <!-- START: content-->
    <main class="content">
        <section class="section section-static">
            <div class="wrap">
                <article class="card card-static">
                    <header class="top">
                        <h1 class="title"><?php the_title(); ?></h1>
                    </header>
                    <div class="body">
                        <?php echo $post->post_content; ?>
                    </div>
                </article>
            </div>
        </section>
        <nav class="section section-nav">
            <div class="section__container">
            <a href="#" class="btn btn-red"><?php _e('Regístrate','summit2020_wpml'); ?></a>
            </div>
        </nav>
    </main>
    <!-- END: content-->