(function($){
  $.fn.translate = function(options) {

    var that = this;
	
    var settings = {
      css: "trn",
      lang: "en"
    };
    settings = $.extend(settings, options || {});
    if (settings.css.lastIndexOf(".", 0) !== 0)
      settings.css = "." + settings.css;
       
    var t = settings.t;
 

    this.lang = function(l) {
      if (l) {
        settings.lang = l;
        this.translate(settings);
      }
        
      return settings.lang;
    };


    this.get = function(index) {
      var res = index;

      try {
        res = t[index][settings.lang];
      }
      catch (err) {
        return index;
      }
      
      if (res)
        return res;
      else
        return index;
    };

    this.g = this.get;


    this.find(settings.css).each(function(i) {
      var $this = $(this);

      var trn_key = $this.attr("data-trn-key");
      if (!trn_key) {
        trn_key = $this.html();
        $this.attr("data-trn-key", trn_key);
      }

      $this.html(that.get(trn_key));
    });
    
    
		return this;
		
		

  };
})(jQuery);