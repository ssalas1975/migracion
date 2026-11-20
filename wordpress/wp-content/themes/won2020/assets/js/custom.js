jQuery(document).ready(function () {

$(".cookies-legal").on("click", function(e){ 
    e.preventDefault();
    Voonto.cmp.show();
});

    //menu-sublist//
var list = document.getElementsByClassName(' nav-item select');
var width = window.innerWidth;
if (width < 992) {
  $('.nav-sublist').addClass('open');
  $('.arrow').addClass('open');
}
for (var i = 0, len = list.length; i < len; i++) {
  if (width > 992) {
    list[i].addEventListener("mouseover", function () {
      //fallando
      this.children[1].classList.add("open");
    })
    list[i].addEventListener("mouseout", function () {
      //fallando
      this.children[1].classList.remove("open");
    })
  }
}



    if (!('loading' in HTMLImageElement.prototype)) {
        const images = document.querySelectorAll('img[loading="lazy"]');
        images.forEach(img => {
            img.onload = "lzld(this)";
        });
        const script = document.createElement('script');
        script.src =
            'https://www.womennow.es/wp-content/themes/won2020/assets/js/lazyload.js';
        document.body.appendChild(script);
    } else {
        const images = document.querySelectorAll('img[loading="lazy"]');
        images.forEach(img => {
            console.log(img.dataset.src);
            img.src = img.dataset.src;
        });
    }
});