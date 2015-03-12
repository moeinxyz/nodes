Dante.Editor.Tooltip.prototype.move = function(coords) {
    var control_spacing, control_width, coord_right, coord_top, pull_size, tooltip;
    tooltip = $(this.el);
    control_width   = tooltip.find(".control").css("width");
    control_spacing = tooltip.find(".inlineTooltip-menu").css("padding-right");
    pull_size = parseInt(control_width.replace(/px/, "")) + parseInt(control_spacing.replace(/px/, ""));
    coord_right = coords.right - pull_size;
    coord_top = coords.top;
    return $(this.el).offset({
      top: coord_top,
      right: coord_right
    });
};
Dante.Editor.Tooltip.prototype.getEmbedFromNode = function(node) {
  this.node = $(node);
  this.node_name = this.node.attr("name");
  this.node.addClass("spinner");
  return $.getJSON("" + this.current_editor.oembed_url + ($(this.node).text().split("").reverse().join(""))).success((function(_this) {
    return function(data) {
      var iframe_src, replaced_node, tmpl, url;
      _this.node = $("[name=" + _this.node_name + "]");
      iframe_src = $(data.html).prop("src");
      tmpl = $(_this.embedTemplate());
      tmpl.attr("name", _this.node.attr("name"));
      $(_this.node).replaceWith(tmpl);
      replaced_node = $(".graf--iframe[name=" + (_this.node.attr("name")) + "]");
      replaced_node.find("iframe").attr("src", iframe_src);
      url = data.url || data.author_url;
      replaced_node.find(".markup--anchor").attr("href", url).text(url);
      return _this.hide();
    };
  })(this));
};        
Dante.Editor.Tooltip.prototype.getExtractFromNode = function(node) {
  this.node = $(node);
  this.node_name = this.node.attr("name");
  this.node.addClass("spinner");
  return $.getJSON("" + this.current_editor.extract_url + ($(this.node).text().split("").reverse().join(""))).success((function(_this) {
    return function(data) {
      var iframe_src, image_node, replaced_node, tmpl;
      _this.node = $("[name=" + _this.node_name + "]");
      iframe_src = $(data.html).prop("src");
      tmpl = $(_this.extractTemplate());
      tmpl.attr("name", _this.node.attr("name"));
      $(_this.node).replaceWith(tmpl);
      replaced_node = $(".graf--mixtapeEmbed[name=" + (_this.node.attr("name")) + "]");
      replaced_node.find("strong").text(data.title);
      replaced_node.find("em").text(data.description);
      replaced_node.append(data.provider_url);
      replaced_node.find(".markup--anchor").attr("href", data.url);
      if (!_.isEmpty(data.images)) {
        image_node = replaced_node.find(".mixtapeImage");
        image_node.css("background-image", "url(" + data.images[0].url + ")");
        image_node.removeClass("mixtapeImage--empty u-ignoreBlock");
      }
      return _this.hide();
    };
  })(this));
};        