window.onload = function () {
    baguetteBox.run('.baguetteBoxOne', {
        captions: function (element) {
            return element.getElementsByTagName('img')[0].alt;
        },
        animation: false,
        titleTag: false,
        overlayBackgroundColor: 'rgba(0,0,0,1)'
    });


    if (typeof oldIE === 'undefined' && Object.keys) {
        hljs.initHighlighting();
    };

    // var year = document.getElementById('year');
    // year.innerText = new Date().getFullYear();
};