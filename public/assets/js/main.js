const wishlistGrid = document.querySelector(".wishlist-grid");
if (wishlistGrid) {
  const wishlistPage = wishlistGrid.closest(".wishlist-page");
  const wishlistEmpty = wishlistPage.querySelector(".wishlist-empty");

  function updateWishlistEmptyState() {
    const isEmpty = wishlistGrid.children.length === 0;
    wishlistEmpty.classList.toggle("is-visible", isEmpty);
    wishlistGrid.hidden = isEmpty;
  }

  wishlistGrid.addEventListener("click", function (event) {
    const removeButton = event.target.closest(".wishlist-remove, .wishlist-button");
    if (removeButton) {
      removeButton.closest(".wishlist-card").remove();
      updateWishlistEmptyState();
    }
  });

  wishlistPage.querySelector(".wishlist-clear").addEventListener("click", function () {
    wishlistGrid.replaceChildren();
    updateWishlistEmptyState();
  });
}

const accountPage = document.querySelector(".account-page");
if (accountPage) {
  const accountTabs = Array.from(accountPage.querySelectorAll('[role="tab"]'));
  const accountPanels = Array.from(accountPage.querySelectorAll('[role="tabpanel"]'));
  const accountTitle = accountPage.querySelector(".account-page-title");

  function activateAccountTab(tab, updateHash) {
    const targetId = tab.getAttribute("aria-controls");
    accountTitle.textContent = tab.dataset.pageTitle;
    accountTabs.forEach(function (item) {
      const isActive = item === tab;
      item.classList.toggle("is-active", isActive);
      item.setAttribute("aria-selected", String(isActive));
      item.tabIndex = isActive ? 0 : -1;
    });
    accountPanels.forEach(function (panel) {
      panel.hidden = panel.id !== targetId;
    });
    if (updateHash) {
      window.history.replaceState(null, "", tab.getAttribute("href"));
    }
  }

  accountTabs.forEach(function (tab, index) {
    tab.addEventListener("click", function (event) {
      event.preventDefault();
      activateAccountTab(tab, true);
    });
    tab.addEventListener("keydown", function (event) {
      let nextIndex = index;
      if (event.key === "ArrowDown" || event.key === "ArrowRight") {
        nextIndex = (index + 1) % accountTabs.length;
      } else if (event.key === "ArrowUp" || event.key === "ArrowLeft") {
        nextIndex = (index - 1 + accountTabs.length) % accountTabs.length;
      } else if (event.key === "Home") {
        nextIndex = 0;
      } else if (event.key === "End") {
        nextIndex = accountTabs.length - 1;
      } else {
        return;
      }
      event.preventDefault();
      accountTabs[nextIndex].focus();
      activateAccountTab(accountTabs[nextIndex], true);
    });
  });

  const initialTab = accountTabs.find(function (tab) {
    return tab.getAttribute("href") === window.location.hash;
  }) || accountTabs[0];
  activateAccountTab(initialTab, false);

  const accountForm = accountPage.querySelector("[data-account-form]");
  accountForm.addEventListener("submit", function (event) {
    event.preventDefault();
    const newPassword = accountForm.elements.newPassword.value;
    const repeatPassword = accountForm.elements.repeatPassword.value;
    const message = accountPage.querySelector("[data-account-message]");
    if (newPassword && newPassword !== repeatPassword) {
      accountForm.elements.repeatPassword.setCustomValidity("Passwords do not match.");
      accountForm.elements.repeatPassword.reportValidity();
      return;
    }
    accountForm.elements.repeatPassword.setCustomValidity("");
    message.textContent = "Your changes are ready to be saved.";
  });

  accountForm.elements.repeatPassword.addEventListener("input", function () {
    this.setCustomValidity("");
  });
  accountForm.elements.newPassword.addEventListener("input", function () {
    accountForm.elements.repeatPassword.setCustomValidity("");
  });

  const wishlistTable = accountPage.querySelector(".account-wishlist-table");
  const wishlistEmpty = accountPage.querySelector(".account-wishlist-empty");
  wishlistTable.addEventListener("click", function (event) {
    const removeButton = event.target.closest(".account-remove-item");
    if (removeButton) {
      removeButton.closest("tr").remove();
      const isEmpty = wishlistTable.tBodies[0].rows.length === 0;
      wishlistTable.hidden = isEmpty;
      wishlistEmpty.hidden = !isEmpty;
    }
  });
}

document.querySelector(".search-form").addEventListener("submit", function (event) {
  event.preventDefault();
});

const productGrid = document.querySelector("#productGrid");
if (productGrid) {
  const viewButtons = document.querySelectorAll(".view-switch-button");

  viewButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      const columns = Number(button.dataset.columns);
      productGrid.style.setProperty("--product-columns", String(columns));

      viewButtons.forEach(function (viewButton) {
        const isActive = viewButton === button;
        viewButton.classList.toggle("is-active", isActive);
        viewButton.setAttribute("aria-pressed", String(isActive));
      });
    });
  });

  document.querySelectorAll(".wishlist-button:not(.addtoWishList)").forEach(function (button) {
    button.addEventListener("click", function () {
      const isActive = button.classList.toggle("is-active");
      const productName = button.closest(".listing-card").querySelector("img").alt;
      button.setAttribute("aria-pressed", String(isActive));
      button.setAttribute(
        "aria-label",
        (isActive ? "Remove " : "Add ") + productName + (isActive ? " from" : " to") + " wishlist"
      );
    });
  });
}

const filterDrawer = document.querySelector(".product-filters");
if (filterDrawer) {
  const filterForm = filterDrawer.querySelector("#productFilters");
  const filterTrigger = document.querySelector(".mobile-filter-button");
  const filterBackdrop = document.querySelector(".filter-backdrop");
  const filterCloseButton = filterDrawer.querySelector(".filter-close-button");
  const filterApplyButton = filterDrawer.querySelector(".filter-apply-button");
  const filterSelectionCount = filterTrigger.querySelector(".filter-selection-count");
  const mobileFilterQuery = window.matchMedia("(max-width: 767.98px)");

  function updateFilterSelectionCount() {
    const checkedCount = filterForm.querySelectorAll('input[type="checkbox"]:checked').length;
    const minPrice = filterForm.querySelector('[name="price-min"]');
    const maxPrice = filterForm.querySelector('[name="price-max"]');
    const priceChanged = minPrice.value !== minPrice.defaultValue || maxPrice.value !== maxPrice.defaultValue;
    const selectionCount = checkedCount + Number(priceChanged);

    filterSelectionCount.textContent = String(selectionCount);
    filterSelectionCount.hidden = selectionCount === 0;
  }

  function closeFilterDrawer(returnFocus) {
    filterDrawer.classList.remove("is-open");
    filterBackdrop.classList.remove("is-visible");
    filterDrawer.inert = true;
    filterDrawer.removeAttribute("role");
    filterDrawer.removeAttribute("aria-modal");
    filterTrigger.setAttribute("aria-expanded", "false");
    document.body.classList.remove("filter-drawer-open");
    if (returnFocus && mobileFilterQuery.matches) {
      filterTrigger.focus();
    }
  }

  function openFilterDrawer() {
    if (!mobileFilterQuery.matches) {
      return;
    }
    filterDrawer.inert = false;
    filterDrawer.setAttribute("role", "dialog");
    filterDrawer.setAttribute("aria-modal", "true");
    filterDrawer.classList.add("is-open");
    filterBackdrop.classList.add("is-visible");
    filterTrigger.setAttribute("aria-expanded", "true");
    document.body.classList.add("filter-drawer-open");
    filterCloseButton.focus();
  }

  filterTrigger.addEventListener("click", openFilterDrawer);
  filterCloseButton.addEventListener("click", function () {
    closeFilterDrawer(true);
  });
  filterBackdrop.addEventListener("click", function () {
    closeFilterDrawer(true);
  });
  filterApplyButton.addEventListener("click", function () {
    closeFilterDrawer(true);
  });
  filterForm.addEventListener("input", updateFilterSelectionCount);
  filterForm.addEventListener("change", updateFilterSelectionCount);
  filterForm.addEventListener("reset", function () {
    window.setTimeout(updateFilterSelectionCount, 0);
  });

  filterDrawer.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
      event.preventDefault();
      closeFilterDrawer(true);
      return;
    }
    if (event.key !== "Tab") {
      return;
    }

    const focusableItems = Array.from(filterDrawer.querySelectorAll(
      'button:not([disabled]), input:not([disabled]), summary, select, [tabindex]:not([tabindex="-1"])'
    )).filter(function (item) {
      return !item.hidden && item.offsetParent !== null;
    });
    const firstItem = focusableItems[0];
    const lastItem = focusableItems[focusableItems.length - 1];

    if (event.shiftKey && document.activeElement === firstItem) {
      event.preventDefault();
      lastItem.focus();
    } else if (!event.shiftKey && document.activeElement === lastItem) {
      event.preventDefault();
      firstItem.focus();
    }
  });

  function syncFilterDrawerToViewport() {
    if (mobileFilterQuery.matches) {
      if (!filterDrawer.classList.contains("is-open")) {
        filterDrawer.inert = true;
      }
    } else {
      filterDrawer.inert = false;
      filterDrawer.classList.remove("is-open");
      filterBackdrop.classList.remove("is-visible");
      filterDrawer.removeAttribute("role");
      filterDrawer.removeAttribute("aria-modal");
      filterTrigger.setAttribute("aria-expanded", "false");
      document.body.classList.remove("filter-drawer-open");
    }
  }

  mobileFilterQuery.addEventListener("change", syncFilterDrawerToViewport);
  syncFilterDrawerToViewport();
  updateFilterSelectionCount();
}

const priceFilter = document.querySelector(".filter-price-group");
if (priceFilter) {
  const minRange = priceFilter.querySelector(".filter-price-min-range");
  const maxRange = priceFilter.querySelector(".filter-price-max-range");
  const minInput = priceFilter.querySelector('[name="price-min"]');
  const maxInput = priceFilter.querySelector('[name="price-max"]');
  const minValue = priceFilter.querySelector(".filter-range-min-value");
  const maxValue = priceFilter.querySelector(".filter-range-max-value");
  const minBound = Number(minRange.min);
  const maxBound = Number(minRange.max);

  function updatePriceFilter(source) {
    let min = Number(minInput.value);
    let max = Number(maxInput.value);

    if (source === minRange) {
      min = Number(minRange.value);
      minInput.value = String(min);
    } else if (source === maxRange) {
      max = Number(maxRange.value);
      maxInput.value = String(max);
    } else if (source === minInput) {
      min = Math.max(minBound, Math.min(maxBound, min));
      if (min > max) {
        max = min;
        maxInput.value = String(max);
      }
      minInput.value = String(min);
      minRange.value = String(min);
      maxRange.value = String(max);
    } else if (source === maxInput) {
      max = Math.max(minBound, Math.min(maxBound, max));
      if (max < min) {
        min = max;
        minInput.value = String(min);
      }
      minRange.value = String(min);
      maxInput.value = String(max);
      maxRange.value = String(max);
    }

    if (min > max) {
      if (source === minRange) {
        max = min;
        maxRange.value = String(max);
        maxInput.value = String(max);
      } else {
        min = max;
        minRange.value = String(min);
        minInput.value = String(min);
      }
    }

    minValue.textContent = String(min);
    maxValue.textContent = String(max);
  }

  minRange.addEventListener("input", function () {
    updatePriceFilter(minRange);
  });
  maxRange.addEventListener("input", function () {
    updatePriceFilter(maxRange);
  });
  minInput.addEventListener("input", function () {
    updatePriceFilter(minInput);
  });
  maxInput.addEventListener("input", function () {
    updatePriceFilter(maxInput);
  });
  priceFilter.closest("form").addEventListener("reset", function () {
    window.setTimeout(function () {
      updatePriceFilter();
      minRange.value = minInput.value;
      maxRange.value = maxInput.value;
    }, 0);
  });
}

function enableDragSlider(carousel, onDrag) {
  let pointerStart = null;
  let suppressClick = false;

  carousel.addEventListener("dragstart", function (event) {
    event.preventDefault();
  });

  carousel.addEventListener("pointerdown", function (event) {
    if (event.button !== 0 || !event.isPrimary) {
      return;
    }

    pointerStart = {
      id: event.pointerId,
      x: event.clientX,
      y: event.clientY,
      dragged: false
    };
  });

  document.addEventListener("pointermove", function (event) {
    if (!pointerStart || event.pointerId !== pointerStart.id) {
      return;
    }

    const deltaX = event.clientX - pointerStart.x;
    const deltaY = event.clientY - pointerStart.y;
    if (!pointerStart.dragged && Math.abs(deltaX) > 40 && Math.abs(deltaX) > Math.abs(deltaY)) {
      pointerStart.dragged = true;
      carousel.classList.add("is-dragging");
      onDrag(deltaX < 0 ? 1 : -1);
    }
    if (pointerStart.dragged) {
      event.preventDefault();
    }
  }, { passive: false });

  function finishDrag(event) {
    if (!pointerStart || event.pointerId !== pointerStart.id) {
      return;
    }
    suppressClick = pointerStart.dragged;
    pointerStart = null;
    carousel.classList.remove("is-dragging");
    if (suppressClick) {
      window.setTimeout(function () {
        suppressClick = false;
      }, 0);
    }
  }

  document.addEventListener("pointerup", finishDrag);
  document.addEventListener("pointercancel", finishDrag);
  carousel.addEventListener("click", function (event) {
    if (suppressClick) {
      event.preventDefault();
      event.stopImmediatePropagation();
      suppressClick = false;
    }
  }, true);
}

if (document.querySelector("#heroCarousel")) {
const heroCarousel = document.querySelector("#heroCarousel");
const heroCarouselInstance = bootstrap.Carousel.getOrCreateInstance(heroCarousel);
enableDragSlider(heroCarousel, function (direction) {
  heroCarouselInstance[direction > 0 ? "next" : "prev"]();
  return true;
});

const offerList = document.querySelector(".hero-thumbnails");
document.querySelectorAll(".hero-rail-arrow").forEach(function (button) {
  button.addEventListener("click", function () {
    const firstOffer = offerList.firstElementChild;
    const lastOffer = offerList.lastElementChild;
    const direction = button.dataset.offerScroll;
    const isHorizontal = window.matchMedia("(max-width: 767.98px)").matches;
    const hasOverflow = isHorizontal
      ? offerList.scrollWidth > offerList.clientWidth + 1
      : offerList.scrollHeight > offerList.clientHeight + 1;
    if (!hasOverflow) {
      if (direction === "down") {
        offerList.append(firstOffer);
      } else {
        offerList.prepend(lastOffer);
      }
      return;
    }

    const styles = window.getComputedStyle(offerList);
    const gap = parseFloat(isHorizontal ? styles.columnGap : styles.rowGap) || 0;
    const offerBounds = firstOffer.getBoundingClientRect();
    offerList.scrollBy({
      left: isHorizontal ? (direction === "down" ? offerBounds.width + gap : -offerBounds.width - gap) : 0,
      top: isHorizontal ? 0 : (direction === "down" ? offerBounds.height + gap : -offerBounds.height - gap),
      behavior: "smooth"
    });
  });
});

heroCarousel.addEventListener("slid.bs.carousel", function (event) {
  const slides = heroCarousel.querySelectorAll(".carousel-item");
  const nextSlideIndex = (event.to + 1) % slides.length;
  const nextSlide = slides[nextSlideIndex];
  const nextPreview = heroCarousel.querySelector(".hero-deck-card--next");
  nextPreview.querySelector("img").src = nextSlide.querySelector(".hero-image").getAttribute("src");
  nextPreview.dataset.bsSlideTo = nextSlideIndex;
  nextPreview.setAttribute("aria-label", "Show next look, slide " + (nextSlideIndex + 1));

  document.querySelectorAll(".hero-indicators button").forEach(function (indicator, index) {
    const isActive = index === event.to;
    indicator.classList.toggle("active", isActive);
    indicator.toggleAttribute("aria-current", isActive);
  });
});
}

const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");

const bestSellingCarousel = document.querySelector("#bestSellingCarousel");
if (bestSellingCarousel) {
  bootstrap.Carousel.getOrCreateInstance(bestSellingCarousel).cycle();
}
const bestSellingHeading = document.querySelector(".best-selling-section .section-heading");
if (bestSellingHeading && !prefersReducedMotion.matches && "IntersectionObserver" in window) {
  bestSellingHeading.classList.add("from-bottom");
  const headingObserver = new IntersectionObserver(function (entries, observer) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add("is-visible");
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15 });
  headingObserver.observe(bestSellingHeading);
}

const everydayCarousel = document.querySelector(".everyday-carousel");
if (everydayCarousel) {
  const everydayTrack = document.querySelector(".everyday-track");
  const everydayToggle = document.querySelector(".everyday-toggle");
let everydayTimer;
let everydayTransitionDirection = 0;
let everydayPaused = prefersReducedMotion.matches;
let everydayHovered = false;
let everydayFocused = false;

function updateEverydaySlides() {
  const viewport = everydayCarousel.getBoundingClientRect();
  Array.from(everydayTrack.children).forEach(function (slide) {
    const bounds = slide.getBoundingClientRect();
    const isVisible = bounds.left < viewport.right && bounds.right > viewport.left;
    slide.inert = !isVisible;
    slide.setAttribute("aria-hidden", String(!isVisible));
  });
}

function updateEverydayTimer() {
  window.clearInterval(everydayTimer);
  if (!everydayPaused && !everydayHovered && !everydayFocused && !prefersReducedMotion.matches) {
    everydayTimer = window.setInterval(function () {
      moveEverydaySlide(1);
    }, 3500);
  }
  if (everydayToggle) {
    everydayToggle.textContent = everydayPaused ? "Play" : "Pause";
    everydayToggle.setAttribute("aria-label", everydayPaused ? "Play slideshow" : "Pause slideshow");
  }
}

function moveEverydaySlide(direction) {
  if (everydayTransitionDirection || everydayTrack.children.length < 2) {
    return;
  }

  const firstSlide = everydayTrack.firstElementChild;
  const lastSlide = everydayTrack.lastElementChild;
  const gap = parseFloat(window.getComputedStyle(everydayTrack).columnGap) || 0;
  const distance = firstSlide.getBoundingClientRect().width + gap;
  everydayTransitionDirection = direction;

  if (direction > 0) {
    everydayTrack.style.transition = "transform 600ms cubic-bezier(.22, .61, .36, 1)";
    everydayTrack.style.transform = "translateX(-" + distance + "px)";
    return;
  }

  everydayTrack.style.transition = "none";
  everydayTrack.insertBefore(lastSlide, firstSlide);
  everydayTrack.style.transform = "translateX(-" + distance + "px)";
  window.requestAnimationFrame(function () {
    window.requestAnimationFrame(function () {
      everydayTrack.style.transition = "transform 600ms cubic-bezier(.22, .61, .36, 1)";
      everydayTrack.style.transform = "translateX(0)";
    });
  });
}

enableDragSlider(everydayCarousel, function (direction) {
  if (everydayTransitionDirection || everydayTrack.children.length < 2) {
    return false;
  }
  moveEverydaySlide(direction);
  return true;
});

everydayTrack.addEventListener("transitionend", function (event) {
  if (event.target !== everydayTrack || event.propertyName !== "transform" || !everydayTransitionDirection) {
    return;
  }
  if (everydayTransitionDirection > 0) {
    everydayTrack.appendChild(everydayTrack.firstElementChild);
  }
  everydayTrack.style.transition = "none";
  everydayTrack.style.transform = "translateX(0)";
  everydayTransitionDirection = 0;
  updateEverydaySlides();
});

everydayCarousel.querySelectorAll(".everyday-control[data-direction]").forEach(function (button) {
  button.addEventListener("click", function () {
    moveEverydaySlide(button.dataset.direction === "next" ? 1 : -1);
  });
});

if (everydayToggle) {
  everydayToggle.addEventListener("click", function () {
    everydayPaused = !everydayPaused;
    updateEverydayTimer();
  });
}

everydayCarousel.addEventListener("pointerenter", function () {
  everydayHovered = true;
  updateEverydayTimer();
});
everydayCarousel.addEventListener("pointerleave", function () {
  everydayHovered = false;
  updateEverydayTimer();
});
everydayCarousel.addEventListener("focusin", function () {
  everydayFocused = true;
  updateEverydayTimer();
});
everydayCarousel.addEventListener("focusout", function (event) {
  if (!everydayCarousel.contains(event.relatedTarget)) {
    everydayFocused = false;
    updateEverydayTimer();
  }
});
window.addEventListener("resize", updateEverydaySlides);
updateEverydaySlides();
updateEverydayTimer();
}

const categoryCarousel = document.querySelector(".category-carousel");
if (categoryCarousel) {
const categoryTrack = categoryCarousel.querySelector(".category-track");
const categoryCards = Array.from(categoryTrack.children);
const categoryDotsContainer = categoryCarousel.querySelector(".category-dots");
const categoryDots = categoryCards.map(function (card, index) {
  const dot = document.createElement("button");
  dot.type = "button";
  dot.setAttribute("aria-label", "Show category " + (index + 1));
  categoryDotsContainer.appendChild(dot);
  return dot;
});
const categoryPrevButton = document.querySelector(".category-prev");
const categoryNextButton = document.querySelector(".category-next");
let categoryIndex = 0;
let categoryPosition = 0;
let categoryTimer;
let categoryHovered = false;
let categoryFocused = false;

function getCategoryMaxIndex() {
  const gridWidth = categoryCarousel.querySelector(".category-grid").clientWidth;
  const firstCard = categoryCards[0];
  const gap = parseFloat(window.getComputedStyle(categoryTrack).columnGap) || 0;
  const cardWidth = firstCard.getBoundingClientRect().width;
  const visibleCards = Math.max(1, Math.floor((gridWidth + gap + 1) / (cardWidth + gap)));
  return Math.max(0, categoryCards.length - visibleCards);
}

function updateCategoryDots() {
  const maxIndex = getCategoryMaxIndex();
  categoryCarousel.classList.toggle("is-static", maxIndex === 0);
  categoryPrevButton.disabled = categoryIndex === 0;
  categoryNextButton.disabled = categoryIndex >= maxIndex;
  categoryDots.forEach(function (dot, index) {
    const isActive = index === categoryIndex;
    dot.hidden = index > maxIndex;
    dot.classList.toggle("active", isActive);
    if (isActive) {
      dot.setAttribute("aria-current", "true");
    } else {
      dot.removeAttribute("aria-current");
    }
  });
}

function setCategoryPosition(position, animate) {
  categoryPosition = position;
  categoryTrack.style.transition = animate ? "transform 600ms cubic-bezier(.22, .61, .36, 1)" : "none";
  if (animate) {
    categoryTrack.getBoundingClientRect();
  }
  const firstCard = categoryCards[0];
  const gap = parseFloat(window.getComputedStyle(categoryTrack).columnGap) || 0;
  const distance = firstCard.getBoundingClientRect().width + gap;
  categoryTrack.style.transform = "translateX(-" + (distance * categoryPosition) + "px)";
}

function updateCategorySlider(nextIndex) {
  categoryIndex = Math.max(0, Math.min(nextIndex, getCategoryMaxIndex()));
  setCategoryPosition(categoryIndex, true);
  updateCategoryDots();
  updateCategoryTimer();
}

let categoryWheelLocked = false;
categoryCarousel.addEventListener("wheel", function (event) {
  const horizontalDelta = event.shiftKey
    ? event.deltaY
    : Math.abs(event.deltaX) > Math.abs(event.deltaY)
      ? event.deltaX
      : 0;
  const nextIndex = Math.max(0, Math.min(
    categoryIndex + (horizontalDelta > 0 ? 1 : -1),
    getCategoryMaxIndex()
  ));
  if (!horizontalDelta || nextIndex === categoryIndex) {
    return;
  }
  event.preventDefault();
  if (categoryWheelLocked) {
    return;
  }
  categoryWheelLocked = true;
  window.setTimeout(function () {
    categoryWheelLocked = false;
  }, 450);
  updateCategorySlider(nextIndex);
}, { passive: false });

function updateCategoryTimer() {
  window.clearInterval(categoryTimer);
  if (categoryIndex < getCategoryMaxIndex() && !prefersReducedMotion.matches && !categoryHovered && !categoryFocused && !document.hidden) {
    categoryTimer = window.setInterval(function () {
      updateCategorySlider(categoryIndex + 1);
    }, 4000);
  }
}

categoryPrevButton.addEventListener("click", function () {
  updateCategorySlider(categoryIndex - 1);
});

categoryNextButton.addEventListener("click", function () {
  updateCategorySlider(categoryIndex + 1);
});

enableDragSlider(categoryCarousel, function (direction) {
  if (direction > 0 && categoryIndex >= getCategoryMaxIndex()) {
    return false;
  }
  if (direction < 0 && categoryIndex <= 0) {
    return false;
  }
  updateCategorySlider(categoryIndex + direction);
  return true;
});

categoryDots.forEach(function (dot, index) {
  dot.addEventListener("click", function () {
    updateCategorySlider(index);
  });
});

categoryCarousel.addEventListener("pointerenter", function () {
  categoryHovered = true;
  updateCategoryTimer();
});
categoryCarousel.addEventListener("pointerleave", function () {
  categoryHovered = false;
  updateCategoryTimer();
});
categoryCarousel.addEventListener("focusin", function () {
  categoryFocused = true;
  updateCategoryTimer();
});
categoryCarousel.addEventListener("focusout", function (event) {
  if (!categoryCarousel.contains(event.relatedTarget)) {
    categoryFocused = false;
    updateCategoryTimer();
  }
});
document.addEventListener("visibilitychange", updateCategoryTimer);
prefersReducedMotion.addEventListener("change", updateCategoryTimer);

setCategoryPosition(categoryIndex, false);
updateCategoryDots();
window.addEventListener("resize", function () {
  categoryIndex = Math.min(categoryIndex, getCategoryMaxIndex());
  setCategoryPosition(categoryIndex, false);
  updateCategoryDots();
  updateCategoryTimer();
});
updateCategoryTimer();
}

const shopCategoryCarousel = document.querySelector(".shop-category-carousel");
if (shopCategoryCarousel) {
const shopCategoryOriginalSlides = Array.from(shopCategoryCarousel.querySelectorAll(".shop-category-slide"));
const shopCategoryStage = shopCategoryCarousel.querySelector(".shop-category-stage");
const shopCategorySlideCount = shopCategoryOriginalSlides.length;
shopCategoryOriginalSlides.forEach(function (slide) {
  const clone = slide.cloneNode(true);
  clone.setAttribute("aria-hidden", "true");
  shopCategoryStage.appendChild(clone);
});
shopCategoryOriginalSlides.slice().reverse().forEach(function (slide) {
  const clone = slide.cloneNode(true);
  clone.setAttribute("aria-hidden", "true");
  shopCategoryStage.insertBefore(clone, shopCategoryStage.firstChild);
});
const shopCategorySlides = Array.from(shopCategoryStage.querySelectorAll(".shop-category-slide"));
const shopCategoryDotsContainer = shopCategoryCarousel.querySelector(".shop-category-dots");
const shopCategoryDots = Array.from({ length: shopCategorySlideCount }, function (_, index) {
  const dot = document.createElement("button");
  dot.type = "button";
  dot.setAttribute("aria-label", "Show " + shopCategoryOriginalSlides[index].querySelector("span").textContent);
  shopCategoryDotsContainer.appendChild(dot);
  return dot;
});
const shopCategoryPrevButton = shopCategoryCarousel.querySelector(".shop-category-prev");
const shopCategoryNextButton = shopCategoryCarousel.querySelector(".shop-category-next");
let shopCategoryIndex = shopCategorySlideCount + 1;
let shopCategoryTimer;
let shopCategoryHovered = false;
let shopCategoryFocused = false;

function getShopCategoryLogicalIndex() {
  return (shopCategoryIndex - shopCategorySlideCount + shopCategorySlideCount) % shopCategorySlideCount;
}

function updateShopCategoryDots() {
  const logicalIndex = getShopCategoryLogicalIndex();
  shopCategoryDots.forEach(function (dot, index) {
    const isActive = index === logicalIndex;
    dot.classList.toggle("active", isActive);
    if (isActive) {
      dot.setAttribute("aria-current", "true");
    } else {
      dot.removeAttribute("aria-current");
    }
  });
}

function applyShopCategoryPositions() {
  const slideCount = shopCategorySlides.length;
  shopCategorySlides.forEach(function (slide, index) {
    const distance = (index - shopCategoryIndex + slideCount) % slideCount;
    const relativePosition = distance > slideCount / 2 ? distance - slideCount : distance;
    slide.classList.remove(
      "is-left",
      "is-center",
      "is-right",
      "is-far-left",
      "is-far-right",
      "is-hidden-left",
      "is-hidden-right"
    );
    if (relativePosition === 0) {
      slide.classList.add("is-center");
      slide.setAttribute("aria-hidden", "false");
    } else if (relativePosition === -1) {
      slide.classList.add("is-left");
      slide.setAttribute("aria-hidden", "true");
    } else if (relativePosition === 1) {
      slide.classList.add("is-right");
      slide.setAttribute("aria-hidden", "true");
    } else {
      slide.classList.add(
        relativePosition < 0
          ? (relativePosition === -2 ? "is-far-left" : "is-hidden-left")
          : (relativePosition === 2 ? "is-far-right" : "is-hidden-right")
      );
      slide.setAttribute("aria-hidden", "true");
    }
  });
}

function normalizeShopCategoryIndex() {
  const firstOriginalIndex = shopCategorySlideCount;
  const lastOriginalIndex = firstOriginalIndex + shopCategorySlideCount - 1;
  if (shopCategoryIndex >= firstOriginalIndex && shopCategoryIndex <= lastOriginalIndex) {
    return;
  }
  const logicalIndex = getShopCategoryLogicalIndex();
  shopCategorySlides.forEach(function (slide) {
    slide.classList.add("is-repositioning");
  });
  shopCategoryIndex = firstOriginalIndex + logicalIndex;
  applyShopCategoryPositions();
  shopCategoryStage.getBoundingClientRect();
  window.requestAnimationFrame(function () {
    shopCategorySlides.forEach(function (slide) {
      slide.classList.remove("is-repositioning");
    });
  });
}

function updateShopCategorySlider(nextIndex) {
  shopCategoryIndex = nextIndex;
  applyShopCategoryPositions();
  if (prefersReducedMotion.matches) {
    normalizeShopCategoryIndex();
  }
  updateShopCategoryDots();
  updateShopCategoryTimer();
}

shopCategoryStage.addEventListener("transitionend", function (event) {
  if (event.propertyName === "transform" && event.target.classList.contains("shop-category-slide")) {
    normalizeShopCategoryIndex();
  }
});

function updateShopCategoryTimer() {
  window.clearInterval(shopCategoryTimer);
  if (shopCategorySlideCount > 1 && !prefersReducedMotion.matches && !shopCategoryHovered && !shopCategoryFocused && !document.hidden) {
    shopCategoryTimer = window.setInterval(function () {
      updateShopCategorySlider(shopCategoryIndex + 1);
    }, 4000);
  }
}

shopCategoryPrevButton.addEventListener("click", function () {
  updateShopCategorySlider(shopCategoryIndex - 1);
});
shopCategoryNextButton.addEventListener("click", function () {
  updateShopCategorySlider(shopCategoryIndex + 1);
});
enableDragSlider(shopCategoryCarousel, function (direction) {
  if (shopCategorySlideCount < 2) {
    return false;
  }
  updateShopCategorySlider(shopCategoryIndex + direction);
  return true;
});
shopCategoryDots.forEach(function (dot, index) {
  dot.addEventListener("click", function () {
    updateShopCategorySlider(shopCategorySlideCount + index);
  });
});
shopCategoryCarousel.addEventListener("pointerenter", function () {
  shopCategoryHovered = true;
  updateShopCategoryTimer();
});
shopCategoryCarousel.addEventListener("pointerleave", function () {
  shopCategoryHovered = false;
  updateShopCategoryTimer();
});
shopCategoryCarousel.addEventListener("focusin", function () {
  shopCategoryFocused = true;
  updateShopCategoryTimer();
});
shopCategoryCarousel.addEventListener("focusout", function (event) {
  if (!shopCategoryCarousel.contains(event.relatedTarget)) {
    shopCategoryFocused = false;
    updateShopCategoryTimer();
  }
});
document.addEventListener("visibilitychange", function () {
  if (!document.hidden) {
    shopCategorySlides.forEach(function (slide) {
      slide.classList.remove("is-repositioning");
    });
  }
  updateShopCategoryTimer();
});
prefersReducedMotion.addEventListener("change", updateShopCategoryTimer);
applyShopCategoryPositions();
updateShopCategoryDots();
updateShopCategoryTimer();
}

const collectionWindow = document.querySelector(".collection-window");
if (collectionWindow) {
const collectionTrack = collectionWindow.querySelector(".collection-track");
const collectionSlides = Array.from(collectionTrack.querySelectorAll("a"));
const collectionStep = function () {
  const styles = window.getComputedStyle(collectionTrack);
  return collectionSlides[0].getBoundingClientRect().width + parseFloat(styles.columnGap);
};
const moveCollectionSlider = function (direction) {
  const maxScroll = collectionTrack.scrollWidth - collectionTrack.clientWidth;
  const nextScroll = direction > 0
    ? (collectionTrack.scrollLeft >= maxScroll - 1 ? 0 : Math.min(maxScroll, collectionTrack.scrollLeft + collectionStep()))
    : (collectionTrack.scrollLeft <= 1 ? maxScroll : Math.max(0, collectionTrack.scrollLeft - collectionStep()));
  collectionTrack.scrollTo({
    left: nextScroll,
    behavior: prefersReducedMotion.matches ? "auto" : "smooth"
  });
  return maxScroll > 0;
};
enableDragSlider(collectionWindow, function (direction) {
  return moveCollectionSlider(direction);
});
document.querySelector(".collection-prev").addEventListener("click", function () {
  moveCollectionSlider(-1);
});
document.querySelector(".collection-next").addEventListener("click", function () {
  moveCollectionSlider(1);
});

const collectionSection = collectionWindow.closest(".best-collections");
let collectionTimer;
let collectionFocused = false;
function updateCollectionTimer() {
  window.clearInterval(collectionTimer);
  if (prefersReducedMotion.matches || document.hidden || collectionFocused) {
    return;
  }
  collectionTimer = window.setInterval(function () {
    moveCollectionSlider(1);
  }, 4500);
}
collectionSection.addEventListener("focusin", function () {
  collectionFocused = true;
  updateCollectionTimer();
});
collectionSection.addEventListener("focusout", function (event) {
  collectionFocused = collectionSection.contains(event.relatedTarget);
  updateCollectionTimer();
});
document.addEventListener("visibilitychange", updateCollectionTimer);
prefersReducedMotion.addEventListener("change", updateCollectionTimer);
updateCollectionTimer();
}

document.querySelectorAll(".wish-button").forEach(function (button) {
  button.addEventListener("click", function () {
    const saved = button.classList.toggle("is-saved");
    button.textContent = saved ? "♥" : "♡";
    button.setAttribute("aria-pressed", saved);
  });
});

document.querySelectorAll(".testimonial-arrows button").forEach(function (button) {
  button.addEventListener("click", function () {
    const quote = document.querySelector(".quote-card p");
    const author = document.querySelector(".quote-card strong");
    const isNext = button.getAttribute("aria-label") === "Next review";
    quote.textContent = isNext
      ? "“The colours are gorgeous and the outfit feels so comfortable. I received so many compliments at the celebration!”"
      : "“The outfit was even more beautiful in person. The fit, the fabric and all the little details felt so special. I can't wait to wear it again!”";
    author.textContent = isNext ? "Ananya R." : "Priya M.";
  });
});

const newsletterForm = document.querySelector("#newsletterForm");
if (newsletterForm) {
  newsletterForm.addEventListener("submit", function (event) {
    event.preventDefault();
    const msg = document.querySelector(".newsletter-message");
    if (msg) msg.textContent = "Thank you for joining us!";
    event.currentTarget.reset();
  });
}

const instagramCarousel = document.querySelector(".instagram-carousel");
if (instagramCarousel) {
const instagramTrack = instagramCarousel.querySelector(".instagram-track");
const instagramCards = Array.from(instagramTrack.children);
const instagramDotsContainer = document.querySelector(".instagram-carousel-dots");
const instagramDots = instagramCards.map(function (_, index) {
  const dot = document.createElement("button");
  dot.type = "button";
  dot.setAttribute("aria-label", "Show Instagram slide " + (index + 1));
  instagramDotsContainer.appendChild(dot);
  dot.addEventListener("click", function () {
    moveInstagramSlider(index);
    updateInstagramTimer();
  });
  return dot;
});
instagramCards.forEach(function (card) {
  const clone = card.cloneNode(true);
  clone.setAttribute("aria-hidden", "true");
  clone.inert = true;
  instagramTrack.appendChild(clone);
});

let instagramIndex = 0;
let instagramTimer;
let instagramHovered = false;
let instagramFocused = false;

function updateInstagramDots() {
  instagramDots.forEach(function (dot, index) {
    const isActive = index === instagramIndex % instagramCards.length;
    if (isActive) {
      dot.setAttribute("aria-current", "true");
    } else {
      dot.removeAttribute("aria-current");
    }
  });
}

function instagramStep() {
  const styles = window.getComputedStyle(instagramTrack);
  return instagramCards[0].getBoundingClientRect().width + (parseFloat(styles.columnGap) || 0);
}

function moveInstagramSlider(nextIndex, animate) {
  instagramIndex = nextIndex;
  instagramTrack.style.transition = animate === false || prefersReducedMotion.matches
    ? "none"
    : "transform 600ms cubic-bezier(.22, .61, .36, 1)";
  instagramTrack.style.transform = "translateX(-" + (instagramStep() * instagramIndex) + "px)";
  updateInstagramDots();
}

enableDragSlider(instagramCarousel, function (direction) {
  moveInstagramSlider(instagramIndex + direction);
  updateInstagramTimer();
  return instagramCards.length > 1;
});

function updateInstagramTimer() {
  window.clearInterval(instagramTimer);
  if (!prefersReducedMotion.matches && !instagramHovered && !instagramFocused && !document.hidden) {
    instagramTimer = window.setInterval(function () {
      moveInstagramSlider(instagramIndex + 1);
    }, 3500);
  }
}

instagramTrack.addEventListener("transitionend", function (event) {
  if (event.propertyName === "transform" && instagramIndex === instagramCards.length) {
    moveInstagramSlider(0, false);
  }
});
instagramCarousel.addEventListener("pointerenter", function () {
  instagramHovered = true;
  updateInstagramTimer();
});
instagramCarousel.addEventListener("pointerleave", function () {
  instagramHovered = false;
  updateInstagramTimer();
});
instagramCarousel.addEventListener("focusin", function () {
  instagramFocused = true;
  updateInstagramTimer();
});
instagramCarousel.addEventListener("focusout", function (event) {
  if (!instagramCarousel.contains(event.relatedTarget)) {
    instagramFocused = false;
    updateInstagramTimer();
  }
});
document.addEventListener("visibilitychange", updateInstagramTimer);
prefersReducedMotion.addEventListener("change", updateInstagramTimer);
window.addEventListener("resize", function () {
  moveInstagramSlider(instagramIndex, false);
});
moveInstagramSlider(0, false);
updateInstagramTimer();
}

const customerFeedback = document.querySelector(".customer-feedback-slider");
if (customerFeedback) {
let customerFeedbackCards = Array.from(customerFeedback.querySelectorAll(".customer-feedback-card"));
const customerFeedbackOriginalCards = customerFeedbackCards.slice();
let customerFeedbackTimer;
let customerFeedbackHovered = false;
let customerFeedbackFocused = false;
const customerFeedbackDots = customerFeedback.querySelector(".customer-feedback-dots");
const customerFeedbackDotButtons = customerFeedbackOriginalCards.map(function (card, index) {
  const dot = document.createElement("button");
  dot.type = "button";
  dot.className = "customer-feedback-dot";
  dot.setAttribute("aria-label", "Show customer review " + (index + 1));
  dot.addEventListener("click", function () {
    showCustomerFeedback(0, customerFeedbackCards.indexOf(card));
    updateCustomerFeedbackTimer();
  });
  customerFeedbackDots.append(dot);
  return dot;
});

function setCustomerFeedbackCardPositions() {
  const positions = ["is-front", "is-behind-one", "is-behind-two"];
  customerFeedbackCards.forEach(function (card, index) {
    positions.forEach(function (position) {
      card.classList.remove(position);
    });
    card.classList.add(positions[index]);
    card.toggleAttribute("aria-hidden", index !== 0);
    if (index === 0) {
      card.setAttribute("aria-live", "polite");
      card.setAttribute("aria-atomic", "true");
    } else {
      card.removeAttribute("aria-live");
      card.removeAttribute("aria-atomic");
    }
  });
  customerFeedbackDotButtons.forEach(function (dot, index) {
    const isActive = customerFeedbackOriginalCards[index] === customerFeedbackCards[0];
    dot.classList.toggle("is-active", isActive);
    dot.setAttribute("aria-pressed", String(isActive));
  });
}

function showCustomerFeedback(direction, targetIndex) {
  if (typeof targetIndex === "number") {
    const selectedCard = customerFeedbackCards[targetIndex];
    customerFeedbackCards = [selectedCard].concat(customerFeedbackCards.filter(function (card) {
      return card !== selectedCard;
    }));
  } else if (direction > 0) {
    customerFeedbackCards.push(customerFeedbackCards.shift());
  } else {
    customerFeedbackCards.unshift(customerFeedbackCards.pop());
  }
  setCustomerFeedbackCardPositions();
}

customerFeedbackOriginalCards.forEach(function (card) {
  card.addEventListener("click", function () {
    const targetIndex = customerFeedbackCards.indexOf(card);
    if (targetIndex > 0) {
      showCustomerFeedback(0, targetIndex);
      updateCustomerFeedbackTimer();
    }
  });
});

enableDragSlider(customerFeedback, function (direction) {
  showCustomerFeedback(direction);
  updateCustomerFeedbackTimer();
  return customerFeedbackCards.length > 1;
});

function updateCustomerFeedbackTimer() {
  window.clearInterval(customerFeedbackTimer);
  if (!prefersReducedMotion.matches && !customerFeedbackHovered && !customerFeedbackFocused && !document.hidden) {
    customerFeedbackTimer = window.setInterval(function () {
      showCustomerFeedback(1);
    }, 3500);
  }
}

setCustomerFeedbackCardPositions();
customerFeedback.addEventListener("pointerenter", function () {
  customerFeedbackHovered = true;
  updateCustomerFeedbackTimer();
});
customerFeedback.addEventListener("pointerleave", function () {
  customerFeedbackHovered = false;
  updateCustomerFeedbackTimer();
});
customerFeedback.addEventListener("focusin", function () {
  customerFeedbackFocused = true;
  updateCustomerFeedbackTimer();
});
customerFeedback.addEventListener("focusout", function (event) {
  if (!customerFeedback.contains(event.relatedTarget)) {
    customerFeedbackFocused = false;
    updateCustomerFeedbackTimer();
  }
});
document.addEventListener("visibilitychange", updateCustomerFeedbackTimer);
prefersReducedMotion.addEventListener("change", updateCustomerFeedbackTimer);
updateCustomerFeedbackTimer();
}

const craftGallery = document.querySelector(".craft-process-gallery");
if (craftGallery) {
  const craftGalleryImages = [
    ["assets/img/create-right-1.jpg", "Artisan carefully stitching an embroidered garment"],
    ["assets/img/create-right-3.jpg", "Artisan hand-embroidering a colorful textile"],
    ["assets/img/create-5.jpg", "Artisans working together on a garment"],
    ["assets/img/create-4.jpg", "Artisans checking a garment in the workshop"],
    ["assets/img/create-3.jpg", "Artisan block-printing a textile"],
    ["assets/img/create-2.jpg", "Fabric designs selected for a new collection"],
    ["assets/img/create-1.jpg", "Artisans carefully preparing garments for dispatch"]
  ];
  const craftGalleryTrack = craftGallery.querySelector(".craft-process-gallery-track");
  const craftGalleryViewport = craftGallery.querySelector(".craft-process-gallery-images");
  const craftImageElements = [];
  const craftGalleryLength = craftGalleryImages.length;
  const craftPrevious = craftGallery.querySelector(".craft-process-gallery-prev");
  const craftNext = craftGallery.querySelector(".craft-process-gallery-next");
  let craftGalleryIndex = 2;
  let craftGalleryTimer;
  let craftGalleryHovered = false;
  let craftGalleryFocused = false;

  while (craftGalleryTrack.firstChild) {
    craftGalleryTrack.removeChild(craftGalleryTrack.firstChild);
  }

  function getCraftImage(index) {
    return craftGalleryImages[(index - 2 + craftGalleryLength) % craftGalleryLength];
  }

  function updateCraftGalleryAccessibility() {
    craftImageElements.forEach(function (image, index) {
      const visibleIndex = index === craftGalleryIndex || index === craftGalleryIndex + 1;
      image.setAttribute("aria-hidden", String(!visibleIndex));
      image.alt = visibleIndex ? getCraftImage(index)[1] : "";
    });
  }

  function positionCraftGallery(animate) {
    const gap = parseFloat(window.getComputedStyle(craftGalleryTrack).columnGap) || 0;
    const slideStep = (craftGalleryViewport.clientWidth + gap) / 2;
    craftGalleryTrack.style.transition = animate ? "" : "none";
    craftGalleryTrack.style.transform = "translateX(-" + (slideStep * craftGalleryIndex) + "px)";
  }

  for (let index = 0; index < craftGalleryLength + 4; index += 1) {
    const image = document.createElement("img");
    const imageData = getCraftImage(index);
    image.className = "craft-process-gallery-image";
    image.src = imageData[0];
    image.alt = "";
    image.setAttribute("aria-hidden", "true");
    craftGalleryTrack.appendChild(image);
    craftImageElements.push(image);
  }

  function showCraftGallery(direction) {
    craftGalleryIndex += direction;
    positionCraftGallery(true);
    updateCraftGalleryAccessibility();
  }

  enableDragSlider(craftGallery, function (direction) {
    showCraftGallery(direction);
    updateCraftGalleryTimer();
    return craftGalleryLength > 1;
  });

  craftGalleryTrack.addEventListener("transitionend", function (event) {
    if (event.propertyName !== "transform") {
      return;
    }

    if (craftGalleryIndex >= craftGalleryLength + 2) {
      craftGalleryIndex = 2;
    } else if (craftGalleryIndex <= 0) {
      craftGalleryIndex = craftGalleryLength;
    } else {
      return;
    }

    positionCraftGallery(false);
    updateCraftGalleryAccessibility();
    window.requestAnimationFrame(function () {
      window.requestAnimationFrame(function () {
        craftGalleryTrack.style.transition = "";
      });
    });
  });

  function updateCraftGalleryTimer() {
    window.clearInterval(craftGalleryTimer);
    if (!prefersReducedMotion.matches && !craftGalleryHovered && !craftGalleryFocused && !document.hidden) {
      craftGalleryTimer = window.setInterval(function () {
        showCraftGallery(1);
      }, 4500);
    }
  }

  craftPrevious.addEventListener("click", function () {
    showCraftGallery(-1);
    updateCraftGalleryTimer();
  });
  craftNext.addEventListener("click", function () {
    showCraftGallery(1);
    updateCraftGalleryTimer();
  });
  updateCraftGalleryAccessibility();
  positionCraftGallery(false);
  window.requestAnimationFrame(function () {
    window.requestAnimationFrame(function () {
      craftGalleryTrack.style.transition = "";
    });
  });
  window.addEventListener("resize", function () {
    positionCraftGallery(false);
    window.requestAnimationFrame(function () {
      window.requestAnimationFrame(function () {
        craftGalleryTrack.style.transition = "";
      });
    });
  });
  if ("ResizeObserver" in window) {
    const craftGalleryObserver = new ResizeObserver(function () {
      positionCraftGallery(false);
    });
    craftGalleryObserver.observe(craftGalleryViewport);
  }
  craftGallery.addEventListener("pointerenter", function () {
    craftGalleryHovered = true;
    updateCraftGalleryTimer();
  });
  craftGallery.addEventListener("pointerleave", function () {
    craftGalleryHovered = false;
    updateCraftGalleryTimer();
  });
  craftGallery.addEventListener("focusin", function () {
    craftGalleryFocused = true;
    updateCraftGalleryTimer();
  });
  craftGallery.addEventListener("focusout", function (event) {
    if (!craftGallery.contains(event.relatedTarget)) {
      craftGalleryFocused = false;
      updateCraftGalleryTimer();
    }
  });
  document.addEventListener("visibilitychange", updateCraftGalleryTimer);
  prefersReducedMotion.addEventListener("change", updateCraftGalleryTimer);
  updateCraftGalleryTimer();
}

const faqQuestions = Array.from(document.querySelectorAll(".faq-question"));
if (faqQuestions.length > 0) {
  const faqAnswerTitle = document.querySelector(".faq-note-title");
  const faqAnswerCopy = document.querySelector(".faq-note-copy");

  function showFaqAnswer(question) {
    faqQuestions.forEach(function (item) {
      const isActive = item === question;
      item.classList.toggle("is-active", isActive);
      item.setAttribute("aria-pressed", String(isActive));
    });
    if (faqAnswerTitle) faqAnswerTitle.textContent = question.dataset.answerTitle;
    if (faqAnswerCopy) faqAnswerCopy.textContent = question.dataset.answer;
  }

faqQuestions.forEach(function (question) {
  question.addEventListener("pointerenter", function () {
    showFaqAnswer(question);
  });
  question.addEventListener("focus", function () {
    showFaqAnswer(question);
  });
  question.addEventListener("click", function () {
    showFaqAnswer(question);
  });
});
}


(function initializeCartPage() {
const cartItems = document.querySelector(".cart-items");
if (!cartItems) {
  return;
}

const cartPage = cartItems.closest(".cart-page");
if (!cartPage || cartPage.hasAttribute("data-dynamic-cart")) {
  return;
}
const cartEmpty = cartItems.querySelector(".cart-empty");
const cartViews = Array.from(cartPage.querySelectorAll("[data-cart-view]"));
const cartSteps = Array.from(cartPage.querySelectorAll("[data-cart-step]"));
const cartTitle = cartPage.querySelector("[data-cart-title]");
const checkoutItems = cartPage.querySelector("[data-checkout-items]");
const completeProducts = cartPage.querySelector("[data-complete-products]");
const checkoutError = cartPage.querySelector("[data-checkout-error]");
let currentStep = 1;

function updateCartEmptyState() {
  const isEmpty = cartItems.querySelectorAll(".cart-item").length === 0;
  cartEmpty.classList.toggle("is-visible", isEmpty);
  cartPage.querySelector(".cart-checkout").disabled = isEmpty;
}

function renderCheckoutItems() {
  checkoutItems.replaceChildren();
  cartItems.querySelectorAll(".cart-item").forEach(function (item) {
    const image = item.querySelector(".cart-item-image");
    const name = item.querySelector(".cart-item-name").textContent;
    const row = document.createElement("div");
    row.className = "checkout-summary-item";
    row.innerHTML = '<span class="checkout-summary-image-wrap"><img class="checkout-summary-image" src="' +
      image.getAttribute("src") + '" alt=""><span class="checkout-summary-quantity">1</span></span>' +
      '<span class="checkout-summary-name"></span><strong class="checkout-summary-price">&#8377; 2600</strong>';
    row.querySelector(".checkout-summary-name").textContent = name;
    checkoutItems.appendChild(row);
  });
}

function updateCartStep(step) {
  currentStep = step;
  cartPage.dataset.checkoutStep = String(step);
  cartViews.forEach(function (view) {
    view.hidden = Number(view.dataset.cartView === "cart" ? 1 : view.dataset.cartView === "checkout" ? 2 : 3) !== step;
  });
  cartSteps.forEach(function (item) {
    const itemStep = Number(item.dataset.cartStep);
    const number = item.querySelector(".cart-step-number");
    item.classList.toggle("is-active", itemStep === step);
    item.classList.toggle("is-complete", itemStep < step);
    number.innerHTML = itemStep < step
      ? '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12 4 4L19 6"></path></svg>'
      : String(itemStep);
    if (itemStep === step) {
      item.setAttribute("aria-current", "step");
    } else {
      item.removeAttribute("aria-current");
    }
  });
  cartTitle.textContent = step === 1 ? "Cart" : step === 2 ? "Checkout" : "Complete !";
  if (step === 2) {
    renderCheckoutItems();
  }
}

cartItems.addEventListener("click", function (event) {
  const button = event.target.closest("button");
  if (!button) return;

  if (button.classList.contains("cart-remove")) {
    button.closest(".cart-item").remove();
    updateCartEmptyState();
    return;
  }

  if (button.hasAttribute("data-quantity-change")) {
    const output = button.parentElement.querySelector("output");
    const quantity = Math.max(1, Number(output.value || output.textContent) + Number(button.dataset.quantityChange));
    output.value = String(quantity);
    output.textContent = String(quantity);
  }
});

cartPage.addEventListener("click", function (event) {
  const nextButton = event.target.closest("[data-cart-next]");
  if (nextButton) {
    const nextStep = Number(nextButton.dataset.cartNext);
    if (nextStep <= currentStep || cartItems.querySelector(".cart-item")) {
      updateCartStep(nextStep);
    }
    return;
  }

  const stepButton = event.target.closest("[data-cart-step]");
  if (stepButton && Number(stepButton.dataset.cartStep) <= currentStep) {
    updateCartStep(Number(stepButton.dataset.cartStep));
  }
});

cartPage.querySelector(".cart-coupon-entry").addEventListener("submit", function (event) {
  event.preventDefault();
  this.nextElementSibling.textContent = "Coupon codes cannot be verified in this demo.";
});

const billingFields = cartPage.querySelector("[data-billing-fields]");
cartPage.querySelectorAll('input[name="billing-address"]').forEach(function (input) {
  input.addEventListener("change", function () {
    const isDifferentAddress = this.value === "different";
    billingFields.hidden = !isDifferentAddress;
    billingFields.querySelectorAll("input").forEach(function (field) {
      field.required = isDifferentAddress;
    });
  });
});

const cardFields = cartPage.querySelector(".card-fields");
cartPage.querySelectorAll('input[name="payment-method"]').forEach(function (input) {
  input.addEventListener("change", function () {
    const isCreditCard = this.value === "Credit Card";
    cartPage.querySelectorAll(".payment-option").forEach(function (option) {
      option.classList.toggle("is-selected", option.querySelector('input[name="payment-method"]').checked);
    });
    cardFields.hidden = !isCreditCard;
    cardFields.querySelectorAll("input").forEach(function (field) {
      field.required = isCreditCard;
    });
    checkoutError.hidden = true;
  });
});
cartPage.querySelector('input[name="payment-method"]:checked').dispatchEvent(new Event("change", { bubbles: true }));

cartPage.querySelector("[data-checkout-coupon]").addEventListener("submit", function (event) {
  event.preventDefault();
  if (!this.querySelector("input").value.trim()) return;
  this.hidden = true;
  const appliedCoupon = cartPage.querySelector("[data-checkout-coupon-applied]");
  appliedCoupon.hidden = false;
  appliedCoupon.querySelector(".checkout-coupon-label").innerHTML =
    '<svg class="checkout-coupon-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12V5a2 2 0 0 1 2-2h7l9 9-7 7z"></path><circle cx="8" cy="8" r="1"></circle></svg><span>S4KFASHION</span>';
});

cartPage.querySelector("[data-remove-coupon]").addEventListener("click", function () {
  cartPage.querySelector("[data-checkout-coupon-applied]").hidden = true;
  cartPage.querySelector("[data-checkout-coupon]").hidden = false;
});

cartPage.querySelector("[data-place-order]").addEventListener("click", function () {
  const paymentMethod = cartPage.querySelector('input[name="payment-method"]:checked').value;
  const requiredFields = Array.from(cartPage.querySelectorAll(".billing-fields input[required], .card-fields:not([hidden]) input[required]"));
  const invalidField = requiredFields.find(function (field) {
    return !field.value.trim();
  });
  if (invalidField) {
    checkoutError.hidden = false;
    invalidField.focus();
    return;
  }

  checkoutError.hidden = true;
  completeProducts.replaceChildren();
  cartItems.querySelectorAll(".cart-item").forEach(function (item) {
    const product = document.createElement("span");
    product.className = "complete-product";
    const productImage = document.createElement("img");
    productImage.src = item.querySelector(".cart-item-image").getAttribute("src");
    productImage.alt = "";
    const quantity = document.createElement("span");
    quantity.textContent = "1";
    product.append(productImage, quantity);
    completeProducts.appendChild(product);
  });
  cartPage.querySelector("[data-order-date]").textContent = new Intl.DateTimeFormat("en-US", {
    year: "numeric",
    month: "long",
    day: "2-digit"
  }).format(new Date());
  cartPage.querySelector("[data-order-payment]").textContent = paymentMethod;
  updateCartStep(3);
});

updateCartEmptyState();
updateCartStep(1);
})();

// Mobile tap toggle for Trending Collections and Shop Look options overlay
(function () {
  const interactiveCards = document.querySelectorAll(
    ".trending-collections-section .collections-item, .shop-look-card"
  );

  if (interactiveCards.length > 0) {
    interactiveCards.forEach(function (card) {
      card.addEventListener("click", function (event) {
        const isAction = event.target.closest(
          ".product-add-to-cart, .color-swatch, .shop-look-swatch, .size-options span, .shop-look-sizes span, .Wishlist, .shop-look-icons"
        );
        if (isAction) {
          return;
        }

        const wasActive = card.classList.contains("is-active");
        interactiveCards.forEach(function (c) {
          c.classList.remove("is-active");
        });

        if (!wasActive) {
          const link = event.target.closest("a");
          if (link && (link.getAttribute("href") === "#" || !link.getAttribute("href"))) {
            event.preventDefault();
          }
          card.classList.add("is-active");
        }
      });
    });

    document.addEventListener("click", function (event) {
      if (!event.target.closest(".trending-collections-section .collections-item, .shop-look-card")) {
        interactiveCards.forEach(function (card) {
          card.classList.remove("is-active");
        });
      }
    });

    // Swatches selection
    document.querySelectorAll(".color-swatches .color-swatch, .shop-look-swatches .shop-look-swatch").forEach(function (swatch) {
      swatch.addEventListener("click", function (e) {
        e.stopPropagation();
        const parent = swatch.parentElement;
        parent.querySelectorAll(".color-swatch, .shop-look-swatch").forEach(function (s) {
          s.classList.toggle("is-selected", s === swatch);
        });
      });
    });

    // Sizes selection
    document.querySelectorAll(".size-options span, .shop-look-sizes span").forEach(function (size) {
      size.addEventListener("click", function (e) {
        e.stopPropagation();
        const parent = size.parentElement;
        parent.querySelectorAll("span").forEach(function (s) {
          s.classList.toggle("is-selected", s === size);
        });
      });
    });

    // Add to cart navigate to details page
    document.querySelectorAll(".product-add-to-cart").forEach(function (btn) {
      btn.addEventListener("click", function (e) {
        e.stopPropagation();
        window.location.href = "product-details.html";
      });
    });
  }

  document.querySelectorAll(".add-to-cart").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.stopPropagation();
      window.location.href = "product-details.html";
    });
  });
})();


/* ==========================================================================
   AUTHENTICATION LOGIC (Login & Sign Up)
   Safely scoped so it only runs if .auth-card is present
   ========================================================================== */
(function initializeAuth() {
  function runAuth() {
    var authCard = document.querySelector(".auth-card");
    if (!authCard) return; // Exit immediately on non-auth pages to ensure zero impact on other pages!

    // Elements
    var tabButtons = document.querySelectorAll(".auth-tab-btn");
    var tabPanes = document.querySelectorAll(".auth-tab-pane");
    var cardTitle = document.getElementById("authCardTitle");
    var cardSubtitle = document.getElementById("authCardSubtitle");

    // Tab Switching Function
    function switchAuthTab(targetTab) {
      tabButtons.forEach(function (btn) {
        var isActive = btn.dataset.tab === targetTab;
        btn.classList.toggle("active", isActive);
        btn.setAttribute("aria-selected", String(isActive));
      });

      tabPanes.forEach(function (pane) {
        var isMatch = pane.id === "pane-" + targetTab;
        pane.classList.toggle("active", isMatch);
      });

      if (targetTab === "login") {
        if (cardTitle) cardTitle.textContent = "Welcome Back to S4K";
        if (cardSubtitle) cardSubtitle.textContent = "Please sign in to access your bag, orders & insider perks";
        document.title = "Sign In | S4K - Everyday Elegance";
      } else {
        if (cardTitle) cardTitle.textContent = "Create Your S4K Account";
        if (cardSubtitle) cardSubtitle.textContent = "Join S4K Insider for 20% off your first order & exclusive launches";
        document.title = "Create Account | S4K - Everyday Elegance";
      }
    }

    // Bind Tab Click
    tabButtons.forEach(function (btn) {
      btn.addEventListener("click", function () {
        var target = this.dataset.tab;
        switchAuthTab(target);
        if (history.replaceState) {
          history.replaceState(null, null, "#" + target);
        }
      });
    });

    // Switch Links (e.g., "Don't have an account? Sign Up", "Already have an account? Sign In")
    document.querySelectorAll("[data-switch-tab]").forEach(function (link) {
      link.addEventListener("click", function (e) {
        e.preventDefault();
        var target = this.dataset.switchTab;
        switchAuthTab(target);
        if (history.replaceState) {
          history.replaceState(null, null, "#" + target);
        }
        var card = document.querySelector(".auth-card");
        if (card) {
          card.scrollIntoView({ behavior: "smooth", block: "start" });
        }
      });
    });

    // Check URL Hash or Initial Page Mode
    var hash = window.location.hash.toLowerCase();
    var urlParams = new URLSearchParams(window.location.search);
    var modeParam = urlParams.get("mode");

    if (hash === "#signup" || modeParam === "signup" || document.body.dataset.defaultAuth === "signup") {
      switchAuthTab("signup");
    } else {
      switchAuthTab("login");
    }

    // Sign In Method Switcher (Password vs OTP)
    var methodButtons = document.querySelectorAll(".auth-method-btn");
    var loginPassMethod = document.getElementById("loginForm");
    var loginOtpMethod = document.getElementById("otpLoginForm");

    methodButtons.forEach(function (btn) {
      btn.addEventListener("click", function () {
        methodButtons.forEach(function (b) { b.classList.remove("active"); });
        this.classList.add("active");
        var method = this.dataset.method;

        if (method === "otp") {
          if (loginPassMethod) loginPassMethod.style.display = "none";
          if (loginOtpMethod) loginOtpMethod.style.display = "block";
        } else {
          if (loginPassMethod) loginPassMethod.style.display = "block";
          if (loginOtpMethod) loginOtpMethod.style.display = "none";
        }
      });
    });

    // Toggle Password Visibility
    document.querySelectorAll(".auth-toggle-pwd").forEach(function (btn) {
      btn.addEventListener("click", function () {
        var targetInput = document.getElementById(this.dataset.target);
        if (!targetInput) return;

        var isPassword = targetInput.type === "password";
        targetInput.type = isPassword ? "text" : "password";

        var icon = this.querySelector("i");
        if (icon) {
          if (isPassword) {
            icon.classList.remove("bi-eye");
            icon.classList.add("bi-eye-slash");
            this.setAttribute("aria-label", "Hide password");
          } else {
            icon.classList.remove("bi-eye-slash");
            icon.classList.add("bi-eye");
            this.setAttribute("aria-label", "Show password");
          }
        }
      });
    });

    // Password Strength Meter for Sign Up
    var signupPassword = document.getElementById("signupPassword");
    var strengthBars = document.querySelectorAll(".auth-strength-bar");
    var strengthStatus = document.getElementById("strengthStatus");
    var ruleLength = document.getElementById("ruleLength");
    var ruleNumber = document.getElementById("ruleNumber");
    var ruleUpper = document.getElementById("ruleUpper");
    var ruleSpecial = document.getElementById("ruleSpecial");

    if (signupPassword) {
      signupPassword.addEventListener("input", function () {
        var val = this.value;
        var score = 0;

        var hasMinLength = val.length >= 8;
        var hasNumber = /\d/.test(val);
        var hasUpper = /[A-Z]/.test(val);
        var hasSpecial = /[^A-Za-z0-9]/.test(val);

        if (ruleLength) updateRule(ruleLength, hasMinLength);
        if (ruleNumber) updateRule(ruleNumber, hasNumber);
        if (ruleUpper) updateRule(ruleUpper, hasUpper);
        if (ruleSpecial) updateRule(ruleSpecial, hasSpecial);

        if (hasMinLength) score++;
        if (hasNumber) score++;
        if (hasUpper) score++;
        if (hasSpecial) score++;

        // Reset bars
        strengthBars.forEach(function (bar) {
          bar.style.backgroundColor = "#e2d9cd";
        });

        if (!val) {
          if (strengthStatus) {
            strengthStatus.textContent = "Too weak";
            strengthStatus.style.color = "#cf222e";
          }
          return;
        }

        var colors = ["#cf222e", "#e36209", "#d4a72c", "#1e7e34"];
        var labels = ["Too weak", "Fair", "Good", "Strong"];

        for (var i = 0; i < score; i++) {
          if (strengthBars[i]) {
            strengthBars[i].style.backgroundColor = colors[score - 1];
          }
        }

        if (strengthStatus) {
          strengthStatus.textContent = labels[score - 1] || "Too weak";
          strengthStatus.style.color = colors[score - 1] || "#cf222e";
        }
      });
    }

    function updateRule(element, isValid) {
      element.classList.toggle("valid", isValid);
      var icon = element.querySelector("i");
      if (icon) {
        if (isValid) {
          icon.classList.remove("bi-circle");
          icon.classList.add("bi-check-circle-fill");
        } else {
          icon.classList.remove("bi-check-circle-fill");
          icon.classList.add("bi-circle");
        }
      }
    }

    // OTP Digits Auto-Focus & Navigation
    var otpInputs = document.querySelectorAll(".auth-otp-digit");
    otpInputs.forEach(function (input, index) {
      input.addEventListener("input", function () {
        if (this.value.length === 1 && index < otpInputs.length - 1) {
          otpInputs[index + 1].focus();
        }
      });

      input.addEventListener("keydown", function (e) {
        if (e.key === "Backspace" && !this.value && index > 0) {
          otpInputs[index - 1].focus();
        }
      });

      input.addEventListener("paste", function (e) {
        e.preventDefault();
        var pasteData = (e.clipboardData || window.clipboardData).getData("text").trim();
        if (/^\d+$/.test(pasteData)) {
          var digits = pasteData.split("");
          otpInputs.forEach(function (digitInput, i) {
            if (digits[i]) {
              digitInput.value = digits[i];
            }
          });
          var focusIdx = Math.min(digits.length, otpInputs.length - 1);
          otpInputs[focusIdx].focus();
        }
      });
    });

    // OTP Countdown & Resend
    var otpCountdown = document.getElementById("otpCountdown");
    var otpResendBtn = document.getElementById("otpResendBtn");
    var timeLeft = 30;
    var timerId = null;

    function startOtpTimer() {
      timeLeft = 30;
      if (otpResendBtn) otpResendBtn.disabled = true;
      if (otpCountdown) otpCountdown.style.display = "inline";

      clearInterval(timerId);
      timerId = setInterval(function () {
        timeLeft--;
        if (otpCountdown) otpCountdown.textContent = "(" + timeLeft + "s)";
        if (timeLeft <= 0) {
          clearInterval(timerId);
          if (otpCountdown) otpCountdown.style.display = "none";
          if (otpResendBtn) otpResendBtn.disabled = false;
        }
      }, 1000);
    }

    if (otpResendBtn) {
      otpResendBtn.addEventListener("click", function () {
        startOtpTimer();
        showFeedbackAlert("loginFeedback", "success", "A new OTP has been dispatched to your mobile number.");
      });
    }

    // Simulated OTP Request
    var requestOtpBtn = document.getElementById("requestOtpBtn");
    var otpEntrySection = document.getElementById("otpEntrySection");
    var otpPhoneInput = document.getElementById("otpPhoneInput");

    if (requestOtpBtn) {
      requestOtpBtn.addEventListener("click", function () {
        if (!otpPhoneInput || !otpPhoneInput.value || otpPhoneInput.value.length < 10) {
          showFeedbackAlert("loginFeedback", "error", "Please enter a valid 10-digit mobile number.");
          if (otpPhoneInput) otpPhoneInput.focus();
          return;
        }
        if (otpEntrySection) otpEntrySection.style.display = "block";
        requestOtpBtn.style.display = "none";
        startOtpTimer();
        showFeedbackAlert("loginFeedback", "success", "OTP sent successfully to +91 " + otpPhoneInput.value);
        if (otpInputs[0]) otpInputs[0].focus();
      });
    }

    // Alert Messenger
    function showFeedbackAlert(containerId, type, message) {
      var box = document.getElementById(containerId);
      if (!box) return;
      box.className = "auth-feedback-alert show " + type;
      box.innerHTML = (type === "success" ? '<i class="bi bi-check-circle-fill"></i> ' : '<i class="bi bi-exclamation-triangle-fill"></i> ') + message;
    }

    // Form Submissions
    var loginForm = document.getElementById("loginForm");
    if (loginForm) {
      loginForm.addEventListener("submit", function (e) {
        e.preventDefault();
        var submitBtn = this.querySelector(".auth-submit-btn");
        var originalText = submitBtn.innerHTML;

        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Signing in...';
        submitBtn.disabled = true;

        setTimeout(function () {
          submitBtn.innerHTML = originalText;
          submitBtn.disabled = false;
          showFeedbackAlert("loginFeedback", "success", "Welcome back! Redirecting to your account dashboard...");
          setTimeout(function () {
            window.location.href = "my-account.html";
          }, 1200);
        }, 1000);
      });
    }

    var otpLoginForm = document.getElementById("otpLoginForm");
    if (otpLoginForm) {
      otpLoginForm.addEventListener("submit", function (e) {
        e.preventDefault();
        var submitBtn = this.querySelector(".auth-submit-btn");
        var originalText = submitBtn.innerHTML;

        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Verifying OTP...';
        submitBtn.disabled = true;

        setTimeout(function () {
          submitBtn.innerHTML = originalText;
          submitBtn.disabled = false;
          showFeedbackAlert("loginFeedback", "success", "OTP verified! Redirecting to your account dashboard...");
          setTimeout(function () {
            window.location.href = "my-account.html";
          }, 1200);
        }, 1000);
      });
    }

    var signupForm = document.getElementById("signupForm");
    if (signupForm) {
      signupForm.addEventListener("submit", function (e) {
        e.preventDefault();
        var submitBtn = this.querySelector(".auth-submit-btn");
        var originalText = submitBtn.innerHTML;

        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Creating Account...';
        submitBtn.disabled = true;

        setTimeout(function () {
          submitBtn.innerHTML = originalText;
          submitBtn.disabled = false;
          showFeedbackAlert("signupFeedback", "success", "Account created successfully! Welcome to S4K Insider. Redirecting...");
          setTimeout(function () {
            window.location.href = "my-account.html";
          }, 1300);
        }, 1100);
      });
    }

    // Forgot Password Modal Submit
    var forgotForm = document.getElementById("forgotPasswordForm");
    if (forgotForm) {
      forgotForm.addEventListener("submit", function (e) {
        e.preventDefault();
        var resetAlert = document.getElementById("forgotPasswordAlert");
        var resetBtn = this.querySelector("button[type='submit']");
        var origText = resetBtn.innerHTML;

        resetBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Sending...';
        resetBtn.disabled = true;

        setTimeout(function () {
          resetBtn.innerHTML = origText;
          resetBtn.disabled = false;
          if (resetAlert) {
            resetAlert.className = "alert alert-success d-flex align-items-center gap-2";
            resetAlert.innerHTML = '<i class="bi bi-check-circle-fill"></i> Password reset link has been dispatched to your email/phone!';
            resetAlert.style.display = "flex";
          }
        }, 900);
      });
    }

    // Continuous Auto-Rotating Customer Reviews Carousel
    var quoteRotator = document.getElementById("authQuoteRotator");
    if (quoteRotator && !quoteRotator.dataset.rotatorInit) {
      quoteRotator.dataset.rotatorInit = "true";
      var slides = quoteRotator.querySelectorAll(".auth-quote-slide");
      var dots = quoteRotator.querySelectorAll(".auth-quote-dot");
      var currentIdx = 0;

      function showReviewSlide(index) {
        if (!slides.length) return;
        slides.forEach(function (slide, i) {
          slide.classList.toggle("active", i === index);
        });
        dots.forEach(function (dot, i) {
          dot.classList.toggle("active", i === index);
        });
        currentIdx = index;
      }

      function nextReviewSlide() {
        var nextIdx = (currentIdx + 1) % slides.length;
        showReviewSlide(nextIdx);
      }

      setInterval(nextReviewSlide, 2600);

      dots.forEach(function (dot) {
        dot.addEventListener("click", function () {
          var target = parseInt(this.dataset.quoteTo, 10);
          showReviewSlide(target);
        });
      });
    }
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", runAuth);
  } else {
    runAuth();
  }
})();