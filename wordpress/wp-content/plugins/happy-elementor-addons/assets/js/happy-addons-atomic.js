"use strict";

function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
/**
 * Happy Addons — Elementor V4 (Atomic) runtime.
 *
 * Atomic elements do not fire frontend/element_ready/*, so every component
 * here is found in the DOM and tracked in a single WeakMap:
 *  - Appearing Image Animation / Text Animation: elements listed in
 *    window.HappyAtomic.features (keyed by the element's data-id).
 *  - In the Elementor editor the canvas renders Atomic elements client side, so
 *    PHP never sees them: Appearing Image Animation reads its settings from the
 *    editor instead (window.HappyAtomic.defaults tells which features are on).
 */
(function (w, d) {
  'use strict';

  if (typeof gsap === 'undefined') {
    return;
  }
  if (typeof ScrollTrigger !== 'undefined') {
    gsap.registerPlugin(ScrollTrigger);
  }
  var MOBILE_QUERY = '(max-width: 767px)';
  var instances = new WeakMap();
  var live = []; // elements with an instance, so detached ones can be cleaned up

  function reducedMotion() {
    return w.matchMedia && w.matchMedia('(prefers-reduced-motion: reduce)').matches;
  }
  function isEditor() {
    return d.body.classList.contains('elementor-editor-active') || !!(w.elementorFrontend && w.elementorFrontend.isEditMode && w.elementorFrontend.isEditMode());
  }
  function isMobile() {
    if (w.elementorFrontend && typeof w.elementorFrontend.getCurrentDeviceMode === 'function') {
      var mode = w.elementorFrontend.getCurrentDeviceMode();
      return 'mobile' === mode || 'mobile_extra' === mode;
    }
    return w.matchMedia && w.matchMedia(MOBILE_QUERY).matches;
  }
  function num(value, fallback) {
    var parsed = parseFloat(value);
    return isNaN(parsed) ? fallback : parsed;
  }
  function parseJSON(text) {
    try {
      return JSON.parse(text) || {};
    } catch (e) {
      return {};
    }
  }
  function triggerStart(config) {
    if ('custom' === config.trigger_point) {
      return config.custom_trigger_start || 'top 80%';
    }
    return String(config.trigger_point || 'top-center').replace('-', ' ');
  }

  /* ------------------------------------------------------------------ *
   * Editor: settings of an element come from the editor, not from PHP
   * ------------------------------------------------------------------ */
  function unwrapProp(value) {
    while (value && 'object' === _typeof(value) && '$$type' in value) {
      value = value.value;
    }
    return value;
  }
  function editorSettings(id) {
    try {
      var editor = w.parent && w.parent.elementor || w.elementor;
      var container = editor && editor.getContainer && editor.getContainer(id);
      var settings = container && (container.settings || container.model && container.model.get('settings'));
      return settings ? settings.attributes || settings.toJSON && settings.toJSON() : null;
    } catch (e) {
      return null;
    }
  }

  // Settings of a feature as the frontend config: prefix stripped, defaults filled in.
  function editorFeatureConfig(settings, prefix, defaults) {
    var config = {};
    Object.keys(defaults).forEach(function (key) {
      var value = unwrapProp(settings[prefix + key]);
      config[key] = null === value || undefined === value || '' === value ? defaults[key] : value;
    });
    return config;
  }
  function register(el, instance) {
    instances.set(el, instance);
    live.push(el);
  }
  function destroy(el) {
    var instance = instances.get(el);
    if (instance && instance.destroy) {
      try {
        instance.destroy();
      } catch (e) {}
    }
    instances["delete"](el);
    live = live.filter(function (item) {
      return item !== el;
    });
  }

  /* ------------------------------------------------------------------ *
   * Appearing Image Animation (e-image)
   * ------------------------------------------------------------------ */
  function AppearingImage(img, config) {
    this.img = img;
    this.config = config;
    this.wrap = null;
    this.timelines = [];
    this.triggers = [];
    this.build();
  }
  AppearingImage.CLIP = {
    left: 'inset(0 0 0 100%)',
    right: 'inset(0 100% 0 0)',
    top: 'inset(0 0 100% 0)',
    bottom: 'inset(100% 0 0 0)'
  };
  AppearingImage.CORNER = {
    'top-left': 'inset(0 100% 100% 0)',
    'top-right': 'inset(0 0 100% 100%)',
    'bottom-left': 'inset(100% 100% 0 0)',
    'bottom-right': 'inset(100% 0 0 100%)',
    center: 'inset(50% 50% 50% 50%)'
  };
  AppearingImage.ORIGIN = {
    left: 'left center',
    right: 'right center',
    top: 'center top',
    bottom: 'center bottom'
  };
  AppearingImage.prototype.build = function () {
    var img = this.img;
    var config = this.config;
    if (reducedMotion() || isMobile() && !config.enable_on_mobile || typeof ScrollTrigger === 'undefined') {
      img.style.visibility = 'visible';
      return;
    }
    var wrap = d.createElement('span');
    wrap.className = 'ha-aia-wrap';
    img.parentNode.insertBefore(wrap, img);
    wrap.appendChild(img);
    img.style.visibility = 'visible';
    this.wrap = wrap;
    gsap.set(wrap, {
      autoAlpha: 0
    });
    if ('stretch' === config.mode) {
      this.stretch();
    } else {
      this.animate();
    }
    setTimeout(function () {
      ScrollTrigger.refresh();
    }, 50);
  };
  AppearingImage.prototype.track = function (tl) {
    this.timelines.push(tl);
    if (tl.scrollTrigger) {
      this.triggers.push(tl.scrollTrigger);
    }
  };
  AppearingImage.prototype.stretch = function () {
    var wrap = this.wrap;
    var img = this.img;
    gsap.set(wrap, {
      autoAlpha: 1
    });
    if (isEditor()) {
      return;
    }
    wrap.style.transition = 'none';
    wrap.style.paddingBottom = '395px';
    var tl = gsap.timeline({
      scrollTrigger: {
        trigger: wrap,
        start: 'top top',
        pin: true,
        scrub: 1,
        pinSpacing: false,
        end: 'bottom bottom+=100'
      }
    });
    tl.to(img, {
      width: '100%',
      borderRadius: '0px'
    });
    this.track(tl);
  };
  AppearingImage.prototype.animate = function () {
    var wrap = this.wrap;
    var img = this.img;
    var config = this.config;
    var duration = num(config.animation_duration, 1.2);
    var easing = config.easing_function || 'power4.inOut';
    var tl = gsap.timeline({
      delay: num(config.delay, 0),
      scrollTrigger: {
        trigger: wrap,
        start: triggerStart(config),
        toggleActions: 'play none none none'
      }
    });
    this.track(tl);
    tl.set(wrap, {
      autoAlpha: 1
    }, 0);
    switch (config.mode) {
      case 'corner-reveal':
        tl.from(wrap, {
          clipPath: AppearingImage.CORNER[config.corner_direction] || AppearingImage.CORNER['top-right'],
          duration: duration,
          ease: easing
        }, 0);
        tl.fromTo(img, {
          scale: 1.15
        }, {
          scale: 1,
          duration: duration,
          ease: 'power2.out'
        }, 0);
        break;
      case 'scale':
        tl.fromTo(img, {
          scale: num(config.scale_from, 0.5),
          transformOrigin: AppearingImage.ORIGIN[config.rs_direction] || AppearingImage.ORIGIN.left
        }, {
          scale: num(config.scale_to, 1),
          duration: duration,
          ease: easing
        }, 0);
        break;
      case 'tiles-reveal':
        this.tiles(tl, duration, easing);
        break;
      default:
        tl.from(wrap, {
          clipPath: AppearingImage.CLIP[config.rs_direction] || AppearingImage.CLIP.left,
          duration: duration,
          ease: easing
        }, 0);
        tl.fromTo(img, {
          scale: 1.2
        }, {
          scale: 1,
          duration: duration,
          ease: 'power2.out'
        }, 0);
    }
  };
  AppearingImage.prototype.tiles = function (tl, duration, easing) {
    var wrap = this.wrap;
    var img = this.img;
    var config = this.config;
    var rect = wrap.getBoundingClientRect();
    var count = Math.max(2, Math.round(num(config.tiles_count, 5)));
    var horizontal = 'horizontal' === config.tiles_orientation;
    var dir = horizontal ? config.tiles_horizontal_direction : config.tiles_vertical_direction;
    var axis = 'scaleY';
    var origin = 'center top';
    var slices = [];
    switch (dir) {
      case 'left-to-right':
        axis = 'scaleX';
        origin = 'left center';
        break;
      case 'right-to-left':
        axis = 'scaleX';
        origin = 'right center';
        break;
      case 'top-to-bottom':
        axis = 'scaleY';
        origin = 'center top';
        break;
      case 'bottom-to-top':
        axis = 'scaleY';
        origin = 'center bottom';
        break;
    }
    var totalW = Math.round(rect.width);
    var totalH = Math.round(rect.height);
    var size = Math.ceil((horizontal ? totalW : totalH) / count);
    wrap.style.position = 'relative';
    wrap.style.overflow = 'hidden';
    for (var i = 0; i < count; i++) {
      var offset = i * size;
      var slice = d.createElement('span');
      slice.className = 'ha-aia-slice';
      Object.assign(slice.style, {
        position: 'absolute',
        top: horizontal ? '0px' : offset + 'px',
        left: horizontal ? offset + 'px' : '0px',
        width: horizontal ? size + 0.4 + 'px' : totalW + 'px',
        height: horizontal ? totalH + 'px' : size + 0.4 + 'px',
        backgroundImage: 'url("' + (img.currentSrc || img.src) + '")',
        backgroundSize: totalW + 'px ' + totalH + 'px',
        backgroundPosition: horizontal ? '-' + offset + 'px 0px' : '0px -' + offset + 'px',
        backgroundRepeat: 'no-repeat',
        transformOrigin: origin,
        backfaceVisibility: 'hidden',
        willChange: 'transform'
      });
      wrap.appendChild(slice);
      slices.push(slice);
    }
    img.style.opacity = 0;
    var from = {
      duration: duration,
      ease: easing,
      stagger: {
        each: num(config.tiles_stagger_delay, 0.08),
        from: 'start'
      }
    };
    from[axis] = 0;
    tl.from(slices, from);
    tl.to(img, {
      opacity: 1,
      duration: 0.2,
      onComplete: function onComplete() {
        slices.forEach(function (slice) {
          slice.remove();
        });
      }
    });
  };
  AppearingImage.prototype.destroy = function () {
    this.triggers.forEach(function (trigger) {
      trigger.kill();
    });
    this.timelines.forEach(function (tl) {
      tl.kill();
    });
    this.triggers = [];
    this.timelines = [];
    var img = this.img;
    var wrap = this.wrap;
    gsap.set(img, {
      clearProps: 'all'
    });
    if (wrap && wrap.parentNode) {
      wrap.parentNode.insertBefore(img, wrap);
      wrap.remove();
    }
    this.wrap = null;
    img.style.visibility = 'visible';
  };

  /* ------------------------------------------------------------------ *
   * Text Animation (e-heading)
   * ------------------------------------------------------------------ */
  function TextAnimation(el, config) {
    this.el = el;
    this.config = config;
    this.timelines = [];
    this.triggers = [];
    this.listeners = [];
    this.original = el.innerHTML;
    this.built = false;
    this.build();
  }
  TextAnimation.prototype.build = function () {
    var el = this.el;
    var config = this.config;
    if (reducedMotion() || isMobile() && !config.enable_on_mobile || typeof ScrollTrigger === 'undefined') {
      el.style.visibility = 'visible';
      return;
    }
    this.highlight(el);
    var mode = config.mode;
    if ('slide' === mode) {
      mode = 'words' === config.chars_words_mode ? 'words' : 'chars';
    }
    var classes = {
      chars: 'ha-hta-chars',
      words: 'ha-hta-words',
      scale: 'ha-hta-text-scale',
      invert: 'ha-hta-text-invert',
      text_flip: 'ha-hta-text-flip',
      smoky_reveal: 'ha-tm-smoky-reveal',
      alt_reveal: 'ha-tm-alt-reveal',
      popup_reveal: 'ha-tm-popup-reveal',
      mixing_reveal: 'ha-tm-mixing-reveal'
    };
    this.modeClass = classes[mode] || 'ha-hta-chars';
    el.classList.add('ha-hta-yes', this.modeClass);
    this.built = true;
    this.apply(mode);
    el.style.visibility = 'visible';
  };
  TextAnimation.prototype.apply = function (mode) {
    var el = this.el;
    var config = this.config;
    var duration = num(config.duration, 0.8);
    var stagger = num(config.stagger_delay, 0.05);
    var easing = config.easing_function || 'power2.out';
    var from = config.stagger_from || 'random';
    var targets;
    var tl = gsap.timeline({
      paused: true
    });
    switch (mode) {
      case 'chars':
      case 'words':
        targets = 'chars' === mode ? this.split(el, 'ha-char') : this.splitWords(el, 'ha-word');
        tl.from(targets, {
          x: num(config.cw_transform_x, 25),
          y: num(config.cw_transform_y, 0),
          opacity: 0,
          duration: duration,
          stagger: stagger,
          ease: easing
        });
        break;
      case 'reveal':
        targets = this.split(el, 'ha-char ha-reveal-char');
        tl.from(targets, {
          yPercent: 'top' === config.tr_orientation ? -100 : 100,
          opacity: 0,
          duration: duration,
          stagger: stagger,
          ease: easing
        });
        break;
      case 'text_flip':
        targets = this.splitLines(el, false);
        gsap.set(el, {
          perspective: 400
        });
        var flip = {
          duration: duration,
          delay: num(config.delay, 0.15),
          opacity: 0,
          force3D: true,
          transformOrigin: 'custom' === config.tv_transform_origin ? config.tv_transform_origin_custom : config.tv_transform_origin,
          stagger: stagger
        };
        flip['horizontal' === config.tv_rotation_direction ? 'rotationY' : 'rotationX'] = num(config.tv_rotation_value, -80);
        tl.from(targets, flip);
        break;
      case 'scale':
        if ('chars' === config.scale_text_break) {
          targets = this.split(el, 'ha-char');
        } else if ('words' === config.scale_text_break) {
          targets = this.splitWords(el, 'ha-word');
        } else {
          targets = this.splitLines(el, false);
        }
        tl.from(targets, {
          scale: num(config.scale, 1.5),
          opacity: 0,
          duration: duration,
          stagger: stagger,
          ease: easing
        });
        break;
      case 'invert':
        this.invert();
        return;
      case 'smoky_reveal':
        targets = this.split(el, 'letter');
        gsap.set(targets, {
          y: num(config.smoky_y_offset, 80),
          scale: num(config.smoky_scale, 2.5),
          filter: 'blur(' + num(config.smoky_blur, 15) + 'px)',
          opacity: 0
        });
        tl.to(targets, {
          y: 0,
          scale: 1,
          filter: 'blur(0px)',
          opacity: 1,
          duration: duration,
          ease: easing,
          stagger: {
            each: stagger,
            from: from
          }
        });
        break;
      case 'alt_reveal':
        targets = this.split(el, 'letter');
        targets.forEach(function (letter, i) {
          gsap.set(letter, {
            y: i % 2 === 0 ? num(config.alt_even_reveal_y_offset, -60) : num(config.alt_odd_reveal_y_offset, 60),
            opacity: 0
          });
        });
        tl.to(targets, {
          y: 0,
          opacity: 1,
          duration: duration,
          ease: easing,
          stagger: stagger
        });
        break;
      case 'popup_reveal':
        targets = this.split(el, 'letter');
        gsap.set(targets, {
          y: num(config.popup_y_offset, 50),
          rotation: num(config.popup_rotation_deg, 40),
          scale: 0,
          opacity: 0,
          transformOrigin: '50% 50%'
        });
        tl.to(targets, {
          y: 0,
          rotation: 0,
          scale: 1,
          opacity: 1,
          duration: duration,
          ease: 'back.out(1.7)',
          stagger: {
            each: stagger,
            from: from
          }
        });
        break;
      case 'mixing_reveal':
        targets = this.splitWords(el, 'ha-word word');
        gsap.set(targets, {
          opacity: 0,
          scale: 0,
          filter: 'blur(' + num(config.mixing_blur, 4) + 'px)'
        });
        tl.to(targets, {
          opacity: 1,
          scale: 1,
          filter: 'blur(0px)',
          duration: duration,
          ease: easing,
          stagger: {
            each: stagger,
            from: from
          }
        });
        break;
      default:
        return;
    }
    this.attach(tl);
    this.timelines.push(tl);
  };
  TextAnimation.prototype.attach = function (tl) {
    var config = this.config;
    var el = this.el;
    if ('playwithscroll' === config.trigger_mode) {
      this.triggers.push(ScrollTrigger.create({
        trigger: el,
        start: 'top bottom',
        end: 'bottom top',
        scrub: 1,
        animation: tl
      }));
      return;
    }
    this.triggers.push(ScrollTrigger.create({
      trigger: el,
      start: triggerStart(config),
      onEnter: function onEnter() {
        gsap.delayedCall(num(config.delay, 0.15), function () {
          tl.play();
        });
      }
    }));
  };
  TextAnimation.prototype.invert = function () {
    var el = this.el;
    var config = this.config;
    if (typeof SplitText === 'undefined') {
      return;
    }
    var rgb = (w.getComputedStyle(el).color.match(/(\d+)/g) || [0, 0, 0]).map(Number);
    var r = rgb[0] / 255;
    var g = rgb[1] / 255;
    var b = rgb[2] / 255;
    var l = Math.max(r, g, b);
    var s = l - Math.min(r, g, b);
    var h = s ? l === r ? (g - b) / s : l === g ? 2 + (b - r) / s : 4 + (r - g) / s : 0;
    var hue = 60 * h < 0 ? 60 * h + 360 : 60 * h;
    var sat = 100 * (s ? l <= 0.5 ? s / (2 * l - s) : s / (2 - (2 * l - s)) : 0);
    var light = 100 * (2 * l - s) / 2;
    el.style.setProperty('--text-color', hue.toFixed(1) + ', ' + sat.toFixed(1) + '%, ' + light.toFixed(1) + '%');
    el.style.setProperty('--ha-opacity', String(num(config.invert_opacity, 0.45)));
    this.splitText = new SplitText(el, {
      type: 'lines',
      linesClass: 'invert-line'
    });
    var start = triggerStart(config);
    var self = this;
    this.splitText.lines.forEach(function (line) {
      var tween = gsap.to(line, {
        backgroundPositionX: '-100%',
        ease: 'none',
        scrollTrigger: {
          trigger: line,
          scrub: 1,
          start: start,
          end: 'bottom center'
        }
      });
      self.timelines.push(tween);
      if (tween.scrollTrigger) {
        self.triggers.push(tween.scrollTrigger);
      }
    });
  };

  // Split the text of a node into per-character spans, keeping highlight spans.
  TextAnimation.prototype.split = function (el, className) {
    return this.splitBy(el, className, '');
  };
  TextAnimation.prototype.splitWords = function (el, className) {
    return this.splitBy(el, className, ' ');
  };
  TextAnimation.prototype.splitBy = function (el, className, separator) {
    var nodes = Array.prototype.slice.call(el.childNodes);
    var parts = [];
    el.textContent = '';
    var emit = function emit(text, highlight, color) {
      var pieces = text.split(separator);
      pieces.forEach(function (piece, i) {
        var span = d.createElement('span');
        span.className = className;
        if (highlight) {
          span.classList.add('ha-tm-highlight');
          if (color) {
            span.style.color = color;
          }
        }
        span.textContent = '' === separator && ' ' === piece ? ' ' : piece;
        el.appendChild(span);
        parts.push(span);
        if (separator && i !== pieces.length - 1) {
          el.appendChild(d.createTextNode(' '));
        }
      });
    };
    nodes.forEach(function (node) {
      if (3 === node.nodeType) {
        emit(node.textContent, false);
      } else if (1 === node.nodeType) {
        emit(node.textContent, 'true' === node.getAttribute('data-highlight'), node.getAttribute('data-color'));
      }
    });
    return parts;
  };
  TextAnimation.prototype.splitLines = function (el, mask) {
    var lines = el.innerHTML.split(/<br\s*\/?>/i);
    var out = [];
    el.textContent = '';
    lines.forEach(function (line) {
      var div = d.createElement('div');
      div.className = mask ? 'ha-invert-line' : 'ha-flip';
      div.innerHTML = line; // content that was already in the element
      el.appendChild(div);
      out.push(div);
    });
    return out;
  };

  // Wrap {text} in a highlight span.
  TextAnimation.prototype.highlight = function (el) {
    var color = this.config.highlight_color || '';
    if (color) {
      el.style.setProperty('--ha-highlight-color', color);
    } else {
      el.style.removeProperty('--ha-highlight-color');
    }
    var _walk = function walk(node) {
      if (3 === node.nodeType) {
        var parts = node.textContent.split(/(\{.*?\})/g);
        if (parts.length < 2) {
          return;
        }
        var fragment = d.createDocumentFragment();
        parts.forEach(function (part) {
          if (part.length > 1 && '{' === part.charAt(0) && '}' === part.charAt(part.length - 1)) {
            var span = d.createElement('span');
            span.className = 'ha-tm-highlight';
            span.setAttribute('data-highlight', 'true');
            span.setAttribute('data-color', color);
            if (color) {
              span.style.color = color;
            }
            span.textContent = part.slice(1, -1);
            fragment.appendChild(span);
          } else if (part) {
            fragment.appendChild(d.createTextNode(part));
          }
        });
        node.parentNode.replaceChild(fragment, node);
      } else if (1 === node.nodeType) {
        Array.prototype.slice.call(node.childNodes).forEach(_walk);
      }
    };
    _walk(el);
  };

  // Stop without touching the markup: the editor has already replaced it.
  TextAnimation.prototype.abandon = function () {
    this.triggers.forEach(function (trigger) {
      trigger.kill();
    });
    this.timelines.forEach(function (tl) {
      tl.kill();
    });
    if (this.splitText) {
      this.splitText.revert();
    }
    this.triggers = [];
    this.timelines = [];
  };
  TextAnimation.prototype.destroy = function () {
    this.triggers.forEach(function (trigger) {
      trigger.kill();
    });
    this.timelines.forEach(function (tl) {
      tl.kill();
    });
    if (this.splitText) {
      this.splitText.revert();
    }
    this.triggers = [];
    this.timelines = [];
    var el = this.el;
    el.innerHTML = this.original;
    el.style.removeProperty('--ha-highlight-color');
    el.classList.remove('ha-hta-yes');
    if (this.modeClass) {
      el.classList.remove(this.modeClass);
    }
    gsap.set(el, {
      clearProps: 'perspective'
    });
    el.style.visibility = 'visible';
  };

  /* ------------------------------------------------------------------ *
   * Boot + lifecycle
   * ------------------------------------------------------------------ */
  // Features that also run in the editor preview: Atomic element type, setting
  // prefix, runtime class and the node that gets animated.
  var EDITOR_FEATURES = [{
    key: 'appearing-image-animation',
    type: 'e-image',
    prefix: 'ha_aia_',
    Runtime: AppearingImage,
    target: function target(node) {
      return node.querySelector('img');
    }
  }, {
    key: 'text-animation',
    type: 'e-heading',
    prefix: 'ha_hta_',
    Runtime: TextAnimation,
    target: function target(node) {
      return node.matches('[data-interaction-id]') ? node : node.querySelector('[data-interaction-id]');
    }
  }];

  // Features added by other plugins (Happy Addons Pro) through HappyAtomic.registerFeature().
  var external = [];
  var booted = false;
  function editorElementType(id) {
    try {
      var editor = w.parent && w.parent.elementor || w.elementor;
      var container = editor && editor.getContainer && editor.getContainer(id);
      return container ? container.model.get('widgetType') || container.model.get('elType') : null;
    } catch (e) {
      return null;
    }
  }
  function scanEditorFeatures(root) {
    var defaults = w.HappyAtomic && w.HappyAtomic.defaults;
    if (!defaults || !isEditor()) {
      return;
    }
    var features = EDITOR_FEATURES.concat(external.filter(function (feature) {
      return feature.type;
    })).filter(function (feature) {
      return defaults[feature.key];
    });
    if (!features.length) {
      return;
    }
    (root || d).querySelectorAll('[data-id]').forEach(function (node) {
      var id = node.getAttribute('data-id');
      var type = editorElementType(id);
      features.forEach(function (feature) {
        if ([].concat(feature.type).indexOf(type) === -1) {
          return;
        }
        var el = feature.target(node);
        if (!el) {
          return;
        }
        var settings = editorSettings(id);
        var config = settings ? editorFeatureConfig(settings, feature.prefix, defaults[feature.key]) : null;
        var existing = instances.get(el);

        // Features can share an element type: one feature must not destroy or
        // rebuild the runtime another feature put on the same element.
        if (existing && existing.featureKey && existing.featureKey !== feature.key) {
          return;
        }
        if (!config || !config.switcher) {
          if (existing) {
            destroy(el);
          }
          return;
        }
        var configJSON = JSON.stringify(config);
        var titleJSON = settings.title ? JSON.stringify(settings.title) : '';
        if (existing) {
          // Editing the text, or any change that re-renders the element, replaces
          // the markup the runtime built; the class it adds is then gone.
          var replaced = existing.isStale ? existing.isStale() : existing.built && !el.classList.contains('ha-hta-yes');
          var changed = existing.configJSON !== configJSON;
          if (!changed && existing.titleJSON === titleJSON && !replaced) {
            return;
          }
          if (replaced || existing.titleJSON !== titleJSON) {
            // Do not restore the old markup over what the editor rendered.
            if (existing.abandon) {
              existing.abandon();
            }
            instances["delete"](el);
            live = live.filter(function (item) {
              return item !== el;
            });
          } else {
            destroy(el);
          }
        }
        var instance = new feature.Runtime(el, config);
        instance.featureKey = feature.key;
        instance.configJSON = configJSON;
        instance.titleJSON = titleJSON;
        register(el, instance);
      });
    });
  }
  function scan(root) {
    scanEditorFeatures(root);
    var features = w.HappyAtomic && w.HappyAtomic.features || {};
    [['appearing-image-animation', AppearingImage], ['text-animation', TextAnimation]].concat(external.map(function (feature) {
      // In the editor the settings scan owns these: a runtime built here from
      // the saved config is destroyed and rebuilt by that scan, over and over.
      return isEditor() && feature.type ? null : [feature.key, feature.Runtime];
    })).filter(Boolean).forEach(function (item) {
      var configs = features[item[0]] || {};
      Object.keys(configs).forEach(function (id) {
        var el = d.querySelector('[data-id="' + id.replace(/"/g, '') + '"]');
        if (el && !instances.has(el)) {
          register(el, new item[1](el, configs[id]));
        }
      });
    });
  }
  function sweep() {
    live.slice().forEach(function (el) {
      if (!d.body.contains(el)) {
        destroy(el);
        return;
      }
    });
    scan(d);
  }
  var watching = false;
  function watchEditor() {
    if (watching) {
      return;
    }
    watching = true;
    var timer = null;
    var observer = new MutationObserver(function () {
      clearTimeout(timer);
      timer = setTimeout(sweep, 200);
    });
    observer.observe(d.body, {
      childList: true,
      subtree: true,
      attributes: true,
      attributeFilter: ['data-ha-config']
    });

    // A setting that does not change the markup (the animation settings) fires
    // no DOM mutation, so the editor settings are also checked on a timer.
    setInterval(function () {
      scanEditorFeatures(d);
    }, 500);
  }
  function boot() {
    booted = true;
    scan(d);
    if (isEditor()) {
      watchEditor();
    }
    w.addEventListener('pagehide', function () {
      live.slice().forEach(destroy);
    });
  }

  // Public surface for add-on features: shared helpers and a registration hook.
  w.HappyAtomic = w.HappyAtomic || {};
  w.HappyAtomic.api = {
    reducedMotion: reducedMotion,
    isEditor: isEditor,
    isMobile: isMobile,
    num: num
  };
  w.HappyAtomic.registerFeature = function (feature) {
    if (!feature || !feature.key || 'function' !== typeof feature.Runtime) {
      return;
    }
    external.push(feature);
    if (booted) {
      scan(d);
    }
  };
  if ('loading' === d.readyState) {
    d.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})(window, document);