(function () {
    document.addEventListener("DOMContentLoaded", function () {
        const containers = document.querySelectorAll(".voc-top100-container");

        containers.forEach(container => {
            const grid = container.querySelector(".voc-top100-grid");
            const buscador = container.querySelector(".voc-top100-buscador");
            const btnClear = container.querySelector(".voc-top100-btn-clear");
            const sortBtns = container.querySelectorAll(".voc-top100-btn-sort");
            const resultsInfo = container.querySelector(".voc-top100-results-info");
            const resultsCount = container.querySelector(".voc-top100-results-count");

            // 1. Obtener datos de los candidatos
            const candidates = JSON.parse(grid.getAttribute("data-candidates") || "[]");
            const badgeUrl = grid.getAttribute("data-badge-url");

            function getAllCards() {
                return Array.from(grid.querySelectorAll(".voc-top100-card"));
            }

            // 2. Renderizar el Grid dinámicamente (Estructura de listado3.html)
            function renderGrid() {
                grid.innerHTML = "";
                candidates.forEach(c => {
                    const linkedinHTML = c.linkedin ? `
                        <div class="voc-top100-card-linkedin">
                            <a href="${c.linkedin}" target="_blank" title="LinkedIn">
                                <i class="fa fa-linkedin"></i>
                            </a>
                        </div>
                    ` : "";

                    const cardHTML = `
                        <div class="voc-top100-card" 
                             data-name="${c.nombre} ${c.apellidos}" 
                             data-lastname="${c.apellidos}" 
                             data-firstname="${c.nombre}">
                            <div class="voc-top100-image-box">
                                <img src="${badgeUrl}" alt="ABC Top 100" class="voc-top100-card-badge">
                                <img src="${c.foto}" alt="${c.nombre}" class="voc-top100-card-img" decoding="async" width="220" height="220">
                                ${linkedinHTML}
                            </div>
                            <h3 class="voc-top100-info-name">${c.nombre} ${c.apellidos}</h3>
                            <p class="voc-top100-info-role">${c.cargo}</p>
                            ${c.empresa ? `<p class="voc-top100-info-company">${c.empresa}</p>` : ""}
                        </div>
                    `;
                    grid.innerHTML += cardHTML;
                });

                // Inicializar Scroll Reveal después de renderizar
                initScrollReveal();
                injectAds();
            }

            // 3. Lógica de Scroll Reveal (IntersectionObserver)
            const observerOptions = {
                root: null,
                rootMargin: "0px 0px -50px 0px",
                threshold: 0.1,
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("visible");
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            function initScrollReveal() {
                const allCards = getAllCards();
                allCards.forEach((card) => {
                    if (!card.classList.contains("hidden") && !card.classList.contains("visible")) {
                        observer.observe(card);
                    }
                });
            }

            // Publicidad
            function injectAds() {
                const existingAds = grid.querySelectorAll(".voc-top100-ad-banner");
                existingAds.forEach(ad => ad.remove());

                const adsData = container.querySelector(".voc-top100-ads-data");
                if (!adsData) return;

                const freqStr = adsData.getAttribute("data-frequency");
                const freq = parseInt(freqStr, 10);
                if (isNaN(freq) || freq <= 0) return;

                const availableAds = Array.from(adsData.querySelectorAll('.voc-top100-ad-content'));
                let validAds = [];
                availableAds.forEach(ad => {
                    if (ad.innerHTML.trim() !== "") {
                        validAds.push(ad);
                    }
                });
                if (validAds.length === 0) return;

                const visibleCards = getAllCards().filter(card => !card.classList.contains('hidden'));
                if (visibleCards.length === 0) return;

                const style = getComputedStyle(grid);
                const gridTemplate = style.getPropertyValue('grid-template-columns');
                let columns = 1;
                if (gridTemplate && gridTemplate !== 'none') {
                    columns = gridTemplate.split(' ').length;
                }
                const interval = freq * columns;
                let adIndex = 0;

                for (let i = interval; i <= visibleCards.length; i += interval) {
                    const cardBeforeAd = visibleCards[i - 1];
                    const adBanner = document.createElement('div');
                    adBanner.className = 'voc-top100-ad-banner';
                    adBanner.style.gridColumn = '1 / -1';
                    adBanner.style.width = '100%';
                    adBanner.style.display = 'block';
                    adBanner.style.marginBottom = '20px';

                    const adContent = validAds[adIndex % validAds.length];
                    adBanner.innerHTML = adContent.innerHTML;
                    cardBeforeAd.parentNode.insertBefore(adBanner, cardBeforeAd.nextSibling);

                    adIndex++;
                }
            }

            // Ordenar A-Z por apellido (luego nombre) en la carga inicial
            candidates.sort((a, b) => {
                const lastA = (a.apellidos || "").toLowerCase();
                const lastB = (b.apellidos || "").toLowerCase();
                const firstA = (a.nombre || "").toLowerCase();
                const firstB = (b.nombre || "").toLowerCase();
                let res = lastA.localeCompare(lastB, 'es', { sensitivity: 'base' });
                if (res === 0) res = firstA.localeCompare(firstB, 'es', { sensitivity: 'base' });
                return res;
            });

            // Ejecutar el renderizado inicial
            renderGrid();
            container.classList.remove("is-loading");

            // 4. Lógica del Buscador
            function filtrar() {
                if (!buscador) return;
                let filtro = buscador.value.trim().toLowerCase();

                if (filtro.length > 0 && filtro.length < 3) {
                    filtro = "";
                }

                let visibleCount = 0;

                getAllCards().forEach((card) => {
                    const texto = card.innerText.toLowerCase();
                    if (texto.includes(filtro)) {
                        card.classList.remove("hidden");
                        // Al mostrarse, re-observamos para la animación
                        observer.observe(card);
                        visibleCount++;
                    } else {
                        card.classList.add("hidden");
                        card.classList.remove("visible"); // Reset animation
                    }
                });

                if (resultsInfo && resultsCount) {
                    if (filtro !== "") {
                        resultsCount.textContent = visibleCount;
                        resultsInfo.style.display = "block";
                    } else {
                        resultsInfo.style.display = "none";
                    }
                }

                injectAds();
            }

            if (buscador) {
                buscador.addEventListener("input", filtrar);
            }

            if (btnClear) {
                btnClear.addEventListener("click", () => {
                    buscador.value = "";
                    filtrar();
                });
            }

            // 5. Lógica de Ordenación
            sortBtns.forEach(btn => {
                btn.addEventListener("click", () => {
                    const order = btn.getAttribute("data-sort");

                    sortBtns.forEach(b => b.classList.remove("active"));
                    btn.classList.add("active");

                    const cards = getAllCards();
                    cards.sort((a, b) => {
                        const lastA = a.getAttribute("data-lastname").toLowerCase();
                        const lastB = b.getAttribute("data-lastname").toLowerCase();
                        const firstA = a.getAttribute("data-firstname").toLowerCase();
                        const firstB = b.getAttribute("data-firstname").toLowerCase();

                        let res = 0;
                        if (order === "asc") {
                            res = lastA.localeCompare(lastB);
                            if (res === 0) res = firstA.localeCompare(firstB);
                        } else {
                            res = lastB.localeCompare(lastA);
                            if (res === 0) res = firstB.localeCompare(firstA);
                        }
                        return res;
                    });

                    cards.forEach(card => grid.appendChild(card));

                    // Al reordenar, resetear visibilidad para que se vuelvan a animar al scrollear
                    cards.forEach(card => {
                        if (!card.classList.contains("hidden")) {
                            card.classList.remove("visible");
                            observer.observe(card);
                        }
                    });

                    injectAds();
                });
            });
        });
    });
})();
