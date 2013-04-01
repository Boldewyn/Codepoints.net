define(['jquery'], function($) {
  var gettext = {}, default_ = "en",
      locale_path = '/static/locale',
      lang = document.documentElement.getAttribute('lang');

  if ('gettext' in window) {
    gettext = window.gettext;
  } else {
    gettext.catalog = {};
  }

  gettext.gettext = function(s) {
    var args = Array.prototype.slice.call(arguments, 1),
        item;
    /* do lookup in catalog */
    if (lang in gettext.catalog && s in gettext.catalog[lang]) {
      s = gettext.catalog[lang][s];
    }
    while (args.length) {
      item = args.shift();
      s = s.replace(/%s/, item);
    }
    return s;
  };

  return gettext;
});