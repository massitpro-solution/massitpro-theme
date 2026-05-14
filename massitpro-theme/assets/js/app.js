(function () {
	const header = document.querySelector('[data-site-header]');
	const mobileToggle = document.querySelector('[data-mobile-toggle]');
	const mobilePanel = document.querySelector('[data-mobile-panel]');

	const syncHeader = function () {
		if (! header) {
			return;
		}

		header.classList.toggle('is-scrolled', window.scrollY > 20);
	};

	const closeMobilePanel = function () {
		if (! mobileToggle || ! mobilePanel) {
			return;
		}

		mobileToggle.classList.remove('is-open');
		mobileToggle.setAttribute('aria-expanded', 'false');
		mobilePanel.classList.remove('is-open');
		mobilePanel.hidden = true;
		document.body.classList.remove('has-mobile-menu');
	};

	const openMobilePanel = function () {
		if (! mobileToggle || ! mobilePanel) {
			return;
		}

		mobileToggle.classList.add('is-open');
		mobileToggle.setAttribute('aria-expanded', 'true');
		mobilePanel.hidden = false;
		mobilePanel.classList.add('is-open');
		document.body.classList.add('has-mobile-menu');
	};

	if (header) {
		syncHeader();
		window.addEventListener('scroll', syncHeader, { passive: true });
	}

	if (mobileToggle && mobilePanel) {
		mobileToggle.addEventListener('click', function () {
			if (mobilePanel.classList.contains('is-open')) {
				closeMobilePanel();
				return;
			}

			openMobilePanel();
		});

		mobilePanel.querySelectorAll('a').forEach(function (link) {
			link.addEventListener('click', closeMobilePanel);
		});

		window.addEventListener('resize', function () {
			if (window.innerWidth >= 1024) {
				closeMobilePanel();
			}
		});
	}

	const revealItems = document.querySelectorAll('[data-reveal]');
	if ('IntersectionObserver' in window && revealItems.length) {
		const observer = new IntersectionObserver(
			function (entries, watcher) {
				entries.forEach(function (entry) {
					if (! entry.isIntersecting) {
						return;
					}

					entry.target.classList.add('is-visible');
					watcher.unobserve(entry.target);
				});
			},
			{
				threshold: 0.1,
				rootMargin: '0px 0px -10% 0px'
			}
		);

		revealItems.forEach(function (item) {
			item.classList.add('reveal-item');
			observer.observe(item);
		});
	} else {
		revealItems.forEach(function (item) {
			item.classList.add('reveal-item', 'is-visible');
		});
	}

	document.querySelectorAll('[data-accordion-trigger]').forEach(function (trigger) {
		trigger.addEventListener('click', function () {
			const item = trigger.closest('.accordion-item');
			const group = item && item.parentElement ? item.parentElement : null;
			const willOpen = item ? ! item.classList.contains('is-open') : false;

			if (! item) {
				return;
			}

			if (group) {
				group.querySelectorAll('.accordion-item').forEach(function (entry) {
					entry.classList.remove('is-open');
					const entryTrigger = entry.querySelector('[data-accordion-trigger]');
					if (entryTrigger) {
						entryTrigger.setAttribute('aria-expanded', 'false');
					}
				});
			}

			item.classList.toggle('is-open', willOpen);
			trigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
		});
	});

	document.querySelectorAll('[data-carousel]').forEach(function (carousel) {
		const key = carousel.getAttribute('data-carousel');
		const prev = key ? document.querySelector('[data-carousel-prev="' + key + '"]') : null;
		const next = key ? document.querySelector('[data-carousel-next="' + key + '"]') : null;

		if (! prev || ! next) {
			return;
		}

		const step = function () {
			return Math.max(carousel.clientWidth * 0.8, 320);
		};

		prev.addEventListener('click', function () {
			carousel.scrollBy({ left: step() * -1, behavior: 'smooth' });
		});

		next.addEventListener('click', function () {
			carousel.scrollBy({ left: step(), behavior: 'smooth' });
		});
	});

	document.querySelectorAll('[data-expanding-cities]').forEach(function (container) {
		var cards = Array.prototype.slice.call(container.querySelectorAll('[data-city-card]'));

		cards.forEach(function (card) {
			card.addEventListener('click', function (e) {
				if (card.classList.contains('is-expanded')) {
					return;
				}

				e.preventDefault();

				cards.forEach(function (c) {
					c.classList.remove('is-expanded');
				});

				card.classList.add('is-expanded');
			});

			var link = card.querySelector('.city-card__link');
			if (link) {
				link.addEventListener('click', function (e) {
					if (! card.classList.contains('is-expanded')) {
						e.preventDefault();
					}
				});
			}
		});
	});

	document.querySelectorAll('[data-services-toggle]').forEach(function (section) {
		var bizData = [];
		var resData = [];
		try { bizData = JSON.parse(section.getAttribute('data-services-biz') || '[]'); } catch (e) {}
		try { resData = JSON.parse(section.getAttribute('data-services-res') || '[]'); } catch (e) {}

		var tabs     = Array.prototype.slice.call(section.querySelectorAll('[data-services-tab]'));
		var panels   = Array.prototype.slice.call(section.querySelectorAll('[data-services-panel]'));
		var pill     = section.querySelector('[data-detail-pill]');
		var dTitle   = section.querySelector('[data-detail-title]');
		var dBody    = section.querySelector('[data-detail-body]');
		var dLink    = section.querySelector('[data-detail-link]');
		var dLinkLbl = section.querySelector('[data-detail-link-label]');
		var prevBtn  = section.querySelector('[data-services-detail-prev]');
		var nextBtn  = section.querySelector('[data-services-detail-next]');

		var activeTab   = 'business';
		var activeIndex = 0;

		function getData() {
			return activeTab === 'business' ? bizData : resData;
		}

		function getPillLabel() {
			return activeTab === 'business' ? 'Business Service' : 'Residential Service';
		}

		function syncStack() {
			var activePanel = section.querySelector('[data-services-panel="' + activeTab + '"]');
			if (!activePanel) return;
			var cards = Array.prototype.slice.call(activePanel.querySelectorAll('[data-card-index]'));
			var total = cards.length;
			cards.forEach(function (card) {
				var idx = parseInt(card.getAttribute('data-card-index'), 10);
				var offset = (idx - activeIndex + total) % total;
				if (offset <= 2) {
					card.setAttribute('data-stack-pos', String(offset));
				} else {
					card.removeAttribute('data-stack-pos');
				}
			});
		}

		function syncDetail() {
			var items = getData();
			var item  = items[activeIndex] || {};
			if (pill)     pill.textContent = getPillLabel();
			if (dTitle)   dTitle.textContent = item.title || '';
			if (dBody)    dBody.textContent = item.body || '';
			if (dLink) {
				if (item.link_url) {
					dLink.href = item.link_url;
					dLink.hidden = false;
					if (dLinkLbl) dLinkLbl.textContent = item.link_label || 'Learn More';
				} else {
					dLink.hidden = true;
				}
			}
			syncStack();
		}

		tabs.forEach(function (tab) {
			tab.addEventListener('click', function () {
				tabs.forEach(function (t) { t.classList.remove('is-active'); });
				tab.classList.add('is-active');

				var key = tab.getAttribute('data-services-tab');
				activeTab = key;
				activeIndex = 0;

				panels.forEach(function (p) {
					p.classList.toggle('is-active', p.getAttribute('data-services-panel') === key);
				});

				syncDetail();
			});
		});

		if (prevBtn) {
			prevBtn.addEventListener('click', function () {
				var items = getData();
				if (!items.length) return;
				activeIndex = (activeIndex - 1 + items.length) % items.length;
				syncDetail();
			});
		}

		if (nextBtn) {
			nextBtn.addEventListener('click', function () {
				var items = getData();
				if (!items.length) return;
				activeIndex = (activeIndex + 1) % items.length;
				syncDetail();
			});
		}

		syncStack();
	});

	const slider = document.querySelector('[data-testimonial-slider]');
	if (slider) {
		const slides = Array.prototype.slice.call(slider.querySelectorAll('[data-testimonial-slide]'));
		const dots = Array.prototype.slice.call(slider.querySelectorAll('[data-testimonial-dot]'));
		const prev = slider.querySelector('[data-testimonial-prev]');
		const next = slider.querySelector('[data-testimonial-next]');
		let index = 0;

		const syncSlides = function () {
			slides.forEach(function (slide, slideIndex) {
				slide.classList.toggle('is-active', slideIndex === index);
			});

			dots.forEach(function (dot, dotIndex) {
				dot.classList.toggle('is-active', dotIndex === index);
			});
		};

		if (prev) {
			prev.addEventListener('click', function () {
				index = (index - 1 + slides.length) % slides.length;
				syncSlides();
			});
		}

		if (next) {
			next.addEventListener('click', function () {
				index = (index + 1) % slides.length;
				syncSlides();
			});
		}

		dots.forEach(function (dot, dotIndex) {
			dot.addEventListener('click', function () {
				index = dotIndex;
				syncSlides();
			});
		});

		syncSlides();
	}

	var contactForm = document.querySelector('.massitpro-contact-form');
	if (contactForm) {
		var serviceRadios = contactForm.querySelectorAll('input[name="massitpro_contact[servicetype]"]');
		var businessFields = contactForm.querySelector('.massitpro-contact-business-fields');
		var businessServices = contactForm.querySelector('.massitpro-contact-business-services');
		var homeServices = contactForm.querySelector('.massitpro-contact-home-services');

		function mcfGetServiceType() {
			for (var i = 0; i < serviceRadios.length; i++) {
				if (serviceRadios[i].checked) return serviceRadios[i].value;
			}
			return '';
		}

		function mcfRefreshVisibility() {
			var val = mcfGetServiceType();
			if (businessFields) businessFields.style.display = val === 'Business' ? '' : 'none';
			if (businessServices) businessServices.style.display = val === 'Business' ? '' : 'none';
			if (homeServices) homeServices.style.display = val === 'Home' ? '' : 'none';

			if (val !== 'Business' && businessFields) {
				businessFields.querySelectorAll('input[type="checkbox"]').forEach(function (c) { c.checked = false; });
			}
			if (val !== 'Home' && homeServices) {
				homeServices.querySelectorAll('input[type="checkbox"]').forEach(function (c) { c.checked = false; });
			}
		}

		mcfRefreshVisibility();
		serviceRadios.forEach(function (r) {
			r.addEventListener('change', function () {
				mcfRefreshVisibility();
				mcfClearGroupError(r.closest('.massitpro-contact-field-group'));
			});
		});

		function mcfClearGroupError(group) {
			if (!group) return;
			group.classList.remove('massitpro-contact-error');
			var err = group.querySelector('.massitpro-contact-error-msg');
			if (err) err.parentNode.removeChild(err);
		}

		function mcfSetGroupError(group, msg) {
			if (!group) return;
			mcfClearGroupError(group);
			group.classList.add('massitpro-contact-error');
			var div = document.createElement('div');
			div.className = 'massitpro-contact-error-msg';
			div.textContent = msg;
			group.appendChild(div);
		}

		function mcfFindGroup(name) {
			var el = contactForm.querySelector('[name="massitpro_contact[' + name + ']"]');
			if (!el) el = contactForm.querySelector('[name="massitpro_contact[' + name + '][]"]');
			if (el) return el.closest('.massitpro-contact-field-group');
			return null;
		}

		function mcfIsVisible(el) {
			if (!el) return false;
			return el.offsetWidth > 0 || el.offsetHeight > 0;
		}

		function mcfValidate() {
			var ok = true;
			var firstBad = null;
			contactForm.querySelectorAll('.massitpro-contact-field-group').forEach(function (g) { mcfClearGroupError(g); });

			var required = ['firstname', 'lastname', 'email', 'phone', 'zip', 'message'];
			for (var i = 0; i < required.length; i++) {
				var grp = mcfFindGroup(required[i]);
				if (!grp || !mcfIsVisible(grp)) continue;
				var input = grp.querySelector('.massitpro-contact-field');
				if (input && !input.value.trim()) {
					ok = false;
					mcfSetGroupError(grp, 'This field is required.');
					if (!firstBad) firstBad = grp;
				}
			}

			var stGroup = mcfFindGroup('servicetype');
			if (stGroup && mcfIsVisible(stGroup) && !mcfGetServiceType()) {
				ok = false;
				mcfSetGroupError(stGroup, 'Please choose Home or Business.');
				if (!firstBad) firstBad = stGroup;
			}

			var mode = mcfGetServiceType();
			if (mode === 'Business') {
				var compGroup = mcfFindGroup('company');
				if (compGroup && mcfIsVisible(compGroup)) {
					var compInput = compGroup.querySelector('.massitpro-contact-field');
					if (compInput && !compInput.value.trim()) {
						ok = false;
						mcfSetGroupError(compGroup, 'Company name is required.');
						if (!firstBad) firstBad = compGroup;
					}
				}
				var empGroup = mcfFindGroup('employees');
				if (empGroup && mcfIsVisible(empGroup)) {
					var empInput = empGroup.querySelector('.massitpro-contact-field');
					if (empInput && !empInput.value) {
						ok = false;
						mcfSetGroupError(empGroup, 'Employee count is required.');
						if (!firstBad) firstBad = empGroup;
					}
				}
				var bizSvcGroup = contactForm.querySelector('.massitpro-contact-business-services .massitpro-contact-field-group');
				if (bizSvcGroup && mcfIsVisible(bizSvcGroup)) {
					var anyBiz = contactForm.querySelectorAll('.massitpro-contact-business-services input[type="checkbox"]:checked');
					if (!anyBiz.length) {
						ok = false;
						mcfSetGroupError(bizSvcGroup, 'Select at least one service.');
						if (!firstBad) firstBad = bizSvcGroup;
					}
				}
			} else if (mode === 'Home') {
				var homeSvcGroup = contactForm.querySelector('.massitpro-contact-home-services .massitpro-contact-field-group');
				if (homeSvcGroup && mcfIsVisible(homeSvcGroup)) {
					var anyHome = contactForm.querySelectorAll('.massitpro-contact-home-services input[type="checkbox"]:checked');
					if (!anyHome.length) {
						ok = false;
						mcfSetGroupError(homeSvcGroup, 'Select at least one service.');
						if (!firstBad) firstBad = homeSvcGroup;
					}
				}
			}

			var agreeGroup = contactForm.querySelector('.massitpro-contact-field-group--accept');
			if (agreeGroup) {
				var agreeInput = agreeGroup.querySelector('input[type="checkbox"]');
				if (agreeInput && !agreeInput.checked) {
					ok = false;
					mcfSetGroupError(agreeGroup, 'You must agree before submitting.');
					if (!firstBad) firstBad = agreeGroup;
				}
			}

			if (firstBad && firstBad.scrollIntoView) {
				firstBad.scrollIntoView({ behavior: 'smooth', block: 'center' });
			}

			return ok;
		}

		contactForm.addEventListener('input', function (e) {
			var grp = e.target.closest('.massitpro-contact-field-group');
			if (grp) mcfClearGroupError(grp);
		});

		contactForm.addEventListener('change', function (e) {
			var grp = e.target.closest('.massitpro-contact-field-group');
			if (grp) mcfClearGroupError(grp);
		});

		contactForm.addEventListener('submit', function (e) {
			e.preventDefault();

			if (!mcfValidate()) return;

			var statusEl = contactForm.querySelector('.massitpro-contact-form__status');
			var submitBtn = contactForm.querySelector('.massitpro-contact-form__submit');
			var ajaxUrl = (typeof massitproContact !== 'undefined' && massitproContact.ajaxUrl) ? massitproContact.ajaxUrl : '';

			if (!ajaxUrl) {
				if (statusEl) {
					statusEl.className = 'massitpro-contact-form__status massitpro-contact-form__status--error';
					statusEl.textContent = 'Form configuration error. Please contact us directly.';
				}
				return;
			}

			var originalLabel = '';
			if (submitBtn) {
				originalLabel = submitBtn.querySelector('span').textContent || 'Send Message';
				submitBtn.disabled = true;
				submitBtn.querySelector('span').textContent = 'Sending...';
			}

			var formData = new FormData(contactForm);

			fetch(ajaxUrl, {
				method: 'POST',
				body: formData,
				credentials: 'same-origin'
			})
			.then(function (res) { return res.json(); })
			.then(function (json) {
				if (statusEl) {
					if (json.success) {
						statusEl.className = 'massitpro-contact-form__status massitpro-contact-form__status--success';
						statusEl.textContent = json.data && json.data.message ? json.data.message : 'Thank you!';
						contactForm.reset();
						mcfRefreshVisibility();
					} else {
						statusEl.className = 'massitpro-contact-form__status massitpro-contact-form__status--error';
						statusEl.textContent = json.data && json.data.message ? json.data.message : 'An error occurred.';

						if (json.data && json.data.field_errors) {
							var fieldErrors = json.data.field_errors;
							for (var fieldName in fieldErrors) {
								if (!fieldErrors.hasOwnProperty(fieldName)) continue;
								var grp = mcfFindGroup(fieldName);
								if (grp) mcfSetGroupError(grp, fieldErrors[fieldName]);
							}
						}
					}
				}
			})
			.catch(function () {
				if (statusEl) {
					statusEl.className = 'massitpro-contact-form__status massitpro-contact-form__status--error';
					statusEl.textContent = 'Network error. Please try again.';
				}
			})
			.finally(function () {
				if (submitBtn) {
					submitBtn.disabled = false;
					submitBtn.querySelector('span').textContent = originalLabel;
				}
			});
		});
	}

	var faqSection = document.querySelector('[data-mitp-faq-section]');
	if (faqSection) {
		var faqTopicNav = faqSection.querySelector('[data-mitp-faq-topic-nav]');
		var faqGroups = faqSection.querySelectorAll('[data-mitp-faq-category]');

		if (faqTopicNav && faqGroups.length) {
			faqTopicNav.addEventListener('click', function (e) {
				var btn = e.target.closest('[data-mitp-faq-filter-button]');
				if (!btn) return;

				var topic = btn.getAttribute('data-mitp-faq-filter-button');

				faqTopicNav.querySelectorAll('.faq-topic-nav__btn').forEach(function (b) {
					b.classList.toggle('is-active', b === btn);
				});

				faqGroups.forEach(function (group) {
					if (topic === 'all' || group.getAttribute('data-mitp-faq-category') === topic) {
						group.removeAttribute('data-mitp-faq-hidden');
					} else {
						group.setAttribute('data-mitp-faq-hidden', '');
					}
				});
			});
		}
	}

	var testimonialsSection = document.querySelector('[data-mitp-testimonials-section]');
	if (testimonialsSection) {
		var mitpFilterBtns = testimonialsSection.querySelectorAll('[data-mitp-testimonials-filter-button]');
		var mitpCards = testimonialsSection.querySelectorAll('[data-mitp-testimonials-card]');
		var mitpPagination = testimonialsSection.querySelector('[data-mitp-testimonials-pagination]');
		var mitpPerPage = 9;
		var mitpCurrentPage = 1;
		var mitpActiveFilter = 'all';

		function mitpGetVisibleCards() {
			var visible = [];
			mitpCards.forEach(function (card) {
				var slug = card.getAttribute('data-mitp-testimonials-industry') || '';
				if (mitpActiveFilter === 'all' || slug === mitpActiveFilter) {
					visible.push(card);
				}
			});
			return visible;
		}

		function mitpPaginate() {
			var visible = mitpGetVisibleCards();
			var totalPages = Math.max(1, Math.ceil(visible.length / mitpPerPage));
			if (mitpCurrentPage > totalPages) mitpCurrentPage = totalPages;

			mitpCards.forEach(function (card) {
				card.setAttribute('data-mitp-testimonials-hidden', '');
			});

			var start = (mitpCurrentPage - 1) * mitpPerPage;
			var end = start + mitpPerPage;
			visible.forEach(function (card, i) {
				if (i >= start && i < end) {
					card.removeAttribute('data-mitp-testimonials-hidden');
				}
			});

			if (mitpPagination) {
				var html = '';
				if (totalPages > 1) {
					html += '<button class="pagination-btn' + (mitpCurrentPage === 1 ? ' is-disabled' : '') + '" data-mitp-testimonials-page="prev" ' + (mitpCurrentPage === 1 ? 'disabled' : '') + '>&larr; Prev</button>';
					for (var p = 1; p <= totalPages; p++) {
						html += '<button class="pagination-btn' + (p === mitpCurrentPage ? ' is-active' : '') + '" data-mitp-testimonials-page="' + p + '">' + p + '</button>';
					}
					html += '<button class="pagination-btn' + (mitpCurrentPage === totalPages ? ' is-disabled' : '') + '" data-mitp-testimonials-page="next" ' + (mitpCurrentPage === totalPages ? 'disabled' : '') + '>&rarr; Next</button>';
				}
				mitpPagination.innerHTML = html;
			}
		}

		if (mitpFilterBtns.length && mitpCards.length) {
			testimonialsSection.addEventListener('click', function (e) {
				var btn = e.target.closest('[data-mitp-testimonials-filter-button]');
				if (!btn) return;

				mitpActiveFilter = btn.getAttribute('data-mitp-testimonials-filter-button');
				mitpCurrentPage = 1;

				mitpFilterBtns.forEach(function (b) {
					b.classList.toggle('is-active', b === btn);
				});

				mitpPaginate();
			});
		}

		if (mitpPagination) {
			mitpPagination.addEventListener('click', function (e) {
				var btn = e.target.closest('[data-mitp-testimonials-page]');
				if (!btn || btn.disabled) return;

				var val = btn.getAttribute('data-mitp-testimonials-page');
				if (val === 'prev') {
					mitpCurrentPage = Math.max(1, mitpCurrentPage - 1);
				} else if (val === 'next') {
					mitpCurrentPage++;
				} else {
					mitpCurrentPage = parseInt(val, 10);
				}
				mitpPaginate();

				testimonialsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
			});
		}

		testimonialsSection.addEventListener('click', function (e) {
			var btn = e.target.closest('[data-mitp-testimonials-read-more]');
			if (!btn) return;

			var quote = btn.previousElementSibling;
			if (quote && quote.classList.contains('testimonial-quote--truncated')) {
				quote.classList.toggle('is-expanded');
				btn.textContent = quote.classList.contains('is-expanded') ? 'Show less' : 'Read more';
			}
		});

		mitpPaginate();
	}

	var projSection = document.querySelector('[data-mitp-projects-section]');
	if (projSection) {
		var projFilterBtns = projSection.querySelectorAll('[data-mitp-projects-filter-button]');
		var projCards = projSection.querySelectorAll('[data-mitp-projects-card]');
		var projPagination = projSection.querySelector('[data-mitp-projects-pagination]');
		var projPerPage = 6;
		var projCurrentPage = 1;
		var projActiveFilter = 'all';

		function projGetVisible() {
			var visible = [];
			projCards.forEach(function (card) {
				var cat = card.getAttribute('data-mitp-projects-category') || '';
				if (projActiveFilter === 'all' || cat === projActiveFilter) {
					visible.push(card);
				}
			});
			return visible;
		}

		function projPaginate() {
			var visible = projGetVisible();
			var totalPages = Math.max(1, Math.ceil(visible.length / projPerPage));
			if (projCurrentPage > totalPages) projCurrentPage = totalPages;

			projCards.forEach(function (card) {
				card.setAttribute('data-mitp-projects-hidden', '');
			});

			var start = (projCurrentPage - 1) * projPerPage;
			var end = start + projPerPage;
			visible.forEach(function (card, i) {
				if (i >= start && i < end) {
					card.removeAttribute('data-mitp-projects-hidden');
				}
			});

			if (projPagination) {
				var html = '';
				if (totalPages > 1) {
					html += '<button class="pagination-btn' + (projCurrentPage === 1 ? ' is-disabled' : '') + '" data-mitp-projects-page="prev" ' + (projCurrentPage === 1 ? 'disabled' : '') + '>&larr; Prev</button>';
					for (var p = 1; p <= totalPages; p++) {
						html += '<button class="pagination-btn' + (p === projCurrentPage ? ' is-active' : '') + '" data-mitp-projects-page="' + p + '">' + p + '</button>';
					}
					html += '<button class="pagination-btn' + (projCurrentPage === totalPages ? ' is-disabled' : '') + '" data-mitp-projects-page="next" ' + (projCurrentPage === totalPages ? 'disabled' : '') + '>&rarr; Next</button>';
				}
				projPagination.innerHTML = html;
			}
		}

		if (projFilterBtns.length && projCards.length) {
			projSection.addEventListener('click', function (e) {
				var btn = e.target.closest('[data-mitp-projects-filter-button]');
				if (!btn) return;

				projActiveFilter = btn.getAttribute('data-mitp-projects-filter-button');
				projCurrentPage = 1;

				projFilterBtns.forEach(function (b) {
					b.classList.toggle('is-active', b === btn);
				});

				projPaginate();
			});
		}

		if (projPagination) {
			projPagination.addEventListener('click', function (e) {
				var btn = e.target.closest('[data-mitp-projects-page]');
				if (!btn || btn.disabled) return;

				var val = btn.getAttribute('data-mitp-projects-page');
				if (val === 'prev') {
					projCurrentPage = Math.max(1, projCurrentPage - 1);
				} else if (val === 'next') {
					projCurrentPage++;
				} else {
					projCurrentPage = parseInt(val, 10);
				}
				projPaginate();
				projSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
			});
		}

		projPaginate();

		projSection.addEventListener('click', function (e) {
			var prev = e.target.closest('[data-mitp-slide-prev]');
			var next = e.target.closest('[data-mitp-slide-next]');
			if (!prev && !next) return;

			var slideshow = (prev || next).closest('[data-mitp-slideshow]');
			if (!slideshow) return;

			var slides = slideshow.querySelectorAll('.project-slideshow__slide');
			var current = -1;
			slides.forEach(function (s, i) {
				if (s.classList.contains('is-active')) current = i;
			});

			if (current < 0) return;

			var target;
			if (prev) {
				target = current > 0 ? current - 1 : slides.length - 1;
			} else {
				target = current < slides.length - 1 ? current + 1 : 0;
			}

			slides[current].classList.remove('is-active');
			slides[target].classList.add('is-active');
		});
	}

})();
