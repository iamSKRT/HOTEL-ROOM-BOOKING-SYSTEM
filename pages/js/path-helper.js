(function() {
  const path = window.location.pathname;
  const pagesSegment = '/pages/';
  const pagesIndex = path.indexOf(pagesSegment);
  const siteRoot = pagesIndex >= 0 ? path.slice(0, pagesIndex + 1) : '/';
  const normalizedRoot = siteRoot.endsWith('/') ? siteRoot : siteRoot + '/';
  window.sitePaths = {
    root: normalizedRoot,
    pagesBase: normalizedRoot + 'pages/',
    apiBase: normalizedRoot + 'api/',
    assetsBase: normalizedRoot + 'assets/',
    page(file) { return this.pagesBase + file.replace(/^\/+/, ''); },
    api(file) { return this.apiBase + file.replace(/^\/+/, ''); },
    asset(file) {
      if (!file) return '';
      if (/^https?:\/\//.test(file)) return file;
      const cleaned = file.replace(/^\.\/+/,'').replace(/^\/+/, '');
      if (/^assets\//.test(cleaned)) {
        return this.assetsBase + cleaned.replace(/^assets\//, '');
      }
      return this.assetsBase + cleaned;
    },
    absolute(file) {
      if (!file) return '';
      if (/^https?:\/\//.test(file)) return file;
      return this.root + file.replace(/^\.\/+/,'').replace(/^\/+/, '');
    },
    image(file) {
      if (!file) return '';
      if (/^https?:\/\//.test(file)) return file;
      const cleaned = file.replace(/^\.\/+/,'').replace(/^\/+/, '');
      if (/^(assets|uploads|images)\//.test(cleaned)) {
        return this.root + cleaned;
      }
      return this.assetsBase + cleaned;
    },
  };
})();
