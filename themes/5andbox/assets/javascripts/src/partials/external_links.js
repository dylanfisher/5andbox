// External links

// Hot paths cached once
const DOC_HOST = location.host;
const EXC_PROTO_RE = /^(mailto:|tel:|sms:|javascript:)/i;
const PDF_RE = /\.pdf(?:[#?]|$)/i;

// External links
App.isExternalLink = function (el) {
  // Read raw attribute (cheap) first
  const href = el.getAttribute('href');
  if (!href) return false;

  // Fast-proto bail (mailto, tel, etc.)
  if (EXC_PROTO_RE.test(href)) return false;

  // Absolute URL resolved by the browser
  const abs = el.href;
  if (!abs) return false;

  // Treat PDFs as "external" (open in new tab)
  if (PDF_RE.test(abs)) return true;

  // Host compare (relative href => el.host may be '')
  const host = el.host || DOC_HOST;
  return host !== DOC_HOST;
};

App.pageLoad.push(function () {
  // Only links/areas with href (fewer nodes than querySelectorAll('a'))
  const links = document.links;

  for (let i = 0; i < links.length; i++) {
    const link = links[i];

    if (!App.isExternalLink(link)) continue;

    // Minimal attribute writes
    if (!link.target) link.target = '_blank';

    link.classList.add('external-link');
  }
});
