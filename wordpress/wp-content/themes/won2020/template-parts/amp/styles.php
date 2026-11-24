<?php
// Get content width
//$content_max_width       = absint( $this->get( 'content_max_width' ) );
// Get template colors
//$theme_color             = $this->get_customizer_setting( 'theme_color' );
//$text_color              = $this->get_customizer_setting( 'text_color' );
//$muted_text_color        = $this->get_customizer_setting( 'muted_text_color' );
//$border_color            = $this->get_customizer_setting( 'border_color' );
//$link_color              = $this->get_customizer_setting( 'link_color' );
//$header_background_color = $this->get_customizer_setting( 'header_background_color' );
//$header_color            = $this->get_customizer_setting( 'header_color' );
$path = get_stylesheet_directory_uri();
?>

/* Carga de fuentes */

@font-face {
    font-family: 'Catamaran';
    font-style: normal;
    font-weight: 100;
    font-display: optional;
    src: url(https://fonts.gstatic.com/s/catamaran/v8/o-0bIpQoyXQa2RxT7-5B6Ryxs2E_6n1iPHjc5a7duw.woff2) format('woff2');
    unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
  }

  @font-face {
    font-family: 'Catamaran';
    font-style: normal;
    font-weight: 500;
    font-display: optional;
    src: url(https://fonts.gstatic.com/s/catamaran/v8/o-0bIpQoyXQa2RxT7-5B6Ryxs2E_6n1iPErd5a7duw.woff2) format('woff2');
    unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
  }

  @font-face {
    font-family: 'Catamaran';
    font-style: normal;
    font-weight: 700;
    font-display: optional;
    src: url(https://fonts.gstatic.com/s/catamaran/v8/o-0bIpQoyXQa2RxT7-5B6Ryxs2E_6n1iPJ_a5a7duw.woff2) format('woff2');
    unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
  }

  @font-face {
    font-family: 'Noto Serif TC';
    font-style: normal;
    font-weight: 200;
    font-display: optional;
    src: url(https://fonts.gstatic.com/s/notoseriftc/v17/XLY9IZb5bJNDGYxLBibeHZ0Bvr8vbXwuVcgUbQ3zF6M3eg1qzxeg6QwzxcSPzLZfXbm0Gs_7uos.119.woff2) format('woff2');
    unicode-range: U+20-22, U+27-2a, U+2c-3b, U+3f, U+41-5d, U+61-7b, U+7d, U+a0, U+ab, U+ae, U+b2-b3, U+b7, U+bb, U+bf, U+c9, U+cd, U+d6, U+e0-ef, U+f1-f4, U+f6, U+f9-fa, U+fc-fd, U+101, U+103, U+110-111, U+113, U+12b, U+14d, U+16b, U+1a1, U+1b0, U+1ce, U+300-301, U+1ea1, U+1ea3, U+1ebf, U+1ec7, U+2013-2014, U+2039-203a, U+203c, U+2122, U+3001-3002, U+4e00, U+4e0a-4e0b, U+4e0d, U+4e2d, U+4eba, U+4ee5, U+4f5c, U+4f86, U+500b, U+5011, U+5165, U+5168, U+516c, U+51fa, U+5206, U+5230, U+52d5, U+53ef-53f0, U+570b, U+5728, U+5730, U+591a, U+5927, U+5929, U+597d, U+5b50, U+5bb6, U+5c0f, U+5e73-5e74, U+5fc3, U+6210-6211, U+6240, U+6587, U+65b0, U+65b9, U+65bc, U+65e5, U+660e, U+662f, U+6642, U+66f4, U+6700, U+6703, U+6708-6709, U+672c, U+6b63, U+6cbb, U+70b9-70ba, U+751f, U+7528, U+767c, U+7684, U+76f8, U+7db2, U+7f8e, U+80fd, U+81ea, U+884c, U+8981, U+958b, U+95dc, U+96fb, U+9801, U+9996, U+ff0c, U+ff1a;
  }

  @font-face {
    font-family: 'Noto Serif TC';
    font-style: normal;
    font-weight: 300;
    font-display: optional;
    src: url(https://fonts.gstatic.com/s/notoseriftc/v17/XLY9IZb5bJNDGYxLBibeHZ0BvtssbXwuVcgUbQ3zF6M3eg1qzxeg6QwzxcSPzLZfXbm0Gs_7uos.119.woff2) format('woff2');
    unicode-range: U+20-22, U+27-2a, U+2c-3b, U+3f, U+41-5d, U+61-7b, U+7d, U+a0, U+ab, U+ae, U+b2-b3, U+b7, U+bb, U+bf, U+c9, U+cd, U+d6, U+e0-ef, U+f1-f4, U+f6, U+f9-fa, U+fc-fd, U+101, U+103, U+110-111, U+113, U+12b, U+14d, U+16b, U+1a1, U+1b0, U+1ce, U+300-301, U+1ea1, U+1ea3, U+1ebf, U+1ec7, U+2013-2014, U+2039-203a, U+203c, U+2122, U+3001-3002, U+4e00, U+4e0a-4e0b, U+4e0d, U+4e2d, U+4eba, U+4ee5, U+4f5c, U+4f86, U+500b, U+5011, U+5165, U+5168, U+516c, U+51fa, U+5206, U+5230, U+52d5, U+53ef-53f0, U+570b, U+5728, U+5730, U+591a, U+5927, U+5929, U+597d, U+5b50, U+5bb6, U+5c0f, U+5e73-5e74, U+5fc3, U+6210-6211, U+6240, U+6587, U+65b0, U+65b9, U+65bc, U+65e5, U+660e, U+662f, U+6642, U+66f4, U+6700, U+6703, U+6708-6709, U+672c, U+6b63, U+6cbb, U+70b9-70ba, U+751f, U+7528, U+767c, U+7684, U+76f8, U+7db2, U+7f8e, U+80fd, U+81ea, U+884c, U+8981, U+958b, U+95dc, U+96fb, U+9801, U+9996, U+ff0c, U+ff1a;
  }

  @font-face {
    font-family: 'Noto Serif TC';
    font-style: normal;
    font-weight: 500;
    font-display: optional;
    src: url(https://fonts.gstatic.com/s/notoseriftc/v17/XLY9IZb5bJNDGYxLBibeHZ0BvoMtbXwuVcgUbQ3zF6M3eg1qzxeg6QwzxcSPzLZfXbm0Gs_7uos.119.woff2) format('woff2');
    unicode-range: U+20-22, U+27-2a, U+2c-3b, U+3f, U+41-5d, U+61-7b, U+7d, U+a0, U+ab, U+ae, U+b2-b3, U+b7, U+bb, U+bf, U+c9, U+cd, U+d6, U+e0-ef, U+f1-f4, U+f6, U+f9-fa, U+fc-fd, U+101, U+103, U+110-111, U+113, U+12b, U+14d, U+16b, U+1a1, U+1b0, U+1ce, U+300-301, U+1ea1, U+1ea3, U+1ebf, U+1ec7, U+2013-2014, U+2039-203a, U+203c, U+2122, U+3001-3002, U+4e00, U+4e0a-4e0b, U+4e0d, U+4e2d, U+4eba, U+4ee5, U+4f5c, U+4f86, U+500b, U+5011, U+5165, U+5168, U+516c, U+51fa, U+5206, U+5230, U+52d5, U+53ef-53f0, U+570b, U+5728, U+5730, U+591a, U+5927, U+5929, U+597d, U+5b50, U+5bb6, U+5c0f, U+5e73-5e74, U+5fc3, U+6210-6211, U+6240, U+6587, U+65b0, U+65b9, U+65bc, U+65e5, U+660e, U+662f, U+6642, U+66f4, U+6700, U+6703, U+6708-6709, U+672c, U+6b63, U+6cbb, U+70b9-70ba, U+751f, U+7528, U+767c, U+7684, U+76f8, U+7db2, U+7f8e, U+80fd, U+81ea, U+884c, U+8981, U+958b, U+95dc, U+96fb, U+9801, U+9996, U+ff0c, U+ff1a;
  }

  @font-face {
    font-family: 'Noto Serif TC';
    font-style: normal;
    font-weight: 600;
    font-display: optional;
    src: url(https://fonts.gstatic.com/s/notoseriftc/v17/XLY9IZb5bJNDGYxLBibeHZ0Bvq8qbXwuVcgUbQ3zF6M3eg1qzxeg6QwzxcSPzLZfXbm0Gs_7uos.119.woff2) format('woff2');
    unicode-range: U+20-22, U+27-2a, U+2c-3b, U+3f, U+41-5d, U+61-7b, U+7d, U+a0, U+ab, U+ae, U+b2-b3, U+b7, U+bb, U+bf, U+c9, U+cd, U+d6, U+e0-ef, U+f1-f4, U+f6, U+f9-fa, U+fc-fd, U+101, U+103, U+110-111, U+113, U+12b, U+14d, U+16b, U+1a1, U+1b0, U+1ce, U+300-301, U+1ea1, U+1ea3, U+1ebf, U+1ec7, U+2013-2014, U+2039-203a, U+203c, U+2122, U+3001-3002, U+4e00, U+4e0a-4e0b, U+4e0d, U+4e2d, U+4eba, U+4ee5, U+4f5c, U+4f86, U+500b, U+5011, U+5165, U+5168, U+516c, U+51fa, U+5206, U+5230, U+52d5, U+53ef-53f0, U+570b, U+5728, U+5730, U+591a, U+5927, U+5929, U+597d, U+5b50, U+5bb6, U+5c0f, U+5e73-5e74, U+5fc3, U+6210-6211, U+6240, U+6587, U+65b0, U+65b9, U+65bc, U+65e5, U+660e, U+662f, U+6642, U+66f4, U+6700, U+6703, U+6708-6709, U+672c, U+6b63, U+6cbb, U+70b9-70ba, U+751f, U+7528, U+767c, U+7684, U+76f8, U+7db2, U+7f8e, U+80fd, U+81ea, U+884c, U+8981, U+958b, U+95dc, U+96fb, U+9801, U+9996, U+ff0c, U+ff1a;
  }

  @font-face {
    font-family: 'Noto Serif TC';
    font-style: normal;
    font-weight: 900;
    font-display: optional;
    src: url(https://fonts.gstatic.com/s/notoseriftc/v17/XLY9IZb5bJNDGYxLBibeHZ0BvvMpbXwuVcgUbQ3zF6M3eg1qzxeg6QwzxcSPzLZfXbm0Gs_7uos.119.woff2) format('woff2');
    unicode-range: U+20-22, U+27-2a, U+2c-3b, U+3f, U+41-5d, U+61-7b, U+7d, U+a0, U+ab, U+ae, U+b2-b3, U+b7, U+bb, U+bf, U+c9, U+cd, U+d6, U+e0-ef, U+f1-f4, U+f6, U+f9-fa, U+fc-fd, U+101, U+103, U+110-111, U+113, U+12b, U+14d, U+16b, U+1a1, U+1b0, U+1ce, U+300-301, U+1ea1, U+1ea3, U+1ebf, U+1ec7, U+2013-2014, U+2039-203a, U+203c, U+2122, U+3001-3002, U+4e00, U+4e0a-4e0b, U+4e0d, U+4e2d, U+4eba, U+4ee5, U+4f5c, U+4f86, U+500b, U+5011, U+5165, U+5168, U+516c, U+51fa, U+5206, U+5230, U+52d5, U+53ef-53f0, U+570b, U+5728, U+5730, U+591a, U+5927, U+5929, U+597d, U+5b50, U+5bb6, U+5c0f, U+5e73-5e74, U+5fc3, U+6210-6211, U+6240, U+6587, U+65b0, U+65b9, U+65bc, U+65e5, U+660e, U+662f, U+6642, U+66f4, U+6700, U+6703, U+6708-6709, U+672c, U+6b63, U+6cbb, U+70b9-70ba, U+751f, U+7528, U+767c, U+7684, U+76f8, U+7db2, U+7f8e, U+80fd, U+81ea, U+884c, U+8981, U+958b, U+95dc, U+96fb, U+9801, U+9996, U+ff0c, U+ff1a;
  }


.bg-black, .bg-black .container {
background-color: #030303
}

.bg-white, .bg-white .container {
background-color: #fff
}

.bg-grey, .bg-grey .container {
background-color: #ededed
}

body, html {
-webkit-font-smoothing: antialiased;
-moz-osx-font-smoothing: grayscale
}

body {
font-family: Catamaran, sans-serif;
font-weight: 100;
font-style: normal;
margin: 0;
padding: 0;
font-size: 1rem;
color: #030303;
background-color: #fff
}

::-moz-selection {
color: #fff;
background: #55c6c7
}

::selection {
color: #fff;
background: #55c6c7
}

h1, h2, h3, h4, h5, h6 {
font-weight: 400;
font-family: Noto Serif TC, serif;
font-weight: 500;
font-size: 1.25rem;
line-height: 1.3;
margin: 0
}

address, h1, h2, h3, h4, h5, h6 {
font-style: normal
}

p {
margin: 0;
font-size: .875rem;
line-height: 1.43;
color: #030303
}

a {
text-decoration: none;
cursor: pointer;
display: block;
}

p a {
    text-decoration: underline;
    display: inline;
    font-weight: 600;
    color: #030303;
}

button, input, select, textarea {
font-family: Catamaran, sans-serif;
font-size: inherit;
line-height: inherit;
background-color: transparent;
border: none
}

select {
width: 100%;
text-transform: none;
box-sizing: border-box
}

textarea {
min-height: 50px
}

img, textarea {
height: auto
}

img {
max-width: 100%;
-ms-interpolation-mode: bicubic
}

figure {
margin: 0;
text-align: center
}

figcaption {
text-align: left
}

ol, ul {
list-style: none;
margin: 0;
padding: 0
}

.u-flex {
display: -ms-flexbox;
display: flex;
-ms-flex-direction: row;
flex-direction: row;
-ms-flex-pack: center;
justify-content: center;
-ms-flex-align: center;
align-items: center;
-ms-flex-wrap: wrap;
flex-wrap: wrap
}

.u-flex-justify-end {
-ms-flex-pack: end;
justify-content: flex-end
}

.u-flex-justify-start {
-ms-flex-pack: start;
justify-content: flex-start
}

.u-mb40 {
margin-bottom: 20px
}

.u-mb42 {
margin-bottom: 21px
}

.u-mb-0 {
margin-bottom: 0
}

.u-mt40 {
margin-top: 40px
}

.u-mt-0 {
margin-top: 0
}

.u-border-bottom {
border-bottom: 1px solid #030303
}

.u-border-top {
border-top: 1px solid #030303
}

.u-max-width-center {
max-width: 620px;
margin: 0 auto
}

.u-border-none {
border-top: none;
border-bottom: none
}

.u-color-cyan {
color: #55c6c7
}

.u-color-white {
color: #fff
}

.u-color-black {
color: #030303
}

.u-bg-cyan {
background-color: #55c6c7
}

.u-bg-white {
background-color: #fff
}

.u-bg-black {
background-color: #030303
}

.u-sticky {
position: -webkit-sticky;
position: sticky;
top: 0
}

.u-fs-20 {
font-size: 1.25rem
}

.btn-primary {
color: #030303
}

.btn-icon {
border-radius: 50%;
width: 24px;
height: 24px;
display: -ms-flexbox;
display: flex;
-ms-flex-pack: center;
justify-content: center;
-ms-flex-align: center;
align-items: center;
cursor: pointer
}

.btn-icon span {
display: none
}

.btn-icon svg {
fill: #fff;
width: 12px;
height: 12px
}

.btn-icon-black {
border: 1px solid #030303;
background-color: #030303;
color: #fff
}

.btn-icon-black span {
display: none
}

.btn-icon-black svg {
fill: #fff;
width: 12px;
height: 12px
}

.btn-icon-search {
border-radius: 50%;
width: 20px;
height: 20px;
display: -ms-flexbox;
display: flex;
-ms-flex-pack: center;
justify-content: center;
-ms-flex-align: center;
align-items: center;
cursor: pointer
}

.btn-icon-search span {
display: none
}

.o-container {
max-width: 1250px;
padding: 0 15px;
margin: 0 auto;
background-color: #fff
}

.content {
z-index: 1;
position: relative
}

.content .o-container {
padding: 0 0 15px
}

.o-section {
position: relative;
z-index: 1
}

.o-section .wrap {
display: -ms-grid;
display: grid;
-ms-grid-rows: auto;
grid-template-rows: auto;
grid-row-gap: 15px;
grid-column-gap: 15px;
grid-auto-flow: row
}

.o-section .wrap>[class*=c-] {
-ms-flex: 1 1 auto;
flex: 1 1 auto;
-ms-flex-direction: column;
flex-direction: column
}

.o-section .amp-carousel-button {
z-index: 3
}

.o-section .amp-carousel-slide {
white-space: normal;
-ms-flex-align: start;
align-items: flex-start;
display: -ms-flexbox;
display: flex;
-ms-flex-direction: column;
flex-direction: column;
box-sizing: border-box;
width: 100%
}

.o-section .amp-carousel-slide .c-article__txt {
display: inherit;
height: 110px
}

.o-section .amp-carousel-slide .c-article__title {
z-index: 1
}

.c-header,
.o-section .amp-carousel-slide .c-article__title a:after {
z-index: 2
}

.c-header {
margin-bottom: 25px;
background-color: #fff;
box-shadow: 0 0 20px 0 rgba(0, 0, 0, .22)
}

.c-header .o-container {
display: -ms-flexbox;
display: flex;
-ms-flex-direction: row;
flex-direction: row;
-ms-flex-pack: justify;
justify-content: space-between;
position: relative;
max-width: 1280px;
padding: 0;
background-color: transparent
}

.c-header__brand {
text-align: center;
display: block;
color: #030303;
width: 180px;
transform: scale(.7) translateX(-15px)
}

.c-header__brand svg {
width: 100%;
height: 100%
}

.c-header__burger {
z-index: 4;
display: -ms-flexbox;
display: flex;
-ms-flex-direction: revert;
flex-direction: revert;
-ms-flex-pack: center;
justify-content: center;
-ms-flex-align: center;
align-items: center
}

.c-header__burger__btn {
width: 60px;
height: 45px;
cursor: pointer;
outline: none;
transform: scale(.5)
}

.c-header__burger__btn span {
display: block;
position: absolute;
height: 4px;
width: 100%;
background: #000;
opacity: 1;
left: 0;
transform: rotate(0deg);
transition: .5s ease-in-out
}

.c-header__burger__btn span:first-child {
top: 0;
transform-origin: left center
}

.c-header__burger__btn span:nth-child(2) {
top: 21px;
transform-origin: left center
}

.c-header__burger__btn span:nth-child(3) {
top: 42px;
transform-origin: left center
}

.c-header__burger__btn.close span:first-child {
transform: rotate(45deg)
}

.c-header__burger__btn.close span:nth-child(2) {
width: 0;
opacity: 0;
transition: .1s
}

.c-header__burger__btn.close span:nth-child(3) {
transform: rotate(-45deg)
}

.c-header__sidebar {
transform: translateY(-100vh);
opacity: 0;
z-index: 4;
transition: opacity .2s ease;
position: absolute;
right: 0
}

.c-header__sidebar__nav {
padding: 10px;
list-style-type: none;
font-size: 2em
}

.c-header__sidebar__nav__item {
width: calc(100% - 30px);
border-bottom: 1px solid #ededed;
margin: 0 auto
}

.c-header__sidebar__nav__item a {
font-size: .75rem;
color: #030303;
padding: 10px 0;
text-transform: uppercase;
font-weight: 500
}

.c-header__sidebar__search {
margin-top: auto;
border-bottom: 1px solid #cfd8dc;
padding: 15px;
position: relative;
background: #cacaca
}

.c-header__sidebar__search__txt {
color: #030303;
font-size: 1.5625rem;
font-weight: 300;
text-align: left;
width: 100%;
padding: 0 115px 0 0;
border: none;
outline: none;
text-indent: 0
}

.c-header__sidebar__search__txt::-moz-placeholder {
font-family: Catamaran, sans-serif;
color: #030303;
font-size: 1.5625rem;
font-weight: 300;
font-stretch: normal;
font-style: normal;
line-height: 1;
letter-spacing: normal;
text-align: left;
opacity: 1
}

.c-header__sidebar__search__txt:-ms-input-placeholder {
font-family: Catamaran, sans-serif;
color: #030303;
font-size: 1.5625rem;
font-weight: 300;
font-stretch: normal;
font-style: normal;
line-height: 1;
letter-spacing: normal;
text-align: left;
opacity: 1
}

.c-header__sidebar__search__txt::placeholder {
font-family: Catamaran, sans-serif;
color: #030303;
font-size: 1.5625rem;
font-weight: 300;
font-stretch: normal;
font-style: normal;
line-height: 1;
letter-spacing: normal;
text-align: left;
opacity: 1
}

.c-header__sidebar__search__btn {
outline: none;
position: absolute;
top: 15px;
right: 25px;
border: 1px solid #030303;
padding: 5px 15px;
background: #fff;
color: #030303;
border-radius: 5px;
font-size: .75rem;
font-weight: 500;
text-transform: uppercase
}

.c-header__sidebar__search__btn:before {
content: '';
background: #fff url(<?php echo $path ?>/assets/img/amp/buscar.svg) no-repeat 50%;
display: inline-block;
text-align: center;
object-fit: contain;
width: 24px;
height: 24px;
vertical-align: middle;
margin-left: -5px;
margin-right: 7px
}

.c-header__sidebar.open {
transform: translateY(83px);
height: calc(100vh - 111px);
border-top: 1px solid #ededed;
transition: opacity .2s ease;
opacity: 1;
background-color: #fff;
width: 100vw;
-ms-flex-direction: column;
flex-direction: column;
padding: 15px 0 0
}

.c-header__options, .c-header__sidebar.open {
display: -ms-flexbox;
display: flex
}

.c-header__options {
-ms-flex-pack: center;
justify-content: center;
margin-top: auto
}

.c-header__options .btn+.btn, .c-header__options .btn-news {
margin-left: 20px
}

.c-header__options .btn-search {
display: none
}

.c-header__options .btn {
color: #fff
}

.c-header__options .btn-icon {
border-radius: 50%;
width: 24px;
height: 24px;
display: -ms-flexbox;
display: flex;
-ms-flex-pack: center;
justify-content: center;
-ms-flex-align: center;
align-items: center;
cursor: pointer
}

.c-header__options .btn-icon span {
display: none
}

.c-header__options .btn-icon svg {
fill: #fff;
width: 12px;
height: 12px
}

.c-header__options .btn-icon-black {
border: 1px solid #030303;
background-color: #030303;
color: #fff
}

.c-header__options .btn-icon-black span {
display: none
}

.c-header__options .btn-icon-black svg {
fill: #fff;
width: 12px;
height: 12px
}

.c-header__options .btn-icon-search {
border-radius: 50%;
width: 20px;
height: 20px;
display: -ms-flexbox;
display: flex;
-ms-flex-pack: center;
justify-content: center;
-ms-flex-align: center;
align-items: center;
cursor: pointer
}

.c-header__options .btn-icon-search span {
display: none
}

.c-header__options .btn-news {
border: 1px solid #030303;
padding: 1px 5px;
text-transform: uppercase;
transition: all .3s;
background-color: #030303;
color: #fff;
border-radius: 5px;
font-size: .75rem;
font-weight: 500
}

.c-header__options .btn-news:hover {
color: #030303;
background-color: #fff
}

.c-header__options .btn-twitter {
background-color: #5d9dc9
}

.c-header__options .btn-facebook {
background-color: #3369a4
}

.c-header__options .btn-whatsapp {
background-color: #43ec62
}

.c-header__options .btn-instagram {
background-color: #d93175
}

.c-header__options .btn-lkd {
background-color: #007ab9
}

.c-header__options .btn-facebook, .c-header__options .btn-instagram, .c-header__options .btn-lkd, .c-header__options .btn-twitter, .c-header__options .btn-whatsapp {
transition: all .3s
}

.c-header__options .btn-facebook:hover, .c-header__options .btn-instagram:hover, .c-header__options .btn-lkd:hover, .c-header__options .btn-twitter:hover, .c-header__options .btn-whatsapp:hover {
background-color: #030303;
color: #fff
}

.c-header:before {
width: 100%;
height: 15px;
content: '';
display: block;
background-color: #030303;
margin: 0 auto
}

.c-detail {
position: relative
}

.c-detail__aside {
height: 60px;
background-color: #fff;
top: 93px;
z-index: 3
}

.c-detail__title {
margin: 0 auto 20px;
font-weight: 300;
font-size: 2rem;
line-height: 1.3
}

.c-detail__subtitle, .c-detail__title {
width: 100%;
letter-spacing: normal;
text-align: center;
color: #030303;
font-stretch: normal;
font-style: normal;
max-width: 90%
}

.c-detail__subtitle {
margin: 0 auto 35px;
font-weight: 400;
font-size: 1.25rem;
line-height: 1.35
}

.c-detail__subtitle p{
margin: 0 auto 35px;
font-weight: 400;
font-size: 1.25rem;
line-height: 1.35
}

.c-detail__body {
width: calc(100% - 30px);
max-width: 620px;
margin: 0 auto
}

.c-detail__body>p {
font-family: Noto Serif TC, serif;
font-size: 1.125rem;
line-height: 1.44;
font-weight: 400;
font-stretch: normal;
font-style: normal;
letter-spacing: normal;
text-align: left;
color: #030303;
margin-bottom: 20px
}

.c-detail__body>p strong {
font-weight: 900
}

.c-detail__body>p a {
border-bottom: 1px solid #030303;
display: inline;
font-weight: 600;
color: #030303
}

.c-detail__body>blockquote {
font-family: Noto Serif TC, serif;
margin: 0 0 20px;
padding: 0
}

.c-detail__body>blockquote p {
font-family: Noto Serif TC, serif;
font-size: 1.75rem;
line-height: 1.33;
text-align: center;
font-weight: 600;
font-stretch: normal;
font-style: normal;
letter-spacing: normal;
color: #55c6c7
}

.c-detail__body>img {
margin: 0 auto 20px;
display: block
}

.c-detail__body>h2, .c-detail__body>h3, .c-detail__body>h4, .c-detail__body>h5, .c-detail__body>h6 {
font-size: 1.5rem;
margin-bottom: 20px;
font-weight: 900
}

.c-detail__body>a {
border-bottom: 1px solid #030303;
display: inline;
font-weight: 600;
color: #030303
}

.c-detail__body>dl {
margin: 0
}

.c-detail__body>dl dd {
margin: 0 0 20px
}

.c-detail__body>ol, .c-detail__body>ul {
padding: 0;
list-style: inside
}

.c-detail__body>.embed {
box-sizing: border-box;
position: relative;
z-index: 1
}

.c-detail__body>.embed .video {
text-align: center;
position: relative;
padding-bottom: 56.25%;
height: 0;
overflow: hidden;
max-width: 100%
}

.c-detail__body>.embed .video embed, .c-detail__body>.embed .video iframe, .c-detail__body>.embed .video object {
position: absolute;
top: 0;
left: 0;
width: 100%;
height: 100%;
border: none
}

.c-detail__body>.embed .ivoox iframe {
width: 100%
}

.c-detail__body>.widget {
box-sizing: border-box;
position: relative;
z-index: 1;
margin-bottom: 20px;
overflow: hidden
}

.c-detail__body>.widget .code {
width: 100%;
margin: 0 auto
}

.c-detail__body>.widget .code .EmbeddedTweet, .c-detail__body>.widget .code>* {
width: 100%
}

.c-detail__body>.widget .embed {
text-align: center;
position: relative;
padding-bottom: 56.25%;
height: 0;
overflow: hidden;
max-width: 100%
}

.c-detail__body>.widget .embed embed, .c-detail__body>.widget .embed iframe, .c-detail__body>.widget .embed object {
position: absolute;
top: 0;
left: 0;
width: 100%;
height: 100%;
border: none
}

.c-detail__gallery {
counter-reset: a
}

.c-detail__gallery__item {
counter-increment: a
}

.c-detail__gallery__item .c-detail__body>* {
text-align: center
}

.c-detail__gallery__item__thumb {
line-height: 0;
max-width: 618px;
margin: 0 auto
}

.c-detail__gallery__item__thumb figcaption {
font-family: Catamaran, sans-serif;
font-size: .875rem;
font-weight: 500;
font-stretch: normal;
font-style: normal;
line-height: 1.2;
letter-spacing: normal;
text-transform: uppercase;
padding: 5px 10px
}

.c-detail__gallery__item__thumb figcaption:before {
content: counter(a) ". ";
margin-right: 3px
}

.c-detail__media {
width: 100%;
margin: 0 auto 10px
}

.c-detail__media__thumb {
line-height: 0;
display: block
}

.c-detail__media__widget {
margin-bottom: 20px
}

.c-detail__media__widget .iframe {
width: 100%;
height: 200px;
border: 1px solid #eee;
box-sizing: border-box;
margin: 0 auto 50px;
display: block
}

.c-detail__media__widget p {
padding: 15px 0;
font-size: 1rem;
text-align: center;
color: #030303;
font-weight: 400;
font-stretch: normal;
font-style: normal
}

.c-detail__media__widget p a {
color: #030303;
text-decoration: underline
}

.c-detail__media__video {
position: relative;
z-index: 1;
line-height: 0
}

.c-detail__media__video__txt {
background-color: rgba(3, 3, 3, .25);
position: absolute;
bottom: 0;
left: 0;
color: #fff;
padding: 10px;
box-sizing: border-box
}

.c-detail__media__video__title, .c-detail__media__video__txt {
width: 100%
}

.c-detail__media__video__title {
font-size: .875rem;
font-weight: 600;
font-stretch: normal;
font-style: normal;
line-height: 1.14;
letter-spacing: normal;
text-align: center;
color: #fcf5f5;
display: -ms-flexbox;
display: flex;
-ms-flex-direction: row;
flex-direction: row;
-ms-flex-pack: start;
justify-content: flex-start;
-ms-flex-align: center;
align-items: center;
text-decoration: none
}

.c-detail__media__video__title:before {
content: '';
width: 30px;
height: 30px;
border: 2px solid #fcf9f9;
border-radius: 50%;
margin-right: 10px;
display: block;
background: url(<?php echo $path ?>/assets/img/amp/triangle.svg) no-repeat 50%
}

.c-detail__media__gallery {
position: relative;
z-index: 1;
line-height: 0
}

.c-detail__media__gallery:before {
content: '';
background: rgba(0, 0, 0, .7) url(<?php echo $path ?>/assets/img/amp/gallery.svg) no-repeat 0 0;
width: 40px;
height: 40px;
position: absolute;
top: 15px;
left: 15px;
display: block;
z-index: 3
}

.c-detail__media__txt {
padding: 10px 5%
}

.c-detail__media__title {
font-size: .875rem;
font-weight: 500;
font-stretch: normal;
font-style: normal;
line-height: 1;
letter-spacing: normal;
text-align: left;
color: #030303
}

.c-detail__top {
max-width: inherit;
padding-left: 15px;
padding-right: 15px;
background-image: linear-gradient(270deg, #fff, #fefefe 10%, #fefefe 20%, #fdfefe 30%, #f9fdfc 40%, #f1fafa 50%, #e4f6f5 60%, #cff0ee 70%, #b1e6e4 80%, #87dad6 90%, #55ccc7);
padding-bottom: 15px
}

.c-detail__top, .c-detail__top .c-detail__media__thumb {
margin-left: -15px;
margin-right: -15px
}

.c-detail__top .c-detail__media__thumb {
margin-bottom: 15px
}

.c-detail__top .c-detail__media__widget {
padding-top: 50px
}

.c-detail__top .c-detail__media__video {
max-width: 790px;
margin: 0 auto 15px
}

.c-detail__top .c-detail__media__video .video-player {
position: relative;
display: block;
background-color: #000;
overflow: hidden
}

.c-detail__top .c-detail__media__video .featured .video-player {
min-height: 150px
}

.c-detail__top .c-detail__media__video .boton-video {
position: absolute;
top: 50%;
left: 50%;
transform: translate(-50%, -50%);
width: 10%;
z-index: 1;
pointer-events: none;
border-radius: 50%;
background-color: rgba(0, 0, 0, .7);
border: 3px solid #fff
}

.c-detail__top .c-detail__media__video .boton-video svg {
width: 100%;
fill: #fff
}

.c-detail__top>:not(.c-detail__media__widget) {
border-bottom: none
}

.c-detail__top.grey-degree {
background-image: linear-gradient(90deg, #dcdcdd, #fefefe 90%, #fff)
}

.c-detail__signature {
margin-top: auto;
margin-bottom: 15px;
padding-left: 15px
}

.c-detail__signature__author {
font-weight: 600;
margin-bottom: 3px
}

.c-detail__signature__author, .c-detail__signature__date {
font-family: Catamaran, sans-serif;
font-size: .875rem;
font-stretch: normal;
font-style: normal;
line-height: 1;
letter-spacing: normal;
text-align: left;
color: #030303
}

.c-detail__signature__date {
font-weight: 300
}

.c-detail__share .btn {
color: #cacaca;
border-radius: 50%;
border: 4px solid #cacaca;
width: 20px;
height: 20px;
background-image: none;
background: #fff;
display: -ms-flexbox;
display: flex;
-ms-flex-direction: row;
flex-direction: row;
-ms-flex-pack: center;
justify-content: center;
-ms-flex-align: center;
align-items: center;
outline: none;
margin-left: 5px
}

.c-detail__share .btn svg {
width: 12px;
height: 12px
}

.c-detail__share .btn .sr-only {
display: none
}

.c-detail__share {
    padding-right: 15px;
}

.c-detail__tags {
border-top: 1px solid #030303;
border-bottom: 1px solid #030303;
padding-left: 15px;
padding-right: 15px
}

.c-detail__tags a, .c-detail__tags span {
font-family: Catamaran, sans-serif;
font-size: .875rem;
line-height: 2.24;
font-weight: 500;
font-stretch: normal;
font-style: normal;
letter-spacing: normal;
text-align: left
}

.c-detail__tags a:not([class*=u-color-]), .c-detail__tags span:not([class*=u-color-]) {
color: #030303
}

.c-detail__tags a {
margin-left: 20px
}

.c-detail__tags a+a:before {
content: '|';
font-weight: 100;
padding-right: 10px;
display: inline-block
}

.c-detail__interest {
padding-left: 15px;
padding-right: 15px
}

.c-detail__interest__title {
font-size: 1.5rem;
font-weight: 300;
font-stretch: normal;
font-style: normal;
line-height: .85;
letter-spacing: normal;
text-align: left;
color: #030303
}

.c-detail__interest__list-item {
font-size: 1.125rem;
font-weight: 300;
font-stretch: normal;
font-style: normal;
line-height: 1.4;
letter-spacing: normal;
text-align: left;
color: #030303;
position: relative
}

.c-detail__interest__list-item a {
color: #030303;
padding-left: 30px
}

.c-detail__interest__list-item:before {
content: '';
background: url(<?php echo $path ?>/assets/img/amp/arrow-2.svg) no-repeat 50%;
transform: rotate(-90deg);
display: block;
width: 17px;
height: 24px;
position: absolute;
top: 0;
left: 0
}

.c-title {
font-family: Catamaran, sans-serif;
padding: 15px 15px 18px;
border-top: 1px solid #030303
}

.c-title, .c-title-small {
font-size: 1.25rem;
text-transform: uppercase;
line-height: 1;
font-weight: 600
}

.c-title-small {
padding: 5px 15px;
margin-bottom: 20px;
border-bottom: 1px solid #030303
}

.c-title-small:not([class*=u-color-]) {
color: #030303
}

.c-list__item {
padding: 0;
margin: 0 0 40px;
position: relative
}

.c-list__item__thumb {
line-height: 0
}

.c-list__item__thumb img {
object-fit: cover;
width: 100%;
height: 100%
}

.c-list__item__txt {
padding: 15px
}

.c-list__item__title {
font-size: 1.125rem;
display: block;
font-weight: 500;
line-height: 1.25;
margin-bottom: 5px;
font-family: Noto Serif TC, serif
}

.c-list__item__title,
.c-list__item__title a {
color: #030303
}

.c-list__item__subtitle {
font-size: 1.125rem;
font-weight: 200;
line-height: 1.25;
font-family: Noto Serif TC, serif;
color: #030303
}

.c-list__item__play a {
font-size: .9375rem;
font-weight: 800;
font-stretch: normal;
font-style: normal;
line-height: 3.33;
letter-spacing: normal;
text-align: center;
color: #fcf9f9;
background-color: #030303;
text-transform: uppercase;
display: -ms-flexbox;
display: flex;
-ms-flex-direction: row;
flex-direction: row;
-ms-flex-pack: center;
justify-content: center;
-ms-flex-align: center;
align-items: center;
transition: background-color .3s
}

.c-list__item__play a:before {
content: '';
width: 38px;
height: 38px;
border: 2px solid #fcf9f9;
border-radius: 50%;
margin-right: 15px;
background: url(<?php echo $path ?>/assets/img/amp/triangle.svg) no-repeat 50%
}

.c-list__item__play a:hover {
background-color: #030303;
color: #aeaeae;
}

.c-list__item__social a {
font-size: 1.1875rem;
font-weight: 500;
font-stretch: normal;
font-style: normal;
line-height: 1;
letter-spacing: normal;
text-align: left;
border-bottom: 1px solid #030303;
width: -webkit-max-content;
width: -moz-max-content;
width: max-content
}

.c-list__item__link,
.c-list__item__social a {
display: inline-block;
color: #030303
}

.c-list__item__link {
border-bottom: 1px solid rgba(0, 0, 0, .7)
}

.c-list__item__link:after {
content: '';
position: absolute;
left: 0;
top: 0;
right: 0;
bottom: 0
}

.c-list__item:last-child {
border-bottom: none
}

.c-list__link {
border-bottom: 1px solid rgba(0, 0, 0, .7);
display: inline-block;
color: #030303;
font-size: .875rem;
font-weight: 400;
font-stretch: normal;
font-style: normal;
line-height: 1;
letter-spacing: normal;
text-align: left;
margin-left: 15px
}
.c-lomas {
width: 100%;
margin: 0 auto 20px
}

.c-lomas__title {
font-family: Catamaran, sans-serif;
font-size: 1.25rem;
text-transform: uppercase;
padding: 15px 15px 18px;
font-weight: 600;
font-stretch: normal;
font-style: normal;
line-height: 1.3;
letter-spacing: normal;
color: #030303;
border-top: 1px solid #030303;
text-align: left
}

.c-lomas__thumb {
display: block;
margin: 0 auto;
line-height: 0
}

.c-lomas__thumb, .c-lomas__thumb img {
max-width: 215px;
width: 100%
}

.c-lomas__list {
counter-reset: a;
margin: 20px 15px 25px;
background-image: linear-gradient(180deg, #fff, #fefefe 10%, #fefefe 20%, #fdfefe 30%, #f9fdfc 40%, #f1fafa 50%, #e4f6f5 60%, #cff0ee 70%, #b1e6e4 80%, #87dad6 90%, rgba(85, 204, 199, .73))
}

.c-lomas__list-item {
counter-increment: a;
font-family: Noto Serif TC, serif;
font-size: 1.375rem;
font-weight: 400;
font-stretch: normal;
font-style: normal;
line-height: 1.45;
letter-spacing: normal;
text-align: center;
padding: 0 5px 10px
}

.c-lomas__list-item, .c-lomas__list-item a {
color: #030303
}

.c-lomas__list-item:before {
content: counter(a);
margin: 5px auto 0;
display: block;
border-bottom: 1px solid #030303;
width: -webkit-min-content;
width: -moz-min-content;
width: min-content
}

.c-lomas__list-item:hover:before {
font-weight: 600
}

.c-article {
display: -ms-flexbox;
display: flex;
-ms-flex-direction: column;
flex-direction: column;
position: relative;
margin-bottom: 25px;
overflow: hidden
}

.c-article__title {
margin-bottom: 10px;
font-size: 1.125rem;
line-height: 1.57;
width: 100%;
text-align: center;
font-weight: 100
}

.c-article__title a {
color: #030303
}

.c-article__title a:after {
content: '';
position: absolute;
left: 0;
top: 0;
right: 0;
bottom: 0
}

.c-article__tag {
font-size: 1rem;
text-transform: uppercase;
font-family: Catamaran, sans-serif;
line-height: 1.64;
color: #030303;
font-weight: 600
}

.c-article__thumb {
line-height: 0;
z-index: 1;
opacity: 1;
transition: opacity .3s;
overflow: hidden
}

.c-article__thumb img {
width: 100%;
transition: transform .6s
}

.c-article__thumb.gallery {
position: relative
}

.c-article__thumb.gallery:before {
content: '';
background: rgba(0, 0, 0, .7) url(<?php echo $path ?>/assets/img/amp/gallery.svg) no-repeat 0 0;
width: 40px;
height: 40px;
position: absolute;
top: 15px;
left: 15px;
display: block;
z-index: 3
}

.c-article__thumb.video {
position: relative
}

.c-article__thumb.video:before {
content: '';
background: rgba(0, 0, 0, .7) url(<?php echo $path ?>/assets/img/amp/triangle.svg) no-repeat 50%;
width: 40px;
height: 40px;
position: absolute;
top: 15px;
left: 15px;
display: block;
z-index: 3;
border-radius: 50%;
border: 2px solid #fff
}

.c-article__txt {
padding: 15px;
display: -ms-flexbox;
display: flex;
background-color: #fff;
-ms-flex-direction: column;
flex-direction: column;
-ms-flex-align: center;
align-items: center;
margin-bottom: auto;
z-index: 2;
text-align: center
}

.c-article:hover .c-article__title a {
border-bottom-color: #030303
}

.c-article:hover .c-article__tag {
color: #a2a2a2
}

.c-article:hover .c-article__thumb {
opacity: .9
}

.c-article:hover .c-article__thumb img {
transform: scale(1.1)
}

.c-article:after {
content: '';
height: 0;
padding-top: 3px;
width: 147px;
display: block;
margin: 0 auto;
background-color: #030303
}

.c-article.degree-max {
padding-bottom: 20px;
background-image: linear-gradient(90deg, #dcdcdd, #fefefe 90%, #fff)
}

.c-article.degree-max .c-article__thumb {
max-width: 875px;
margin: 0 auto
}

.c-article.degree-max .c-article__txt {
background-color: transparent
}

.c-interest {
border-top: 1px solid #030303;
padding: 20px 0;
margin-bottom: 5px
}

.c-interest__title {
text-align: left;
color: #030303;
font-weight: 500;
-ms-flex-pack: start;
justify-content: flex-start;
padding-left: 15px;
font-size: 1rem
}

.c-interest__list-item {
color: rgba(3, 3, 3, .7);
font-family: Catamaran, sans-serif;
font-size: .875rem;
font-weight: 500;
padding: 0 5px
}

.c-interest__list-item+.c-interest__list-item:before {
content: '|';
padding-right: 10px;
display: inline-block
}

.c-footer .o-container {
padding: 20px 0;
border-top: 27px solid #030303;
background-image: linear-gradient(90deg, #fff 1%, #fefefe 11%, #fefefe 20%, #fdfefe 30%, #f9fdfc 40%, #f1fafa 50%, #e4f6f5 60%, #cff0ee 70%, #b1e6e4 80%, #87dad6 89%, #55ccc7 99%)
}

.c-footer .u-grid {
display: -ms-grid;
display: grid;
margin: 0 20px
}

.c-footer__brand {
color: #030303;
width: 100%;
max-width: 140px
}

.c-footer__share .btn-icon {
display: -ms-inline-flexbox;
display: inline-flex;
background-color: #030303;
color: #fff;
border-radius: 50%;
width: 24px;
height: 24px
}

.c-footer__share .btn-icon svg {
width: 12px;
height: 12px
}

.c-footer__share .btn-icon+.btn-icon {
margin-left: 10px
}

.c-footer__newsletter {
text-align: right;
font-family: Catamaran, sans-serif;
font-size: 1.375rem;
font-weight: 700;
font-stretch: normal;
font-style: normal;
line-height: 1.14;
letter-spacing: normal
}

.c-footer__newsletter, .c-footer__newsletter a {
color: #050505
}

.c-footer__newsletter a:nth-child(2) {
text-transform: uppercase
}

.c-footer__txt {
font-family: Catamaran, sans-serif;
font-size: .9375rem;
font-weight: 300;
font-stretch: normal;
font-style: normal;
line-height: 1.33;
letter-spacing: normal;
text-align: left;
color: #050505
}

.c-footer__bottom {
padding-top: 20px;
padding-left: 20px;
padding-right: 20px
}

.c-footer__bottom a {
text-transform: uppercase;
color: #030303
}

.c-footer__bottom a+a {
margin-left: 30px
}

@media (min-width:768px) {
.u-grid {
--gap: 15px;
display: -ms-grid;
display: grid;
grid-column-gap: var(--gap)
}

.u-grid-columns-2 {
-ms-grid-columns: 1fr 15px 1fr;
grid-template-columns: repeat(2, 1fr)
}

.u-grid-columns-3 {
--ncols: 3;
-ms-grid-columns: 1fr 15px 1fr 15px 1fr;
grid-template-columns: repeat(3, 1fr)
}

.u-grid-columns-3-add {
--ncols: 3;
-ms-grid-columns: 1fr 15px 1fr 15px 300px;
grid-template-columns: repeat(2, 1fr) 300px
}

.u-grid-columns-4 {
--ncols: 4;
-ms-grid-columns: 1fr 15px 1fr 15px 1fr 15px 1fr;
grid-template-columns: repeat(4, 1fr)
}

.u-grid-start-1 {
-ms-grid-column: 1;
grid-column-start: 1
}

.u-grid-start-2 {
-ms-grid-column: 2;
grid-column-start: 2
}

.u-grid-start-3 {
-ms-grid-column: 3;
grid-column-start: 3
}

.u-grid-start-4 {
-ms-grid-column: 4;
grid-column-start: 4
}

.u-grid-col-start-1 {
-ms-grid-column: 1;
grid-column-start: 1
}

.u-grid-col-start-2 {
-ms-grid-column: 2;
grid-column-start: 2
}

.u-grid-col-start-3 {
-ms-grid-column: 3;
grid-column-start: 3
}

.u-grid-col-start-4 {
-ms-grid-column: 4;
grid-column-start: 4
}

.u-grid-col-span-1 {
-ms-grid-column-span: 1;
-ms-grid-column: 1;
grid-column: 1/span 1
}

.u-grid-col-span-2 {
-ms-grid-column-span: 2;
-ms-grid-column: 1;
grid-column: 1/span 2
}

.u-grid-col-span-3 {
-ms-grid-column-span: 3;
-ms-grid-column: 1;
grid-column: 1/span 3
}

.u-grid-col-span-4 {
-ms-grid-column-span: 4;
-ms-grid-column: 1;
grid-column: 1/span 4
}

.u-grid-row-start-1 {
-ms-grid-row: 1;
grid-row-start: 1
}

.u-grid-row-start-2 {
-ms-grid-row: 2;
grid-row-start: 2
}

.u-grid-row-start-3 {
-ms-grid-row: 3;
grid-row-start: 3
}

.u-grid-row-start-4 {
-ms-grid-row: 4;
grid-row-start: 4
}

.u-grid-row-span-1 {
-ms-grid-row-span: 1;
-ms-grid-row: 1;
grid-row: 1/span 1
}

.u-grid-row-span-2 {
-ms-grid-row-span: 2;
-ms-grid-row: 1;
grid-row: 1/span 2
}

.u-grid-row-span-3 {
-ms-grid-row-span: 3;
-ms-grid-row: 1;
grid-row: 1/span 3
}

.u-grid-row-span-4 {
-ms-grid-row-span: 4;
-ms-grid-row: 1;
grid-row: 1/span 4
}

.u-mb40 {
margin-bottom: 40px
}

.u-mb42 {
margin-bottom: 42px
}

.content .o-container {
padding: 0 15px 15px
}

.o-section .amp-carousel-slide {
                    max-width: 410px;
                }
                
.c-header__sidebar.open {
right: 15px;
max-width: 640px
}

.c-detail__aside {
top: 75px;
background-color: transparent;
z-index: 1
}

.c-detail__title {
font-size: 2.6875rem;
line-height: 1.16
}

.c-detail__subtitle, .c-detail__title {
max-width: 620px
}

.c-detail__subtitle {
margin: 0 auto 45px
}

.c-detail__body {
padding: 0 0 20px;
width: 100%
}

.c-detail__body>p {
font-size: 1.125rem;
line-height: 1.67
}

.c-detail__body>blockquote p {
font-size: 2.0625rem;
line-height: 1.21
}

.c-detail__body>blockquote {
max-width: 80%;
margin: 0 auto 20px
}

.c-detail__body>.widget .code, .c-detail__body>.widget .code>* {
max-width: 500px
}

.c-detail__gallery__item__thumb figcaption {
padding: 14px 0 0
}

.c-detail__media {
margin: 0 auto 35px;
max-width: 620px
}

.c-detail__media__widget .iframe {
max-width: 655px
}

.c-detail__media__gallery:before {
top: 5px;
left: 5px;
width: 48px;
height: 48px
}

.c-detail__media__txt {
padding: 10px 0 0
}

.c-detail__top {
margin-left: 0;
margin-right: 0
}

.c-detail__top .c-detail__signature {
margin-bottom: 10px
}

.c-detail__top .c-detail__tags {
background-color: transparent
}

.c-detail__signature {
padding-left: 0;
margin: 40px 0
}

.c-detail__share .btn {
width: 24px;
height: 24px
}

.c-detail__share .btn svg {
width: 16px;
height: 16px
}

.c-detail__tags a, .c-detail__tags span {
font-size: 1.5625rem;
line-height: 2.24
}

.c-detail__tags a+a:before {
padding-right: 20px
}

.c-detail__tags {
padding-left: 0;
padding-right: 0;
background: #fff;
z-index: 2;
position: relative
}

.c-detail__tags+.c-detail__interest {
margin-top: -40px;
padding-top: 40px
}

.c-detail__interest {
padding-left: 0;
padding-right: 0;
background: #fff;
z-index: 2;
position: relative
}

.c-detail__interest__title {
font-size: 2.25rem
}

.c-detail__interest__list-item {
font-size: 1.5rem;
line-height: 1.85
}

.c-detail__interest__list-item:before {
top: 10px
}

.c-title {
font-size: 2.1875rem;
padding: 25px 0 20px
}

.c-title-small {
padding: 5px 0;
margin-bottom: 40px
}

.c-list__item__thumb {
-ms-grid-column-span: 2;
-ms-grid-column: 2;
grid-column: 2/span 2;
-ms-grid-row-span: 1;
-ms-grid-row: 1;
grid-row: 1/span 1
}

.c-list__item__txt {
padding: 0;
-ms-grid-column-span: 8;
-ms-grid-column: 4;
grid-column: 4/span 8
}

.c-list__item__play {
-ms-grid-column-span: 2;
-ms-grid-column: 2;
grid-column: 2/span 2;
-ms-grid-row-span: 1;
-ms-grid-row: 2;
grid-row: 2/span 1
}

.c-list__item__social {
padding: 0;
-ms-grid-column-span: 8;
-ms-grid-column: 4;
grid-column: 4/span 8
}

.c-list__item {
display: -ms-grid;
display: grid;
grid-row-gap: 15px;
grid-column-gap: 30px;
grid-auto-flow: row;
-ms-grid-columns: (1fr)[12];
grid-template-columns: repeat(12, 1fr);
border-bottom: 1px solid rgba(0, 0, 0, .37);
padding: 30px 0;
margin: 0
}

.c-list__link {
font-size: 1.375rem;
margin-left: 0
}

.u-grid-columns-3-add .c-list__item__thumb {
-ms-grid-column-span: 3;
-ms-grid-column: 1;
grid-column: 1/span 3;
-ms-grid-row-span: 1;
-ms-grid-row: 1;
grid-row: 1/span 1
}

.u-grid-columns-3-add .c-list__item__txt {
-ms-grid-column-span: 9;
-ms-grid-column: 4;
grid-column: 4/span 9
}

.c-lomas__title {
text-align: center;
font-size: 2.5rem;
padding: 25px 0;
border-top: none
}

.c-lomas__list {
margin-left: 0;
margin-right: 0
}

.c-article__title {
font-size: 1.375rem;
line-height: 1.45
}

.c-article__tag {
font-size: 1.25rem
}

.c-article__thumb.gallery:before, .c-article__thumb.video:before {
top: 5px;
left: 5px;
width: 48px;
height: 48px
}

.c-article__thumb.video:before {
background-size: 40%
}

.c-article.degree-max {
padding-bottom: 40px
}

.c-article__txt {
padding: 10px 0
}

.c-article:after {
width: 250px
}

.c-interest {
padding: 40px 0;
margin-bottom: 45px
}

.c-interest__title {
font-size: 1.375rem;
text-align: center;
-ms-flex-pack: center;
justify-content: center;
padding-left: 0
}

.c-interest__list-item {
padding: 0 10px;
font-size: 1.125rem
}

.c-interest__list-item+.c-interest__list-item:before {
padding-right: 20px
}

.c-footer .o-container {
padding-top: 40px
}
}

@media (min-width:992px) {
.u-hidden-md {
display: none
}

.o-section .wrap.cols2 {
-ms-grid-columns: 1fr 15px 1fr;
grid-template-columns: repeat(2, 1fr)
}

.o-section .wrap.cols3 {
-ms-grid-columns: 1fr 15px 1fr 15px 1fr 15px;
grid-template-columns: repeat(3, 1fr)
}

.c-list__item__subtitle,
.c-list__item__title {
font-size: 1.6875rem
}
.c-article {
margin-bottom: 42px
}
}

@media(max-width:767px) {
.u-grid {
--gap: 15px;
display: -ms-grid;
display: grid;
grid-column-gap: var(--gap)
}

.u-grid-columns-2-xs {
-ms-grid-columns: 1fr 15px 1fr;
grid-template-columns: repeat(2, 1fr)
}

.u-grid-col-xs-start-1 {
-ms-grid-column: 1;
grid-column-start: 1
}

.u-grid-col-xs-start-2 {
-ms-grid-column: 2;
grid-column-start: 2
}

.u-grid-col-xs-start-3 {
-ms-grid-column: 3;
grid-column-start: 3
}

.u-grid-col-xs-start-4 {
-ms-grid-column: 4;
grid-column-start: 4
}

.u-grid-col-xs-span-1 {
-ms-grid-column-span: 1;
-ms-grid-column: 1;
grid-column: 1/span 1
}

.u-grid-col-xs-span-2 {
-ms-grid-column-span: 2;
-ms-grid-column: 1;
grid-column: 1/span 2
}

.u-grid-col-xs-span-3 {
-ms-grid-column-span: 3;
-ms-grid-column: 1;
grid-column: 1/span 3
}

.u-grid-col-xs-span-4 {
-ms-grid-column-span: 4;
-ms-grid-column: 1;
grid-column: 1/span 4
}

.u-grid-row-xs-start-1 {
-ms-grid-row: 1;
grid-row-start: 1
}

.u-grid-row-xs-start-2 {
-ms-grid-row: 2;
grid-row-start: 2
}

.u-grid-row-xs-start-3 {
-ms-grid-row: 3;
grid-row-start: 3
}

.u-grid-row-xs-start-4 {
-ms-grid-row: 4;
grid-row-start: 4
}

.u-grid-row-xs-span-1 {
-ms-grid-row-span: 1;
-ms-grid-row: 1;
grid-row: 1/span 1
}

.u-grid-row-xs-span-2 {
-ms-grid-row-span: 2;
-ms-grid-row: 1;
grid-row: 1/span 2
}

.u-grid-row-xs-span-3 {
-ms-grid-row-span: 3;
-ms-grid-row: 1;
grid-row: 1/span 3
}

.u-grid-row-xs-span-4 {
-ms-grid-row-span: 4;
-ms-grid-row: 1;
grid-row: 1/span 4
}

.u-hidden-xs {
display: none
}
}

@media (max-width:991px) {
.u-hidden-sm {
display: none
}

.c-list__item-podcast {
border-bottom: 1px solid #030303;
padding: 15px;
display: -ms-grid;
display: grid;
grid-row-gap: 15px;
grid-column-gap: 15px;
grid-auto-flow: row;
-ms-grid-columns: 70px 1fr;
grid-template-columns: 70px 1fr;
margin: 0
}

.c-list__item-podcast .c-list__item__txt {
padding: 0
}

.c-list__item-podcast .c-list__item__subtitle,
.c-list__item-podcast .c-list__item__title {
-ms-grid-column: 2;
grid-column-start: 2
}

.c-list__item-podcast .c-list__item__social {
-ms-grid-column: 2;
grid-column-start: 2;
font-size: .75rem
}

.c-list__item-podcast .c-list__item__social a {
font-size: .75rem
}

.c-list__item-podcast .c-list__item__thumb {
margin: 0 auto
}

.c-list__item-podcast .c-list__item__thumb img {
height: inherit
}

.c-list__item-podcast .c-list__item__play {
max-width: 200px;
margin: 0;
-ms-grid-column: 2;
grid-column-start: 2
}

.c-list__item-podcast .c-list__item__play a {
font-size: .75rem;
padding: 5px 0;
max-width: 150px
}.c-interest__list {
-ms-flex-wrap: nowrap;
flex-wrap: nowrap;
overflow: scroll;
white-space: nowrap;
-ms-flex-pack: start;
justify-content: flex-start;
padding-left: 10px
}

.c-footer .u-grid {
grid-column-gap: 10px;
grid-row-gap: 10px;
margin: 0 15px;
-ms-grid-columns: 90px 15px 1fr;
grid-template-columns: 90px 1fr
}

.c-footer__newsletter {
font-size: .9375rem
}

.c-footer__share, .c-footer__where {
margin-left: -15px
}

.c-footer__bottom {
display: -ms-flexbox;
display: flex;
-ms-flex-direction: column-reverse;
flex-direction: column-reverse;
padding-top: 15px;
padding-left: 15px;
padding-right: 15px
}

.c-footer__bottom a {
font-size: .625rem
}

.c-footer__bottom a+a {
margin-left: 15px
}
}

.policy-add{
    width: 46%;
    float: left;
    font-size: 12px;
}

.link-legal-platform{
    display:inline;
    text-transform: lowercase;
    font-size: 12px;
}

@media (max-width: 699px){
    .policy-add{
        width: 100%;
        margin-top: 10px;
    }

    .link-legal-platform{
        display:inline;
        text-transform: lowercase;
        font-size: 12px;
    }
}