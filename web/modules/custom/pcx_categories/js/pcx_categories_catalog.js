(function ($) {
  Drupal.behaviors.pcx_categories_catalog = {
    attach: function (context, settings) {
      $('#block-paycheck-exchange-catalogmenu li.active > ul.child-menu a').hide();

      $('.views-field-view-taxonomy-term').each(function() {
        var href = $(this).find('a').attr('href');
        $('#block-paycheck-exchange-catalogmenu li.active > ul.child-menu a[href="'+href+'"]').show();
      });

      // can access setting from 'drupalSettings';
  /*     var pcx_user_uid = drupalSettings.pcx_categories.pcx_user_uid;
       $("#block-mainmenuauthenticated li a").click(function(){
            CookiesValue = 'paycheckexchange_kofe_' + pcx_user_uid; 
            createCookie("paycheckexchange_kofe", CookiesValue, 30);
        });
       var CookiesisExist = getCookie("paycheckexchange_kofe");
       console.log(CookiesisExist);
*/  /*      $(".logout").click(function(){
           if (CookiesisExist) {
               deleteCookie("paycheckexchange_kofe");
                window.location='https://paycheckexchange.kofetime.com/wp-login.php?action=logout';  return false;
          }else{
                window.location='http://paycheckexchange.com/user/logout';  return false;
          }
        });*/

    }
  };
}(jQuery));


/*
function createCookie(name, value, expires, path, domain) {
  var cookie = name + "=" + escape(value) + ";";

  if (expires) {
    // If it's a date
    if(expires instanceof Date) {
      // If it isn't a valid date
      if (isNaN(expires.getTime()))
       expires = new Date();
    }
    else
      expires = new Date(new Date().getTime() + parseInt(expires) * 1000 * 60 * 60 * 24);

    cookie += "expires=" + expires.toGMTString() + ";";
  }

  if (path)
    cookie += "path=" + path + ";";
  if (domain)
    cookie += "domain=" + domain + ";";

  document.cookie = cookie;
}


function getCookie(name) {
  var regexp = new RegExp("(?:^" + name + "|;\s*"+ name + ")=(.*?)(?:;|$)", "g");
  var result = regexp.exec(document.cookie);
  return (result === null) ? null : result[1];
}

function deleteCookie(name, path, domain) {
  // If the cookie exists
  if (getCookie(name))
    createCookie(name, "", -1, path, domain);
 } */
