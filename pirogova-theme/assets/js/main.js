/**
 * Pirogova Theme — main.js
 *
 * Modules:
 *  1. Header scroll behaviour + mobile burger
 *  2. Smooth scroll for anchor links
 *  3. Product popup (open, load data, close)
 *  4. Variation (weight) selector inside popup
 *  5. AJAX Add to cart
 *  6. Cart count refresh
 */

/* global PirogovaData, Swiper */

'use strict';

(function () {

  // ─── Helpers ─────────────────────────────────────────────────────────────
  const $ = (sel, ctx = document) => ctx.querySelector(sel);
  const $$ = (sel, ctx = document) => [...ctx.querySelectorAll(sel)];

  // ─── 1. Header ───────────────────────────────────────────────────────────
  const header = $('#site-header');
  const burgerBtn = $('#burger-btn');
  const mobileMenu = $('#mobile-menu');
  const mobileOverlay = $('#mobile-overlay');

  // Add "scrolled" class when page is scrolled past threshold.
  if (header) {
    const onScroll = () => {
      header.classList.toggle('scrolled', window.scrollY > 20);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  // Mobile burger toggle.
  if (burgerBtn && mobileMenu) {
    const toggleMenu = (open) => {
      burgerBtn.setAttribute('aria-expanded', String(open));
      mobileMenu.classList.toggle('open', open);
      mobileMenu.setAttribute('aria-hidden', String(!open));
      mobileOverlay.classList.toggle('open', open);
      document.body.style.overflow = open ? 'hidden' : '';
    };

    burgerBtn.addEventListener('click', () => {
      const isOpen = burgerBtn.getAttribute('aria-expanded') === 'true';
      toggleMenu(!isOpen);
    });

    mobileOverlay.addEventListener('click', () => toggleMenu(false));

    // Close when a link inside is clicked.
    $$('a', mobileMenu).forEach(link => {
      link.addEventListener('click', () => toggleMenu(false));
    });
  }

  // ─── 2. Smooth scroll ────────────────────────────────────────────────────
  document.addEventListener('click', (e) => {
    const anchor = e.target.closest('a[href^="#"]');
    if (!anchor) return;
    const target = document.getElementById(anchor.getAttribute('href').slice(1));
    if (!target) return;
    e.preventDefault();
    const offset = (parseInt(getComputedStyle(document.documentElement).getPropertyValue('--header-h'), 10) || 72) + 8;
    window.scrollTo({ top: target.getBoundingClientRect().top + window.scrollY - offset, behavior: 'smooth' });
  });

  // ─── 3. Popup ────────────────────────────────────────────────────────────
  const overlay = $('#popup-overlay');
  const popup = $('#product-popup');
  const popupClose = $('#popup-close');

  // Swiper instance (lazy-init once popup opens first time).
  let swiper = null;
  let currentProductId = null;
  let currentVariationId = null;

  /**
   * Initialise or re-init the Swiper carousel inside the popup.
   */
  function initSwiper() {
    if (swiper) swiper.destroy(true, true);
    swiper = new Swiper('#popup-swiper', {
      loop: false,
      spaceBetween: 0,
      pagination: { el: '.popup-swiper-pagination', clickable: true },
      navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
      keyboard: { enabled: true },
      a11y: { enabled: true },
    });
  }

  /**
   * Open popup, show spinner, then load product data.
   * @param {number} productId
   * @param {number|null} preselectedVariationId
   */
  function openPopup(productId, preselectedVariationId = null) {
    if (!popup || !overlay) return;

    // Show overlay + popup.
    overlay.classList.add('open');
    overlay.removeAttribute('aria-hidden');
    popup.classList.add('open');
    popup.removeAttribute('aria-hidden');
    document.body.style.overflow = 'hidden';
    popup.focus();

    // Reset state.
    showPopupLoading(true);
    hideCartFeedback();

    currentProductId = productId;
    currentVariationId = null;

    // Load product data via AJAX.
    loadProduct(productId, preselectedVariationId);
  }

  /**
   * Close popup and clean up.
   */
  function closePopup() {
    if (!popup || !overlay) return;
    overlay.classList.remove('open');
    overlay.setAttribute('aria-hidden', 'true');
    popup.classList.remove('open');
    popup.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    currentProductId = null;
    currentVariationId = null;
    // Return focus to the trigger that opened the popup.
    if (lastTrigger) {
      lastTrigger.focus();
      lastTrigger = null;
    }
  }

  let lastTrigger = null;

  // Attach open triggers.
  document.addEventListener('click', (e) => {
    const trigger = e.target.closest('.pirogova-popup-trigger');
    if (!trigger) return;
    const productId = parseInt(trigger.dataset.productId || trigger.closest('[data-product-id]')?.dataset.productId, 10);
    if (!productId) return;
    const variationId = trigger.dataset.variationId ? parseInt(trigger.dataset.variationId, 10) : null;
    lastTrigger = trigger;
    openPopup(productId, variationId);
  });

  // Close handlers.
  if (popupClose) popupClose.addEventListener('click', closePopup);
  if (overlay) overlay.addEventListener('click', closePopup);

  // Escape key closes popup.
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && popup?.classList.contains('open')) closePopup();
  });

  // Trap focus inside popup while open.
  popup?.addEventListener('keydown', (e) => {
    if (e.key !== 'Tab') return;
    const focusable = $$('button:not([disabled]), input:not([disabled]), a[href], [tabindex="0"]', popup)
      .filter(el => el.offsetParent !== null);
    if (!focusable.length) return;
    const first = focusable[0];
    const last = focusable[focusable.length - 1];
    if (e.shiftKey && document.activeElement === first) {
      e.preventDefault();
      last.focus();
    } else if (!e.shiftKey && document.activeElement === last) {
      e.preventDefault();
      first.focus();
    }
  });

  // ─── 4. Load product data ────────────────────────────────────────────────
  function loadProduct(productId, preselectedVariationId) {
    const body = new URLSearchParams({
      action: 'pirogova_get_product',
      nonce: PirogovaData.nonce,
      product_id: productId,
    });

    fetch(PirogovaData.ajaxUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: body.toString(),
    })
      .then(r => r.json())
      .then(res => {
        if (!res.success) throw new Error(res.data?.message || 'Ошибка');
        renderProduct(res.data, preselectedVariationId);
      })
      .catch(err => {
        console.error('[Pirogova] Product load error:', err);
        showPopupLoading(false);
      });
  }

  /**
   * Render product data into popup DOM.
   */
  function renderProduct(data, preselectedVariationId) {
    // Title.
    const titleEl = $('#popup-product-name');
    if (titleEl) titleEl.textContent = data.name;

    // Description.
    const descEl = $('#popup-description');
    if (descEl) descEl.innerHTML = data.description || '';

    // Gallery.
    renderGallery(data.images);

    // Variations.
    renderWeights(data.variations, preselectedVariationId);

    // Show content.
    showPopupLoading(false);

    // If simple product (no variations), show price immediately.
    if (data.type === 'simple') {
      currentVariationId = data.id;
      const priceEl = $('#popup-price');
      if (priceEl) priceEl.innerHTML = data.price_html || '';
      enableAddToCart(true);
    }
  }

  /**
   * Build Swiper slides from images array.
   */
  function renderGallery(images) {
    const wrapper = $('#popup-swiper-wrapper');
    if (!wrapper) return;
    wrapper.innerHTML = '';

    if (!images || !images.length) {
      wrapper.innerHTML = '<div class="swiper-slide"><div class="popup__img-skeleton"></div></div>';
    } else {
      images.forEach(img => {
        const slide = document.createElement('div');
        slide.className = 'swiper-slide';
        const picture = document.createElement('img');
        picture.src = img.src;
        picture.alt = img.alt || '';
        picture.loading = 'eager';
        slide.appendChild(picture);
        wrapper.appendChild(slide);
      });
    }

    initSwiper();
  }

  /**
   * Swap only the first slide's image (used when selecting a variation).
   */
  function swapGalleryImage(imgSrc, imgAlt) {
    if (!swiper) return;
    const firstSlide = swiper.slides[0];
    if (!firstSlide) return;
    const img = firstSlide.querySelector('img');
    if (img) {
      img.style.opacity = '0';
      img.style.transition = 'opacity 0.2s';
      setTimeout(() => {
        img.src = imgSrc;
        img.alt = imgAlt || '';
        img.style.opacity = '1';
      }, 150);
    }
    swiper.slideTo(0, 300);
  }

  /**
   * Render weight / variation buttons.
   */
  function renderWeights(variations, preselectedVariationId) {
    const container = $('#popup-weights');
    const optionsEl = $('#popup-weights-options');
    const priceEl = $('#popup-price');
    const addBtn = $('#popup-add-to-cart');

    if (!container || !optionsEl) return;

    if (!variations || !variations.length) {
      container.style.display = 'none';
      return;
    }

    container.style.display = '';
    optionsEl.innerHTML = '';

    variations.forEach(variation => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'popup__weight-btn';
      btn.textContent = variation.weight_label;
      btn.dataset.variationId = variation.id;
      btn.dataset.priceHtml = variation.price_html;
      btn.dataset.imageSrc = variation.image_src || '';
      btn.dataset.imageAlt = variation.image_alt || '';
      btn.disabled = !variation.in_stock;
      if (!variation.in_stock) btn.title = 'Нет в наличии';

      btn.addEventListener('click', () => {
        // Deselect all.
        $$('.popup__weight-btn', optionsEl).forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        currentVariationId = variation.id;

        // Update price.
        if (priceEl) {
          priceEl.classList.add('updating');
          priceEl.innerHTML = variation.price_html;
          setTimeout(() => priceEl.classList.remove('updating'), 250);
        }

        // Swap main gallery image.
        if (variation.image_src) {
          swapGalleryImage(variation.image_src, variation.image_alt);
        }

        // Enable add-to-cart.
        enableAddToCart(variation.in_stock);
      });

      optionsEl.appendChild(btn);
    });

    // Pre-select.
    const toSelect = preselectedVariationId
      ? optionsEl.querySelector(`[data-variation-id="${preselectedVariationId}"]`)
      : optionsEl.querySelector('.popup__weight-btn:not([disabled])');

    if (toSelect) toSelect.click();
    else if (priceEl) priceEl.innerHTML = '';
  }

  // ─── 5. Add to cart ──────────────────────────────────────────────────────
  const addToCartBtn = $('#popup-add-to-cart');
  const qtyInput = $('#popup-qty-input');
  const qtyMinus = $('#popup-qty-minus');
  const qtyPlus = $('#popup-qty-plus');

  if (qtyMinus) qtyMinus.addEventListener('click', () => {
    const val = parseInt(qtyInput.value, 10) || 1;
    if (val > 1) qtyInput.value = val - 1;
  });
  if (qtyPlus) qtyPlus.addEventListener('click', () => {
    const val = parseInt(qtyInput.value, 10) || 1;
    qtyInput.value = Math.min(val + 1, 99);
  });
  if (qtyInput) qtyInput.addEventListener('change', () => {
    let val = parseInt(qtyInput.value, 10) || 1;
    val = Math.max(1, Math.min(99, val));
    qtyInput.value = val;
  });

  if (addToCartBtn) {
    addToCartBtn.addEventListener('click', () => {
      if (!currentProductId) return;

      const qty = parseInt(qtyInput?.value, 10) || 1;
      const i18n = PirogovaData.i18n;

      // Disable button during request.
      addToCartBtn.disabled = true;
      addToCartBtn.textContent = i18n.adding;

      const body = new URLSearchParams({
        action: 'pirogova_add_to_cart',
        nonce: PirogovaData.nonce,
        product_id: currentProductId,
        variation_id: currentVariationId || 0,
        quantity: qty,
      });

      fetch(PirogovaData.ajaxUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body.toString(),
      })
        .then(r => r.json())
        .then(res => {
          if (!res.success) throw new Error(res.data?.message || 'Ошибка');
          // Update cart count.
          updateCartCount(res.data.cart_count);
          // Show success feedback.
          showCartFeedback(i18n.added, res.data.checkout_url);
          // Re-enable button.
          addToCartBtn.textContent = i18n.addToCart;
          addToCartBtn.disabled = false;
        })
        .catch(err => {
          console.error('[Pirogova] Add to cart error:', err);
          addToCartBtn.textContent = i18n.addToCart;
          addToCartBtn.disabled = false;
        });
    });
  }

  // ─── 6. Cart count ───────────────────────────────────────────────────────
  function updateCartCount(count) {
    const countEl = $('#cart-count');
    if (!countEl) return;
    countEl.textContent = count;
    countEl.dataset.count = count;
    // Bump animation.
    countEl.classList.remove('bump');
    void countEl.offsetWidth; // reflow
    countEl.classList.add('bump');
    setTimeout(() => countEl.classList.remove('bump'), 300);
  }

  // ─── Popup helpers ───────────────────────────────────────────────────────
  function showPopupLoading(show) {
    const loading = $('#popup-loading');
    const content = $('#popup-content');
    if (loading) loading.style.display = show ? 'flex' : 'none';
    if (content) {
      if (show) {
        content.setAttribute('hidden', '');
      } else {
        content.removeAttribute('hidden');
      }
    }
  }

  function enableAddToCart(enabled) {
    const btn = $('#popup-add-to-cart');
    if (!btn) return;
    btn.disabled = !enabled;
    btn.setAttribute('aria-disabled', String(!enabled));
  }

  function showCartFeedback(message, checkoutUrl) {
    const feedback = $('#popup-cart-feedback');
    const msg = $('#popup-cart-msg');
    const link = feedback?.querySelector('.popup__checkout-link');
    if (!feedback) return;
    if (msg) msg.textContent = message;
    if (link && checkoutUrl) link.href = checkoutUrl;
    feedback.removeAttribute('hidden');
  }

  function hideCartFeedback() {
    const feedback = $('#popup-cart-feedback');
    if (feedback) feedback.setAttribute('hidden', '');
  }

  // ─── Scroll-reveal ───────────────────────────────────────────────────────
  if ('IntersectionObserver' in window) {
    const revealItems = $$('.catalog-card, .hiw__step, .media-card, .contacts__item');
    const revealObs = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
          revealObs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    revealItems.forEach((el, i) => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(24px)';
      el.style.transitionProperty = 'opacity, transform';
      el.style.transitionDuration = '0.45s';
      el.style.transitionTimingFunction = 'cubic-bezier(0.4, 0, 0.2, 1)';
      el.style.transitionDelay = `${(i % 4) * 60}ms`;
      revealObs.observe(el);
    });
  }

})();
