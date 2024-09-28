// nyaobounce.js
(function (global) {
  'use strict';

  function nyaobounce(el, customConfig = {}) {
    const defaultConfig = {
      aggressive: false,
      sensitivity: 20,
      timer: 1000,
      delay: 0,
      callback: () => {},
      cookieExpire: '',
      cookieDomain: '',
      cookieName: 'viewedNyaoBounceModal',
      sitewide: false,
    };

    const config = { ...defaultConfig, ...customConfig };

    let delayTimer = null;
    let disableKeydown = false;
    const html = document.documentElement;

    const cookieOptions = {
      name: config.cookieName,
      expire: setCookieExpire(config.cookieExpire),
      domain: config.cookieDomain ? `; domain=${config.cookieDomain}` : `; domain=${window.location.hostname}`,
      path: config.sitewide ? '; path=/' : '; path=/',
    };

    setTimeout(attachNyaobounce, config.timer);

    function setCookieExpire(days) {
      if (!days) return '';
      const ms = days * 24 * 60 * 60 * 1000;
      const date = new Date();
      date.setTime(date.getTime() + ms);
      return `; expires=${date.toUTCString()}`;
    }

    function attachNyaobounce() {
      if (isDisabled()) return;

      html.addEventListener('mouseleave', handleMouseleave);
      html.addEventListener('mouseenter', handleMouseenter);
      html.addEventListener('keydown', handleKeydown);
    }

    function handleMouseleave(e) {
      if (e.clientY > config.sensitivity) return;
      delayTimer = setTimeout(fire, config.delay);
    }

    function handleMouseenter() {
      if (delayTimer) {
        clearTimeout(delayTimer);
        delayTimer = null;
      }
    }

    function handleKeydown(e) {
      if (disableKeydown) return;
      if (!e.metaKey || e.keyCode !== 76) return;

      disableKeydown = true;
      delayTimer = setTimeout(fire, config.delay);
    }

    function isDisabled() {
      return getCookie(cookieOptions.name) === 'true' && !config.aggressive;
    }

    function fire() {
      if (isDisabled()) return;

      if (el) el.style.display = 'block';
      config.callback();
      disable();
    }

    function disable(options = {}) {
      const finalOptions = {
        ...cookieOptions,
        ...options,
        expire: setCookieExpire(options.cookieExpire || config.cookieExpire),
        domain: options.cookieDomain
          ? `;domain=${options.cookieDomain}`
          : cookieOptions.domain,
        path: options.sitewide ? ';path=/' : cookieOptions.path,
        name: options.cookieName || cookieOptions.name,
      };

      document.cookie = `${finalOptions.name}=true${finalOptions.expire}${finalOptions.domain}${finalOptions.path}`;

      // Remove event listeners
      html.removeEventListener('mouseleave', handleMouseleave);
      html.removeEventListener('mouseenter', handleMouseenter);
      html.removeEventListener('keydown', handleKeydown);
    }

    function getCookie(name) {
      const cookies = document.cookie.split('; ');
      for (const cookie of cookies) {
        const [cname, cvalue] = cookie.split('=');
        if (cname === name) return cvalue;
      }
      return null;
    }

    return {
      fire,
      disable,
      isDisabled,
    };
  }

  // Expose nyaobounce to the global object
  global.nyaobounce = nyaobounce;
})(window);
