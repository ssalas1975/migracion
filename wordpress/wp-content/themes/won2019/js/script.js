

  url=window.location.href;
  // console.log(url)
  // console.log('res: '+url.indexOf('www'));
  //if(url.indexOf('2019')==-1&&url.indexOf('localhost')==-1){ window.location.href='https://2019.womennow.es/'; }

  var isMobile = false; //initiate as false
  // device detection
  if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
      || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) { 
      isMobile = true;
  }

jQuery(document).ready(function(){

  ///SWIPER
  var mySwiper = new Swiper ('.swiper-container', {
      navigation: {
        nextEl: '.arrowRight',
        prevEl: '.arrowLeft',
      },
      pagination: {
        el: '.bullets',
      },
      loop: true,
      slidesPerView: 1,
      spaceBetween: 0,
    })
 ///SWIPER

  ///HAMBURGUER MENU
  var forEach=function(t,o,r){if("[object Object]"===Object.prototype.toString.call(t))for(var c in t)Object.prototype.hasOwnProperty.call(t,c)&&o.call(r,t[c],c,t);else for(var e=0,l=t.length;l>e;e++)o.call(r,t[e],e,t)};
  var hamburgers = document.querySelectorAll(".hamburger");
  if (hamburgers.length > 0) {
    forEach(hamburgers, function(hamburger) {
      hamburger.addEventListener("click", function() {
        this.classList.toggle("is-active");
      }, false);
    });
  }

  jQuery('.hamburger').click(function(){
    jQuery(this).toggleClass('on');
    jQuery('nav.menu').toggleClass('on');
  })
  ///HAMBURGUER MENU


   ///SUMMIT
    if((jQuery(window).width()+15)<=760){
       jQuery('.summit .fecha:nth-child(2) article').hide()
       if(jQuery('.summit .fecha:nth-child(1) article').length)jQuery('.summit .fecha:nth-child(1) h1.date').toggleClass('on')
       jQuery('.summit .fecha .date').click(function(){
          if(!jQuery(this).hasClass('on')){
            jQuery('.summit .fecha article').hide()
            jQuery(this).parent().find('article').show()
            jQuery('.summit .fecha .date').each(function(){
              jQuery(this).toggleClass('on')
            })
          }
          
       })
    }


  ///SPEAKERS
  jQuery('.speakers li .open').click(function(){
    if(jQuery(this).parent().hasClass('on')){
      jQuery('.speakers li').removeClass('on');
      if(isMobile){jQuery(this).parent().find('div.info').transition({'transform':'translateY(100%)'},350,'ease')}
    }else{
       if(isMobile){jQuery(this).parent().find('div.info').transition({'transform':'translateY(0%)'},350,'ease')}
      jQuery('.speakers li.on').find('div.info').transition({'transform':'translateY(100%)'},350,'ease')
      jQuery('.speakers li').removeClass('on');
      jQuery(this).parent().addClass('on');
    }
  })
  // jQuery('.speakers li:not(.on)').click(function(){
  //   jQuery('.speakers li.on').removeClass('on');
  // })


  // ROLLOVER
   if(!isMobile){
        // rollover speakers
        jQuery('.speakers li').hover(function() {
          jQuery(this).find('div.info').transition({'transform':'translateY(0%)'},350,'ease')
        }, function() {
          if(!jQuery(this).hasClass('on')){
            jQuery(this).find('div.info').transition({'transform':'translateY(100%)'},350,'ease')
          }
        });
       // rollover noticias
      jQuery('.news li').hover(function() {
        jQuery(this).find('.hover').transition({'transform':'translateY(0%)'},350,'ease')
      }, function() {
        if(!jQuery(this).hasClass('on')){
          jQuery(this).find('.hover').transition({'transform':'translateY(-100%)'},350,'ease')
        }
      });
   }
  
   ///ROLLOVER


   ///ANIMACION Y MENU FIXED
   var height_header=jQuery('header .cont-logo').height()+parseInt(jQuery('header .cont-logo').css('margin-top'))+parseInt(jQuery('header .cont-logo').css('margin-bottom'))
    function onscroll(){
      // console.log(jQuery(window).scrollTop())
      var scrollHeight = jQuery(document).height();
      var scrollPosition = jQuery(window).height() + jQuery(window).scrollTop();

      ///animacion
      margen=50;
      if(isMobile){margen=350}
      if (scrollHeight - scrollPosition <= margen ) {
          jQuery('footer').addClass('on')
      }


      ///menu fixed
      // console.log(jQuery(window).width()+15)
      if((jQuery(window).width()+15)>=1024){
        if (jQuery(window).scrollTop() >= height_header) {
          if(!jQuery('header').hasClass('scroll')){
            jQuery('body').css({'padding-top':height_header})
            jQuery('header').addClass('scroll').css({'transform':'translate(-50%,-100%)'}).transition({'transform':'translate(-50%,0%)'},350,'ease')
          }
        }else{
            jQuery('body').css({'padding-top':'0'})
            jQuery('header').removeClass('scroll').css({'transform':''})
        }
      }else{
        jQuery('body').css({'padding-top':'0'})
        jQuery('header').removeClass('scroll').css({'transform':''})
      }
      

    }
    jQuery(window).on('scroll', onscroll);
    onscroll()
   ///ANIMACION


  /* COOKIES  */
  // traduccion  
  var translate_cookie='<span id="cn-notice-text">'+
  'This website uses its own and third party cookies for advertising and analytical purposes to create profiles based on browsing habits.'+  
  '<br>For more information and to change the configuration: '+ 
  '<a href="">Cookies Policy.</a>'+
  '</span><a href="#" id="cn-accept-cookie" data-cookie-set="accept" class="cn-set-cookie cn-button button">OK</a>';

  if(getLangCode!='es'){
    // console.log(jQuery('.cookie-notice-container').html())
    jQuery('.cookie-notice-container').html(translate_cookie)
  }
   // console.log(jQuery('.cookie-notice-container').html())

   /* COOKIES  */


   /* NEWSLETTER  */
    
    jQuery('.popup .envio').click(function(){

      email=jQuery('input[name=email]').val();
      url_form='https://registro.womennow.es/wm2019/es/API/SubscribeEmail';
      if(getLangCode=='en'){url_form='https://registro.womennow.es/wm2019/en/API/SubscribeEmail';}
      ///chequeo el email
      var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
      if (caract.test(email) == false){
        jQuery('.popup .mensaje').text('El email es incorrecto')
      }else{
        jQuery.ajax({
          data:  {"email":email},
          url: url_form , 
          type:  'post',
          success:  function (data) { 
            if(data=='ok'){
              ///saco el cierre
              jQuery('.popup .center').css({'opacity':'0'});
              setTimeout(function(){ 
                jQuery('.popup .init').hide(); jQuery('.popup .close').show()
                jQuery('.popup .center').css({'opacity':'1'}) 
              },850)
            }else if(data=='Tu email ya está subscrito'){
              ///ya recogido
              jQuery('.popup .mensaje').text('Tu email ya está subscrito')
            }else{
              ///error
              jQuery('.popup .mensaje').text('Ha ocurrido algun error. Inténtelo de nuevo más tarde.')
            }
          }
        })
      }
    })

    if(!Cookies.get('women_newsletter')&&jQuery('.popsummit').length){
      setTimeout(function(){
        jQuery('.popup').addClass('on')
        Cookies.set('women_newsletter', 'ok', { expires: 999 });
      },500)
      
      jQuery('.cierre').click(function(){ cierre()})
    }

    // console.log('cookie: '+Cookies.get('women_newsletter'))
    // console.log('popup: '+jQuery('.popsummit').length)

   if(jQuery('.cierreX').length){ 
    jQuery('.cierreX').click(function(){ cierre()})}
    function cierre(){
      jQuery('.popup').removeClass('on')
      if(jQuery('#iframe').length){
        $f(jQuery('#iframe')[0]).api("pause");
      }
    }
   /* NEWSLETTER  */


    /* MAPS  */
    jQuery('.link.map').click(function(){ jQuery('.popup').addClass('on') })
    /* MAPS  */



      //////////
      //RESIZE
      /////////

      jQuery(window).resize(rescalo);
      function rescalo(){

       ///NOTICIAS
       //ajustando el alto
       if(jQuery('.news li').length){
        alto=0;
        jQuery('.news li').each(function(){
          jQuery(this).height('auto')
          if(jQuery(this).height()>alto){alto=jQuery(this).height()}
        })
        jQuery('.news li').height(alto+45)
       }
      
        ///PLANES
       //ajustando el alto
       if(jQuery('.planes').length){
        
        jQuery('.planes a').each(function(){
          jQuery(this).find('li.texto').width(jQuery(this).width()-jQuery(this).find('li.imagen').width()-32)
        })
       }

      }
      rescalo()

}) 
