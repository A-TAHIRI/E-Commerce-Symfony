function getUrlParam(r){var s=new RegExp("[?&]"+r+"=([^&#]*)").exec(window.location.href);return null==s?null:s[1]||0}"styles-red.css"==getUrlParam("style")&&($('link[href="css/styles.css"]').attr("href","css/styles-red.css"),$(".brand h1 a img").attr("src","img/logo-red.gif"),$(".navbar-brand img").attr("src","img/logo-red.gif")),"styles-green.css"==getUrlParam("style")&&($('link[href="css/styles.css"]').attr("href","css/styles-green.css"),$(".brand h1 a img").attr("src","img/logo-green.gif"),$(".navbar-brand img").attr("src","img/logo-green.gif"));var querystring="style="+getUrlParam("style");null!=getUrlParam("style")&&$("a.ext").each(function(){var r=$(this).attr("href");r&&(r+=(r.match(/\?/)?"&":"?")+querystring,$(this).attr("href",r))});