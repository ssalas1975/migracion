
    <script type="application/ld+json">
      {
        "@context": "http://schema.org",
        "@type": "Blog",
		"blogPosts":{"@type": "BlogPosting"},
		"articleBody": "<?php echo trim(strip_tags(get_the_content()))?>",
		"author": "Local Digital Kit",
		"copyrightHolder": "Local Digital Kit",
		"copyrightYear": "<?php echo date("Y"); ?>",
        "headline": "<?php echo get_the_title() ?>",
        "datePublished": "<?php echo get_the_date('Y-m-dTh:i:s')?>",
        "dateModified": "<?php echo get_the_modified_date('Y-m-dTh:i:s')?>",
        "description": "<?php echo trim(strip_tags(get_the_excerpt()))?>",
        "publisher": "Medio de comunicación",
        "image": {
          "@type": "ImageObject",
          "contentUrl": "<?php echo ldk_get_thumbnail()?>",
		  "alternativeHeadline": "<?php echo get_the_title()?>"
  
        }
      }

    </script>