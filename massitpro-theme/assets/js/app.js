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

	const filterButtons = document.querySelectorAll('.filter-btn');
	if (filterButtons.length) {
		filterButtons.forEach(function (button) {
			button.addEventListener('click', function () {
				const filter = button.getAttribute('data-filter') || 'all';

				filterButtons.forEach(function (entry) {
					entry.classList.toggle('is-active', entry === button);
				});

				document.querySelectorAll('.testimonial-card').forEach(function (card) {
					const industry = card.getAttribute('data-industry') || '';

					if ('all' === filter || industry === filter) {
						card.hidden = false;
						card.style.display = '';
					} else {
						card.hidden = true;
						card.style.display = 'none';
					}
				});
			});
		});
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

	var faqSection = document.querySelector('[data-faq-section]');
	if (faqSection) {
		var topicNav = faqSection.querySelector('[data-faq-topic-nav]');
		var faqGroups = faqSection.querySelectorAll('[data-faq-category]');

		if (topicNav && faqGroups.length) {
			topicNav.addEventListener('click', function (e) {
				var btn = e.target.closest('[data-faq-topic]');
				if (!btn) return;

				var topic = btn.getAttribute('data-faq-topic');

				topicNav.querySelectorAll('.faq-topic-nav__btn').forEach(function (b) {
					b.classList.toggle('is-active', b === btn);
				});

				faqGroups.forEach(function (group) {
					if (topic === 'all' || group.getAttribute('data-faq-category') === topic) {
						group.removeAttribute('data-faq-hidden');
					} else {
						group.setAttribute('data-faq-hidden', '');
					}
				});
			});
		}
	}

})();
