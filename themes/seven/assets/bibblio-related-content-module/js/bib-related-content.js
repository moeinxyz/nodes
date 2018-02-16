"use strict";

(function() {
  var isNodeJS = false;

  // support for NodeJS, which doesn't support XMLHttpRequest natively
  if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    isNodeJS = true;
  }

  // Bibblio module
  var Bibblio = {
    moduleVersion: "3.0.2",
    moduleTracking: {},

    initRelatedContent: function(options, callbacks) {
      // Validate the values of the related content module options
      if(!BibblioUtils.validateModuleOptions(options))
        return;

      var callbacks = callbacks || {};
      Bibblio.getRelatedContentItems(options, callbacks);
    },

    getRelatedContentItems: function(options, callbacks) {
      var moduleSettings = BibblioUtils.getModuleSettings(options);
      var subtitleField = moduleSettings.subtitleField;
      var accessToken = options.recommendationKey;
      // URL arguments should be injected but the module only supports these settings now anyway.
      var fields = BibblioUtils.getRecommendationFields(subtitleField);
      var url = BibblioUtils.getRecommendationUrl(options, 6, 1, fields);
      BibblioUtils.bibblioHttpGetRequest(url, accessToken, true, function(response, status) {
        Bibblio.handleAutoIngestion(options, callbacks, response, status);
      });
    },

    handleAutoIngestion: function(options, callbacks, recommendationsResponse, status) {
      if(options.autoIngestion) {
        // Check if results does not exists, or is empty string
        if(!recommendationsResponse.results) {

          // Create scrape request
          Bibblio.createScrapeRequest(options);
          // Skip rendering module
          return;
        }
        else if(recommendationsResponse.results.length < 1) {
          // Display coming soon disclaimer
          BibblioUtils.displayComingSoonTemplate(options.targetElementId);
          // Skip rendering module
          return;
        }
      }
      // Render module only when recommendations have been retrieved
      if(status != 404 && recommendationsResponse.results) {
        Bibblio.renderModule(options, callbacks, recommendationsResponse);
      }
    },

    createScrapeRequest: function(options) {
      var href = ((typeof window !== 'undefined') && window.location && window.location.href) ? window.location.href : '';

      if (!href) {
        console.error("Bibblio related content module: Cannot not determine url to scrape.");
        return false;
      } else {
        href = BibblioUtils.stripUrlTrackingParameters(href);
      }

      var accessToken = options.recommendationKey;

      var scrapeRequest = {
        customUniqueIdentifier: options.customUniqueIdentifier,
        url: href
      };

      var url = "https://api.bibblio.org/v1/content-item-url-ingestions/";

      BibblioUtils.bibblioHttpPostRequest(url, accessToken, scrapeRequest, true, function(response, status) {
        Bibblio.handleCreatedScrapeRequest(response, status, options);
      });
    },

    handleCreatedScrapeRequest: function(response, status, options) {
      // If response is 422 and is a domain whitelist error, don't display coming soon container
      if(status == 422 && response.errors.url == "domain is not whitelisted") {
        console.error("Bibblio related content module: Page could not be ingested to Bibblio because domain has not been whitelisted for auto ingestion.");
      } else {
        // Display coming soon disclaimer
        BibblioUtils.displayComingSoonTemplate(options.targetElementId);
      }
    },

    renderModule: function(options, callbacks, recommendationsResponse) {
      var relatedContentItems = recommendationsResponse.results;
      var moduleSettings = BibblioUtils.getModuleSettings(options);
      var containerId = options.targetElementId;
      var relatedContentItemContainer = document.getElementById(containerId);
      var moduleHTML = BibblioTemplates.getModuleHTML(relatedContentItems, options, moduleSettings);
      relatedContentItemContainer.innerHTML = moduleHTML;

      Bibblio.initTracking(options, callbacks, recommendationsResponse);
    },

    initTracking: function(options, callbacks, recommendationsResponse) {
      var trackingLink = recommendationsResponse._links.tracking.href;
      var activityId = BibblioUtils.getActivityId(trackingLink);
      BibblioUtils.createModuleTrackingEntry(activityId);
      BibblioUtils.bindContentItemsClickEvents(options, callbacks, recommendationsResponse);
      BibblioUtils.setOnViewedListeners(options, callbacks, recommendationsResponse);
    }
  };

  // Bibblio utility module
  var BibblioUtils = {
    /// Init module functions
    validateModuleOptions: function(options) {
      if(options.autoIngestion && !options.customUniqueIdentifier) {
        console.error("Bibblio related content module: Please provide a customUniqueIdentifier in the options parameter when autoIngestion is set to true.");
        return false;
      }

      if(options.contentItemId && options.customUniqueIdentifier) {
        console.error("Bibblio related content module: Cannot supply both contentItemId and customUniqueIdentifier.");
        return false;
      }

      if(!options.targetElementId) {
        console.error("Bibblio related content module: Please provide a value for targetElementId in the options parameter.");
        return false;
      }

      if(!options.recommendationKey) {
        console.error("Bibblio related content module: Please provide a recommendation key for the recommendationKey value in the options parameter.");
        return false;
      }

      if(!options.contentItemId && !options.customUniqueIdentifier) {
        console.error("Bibblio related content module: Please provide a contentItemId or a customUniqueIdentifier in the options parameter.");
        return false;
      }

      return true;
    },

    /// Get recommendations functions
    getRecommendationFields: function(subtitleField) {
      var fields = ["name", "url", "moduleImage"];
      if(subtitleField)
        fields.push(BibblioUtils.getRootProperty(subtitleField));
      return fields;
    },

    getRecommendationUrl: function(options, limit, page, fields) {
      var baseUrl = "https://api.bibblio.org/v1";
      var catalogueIds = options.catalogueIds ? options.catalogueIds : [];
      var userId = options.userId;
      var querystringArgs = [
          "limit=" + limit,
          "page=" + page,
          "fields=" + fields.join(",")
      ];

      // Add identifier query param depending on if they supplied the uniqueCustomIdentifier or contentItemId
      var identifierQueryArg = null;
      if(options.contentItemId)
        identifierQueryArg = "contentItemId=" + options.contentItemId;
      else if(options.customUniqueIdentifier)
        identifierQueryArg = "customUniqueIdentifier=" + options.customUniqueIdentifier;
      querystringArgs.push(identifierQueryArg);

      if (catalogueIds.length > 0) {
          querystringArgs.push("catalogueIds=" + catalogueIds.join(","));
      }

      if (userId) {
          querystringArgs.push("userId=" + userId);
      }

      return baseUrl + "/recommendations?" + querystringArgs.join("&");
    },

    /// Auto ingestion functions
    stripUrlTrackingParameters: function(url) {
      var trackingParameters = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];

      var parser = document.createElement('a');
      parser.href = url;

      var params = parser.search;
      if (params.charAt(0) === '?') {
        params = params.substr(1);
      }

      if (params) {
        params = params.split('&');
        params = params.filter(function(param) {
          var paramName = param.split('=')[0].toLowerCase();
          return (trackingParameters.indexOf(paramName) === -1);
        });
      }

      if (params.length > 0) {
        parser.search = '?' + params.join('&');
      } else {
        parser.search = '';
      }

      return parser.href;
    },

    displayComingSoonTemplate: function(targetElementId) {
      var relatedContentItemCountainer = document.getElementById(targetElementId);
      relatedContentItemCountainer.innerHTML = BibblioTemplates.getComingSoonHTML();
    },

    /// Render module functions
    getPresetModuleClasses: function(stylePreset) {
      var presets = {
          "grid-4": "bib--grd-4 bib--wide",
          "box-5": "bib--box-5 bib--wide",
          "box-6": "bib--box-6 bib--wide"
      };
      return presets[stylePreset] || presets["box-6"];
    },

    linkRelFor: function(url) {
      var currentdomain = window.location.hostname;
      var matches = (BibblioUtils.getDomainName(currentdomain) == BibblioUtils.getDomainName(url));
      return (matches ? '' : ' rel="noopener noreferrer" ');
    },

    linkTargetFor: function(url) {
      var currentdomain = window.location.hostname;
      var matches = (BibblioUtils.getDomainName(currentdomain) == BibblioUtils.getDomainName(url));
      return (matches ? '_self' : '_blank');
    },

    linkHrefFor: function(url, queryStringParams) {
      if (!queryStringParams || (typeof queryStringParams !== 'object') || (Object.keys(queryStringParams).length === 0))
        return url;

      var queryStringParamsList = [];
      var param;
      Object.keys(queryStringParams).forEach(function (key) {
        param = encodeURIComponent(key) + "=" + encodeURIComponent(queryStringParams[key]);
        queryStringParamsList.push(param);
      });

      // Check if the url already has query params attached
      var urlSegments = url.split("#");
      if(urlSegments[0].indexOf('?') == -1)
        urlSegments[0] += "?";
      else
        urlSegments[0] += "&";
      urlSegments[0] += queryStringParamsList.join("&");

      return urlSegments.join("#");
    },

    /// Tracking functions
    getActivityId: function(trackingLink) {
      var activityId = trackingLink.replace("https://", "")
                                   .replace("http://", "")
                                   .replace(/v[0-9]\//g, "")
                                   .replace("api.bibblio.org/activities/", "");
      return activityId;
    },

    createModuleTrackingEntry: function(activityId) {
      Bibblio.moduleTracking[activityId] = {
        "trackedRecommendations": [],
        "hasModuleBeenViewed": false
      }
    },

    // Click events
    bindContentItemsClickEvents: function(options, callbacks, recommendationsResponse) {
      var containerId = options.targetElementId;
      var relatedContentItemlinks = document.getElementById(containerId).getElementsByClassName("bib__link");
      // (options, recommendationsResponse, callback, event)
      for (var i = 0; i < relatedContentItemlinks.length; i++) {
        // This event is only here for the callback on left clicks
        relatedContentItemlinks[i].addEventListener('click', function(event) {
          var callback = null;

          if (event.which == 1 && callbacks.onRecommendationClick) { // Left click
            callback = callbacks.onRecommendationClick;
          }

          BibblioEvents.onRecommendationClick(options, recommendationsResponse, event, callback);
        }, false);

        relatedContentItemlinks[i].addEventListener('mousedown', function(event) {
          if (event.which == 3)
            BibblioEvents.onRecommendationClick(options, recommendationsResponse, event);
        }, false);

        relatedContentItemlinks[i].addEventListener('mouseup', function(event) {
        	if (event.which < 4) {
        		BibblioEvents.onRecommendationClick(options, recommendationsResponse, event);
        	}
        }, false);

        relatedContentItemlinks[i].addEventListener('auxclick', function(event) {
          if (event.which < 4) {
            BibblioEvents.onRecommendationClick(options, recommendationsResponse, event);
          }
        }, false);

        relatedContentItemlinks[i].addEventListener('keydown', function(event) {
          if (event.which == 13) {
            BibblioEvents.onRecommendationClick(options, recommendationsResponse, event);
          }
        }, false);
      }
    },

    hasRecommendationBeenClicked: function(activityId, clickedContentItemId) {
      var moduleTrackedRecommendations = Bibblio.moduleTracking[activityId]["trackedRecommendations"];
      return moduleTrackedRecommendations.indexOf(clickedContentItemId) !== -1;
    },

    addTrackedRecommendation: function(activityId, clickedContentItemId) {
      var moduleTrackedRecommendations = Bibblio.moduleTracking[activityId]["trackedRecommendations"];
      moduleTrackedRecommendations.push(clickedContentItemId);
    },

    // Viewed event
    setOnViewedListeners: function(options, callbacks, recommendationsResponse) {
      // old (options, submitViewedActivityData, activityId, callbacks)
      // (options, recommendationsResponse, callback)
      var callback = null;
      var containerId = options.targetElementId;
      var trackingLink = recommendationsResponse._links.tracking.href;
      var activityId = BibblioUtils.getActivityId(trackingLink);
      if(callbacks.onRecommendationViewed) {
        callback = callbacks.onRecommendationViewed;
      }

      // Check if the module is in view immeditally after rendered
      if(BibblioUtils.isRecommendationTileInView(containerId)) {
        BibblioEvents.onRecommendationViewed(options, recommendationsResponse, callback);
      }
      else {
        var ticking = false;
        var visiblityCheckDelay = 50;
        // Scroll event
        var eventListener = function(event) {
          if(BibblioUtils.hasModuleBeenViewed(activityId)){
            window.removeEventListener("scroll", eventListener, true);
            return;
          }
          if(!ticking) {
            window.setTimeout(function() {
              if(BibblioUtils.isRecommendationTileInView(containerId))
                BibblioEvents.onRecommendationViewed(options, recommendationsResponse, callback);
              ticking = false;
            }, visiblityCheckDelay);
          }
          ticking = true;
        }
        window.addEventListener('scroll', eventListener, true);
      }
    },

    isRecommendationTileInView: function(containerId) {
      var tiles = document.getElementById(containerId).getElementsByClassName("bib__tile");
      var scrollableParents = BibblioUtils.getScrollableParents(containerId);
      if(scrollableParents !== false) {
        for(var i = 0; i < tiles.length; i++) {
          if(BibblioUtils.isTileVisible(tiles[i], scrollableParents))
            return true;
        }
      }
      return false;
    },

    getScrollableParents: function(containerId) {
      var moduleElement = document.getElementById(containerId);
      var moduleRect = moduleElement.getBoundingClientRect();

      // is module displayed
      if(moduleRect.top == 0 && moduleRect.bottom == 0)
        return false;

      // is module visible
      var moduleStyle = window.getComputedStyle(moduleElement);
      if(moduleStyle.getPropertyValue("visibility") === "hidden")
        return false;

      // get scrollable parents
      var parent = moduleElement.parentNode;
      var parentStyle, parentRect, isScrollable;
      var scrollableParents = [];
      while(parent !== document.body) {
        // is parent visible
        parentStyle = window.getComputedStyle(parent);
        if(parentStyle.getPropertyValue("visibility") === "hidden")
          return false;

        // does container have scrollbar
        isScrollable = BibblioUtils.hasScrollableOverflow(parentStyle.getPropertyValue("overflow-x")) ||
                       BibblioUtils.hasScrollableOverflow(parentStyle.getPropertyValue("overflow-y"));
        if(isScrollable) {
          parentRect = parent.getBoundingClientRect();
          scrollableParents.push({
            rect: parentRect,
            // replace with clientWidth and clientHeight for exact measurements
            width: parentRect.right - parentRect.left,
            height: parentRect.bottom - parentRect.top,
            style: parentStyle
          });
        }

        parent = parent.parentNode;
      }

      return scrollableParents;
    },

    isTileVisible: function(tile, scrollableParents) {
      var tileRect = tile.getBoundingClientRect();
      var tileWidth = tileRect.right - tileRect.left;
      var tileHeight = tileRect.bottom - tileRect.top;

      // is tile displayed
      if(tileHeight == 0)
        return false;

      // is tile in window's current viewport
      var isInVerticleView, isInHorizontalView;
      isInVerticleView  = tileHeight <= window.innerHeight &&     // isn't higher than viewport
                          tileRect.bottom <= window.innerHeight;  // whole tile height is within viewport
      isInHorizontalView  = tileWidth <= window.innerWidth &&     // isn't wider than viewport
                            tileRect.right <= window.innerWidth;  // whole tile width in within viewport
      if(!isInVerticleView || !isInHorizontalView)
        return false;

      // is tile displayed in scrollable parents
      var parent, parentRect;
      for(var i = 0; i < scrollableParents.length; i++) {
        parent = scrollableParents[i];
        parentRect = parent.rect;
        isInVerticleView  = tileHeight <= parent.height &&         // isn't higher than viewport
                            tileRect.bottom <= parentRect.bottom;  // whole tile height is within viewport
        isInHorizontalView  = tileWidth <= parent.width &&         // isn't wider than viewport
                              tileRect.right <= parentRect.right;  // whole tile width in within viewport
        if(!isInVerticleView || !isInHorizontalView)
          return false;
      }

      return true;
    },

    hasScrollableOverflow: function(overflowProp) {
      return overflowProp === "scroll" || overflowProp === "auto" || overflowProp === "hidden";
    },

    hasModuleBeenViewed: function(activityId) {
      return Bibblio.moduleTracking[activityId]["hasModuleBeenViewed"];
    },

    setModuleViewed: function(activityId) {
      Bibblio.moduleTracking[activityId]["hasModuleBeenViewed"] = true;
    },

    /// Common utils
    getChildProperty: function(obj, path) {
      if ((typeof obj === 'object') && (typeof path === 'string')) {
        var arr = path.split('.');
        while (arr.length && (obj = obj[arr.shift()]));
        return obj;
      } else {
        return undefined;
      }
    },

    getModuleSettings: function(options) {
      var moduleSettings = {};
      moduleSettings.stylePreset = options.stylePreset || "default";
      moduleSettings.styleClasses = options.styleClasses || false;
      moduleSettings.subtitleField = (options.subtitleField ? options.subtitleField : "headline");
      return moduleSettings;
    },

    getDomainName: function(url) {
      var r = /^(?:https?:\/\/)?(?:www\.)?(.[^/]+)/;
      var matchResult = url.match(r);
      return (url.match(r) ? matchResult[1].replace('www.', '') : "");
    },

    getRootProperty: function(accessor) {
      if (accessor == false || accessor == undefined) {
          return accessor;
      } else {
         return accessor.split(".")[0];
      }
    },

    // Http requests
    bibblioHttpGetRequest: function(url, accessToken, isAsync, callback) {
      var options = {
        url: url,
        method: "GET",
        accessToken: accessToken
      }
      BibblioUtils.bibblioHttpRequest(options, isAsync, callback);
    },

    bibblioHttpPostRequest: function(url, accessToken, body, isAsync, callback) {
      var options = {
        url: url,
        method: "POST",
        accessToken: accessToken,
        body: body
      }
      BibblioUtils.bibblioHttpRequest(options, isAsync, callback);
    },

    bibblioHttpRequest: function(options, isAsync, callback) {
      var url = options.url;
      var method = options.method;
      var accessToken = options.accessToken;
      if(isNodeJS) {
        var https = require('https');
        var baseUrl = "https://api.bibblio.org";
        var path = url.replace(baseUrl, "");
        var hostname = baseUrl.replace("https://", "");
        var httpOptions = {
          hostname: hostname,
          path: path,
          method: method,
          headers: {
            'Content-Type': 'application/json'
          }
        };
        if(accessToken) {
          httpOptions.headers.Authorization = "Bearer " + accessToken;
        }
        // Add body if method is POST
        if(method == "POST")
          httpOptions.body = JSON.stringify(options.body);

        var req = https.request(httpOptions, function(response) {
          var responseText = "";
          // Build response text
          response.on('data', function(dataChunk) {
            responseText += dataChunk;
          });

          response.on('end', function() {
              try {
                var responseObject = JSON.parse(responseText);
                BibblioUtils.httpCallback(callback, responseObject, response.statusCode);
              }
              catch(err) {
                BibblioUtils.httpCallback(callback, {}, response.statusCode);
              }
          })
        });

        req.on('error', function(e) {
          BibblioUtils.httpCallback(callback, {}, response.statusCode);
        });

        // Add body if method is POST
        if(method == "POST") {
          var requestBody = JSON.stringify(options.body);
          req.write(requestBody);
        }

        req.end();
      }
      else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
          if (xmlhttp.readyState === 4) {
            try {
              var response = JSON.parse(xmlhttp.responseText);
              BibblioUtils.httpCallback(callback, response, xmlhttp.status);
            }
            catch (err) {
              BibblioUtils.httpCallback(callback, {}, xmlhttp.status);
            }
          }
        };
        xmlhttp.open(method, url, isAsync);
        xmlhttp.setRequestHeader('Content-Type', 'application/json');
        if(accessToken)
          xmlhttp.setRequestHeader("Authorization", "Bearer " + accessToken);
        // If method is POST add body to request
        if(method == "POST") {
          var requestBody = JSON.stringify(options.body);
          xmlhttp.send(requestBody);
        }
        else
          xmlhttp.send();
      }
    },

    httpCallback: function(callback, response, status) {
      if (callback != null && typeof callback === "function") {
        callback(response, status);
      }
    }
  };

  // Bibblio events module
  var BibblioEvents = {
    onRecommendationClick: function(options, recommendationsResponse, event, callback) {
      var clickedContentItemId = event.currentTarget.getAttribute("data");
      var moduleSettings = BibblioUtils.getModuleSettings(options);
      var relatedContentItems = recommendationsResponse.results;
      var trackingLink = recommendationsResponse._links.tracking.href;
      var sourceContentItemId = recommendationsResponse._links.sourceContentItem.id;
      var activityId = BibblioUtils.getActivityId(trackingLink);

      var userId = options.userId ? options.userId : null;
      var activityData = BibblioActivity.constructOnClickedActivityData(
          sourceContentItemId,
          clickedContentItemId,
          options.catalogueIds,
          relatedContentItems,
          {
              type: "BibblioRelatedContent",
              version: Bibblio.moduleVersion,
              config: moduleSettings
          },
          userId
      );

      if(!BibblioUtils.hasRecommendationBeenClicked(activityId, clickedContentItemId)) {
        var response = BibblioActivity.track(trackingLink, activityData);
        BibblioUtils.addTrackedRecommendation(activityId, clickedContentItemId);
      }

      // Call client callback if it exists
      if (callback != null && typeof callback === "function") {
          callback(activityData, event);
      }
    },

    onRecommendationViewed: function(options, recommendationsResponse, callback) {
      var trackingLink = recommendationsResponse._links.tracking.href;
      var activityId = BibblioUtils.getActivityId(trackingLink);
      if(!BibblioUtils.hasModuleBeenViewed(activityId)) {
        var moduleSettings = BibblioUtils.getModuleSettings(options);
        var relatedContentItems = recommendationsResponse.results;
        var sourceContentItemId = recommendationsResponse._links.sourceContentItem.id;
        BibblioUtils.setModuleViewed(activityId);
        var userId = options.userId ? options.userId : null;
        var activityData = BibblioActivity.constructOnViewedActivityData(
            sourceContentItemId,
            options.catalogueIds,
            relatedContentItems,
            {
                type: "BibblioRelatedContent",
                version: Bibblio.moduleVersion,
                config: moduleSettings
            },
            userId
        );

        var response = BibblioActivity.trackAsync(trackingLink, activityData);

        // Call client callback if it exists
        if (callback != null && typeof callback === "function") {
            callback(activityData);
        }
      }
    }
  };

  // Bibblio template module
  var BibblioTemplates = {
    outerModuleTemplate: '<ul class="bib__module <% classes %>"><% recommendedContentItems %><a href="http://bibblio.org/about" target="_blank" class="bib__origin">Refined by</a> </ul>',

    relatedContentItemTemplate: '<li class="bib__tile bib__tile--<% tileNumber %> "><a href="<% linkHref %>" target="<% linkTarget %>" <% linkRel %> data="<% contentItemId %>" class="bib__link <% linkImageClass %>" <% linkStyle %> ><span class="bib__container"><span class="bib__info"><span class="bib__title"><span><% name %></span></span><% subtitleHTML %></span></span></a></li>',

    subtitleTemplate: '<span class="bib__preview"><% subtitle %></span>',

    comingSoonTemplate: '<div class="bib_pending-recs"> <div class="bib_pending-recs-text"> <div class="bib_pending-recs-header">Bibblio is busy indexing this content</div> <div class="bib_pending-recs-subheader">Relevant recommendations are on their way!</div> </div> </div>',

    getTemplate: function(template, options) {
      Object.keys(options).forEach(function(key) {
        template = template.replace("<% " + key + " %>", options[key]);
      });
      // Remove any unused placeholders
      var placeHolderStart = template.indexOf("<%");
      while(placeHolderStart != -1) {
        var placeHolderEnd = template.indexOf("%>", placeHolderStart);
        var placeHolder = template.substring(placeHolderStart, placeHolderEnd + 2);
        // Remove unused place holder
        template = template.replace(placeHolder, '');
        // Get next unused place holder
        placeHolderStart = template.indexOf("<%");
      }
      return template;
    },

    getSubtitleHTML: function(contentItem, moduleSettings) {
      var subtitleField = BibblioUtils.getChildProperty(contentItem.fields, moduleSettings.subtitleField);
      var subtitleHTML = '';
      if(subtitleField) {
        var templateOptions = {
          subtitle: subtitleField
        };
        subtitleHTML = BibblioTemplates.getTemplate(BibblioTemplates.subtitleTemplate, templateOptions);
      }

      return subtitleHTML;
    },

    getRelatedContentItemHTML: function(contentItem, contentItemIndex, options, moduleSettings) {
      // Create template for subtitle
      var subtitleHTML = BibblioTemplates.getSubtitleHTML(contentItem, moduleSettings);

      // Create template for related content item
      var contentItemUrl = (contentItem.fields.url ? contentItem.fields.url : '');
      var contentItemImageUrl = "";
      if(contentItem.fields.moduleImage && contentItem.fields.moduleImage.contentUrl)
        contentItemImageUrl = contentItem.fields.moduleImage.contentUrl;

      var templateOptions = {
          contentItemId: (contentItem.contentItemId ? contentItem.contentItemId : ''),
          name: (contentItem.fields.name ? contentItem.fields.name   : ''),
          linkHref: BibblioUtils.linkHrefFor(contentItemUrl, options.queryStringParams),
          linkTarget: BibblioUtils.linkTargetFor(contentItemUrl),
          linkRel: BibblioUtils.linkRelFor(contentItemUrl),
          linkImageClass: (contentItemImageUrl ? 'bib__link--image' : ''),
          linkStyle: (contentItemImageUrl ? 'style="background-image: url(' + contentItemImageUrl + ')"' : ''),
          subtitleHTML: subtitleHTML,
          tileNumber: contentItemIndex + 1
      };

      return BibblioTemplates.getTemplate(BibblioTemplates.relatedContentItemTemplate, templateOptions);
    },

    getOuterModuleHTML: function(moduleSettings, relatedContentItemsHTML) {
      var classes = moduleSettings.styleClasses ? moduleSettings.styleClasses : BibblioUtils.getPresetModuleClasses(moduleSettings.stylePreset);
      var templateOptions = {
          classes: classes,
          recommendedContentItems: relatedContentItemsHTML
      };

      return BibblioTemplates.getTemplate(BibblioTemplates.outerModuleTemplate, templateOptions);
    },

    getModuleHTML: function(relatedContentItems, options, moduleSettings) {
      // Create content items HTML
      var contentItemsHTML = "";
      for(var i = 0; i < relatedContentItems.length; i++) {
        contentItemsHTML += BibblioTemplates.getRelatedContentItemHTML(relatedContentItems[i], i, options, moduleSettings) + "\n";
      }

      // Create module HTML
      var moduleHTML = BibblioTemplates.getOuterModuleHTML(moduleSettings, contentItemsHTML);
      return moduleHTML;
    },

    getComingSoonHTML: function() {
      return BibblioTemplates.comingSoonTemplate;
    }
  }

  // BibblioActivity module
  var BibblioActivity = {
    track: function(trackingLink, activityData) {
      if(trackingLink != null) {
        BibblioUtils.bibblioHttpPostRequest(trackingLink, null, activityData, false);
      }
    },

    trackAsync: function(trackingLink, activityData){
      if(trackingLink != null) {
        BibblioUtils.bibblioHttpPostRequest(trackingLink, null, activityData, true);
      }
    },

    constructOnClickedActivityData: function(sourceContentItemId, clickedContentItemId, catalogueIds, relatedContentItems, instrument, userId) {
      var activityData = {
        "type": "Clicked",
        "object": BibblioActivity.constructActivityObject(clickedContentItemId),
        "context": BibblioActivity.constructActivityContext(sourceContentItemId, catalogueIds, relatedContentItems),
        "instrument": BibblioActivity.constructActivityInstrument(instrument)
      };

      if(userId != null)
        activityData["actor"] = {"userId": userId};

      return activityData;
    },

    constructOnViewedActivityData: function(sourceContentItemId, catalogueIds, relatedContentItems, instrument, userId) {
      var activityData = {
        "type": "Viewed",
        "context": BibblioActivity.constructActivityContext(sourceContentItemId, catalogueIds, relatedContentItems),
        "instrument": BibblioActivity.constructActivityInstrument(instrument)
      };

      if(userId != null)
        activityData["actor"] = {"userId": userId};

      return activityData;
    },

    constructActivityInstrument: function(instrument) {
      return {
          "type": instrument.type,
          "version": instrument.version,
          "config": instrument.config
      };
    },

    constructActivityObject: function(clickedContentItemId) {
      return [["contentItemId", clickedContentItemId]];
    },

    constructActivityContext: function(sourceContentItemId, catalogueIds, relatedContentItems) {
      var context = [];
      var href = ((typeof window !== 'undefined') && window.location && window.location.href) ? window.location.href : '';

      context.push(["sourceHref", href]);
      context.push(["sourceContentItemId", sourceContentItemId]);
      for(var i = 0; i < relatedContentItems.length; i++)
        context.push(["recommendations.contentItemId", relatedContentItems[i].contentItemId]);

      // include all specified catalogue ids in the context
      // but assume recommendations are from the source content item's catalogue if no catalogues were specified
      if (catalogueIds && (catalogueIds.length > 0)) {
        for(var i = 0; i < catalogueIds.length; i++)
          context.push(["recommendations.catalogueId", catalogueIds[i]]);
      } else {
        if (relatedContentItems[0].catalogueId) {
          context.push(["recommendations.catalogueId", relatedContentItems[0].catalogueId]);
        }
      }

      return context;
    }
  };

  if (isNodeJS) {
    module.exports = {
      Bibblio: Bibblio,
      BibblioUtils: BibblioUtils,
      BibblioActivity: BibblioActivity,
      BibblioEvents: BibblioEvents
    };
  } else {
    window.Bibblio = Bibblio;
    window.BibblioActivity = BibblioActivity;
    window.BibblioUtils = BibblioUtils;
    window.BibblioEvents = BibblioEvents;
  }
})();
