<?php if (!empty($merchants)): ?>
    <div class="relacionados-module desktop">
        <div class="title-box">
            <h2 class="relacionados-title"><a href="<?php echo $viewMore; ?>"><?php echo $title; ?></a></h2>
        </div>
        <div class="wrapper">
            <div class="carousel">
                <?php foreach ($merchants as $merchant): ?>
                <div class="item"><a href="<?php echo $merchant['url']; ?>"><div class="item-image"><img src="<?php echo $merchant['image']; ?>" alt=""></div></a>
                    <div class="item-content">
                        <h3 class="item-title"><a href="<?php echo $merchant['url']; ?>"><?php echo $merchant['title']; ?></a></h3>
                        <h3 class="item-description"><?php echo $merchant['address']; ?></h3>
                        <a href="<?php echo $merchant['url']; ?>"><button class="item-button">RESERVAR</button></a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="relacionados-module mobile">
        <div class="title-box">
            <h2 class="relacionados-title"><a href="<?php echo $viewMore; ?>"><?php echo $title; ?></a></h2>
        </div>
        <div class="wrapper">
            <div class="carousel mobile">
                <?php foreach ($merchants as $merchant): ?>
                <div class="item"><a href="<?php echo $merchant['url']; ?>"><div class="item-image"><img src="<?php echo $merchant['image']; ?>" alt=""></div></a>
                    <div class="item-content">
                        <h3 class="item-title"><a href="<?php echo $merchant['url']; ?>"><?php echo $merchant['title']; ?></a></h3>
                        <h3 class="item-description"><?php echo $merchant['address']; ?></h3>
                        <a href="<?php echo $merchant['url']; ?>"><button class="item-button">RESERVAR</button></a>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if($viewMoreMobile): ?>
                <div class="item more">
                    <div class="item-content">
                        <h3 class="item-title"><a href="<?php echo $viewMore; ?>">VER MAS</a></h3>
                        <a class="item-button" href="<?php echo $viewMore; ?>">+</a>
                    </div>
                </div>
				<?php endif; ?>
            </div>
        </div>
    </div>
<?php endif;?>