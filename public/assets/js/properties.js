/* ==========================================================================
   EstateEase - static property listings
   Fetches data/properties.json, renders cards, supports filtering & search.
   ========================================================================== */

(() => {
    'use strict';

    const DATA_URL       = (document.body.dataset.dataUrl) || 'data/properties.json';
    const FAV_KEY        = 'estateease:favourites';
    const featuredGrid   = document.getElementById('featured-grid');
    const listingsGrid   = document.getElementById('property-grid');
    const filterForm     = document.getElementById('filter-bar');
    const resultCountEl  = document.getElementById('result-count');

    let allProperties = [];
    let favourites    = new Set(loadFavourites());

    fetch(DATA_URL, { cache: 'no-cache' })
        .then((res) => {
            if (!res.ok) throw new Error('Network error: ' + res.status);
            return res.json();
        })
        .then((payload) => {
            allProperties = payload.properties || [];
            if (featuredGrid) renderFeatured();
            if (listingsGrid) {
                populateFilterOptions();
                seedFromQueryString();
                applyFiltersAndRender();
            }
        })
        .catch((err) => {
            const msg = '<div class="empty-state"><p>Could not load properties.</p><p class="muted">' + String(err) + '</p></div>';
            if (featuredGrid) featuredGrid.innerHTML = msg;
            if (listingsGrid) listingsGrid.innerHTML = msg;
        });

    // ---------- Rendering ----------
    function renderFeatured() {
        const items = allProperties.filter((p) => p.featured).slice(0, 6);
        featuredGrid.innerHTML = items.length
            ? items.map(cardHtml).join('')
            : '<div class="empty-state">No featured properties yet.</div>';
        wireFavouriteButtons(featuredGrid);
    }

    function renderListings(items) {
        if (!items.length) {
            listingsGrid.innerHTML = '<div class="empty-state"><p>No properties matched your filters.</p></div>';
        } else {
            listingsGrid.innerHTML = items.map(cardHtml).join('');
            wireFavouriteButtons(listingsGrid);
        }
        if (resultCountEl) {
            resultCountEl.textContent = items.length + ' ' + (items.length === 1 ? 'property' : 'properties') + ' found.';
        }
    }

    function cardHtml(p) {
        const fav      = favourites.has(p.id);
        const photo    = escapeHtml(p.photo);
        const name     = escapeHtml(p.name);
        const city     = escapeHtml(p.city || '');
        const type     = escapeHtml(p.type || '');
        const status   = escapeHtml(p.status || 'available');
        const statusCls = status === 'sold' ? 'badge badge--muted'
                        : status === 'pending' ? 'badge badge--warning'
                        : 'badge badge--success';
        const bed = Number(p.bedrooms) > 0 ? '<span>&#128719; ' + p.bedrooms + ' bd</span>' : '';
        const bth = Number(p.bathrooms) > 0 ? '<span>&#128703; ' + p.bathrooms + ' ba</span>' : '';
        return (
            '<article class="card">' +
                '<div class="card__media" style="position:relative;">' +
                    '<img src="' + photo + '" alt="' + name + '" loading="lazy">' +
                    '<div style="position:absolute;top:0.6rem;left:0.6rem;display:flex;gap:0.35rem;">' +
                        '<span class="' + statusCls + '">' + status + '</span>' +
                        (p.featured ? '<span class="badge">Featured</span>' : '') +
                    '</div>' +
                    '<button class="fav-btn" data-fav="' + p.id + '" aria-label="Save property" ' +
                        'style="position:absolute;top:0.6rem;right:0.6rem;background:rgba(255,255,255,0.95);border:none;border-radius:50%;width:36px;height:36px;cursor:pointer;font-size:1.1rem;box-shadow:0 1px 4px rgba(0,0,0,0.1);">' +
                        (fav ? '&#10084;&#65039;' : '&#9825;') +
                    '</button>' +
                '</div>' +
                '<div class="card__body">' +
                    '<h3 class="card__title">' + name + '</h3>' +
                    '<div class="card__meta">' +
                        '<span>&#128205; ' + city + '</span>' +
                        '<span>' + type + '</span>' +
                        '<span>' + p.area_sqm + ' m&#178;</span>' +
                        bed + bth +
                    '</div>' +
                    '<p class="muted" style="font-size:0.88rem;margin:0 0 0.75rem;">' + escapeHtml(p.description || '') + '</p>' +
                    '<div class="card__price">' + formatMoney(p.price) + '</div>' +
                '</div>' +
            '</article>'
        );
    }

    function wireFavouriteButtons(root) {
        root.querySelectorAll('[data-fav]').forEach((btn) => {
            btn.addEventListener('click', (evt) => {
                evt.preventDefault();
                evt.stopPropagation();
                const id = Number(btn.getAttribute('data-fav'));
                if (favourites.has(id)) {
                    favourites.delete(id);
                    btn.innerHTML = '&#9825;';
                } else {
                    favourites.add(id);
                    btn.innerHTML = '&#10084;&#65039;';
                }
                saveFavourites();
            });
        });
    }

    // ---------- Filters ----------
    function populateFilterOptions() {
        const cityEl = document.getElementById('f-city');
        const typeEl = document.getElementById('f-type');
        if (cityEl) {
            const cities = Array.from(new Set(allProperties.map((p) => p.city))).sort();
            cities.forEach((c) => cityEl.append(new Option(c, c)));
        }
        if (typeEl) {
            const types = Array.from(new Set(allProperties.map((p) => p.type))).sort();
            types.forEach((t) => typeEl.append(new Option(t, t)));
        }
    }

    function seedFromQueryString() {
        const params = new URLSearchParams(location.search);
        const qEl = document.getElementById('f-query');
        if (qEl && params.has('q')) qEl.value = params.get('q');
    }

    function applyFiltersAndRender() {
        const q    = (document.getElementById('f-query')?.value || '').toLowerCase().trim();
        const city = document.getElementById('f-city')?.value || '';
        const type = document.getElementById('f-type')?.value || '';
        const sort = document.getElementById('f-sort')?.value || 'recent';

        let items = allProperties.filter((p) => {
            if (city && p.city !== city) return false;
            if (type && p.type !== type) return false;
            if (q) {
                const blob = (p.name + ' ' + p.city + ' ' + p.type + ' ' + (p.description || '')).toLowerCase();
                if (!blob.includes(q)) return false;
            }
            return true;
        });

        items.sort((a, b) => {
            switch (sort) {
                case 'price_asc':  return a.price - b.price;
                case 'price_desc': return b.price - a.price;
                case 'area_desc':  return b.area_sqm - a.area_sqm;
                default:           return (b.listed_on || '').localeCompare(a.listed_on || '');
            }
        });

        renderListings(items);
    }

    if (filterForm) {
        filterForm.addEventListener('input', applyFiltersAndRender);
        filterForm.addEventListener('change', applyFiltersAndRender);
    }

    // ---------- Persistence ----------
    function loadFavourites() {
        try {
            return JSON.parse(localStorage.getItem(FAV_KEY) || '[]');
        } catch (e) {
            return [];
        }
    }

    function saveFavourites() {
        try {
            localStorage.setItem(FAV_KEY, JSON.stringify(Array.from(favourites)));
        } catch (e) { /* storage full or disabled */ }
    }

    // ---------- Helpers ----------
    function formatMoney(amount) {
        return 'PKR ' + Number(amount).toLocaleString('en-PK');
    }

    function escapeHtml(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
})();
